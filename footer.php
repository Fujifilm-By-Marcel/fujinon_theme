<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package fujinon_theme
 */

?>

	<footer id="colophon" class="site-footer offblack-bg">
		<div class="container">
			<div class="footer-widgets">			
				<aside class="widget-area">
					<?php dynamic_sidebar( 'footer-col-1' ); ?>
				</aside><!-- #secondary -->
				<aside class="widget-area">
					<?php dynamic_sidebar( 'footer-col-2' ); ?>
				</aside><!-- #secondary -->			
			</div>
		</div>
		<div class="black-bg">
			<div class="container">
				<div class="site-info">
					<!-- LOGO AND TEXT -->
					<?php $logo = get_field('logo', 'option'); ?>
					<div>
						<img class="logo" src="<?php echo $logo['url']; ?>" alt="" width="<?php echo $logo['width']; ?>" height="<?php echo $logo['height']; ?>">				
						<span><?= get_field('footer_text', 'option'); ?></span>
					</div>
					<!-- MENU -->
					<div>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'menu-2',
								'menu_id'        => 'footer-menu',
							)
						);
						?>
					</div>
				</div><!-- .site-info -->
			</div>
		</div>
		<div class="modal newsletter-modal"  style="display:none;">			
			<div class="modal-content container">
				<div class="close"><i class="fal fa-times"></i></div>
				<?= get_field('newsletter_modal', 'option'); ?>
			</div>		
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
