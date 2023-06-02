<?= (!empty($chapter)) 
   ? '<h3 class="chapter">' . $chapter . '</h3>'
   : '';
?>

<div class="verse" title="<?= $verse['reference'] ?>" data-reference="<?= $verse['reference'] ?>" data-vid="<?= $verse['id'] ?>">
   <span class="verse_number">
      <a href="<?= $verse['url'] ?>" target="_blank" ><?= $verse['v'] ?></a>
   </span>
   <?= $verse['t'] ?>
</div>
