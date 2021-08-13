<?php
get_header();
?>
<style>
/* equal height rows */
.grid-split{
	display: grid;
	grid-auto-rows: 1fr;
}

/* add space between elements */
.home-tile .container{
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	gap: 1rem;
}
@media(min-width: 50em){
	.teasers .split:nth-child(odd){
		flex-direction: row-reverse;

	}
	.teasers .split > *{
		width: 50%;
	}
	.teasers .split > *:first-child > div{
		max-width:40ch; 
		margin:auto;
		padding: 0 1rem;
	}
	.grid-split{
		grid-template-columns: 1fr 1fr;
	}
}

</style>

<?php the_field('hero_embed'); ?>
<section class="home-tiles grid-split" style="color:white;margin-bottom:5rem;">
	<?php
	if( have_rows('tiles') ):
	    while( have_rows('tiles') ) : the_row();?>
        	<div class="home-tile" style="background:black;background-image: url('<?php the_sub_field('background_image') ?>');background-size:cover;">
        		<div class="container" style="height:100%;min-height:70vh" >
	        		<h1><?php the_sub_field('title'); ?></h1>
	        		<?php if(get_sub_field('image')){ ?>
	        			<img src="<?php echo get_sub_field('image')['url']; ?>" width="<?php echo get_sub_field('image')['width']; ?>" height="<?php echo get_sub_field('image')['height']; ?>" style="margin:auto;">
	        		<?php } ?>
	        		<div style="margin-top:auto;">
	        			<?php the_sub_field('text'); ?>
	        		</div>
	        	</div>
	        </div>
	    <?php
	    endwhile;	
	endif;
	?>
</section>
<section class="teasers" style="margin-bottom:5rem;">
	<div class="container" style="max-width:80em;">
		<?php
		if( have_rows('teasers') ):
		    while( have_rows('teasers') ) : the_row(); ?>
		    	<div class="split">
		    		<div style="display:flex;align-items:center;justify-content: center;">
		    			<div>
		    				<?php the_sub_field('text'); ?>
		    			</div>
		    		</div>
		    		<img src="<?php echo get_sub_field('image')['url']; ?>" width="<?php echo get_sub_field('image')['width']; ?>" height="<?php echo get_sub_field('image')['height']; ?>" style="margin:auto;">
		    	</div>
		    <?php
		    endwhile;
		endif;
		?>
	</div>
</section>
<script>

</script>
<?php
get_sidebar();
get_footer();
