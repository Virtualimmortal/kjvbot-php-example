<!-- Mobile -->
<ul class="right hide-on-large-only">
   <!--<li><a class="highlight-btn" href="#"><i class="material-icons">highlight</i></a></li>-->
   <!--<li><a class="highlight-btn" href="#"><i class="material-icons">brightness_3</i></a></li>-->
</ul>

<!-- Desktop -->
<ul class="right">
   <!--<li><a class="highlight-btn" href="#"><i class="material-icons">highlight</i></a></li>-->
   <? /*
   <li><a title="Programmatical" href="programmatic.php?s=<?= get_var('s', '') ?>"><i class="fab fa-android"></i></a></li>
   */ ?>
   
   <li class="edit_bookmarks_btn_wrapper hide"><a title="Edit bookmarks" href="bookmarks.php"><i class="material-icons">book</i></a></li>
   <li class="hide-on-small-only"><a href="#"><i class="fas fa-magic"></i></a></li>
   <?php
   if (!empty($search_string)) 
   { 
      ?>
      <li><a title="Order" href="order.php?s=<?= get_var('s', '') ?>"><i class="material-icons">swap_vert</i></a></li>
      <li class="hide-on-small-only"><a title="Search" class="lg-search-btn" href="#"><i class="material-icons">search</i></a></li>
      <?php
   }
   ?>

   <!--<li><a href="#"><i class="fas fa-puzzle-piece"></i></a></li>-->
   <!--<li><a href="#"><i class="material-icons">android</i></a></li>-->
   <!--<li><a href="#"><i class="material-icons">redeem</i></a></li>-->
</ul>
