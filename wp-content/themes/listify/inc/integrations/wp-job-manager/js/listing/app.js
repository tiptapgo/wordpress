(function() {
  var ListifySingleMap, initialize,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  ListifySingleMap = (function() {
    function ListifySingleMap() {
      this.setMarker = __bind(this.setMarker, this);
      this.setupMap = __bind(this.setupMap, this);
      this.setOptions = __bind(this.setOptions, this);
      this.canvas = 'listing-contact-map';
      if (!document.getElementById(this.canvas)) {
        return;
      }
      this.setOptions();
      this.setupMap();
      this.setMarker();
    }

    ListifySingleMap.prototype.setOptions = function() {
      this.options = listifySingleMap;
      this.latlng = new google.maps.LatLng(this.options.lat, this.options.lng);
      this.zoom = parseInt(this.options.zoom);
      return this.mapOptions = {
        zoom: this.zoom,
        center: this.latlng,
        scrollwheel: false,
        draggable: false
      };
    };

    ListifySingleMap.prototype.setupMap = function() {
      return this.map = new google.maps.Map(document.getElementById(this.canvas), this.mapOptions);
    };

    ListifySingleMap.prototype.setMarker = function() {
      this.marker = new RichMarker({
        position: this.latlng,
        flat: true,
        draggable: false,
        content: '<div class="map-marker type-' + this.options.term + '"><i class="' + this.options.icon + '"></i></div>'
      });
      return this.marker.setMap(this.map);
    };

    return ListifySingleMap;

  })();

  initialize = function() {
    return new ListifySingleMap();
  };

  google.maps.event.addDomListener(window, 'load', initialize);

  jQuery(function($) {
    var ListifyListingComments;
    ListifyListingComments = (function() {
      function ListifyListingComments() {
        this.toggleStars = __bind(this.toggleStars, this);
        this.bindActions = __bind(this.bindActions, this);
        this.bindActions();
      }

      ListifyListingComments.prototype.bindActions = function() {
        $('.comment-sorting-filter').on('change', function(e) {
          return $(this).closest('form').submit();
        });
        return $('#respond .stars-rating .star').on('click', (function(_this) {
          return function(e) {
            e.preventDefault();
            return _this.toggleStars(e.target);
          };
        })(this));
      };

      ListifyListingComments.prototype.toggleStars = function(el) {
        var rating;
        $('#respond .stars-rating .star').removeClass('active');
        el = $(el);
        el.addClass('active');
        rating = el.data('rating');
        if ($('#comment_rating').length === 0) {
          return $('.form-submit').append($('<input />').attr({
            type: 'hidden',
            id: 'comment_rating',
            name: 'comment_rating',
            value: rating
          }));
        } else {
          return $('#comment_rating').val(rating);
        }
      };

      return ListifyListingComments;

    })();
    return new ListifyListingComments();
  });

}).call(this);

//# sourceMappingURL=app.js.map
