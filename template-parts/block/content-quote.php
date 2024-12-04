<?php
/**
 * Block Name: Quote
 *
 * This is the template that displays the quote block.
 */

// create id attribute for specific styling
$id = 'quote-' . $block['id'];
$classname = isset($block['className']) ? $block['className'] : '';


?>
<div id="<?php echo $id; ?>" class="quote-block <?php echo $classname ?>" >
    <i class="fas fa-quote-left"></i>
	<p class="quote"><?= get_field('quote'); ?></p>
	<p class="author">- <?= get_field('author'); ?> -</p>
</div>