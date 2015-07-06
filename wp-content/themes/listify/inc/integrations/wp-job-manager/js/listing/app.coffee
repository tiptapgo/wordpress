class ListifySingleMap
  constructor: () ->
    @canvas = 'listing-contact-map'

    if ! document.getElementById( @canvas ) then return;
  
    @setOptions()
    @setupMap()
    @setMarker()
  
  setOptions: =>
    @options = listifySingleMap;
 
    @latlng = new google.maps.LatLng @options.lat, @options.lng
    @zoom = parseInt @options.zoom
  
    @mapOptions = {
      zoom: @zoom
      center: @latlng
      scrollwheel: false
      draggable: false
    }
  
  setupMap: =>
    @map = new google.maps.Map document.getElementById( @canvas ), @mapOptions
  
  setMarker: =>
    @marker = new RichMarker(
      position: @latlng
      flat: true
      draggable: false
      content: '<div class="map-marker type-' + @options.term + '"><i class="' + @options.icon + '"></i></div>'
    ) 

    @marker.setMap @map
  
initialize = () ->
  new ListifySingleMap()
  
google.maps.event.addDomListener window, 'load', initialize

jQuery ($) ->
  class ListifyListingComments
    constructor: ->
      @bindActions()

    bindActions: =>
      $( '.comment-sorting-filter' ).on 'change', (e) ->
        $(@).closest( 'form' ).submit()

      $( '#respond .stars-rating .star' ).on 'click', (e) =>
        e.preventDefault()

        @toggleStars(e.target)

    toggleStars: (el) =>
      $( '#respond .stars-rating .star' ).removeClass 'active'

      el = $(el);
      el.addClass 'active'

      rating = el.data 'rating'

      if $( '#comment_rating' ).length == 0
        $( '.form-submit' ).append $( '<input />' ).attr({ type: 'hidden', id: 'comment_rating', name: 'comment_rating', value: rating })
      else
        $( '#comment_rating' ).val rating

  new ListifyListingComments()
