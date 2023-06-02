<!-- Modal Structure -->
<div id="remove_all_bookmarks_dialog" class="modal">
   <div class="modal-content grey-text text-darken-3">
      <h4>Remove All Bookmarks</h4>
      <p>Are you sure?</p>
   </div>
   <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat btnOk">Yes</a>
      <a href="#!" class="modal-close waves-effect waves-red btn-flat btnCancel">No</a>
   </div>
</div>

<script>
$(document).ready(function()
{
   var $remove_all_bookmarks_dialog = $('#remove_all_bookmarks_dialog');
   $remove_all_bookmarks_dialog.modal({
      'onOpenStart': false,
    });

   $('.bookmark_tools .clear_all_bookmarks_btn').on('click', function()
   {
      $remove_all_bookmarks_dialog.modal('open');
   });

   $remove_all_bookmarks_dialog.find('.modal-footer .btnOk').on('click', function() 
   {
      var package = {
         'action': 'clear_all_bookmarks',
      }
      $.ajax({
         url:'api.php', 
         dataType: 'json',
         data: package,
         success: function(data) 
         {
            M.toast({
               html: 'Bookmarks removed',
               displayLength: 5000
            });
            $('.sidenav .bookmark_menu .bookmark_links li').remove();
         },
         error: function(response) 
         {
            //console.log('There was a problem with the api call');
            console.log(response);
         },
      });
   });

   $remove_all_bookmarks_dialog.find('.modeal-footer .btnCancel').on('click', function() 
   {
      $remove_all_bookmarks_dialog.modal('Close');
   });

});

</script>
