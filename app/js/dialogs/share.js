
mb.dialogs.share = function(command, args, callback) 
{
   var dialog = this;
   var el = $('#share_dialog');
   var imports = [];
   var commands =  {
      'init': function(args, callback)
      {
         el.modal({
            'onOpenStart': false,
         });
         el.find('.copy_page_link_btn').on('click', function(e) {
            e.preventDefault();
            var element = document.getElementById('page_link_text');
            element.select();
            document.execCommand('copy',false);
         
            notify('<i class="fas fa-copy"></i> &nbsp; Page link copied to clipboard');
            return false;
         });
         el.find('.copy_page_embed_code_btn').on('click', function(e) {
            e.preventDefault();
            var embedCode = el.find('textarea.page_embed_code').val();
            var element = document.getElementById('page_embed_code');
            element.select();
            document.execCommand('copy',false);
            notify('<i class="fas fa-copy"></i> &nbsp; Page embed code copied to clipboard');
            return false;
         });
         el.find('#meme_select').change(function(e)
         {
            function replaceQueryParam(param, newval, search) {
               var regex = new RegExp("([?;&])" + param + "[^&;]*[;&]?");
               var query = search.replace(regex, "$1").replace(/&$/, '');
           
               return (query.length > 2 ? query + "&" : "?") + (newval ? param + "=" + newval : '');
            }
            
            // Update Preview Image
            var image = this.value;
            el.find('img.meme_preview').attr('src', image);

            // Update page link input
            var name = image.replace('/images/memes/', '');
            var url = document.getElementById('page_link_text').value;
            document.getElementById('page_link_text').value = replaceQueryParam('m', name, url);
         });
      },
      'open': function()
      {
         el.modal('open');
      },
   };

   function importFile(merge, callback)
   {
      var package = {
         'action': 'upload_session_bookmarks',
         'data': {
            'imports': imports,
            'merge': merge,
         }
      };
   
      $.ajax({
         url:'api.php', 
         dataType: 'json',
         method: 'POST',
         data: package,
         success: function(data) 
         {
   
            //$('.sidenav .bookmark_menu .bookmark_links li').remove();
            var $bookmarks = $('.bookmark_menu .collapsible-body > ul.bookmark_links');
   
            $bookmarks.find('.verse_link').remove();
   
            $.each(data.bookmarks, function(index, bookmark) 
            {
               $bookmarks.append($(bookmark));
            });
            M.toast({
               html: 'Bookmarks Imported!',
               displayLength: 5000
            });
            console.log(callback);
            if (typeof callback === 'function')
            {
               callback(imports);
            }

         },
         error: function(response) 
         {
            //console.log('There was a problem with the api call');
            console.log(response);
         },
      });
   };
   
   if ((typeof command !== 'undefined') && (typeof commands[command] === 'function'))
   {
      commands[command](args, callback);
   }
};

