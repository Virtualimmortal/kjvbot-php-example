<div class="header">

   <nav>
      <div class="nav-wrapper ">

         <? render('pages/edit/header_left.php'); ?>

         <a href="/"><img class="brand-logo center" src="images/kjvbot-logo-96.png"></a>

         <? render('pages/edit/header_right.php'); ?>

      </div>
   </nav>

   <? render('components/sidenav/main_left.php'); ?>

</div>

<? render('components/dialogs/bookmark_collection_title.php', array()); ?>
<? //render('components/dialogs/create_new_bookmark.php', array()); ?>
<? render('components/dialogs/share.php', array()); ?>
<? render('components/dialogs/open.php', array()); ?>
<? render('components/dialogs/save.php', array()); ?>
<? render('components/dialogs/remove_all_bookmarks.php', array()); ?>

<div class="tap-target" id="save-notify-tap-target" data-target="save_btn">
   <div class="tap-target-content">
      <h5>You have pending changes</h5>
      <p>Hit the checkmark button to apply your changes.</p>
   </div>
</div>

<script>
   $(document).ready(function()
   {
      /****
       * Check with user before navigating if there are unsaved changes
       * 
      $('a:not([href=""],[href="!#"])').on('click', function()
      {
         if ($('body').hasClass('modified'))
         {
            return "You have changes that have not been applied yet.  Would you like to discard them?";
         }
      });
       */
      window.onbeforeunload = function() {
         /*
         $.ajax("someURL", {
            async: false,
            data: "test",
            success: function(event) {
                  console.log("Ajax request executed");
            }
         });
         */
         if ($('body').hasClass('modified'))
         {
            $('#save-notify-tap-target').tapTarget('open');
            return "You have changes that have not been applied yet.  Would you like to discard them?";
         }
      };
     
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

      $('.header .open_btn').on('click', function()
      {
         mb.dialogs.open('open');
      });

      $('.header .save_btn').on('click', function()
      {
         //mb.dialogs.save('open');
         storage_set();
         //notify(mb.storage.bookmarks.length + ' bookmarks saved');
         $('body').removeClass('modified');
         //loading(1);
         location.reload();
         /*
         $('ul.bookmark_menu ul.bookmark_links').html('');
         mb.storage.bookmarks.forEach(function(key) {
            addLinkToBookmarkMenu(key);
         });
         */
      });

   });
</script>
