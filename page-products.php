<?php
/*function enqueue_scripts(){
	wp_enqueue_script('owl-carousel', get_template_directory_uri().'/js/owl.carousel/owl.carousel.min.js', array( 'jquery' ), false, true ); 
	wp_enqueue_style('owl-carousel', get_template_directory_uri().'/js/owl.carousel/assets/owl.carousel.min.css');
	wp_enqueue_style('owl-carousel-theme', get_template_directory_uri().'/js/owl.carousel/assets/owl.theme.default.min.css');
}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );*/
get_header();
?>


<section class="product-nav">
	<div class="container">
		<div class="split">
			<div class="underline nav-item active"><a href="#"><h3><span>cinema</span></h3></a></div>
			<div class="underline nav-item"><a href="#"><h3><span>broadcast</span></h3></a></div>
		</div>
	</div>
</section>

<?php 
set_query_var( 'hero-classes', 'standard-spacing-margin' );
get_template_part( 'template-parts/content', 'hero' ); 
?>



<?php
get_sidebar();
get_footer();