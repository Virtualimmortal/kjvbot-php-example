<?
require_once '../includes/classes/bibleproject.php';
render('pages/search_results/header.php', array('search_string' => $search_string));
$bible = Bible::getInstance();
// find out the domain:
$domain = $_SERVER['HTTP_HOST'];
// find out the path to the current file:
$path = $_SERVER['SCRIPT_NAME'];
// find out the QueryString:
$queryString = $_SERVER['QUERY_STRING'];
// put it all together:
$url = "http://" . $domain . $path . "?" . $queryString;
$has_internet = true;

   /* Show query errors */
   if (!empty($search_results['errors']))
   {
      render('components/search_result_errors.php', array('errors' => $search_results['errors']));
   } 
   elseif (count($search_results))
   {
      ?>
      <div class="results">
         <div class="search_string">Showing results for <br/><span class="highlight"><?= $bible->friendlyReference($search_string) ?></span></div>
         
         <?
         $search_results_books = array();
         /* Results output */
         foreach ($search_results as $facet => $result)
         {
            foreach ($result['data'] as $chapter => $verses)
            {
               foreach ($verses as $v)
               {
                  $book = $bible->utils->booksFullnames[$v['b']-1];
                  if (!in_array($book, $search_results_books))
                  {
                     $search_results_books[] = $book;
                     $bibleproject_video_count = $bibleproject_video_count + count(BibleProject::$youtube_ids[$book]);
                  }
               }
            }
            
            if ($result['type'] == 'keyword')
            {
               render('components/keyword_search_results.php', array('result' => $result));
            }
            if ($result['type'] == 'verse')
            {

               render('components/verse_search_result.php', array('verse' => $result));
            }
         }
         ?>
         
         <?
         if ($has_internet) 
         {
            ?>
            <div class="fb-like left" style="margin-top: 1em;" data-href="<?= $url; ?>" data-colorscheme="dark" data-width="" data-layout="standard" data-action="like" data-size="small" data-share="true"></div>
            <?
         }
         ?>
         
      </div>

      <?
      if ($has_internet) 
      {
         ?>
         <div class="container">
            <div class="row">
            <div class="divider"></div>
            </div>
         </div>
         <?
         render('components/bibleproject_video_list.php', array(
            'search_results_books' => $search_results_books,
            'bibleproject_video_count' => $bibleproject_video_count,
         ));
      }
   }
