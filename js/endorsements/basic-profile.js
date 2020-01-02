define(function(require) {
   var lightbox = require('elgg/lightbox');
   var spinner = require('elgg/spinner');
    
   lightbox.open({
      html: '<p>Hello world!</p>',
      onClosed: function() {
         lightbox.open({
            onLoad: spinner.start,
            onComplete: spinner.stop,
            photo: true,
            href: 'https://elgg.org/cache/1457904417/default/community_theme/graphics/logo.png',
         });
      }
   });
});