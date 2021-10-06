<section class="hero <?php echo get_query_var('hero-classes'); ?>">	
	<div class="hero-container">
		<?php the_field('hero_video'); ?>
		<?php echo wp_get_attachment_image( get_field('hero_bg'), 'full', "", array( "class" => "full-height" ) );  ?>
	</div>
	<div class="hero-overlay">
		<div class="container">
			<div class="left-half">				
				<img src="<?php echo get_field('hero_logo')['url']; ?>" alt="" width="<?php echo get_field('hero_logo')['width']; ?>" height="<?php echo get_field('hero_logo')['height']; ?>">
				<?php the_field('hero_overlay_text'); ?>
			</div>
			<div class="right-half">
				<p><?php the_field('hero_credit'); ?></p>
			</div>
		</div>
	</div>
</section>