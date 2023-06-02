<?php
$scripture_tab_id = uniqid();
$synonyms_tab_id = uniqid();
$bible = Bible::getInstance();
?>

<div class="keyword_result_container">

   <div class="keyword result" data-key="<?= $result['keyword'] ?>">
      
      <h2 title="Suggestions for &quot;<?= $result['keyword'] ?>&quot;"><?= $result['keyword']?></h2>

      <div id="<?= $synonyms_tab_id; ?>" class="synonym_container col s12">
         <?php 
         $synonyms = getSynonyms($result['keyword']);
         foreach ($synonyms as $synonym)
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
