(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  jQuery(function($) {
    var AutoLocateView, FiltersView, Location, LocationView, LocationsCollection, LocationsCollectionView, MapCanvasView, MapView, Marker, MarkerView, MarkersCollection, MarkersCollectionView, MetaView, Plotter, RadiusView, plotter;
    Plotter = (function() {
      Plotter.geocoder = new google.maps.Geocoder();

      Plotter.settings = listifyMapSettings;

      Plotter.loadedOnce = false;

      function Plotter() {
        this.filters = new FiltersView();
        if (Plotter.settings.displayMap) {
          this.map = new MapView({
            filters: this.filters
          });
        }
        if (!Plotter.settings.facewp) {
          this.meta = new MetaView({
            filters: this.filters
          });
        }
      }

      return Plotter;

    })();
    MetaView = (function(_super) {
      __extends(MetaView, _super);

      function MetaView() {
        this.viewToggle = __bind(this.viewToggle, this);
        this.setFound = __bind(this.setFound, this);
        this.initialize = __bind(this.initialize, this);
        return MetaView.__super__.constructor.apply(this, arguments);
      }

      MetaView.prototype.initialize = function(options) {
        if (options == null) {
          options = {};
        }
        this.filters = options.filters;
        this.setFound();
        return this.viewToggle();
      };

      MetaView.prototype.setFound = function() {
        $('div.job_listings').on('updated_results', (function(_this) {
          return function(event, results) {
            $('.results-found').text(results.found);
            return _this.filters.startup();
          };
        })(this));
        return $(document).on('facetwp-loaded', (function(_this) {
          return function() {
            return _this.filters.startup();
          };
        })(this));
      };

      MetaView.prototype.viewToggle = function() {
        var $sections, $toggle;
        $toggle = $('.archive-job_listing-toggle');
        $sections = $('.content-area, .job_listings-map-wrapper');
        return $toggle.on('click', function(e) {
          var target;
          e.preventDefault();
          $('body').toggleClass('map-toggled');
          $toggle.removeClass('active');
          $(this).addClass('active');
          target = $(this).data('toggle');
          $sections.hide().filter($(target)).show();
          $('html, body').animate({
            scrollTop: $('.archive-job_listing-toggle-wrapper').offset().top
          }, 1);
          return $('.job_listings-map-wrapper').trigger('map-toggled');
        });
      };

      return MetaView;

    })(Backbone.View);
    FiltersView = (function(_super) {
      __extends(FiltersView, _super);

      function FiltersView() {
        this.startup = __bind(this.startup, this);
        this.shutdown = __bind(this.shutdown, this);
        this.check = __bind(this.check, this);
        this.haltform = __bind(this.haltform, this);
        this.update = __bind(this.update, this);
        this.monitor = __bind(this.monitor, this);
        this.initialize = __bind(this.initialize, this);
        return FiltersView.__super__.constructor.apply(this, arguments);
      }

      FiltersView.prototype.target = $('div.job_listings');

      FiltersView.prototype.form = $('.job_filters');

      FiltersView.prototype.address = $('#search_location');

      FiltersView.prototype.lat = $('#search_lat');

      FiltersView.prototype.lng = $('#search_lng');

      FiltersView.prototype.use = $('#use_search_radius');

      FiltersView.prototype.submit = $('.update_results');

      FiltersView.prototype.initialize = function() {
        this.shutdown();
        this.locationsCollection = new LocationsCollection();
        this.locationsCollectionView = new LocationsCollectionView({
          collection: this.locationsCollection,
          filters: this
        });
        if (this.form.length) {
          this.autoLocateView = new AutoLocateView({
            filters: this,
            collectionView: this.locationsCollectionView
          });
          this.autoLocateView.render();
        }
        this.radiusView = new RadiusView({
          filters: this
        });
        this.radiusView.render();
        this.haltform();
        this.check();
        this.update();
        return this.monitor();
      };

      FiltersView.prototype.monitor = function() {
        return this.target.on('update_results', (function(_this) {
          return function(e, page, append) {
            return _this.shutdown();
          };
        })(this));
      };

      FiltersView.prototype.update = function() {
        return this.target.triggerHandler('update_results', [1, false]);
      };

      FiltersView.prototype.haltform = function() {
        return this.form.on('submit', (function(_this) {
          return function(e) {
            _this.shutdown();
            return e.preventDefault();
          };
        })(this));
      };

      FiltersView.prototype.check = function() {
        return this.target.on('update_results', (function(_this) {
          return function(e, page, append) {
            if (0 === _this.lat.val() && '' !== _this.address.val()) {
              e.stopImmediatePropagation();
              return _this.locationsCollectionView.generate();
            }
          };
        })(this));
      };

      FiltersView.prototype.shutdown = function() {
        this.submit.text(this.submit.data('refresh')).addClass('refreshing').attr('disabled', true);
        return $('.job_listings-map-wrapper').addClass('loading');
      };

      FiltersView.prototype.startup = function() {
        this.submit.text(this.submit.data('label')).removeClass('refreshing').attr('disabled', false);
        return $('ul.job_listings, .job_listings-map-wrapper').removeClass('loading');
      };

      return FiltersView;

    })(Backbone.View);
    AutoLocateView = (function(_super) {
      __extends(AutoLocateView, _super);

      function AutoLocateView() {
        this.find = __bind(this.find, this);
        this.render = __bind(this.render, this);
        this.bindActions = __bind(this.bindActions, this);
        this.initialize = __bind(this.initialize, this);
        return AutoLocateView.__super__.constructor.apply(this, arguments);
      }

      AutoLocateView.prototype.input = $('.search_location');

      AutoLocateView.prototype.initialize = function(options) {
        this.filters = options.filters;
        this.collectionView = options.collectionView;
        return this.bindActions();
      };

      AutoLocateView.prototype.bindActions = function() {
        return this.input.on('click', '.locate-me', (function(_this) {
          return function(e) {
            e.preventDefault();
            $('.locate-me').addClass('loading');
            _this.filters.shutdown();
            return _this.find();
          };
        })(this));
      };

      AutoLocateView.prototype.render = function() {
        return this.input.prepend('<i class="locate-me"></i>');
      };

      AutoLocateView.prototype.find = function() {
        var cv, error, filters, success;
        cv = this.collectionView;
        filters = this.filters;
        if (!navigator.geolocation) {
          return;
        }
        success = function(position) {
          var lat, lng;
          lat = position.coords.latitude;
          lng = position.coords.longitude;
          cv.set({
            'lat': lat,
            'lng': lng
          });
          return $('.locate-me').removeClass('loading');
        };
        error = function() {
          $('.locate-me').removeClass('loading');
          return filters.startup();
        };
        navigator.geolocation.getCurrentPosition(success, error);
        return this;
      };

      return AutoLocateView;

    })(Backbone.View);
    RadiusView = (function(_super) {
      __extends(RadiusView, _super);

      function RadiusView() {
        this.render = __bind(this.render, this);
        this.initialize = __bind(this.initialize, this);
        return RadiusView.__super__.constructor.apply(this, arguments);
      }

      RadiusView.prototype.wrapper = $('.search-radius-wrapper');

      RadiusView.prototype.use = $('#use_search_radius');

      RadiusView.prototype.slider = $('#search-radius');

      RadiusView.prototype.input = $('#search_radius');

      RadiusView.prototype.label = $('.search-radius-label .radi');

      RadiusView.prototype.defaults = {
        min: parseInt(Plotter.settings.searchRadius.min),
        max: parseInt(Plotter.settings.searchRadius.max),
        avg: parseInt(Plotter.settings.searchRadius["default"])
      };

      RadiusView.prototype.initialize = function(options) {
        if (options == null) {
          options = {};
        }
        return this.filters = options.filters;
      };

      RadiusView.prototype.render = function() {
        return this.slider.slider({
          value: this.defaults.avg,
          min: this.defaults.min,
          max: this.defaults.max,
          step: 1,
          slide: (function(_this) {
            return function(event, ui) {
              _this.input.val(ui.value);
              return _this.label.text(ui.value);
            };
          })(this),
          stop: (function(_this) {
            return function(event, ui) {
              return _this.filters.update();
            };
          })(this)
        });
      };

      return RadiusView;

    })(Backbone.View);
    LocationsCollectionView = (function(_super) {
      __extends(LocationsCollectionView, _super);

      function LocationsCollectionView() {
        this.render = __bind(this.render, this);
        this.set = __bind(this.set, this);
        this.placeChanged = __bind(this.placeChanged, this);
        this.generate = __bind(this.generate, this);
        this.check = __bind(this.check, this);
        this.initialize = __bind(this.initialize, this);
        return LocationsCollectionView.__super__.constructor.apply(this, arguments);
      }

      LocationsCollectionView.prototype.initialize = function(options) {
        var field;
        if (options == null) {
          options = {};
        }
        this.collection = options.collection;
        this.filters = options.filters;
        field = document.getElementById('search_location');
        if (!field) {
          return;
        }
        this.autocomplete = new google.maps.places.Autocomplete(field);
        $(field).unbind('change');
        google.maps.event.addListener(this.autocomplete, 'place_changed', this.placeChanged);
        $(field).keypress(function(e) {
          if (e.which === 13) {
            google.maps.event.trigger(this.autocomplete, 'place_changed');
            return false;
          }
        });
        this.listenTo(this.collection, 'add', this.render);
        return this.check();
      };

      LocationsCollectionView.prototype.check = function() {
        if ('' !== this.filters.address.val()) {
          return this.generate();
        }
      };

      LocationsCollectionView.prototype.generate = function() {
        return this.set({
          id: this.filters.address.val()
        });
      };

      LocationsCollectionView.prototype.placeChanged = function(place) {
        this.filters.shutdown();
        place = this.autocomplete.getPlace();
        if (place.geometry != null) {
          return this.set({
            id: place.formatted_address,
            lat: place.geometry.location.lat(),
            lng: place.geometry.location.lng()
          });
        } else {
          return this.set({
            id: place.name
          });
        }
      };

      LocationsCollectionView.prototype.set = function(atts) {
        return this.collection.add(atts);
      };

      LocationsCollectionView.prototype.render = function(location) {
        return location = new LocationView({
          model: location,
          filters: this.filters
        });
      };

      return LocationsCollectionView;

    })(Backbone.View);
    LocationView = (function(_super) {
      __extends(LocationView, _super);

      function LocationView() {
        this.geocode = __bind(this.geocode, this);
        this.render = __bind(this.render, this);
        this.initialize = __bind(this.initialize, this);
        return LocationView.__super__.constructor.apply(this, arguments);
      }

      LocationView.prototype.model = Location;

      LocationView.prototype.initialize = function(options) {
        if (options == null) {
          options = {};
        }
        this.filters = options.filters;
        this.listenTo(this.model, 'change', this.render);
        if (this.model.get('id') && this.model.get('lat')) {
          this.render();
          return this.filters.update();
        } else {
          return this.geocode().done((function(_this) {
            return function(location) {
              return _this.model.set({
                'id': location.formatted_address,
                'lat': location.geometry.location.lat(),
                'lng': location.geometry.location.lng()
              });
            };
          })(this)).always((function(_this) {
            return function() {
              return _this.filters.update();
            };
          })(this));
        }
      };

      LocationView.prototype.render = function() {
        this.filters.lat.val(this.model.get('lat'));
        this.filters.lng.val(this.model.get('lng'));
        return this.filters.address.val(this.model.get('id'));
      };

      LocationView.prototype.geocode = function() {
        var args;
        this.deferred = $.Deferred();
        if (this.model.get('id')) {
          args = {
            'address': this.model.get('id')
          };
        } else {
          args = {
            'latLng': new google.maps.LatLng(this.model.get('lat'), this.model.get('lng'))
          };
        }
        Plotter.geocoder.geocode(args, (function(_this) {
          return function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
              return _this.deferred.resolve(results[0]);
            } else {
              return _this.deferred.reject();
            }
          };
        })(this));
        return this.deferred.promise();
      };

      return LocationView;

    })(Backbone.View);
    Location = (function(_super) {
      __extends(Location, _super);

      function Location() {
        return Location.__super__.constructor.apply(this, arguments);
      }

      return Location;

    })(Backbone.Model);
    LocationsCollection = (function(_super) {
      __extends(LocationsCollection, _super);

      function LocationsCollection() {
        return LocationsCollection.__super__.constructor.apply(this, arguments);
      }

      LocationsCollection.prototype.model = Location;

      return LocationsCollection;

    })(Backbone.Collection);
    MapView = (function(_super) {
      __extends(MapView, _super);

      function MapView() {
        this.initialize = __bind(this.initialize, this);
        return MapView.__super__.constructor.apply(this, arguments);
      }

      MapView.prototype.bounds = new google.maps.LatLngBounds();

      MapView.prototype.infobubble = new InfoBubble({
        backgroundClassName: 'map-marker-info',
        borderRadius: 4,
        padding: 15,
        borderColor: '#ffffff',
        shadowStyle: 0,
        minHeight: 110,
        maxHeight: 110,
        minWidth: 225,
        maxWidth: 275,
        hideCloseButton: true,
        flat: true,
        anchor: RichMarkerPosition.BOTTOM
      });

      MapView.prototype.clusterer = new MarkerClusterer(null, [], {
        ignoreHidden: true
      });

      MapView.prototype.initialize = function(options) {
        if (options == null) {
          options = {};
        }
        this.filters = options.filters;
        this.canvas = new MapCanvasView({
          map: this,
          filters: this.filters
        });
        this.markersCollection = new MarkersCollection();
        this.markersCollectionView = new MarkersCollectionView({
          collection: this.markersCollection,
          map: this
        });
        $('.job_listings').on('updated_results', (function(_this) {
          return function(event, results) {
            _this.markersCollectionView.load(event);
            return plotter.loadedOnce = true;
          };
        })(this));
        return $(document).on('facetwp-loaded facetwp-refresh', (function(_this) {
          return function(event) {
            _this.markersCollectionView.load();
            return plotter.loadedOnce = true;
          };
        })(this));
      };

      return MapView;

    })(Backbone.View);
    MapCanvasView = (function(_super) {
      __extends(MapCanvasView, _super);

      function MapCanvasView() {
        this.showDefault = __bind(this.showDefault, this);
        this.hideBubble = __bind(this.hideBubble, this);
        this.fitbounds = __bind(this.fitbounds, this);
        this.clusterOverlay = __bind(this.clusterOverlay, this);
        this.createClusterer = __bind(this.createClusterer, this);
        this.resize = __bind(this.resize, this);
        this.mapHeight = __bind(this.mapHeight, this);
        this.canvas = __bind(this.canvas, this);
        this.initialize = __bind(this.initialize, this);
        return MapCanvasView.__super__.constructor.apply(this, arguments);
      }

      MapCanvasView.prototype.initialize = function(options) {
        if (options == null) {
          options = {};
        }
        this.map = options.map;
        this.filters = options.filters;
        google.maps.event.addDomListener(window, 'load', this.canvas);
        return $('.job_listings-map-wrapper').on('map-toggled', this.resize);
      };

      MapCanvasView.prototype.canvas = function() {
        this.el = document.getElementById('job_listings-map-canvas');
        if (!this.el) {
          return this.loaded = true;
        }
        this.settings = Plotter.settings.mapOptions;
        this.opts = {
          zoom: parseInt(this.settings.zoom),
          maxZoom: parseInt(this.settings.maxZoom),
          styles: [
            {
              featureType: "poi",
              elementType: "labels",
              stylers: [
                {
                  visibility: "off"
                }
              ]
            }
          ]
        };
        if (this.settings.center) {
          this.defaultCenter = new google.maps.LatLng(this.settings.center[0], this.settings.center[1]);
        } else {
          this.defaultCenter = new google.maps.LatLng(41.850033, -87.6500523);
        }
        this.opts.center = this.defaultCenter;
        this.obj = new google.maps.Map(this.el, this.opts);
        this.createClusterer();
        google.maps.event.addListener(this.obj, 'click', this.hideBubble);
        google.maps.event.addListener(this.obj, 'zoom_changed', this.hideBubble);
        $(window).on('resize', this.resize);
        this.mapHeight();
        this.loaded = true;
        return this.obj;
      };

      MapCanvasView.prototype.mapHeight = function() {
        var height;
        if (!$('body').hasClass('fixed-map')) {
          return;
        }
        if ($(window).outerWidth() > 993 && $('body').hasClass('fixed-map')) {
          height = $(window).outerHeight() - $('.site-header').outerHeight();
        } else if ($(window).outerWidth() < 993) {
          height = $(window).outerHeight() - $('.archive-job_listing-toggle-wrapper').outerHeight();
        }
        if ($('body').hasClass('admin-bar') && $('body').hasClass('fixed-map')) {
          height = height - $('#wpadminbar').outerHeight();
        }
        return $('.job_listings-map-wrapper, .job_listings-map').css('height', height);
      };

      MapCanvasView.prototype.resize = function() {
        this.mapHeight();
        google.maps.event.trigger(this.obj, 'resize');
        return this.fitbounds();
      };

      MapCanvasView.prototype.createClusterer = function() {
        this.map.clusterer.setMap(this.obj);
        this.map.clusterer.setMaxZoom(this.opts.maxZoom);
        this.map.clusterer.setGridSize(parseInt(this.settings.gridSize));
        return google.maps.event.addListener(this.map.clusterer, 'click', this.clusterOverlay);
      };

      MapCanvasView.prototype.clusterOverlay = function(c) {
        var content, markers, zoom;
        markers = c.getMarkers();
        zoom = this.obj.getZoom();
        if (zoom < this.opts.maxZoom) {
          return;
        }
        content = _.map(markers, function(marker) {
          return _.template($('#infoBubbleTemplate').html(), marker.meta);
        });
        return $.magnificPopup.open({
          items: {
            src: '<div class="cluster-overlay popup">' + content.join('') + '</div>',
            type: 'inline'
          }
        });
      };

      MapCanvasView.prototype.fitbounds = function() {
        return this.obj.fitBounds(this.map.bounds);
      };

      MapCanvasView.prototype.hideBubble = function() {
        return this.map.infobubble.close();
      };

      MapCanvasView.prototype.showDefault = function() {
        var last;
        if (_.isUndefined(this.obj)) {
          return;
        }
        if ('' === this.filters.address) {
          this.obj.setCenter(this.opts.center);
        } else {
          last = this.filters.locationsCollection.last();
          this.obj.setCenter(new google.maps.LatLng(last.get('lat'), last.get('lng')));
        }
        return this.obj.setZoom(this.opts.zoom);
      };

      return MapCanvasView;

    })(Backbone.View);
    MarkersCollectionView = (function(_super) {
      __extends(MarkersCollectionView, _super);

      function MarkersCollectionView() {
        this.resize = __bind(this.resize, this);
        this.setClusterer = __bind(this.setClusterer, this);
        this.clearClusterer = __bind(this.clearClusterer, this);
        this.clearBounds = __bind(this.clearBounds, this);
        this.fitBounds = __bind(this.fitBounds, this);
        this.removeOld = __bind(this.removeOld, this);
        this.render = __bind(this.render, this);
        this.parseResults = __bind(this.parseResults, this);
        this.load = __bind(this.load, this);
        this.listen = __bind(this.listen, this);
        this.initialize = __bind(this.initialize, this);
        return MarkersCollectionView.__super__.constructor.apply(this, arguments);
      }

      MarkersCollectionView.prototype.initialize = function(options) {
        if (options == null) {
          options = {};
        }
        this.collection = options.collection;
        this.map = options.map;
        return google.maps.event.addDomListener(window, 'load', this.listen);
      };

      MarkersCollectionView.prototype.listen = function() {
        if (_.isUndefined(this.map.canvas.obj)) {
          return this;
        }
        this.listenTo(this.collection, 'add', this.render);
        this.listenTo(this.collection, 'reset', this.removeOld);
        if (Plotter.settings.useClusters === '1') {
          this.listenTo(this.collection, 'markers-reset', this.clearClusterer);
          this.listenTo(this.collection, 'markers-added', this.setClusterer);
        }
        this.listenTo(this.collection, 'markers-reset', this.clearBounds);
        this.listenTo(this.collection, 'markers-added', this.fitBounds);
        return this.listenTo(this.collection, 'markers-added', this.resize);
      };

      MarkersCollectionView.prototype.load = function(event) {
        var data;
        data = this.parseResults(event);
        this.collection.reset();
        if (_.isEmpty(data)) {
          return this.map.canvas.showDefault();
        } else {
          this.collection.set(data);
          return this.collection.trigger('markers-added');
        }
      };

      MarkersCollectionView.prototype.parseResults = function(event) {
        var data, html;
        if (!_.isUndefined(event && !_.isUndefined(event.target))) {
          html = $(event.target).find('ul.job_listings').first().find('.type-job_listing');
        } else {
          html = $('ul.job_listings').first().find('.type-job_listing');
        }
        data = _.map(html, function(i) {
          return $(i).data();
        });
        return data = _.filter(data, function(i) {
          return _.has(i, 'latitude');
        });
      };

      MarkersCollectionView.prototype.render = function(marker) {
        var markerview;
        markerview = new MarkerView({
          model: marker,
          map: this.map
        });
        this.map.bounds.extend(marker.position());
        return markerview.add();
      };

      MarkersCollectionView.prototype.removeOld = function(collection, opts) {
        _.each(opts.previousModels, function(model) {
          return model.trigger('hide', model);
        });
        return this.collection.trigger('markers-reset');
      };

      MarkersCollectionView.prototype.fitBounds = function() {
        var autofit;
        autofit = parseInt(Plotter.settings.autoFit);
        if (autofit === 1 || Plotter.loadedOnce === true) {
          return this.map.canvas.fitbounds();
        }
      };

      MarkersCollectionView.prototype.clearBounds = function() {
        return this.map.bounds = new google.maps.LatLngBounds();
      };

      MarkersCollectionView.prototype.clearClusterer = function() {
        return this.map.clusterer.clearMarkers();
      };

      MarkersCollectionView.prototype.setClusterer = function() {
        var markers;
        markers = this.collection.map(function(model) {
          return model.get('obj');
        });
        this.map.clusterer.addMarkers(markers);
        return this.map.canvas.obj.setZoom(this.map.canvas.obj.getZoom() + 1);
      };

      MarkersCollectionView.prototype.resize = function() {
        return google.maps.event.trigger(this.map.canvas.obj, 'resize');
      };

      return MarkersCollectionView;

    })(Backbone.View);
    MarkerView = (function(_super) {
      __extends(MarkerView, _super);

      function MarkerView() {
        this.remove = __bind(this.remove, this);
        this.add = __bind(this.add, this);
        this.renderInfoBubble = __bind(this.renderInfoBubble, this);
        this.initialize = __bind(this.initialize, this);
        return MarkerView.__super__.constructor.apply(this, arguments);
      }

      MarkerView.prototype.template = $('#pinTemplate').html();

      MarkerView.prototype.templateInfoBubble = $('#infoBubbleTemplate').html();

      MarkerView.prototype.initialize = function(options) {
        var trigger;
        if (options == null) {
          options = {};
        }
        this.map = options.map;
        this.defaults = {
          flat: true,
          draggable: false,
          position: this.model.position(),
          content: _.template(this.template, this.model.toJSON()),
          meta: this.model.toJSON()
        };
        this.marker = new RichMarker(this.defaults);
        this.model.set('obj', this.marker);
        this.listenTo(this.model, 'hide', this.remove);
        trigger = Plotter.settings.trigger;
        if ($(window).outerWidth() <= 992) {
          trigger = 'click';
        }
        return google.maps.event.addListener(this.model.get('obj'), trigger, this.renderInfoBubble);
      };

      MarkerView.prototype.renderInfoBubble = function() {
        if (this.map.infobubble.isOpen_ && this.map.infobubble.anchor === this.model.get('obj')) {
          return;
        }
        this.map.infobubble.setContent(_.template(this.templateInfoBubble, this.model.toJSON()));
        return this.map.infobubble.open(this.map.canvas.obj, this.model.get('obj'));
      };

      MarkerView.prototype.add = function() {
        return this.model.get('obj').setMap(this.map.canvas.obj);
      };

      MarkerView.prototype.remove = function() {
        return this.model.get('obj').setMap(null);
      };

      return MarkerView;

    })(Backbone.View);
    Marker = (function(_super) {
      __extends(Marker, _super);

      function Marker() {
        this.position = __bind(this.position, this);
        return Marker.__super__.constructor.apply(this, arguments);
      }

      Marker.prototype["default"] = {
        id: '',
        obj: '',
        lat: '',
        lng: '',
        title: ''
      };

      Marker.prototype.position = function() {
        return new google.maps.LatLng(this.get('latitude'), this.get('longitude'));
      };

      return Marker;

    })(Backbone.Model);
    MarkersCollection = (function(_super) {
      __extends(MarkersCollection, _super);

      function MarkersCollection() {
        return MarkersCollection.__super__.constructor.apply(this, arguments);
      }

      MarkersCollection.prototype.model = Marker;

      return MarkersCollection;

    })(Backbone.Collection);
    InfoBubble.prototype.getAnchorHeight_ = function() {
      return 55;
    };
    return plotter = new Plotter();
  });

}).call(this);

//# sourceMappingURL=app.js.map
