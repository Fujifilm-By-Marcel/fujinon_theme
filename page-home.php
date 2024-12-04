<?php
get_header();
?>

<?php 
set_query_var( 'hero-classes', '' );
get_template_part( 'template-parts/content', 'hero' ); 
?>

<section class="home-tiles" style="color:white;">
	<?php
	if( have_rows('tiles') ):
	    while( have_rows('tiles') ) : the_row();?>
        
        	<div class="home-tile" data-link="<?= get_sub_field('link') ?>" data-link-target="<?= get_sub_field('link_target') ?>" style="background:black;background-image: url('<?= get_sub_field('background_image') ?>');background-size:cover;background-position: center;<?= get_sub_field('custom_style') ?>">        			        		
        		<div class="container">
        			<h1><?= get_sub_field('title'); ?></h1>
        		</div>
        		
        		<div class="container">
        		<?php if(get_sub_field('image')){ ?>
        			<?php echo wp_get_attachment_image( get_sub_field('image'), 'full', false, array( 'style' => 'width:500px;' ) ); ?>
        		<?php } ?>
        		</div>
        		<div class="container">
        			<?= get_sub_field('text'); ?>
        		</div>	        	
	        </div>
	    
	    <?php
	    endwhile;	
	endif;
	?>
</section>
<section class="teasers standard-spacing-margin black-bg" style="padding:3em 0;">
	<?php
	if( have_rows('teasers') ):
	    while( have_rows('teasers') ) : the_row(); ?>
	    	<div class="split column-reverse">
	    		<div>
	    			<div class="container">
	    				<?= get_sub_field('text'); ?>
	    			</div>
	    		</div>
	    		<div>
	    			<?php echo wp_get_attachment_image( get_sub_field('image'), 'full' ); ?>	
	    		</div>
	    	</div>
	    <?php
	    endwhile;
	endif;
	?>
</section>
<?php 
$featured = get_field('featured');
if ( isset($featured['content']) && $featured['content'] != "" ){ ?>
<section class="featured standard-spacing-padding standard-spacing-margin">
	<div class="split container" style="">
		<div class="content">
			<?php echo $featured['content']; ?>
			<?php echo wp_get_attachment_image( $featured['image'], 'large', false, array( 'class' => 'mobile-only' ) ); ?>
			<?php echo $featured['content_part_2']; ?>
		</div>
		<div>
			<?php echo wp_get_attachment_image( $featured['image'], 'large', false, array( 'class' => 'desktop-only' ) ); ?>
		</div>
	</div>			
</section>
<?php } ?>
<?php 
get_template_part( 'template-parts/content', 'discover-block' ); ?>
<script>
(function($) {
	$(".home-tile").click(function (){
		var url = $(this).data('link');
		if( $(this).data('link-target') == "_blank" ){
			window.open(url, '_blank').focus();	
		} else {
			location.href = url;
		}
		
	});
})( jQuery );
</script>
<?php
get_sidebar();
get_footer();
