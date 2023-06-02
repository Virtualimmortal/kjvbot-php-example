mb.respond._behaviors.browser = {

   navigate: function(payload)
   {
      if (typeof payload.url === 'string')
         window.location = payload.url;
   },
}
