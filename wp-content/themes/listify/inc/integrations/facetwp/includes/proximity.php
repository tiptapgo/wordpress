<?php

class FacetWP_Facet_Proximity
{

    public $ordered_posts;


    function __construct() {
        $this->label = __( 'Proximity', 'listify' );
    }


    /**
     * Generate the facet HTML
     */
    function render( $params ) {

        $output = '';
        $value = $params['selected_values'];
        $lat = empty( $value[0] ) ? '' : $value[0];
        $lng = empty( $value[1] ) ? '' : $value[1];
        $chosen_radius = empty( $value[2] ) ? '' : $value[2];
        $location_name = empty( $value[3] ) ? '' : urldecode( $value[3] );
        $output .= '<input type="text" id="facetwp-location" value="' . $location_name . '" placeholder="' . __( 'Enter location...', 'listify' ) . '" />';
        $output .= '<select id="facetwp-radius">';
        foreach ( apply_filters( 'listify_facetwp_proximity_radius', array( 5, 10, 25, 50, 100 ) ) as $radius ) {
            $selected = ( $chosen_radius == $radius ) ? ' selected' : '';
            $output .= "<option value=\"$radius\"$selected>$radius miles</option>";
        }
        $output .= '</select>';
        $output .= '<div style="display:none">';
        $output .= '<input type="text" class="facetwp-lat" value="' . $lat . '" />';
        $output .= '<input type="text" class="facetwp-lng" value="' . $lng . '" />';
        $output .= '</div>';
        $output .= '<input type="button" class="facetwp-reset" value="Reset" />';
        $output .= '<input type="button" class="facetwp-update" value="Apply" />';
        return $output;
    }


    /**
     * Filter the query based on selected values
     */
    function filter_posts( $params ) {
        global $wpdb;

        $facet = $params['facet'];
        $selected_values = $params['selected_values'];

        if ( empty( $selected_values ) || empty( $selected_values[0] ) ) {
            return 'continue';
        }

        $lat = (float) $selected_values[0];
        $lng = (float) $selected_values[1];
        $radius = (int) $selected_values[2];

        // Lat = facet_value
        // Lng = facet_display_value
        // TODO: optimize
        $sql = "
        SELECT DISTINCT post_id,
        ( 3959 * acos( cos( radians( $lat ) ) * cos( radians( facet_value ) ) * cos( radians( facet_display_value ) - radians( $lng ) ) + sin( radians( $lat ) ) * sin( radians( facet_value ) ) ) ) AS distance
        FROM {$wpdb->prefix}facetwp_index
        WHERE facet_name = '{$facet['name']}'
        HAVING distance < $radius
        ORDER BY distance";

        $this->ordered_posts = $wpdb->get_col( $sql );
        return $this->ordered_posts;
    }


    /**
     * Output any admin scripts
     */
    function admin_scripts() {
?>
<script>
(function($) {
    wp.hooks.addAction('facetwp/load/proximity', function($this, obj) {
        $this.find('.facet-source').val(obj.source);
    });

    wp.hooks.addFilter('facetwp/save/proximity', function($this, obj) {
        obj['source'] = $this.find('.facet-source').val();
        return obj;
    });

    wp.hooks.addAction('facetwp/change/proximity', function($this) {
    });
})(jQuery);
</script>
<?php
    }


    /**
     * Output any front-end scripts
     */
    function front_scripts() {
?>
<script>

(function($) {
    $(document).on('facetwp-loaded', function() {
        var place;
        var input = document.getElementById('facetwp-location');
        var autocomplete = new google.maps.places.Autocomplete(input);

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            place = autocomplete.getPlace();
            $('.facetwp-lat').val(place.geometry.location.lat());
            $('.facetwp-lng').val(place.geometry.location.lng());
        });

        $(document).on('click', '#facetwp-location', function() {
            $(this).val('');
        });
    });
})(jQuery);

</script>
<script>
(function($) {
    wp.hooks.addAction('facetwp/refresh/proximity', function($this, facet_name) {
        var lat = $this.find('.facetwp-lat').val();
        var lng = $this.find('.facetwp-lng').val();
        var radius = $this.find('#facetwp-radius').val();
        var location = encodeURIComponent($this.find('#facetwp-location').val());
        FWP.facets[facet_name] = ('' != lat && 'undefined' != typeof lat) ?
            [lat, lng, radius, location] : [];
    });

    wp.hooks.addAction('facetwp/ready', function() {
        $(function() {
            $(document).on('click', '.facetwp-update', function() {
                FWP.refresh();
            });

            $(document).on('click', '.facetwp-reset', function() {
                var $parent = $(this).closest('.facetwp-facet');
                $parent.find('.facetwp-lat').val('');
                $parent.find('.facetwp-lng').val('');
                FWP.refresh();
            });
        });
    });
})(jQuery);
</script>
<?php
    }


    /**
     * Output admin settings HTML
     */
    function settings_html() {
    }
}
