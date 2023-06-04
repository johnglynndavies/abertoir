(function(){

  /**
   * Track outbound links and downloads
   */
  var links = document.querySelectorAll('a');

  for (i = 0; i < links.length; ++i) {
    links[i].addEventListener('click', function (event) {
      var href = event.currentTarget.href;

      if ( href ) {
        var href_lower = href.toLowerCase();

        if ( href.match(/.+\.([a-z0-9]{2,4})$/) ) {
          var href_extension = href_lower.replace(/.+\.([a-z0-9]{2,4})$/, "$1");
          
          if ( href_extension == "pdf" || href_extension == "doc" || href_extension == "docx" ) {
            gtag('event', 'click', {
              'event_category': 'downloads',
              'event_label': href,
              'transport_type': 'beacon'
            });
          }
        }

        if ( href_lower.substr(0, 4) == "http" ) {
          var domain = document.domain.replace("www.",'');

          if ( href_lower.indexOf(domain) !== -1 ) {
            return;
          }

          gtag('event', 'click', {
            'event_category': 'outbound',
            'event_label': href,
            'transport_type': 'beacon'
          });
        }
      }

    }, false);
  }

  /**
   * Track social sharing
   */
  var socialShareLinks = document.querySelectorAll('a.wp-block-social-link-anchor');

  for (i = 0; i < socialShareLinks.length; ++i) {
    socialShareLinks[i].addEventListener('click', function (event) {
      var location = window.location.href;
      var label = event.currentTarget.getAttribute('aria-label');
      var category = 'inquiry';
      if (label.indexOf('Share on ') !== -1) {
        category = 'share';
        label = label.split('Share on ')[1].toLowerCase();
      }
      else if (label.indexOf('Abertoir on ') !== -1) {
        label = label.split('Abertoir on ')[1].toLowerCase();
      }
      else if (label.indexOf('Email') == 0) {
        label = 'email';
      }

      gtag('event', 'social', {
        'event_category': category,
        'event_label': label,
        'transport_type': 'beacon'
      });

    }, false);
  }


})();
