<?php

class Bible
{
   public $utils = null;
   public function __construct() 
   {
      $this->utils = new ScriptureUtils();
   }
   private static $_instance = null;
   private $_search_string_delimiter = ';';
   private $_meta = null;

   public $search_facets = [];

   
   /*
   *  
   */
   public function previous($starting_verse, $limit=10)
   {
      $verses = App::getInstance()->db->query(
         'SELECT * FROM t_kjv WHERE id < ? ORDER BY id DESC LIMIT ?',
         array(
            $starting_verse,
            $limit,
         ))->fetchAll();

      return $verses;
   }

   
   /*
   *  
   */
  public function next($starting_verse, $limit=10)
  {
      $verses = App::getInstance()->db->query(
         'SELECT * FROM t_kjv WHERE id > ? ORDER BY id ASC LIMIT ?',
         array(
            $starting_verse,
            $limit,
         ))->fetchAll();

      return $verses;
   }

   public function friendlyReference($vid)
   {
      if ($this->utils->isValidVerseId($vid))
      {
         return $this->utils->friendlyVerseId($vid);
      }
      else 
      {
         return $vid;
      }
   }
   
   /*
   *  Under Construction !
   */
   public function parseSearchString($search_string)
   {
      /*
      * Example of a complicated search string
      * 
      *  John 14:1-3 ; 1 Corinthians 15:1-53 ; house ; 1 Thess 4:13-18 ; heart ; 1 Cor 1622 ; Jesus ; 2 Thess 2:1,3,5-9,7,12 ; 1 John 2:28-3:2 ; Jude 21
      */
      $search_facets = explode($this->_search_string_delimiter, $search_string); // It would be nice if we could more intelligently notice a scripture reference and not just have to define the boundaries of a facet by using a ";" delimiter
      $references = array();
      $keywords = array();
      foreach ($search_facets as $search_facet)
      {
         // Trying to detect if this portion is a scripture reference
         preg_match('/([1|2|3]?([i|I]+)?(\s?)\w+(\s+?))((\d+)?(,?)(\s?)(\d+))+(:?)((\d+)?([\-–]\d+)?(,(\s?)\d+[\-–]\d+)?)?/', $search_facet, $matches);
         
         if (ScriptureUtils::isValidVerseId($search_facet) || (!empty($matches)))
         {
            if (ScriptureUtils::isValidVerseId($search_facet))
            {
               //debug($this->utils->friendlyVerseId($search_facet));
               $references[] = $this->utils->friendlyVerseId($search_facet);
            }
            else 
            {
               $references[] = $search_facet;
            }
         }
         else
         {
            $keywords[] = $search_facet;
         }
      }
      
      $references = array_map('trim', array_filter($references));
      $keywords = array_map('trim', array_filter($keywords));
      $search_facets = array_map('trim', array_merge($keywords, $references));
      
      $this->search_facets = array(
         'references' => $references, // array('John 14:1-3', '1 Corinthians 15:1-53', '1 Thess 4:13-1', '2 Thess 2:1,3', '1 John 2:28-3:2', 'Jude 21')
         'keywords' => $keywords, // array('house', 'heart', 'Jesus')
         'combined' => $search_facets, // array('John 14:1-3', '1 Corinthians 15:1-53', 'house', '1 Thess 4:13-18', 'heart', '1 Cor 1622', 'Jesus', '2 Thess 2:1,3', '1 John 2:28-3:2', 'Jude 21')
         'search_string' => $search_string, // John 14:1-3 ; 1 Corinthians 15:1-53 ; house ; 1 Thess 4:13-18 ; heart ; 1 Cor 1622 ; Jesus ; 2 Thess 2:1,3 ; 1 John 2:28-3:2 ; Jude 21
      );
   }


   /*
   *  
   */
   public function search($search_string)
   {
      if (!empty($search_string))
      {
         $db = App::getInstance()->db;
         $base_url = config('base_url');

         // Process
         $this->parseSearchString($search_string);
         $search_results = array();

         foreach ($this->search_facets['combined'] as $facet) 
         {
            $facet = trim($facet);

            // Query scripture references first
            if (in_array($facet, $this->search_facets['references']))
            {
               /*
               *  Query Scripture References
               */
               
               $ret = new bible_to_sql($facet);
               $sql = $ret->sql();
               if (empty($sql))
               {
                  error($facet);
                  error($ret->sql());
               }

               $sqlquery = "SELECT * FROM kjv.t_kjv WHERE " . $sql;
               //debug($sqlquery);
               //echo "sql query: " . $sqlquery . "<br />"; die();

               $result = $db->query($sqlquery);

               //debug($row);

               if ($result->numRows()) 
               {
                  $rows = $result->fetchAll();

                  if ($this->utils->isValidVerseId($facet))
                  {
                     $reference_friendly = $this->utils->friendlyVerseId($facet);
                  }
                  else 
                  {
                     $reference_friendly = $facet;
                  }

                  $this->setMeta(array(
                     'title' => $reference_friendly,
                     'description' => $row['t'] . ' ...more',
                     'type' => 'article',
                     'url' => $base_url,
                  ));

                  $search_results[$facet] = array(
                     'type' => 'verse',
                     'reference' => $facet,
                     'reference_friendly' => $reference_friendly,
                     'data' => array(),
                  );
                  foreach ($rows as $row)
                  {
                     $passage = $this->utils->getPassage($row);
                     $book_chapter = explode(':', $passage);
                     $book_chapter = $row[1];
                     $search_results[$facet]['data'][$book_chapter][] = $row;
                  }
               } 
               
            }
            else 
            {
               /*
               *  Keyword Searches
               */
               
               $words = explode(' ', $facet);
               $first = true;
               foreach ($words as $word)
               {
                  if ($first) 
                  {
                     $like = 't LIKE ' . '"%%' . $word . '%%"';
                     $first = false;
                  }
                  else
                  {
                     $like .= ' AND t LIKE ' . '"%%' . $word . '%%"';
                  }
               }
               $result = $db->query("SELECT * FROM kjv.t_kjv WHERE $like");
               $count = $result->numRows();
            
               if ($count == 1) 
               {
                  // Single verse result
                  $row = $result->fetchArray();
                  $passage = $this->utils->getPassage($row);
                  $book_chapter = explode(':', $passage);
                  $book_chapter = $book_chapter[0];
                  $this->setMeta(array(
                     'title' => $search_string,
                     'description' => $row['t'],
                     'type' => 'article',
                     'url' => $base_url,
                     'image' => $base_url.'/images/588984-600.jpg',
                  ));
                  $synonyms = getSynonyms($facet);
                  $search_results[$facet] = array(
                     'type' => 'keyword',
                     'keyword' => $facet,
                     'synonyms' => $synonyms,
                     'reference' => $passage,
                     'reference_friendly' => $passage,
                     'data' => array($book_chapter => array($row)),
                  );
                  //debug($search_results);
                  //debug($row);
               }
               else if ($count >= 2) 
               {
                  // Multiple results
                  $rows = $result->fetchAll();
                  
                  $data = array();
                  foreach ($rows as $row)
                  {
                     $passage = $this->utils->getPassage($row);
                     $book_chapter = explode(':', $passage);
                     $book_chapter = $book_chapter[0];
                     if (empty($this->_meta))
                     {
                        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        $this->setMeta(array(
                           'title' => $search_string,
                           'description' => $row['t'],
                           'type' => 'article',
                           'url' => $escaped_url,
                        ));                     
                     }
                     $row['reference_friendly'] = $passage;
                     $data[$book_chapter][] = $row;
                  }
                  
                  $synonyms = getSynonyms($facet);
                  $search_results[$facet] = array(
                     'type' => 'keyword',
                     'keyword' => $facet,
                     'synonyms' => $synonyms,
                     'data' => $data,
                  );
               }
               else 
               {
                  // No results
                  $synonyms = getSynonyms($facet);
                  $search_results[$facet] = array(
                     'type' => 'keyword',
                     'keyword' => $facet,
                     'synonyms' => $synonyms,
                     'data' => array(),
                  );

               }
            }
         }
         

         return $search_results;
      }
      
      if ((!empty($search_results)) && (get_var('d')))
      {
         header('Content-Type: application/json');
         echo json_encode($search_results);
         die();
      }
   }


   private function setMeta ($data)
   {
      if (empty($this->_meta))
      {
         // Memes integration
         $meme = get_var('m', '');

         if ((!empty($meme)) && (file_exists(getcwd().'/images/memes/'.$meme)))
         {
            $meme = '/images/memes/' . $meme;
            list($width, $height, $type, $attr) = getimagesize(getcwd().$meme);
            $meme = config('base_url') . $meme;
         }
         else
         {
            $meme = config('base_url') . '/images/eh5zp6hvg__1280.jpg';
            list($width, $height, $type, $attr) = getimagesize(getcwd().'/images/eh5zp6hvg__1280.jpg');
         }

         $meta = array(
            'title' => 'Bible Search',
            'description' => 'Study to shew thyself approved unto God 🔎',
            'type' => 'website',
            'url' => config('base_url'),
            'image' => $meme,
            'image_width' => $width,
            'image_height' => $height,
         );
         
         $this->_meta = array_merge($meta, $data); 
      }
      else
      {
         //error('Attempted to override meta data');
      }

      return $this->_meta;
   }


   public function getMeta()
   {
      return $this->_meta;
   }

   
   /*
   *  Return Singleton instance
   */
   public static function getInstance()
   {
     if (self::$_instance == null)
     {
       self::$_instance = new Bible();
     }
  
     return self::$_instance;
   }

}

/**
 * This needs to return the id range for the book from the 
 */
function referenceToCompleteBook(&$reference)
{
   return $reference;
}


/*
* Turns Gen 1:1 in 01001001
*/
function convertToNumber($reference = '') 
{
   $string = preg_replace('/\s(\S*)$/', '.$1', trim($reference)); //trim end for sanitization.
   $split = explode(".", $string);
   $book_number = bible_to_sql::addZeros(convertToBookNumber($split[0]),2);
   $separatedVerse = explode(":",$split[1]);
   $chapter = bible_to_sql::addZeros($separatedVerse[0],3);
   $verse = bible_to_sql::addZeros($separatedVerse[1],3);
   
   return $book_number . $chapter . $verse;
}

/*
* Bible to SQL Stuff
*/
function convertToBookNumber($book = NULL) 
{
   $db = App::getInstance()->db;	
   
   $name = $db->query("SELECT B from kjv.key_abbreviations_english WHERE A=?", $book)->fetchArray();

   return $name['B'];
}

function convertToBook($number = NULL) 
{
	$db = App::getInstance()->db;	
	$query = "SELECT n from kjv.key_english WHERE b=?";
	$db = App::getInstance()->db;	
   $book = $db->query(
      "SELECT n from kjv.key_english WHERE b=?",
      $number
   )->fetchArray();

   return $book[0];
}


//JOSHUA 1:8-10 to 0601008-0601010

//return book number
class bible_to_sql {
	
   public $book = null;
   public $utils = null;
	protected $bookName = null;
	protected $chapter = null;
	protected $chapterHuman = null;
	protected $verse = 001;
	protected $endverse = 999;
   protected $range = FALSE;
	
   public function __construct($string = NULL) 
   {
      $this->utils = new ScriptureUtils();
		//places a . between book name and reference. 
		//i.e. changes "Song of Solomon 9:6" to "Song of Solomon.9:6"
		$string = preg_replace('/\s(\S*)$/', '.$1', trim($string)); //trim end for sanitization.
				
		//split
      $separatedArray = explode(".",$string);
      if (ScriptureUtils::isValidVerseId($separatedArray[0]))
      {
         $vid = $separatedArray[0];

         $this->book = substr($vid, 0, 2);
         
         $this->bookName = convertToBook($this->book);
         
         //split chapter and verse
         $this->chapter = substr($vid, 2, 3);
         $this->chapterHuman = intval($this->chapter);
         
         $this->constructVerseQuery($vid);
      }
      else
      {
         $this->book = $this->addZeros(convertToBookNumber($separatedArray[0]),2);
         $this->bookName = convertToBook($this->book);
         
         //split chapter and verse
         $separatedVerse = explode(":",$separatedArray[1]);
         $this->chapterHuman = $separatedVerse[0];
         $this->chapter = $this->addZeros($separatedVerse[0],3);
         
         $this->constructVerseQuery($separatedArray[1]);
      }
      //debug($this->sql());
   }
   
   function constructVerseQuery($string)
   {
      $query = '';
      if ($this->utils->isValidVerseId($string))
      {
         $this->query = ' id = ' . $string;
         return;
      }
      
      //    62002005 - 62002009
      //  1 John 2:5 - 9          ,        12         ,          15          ,        28        -        3:2


      $chapter_groups = explode(':', $string);
      $references = array();

      // Multiple Chapter References in one facet
      if (count($chapter_groups) >> 2)
      {
         $chapter = $chapter_groups[0];
         // The first element in $chapter_groups array should be a chapter identifier
         foreach ($chapter_groups as $group)
         {
            if (!empty($references[$chapter]))
            {
               // maintenance on previous loop (removing chapter identifier that belongs to the next group)
               $chapter = array_pop($references[$chapter]);
            }

            $sets = explode(',', $group);
            foreach ($sets as $set)
            {
               // if this set specifies a range: 5-9
               if (strpos($set, '-'))
               {
               }
               else 
               {
               }
               $references[$chapter][] = $set;
            }
         }
      }
      elseif (count($chapter_groups) == 2)
      {
         $references[$chapter_groups[0]] = explode(',', $chapter_groups[1]);
      }
      else 
      {
         // if (!$verses) ? $whole_chapter = true; //?
         $start = $this->book . $this->addZeros($string, 3) . '000';
         $end = $this->book . $this->addZeros($string+1, 3) . '000';
         $this->query = ' id BETWEEN ' . $start . ' AND ' . $end;

         return;
      }

      foreach ($references as $chapter => $verses)
      {
         foreach ($verses as $verse)
         {
            $op = (!empty($query))
            ? ' OR '
            : '';
            //determine if single or range
            /*
            */
            if (strpos($verse, '-') !== FALSE) 
            {
               $split = explode("-", $verse);
               $verse = $this->book . $this->addZeros($chapter, 3) . $this->addZeros($split[0], 3);
               if ($split[1]) 
               {
                  $endverse = $this->book . $this->addZeros($chapter, 3) . $this->addZeros($split[1],3);
               }
               $query .= $op . 'id BETWEEN ' . $verse . ' AND ' . $endverse;
            }
            else 
            {
               $verse = $this->book . $this->addZeros($chapter, 3) . $this->addZeros($verse,3);
               $query .= $op . 'id = ' . $verse;
            }
         }
      }

      $this->query = $query;
   }
		
	public function addZeros($input,$max) {
		
		$len = strlen($input);
		
		for ($len; $len < $max; $len++) {
			$input = "0".$input;
		}
		
		return $input;
		
   }
   
	/*
	public function sql() {
		if ($this->range) {
			return "id BETWEEN ".$this->book.$this->chapter.$this->verse." and ".$this->book.$this->chapter.$this->endverse." ";
		} else {
			return "id='".$this->book.$this->chapter.$this->verse."'";
		}
   }
   */
   public function sql() {
      return $this->query;
   }

	public function getBook() {
		return $this->bookName;
	}
	
	public function getChapter() {
		return $this->chapterHuman;
	}
	
	public function getVerse() {
		return $this->verse;
	}
	
	public function getEndVerse() {
		return $this->endverse;
	}
	
	public function getRange() {
		return $this->range;
	}

}

class ScriptureUtils {

   private $booksAbbreviated = array(
      "Gen", "Exo", "Lev", "Num", "Deu", "Jos", "Jud", "Rut", "1 Sam", "2 Sam", "1 Kin", 
      "2 Kin", "1 Chr", "2 Chr", "Ezr", "Neh", "Est", "Job", "Psa", "Pro", "Ecc", "Son", 
      "Isa", "Jer", "Lam", "Eze", "Dan", "Hos", "Joe", "Amo", "Oba", "Jon", "Mic", 
      "Nah", "Hab", "Zep", "Hag", "Zec", "Mal", "Mat", "Mar", "Luk", "Joh", "Act", 
      "Rom", "1 Cor", "2 Cor", "Gal", "Eph", "Phi", "Col", "1 The", "2 The", "1 Tim", 
      "2 Tim", "Tit", "Phi", "Heb", "Jam", "1 Pet", "2 Pet", "1 Joh", "2 Joh", "3 Joh", 
      "Jud", "Rev"
   );

   public $booksFullnames = array(
      "Genesis", "Exodus", "Leviticus", "Numbers", "Deuteronomy", "Joshua", "Judges", 
      "Ruth", "1 Samuel", "2 Samuel", "1 Kings", "2 Kings", "1 Chronicles", 
      "2 Chronicles", "Ezra", "Nehemiah", "Esther", "Job", "Psalm", "Proverbs", 
      "Ecclesiastes", "Song of Solomon", "Isaiah", "Jeremiah", "Lamentations", "Ezekiel", 
      "Daniel", "Hosea", "Joel", "Amos", "Obadiah", "Jonah", "Micah", "Nahum", 
      "Habakkuk", "Zephaniah", "Haggai", "Zechariah", "Malachi", "Matthew", "Mark", 
      "Luke", "John", "Acts", "Romans", "1 Corinthians", "2 Corinthians", "Galatians", 
      "Ephesians", "Philippians", "Colossians", "1 Thessalonians", "2 Thessalonians", 
      "1 Timothy", "2 Timothy", "Titus", "Philemon", "Hebrews", "James", "1 Peter", 
      "2 Peter", "1 John", "2 John", "3 John", "Jude", "Revelation"
   );

   private $addLinkPattern;

   public function __construct() {
      $this->defineLinkPattern();
   }

   public function addLinks($text) {
      $returnString = preg_replace_callback(
         $this->addLinkPattern,
         array($this, "addLinkCallback"),
         $text
      );
      return $returnString;
   }

   private function addLinkCallback($matches) {
      $returnString = "";
      foreach($matches as $match) {
         $returnString .= "<a href=\"bible.php?passage=$match\">$match</a>";
      }
      return $returnString;
   }

   private function defineLinkPattern() {
      // It is important that the full names appear before the abbreviated ones.
      $bookList = implode("|", array_merge($this->booksFullnames, $this->booksAbbreviated));
      $this->addLinkPattern = "/\\b(?:$bookList)(?:\\s+\\d+)?(?::\\d+(?:–\\d+)?(?:,\\s*\\d+(?:–\\d+)?)*)?/";
   }

   /*
   *  array('book', 'chapter', 'verse')
   */
   public function getPassage($ref)
   {
      
      $passage = $this->booksFullnames[intval($ref['b'])-1] . ' ' . $ref['c'] . ':' . $ref['v'];

      return $passage;
   }

   public function friendlyVerseId($vid)
   {
      if ($this->isValidVerseId($vid))
      {
         $book = intval(substr($vid, 0, 2));
         $chapter = intval(substr($vid, 2, 3));
         $verse = intval(substr($vid, 5, 3));
         $friendly = $this->booksFullnames[$book-1] . ' ' . $chapter . ':' . $verse;
         
         return $friendly;
      }
   }

   public function isValidVerseId($vid)
   {
      return ((is_numeric($vid)) && (strlen((string)$vid) == 8));
   }

   private $_index = array(
      'books' => array(
         'Genesis' => array(
            'start' => '',
            'end' => '',
         ),
         'Exodus' => array(
            'start' => '',
            'end' => '',
         ),
         'Leviticus' => array(
            'start' => '',
            'end' => '',
         ),
         'Numbers' => array(
            'start' => '',
            'end' => '',
         ),
         'Deuteronomy' => array(
            'start' => '',
            'end' => '',
         ),
         'Joshua' => array(
            'start' => '',
            'end' => '',
         ),
         'Judges' => array(
            'start' => '',
            'end' => '',
         ),
         'Ruth' => array(
            'start' => '',
            'end' => '',
         ),
         '1 Samuel' => array(
            'start' => '',
            'end' => '',
         ),
         '2 Samuel' => array(
            'start' => '',
            'end' => '',
         ),
         '1 Kings' => array(
            'start' => '',
            'end' => '',
         ),
         '2 Kings' => array(
            'start' => '',
            'end' => '',
         ),
         '1 Chronicles' => array(
            'start' => '',
            'end' => '',
         ),
         '2 Chronicles' => array(
            'start' => '',
            'end' => '',
         ),
         'Ezra' => array(
            'start' => '',
            'end' => '',
         ),
         'Nehemiah' => array(
            'start' => '',
            'end' => '',
         ),
         'Esther' => array(
            'start' => '',
            'end' => '',
         ),
         'Job' => array(
            'start' => '',
            'end' => '',
         ),
         'Psalm' => array(
            'start' => '',
            'end' => '',
         ),
         'Proverbs' => array(
            'start' => '',
            'end' => '',
         ),
         'Ecclesiastes' => array(
            'start' => '',
            'end' => '',
         ),
         'Song of Solomon' => array(
            'start' => '',
            'end' => '',
         ),
         'Isaiah' => array(
            'start' => '',
            'end' => '',
         ),
         'Jeremiah' => array(
            'start' => '',
            'end' => '',
         ),
         'Lamentations' => array(
            'start' => '',
            'end' => '',
         ),
         'Ezekiel' => array(
            'start' => '',
            'end' => '',
         ),
         'Daniel' => array(
            'start' => '',
            'end' => '',
         ),
         'Hosea' => array(
            'start' => '',
            'end' => '',
         ),
         'Joel' => array(
            'start' => '',
            'end' => '',
         ),
         'Amos' => array(
            'start' => '',
            'end' => '',
         ),
         'Obadiah' => array(
            'start' => '',
            'end' => '',
         ),
         'Jonah' => array(
            'start' => '',
            'end' => '',
         ),
         'Micah' => array(
            'start' => '',
            'end' => '',
         ),
         'Nahum' => array(
            'start' => '',
            'end' => '',
         ),
         'Habakkuk' => array(
            'start' => '',
            'end' => '',
         ),
         'Zephaniah' => array(
            'start' => '',
            'end' => '',
         ),
         'Haggai' => array(
            'start' => '',
            'end' => '',
         ),
         'Zechariah' => array(
            'start' => '',
            'end' => '',
         ),
         'Malachi' => array(
            'start' => '',
            'end' => '',
         ),
         'Matthew' => array(
            'start' => '',
            'end' => '',
         ),
         'Mark' => array(
            'start' => '',
            'end' => '',
         ),
         'Luke' => array(
            'start' => '',
            'end' => '',
         ),
         'John' => array(
            'start' => '',
            'end' => '',
         ),
         'Acts' => array(
            'start' => '',
            'end' => '',
         ),
         'Romans' => array(
            'start' => '',
            'end' => '',
         ),
         '1 Corinthians' => array(
            'start' => '',
            'end' => '',
         ),
         '2 Corinthians' => array(
            'start' => '',
            'end' => '',
         ),
         'Galatians' => array(
            'start' => '',
            'end' => '',
         ),
         'Ephesians' => array(
            'start' => '',
            'end' => '',
         ),
         'Philippians' => array(
            'start' => '',
            'end' => '',
         ),
         'Colossians' => array(
            'start' => '',
            'end' => '',
         ),
         '1 Thessalonians' => array(
            'start' => '',
            'end' => '',
         ),
         '2 Thessalonians' => array(
            'start' => '',
            'end' => '',
         ),
         '1 Timothy' => array(
            'start' => '',
            'end' => '',
         ),
         '2 Timothy' => array(
            'start' => '',
            'end' => '',
         ),
         'Titus' => array(
            'start' => '',
            'end' => '',
         ),
         'Philemon' => array(
            'start' => '',
            'end' => '',
         ),
         'Hebrews' => array(
            'start' => '',
            'end' => '',
         ),
         'James' => array(
            'start' => '',
            'end' => '',
         ),
         '1 Peter' => array(
            'start' => '',
            'end' => '',
         ),
         '2 Peter' => array(
            'start' => '',
            'end' => '',
         ),
         '1 John' => array(
            'start' => '',
            'end' => '',
         ),
         '2 John' => array(
            'start' => '',
            'end' => '',
         ),
         '3 John' => array(
            'start' => '',
            'end' => '',
         ),
         'Jude' => array(
            'start' => '',
            'end' => '',
         ),
         'Revelation' => array(
            'start' => '',
            'end' => '',
         ),
      ),
   );

}
