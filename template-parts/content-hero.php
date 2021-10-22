<?php 
$id = get_query_var ( 'hero-id' ,get_the_id()); 
$style = get_query_var ( "style" );
?>
<section class="hero <?php echo get_query_var('hero-classes'); ?>" style="<?php echo $style ?>">	
	<div class="hero-container">
		<?php the_field('hero_video', $id); ?>
		<?php echo wp_get_attachment_image( get_field('hero_bg', $id), 'full', "", array( "class" => "cover" ) );  ?>
	</div>
	<div class="hero-overlay">
		<div class="container">
			<div class="left-half">				
				<img src="<?php echo get_field('hero_logo', $id)['url']; ?>" alt="" width="<?php echo get_field('hero_logo', $id)['width']; ?>" height="<?php echo get_field('hero_logo', $id)['height']; ?>">
				<?php the_field('hero_text', $id); ?>
			</div>
			<div class="right-half">
				<p><?php the_field('hero_credit', $id); ?></p>
			</div>
		</div>
	</div>
</section>