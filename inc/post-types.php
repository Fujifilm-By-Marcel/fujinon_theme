<?php

function fujinon_register_post_types() {

    register_post_type(
        'offers',
        array(
            'labels'             => array(
                'name'          => __( 'Promotions', 'fujinon_theme' ),
                'singular_name' => __( 'Promotion', 'fujinon_theme' ),
            ),
            'menu_position'      => 30,
            'menu_icon'          => 'dashicons-megaphone',
            'rewrite'            => array(
                'slug'       => 'promotions',
                'with_front' => false,
            ),
            'supports'           => array(
                'title',
                'editor',
                'thumbnail',
                'custom-fields',
                'page-attributes',
            ),
            'hierarchical'       => true,
            'has_archive'        => false,
            'public'             => true,
            'publicly_queryable' => false,
            'query_var'          => false,
            'show_in_rest'       => false,
            'show_in_admin_bar'  => false,
            'show_in_nav_menus'  => false,
            'exclude_from_search' => true,
        )
    );

}
add_action( 'init', 'fujinon_register_post_types' );



function fujinon_create_product_tax() {
    register_taxonomy(
        'fujinon-product-type',
        'offers',
        array(
            'label'              => __( 'Categories', 'fujinon_theme' ),
            'rewrite'            => array(
                'slug'       => 'fujinon-product-category',
                'with_front' => false,
            ),
            'hierarchical'       => true,
            'has_archive'        => false,
            'publicly_queryable' => false,
            'query_var'          => false,
            'rewrite'            => false,
            'show_admin_column'  => true,
        )
    );
}
add_action( 'init', 'fujinon_create_product_tax' );









