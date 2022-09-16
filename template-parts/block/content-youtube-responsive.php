<?php
/**
 * Block Name: Youtube Responsive
 *
 * This is the template that displays the youtube responsive block.
 */

// get image field (array)
$avatar = get_field('video_id');

// create id attribute for specific styling
$id = 'youtube-' . $block['id'];

?>
<div id="<?php echo $id; ?>" class="youtube-responsive-block <?php echo $block['className'] ?>" style="position: relative; padding-bottom: 56.25%; padding-top: 30px; height: 0; overflow: hidden;">
    <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" width="560" height="315" src="https://www.youtube.com/embed/<?php the_field('video_id') ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>