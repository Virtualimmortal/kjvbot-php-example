<nav>
    <div class="nav-wrapper">
      <ul id="nav-mobile" class="left">
          <li><a class="popout_verse" href="?s=<?= urlencode($verse['reference']) ?>" target="_blank" title="Read in context - <?= $verse['reference'] ?>"><i class="material-icons">open_in_new</i></a></li>
          <li><a class="copy_verse" href="#" title="Copy to Clipboard"><i class="material-icons">content_copy</i></a></li>
          <li><a class="bookmark_verse" href="#" title="Bookmark <?= $verse['reference'] ?>"><i class="material-icons">bookmark_outline</i></a></li>
      </ul>
    </div>
</nav>
