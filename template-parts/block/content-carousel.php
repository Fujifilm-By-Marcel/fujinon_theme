<?php
/**
 * Block Name: Carousel
 *
 * This is the template that displays the carousel block.
 */

// create id attribute for specific styling
$id = 'carousel-' . $block['id'];
$classname = isset($block['className']) ? $block['className'] : '';
$images = get_field('images');

//open container
echo "<div id=".$id." class='carousel-block ".$classname." '>";
echo '<div class="owl-carousel owl-theme">';


if( have_rows('carousel') ):
    while( have_rows('carousel') ) : the_row();
        echo "<div>";
        $image_id = get_sub_field('image');
        $credit = get_sub_field('credit');
        echo wp_get_attachment_image( $image_id, 'large' );          
        echo '<p>'.$credit.'</p>';        
        echo "</div>";
    endwhile;
endif;

//close container
echo "</div>";
echo "</div>";

?>
<script>
    jQuery(document).ready(function( $ ) {
        $('.owl-carousel').owlCarousel({
            loop:true,
            margin:10,
            items:1,
            autoHeight:true
        })
    });
</script>
