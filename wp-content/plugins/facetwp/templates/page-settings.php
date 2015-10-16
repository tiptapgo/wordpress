<?php

global $wpdb;

// An array of facet type objects
$facet_types = FWP()->helper->facet_types;

// Get taxonomy list
$taxonomies = get_taxonomies( array(), 'object' );

// Determine the excluded meta keys
$excluded_fields = apply_filters( 'facetwp_excluded_custom_fields', array(
    '_edit_last',
    '_edit_lock',
) );

$meta_keys = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} ORDER BY meta_key" );
$custom_fields = array_diff( $meta_keys, $excluded_fields );

// Export feature
$export = array();
$settings = FWP()->helper->settings_raw;

foreach ( $settings['facets'] as $facet ) {
    $export['facet-' . $facet['name']] = 'Facet - ' . $facet['label'];
}

foreach ( $settings['templates'] as $template ) {
    $export['template-' . $template['name']] = 'Template - '. $template['label'];
}

// Data sources
$sources = array(
    'posts' => array(
        'label' => __( 'Posts', 'fwp' ),
        'choices' => array(
            'post_type'         => __( 'Post Type', 'fwp' ),
            'post_date'         => __( 'Post Date', 'fwp' ),
            'post_modified'     => __( 'Post Modified', 'fwp' ),
            'post_title'        => __( 'Post Title', 'fwp' ),
            'post_author'       => __( 'Post Author', 'fwp' )
        )
    ),
    'taxonomies' => array(
        'label' => __( 'Taxonomies', 'fwp' ),
        'choices' => array()
    ),
    'custom_fields' => array(
        'label' => __( 'Custom Fields', 'fwp' ),
        'choices' => array()
    )
);

foreach ( $taxonomies as $tax ) {
    $sources['taxonomies']['choices'][ 'tax/' . $tax->name ] = $tax->labels->name;
}

foreach ( $custom_fields as $cf ) {
    $sources['custom_fields']['choices'][ 'cf/' . $cf ] = $cf;
}

$sources = apply_filters( 'facetwp_facet_sources', $sources );

?>

<script src="<?php echo FACETWP_URL; ?>/assets/js/event-manager.js?ver=<?php echo FACETWP_VERSION; ?>"></script>
<?php
foreach ( $facet_types as $class ) {
    $class->admin_scripts();
}
?>
<script src="<?php echo FACETWP_URL; ?>/assets/js/admin.js?ver=<?php echo FACETWP_VERSION; ?>"></script>
<link href="<?php echo FACETWP_URL; ?>/assets/css/admin.css?ver=<?php echo FACETWP_VERSION; ?>" rel="stylesheet">

<div class="facetwp-header">
    <span class="facetwp-logo" title="FacetWP">&nbsp;</span>
    <span class="facetwp-header-nav">
        <a class="facetwp-nav-tab" rel="welcome"><?php _e( 'Welcome', 'fwp' ); ?></a>
        <a class="facetwp-nav-tab" rel="facets"><?php _e( 'Facets', 'fwp' ); ?></a>
        <a class="facetwp-nav-tab" rel="templates"><?php _e( 'Templates', 'fwp' ); ?></a>
        <a class="facetwp-nav-tab" rel="settings"><?php _e( 'Settings', 'fwp' ); ?></a>
        <a class="facetwp-nav-tab" rel="support"><?php _e( 'Support', 'fwp' ); ?></a>
    </span>
</div>

<div class="wrap">

    <div class="facetwp-response"></div>
    <div class="facetwp-loading"></div>

    <!-- Welcome tab -->

    <div class="facetwp-content facetwp-content-welcome about-wrap">
        <h1>Welcome to FacetWP</h1>
        <div class="about-text">Thanks for choosing FacetWP. Below is a quick introduction to the plugin's key components - Facets and Templates.</div>
        <div class="wp-badge">Version <?php echo FACETWP_VERSION; ?></div>
        <div class="welcome-box-wrap">
            <div class="welcome-box">
                <h2>Facets</h2>
                <p>A facet is a UI element (checkboxes, dropdown, etc.) used to filter, or "drill-down", content.</p>
                <a class="button" href="https://facetwp.com/documentation/facet-configuration/" target="_blank">Learn more</a>
            </div>
            <div class="welcome-box">
                <h2>Templates</h2>
                <p>A FacetWP Template is responsible for loading (see <code>Query Arguments</code>) and displaying (see <code>Display Code</code>) the posts listing. A template is <strong>required</strong>, since facets use these posts to filter on.</p>
                <a class="button" href="https://facetwp.com/documentation/template-configuration/" target="_blank">Learn more</a>
            </div>
        </div>
    </div>

    <!-- Facets tab -->

    <div class="facetwp-content facetwp-content-facets">
        <div class="facetwp-action-buttons">
            <div style="float:right">
                <a class="button facetwp-rebuild"><?php _e( 'Re-index', 'fwp' ); ?></a>
                <a class="button-primary facetwp-save"><?php _e( 'Save Changes', 'fwp' ); ?></a>
            </div>
            <a class="button add-facet"><?php _e( 'Add Facet', 'fwp' ); ?></a>
            <div class="clear"></div>
        </div>

        <div class="facetwp-tabs">
            <ul></ul>
        </div>
        <div class="facetwp-facets"></div>
        <div class="clear"></div>
    </div>

    <!-- Templates tab -->

    <div class="facetwp-content facetwp-content-templates">
        <div class="facetwp-action-buttons">
            <div style="float:right">
                <a class="button-primary facetwp-save"><?php _e( 'Save Changes', 'fwp' ); ?></a>
            </div>
            <a class="button add-template"><?php _e( 'Add Template', 'fwp' ); ?></a>
            <div class="clear"></div>
        </div>

        <div class="facetwp-tabs">
            <ul></ul>
        </div>
        <div class="facetwp-templates"></div>
        <div class="clear"></div>
    </div>

    <!-- Settings tab -->

    <div class="facetwp-content facetwp-content-settings">
        <div class="facetwp-action-buttons">
            <div style="float:right">
                <a class="button-primary facetwp-save"><?php _e( 'Save Changes', 'fwp' ); ?></a>
            </div>
            <div class="clear"></div>
        </div>

        <div class="facetwp-settings-wrap">
            <table>
                <tr>
                    <td style="width:175px"><?php _e( 'License Key', 'fwp' ); ?></td>
                    <td>
                        <input type="text" class="facetwp-license" style="width:280px" value="<?php echo get_option( 'facetwp_license' ); ?>" />
                        <input type="button" class="button facetwp-activate" value="<?php _e( 'Activate', 'fwp' ); ?>" />
                        <div class="facetwp-activation-status field-notes"><?php echo $message; ?></div>
                    </td>
                </tr>
            </table>

            <!-- General settings -->

            <table style="width:100%">
                <tr>
                    <td style="width:175px; vertical-align:top">
                        <?php _e( 'Permalink Type', 'fwp' ); ?>
                        <div class="facetwp-tooltip">
                            <span class="icon-question">?</span>
                            <div class="facetwp-tooltip-content"><?php _e( 'How should permalinks be constructed?', 'fwp' ); ?></div>
                        </div>
                    </td>
                    <td>
                        <select class="facetwp-setting" data-name="permalink_type">
                            <option value="hash"><?php _e( 'URL Hash', 'fwp' ); ?></option>
                            <option value="get"><?php _e( 'GET variables', 'fwp' ); ?></option>
                        </select>
                        <div class="field-notes"><?php _e( 'GET variables are recommended.', 'fwp' ); ?></div>
                    </td>
                </tr>
                <tr>
                    <td style="width:175px; vertical-align:top">
                        <?php _e( 'Term URLs', 'fwp' ); ?>
                        <div class="facetwp-tooltip">
                            <span class="icon-question">?</span>
                            <div class="facetwp-tooltip-content"><?php _e( 'What should appear in the URL for taxonomy terms?', 'fwp' ); ?></div>
                        </div>
                    </td>
                    <td>
                        <select class="facetwp-setting" data-name="term_permalink">
                            <option value="term_id"><?php _e( 'Term ID', 'fwp' ); ?></option>
                            <option value="slug"><?php _e( 'Slug', 'fwp' ); ?></option>
                        </select>
                        <div class="field-notes"><?php _e( 'Please re-index after changing this value.', 'fwp' ); ?></div>
                    </td>
                </tr>
                <tr>
                    <td style="width:175px; vertical-align:top">
                        <?php _e( 'Thousands Separator', 'fwp' ); ?>
                    </td>
                    <td>
                        <input type="text" style="width:50px" class="facetwp-setting" data-name="thousands_separator" />
                    </td>
                </tr>
                <tr>
                    <td style="width:175px; vertical-align:top">
                        <?php _e( 'Decimal Separator', 'fwp' ); ?>
                    </td>
                    <td>
                        <input type="text" style="width:50px" class="facetwp-setting" data-name="decimal_separator" />
                    </td>
                </tr>
            </table>

            <!-- Migration -->

            <table style="width:100%">
                <tr>
                    <td style="width:175px; vertical-align:top">
                        <?php _e( 'Export', 'fwp' ); ?>
                    </td>
                    <td valign="top" style="width:260px">
                        <select class="export-items" multiple="multiple" style="width:250px; height:100px">
                            <?php foreach ( $export as $val => $label ) : ?>
                            <option value="<?php echo $val; ?>"><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div style="margin-top:5px"><a class="button export-submit"><?php _e( 'Export', 'fwp' ); ?></a></div>
                    </td>
                    <td valign="top">
                        <textarea class="export-code" placeholder="Loading..."></textarea>
                    </td>
                </tr>
            </table>

            <table style="width:100%">
                <tr>
                    <td style="width:175px; vertical-align:top">
                        <?php _e( 'Import', 'fwp' ); ?>
                    </td>
                    <td>
                        <div><textarea class="import-code" placeholder="<?php _e( 'Paste the import code here', 'fwp' ); ?>"></textarea></div>
                        <div><input type="checkbox" class="import-overwrite" /> <?php _e( 'Overwrite existing items?', 'fwp' ); ?></div>
                        <div style="margin-top:5px"><a class="button import-submit"><?php _e( 'Import', 'fwp' ); ?></a></div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Support tab -->

    <div class="facetwp-content facetwp-content-support">
        <?php include( FACETWP_DIR . '/templates/page-support.php' ); ?>
    </div>

    <!-- Hidden: clone settings -->

    <div class="facets-hidden">
        <div class="facetwp-facet">
            <table class="facetwp-table">
                <tr>
                    <td style="width:175px"><?php _e( 'Label', 'fwp' ); ?>:</td>
                    <td>
                        <input type="text" class="facet-label" value="" />
                        <input type="text" class="facet-name" value="" />
                    </td>
                </tr>
                <tr>
                    <td><?php _e( 'Facet type', 'fwp' ); ?>:</td>
                    <td>
                        <select class="facet-type">
                            <?php foreach ( $facet_types as $name => $class ) : ?>
                            <option value="<?php echo $name; ?>"><?php echo $class->label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr class="facetwp-show name-source">
                    <td>
                        <?php _e( 'Data source', 'fwp' ); ?>:
                    </td>
                    <td>
                        <select class="facet-source">
                            <?php foreach ( $sources as $group ) : ?>
                            <optgroup label="<?php echo $group['label']; ?>">
                                <?php foreach ( $group['choices'] as $val => $label ) : ?>
                                <option value="<?php echo esc_attr( $val ); ?>"><?php echo esc_html( $label ); ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
<?php
foreach ( $facet_types as $class ) {
    $class->settings_html();
}
?>
            </table>
            <a class="remove-facet"><?php _e( 'Delete Facet', 'fwp' ); ?></a>
        </div>
    </div>

    <div class="templates-hidden">
        <div class="facetwp-template">
            <div class="table-row">
                <div class="row-label">
                    <?php _e( 'Label', 'fwp' ); ?>:
                    <div class="facetwp-tooltip">
                        <span class="icon-question">?</span>
                        <div class="facetwp-tooltip-content">Use the template name (to the right of the label) when using the template shortcode</div>
                    </div>
                </div>
                <input type="text" class="template-label" value="" />
                <input type="text" class="template-name" value="" />
            </div>
            <div class="table-row">
                <div class="row-label">
                    <?php _e( 'Query Arguments', 'fwp' ); ?>:
                    <div class="facetwp-tooltip">
                        <span class="icon-question">?</span>
                        <div class="facetwp-tooltip-content">This box returns an array of <a href="http://codex.wordpress.org/Class_Reference/WP_Query" target="_blank">WP_Query</a> arguments that are used to fetch the initial batch of posts from the database.</div>
                    </div>
                </div>
                <textarea class="template-query"></textarea>
            </div>
            <div class="table-row">
                <div class="row-label">
                    <?php _e( 'Display Code', 'fwp' ); ?>:
                    <div class="facetwp-tooltip">
                        <span class="icon-question">?</span>
                        <div class="facetwp-tooltip-content">This is your template output. Using the <a href="http://codex.wordpress.org/The_Loop" target="_blank">WordPress Loop</a>, we iterate through our posts to display some HTML for each.</div>
                    </div>
                </div>
                <textarea class="template-template"></textarea>
            </div>
            <a class="remove-template"><?php _e( 'Delete Template', 'fwp' ); ?></a>
        </div>
    </div>
</div>
