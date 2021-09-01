<?php
get_header();
?>
<style>



</style>
<section class="video">
	<div class="video-container">
		<?php the_field('hero_video'); ?>
	</div>
	<div class="hero-overlay">
		<div class="container">
			<div><img src="<?php echo get_field('hero_logo')['url']; ?>" alt="" width="<?php echo get_field('hero_logo')['width']; ?>" height="<?php echo get_field('hero_logo')['height']; ?>"></div>
			<div>
				<p style="text-align:right;margin:0;"><?php the_field('hero_credit'); ?></p>
			</div>
		</div>
	</div>
</section>
<section class="home-tiles standard-spacing-margin" style="color:white;">
	<?php
	if( have_rows('tiles') ):
	    while( have_rows('tiles') ) : the_row();?>
        	<div class="home-tile" style="background:black;background-image: url('<?php the_sub_field('background_image') ?>');background-size:cover;background-position: center;<?php the_sub_field('custom_style') ?>">
        		<!--<div class="container" >-->
	        		
	        		<div class="container">
	        			<h1><?php the_sub_field('title'); ?></h1>
	        		</div>
	        		
	        		<div class="container">
	        		<?php if(get_sub_field('image')){ ?>
	        			<img src="<?php echo get_sub_field('image')['url']; ?>" width="<?php echo get_sub_field('image')['width']; ?>" height="<?php echo get_sub_field('image')['height']; ?>">
	        		<?php } ?>
	        		</div>
	        		<div class="container">
	        			<?php the_sub_field('text'); ?>
	        		</div>
	        	<!--</div>-->
	        </div>
	    <?php
	    endwhile;	
	endif;
	?>
</section>
<section class="teasers standard-spacing-margin">
	<div class="container" style="max-width:80em;">
		<?php
		if( have_rows('teasers') ):
		    while( have_rows('teasers') ) : the_row(); ?>
		    	<div class="split">
		    		<div>
		    			<div>
		    				<?php the_sub_field('text'); ?>
		    			</div>
		    		</div>
		    		<div>
		    			<img src="<?php echo get_sub_field('image')['url']; ?>" width="<?php echo get_sub_field('image')['width']; ?>" height="<?php echo get_sub_field('image')['height']; ?>" style="margin:auto;">
		    		</div>
		    	</div>
		    <?php
		    endwhile;
		endif;
		?>
	</div>
</section>

<?php do_shortcode('[my_discover_block]'); ?>

<script>

</script>
<?php
get_sidebar();
get_footer();
