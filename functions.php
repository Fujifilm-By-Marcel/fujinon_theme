<?php
/**
 * fujinon_theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package fujinon_theme
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'fujinon_theme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function fujinon_theme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on fujinon_theme, use a find and replace
		 * to change 'fujinon_theme' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'fujinon_theme', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'fujinon_theme' ),
				'menu-2' => esc_html__( 'Footer', 'fujinon_theme' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'fujinon_theme_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'fujinon_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function fujinon_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'fujinon_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'fujinon_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function fujinon_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'fujinon_theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'fujinon_theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Column 1', 'fujinon_theme' ),
			'id'            => 'footer-col-1',
			'description'   => esc_html__( 'Add widgets here.', 'fujinon_theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Column 2', 'fujinon_theme' ),
			'id'            => 'footer-col-2',
			'description'   => esc_html__( 'Add widgets here.', 'fujinon_theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'fujinon_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function fujinon_theme_scripts() {
	wp_enqueue_style( 'fujinon_theme-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'fujinon_theme-style', 'rtl', 'replace' );

	wp_enqueue_script( 'fujinon_theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	wp_enqueue_script( 'FontAwesome', 'https://kit.fontawesome.com/0782e052a8.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'fujinon_theme_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Add ACF options page
 */
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Header Settings',
		'menu_title'	=> 'Header',
		'parent_slug'	=> 'theme-general-settings',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Footer Settings',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
	));
	
}

//social shortcode
function my_social() { ?>
    <?php if( have_rows('social', 'option') ) { ?>        
        <div class="social">
            <?php while( have_rows('social' , 'option') ) : the_row(); ?>
            	<a href="<?php the_sub_field('link') ?>" target="_blank"><?php the_sub_field('fa_icon') ?></a>
            <?php endwhile; ?>
        </div>
    <?php } 
}
add_shortcode( 'my_social', 'my_social' );

//discover block shortcode
function my_discover_block() { ?>
	<?php if( get_field("discover_block", "option")['content'] != "" ){ ?>
	<section class="discover-block standard-spacing-margin" >
		<div class='container' style="background:#acacac;background:<?php echo get_field("discover_block", "option")['background'] ?>;" style="max-width:90em;">
			<div style="max-width:<?php echo get_field('discover_block', "option")['max_width']; ?>;margin:auto;">
				<?php echo get_field("discover_block", "option")['content'] ?>
			</div>
		</div>
	</section>
	<?php }
}
add_shortcode( 'my_discover_block', 'my_discover_block' );


require_once('inc/inriver-func.php');

//schedule daily inriver pull
if ( ! wp_next_scheduled( 'expire_posts' ) ) {
    wp_schedule_event( time(), 'daily', 'buildFile' );
}
add_action( 'buildFile', 'buildFile' );