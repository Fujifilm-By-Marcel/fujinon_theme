<?php
/**
 * Block Name: Vimeo Responsive
 *
 * This is the template that displays the Vimeo responsive block.
 */

// create id attribute for specific styling
$id = 'vimeo-' . $block['id'];
$classname = isset($block['className']) ? $block['className'] : '';

?>
  
<div id="<?php echo $id; ?>" class="vimeo-responsive-block <?php echo $classname ?>"  style="left: 0; width: 100%; height: 0; position: relative; padding-bottom: 56.25%;">
    <iframe src="<?= get_field('video_url') ?>" style="top: 0; left: 0; width: 100%; height: 100%; position: absolute; border: 0;" allowfullscreen scrolling="no" allow="encrypted-media;">
    </iframe>
</div>