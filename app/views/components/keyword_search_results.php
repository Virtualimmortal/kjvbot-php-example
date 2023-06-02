<?php
$bible = Bible::getInstance();
?>

<div class="keyword_result_container">

   <div class="keyword result" data-key="<?= $result['keyword'] ?>">
      
      <h2 title="Search results for &quot;<?= $result['keyword'] ?>&quot;"><?= $result['keyword']?></h2>
      
      <div class="scriptures col s12">
         
         <?php
         $current_chapter = '';
         foreach ($result['data'] as $chapter => $verses)
         {
            foreach ($verses as $verse)
            {
               $book = $bible->utils->booksFullnames[$verse['b']-1];
               if ($current_chapter !== $book . ' ' . $verse['c'])
               {
                  $book_chapter = $book . ' ' . $verse['c'];
                  $current_chapter = $book_chapter;
               }
               else
               {
                  $book_chapter = '';
               }
               $book_number = $bible->bibleToSql->addZeros($verse['b'], 2);
               $chapter = $bible->bibleToSql->addZeros($verse['c'], 3);
               $verse_number = $bible->bibleToSql->addZeros($verse['v'], 3);
               $verse_id = $book_number . $chapter . $verse_number;
               $verse['reference'] = Bible::getInstance()->utils->friendlyVerseId($verse_id);
               $verse['url'] = 'search.php?s=' . urlencode($verse['reference']);
               
               render('components/verse.php', array('chapter' => $book_chapter, 'verse' => $verse));
            }
         }
         ?>
      </div>

      <div class="synonym_container col s12">
         <?php 
         if (!empty($result['synonyms'])) 
         {
            $synonyms = array();
            
            foreach ($result['synonyms'] as $synonym)
            {
               $formatted_word = preg_replace( "/\n\s+/", "\n", rtrim(html_entity_decode(strip_tags($synonym->word))));

               echo '<a class="btn synonym" target="_blank" href="search.php?s=' . $formatted_word . '">' . $formatted_word . '</a>';
               //echo '<a href="#!" class="collection-item"><span class="badge">' . ((int)$synonym->score * .001) . '%</span>' . $synonym->word . '</a>';
            }
         }
         ?>
      </div>

   </div>
   <nav>
      <div class="nav-wrapper">
         <ul id="nav-mobile" class="left">
            <li><a class="popout_verse" href="?s=<?= urlencode($result['keyword']) ?>" target="_blank" title="Isolate this collection - <?= $verse['reference'] ?>"><i class="material-icons">open_in_new</i></a></li>
            <li><a class="copy_keyword_search" href="#" title="Copy to Clipboard"><i class="material-icons">content_copy</i></a></li>
            <li><a class="bookmark_verse" href="#" title="Bookmark <?= $reference ?>"><i class="material-icons">bookmark_outline</i></a></li>
         </ul>
      </div>
   </nav>

</div>
