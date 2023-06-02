<div class="header">

   <nav>
      <div class="nav-wrapper ">

         <? render('pages/programmatic/header_left.php'); ?>

         <img class="brand-logo center" src="images/kjvbot-logo-96.png">

         <? render('pages/programmatic/header_right.php'); ?>

      </div>
   </nav>

   <? render('components/sidenav/programmatic_left.php'); ?>
      
</div>

<? render('components/dialogs/bookmark_collection_title.php', array()); ?>
<? render('components/dialogs/share.php', array()); ?>
<? render('components/dialogs/open.php', array()); ?>
<? render('components/dialogs/save.php', array()); ?>
<? render('components/dialogs/remove_all_bookmarks.php', array()); ?>

<script>
   $(document).ready(function()
   {
      mb.dialogs.share('init', 0, function(imports)
      {
         //console.log(imports);
      });

      mb.dialogs.open('init', 0, function(imports)
      {
         //console.log(imports);
      });

      mb.dialogs.save('init', 0, function(imports)
      {
         //console.log(imports);
      });

      $('.header .share_btn').on('click', function()
      {
         mb.dialogs.share('open');
      });

      $('.header .open_btn').on('click', function()
      {
         mb.dialogs.open('open');
      });

      $('.header .save_btn').on('click', function()
      {
         mb.dialogs.save('open');
      });

   });
</script>
