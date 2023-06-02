<ul class="left">
   <li>
      <a href="#" data-target="slide-out" class="sidenav-trigger main-menu-btn show-on-large"><i class="material-icons">menu</i></a>
   </li>
   <li class="hide show-on-medium-and-up">
      <a href="/bible"><i class="material-icons">home</i></a>
   </li>
   <li>
      <a title="Share" class="share_btn" href="#!"><i class="fas fa-share-square"></i></a>
   </li>
   
   <?php
   if (!empty($search_string)) 
   { 
      ?>
      <li>
         <a title="Start new search" href="<?= $_SERVER['PHP_SELF'] ?>" target="_blank" class="new_window_btn"><i class="material-icons">add</i></a>
      </li>
      <?php
   }
   ?>

</ul>
