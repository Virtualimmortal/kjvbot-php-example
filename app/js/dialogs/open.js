
mb.dialogs.open = function(command, args, callback) 
{
   var dialog = this;
   var el = $('#open_dialog');
   var imports = [];
   var commands =  {
      'init': function(args, callback)
      {
         el.modal({
            'onOpenStart': false,
         });
      
         el.find('.modal-footer .btnMerge').on('click', function() 
         {
            importFile(1, callback);
         });
         el.find('.modal-footer .btnOk').on('click', function() 
         {
            importFile(0, callback);
         });
      
         el.find('.modal-footer .btnCancel').on('click', function() 
         {
            el.modal('Close');
         });
      
         el.find('input.upload').on('change', function(e)
         {
            e.stopPropagation();
            e.preventDefault();   
            var files = e.target.files; // FileList object
      
            // files is a FileList of File objects. List some properties.
            var output = [];
            for (var i = 0, f; f = files[i]; i++) {
               output.push('<li><strong>', escape(f.name), '</strong> (', f.type || 'n/a', ') - ',
                  f.size, ' bytes, last modified: ',
                  f.lastModifiedDate ? f.lastModifiedDate.toLocaleDateString() : 'n/a',
                  '</li>');
               
               var reader = new FileReader();
      
               // Closure to capture the file information.
               reader.onload = (function(theFile) 
               {
                  return function(e) 
                  {
                     imports = JSON.parse(e.target.result);
      
                     el.find('.preview').jsonViewer(imports);
                  };
               })(f);
      
               // Read in the image file as a data URL.
               reader.readAsText(f);
            }
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

