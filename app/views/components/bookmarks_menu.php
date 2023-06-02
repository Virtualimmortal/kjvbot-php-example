<ul class="bookmark_menu collapsible collapsible-accordion">
   <li>
      <a class="collapsible-header">Bookmarks<i class="material-icons">bookmark</i></a>
      <div class="collapsible-body">
         <ul class="bookmark_tools collapsible collapsible-accordion">
            <li><hr/></li>
            <!--
            <li>
               <a href="search.php?s=<?= $encoded_bookmarks ?>"><i class="material-icons">collections_bookmark</i>Current Bookmarks</a>
            </li>
            <li>
               <a class="share_btn" data-link="https://mybiblebot.net/search.php?s=<?= $encoded_bookmarks ?>"><i class="material-icons">share</i>Share</a>
            </li>
            -->
            <li>
               <a href="bookmarks.php"><i class="fas fa-edit"></i>
Edit</a>
            </li>
            <li>
               <input id="bookmarks_file_import" type="file" style="display: none;"/>
               <a class="import_bookmarks_btn"><i class="fas fa-file-import"></i>Import</a>
            </li>
            <li>
               <a class="modal-trigger" data-target="bookmark_collection_title"><i class="fas fa-file-export"></i>Export</a>
            </li>
            <li><hr/></li>
         </ul>
         <ul class="bookmark_links">
         </ul>
      </div>
   </li>
</ul>

<script>

function addLinkToBookmarkMenu(key)
{
   $('ul.bookmark_menu ul.bookmark_links').append('<li class="verse_link"><a href="search.php?s='+key+'" target="_blank" data-key="'+key+'"><i class="material-icons">bookmark_border</i>'+key+'</a></li>');
}

$(document).ready(function() {


   $('.bookmark_tools .share_btn').on('click', function()
   {
      copyText(location.protocol + '//' + location.host + '/search.php?s=' + mb.storage.bookmarks);
      notify('Share link copied to clipboard.');
   });

   //console.log(mb.storage.bookmarks);
   var reversed_bookmarks = Object.create(mb.storage.bookmarks);
   reversed_bookmarks.reverse();
   reversed_bookmarks.forEach(function(key) {
      addLinkToBookmarkMenu(key);
   });
});
</script>
