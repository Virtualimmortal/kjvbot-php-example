<?php
$url = $_SERVER['PHP_SELF'] . '?s=' . urlencode(get_var('s')).'&l=' . $location['location_id'];
?>
<div class="location" data-reference="<?= $reference ?>" data-vid="<?= $location['id'] ?>">
   <h4 class="name">
      <a href="<?= $url ?>" target="_blank" ><?= $location['name'] ?></a>
</h4>
<?= $location['address'] ?>  <?= $location['city'] ?>, <?= $location['state'] ?> <?= $location['zip'] ?>
</div>

<? /*
<nav>
   <div class="nav-wrapper">
      <ul id="nav-mobile" class="left">
         <li><a class="popout_verse" href="?s=<?= urlencode($result['keyword']) ?>" target="_blank" title="Isolate this collection - <?= $verse['reference'] ?>"><i class="material-icons">open_in_new</i></a></li>
         <li><a class="copy_keyword_search" href="#" title="Copy to Clipboard"><i class="material-icons">content_copy</i></a></li>
         <li><a class="bookmark_verse" href="#" title="Bookmark <?= $reference ?>"><i class="material-icons">bookmark_outline</i></a></li>
      </ul>
   </div>
</nav>
*/ ?>
