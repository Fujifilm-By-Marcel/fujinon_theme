<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package fujinon_theme
 */

?>
<div class="toolbar">
	<div class="container">
		<div class="back"><a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>"><i class="fal fa-arrow-left"></i></a></div>
		<div class="share"><!-- ShareThis BEGIN --><div class="sharethis-inline-share-buttons"></div><!-- ShareThis END --></div>
	</div>
</div>
<div class="container-65">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php
		if ( 'post' === get_post_type() ) :
			?>
			<div class="featured-product-wrapper-mobile" >
				<div class="entry-meta">
					<?php
					fujinon_theme_posted_on();
					fujinon_theme_posted_by();
					?>
				</div><!-- .entry-meta -->			
				<?php
				$featured_product = get_field('featured_product');
				if( $featured_product ): ?>    
				<div class="featured-product-anchor mobile-only" style="text-align: center;">
			    	<a href="<?php echo esc_url( $featured_product['href'] ); ?>"><img style="display:block;margin:auto;" src="<?php echo esc_url( $featured_product['image'] ); ?>"  />
			        	<p><?php echo $featured_product['label']; ?></p>
			    	</a>
		        	
				</div>    
				<?php endif; ?>				
			</div>
		<?php endif;

		if ( is_singular() ) :
			echo '<div class="featured-product-wrapper-desktop" >';
			echo '<div>';
			the_title( '<h1 class="entry-title">', '</h1>' );
			echo get_field('subheader') ? '<p class="sub-heading" style="font-size:1.875em">'.get_field('subheader')."</p>" : '';
			echo '</div>';
			echo '<div>';
			if( $featured_product ): ?>    
				<div class="featured-product-anchor desktop-only" style="text-align: center;">
			    	<a href="<?php echo esc_url( $featured_product['href'] ); ?>"><img style="display:block;margin:auto;" src="<?php echo esc_url( $featured_product['image'] ); ?>"  />
			        	<p><?php echo $featured_product['label']; ?></p>
			    	</a>
		        	
				</div>    
			<?php 
			endif;
			echo '</div>';
			echo '</div>';
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
		
		?>
		
	</header><!-- .entry-header -->

	<?php //fujinon_theme_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'fujinon_theme' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'fujinon_theme' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php fujinon_theme_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
</div>