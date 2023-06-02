<?php
$url = 'search.php?s=' . urlencode($bookmark['reference']);
?>
<li class="verse_link">
   <a href="<?= $url ?>" target="_blank"><i class="material-icons">bookmark_border</i><?= $bookmark['reference'] ?></a>
</li>

