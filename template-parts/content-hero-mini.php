<?php 
$pid = false;
if(get_query_var('blog')){
	$pid = get_option( 'page_for_posts' );	
}
?>

<section class="hero mini-hero <?php echo get_query_var('hero-classes'); ?>">	
	<div class="hero-container">		
		<?php echo wp_get_attachment_image( get_field('hero_bg', $pid), 'full', "", array( "class" => "cover" ) );  ?>
	</div>
	<div class="hero-overlay">
		<div class="container">			
			<div class="header-block">
				<?= get_field('hero_text', $pid); ?>
			</div>			
		</div>
	</div>
</section>