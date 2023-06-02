<div class="result_container">
   <div class="verse_wrapper">   
      <div class="verse result" title="<?= $verse['reference_friendly'] ?>" data-key="<?= $verse['reference_friendly'] ?>">
         
         <div class="before"></div>
         <?
         $string = preg_replace('/\s(\S*)$/', '.$1', trim($verse['reference_friendly'])); 
         $reference_parts = explode(".",$string);
         $book = $reference_parts[0];
         foreach ($verse['data'] as $chapter => $verses)
         {
            ?>
            <div class="verse_wrapper">
               <?php
               foreach ($verses as $v)
               {                  
                  $reference = $reference_parts[0] . ' ' . $v[2] . ':' . $v[3];
                  if (($current_book !== $book) || ($current_chapter !== $v[2]))
                  {
                     $book_chapter = $book . ' ' . $v[2];
                     $current_book = $book;
                     $current_chapter = $v[2];
                  }
                  else
                  {
                     $book_chapter = '';
                  }
                  render('components/verse.php', array('chapter' => $book_chapter, 'reference' => $reference, 'verse' => $v));
               }
               ?>
            </div>
            <?php
         }
         ?>

         <div class="after"></div>

      </div>
   </div>
</div>
