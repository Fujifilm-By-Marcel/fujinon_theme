<?php
/**
 * Block Name: Featured Product
 *
 * This is the template that displays the Featured Product block.
 */

// create id attribute for specific styling
$id = 'product-' . $block['id'];
$classname = isset($block['className']) ? $block['className'] : '';
$value = get_post(get_field('product')[0]);

//echo "<pre>";
//print_r($value);
//echo "</pre>";


$post_meta = get_post_meta($value->ID);
?>
<div id="<?php echo $id; ?>" class="product-block <?php echo $classname ?>" >
	<div data-index="<?php echo $value->ID; ?>">		
		<p class="category">FEATURED PRODUCT</p>		
		<div class="desktop-columns">
			<div>
				<?php echo get_the_post_thumbnail($value->ID, "large") ?>		
			</div>
			<div>
				<h3 class="title"><?php echo $value->post_title; ?></h3>
				<div class="content">				
					<p><?php echo $value->post_content ?></p>
				</div>
				<div class="buttons">
					<a href="<?php echo $post_meta['page_url'][0] ?>" target="_blank" class="button reduced-padding">LEARN MORE</a>
				</div>
			</div>
		</div>
	</div>
</div>