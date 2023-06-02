
mb.dialogs.save = function(command, args, callback) 
{
   var dialog = this;
   var el = $('#save_dialog');
   var files = [];
   var commands =  {
      'init': function(args, callback)
      {         
         // Dialog Buttons
         el.modal({
            'onOpenStart': false,
         });
      
         el.find('.modal-footer .btnSave').on('click', function() 
         {
            var filename = el.find('input.filename').val();

            var file = sortableSearchResults.toArray().join(';');
            mb.storage.files[filename] = file;
            storage_set();
         });
      
         el.find('.modal-footer .btnCancel').on('click', function() 
         {
            el.modal('Close');
         });

         el.find('.files').on('click', function()
         {
            el.find('.selected').removeClass('selected');
         });
      },
      'open': function()
      {
         load();
         el.modal('open');
         renderFiles();
         el.find('input.filename').focus();
      },
      'loadFiles': function()
      {
         loadFiles();
      },

   };

   function renderFiles()
   {
      if (!Object.keys(files).length) return;

      var $files = el.find('.files');

      $files.find('.file').remove();
      
      $.each(files, function(index, file)
      {
         var $file = $('<li class="file"><a>'+index+'</a></li>');
         
         $file.on('click', function(e)
         {
            el.find('input.filename').val($(this).text());
            el.find('.selected').removeClass('selected');
            $(this).toggleClass('selected');

            if ($(this).hasClass('selected'))
            {
               el.find('input.filename').focus().select();
            }
            e.stopPropagation();
         });
         $files.append($file);
      });
   }
   
   function load()
   {
      
      files = storage_get().files;
      /*
      */
      files = {
         '9-4-19-2023': {"status":"Ok","bookmarks":["Phil 3:20-21","Psalms 100:3","Romans 5:1","Romans 5:1,6:2-9,11,12,15,25-33,7:5-12","2 Corinthians 4:3-4","Psalms 2","Daniel 9","2 Corinthians 4:3-4","Psalms 2"]},
         'September 5, 2019 - This is a test of the long named index/file name that is helpful in remembering what is in them': {"status":"Ok","bookmarks":["John 14:1","1 Cor 15:53","1 Thess 4:13-18","1 Cor 1:7-8","Rom 8:19"]},
         'September 6, 2019 - This is a test of the long named index/file name that is helpful in remembering what is in them': {"status":"Ok","bookmarks":["John 14:1","1 Cor 15:53","1 Thess 4:13-18","1 Cor 1:7-8","Rom 8:19"]},
         'September 7, 2019 - This is a test of the long named index/file name that is helpful in remembering what is in them': {"status":"Ok","bookmarks":["John 14:1","1 Cor 15:53","1 Thess 4:13-18","1 Cor 1:7-8","Rom 8:19"]},
         'September 8, 2019 - This is a test of the long named index/file name that is helpful in remembering what is in them': {"status":"Ok","bookmarks":["John 14:1","1 Cor 15:53","1 Thess 4:13-18","1 Cor 1:7-8","Rom 8:19"]},
         'September 9, 2019 - This is a test of the long named index/file name that is helpful in remembering what is in them': {"status":"Ok","bookmarks":["John 14:1","1 Cor 15:53","1 Thess 4:13-18","1 Cor 1:7-8","Rom 8:19"]},
         'September 10, 2019 - This is a test of the long named index/file name that is helpful in remembering what is in them': {"status":"Ok","bookmarks":["John 14:1","1 Cor 15:53","1 Thess 4:13-18","1 Cor 1:7-8","Rom 8:19"]},
         'September 11, 2019 - This is a test of the long named index/file name that is helpful in remembering what is in them': {"status":"Ok","bookmarks":["John 14:1","1 Cor 15:53","1 Thess 4:13-18","1 Cor 1:7-8","Rom 8:19"]},
         'September 12, 2019 - This is a test of the long named index/file name that is helpful in remembering what is in them': {"status":"Ok","bookmarks":["John 14:1","1 Cor 15:53","1 Thess 4:13-18","1 Cor 1:7-8","Rom 8:19"]},
         'September 13, 2019 - This is a test of the long named index/file name that is helpful in remembering what is in them': {"status":"Ok","bookmarks":["John 14:1","1 Cor 15:53","1 Thess 4:13-18","1 Cor 1:7-8","Rom 8:19"]},
         'September 14, 2019 - This is a test of the long named index/file name that is helpful in remembering what is in them': {"status":"Ok","bookmarks":["John 14:1","1 Cor 15:53","1 Thess 4:13-18","1 Cor 1:7-8","Rom 8:19"]},
         'September 15, 2019 - This is a test of the long named index/file name that is helpful in remembering what is in them': {"status":"Ok","bookmarks":["John 14:1","1 Cor 15:53","1 Thess 4:13-18","1 Cor 1:7-8","Rom 8:19"]},
         'September 16, 2019 - This is a test of the long named index/file name that is helpful in remembering what is in them': {"status":"Ok","bookmarks":["John 14:1","1 Cor 15:53","1 Thess 4:13-18","1 Cor 1:7-8","Rom 8:19"]},
         'September 17, 2019 - This is a test of the long named index/file name that is helpful in remembering what is in them': {"status":"Ok","bookmarks":["John 14:1","1 Cor 15:53","1 Thess 4:13-18","1 Cor 1:7-8","Rom 8:19"]},
      }

      
   }
   
   if ((typeof command !== 'undefined') && (typeof commands[command] === 'function'))
   {
      commands[command](args, callback);
   }
};

