<?php


// Register two new custom fields: Name & description to
// additional info tab within the store edit screen in wp
function fujinon_custom_meta_box_fields( $meta_fields ) : array
{

    $meta_fields[__( 'Additional Information', 'wpsl' )] = array(
        'phone' => array(
            'label' => __( 'Tel', 'wpsl' )
        ),
        'fax' => array(
            'label' => __( 'Fax', 'wpsl' )
        ),
        'email' => array(
            'label' => __( 'Email', 'wpsl' )
        ),
        'url' => array(
            'label' => __( 'Url', 'wpsl' )
        ),
        'name' => array(
            'label' => __( 'Name', 'wpsl' )
        ),
        'description' => array(
            'label' => __( 'Description', 'wpsl' )
        ),
    );

    return $meta_fields;
}

add_filter( 'wpsl_meta_box_fields', 'fujinon_custom_meta_box_fields' );




// Add our two new fields to the JSON frontend data, so we can use it.
function fujinon_custom_frontend_meta_fields( $store_fields ) : array
{

    $store_fields['wpsl_name'] = array(
        'name' => 'name',
        'type' => 'string'
    );

    $store_fields['wpsl_description'] = array(
        'name' => 'description',
        'type' => 'string'
    );

    return $store_fields;
}

add_filter( 'wpsl_frontend_meta_fields', 'fujinon_custom_frontend_meta_fields' );





// Custom template for the main WPSL shortcode.
function fujinon_custom_wpsl_templates( $templates ) : array
{

    /**
     * The 'id' is for internal use and must be unique ( since 2.0 ).
     * The 'name' is used in the template dropdown on the settings page.
     * The 'path' points to the location of the custom template,
     * in this case the folder of your active theme.
     */
    $templates[] = array (
        'id'   => 'fujinon-custom',
        'name' => 'Fujinon - Custom',
        'path' => get_stylesheet_directory() . '/' . 'wpsl-templates/custom.php',
    );

    return $templates;
}

add_filter( 'wpsl_templates', 'fujinon_custom_wpsl_templates' );



// Listing template in the list view next to the map
function fujinon_custom_wpsl_listing_template()
{

    global $wpsl, $wpsl_settings;

    $listing_template = '<li class="wpsl-store-list-item" data-store-id="<%= id %>">' . "\r\n";
    $listing_template .= "\t\t" . '<div class="wpsl-store-location wpsl-store-details">' . "\r\n";
    $listing_template .= "\t\t\t" . '<p>' . "\r\n";
    $listing_template .= "\t\t\t\t" . wpsl_store_header_template( 'listing' ) . "\r\n"; // Check which header format we use
    $listing_template .= "\t\t\t" . '</p>' . "\r\n";
    $listing_template .= "\t\t" . '</div>' . "\r\n";
    $listing_template .= "\t" . '</li>';

    return $listing_template;
}

add_filter( 'wpsl_listing_template', 'fujinon_custom_wpsl_listing_template' );





// Adding custom category markers for each store on the map
function fujinon_custom_info_window_meta_fields( $meta_fields, $store_id )
{

    $terms = wp_get_post_terms( $store_id, 'wpsl_store_category' );

    if ( $terms ) {
        if ( !is_wp_error( $terms ) ) {
            $meta_fields['categoryMarkerUrl'] = get_field( 'wpsl_category_marker', $terms[0]->taxonomy . '_' . $terms[0]->term_id );
        }
    }

    return $meta_fields;
}
add_filter( 'wpsl_cpt_info_window_meta_fields', 'fujinon_custom_info_window_meta_fields', 10, 2 );




// Adding custom category markers for each store on the map
function fujinon_custom_wpsl_meta( $store_meta, $store_id )
{

    $terms = wp_get_post_terms( $store_id, 'wpsl_store_category' );

    if ( $terms ) {
        if ( !is_wp_error( $terms ) ) {
            if ( isset( $_GET['filter'] ) && $_GET['filter'] ) {
                $filter_ids = explode( ',', $_GET['filter'] );

                foreach ( $terms as $term ) {
                    if ( in_array( $term->term_id, $filter_ids ) ) {
                        $cat_marker = get_field( 'wpsl_category_marker', $terms[0]->taxonomy . '_' . $terms[0]->term_id );

                        if ( $cat_marker ) {
                            $store_meta['categoryMarkerUrl'] = $cat_marker;
                        }
                    }
                }
            } else {
                $store_meta['categoryMarkerUrl'] = get_field( 'wpsl_category_marker', $terms[0]->taxonomy . '_' . $terms[0]->term_id );
            }
        }
    }

    return $store_meta;
}
add_filter( 'wpsl_store_meta', 'fujinon_custom_wpsl_meta', 10, 2 );



// Custom info window template on the main map when clicking on a store.
function fujinon_custom_info_window_template()
{

    global $wpsl_settings, $wpsl;

    $info_window_template = '<div data-store-id="<%= id %>" class="wpsl-info-window">' . "\r\n";

    // Column One
    $info_window_template .= "\t\t" . '<div class="info-column column-one">' . "\r\n";
    $info_window_template .= "\t\t\t" . '<h3 class="store-name">' . wpsl_store_header_template() . '</h3>' . "\r\n";
    $info_window_template .= "\t\t\t" . '<p class="store-address">
                                           <a target="_blank" rel="noopener noreferrer" href="https://maps.google.com/?q=<%= address %> <% if ( address2 ) { print(address2) } %>, <%= city %>, <%= state %>, <%= zip %>, <%= country %>">
                                             <% if ( address ) { print(address,) } %> <% if ( address2 ) { print(address2,) } %>' . wpsl_address_format_placeholders() . '<% print(", " + country) %>
                                           </a>
                                         </p>' . "\r\n";
    $info_window_template .= "\t\t\t" . '<% if ( phone ) { %>' . "\r\n";
    $info_window_template .= "\t\t\t" . '<p class="phone"><strong>T:</strong> 
                                            <a href="tel:<%= phone %>"><%= phone %></p>' . "\r\n";
    $info_window_template .= "\t\t\t" . '<% } %>' . "\r\n";
    $info_window_template .= "\t\t\t" . '<% if ( url ) { %>' . "\r\n";
    $info_window_template .= "\t\t\t" . '<p class="website"><strong>W:</strong> 
                                            <a target="_blank" rel="noopener noreferrer" href="<%= url %>">Website</a>
                                         </p>' . "\r\n";
    $info_window_template .= "\t\t\t" . '<% } %>' . "\r\n";
    $info_window_template .= "\t\t" . '</div>' . "\r\n";


    // Column Two
    $info_window_template .= "\t\t" . '<div class="info-column column-two">' . "\r\n";
    $info_window_template .= "\t\t\t" . '<div class="store-thumb">' . "\r\n";
    $info_window_template .= "\t\t\t\t" . '<%= thumb %>' . "\r\n";
    $info_window_template .= "\t\t\t" . '</div>' . "\r\n";
    $info_window_template .= "\t\t\t" . '<h5 class="support-heading">Sales support</h5>' . "\r\n";
    $info_window_template .= "\t\t\t" . '<h3 class="name"><%= name %></h3>' . "\r\n";
    $info_window_template .= "\t\t\t" . '<p class="description"><%= description %></p>' . "\r\n";
    $info_window_template .= "\t\t\t" . '<div class="button-container">' . "\r\n";
    $info_window_template .= "\t\t\t\t" . '<a class="button open-agent-contact-modal-map" style="cursor:pointer;" onclick="openAgentModal()">BOOK A CALL</a>' . "\r\n";
    $info_window_template .= "\t\t\t" . '</div>' . "\r\n";
    $info_window_template .= "\t\t" . '</div>' . "\r\n";


    $info_window_template .= "\t" . '</div>' . "\r\n";

    return $info_window_template;
}

add_filter( 'wpsl_info_window_template', 'fujinon_custom_info_window_template' );