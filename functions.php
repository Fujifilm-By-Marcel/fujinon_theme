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
	define( '_S_VERSION', '1.0.18' );
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

	wp_enqueue_style( 'fujinon_wpsl', get_template_directory_uri() . '/src/wpsl.css' );

	wp_enqueue_script( 'fujinon_theme-common', get_template_directory_uri() . '/js/common.js', array( 'jquery' ), _S_VERSION, true );
	wp_enqueue_script( 'fujinon_theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );	

	wp_enqueue_script( 'FontAwesome', 'https://kit.fontawesome.com/0782e052a8.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}


    if ( is_page_template( 'templates/promotions.php' ) ) {
        wp_enqueue_style('promotions', get_stylesheet_directory_uri() . '/src/promotions-assets/promotions.css', array(), false);
        wp_enqueue_style('bootstrap-grid', get_stylesheet_directory_uri().'/src/promotions-assets/css/bootstrap-grid.min.css', array(),'5.3.2');
        wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), false);


        wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), false, true );
        wp_enqueue_script( 'filterizr', get_template_directory_uri() . '/src/promotions-assets/js/filterizr.min.js', array( 'jquery' ), null, true );
        wp_enqueue_script( 'promotions-tabs', get_template_directory_uri() . '/src/promotions-assets/js/tabs.js', array( 'jquery' ), false, true );
        wp_enqueue_script( 'promotions-main', get_template_directory_uri() . '/src/promotions-assets/js/main.js', array( 'jquery' ), false, true );
    }

    if ( is_page( 'lens-services' ) ) {
        wp_enqueue_style( 'nanoscroller-css', get_template_directory_uri() . '/src/nanoscroller.css' );
        wp_enqueue_script( 'nanoscroller-js', get_template_directory_uri() . '/src/nanoscroller.js', array('jquery'), false, true );
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
 * Register Custom Post Types.
 */
require get_template_directory() . '/inc/post-types.php';

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
            	<a href="<?= get_sub_field('link') ?>" target="_blank"><?= get_sub_field('fa_icon') ?></a>
            <?php endwhile; ?>
        </div>
    <?php } 
}
add_shortcode( 'my_social', 'my_social' );

//create class for inriver functions
require_once('inc/inriver-func.php');
$inriver = new Inriver();

//schedule daily inriver pull
if ( ! wp_next_scheduled( 'buildProductData' ) ) {
    wp_schedule_event( time(), 'daily', 'buildProductData' );
}

//shortcode for url encode
add_shortcode( 'make_button',    'make_button' );

function make_button( $atts ) {    
    return '<a class="button" href="#'.str_replace(" ", "-", strtolower($atts['anchor']) ).'">BOOK A CALL</a>';
}
    
/**
 * Output something after your form(s).
 *
 * @link  https://wpforms.com/developers/how-to-display-an-image-after-your-form/
 */
 /*
function wpf_dev_frontend_output_after( $form_data, $form ) { 
    //echo "<pre>";
    //print_r($form_data['fields']);
    //echo "</pre>";    
    $key = array_search('form number', array_column($form_data['fields'], 'label'));
    $form_number = array_values($form_data['fields'])[$key]['default_value'];    
    ?>    
    <div class="csrf-token-<?php echo $form->ID ?>" message=""></div>
    <div class="session-<?php echo $form->ID ?>" session=""></div>
    <script>
	function get_tokens() {
        jQuery.ajax({
            url: "https://ffus-optics.com/get-token/<?php echo $form_number ?>/",
            type: 'GET',
            contentType: false,
            processData: false,
            success: function(data) {
                var obj = JSON.parse(data);
                var response_code = obj['response'];
                var message = obj['message'];
                var session = obj['session'];
                jQuery('.csrf-token-<?php echo $form->ID ?>').attr("message",message);
                jQuery('.session-<?php echo $form->ID ?>').attr("session",session);
                console.log(message);
            }
        });
        return false;
    }
	get_tokens();
	</script>
    <?php
 
}
add_action( 'wpforms_frontend_output_after', 'wpf_dev_frontend_output_after', 10, 2 );
*/

/**
 * Action that fires when an entry is saved to the database.
 *
 * @link  https://wpforms.com/developers/wpforms_process_entry_save/
 *
 * @param array  $fields    Sanitized entry field. values/properties.
 * @param array  $entry     Original $_POST global.
 * @param int    $form_id   Form ID. 
 * @param array  $form_data Form data and settings.
 */
function wpf_dev_process_entry_save( $fields, $entry, $form_id, $form_data ) {
  	
  	$key = array_search('form number', array_column($form_data['fields'], 'label'));
    $form_number = array_values($form_data['fields'])[$key]['default_value'];
  
    // Example checking for the Yes value of a checkbox field and if yes, we'll then run our code
    if( $form_number ) {
         
    	//get message and session
    	$json = file_get_contents("https://ffus-optics.com/get-token/".$form_number."/");    	
    	$decoded = json_decode($json);
    	$message = $decoded->message;
    	$session = $decoded->session;

    	//build data
    	$data = [];
    	$data['csrf_token'] = $message;
    	$data['session'] = $session;
    	foreach($fields as $key => $value){			
    		$data[$value['name']] = $value['value'];
    	}
		
		//start log file
		//$myfile = fopen(get_template_directory()."/data/form-logs.txt", "a") or die("Unable to open file!");
		//$txt = "";
		//$txt .= date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) )." data: ".http_build_query($data)."\n";

		//url for post request
		$url = 'https://ffus-optics.com/submit/'.$form_number.'/';

		//send post request
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);

		//log results
		//if ($result === FALSE) { 
			//$txt .= date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) )." Failed to submit data...\n";		
		//} else {
			//$txt .= date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) )." Sucess...\n";		
			//$txt .= date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) )." result: ".json_encode($result)."\n";		
		//}	

		//close log file
		//fwrite($myfile, $txt);
		//fclose($myfile);
		 
    }
}
add_action( 'wpforms_process_entry_save', 'wpf_dev_process_entry_save', 10, 4 );

/**
 * Redirection Plugin Editor access
 */
add_filter( 'redirection_role', 'redirection_to_editor' );
function redirection_to_editor() {
    return 'edit_pages';
}

function extend_editor_caps() {
    // gets the editor role
    $roleObject = get_role( 'editor' );
    
    if( !$roleObject->has_cap( 'edit_theme_options' ) ) {
        $roleObject->add_cap( 'edit_theme_options' );
    }
}
add_action( 'admin_init', 'extend_editor_caps');

// Allow editors to use ACF
function my_acf_settings_capability( $capability ) {
    return 'edit_pages';
}
add_filter('acf/settings/capability', __NAMESPACE__ . '\\my_acf_settings_capability');

//lazy load pagination
//More posts - first for logged in users, other for not logged in
add_action('wp_ajax_ajax_next_posts', 'ajax_next_posts');
add_action('wp_ajax_nopriv_ajax_next_posts', 'ajax_next_posts');


function ajax_next_posts() {

    //Build query
    $args = array(
        //All your query arguments
        'cat' => $_GET['cat'],
		'post_status' => 'publish'
    );

    //Get page
    if( ! empty( $_GET['page'] ) ) {
        $page = $_GET['page'];
        $args['paged'] = $page;
    }

    $query_results = new WP_Query( $args );

    //Results found
    if ( $query_results->have_posts() ) {

        //Start "saving" results' HTML
        $results_html = '';
        ob_start();

        while ( $query_results->have_posts() ) { 

            $query_results->the_post();

            //Your single post HTML here
            get_template_part( 'template-parts/content-blog', get_post_type() );
        }    

        //"Save" results' HTML as variable
        $results_html = ob_get_clean();  
    }

    //Build ajax response
    $response = array();

    //1. value is HTML of new posts and 2. is total count of posts
    array_push ( $response, $results_html, $page);
    echo json_encode( $response );

    //Always use die() in the end of ajax functions
    die();  
}

//add acf blocks for gutenburg
add_action('acf/init', 'my_acf_init');
function my_acf_init() {
	
	// check function exists
	if( function_exists('acf_register_block') ) {
		
		
		acf_register_block(array(
			'name'				=> 'youtube-responsive',
			'title'				=> __('Youtube Responsive'),
			'description'		=> __(''),
			'render_callback'	=> 'acf_block_render_callback',
			'category'			=> 'formatting',
			'icon'				=> 'youtube',
			'keywords'			=> array( 'youtube' ),
		));

		acf_register_block(array(
			'name'				=> 'vimeo-responsive',
			'title'				=> __('Vimeo Responsive'),
			'description'		=> __(''),
			'render_callback'	=> 'acf_block_render_callback',
			'category'			=> 'formatting',
			'icon'				=> 'vimeo',
			'keywords'			=> array( 'vimeo' ),
		));


		acf_register_block(array(
			'name'				=> 'director',
			'title'				=> __('Director'),
			'description'		=> __(''),
			'render_callback'	=> 'acf_block_render_callback',
			'category'			=> 'formatting',
			'icon'				=> 'format-video',
			'keywords'			=> array( 'director' ),
		));

		acf_register_block(array(
			'name'				=> 'quote',
			'title'				=> __('Quote Custom'),
			'description'		=> __(''),
			'render_callback'	=> 'acf_block_render_callback',
			'category'			=> 'formatting',
			'icon'				=> 'format-quote',
			'keywords'			=> array( 'quote' ),
		));

		acf_register_block(array(
			'name'				=> 'director-multiple',
			'title'				=> __('Director Multiple'),
			'description'		=> __(''),
			'render_callback'	=> 'acf_block_render_callback',
			'category'			=> 'formatting',
			'icon'				=> 'format-video',
			'keywords'			=> array( 'director','multiple' ),
		));

		acf_register_block(array(
			'name'				=> 'carousel',
			'title'				=> __('Carousel'),
			'description'		=> __(''),
			'render_callback'	=> 'acf_block_render_callback',
			'category'			=> 'formatting',
			'icon'				=> 'format-gallery',
			'keywords'			=> array( 'carousel' ),
		));

		acf_register_block(array(
			'name'				=> 'featured-product',
			'title'				=> __('Featured Product'),
			'description'		=> __(''),
			'render_callback'	=> 'acf_block_render_callback',
			'category'			=> 'formatting',
			'icon'				=> 'database',
			'keywords'			=> array( 'featured', 'product' ),
		));
	}
}

function acf_block_render_callback( $block ) {
	
	$slug = str_replace('acf/', '', $block['name']);
	
	if( file_exists( get_theme_file_path("/template-parts/block/content-{$slug}.php") ) ) {
		include( get_theme_file_path("/template-parts/block/content-{$slug}.php") );
	}
}





// Custom functions
function check_in_range( $start_date, $end_date, $date_from_user ) {
    // Convert to timestamp
    $start_ts   =   strtotime( $start_date );
    $end_ts     =   strtotime( $end_date );
    $user_ts    =   strtotime( $date_from_user );

    // Check that user date is between start & end
    return ( ( $user_ts >= $start_ts ) && ( $user_ts <= $end_ts ) );
}


function check_out_range($start_date, $end_date, $date_from_user){
    // Convert to timestamp
    $start_ts = strtotime($start_date);
    $end_ts = strtotime($end_date);
    $user_ts = strtotime($date_from_user);

    // Check that user date is between start & end
    return (($user_ts > $start_ts) && ($user_ts < $end_ts));
}

// Add custom content security policy
function add_content_security_policy() {

	$reliableUrls = array(
		'https://ka-p.fontawesome.com',
		'https://stage.fujinon.com',
		'https://www.fujinon.com',
		'https://fujinon.com',
		'https://kit.fontawesome.com',
		'https://www.googletagmanager.com',
		'https://www.google-analytics.com',
		'https://connect.facebook.net',
		'https://*.googleapis.com',
		'https://*.gstatic.com',
		'https://facebook.com',
		'https://*.cloudfront.net',
		'https://www.facebook.com',
		'https://www.youtube.com',
        'https://maps.google.com'
	);

    $csp = "Content-Security-Policy: default-src 'self'; ";
	$csp .= "connect-src 'self' " . implode(' ', $reliableUrls) . "; ";
    $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' blob: " . implode(' ', $reliableUrls) . "; ";
    $csp .= "style-src 'self' 'unsafe-inline' " . implode(' ', $reliableUrls) . "; ";
    $csp .= "img-src 'self' data: " . implode(' ', $reliableUrls) . "; ";
    $csp .= "font-src 'self' " . implode(' ', $reliableUrls) . "; ";
	$csp .= "frame-src 'self' " . implode(' ', $reliableUrls) . "; ";
    header($csp);
}
add_action('send_headers', 'add_content_security_policy');


// Remove Author info from RSS Feed using custom RSS Template to prevent scraping
function create_my_custom_feed() {  
    load_template( TEMPLATEPATH . '/feed-rss2.php');  
}  
add_feed('rss2', 'create_my_custom_feed');


require_once(__DIR__ . '/inc/wpsl.php');
