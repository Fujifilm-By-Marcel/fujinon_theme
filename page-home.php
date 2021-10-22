<?php
get_header();
?>

<?php 
set_query_var( 'hero-classes', '' );
get_template_part( 'template-parts/content', 'hero' ); 
?>

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
<?php 
get_template_part( 'template-parts/content', 'discover-block' ); 
get_sidebar();
get_footer();
