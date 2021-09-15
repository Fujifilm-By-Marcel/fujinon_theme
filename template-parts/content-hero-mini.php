<section class="hero mini-hero <?php echo get_query_var('hero-classes'); ?>">	
	<div class="hero-container">		
		<?php echo wp_get_attachment_image( get_field('hero_bg'), 'full', "", array( "class" => "full-width" ) );  ?>
	</div>
	<div class="hero-overlay">
		<div class="container">			
			<div class="header-block">
				<?php the_field('hero_text'); ?>
			</div>			
		</div>
	</div>
</section>