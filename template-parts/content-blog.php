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
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

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
	echo "<div class='cta'><span>EXPLORE ></span></div>";

	echo "</div>";
	?>

</article><!-- #post-<?php the_ID(); ?> -->
</a>