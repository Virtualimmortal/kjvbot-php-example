var mb = {};
mb.dialogs = {};

/*
* Initialize mediabrain responders on page load
*/
$(function() {

   $(window).respond();

});

storage_init();
mb.storage = storage_get();

function storage_init()
{
   if (!localStorage.getItem('kjvbot'))
   {
      mb.storage = {
         'bookmarks': [],
      };
      storage_set();
   }
   storage_get();
}

function storage_get()
{
   return JSON.parse(localStorage.getItem('kjvbot'));
}

function storage_set()
{
   //console.log(mb.storage);
   localStorage.setItem('kjvbot', JSON.stringify(mb.storage));
}
   
mb.ajax = function(options) 
{ 
   var defaults = {
      statusCode: {
         // Forbidden (Login Required)
         403: function()
         {
            window.location = '/login.php?timeout=t&next=' + encodeURIComponent(window.location.pathname + window.location.search);
         },
         // Internal Server Error
         500: function(jqxhr, textStatus, errorThrown)
         {
            window.notifier.notify('Something went wrong while trying to process your request.' +
               ' Please contact support with the following error information: <br/>' +
               ' status: ' + jqxhr.status + '<br/>' +
               ' response: ' + jqxhr.responseText + '<br/>' +
               ' error thrown: ' + errorThrown);
         },
      },
   };
   
   // Make sure CSRF prevention data is present when posting
   if (((options.hasOwnProperty('type')) && (options.type.toUpperCase() === 'POST'))
      || ((options.hasOwnProperty('method')) && (options.method.toUpperCase() === 'POST')))
   {
      for (var key in mb.csrf)
      {
         if (options.data instanceof FormData)
         {
            // Add CSRF token to FormData()
            options.data.append(key, mb.csrf[key]);
         }
         else if (typeof options.data === 'string')
         {
            if (options.data.indexOf(key) == -1)
            {
               // Add CSRF token to parameterized string
               options.data = options.data + '&' + key + '=' + mb.csrf[key];
            }
         }
         else if (typeof options.data === 'object')
         {
            if (!options.data.hasOwnProperty(key))
            {
               // Add CSRF token to object
               $.extend(options.data, mb.csrf);
            }
            else if (options.data[key] !== mb.csrf[key])
            {
               options.data[key] = mb.csrf[key];
            }
         }
         else if (typeof options.data == 'undefined')
         {
            options.data = mb.csrf;
         }
      }
   }
   
   // Extend default options
   $.extend(options, defaults); 
   
   return $.ajax(options);
}

// Shorthand for a mb.ajax() post request
mb.post = function(url, data, callback, type)
{
   // shift arguments if data argument was omitted
   if (jQuery.isFunction(data)) {
      type = type || callback;
      callback = data;
      data = undefined;
   }
   
   return mb.ajax({
      type: "POST",
      url: url,
      data: data,
      success: callback,
      dataType: type
   });
}

// Shorthand for a mb.ajax() get request
mb.get = function(url, data, callback, type)
{
   // shift arguments if data argument was omitted
   if (jQuery.isFunction(data)) {
      type = type || callback;
      callback = data;
      data = undefined;
   }
   
   return mb.ajax({
      type: "GET",
      url: url,
      data: data,
      success: callback,
      dataType: type
   });
}

mb.loadJs = function (url, callback) 
{
   if (typeof url === 'string')
   {
      // Return if script is already loaded
      if ($('script[src="' + url + '"]').length) 
      {
         if (typeof callback === 'function')
         {
            callback();
         }
         return;
      }

      mb.ajax({
         url: url,
         dataType: 'script',
         success: callback,
         async: true
      });
   }
   else if (typeof url === 'object')
   {
      var scriptComplete = 0;
      $.each(url, function(index, path) 
      {
         console.log(path);
         if (path == 'undefined.js') return;
         mb.loadJs(path, function(){
            scriptComplete++;
            if (url.length == scriptComplete)
            {
               if (typeof callback === 'function')
               {
                  callback();
               }
            }
         });
      });
   }
   else
   {
      console.error('An unexpected url was passed to the loadJs function, url contents:');
      console.error(url);
   }
}

mb.loadCss = function (url) 
{
   if ($("link[href='"+url+"']").length < 1)
   {
      $('<link>')
      .appendTo('head')
      .attr({
         type: 'text/css', 
         rel: 'stylesheet',
         href: url
      });
   }
}

mb.download = function(content, fileName, contentType) {
   var a = document.createElement("a");
   var file = new Blob([content], {type: contentType});
   a.href = URL.createObjectURL(file);
   a.download = fileName;
   a.click();
}


// Browser Tab Methods
mb.browserTab = {
   
   documentTitle : document.title, 
   titleTimer : null,
   
   alert : function(message) {
      var self = this;
      var state = false;
      
      // Store the current title
      self.documentTitle = document.title;
      // Set the flash interval
      self.titleTimer = setInterval(flash, 1000);
      
      function flash() {
         // switch between old and new titles
         document.title = state ? self.documentTitle : message;
         state = !state;
      }
   },
   
   reset : function() {
      var self = this;
      if (self.titleTimer != null) clearInterval(self.titleTimer);
      // Restore previous title
      document.title = self.documentTitle; 
   }
};

/*
* jQuery Responder
*/
jQuery.fn.respond = function(options) 
{
   mb.respond = []; // Extend the mediabrain object

   var defaults = {
      'contentSelector': "body",
      'scrollingVideoContainerSelector': window,
      callbacks: {
         'contentFixed': function(){},
         'contentReleased': function(){},
      },
   };

   var ui = this;
   var settings = $.extend({}, defaults, options);
   var _initialized = false;
   var _pluginDir = '/js/plugins/respond';


   function learn(element)
   {
      loadBehaviors(element, function()
      {
         behave(element)
      });

      _initialized = true;
   }

   return this.each(function() 
   {
      learn(this);
   });


   function loadBehaviors(element, callback)
   {
      // TODO - initialize a loader here to autoload _behaviors, based on settings or what is on the page?
      mb.respond._behaviors = [
         {  
            //Video Playback
            process: function(element)
            {
               async function playVideo(el) {
                  try {
                     await el.play();
                  } catch(err) {
                     console.log(err);
                  }
               }

               // Video scrolling playback control
               function monitorVideo()
               {
                  $('video.pauseWhenHidden').each(function() 
                  {
                     var $this = $(this);

                     if ((!$this[0].paused) && (!$this.visible(true))) 
                     {
                        $this[0].pause();
                     }
                     /*
                     else
                     {
                        playVideo($this[0]);
                     }
                     */
                  })
               };
               $(settings.scrollingVideoContainerSelector).on('scroll', monitorVideo);
               $(element).resize(monitorVideo);
            },
         },
         {
            // Inter-viewport communications
            process: function(element)
            {
               function receiveMessage(event)
               {
                  trigger(element, event.data);
               }
               element.addEventListener("message", receiveMessage, false);
            },
         },
      ];

      if (typeof callback == 'function')
      {
         callback();
      }
   }


   function behave(element)
   {
      $(mb.respond._behaviors).each(function(key, behavior)
      {
         behavior.process(element);
      });
   }

   
   function trigger(element, data)
   {
      /*
      * package = {
      *    'plugin': 'pluginName',
      *    'method': 'methodName',
      *    'payload': {}, // data passed as an argument to the method
      * }
      */

      if (typeof mb.respond._behaviors[data.plugin] === 'undefined')
      {
         // THIS NEEDS TO BE FINISHED
         /*
         mb.loadJs(_pluginDir + '/' + data.plugin + '.js', function() {
            execute(data);
         });
         */
      }
      else 
      {
         execute(data);
      }
   }


   function responsive(data)
   {
      return (typeof data.plugin === 'string') && 
      (typeof mb.respond._behaviors[data.plugin] !== 'undefined') &&
      (((typeof data.method !== 'undefined') ? (typeof data.method === 'string') : true) &&
         (typeof mb.respond._behaviors[data.plugin][data.method] === 'function'));
   }


   function execute(data)
   {
      if (responsive(data))
      {
         // Execute the request
         var result = {};
         if (typeof mb.respond._behaviors[data.plugin] === 'function')
         {
            result = mb.respond._behaviors[data.plugin](data);
         }
         else
         {
            result = mb.respond._behaviors[data.plugin][data.method](data.payload);
         }
         
         if (typeof data.callback == 'function')
         {
            data.callback(result);
         }
         else
         {
            return result;
         }
      }
      else
      {
         console.log('The plugin is not available - mb.respond._behaviors.' + data.plugin + '.' + data.method);
         handleError(data);
      }
   }



   function handleError(data)
   {
      console.log(data);
   }
}




/*
* IsVisible()
*/
!function(t){var i=t(window);t.fn.visible=function(t,e,o){if(!(this.length<1)){var r=this.length>1?this.eq(0):this,n=r.get(0),f=i.width(),h=i.height(),o=o?o:"both",l=e===!0?n.offsetWidth*n.offsetHeight:!0;if("function"==typeof n.getBoundingClientRect){var g=n.getBoundingClientRect(),u=g.top>=0&&g.top<h,s=g.bottom>0&&g.bottom<=h,c=g.left>=0&&g.left<f,a=g.right>0&&g.right<=f,v=t?u||s:u&&s,b=t?c||a:c&&a;if("both"===o)return l&&v&&b;if("vertical"===o)return l&&v;if("horizontal"===o)return l&&b}else{var d=i.scrollTop(),p=d+h,w=i.scrollLeft(),m=w+f,y=r.offset(),z=y.top,B=z+r.height(),C=y.left,R=C+r.width(),j=t===!0?B:z,q=t===!0?z:B,H=t===!0?R:C,L=t===!0?C:R;if("both"===o)return!!l&&p>=q&&j>=d&&m>=L&&H>=w;if("vertical"===o)return!!l&&p>=q&&j>=d;if("horizontal"===o)return!!l&&m>=L&&H>=w}}}}(jQuery);
