<?php
$bible = Bible::getInstance();
?>
<div class="result_container">
   <div class="verse_wrapper">   
      <div class="verse result" title="<?= $verse['reference_friendly'] ?>" data-key="<?= $verse['reference_friendly']; ?>">
         
         <div class="before"></div>
         <p>
         <?
         foreach ($verse['data'] as $chapter => $verses)
         {
            ?>
            <div class="verse_wrapper">
               <?php
               foreach ($verses as $v)
               {
                  $book = $bible->utils->booksFullnames[$v['b']-1];

                  if (($current_book !== $book) || ($current_chapter !== $v['c']))
                  {
                     $book_chapter = $book . ' ' . $v['c'];
                     $current_book = $book;
                     $current_chapter = $v['c'];
                  }
                  else
                  {
                     $book_chapter = '';
                  }
                  $book_number = $bible->bibleToSql->addZeros($v['b'], 2);
                  $chapter = $bible->bibleToSql->addZeros($v['c'], 3);
                  $verse_number = $bible->bibleToSql->addZeros($v['v'], 3);
                  $verse_id = $book_number . $chapter . $verse_number;
                  $v['reference'] = Bible::getInstance()->utils->friendlyVerseId($verse_id);
                  $v['url'] = 'search.php?s=' . urlencode($v['reference']);

                  render('components/verse.php', array('chapter' => $book_chapter, 'verse' => $v));
               }
               ?>
            </div>
            <?php
         }
         ?>
         </p>

         <div class="after"></div>

      </div>
      
      <?php 
      render('components/verse_nav.php', array('chapter' => $book_chapter, 'verse' => $verse));
      ?>
   </div>
</div>
