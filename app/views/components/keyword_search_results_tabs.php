<?php
$scripture_tab_id = uniqid();
$synonyms_tab_id = uniqid();
$bible = Bible::getInstance();
?>

<div class="keyword_result_container">

   <div class="keyword result" data-key="<?= $result['keyword'] ?>">
      
      <h2 title="Search results for &quot;<?= $result['keyword'] ?>&quot;"><?= $result['keyword']?></h2>
      
      <ul class="tabs">
         <li class="tab col s3">
            <a class="active" href="#<?= $scripture_tab_id; ?>"><i class="material-icons">place</i> Verses
               <span class="new badge pink darken-3" data-badge-caption=""><?= count($result['data']); ?></span>
            </a>
         </li>
         <li class="tab col s3">
            <a href="#<?= $synonyms_tab_id; ?>"><i class="material-icons">filter_drama</i> Similar
               <span class="new badge pink darken-3" data-badge-caption=""><?= count($result['synonyms']); ?></span>
            </a>
         </li>
      </ul>
      
      <div id="<?= $scripture_tab_id; ?>" class="scriptures col s12">
         
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
               render('components/verse.php', array('chapter' => $book_chapter, 'verse' => $verse));
            }
         }
         ?>
      </div>

      <div id="<?= $synonyms_tab_id; ?>" class="synonym_container col s12">
         <?php 
         $synonyms = array();
         foreach ($result['synonyms'] as $synonym)
         {
            $formatted_word = preg_replace( "/\n\s+/", "\n", rtrim(html_entity_decode(strip_tags($synonym->word))));

            echo '<a class="btn synonym" target="_blank" href="search.php?s=' . $formatted_word . '">' . $formatted_word . '</a>';
            //echo '<a href="#!" class="collection-item"><span class="badge">' . ((int)$synonym->score * .001) . '%</span>' . $synonym->word . '</a>';
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
