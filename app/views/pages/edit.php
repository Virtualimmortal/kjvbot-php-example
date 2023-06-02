<style>
body {
   background-color: #f5f5f5;
}
.page.container {
   text-align: center;
}
#facet_editor {
   display: none;
   font-size: 1.2em;
}
.scriptureList {
   margin: 0 auto 5rem;
}
.scriptureList .facet_title {
   cursor: pointer;
   display: inline-block;
}
.scriptureList .contents {
   text-align: left;
   height: 0;
   max-height: 250px;
   overflow-x: hidden;
   overflow-y: hidden;
   display: block;
   clear: both;
   -webkit-transition: all 1s linear;
   transition: all 1s linear;
}
.scriptureList .contents h3 {
   margin: 1rem 0;
}
.scriptureList li {
   background-color: #3c67c8;
   color: #f0f0f0;
}
.scriptureList li a {
   color: #040627;
}
.scriptureList li a:hover {
   color: #fff;
}

.nightMode .scriptureList li a {
   color: #7b8cd2;
}
.nightMode .scriptureList li a:hover {
   color: #33cdff;
}
.scriptureList li,
.nightMode .scriptureList li {
   min-height: 75px;
   padding-left: 30px;
   padding: 0 15px 1rem;
   border: 1px solid #a1abce;
}
.scriptureList li.selected,
.nightMode .scriptureList li.selected {
   background-color: #382FC2;
   color: #fff;
   border-color: #041277;
}
.scriptureList .relative_wrapper {
   position: relative;
}
.scriptureList .action_menu_left,
.scriptureList .action_menu_right {
   font-size: 1.2em;
   top: 20px;
}

.scriptureList .action_menu_left {
   position: absolute;
   left: 0;
}
.scriptureList .action_menu_right {
   position: absolute;
   right: 0;
}
.scriptureList .action_menu a {
   padding-top: 20px;
   width: 25px;
   height: 50px;
   display: block;
   float: left;
}
.scriptureList .action_menu_right .drag_btn {
   cursor: all-scroll;
}
.scriptureList li.sortable-chosen,
.nightMode .scriptureList li.sortable-chosen {
   background-color: #382FC2;
   color: #fff;
   /* border-color: #4D3D6C; */
   border-color: #5041FF;
}
.nightMode .scriptureList li.open {
   background-color: #222830;
   color: #ededed;
}
.nightMode .scriptureList li.open a {
   color: #ededed;
}
.nightMode .scriptureList li.open a:hover {
   color: #ededed;
}

.nightMode #facet_editor {
   color: #eee;
   background-color: #111;
}
.nightMode .scriptureList li {
   background-color: #2c354a;
   border-color: #1D2739;
}
.scriptureList li .remove_btn {
   display: none;
}
.scriptureList li.open .remove_btn {
   display: block;
}
.scriptureList li.open .contents {
   height: auto;
   overflow-y: auto;
   margin-top: 1rem;
}
.scriptureList li.open .contents .result .verse {
}
</style>
<?php
$bible = Bible::getInstance();
render('pages/edit/header.php', array('search_string' => $search_string));
?>


<div class="page container">

   <input id="facet_editor" type="text" value="<?= (!empty($search_string)) ? $search_string : ''; ?>" placeholder="Search" />


   <? if (!empty($search_results)) { ?>

   <h3>Search Results</h3> 
   
   <ul id="searchResults" class="scriptureList">
      <?php
      foreach ($search_results as $result)
      {
         $facet = ($result['type'] == 'verse') ? $result['reference'] : $result['keyword'];
         ?>
         <li data-id="<?= $result['reference'] ?>">

            <div class="relative_wrapper">

               <h5 class="facet_title"><?= $facet ?></h5>

               <div class="action_menu_left">
                  <a class="" target="_blank" href="search.php?s=<?= urlencode($facet) ?>"><i class="small material-icons">open_in_new</i></a>
               </div>

               <div class="contents">
                  <?php
                  if ($result['type'] == 'keyword')
                  {
                     render('pages/edit/list_item_keyword.php', array('result' => $result));
                  }
                  if ($result['type'] == 'verse')
                  {
                     render('pages/edit/list_item_verse.php', array('verse' => $result));
                  }
                  ?>               
               </div>

               <div class="action_menu_right">
                  <a class="drag_btn"><i class="selected small material-icons">swap_vert</i></a>
                  <a class="remove_btn right"><i class="small material-icons">clear</i></a>
               </div>

            </div>

         </li>
         <?php
      }
      ?>
   
   </ul>

   <? } ?>
   <? /*
   <? if (count($_SESSION['bookmarks'])) { ?>

   <h2>Bookmarks</h2>
   <ul id="bookmarks" class="scriptureList" data-search="">
      <?php
      foreach ($_SESSION['bookmarks'] as $bookmark)
      {
         echo '<li data-id="'.$bookmark.'">'.$bookmark.'</li>';
      }
      ?>
   
   </ul>

   <? } ?>
   */ ?>

</div>

<script>
$(document).ready(function() 
{
   $('#facet_editor').focus().select();

   var searchResults = document.getElementById('searchResults');
   window.sortableSearchResults = Sortable.create(searchResults, {
      group: 'searchResults',
      handle: '.drag_btn',
      fallbackOnBody: true,
      swapThreshold: 0.65,
      multiDrag: true, // Enable multi-drag
      selectedClass: 'selected', // The class applied to the selected items
      animation: 150,
      onUpdate: function()
      {
         //console.log(this.toArray());
         var search_string = this.toArray().join(';');
         $('#facet_editor').val(search_string);
         $('.header .view_search_btn').attr('href', 'search.php?s='+encodeURIComponent(search_string));
      }
   });

   $(searchResults).find('.facet_title').on('click', function(e)
   {
      e.preventDefault();
      e.stopPropagation();
      $(this).closest('li').toggleClass('open');
   })
/*
   var bookmarks = document.getElementById('bookmarks');
   window.sortableBookmarks = Sortable.create(bookmarks, {
      group: 'bookmarks',
      fallbackOnBody: true,
      swapThreshold: 0.65,
      multiDrag: true, // Enable multi-drag
      selectedClass: 'selected', // The class applied to the selected items
      animation: 150,
      onUpdate: function()
      {
         //console.log('Updated');
      }
   });
*/
   $('#facet_editor').keypress(function (e) {
      if (e.which == 13) 
      {
         // Add to list
         addNewFacetsToSearchResultsList(this.value);
         return false;
      }
   });

   $('.header .left .copy_btn').on('click', function()
   {
      loading(1);
      var text = '';
      $('ul#searchResults.scriptureList .verse[data-reference]:not([data-reference=""])').each(function()
      {
         var temp = $(this).clone()
         temp.find('.verse_number').remove();
         text = text + temp.text() + "\n" + $(this).attr('data-reference');
      });
      copyText(text);
      loading(0);
      notify('Copied to clipboard');
   });

   $('.header .right .preview_btn').on('click', function()
   {
      var search_string = '';
      $(bookmarks).find('li').each(function(bookmark) 
      {
         var prefix = (search_string.length) ? ';' : '';
         search_string += prefix + $(this).attr('data-key');
      });
   });

   // https://mediabrain.net/bible/edit.php?s=Phil+3%3A20-21%3BPsalms+100%3A3%3BRomans+5%3A1%3BRomans+5%3A1%2C6%3A2-9%2C11%2C12%2C15%2C25-33%2C7%3A5-12%3B2+Corinthians+4%3A3-4%3BPsalms+2%3BDaniel+9%3B2+Corinthians+4%3A3-4%3BPsalms+2
   function addNewFacetsToSearchResultsList(search_string)
   {
      if (!search_string.length) return;

      var facets = search_string.split(';');
      
      $.each(facets, function(key, value)
      {
         var facet = value.trim();
         //console.log(facet);
         $(searchResults).prepend('<li class="" data-id="'+facet+'">'+facet+'</li>');

      });

      search_uri_string = encodeURI(sortableSearchResults.toArray().join(';'));
      $('.header .view_search_btn').attr('href', 'search.php?s='+search_uri_string);
   }

});
</script>
