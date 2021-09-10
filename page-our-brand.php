<?php
function enqueue_scripts(){
	wp_enqueue_script('owl-carousel', get_template_directory_uri().'/js/owl.carousel/owl.carousel.min.js', array( 'jquery' ), false, true ); 
	wp_enqueue_style('owl-carousel', get_template_directory_uri().'/js/owl.carousel/assets/owl.carousel.min.css');
	wp_enqueue_style('owl-carousel-theme', get_template_directory_uri().'/js/owl.carousel/assets/owl.theme.default.min.css');
}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );
get_header();
?>
<style>

</style>

<section class="video standard-spacing-margin">	
	<div class="video-container">
		<?php echo wp_get_attachment_image( get_field('hero_bg'), 'full', "", array( "class" => "full-width" ) );  ?>
	</div>
	<div class="hero-overlay">
		<div class="container">
			<div style="max-width:55ch;">
				<?php the_field('hero_overlay_text'); ?>
			</div>
			<div>
				
			</div>
		</div>
	</div>
</section>


<section class="history standard-spacing-margin">
	<div class="container header-block" style="">
		<?php the_field('carousel_header_text') ?>
	</div>
	<div class="timeline-carousel">
		<div class="container-inner">
			<?php if( have_rows('carousel') ): ?>
			    <div class="owl-carousel owl-theme">
			    <?php while( have_rows('carousel') ): the_row(); 
			        $image = get_sub_field('image');
			        ?>
			        <div class="owl-content" data-dot='<span></span><p><?php the_sub_field('year'); ?></p>'>
			            <div class="split" style="max-width:70em;margin:auto;">			         
				            <div>
					            <h3 class="underline"><?php the_sub_field('year'); ?></h3>
					            <p><?php the_sub_field('text'); ?></p>
					        </div>
					        <div>
					        	<div>
						            <?php echo wp_get_attachment_image( $image, 'full' ); ?>
						            <?php the_sub_field('embed'); ?>
						        </div>
					        </div>
					    </div>
			        </div>
			    <?php endwhile; ?>
			    </div>
			<?php endif; ?>
		</div>
	</div>
	<!--<div style="position: relative;">
		<div style="background: #000;width: 1px;height: 109px;left: 50%;position: absolute;transform: translateX(-50%);top: -86px;z-index: 10;"></div>
	</div>-->
</section>


<?php do_shortcode('[my_discover_block]'); ?>

<section class="teasers brand-teasers standard-spacing-padding black-bg">
	<?php
	if( have_rows('products') ):
	    while( have_rows('products') ) : the_row(); $image = get_sub_field('image'); ?>

	        
	        <div class="split column-reverse">
	        	<div>
	        		<div class="container">
	        			<?php the_sub_field('text'); ?>		
	        		</div>	        			
	        	</div>	        	
	        	<div>
	        		<?php echo wp_get_attachment_image( $image, 'full' ); ?>	        		
	        	</div>
	        </div>
	    
	    <?php
	    endwhile;
	endif;
	?>
</section>


<script>
	jQuery(document).ready(function( $ ) {

		function centerDots(){
			let carouselwidth = $('.owl-dots').width();
			let activedot = $('.owl-dots .owl-dot.active');
			let activeleft = activedot.position().left;	
			let dotwidth = activedot.width();	
			let offset = carouselwidth/2-activeleft-dotwidth/2;
			$('.owl-dots').css('left', offset+"px");
		}

		let owl  = $(".owl-carousel");
		owl.owlCarousel({
			items:1,
			dotsData: true,
			autoplay:false,
			autoplayHoverPause:false,
			loop:true,
		});

		owl.on('changed.owl.carousel', function(event) {			
			centerDots();
		})

		owl.trigger('refresh.owl.carousel');

	});
</script>
<?php
get_sidebar();
get_footer();
