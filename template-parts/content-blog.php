<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package fujinon_theme
 */

?>
<a class="article-link" href="<?php echo esc_url( get_permalink() ) ?>">
<article class="single-post" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php if( $wp_query->current_post == 0 && !is_paged() ) : ?>
	<div class="aspect-ratio">
		<?php fujinon_theme_post_thumbnail(false); ?>	
		<div class="overlay-split">
			<div>
				<div class="category">
					<?php fujinon_theme_category_list(); ?>			
				</div>
				<div class="article-content">
					<?php
					the_title( '<h3>', '</h3>' );
					the_excerpt();
					?>
				</div>
			</div>
			<div>
				<div class="cta"><span>EXPLORE&nbsp;></span></div>
			</div>
		</div>
	</div>
<?php else : ?>

	<?php 
	fujinon_theme_post_thumbnail(false);
	//open content div
	echo "<div class='overlay'>";

	//category
	echo "<div class='category'>";
	fujinon_theme_category_list();
	echo "</div>";

	//content
	echo "<div class='article-content'>";
	the_title( '<h3>', '</h3>' );
	the_excerpt();
	echo "</div>";

	//cta
	echo "<div class='cta'><span>EXPLORE&nbsp;></span></div>";

	echo "</div>";
	?>

<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
</a>