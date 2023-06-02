(function($) {

   var $header = $('.header');
   var $primarySearchField = $('#index-search-field');

   // Init bible object
   window.bible = {
      'bookmarks': [],
      'toc': (function() {
         var json = null;
         $.ajax({
            'async': false,
            'global': false,
            'url': "js/toc.json",
            'dataType': "json",
            'success': function (data) {
               json = data;
            }
         });
         return json;
      })(),
      'books': (function() {
         var json = null;
         $.ajax({
            'async': false,
            'global': false,
            'url': "js/books.json",
            'dataType': "json",
            'success': function (data) {
               json = data;
            }
         });
         return json;
      })(),

   };

   /**
    * Load Bible Structure
    */
   var bible = {
   };

   // Init Materialize
   M.AutoInit();
   
   $('.sidenav').sidenav({
      'onOpenStart': function()
      {
         $(this)[0].$el.addClass('shadow');
      },
      'onCloseStart': function()
      {
         $(this)[0].$el.removeClass('shadow');
      },
   });
   

   /*
   *  Keyboard Assignments
   */
   $(window).on('keydown', function(e) {
      
     //console.log(e);
      /*
      *  Ctrl + Space - Activate Primary Search
      */
      if (e.which == 32)
      {
         if (e.ctrlKey)
            window.location = '/index.php';
      }
      /*
      *  Ctrl + Shift + f - Activate Primary Search
      */
      if (e.which == 70)
      {
         if ((e.ctrlKey) && (e.shiftKey))
            toggleHeaderSearchboxVisibility();
      }
  });

   /*
   *  Header search view toggle
   */
   $('nav .sm-search-btn, nav .lg-search-btn, nav .brand-logo', $header).on('click', function(e) 
   {
      toggleHeaderSearchboxVisibility();
   });

   /*
   *  Verse Event Listeners
   */
   $('.verse.result').on('click', function() {

      var $element = $(this);

      function read(from, length, target, callback)
      {         
         //if ($element.hasClass('reading')) return true;
         var direction = 1;

         if (length < 0) {
            length = length * (-1);
            direction = 0;
         }
         loading(2);
         $element.addClass('reading');

         $.ajax({
            url:'read.php', 
            dataType: 'json',
            data: 'r='+from+'&l='+length+'&d='+direction+'&render=true',
            success: function(result) 
            {
               $element.addClass('expanded');
               var beforeEl = target.find('.before');
               var afterEl = target.find('.after');
               if (direction)
               {
                  // Forward reading
                  $after = $('<div class="after"></div>')
                  $.each(result.data, function(search, result) 
                  {
                     $after.append(result)
                  });
                  target.append($after);
               }
               else 
               {
                  // Backwards reading
                  $before = $('<div class="before"></div>')
                  $.each(result.data, function(search, result) 
                  {
                     $before.prepend(result);
                  });
                  target.prepend($before);

                  if ($element.find('.verse[data-vid="'+from+'"]').length)
                  {
                     var vpos = Math.round($element.find('.verse[data-vid="'+from+'"]').offset().top);
                     //console.log(vpos);
                     //console.log(vpos - ($element[0].offsetHeight / 2));
                     //console.log($element[0]);
                     if (vpos)
                     {
                        $element.scrollTop($element.find('.before').first()[0].offsetHeight);
                     }
                  }
                  else 
                  {
                     //$element.scrollTop(100);
                  }
               }
               $element.removeClass('reading');

               loading(0);
               
               if (typeof callback == 'function')
               {
                  callback($element, from);
               }

            },
            error: function(response) 
            {
               loading(0);
               $element.removeClass('reading');
               console.log('Error requesting read.php');
            },
         });
      }

      function loadMoreBefore(callback)
      {
         var first = $element.find('.before .verse').first().attr('data-vid');
         first = (first) ? first : $element.find('.verse_wrapper .verse').attr('data-vid');
         read(first, -20, $element, callback);
      }
      function loadMoreAfter(callback)
      {
         var last = $element.find('.after .verse').last().attr('data-vid');
         last = (last) ? last :  $element.find('.verse_wrapper .verse').attr('data-vid');
         read(last, 20, $element, callback);
      }

      if (!$element.hasClass('expanded'))
      {
         if (!$element.find('.after .verse').length)
         {
            loadMoreAfter(loadMoreBefore);

            $element.scroll(function() {
               if ($element.hasClass('reading')) return true;
               //console.log($element[0].scrollTop);
               //console.log($element[0].scrollHeight);
               //console.log(target.find('.verse[data-vid="'+from+'"]'));
               if ($element[0].scrollTop < 30) 
               {
                  loadMoreBefore();
               }
               if ($element[0].scrollTop > ($element[0].scrollHeight - $element[0].offsetHeight - 100))
               {
                  loadMoreAfter();
               }
            });   
         }
      }

   });

   function speak(words)
   {
      var msg = new SpeechSynthesisUtterance(words);
      window.speechSynthesis.speak(msg);
   }

   /*
   *  NightmodeButton
   */
   $('.nightModeTrigger, .nightModeTrigger .switch input').on('click', function(e) 
   {
      var $this = ($(this).prop('nodeName') == 'input') 
         ? $(this) 
         : $(this).find('.switch input');

      $this.prop('checked', !$('body').hasClass('nightMode'));
      toggleNightMode();
   });


   /*
   *  Primary Search Field
   */
   $primarySearchField.on('focus', function(e) {
      var $this = $(this);

      $this.closest('.primary-search-field').addClass('focus');

      if ($this.hasClass('submitted'))
      {
         $this.select();
         $this.on('change', function() {
            $(this).removeClass('submitted').off('change');
         });
      }
   }).on('blur', function() {
      $(this).closest('.primary-search-field').removeClass('focus');
   });


   /*
   *  Search Field Behavior
   */
   $('.scripture-search').each(function() {
      var $this = $(this);

      $this.on('keyup', function(e) {
         var searchString = $this.val();

         if (e.which == 13)
         {
            // Submit Search Request
            loading(1);
            $('body').addClass('submitted');
            $this.addClass('submitted').blur();
            $this.closest('form').submit();
            //searchScriptures(searchString);
         }
         if (e.which == 27)
         {
            $this.blur();
         }
      });
   });


   /*
   *  Search String display click to display search box
   */
  $('.results .search_string .highlight').on('click', function(e) 
  {
     toggleHeaderSearchboxVisibility();
  });

  function toggleHeaderSearchboxVisibility() 
  {
      var $field = $('.sm-search-field', $header);
      if (!$field.hasClass('visible')) {
         $field.off('blur');
         $('.sm-search-field input', $header).focus().select().on('blur', function() {
            var $field = $(this);
            $field.parent().removeClass('visible');
            $field.off('blur');
         });
         $field.addClass('visible');
      }
      else
      {
         $field.off('blur');
         $field.removeClass('visible');
      }
   }


   /*
   *  🔎
   */
  $('#index-search-btn').on('click', function(e) {
      var $searchField = $('#index-search-field');
      var searchString = $searchField.val();
      e.preventDefault();
      e.stopPropagation();

      if (!searchString.length)
      {
         $searchField.focus().select();
      }
      else
      {
         loading(1);
         $searchField.addClass('submitted');
         $searchField.closest('form').submit();
         //searchScriptures(searchString);
      }
   });

   if (mb.storage.bookmarks.length) $('.edit_bookmarks_btn_wrapper').removeClass('hide');
   
   /*
   *  Bookmarking UI Element Bindings
   */
   function onBookmarksUploaderChange(event) {
      var reader = new FileReader();
      reader.onload = onReaderLoad;
      reader.readAsText(event.target.files[0]);
   }

   function onReaderLoad(event){
      var bookmarks = JSON.parse(event.target.result);
      notify(mb.storage.bookmarks.length + ' bookmarks imported');
      console.log(bookmarks);
      mb.storage.bookmarks = bookmarks;
      storage_set();
      loading(1);
      location.reload();

   }
   
   document.getElementById('bookmarks_file_import').addEventListener('change', onBookmarksUploaderChange);

   $('.import_bookmarks_btn').on('click', function(e)
   {
      e.preventDefault();
      $("#bookmarks_file_import:hidden").trigger('click');
   });

   $('#bookmark_collection_title.modal .export_btn').on('click', function()
   {
      var customFilename = $('#bookmark_collection_title.modal input#bookmarks_title').val();
      var date = new Date();
      var filename = (customFilename.length)
         ? customFilename
         : $('#bookmark_collection_title.modal input#bookmarks_title').attr('data-default-filename');

      M.Modal.getInstance($('#bookmark_collection_title')).close();
      mb.download(JSON.stringify(mb.storage.bookmarks), filename, 'application/json');
      notify(mb.storage.bookmarks.length + ' bookmarks exported');
   });


   
   /*
   *  Page link
   */
   $('.page_link').on('click', function(e) {
      e.preventDefault();
      var url = window.location.href;
      copyText(url);
      notify('<i class="fas fa-copy"></i> &nbsp; Page link copied to clipboard');
      return false;
   });

   /*
   *  Results
   $('.results .keyword[data-key]:not([data-key=""]) .scriptures').each(function() {
      // Keyword highlighting
      var $this = $(this);
      var keyword = $this.closest('.keyword.result').attr('data-key');
      var words = keyword.split(' ');
      var html = $this.html();
      var query = '';
      var enew = '';
      var newe = '';
      $.each(words, function(index, word)
      {
         console.log(word);
         query = new RegExp("(\\b" + word + "\\b)", "gim");
         enew = html.replace(/(<span class="highlight">|<\/span>)/igm, "");
         newe = enew.replace(query, '<span class="highlight">$1</span>');
      });
      $this.html(html);
   });
   */

   $('.tabs').tabs({swipeable: true});

   $('.collapsible').not('.expandable').collapsible();
   $('.collapsible.expandable').collapsible({
      accordion: false
   });

   /**
    * Verse Action buttons
    */
   $('.results .verse_wrapper .copy_verse').on('click', function(e) 
   {
      e.preventDefault();
      clipboard($(this).closest('.verse_wrapper').find('.verse.result'));
   });
   $('.results .verse_wrapper .bookmark_verse').on('click', function(e) 
   {
      e.preventDefault();
      bookmark_verse($(this).closest('.verse_wrapper').find('.verse.result'));
   });

   /**
    * Keyword Action buttons
    */
   $('.results .keyword_result_container .copy_keyword_search').on('click', function(e) 
   {
      e.preventDefault();
      clipboard($(this).closest('.keyword_result_container'));
   });
   $('.results .keyword_result_container .bookmark_verse').on('click', function(e) 
   {
      e.preventDefault();
      bookmark_verse($(this).closest('.keyword_result_container').find('.keyword.result'));
   });
   $('.results .keyword_result_container > h2').on('click', function(e) 
   {
      e.preventDefault();
      clipboard($(this));
   });

   /*
   *  
   */
   function searchScriptures(searchString, $overlay)
   {
      if (!searchString.length)
      {
         error.log('Nothing to search for');
      }
      
      //console.log('Submitting search request for "' + searchString + '"...');
      
      if (typeof $overlay === 'undefined')
      {
         $overlay = new overlay(loading);
         $overlay.append('<div class="content"></div>');
      }


      $overlay.addClass('maximized loading').append();
      
      $.ajax({
         url:'read.php', 
         dataType: 'json',
         data: 'b='+encodeURI(searchString),
         success: function(data) 
         {
            var $content = $('.content', $overlay);

            $.each(data, function(search, result) 
            {
               //console.log(result);
               if (result.type === 'keyword')
               {
                  $content.append(renderKeywordData(search, result.data));
               }
               if (result.type === 'verse')
               {
                  $content.append(renderVerseData(search, result.data));
               }
            });
            
            $overlay.removeClass('loading');

         },
         error: function(response) 
         {
            $overlay.removeClass('loading');
            $overlay.addClass('error');
            $($overlay).html('<h2>No scripture was returned, please try again!</h2>'); // <---- this is the div id we update
         },
      });
   }


   /*
   *  
   */
   function renderKeywordData(keyword, data)
   {
      var $result = $('<div class="container"></div>');

      $.each(data, function(reference, obj)
      {
         $result.append(renderVerseData(reference, obj.data));
      });

      return $result;
   }
   

   /*
   *  
   */
   function renderVerseData(reference, data)
   {
      //console.log(reference);
      //console.log(data);
      var $verse = $('<div class="verse"></div>');
      //$verse.append('<div class="reference">'+reference+'</div>');
      $verse.append('<h3 class="reference">'+reference+'</h3>');
      $verse.append('<p><div class="number">'+data[3]+'</div>'+data[4]+'</p>');
      /*
      $verse = $('<div class="card"><div class="card-content"><h3>'+reference+'</h3><p><span class="number">'+data[3]+'</span>'+data[4]+'</p></div></div>');
      */

      return $verse;
   }


   /*
   *  
   */
   function toggleNightMode()
   {
      var $body = $('body');

      $body.toggleClass('nightMode');

      var mode = ($('body').hasClass('nightMode')) 
         ? 'night'
         : 'day'; 
      var package = {
         'action': 'toggle_night_mode',
         'data': {
            'day_night_mode': mode,
         }
      }
      
      $.ajax({
         url:'api.php', 
         dataType: 'json',
         data: package,
         success: function(data) 
         {
            //console.log(data);
         },
         error: function(response) 
         {
            //console.log('There was a problem with the api call');
            console.log(response);
         },
      });

   }


   /*
   *  Clipboard
   */
   function clipboard(element)
   {
      var text = '';
      var facet = element.attr('title');

      if (element.hasClass('verse')) 
      {
         var verse = element.find('.verse_wrapper').clone();
         verse.find('.verse_number').remove();
         verse.find('.verse').each(function()
         {
            text = text + $(this).text().trim() + "\n";
         });
         text = text  + facet + "\n";
      }
      else if (element.hasClass('keyword_result_container')) 
      {
         var verses = element.find('.verse');
         var facet = $(element).find('.keyword.result').attr('data-key');
         text = '"' + facet + '"' + "\n\n";

         verses.each(function()
         {
            var verse = $(this).clone();
            verse.find('.verse_number').remove();
            text = text + verse.text().trim() + "\n";
            text = text + $(this).attr('data-reference') + "\n\n";
         });

      }
      else 
      {
         text = element.text();
      }

      copyText(text);
      notify(' Copied to Clipboard - ' + '<strong>' + facet + '</strong>');
   }


   /*
   *  Bookmark Verse
   */
  function bookmark_verse(element)
  {
      var el = element.clone();
      var key = el.attr('data-key')
      el.find('nav').remove();
      //console.log(key);
      //console.log(mb.storage.bookmarks);
      mb.storage.bookmarks.push(key);
      storage_set();
      addLinkToBookmarkMenu(key);
      notify("Bookmarked " + key);
      //console.log(mb.storage.bookmarks);
      /*
      $.ajax({
         url:'api.php?action=add_bookmark',
         dataType: 'json',
         method: 'POST',
         data: package,
         success: function(data) 
         {

            //console.log(data);
            var $bookmarks = $('.bookmark_menu .collapsible-body > ul.bookmark_links');
            $bookmarks.find('.verse_link').remove();
            $.each(data.bookmarks, function(index, bookmark) 
            {
               $bookmarks.append($(bookmark));
            });

            notify('Bookmarked - &nbsp;' + '<strong> ' + key + ' </strong>');
         },
         error: function(response) 
         {
            console.log('There was a problem with the api call');
            console.log(response);
         },
      });
      */
   }

   /*
   *  Overlay
   */
   $('.overlay').overlay();

   $('a.disabled').on('click', function(e)
   {
      e.preventDefault();
      e.stopPropagation();
      return false;
   })

})(jQuery);


function copyText(text) 
{
   var input = document.createElement('textarea');
   document.body.appendChild(input);
   input.value = text;
   input.select();
   document.execCommand('copy',false);
   input.remove();
}

function notify(message)
{
   M.toast({
      html: message,
      displayLength: 5000
   });
}


/*
*  Loading Indicator
*/
function loading(loading)
{
   if (loading)
   {
      if (loading == 2)
      {
         $('body').addClass('loading');
      }
      else 
      {
         $('body').addClass('loading-bg');
      }
   }
   else
   {
      $('body').removeClass('loading loading-bg');
   }
}


