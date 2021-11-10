<?php
get_header();
set_query_var( 'hero-classes', 'standard-spacing-margin' );
get_template_part( 'template-parts/content', 'hero-mini' ); 
?>

<section class="icon-descriptions standard-spacing-margin">
	<div class="container">
		<div class="split two-column-grid">
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

<?php 
get_template_part( 'template-parts/content', 'discover-block' ); 
get_sidebar();
get_footer();