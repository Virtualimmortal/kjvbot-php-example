<?php
$scripture_tab_id = uniqid();
$synonyms_tab_id = uniqid();
?>

<div class="keyword_result_container">

   <div class="keyword result" data-key="<?= $result['keyword'] ?>">
      
      <h4 title="Search results for &quot;<?= $result['keyword'] ?>&quot;"><?= $result['keyword']?></h4>
      
      <div id="<?= $scripture_tab_id; ?>" class="scriptures col s12">
         
         
         <?php
         $current_chapter = '';
         foreach ($result['data'] as $index => $verses)
         {
            //debug($index);
            foreach ($verses as $v)
            {
               $string = preg_replace('/\s(\S*)$/', '.$1', trim($index)); 
               $reference_parts = explode(".",$string);
               $reference = $reference_parts[0] . ' ' . $v[2] . ':' . $v[3];
               $book = $reference_parts[0];
               if ($current_chapter !== $book . ' ' . $v[2])
               {
                  $book_chapter = $book . ' ' . $v[2];
                  $current_chapter = $book_chapter;
               }
               else
               {
                  $book_chapter = '';
               }
               render('components/verse.php', array('chapter' => $book_chapter, 'reference' => $reference, 'verse' => $v));
            }
         }
         ?>
      </div>
<?php
/*
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
*/ ?>

   </div>
</div>
