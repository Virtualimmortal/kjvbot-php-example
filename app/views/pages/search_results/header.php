<div class="header">

   <nav>
      <div class="nav-wrapper ">

         <? render('pages/search_results/header_left.php', array('search_string' => $search_string)); ?>

         <img class="brand-logo center" src="images/kjvbot-logo-96.png">
         
         <? render('pages/search_results/header_right.php', array('search_string' => $search_string)); ?>

      </div>
   </nav>

   <? render('components/sidenav/main_left.php'); ?>
      
   <div class="container">
      <div class="center">
         <div class="sm-search-field input-field col s6 s12 black-text">
            <!--<i class="grey-text material-icons prefix">search</i>-->
            <h1>Bible Search</h1>
            <form action="search.php" method="get">
               <input name="s" value="<?= $search_string; ?>" type="text" class="scripture-search grey-text" placeholder="<?= $search_string; ?>" >
            </form>

         </div>
      </div>
   </div>

</div>
<? render('components/dialogs/bookmark_collection_title.php', array()); ?>
<? render('components/dialogs/share.php', array()); ?>
<? render('components/dialogs/import.php', array()); ?>
<? render('components/dialogs/remove_all_bookmarks.php', array()); ?>

<script>
   $(document).ready(function()
   {
      mb.dialogs.share('init', 0, function(imports)
      {
         
      });

      /*
      mb.dialogs.create_new_bookmark('init', 0, function(imports)
      {
         //console.log(imports);
      });
      */

      $('.header .share_btn').on('click', function()
      {
         mb.dialogs.share('open');
      });

   });
</script>
