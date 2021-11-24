<?php
function enqueue_scripts(){
	wp_enqueue_script('owl-carousel', get_template_directory_uri().'/js/page-lens-services.js', array( 'jquery' ), false, true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );
get_header();
set_query_var( 'hero-classes', 'standard-spacing-margin' );
get_template_part( 'template-parts/content', 'hero-mini' ); 
?>
<section class="icon-descriptions standard-spacing-margin">
	<div class="container">
		<div class="split">
			<?php
			if( have_rows('icon_descriptions') ):
			    while( have_rows('icon_descriptions') ) : the_row(); ?>			        
			    	<div>
				        <div>
							<?php the_sub_field('icon'); ?>
							<?php the_sub_field('content'); ?>
						</div>	
					</div>
			    <?php 
			    endwhile;
			endif;
			?>
		</div>
	</div>
</section>
<section class="mapsvg-container standard-spacing-margin">
	<div class="map-wrapper">
		<div class="container">
			<div class="header-block">
				<?php the_field('map_header'); ?>
			</div>
		</div>
		<?php echo do_shortcode(get_field('mapsvg')); ?>
	</div>
</section>
<section class="team-info standard-spacing-margin">
	<div class="container">
		<div class="header-block">
			<?php the_field('team_header') ?>
		</div>
	</div>

	<div class="container">
		<div class="team-members">
			<?php
			if( have_rows('team_members') ):
			    while( have_rows('team_members') ) : the_row(); ?>			        
			    	<div class="content-wrap">
			    		<img src="<?php the_sub_field('portrait'); ?>" alt="">
				        <div class="content">				        	
				        	<div class="map-button">
								<a class="button circle" href="#"><i class="fas fa-map-marked"></i></a>
							</div>							
					        <div class="container">
								<?php the_sub_field('content'); ?>
							</div>							
							<div class="card">
								<div class="container">
									<?php the_sub_field('card_content'); ?>
								</div>
							</div>							
						</div>	
						<div class="map-modal" style="display: none;">
							<div class="close"><i class="fal fa-times"></i></div>
							<div class="container">
								<?php the_sub_field('modal_content'); ?>
							</div>
						</div>
					</div>
			    <?php 
			    endwhile;
			endif;
			?>
		</div>
	</div>	
</section>
<?php
get_sidebar();
get_footer();
