(function($) {
   
   $.fn.overlay = function(options)
   {
      var defaults = {
         buttons: {
            'close': {
               'icon': 'close',
               'click': function($this) {
                  $this.slideDown();
               }
            }
         }
      };
      var settings = $.extend(defaults, options);
      
      return this.each(function() 
      {
         var $this = $(this);

         renderControls($this);

         if (typeof settings.element !== 'undefined')
         {
            
         }

         
      });

      function renderControls($this)
      {
         var $controls = $this.find('.controls')
         if (!$controls.length)
         {
            $controls = $('<div class="controls"></div>');
            $this.append($controls);
         }
         $.each(settings.buttons, function(name, obj) {
            $btn = $('<div title="'+name+'" class="btn"><i class="material-icons">'+obj.icon+'</i></div>').on('click', obj.func)
            $controls.append($btn);
         });
      }
   };


   
})(jQuery);

var overlay = function(content, callback) 
{
   $overlay = $('<div class="overlay"></div>').overlay();
   
   if ((typeof content !== 'undefined')  && (content.length))
   {
      $overlay.append(content);
   }

   if (typeof callback === 'function')
   {
      $('body').append(callback($overlay));
   }
   else 
   {
      $('body').append($overlay);
   }

   return $overlay;
};
