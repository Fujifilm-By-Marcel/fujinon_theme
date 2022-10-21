<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package fujinon_theme
 */

get_header();

$category = get_category( get_query_var( 'cat' ) );
$cat_id = false;
if(isset($category->cat_ID)){
	$cat_id = $category->cat_ID;
}

set_query_var( 'hero-classes', '' );
set_query_var( 'blog', '1' );
get_template_part( 'template-parts/content', 'hero-mini' ); 
?>

	<main id="primary" class="site-main">

		<!-- category links -->		
		<div class="category-links">
			<div class="container">
				<span>FILTER BY:</span>&nbsp;&nbsp;&nbsp;&nbsp;
				<?php
				//echo category links as buttons				
				$categories = get_categories( array(
					'orderby' => 'name',
					'parent'  => 0
				) );

				foreach ( $categories as $category ) {

					$tax_class = "";
					if($cat_id == $category->term_id){
						$tax_class = "active";
					}

					printf( '<a class="tax-button %3$s" href="%1$s">%2$s</a>&nbsp;&nbsp;&nbsp;&nbsp;',
						esc_url( get_category_link( $category->term_id ) ),
						esc_html( $category->name ),
						$tax_class
					);
				}
				

				//get_search_form();
				?>


			</div>
		</div>
		

		<div class="container">
		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;

			echo "<div class='posts-container'>";
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content-blog', get_post_type() );

			endwhile;
			echo "</div>"; //close posts-container
			
			//no longer using default post navigation
			//the_posts_navigation();

			echo '<div class="lazy-pagination"><a class="button" id="more-posts-button" href="#" data-page="1" data-maxpage="'.$wp_query->max_num_pages.'" data-cat="'.get_query_var( 'cat' ).'">LOAD MORE</a><div class="loader"></div></div>';

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>
		</div>
		<?php get_template_part( 'template-parts/content', 'discover-block' ); ?>
	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
