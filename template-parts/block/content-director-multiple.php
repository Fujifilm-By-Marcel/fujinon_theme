<?php
/**
 * Block Name: Director Multiple
 *
 * This is the template that displays the director block.
 */

// create id attribute for specific styling
$id = 'director-multiple-' . $block['id'];
$classname = isset($block['className']) ? $block['className'] : '';
$director_pids = get_field('director_multiple');
$columns = "columns-".get_field('columns');

//open container
echo "<div id=".$id." class='director-multiple-block ".$classname." ".$columns." '>";

foreach ($director_pids as $director_pid){
?>
<div class="director-block" >
    <img class="portrait" src="<?php echo get_field('portrait', $director_pid) ?>" alt="">
    <p class="director-title"><?php echo get_field('title', $director_pid) ?></p>
    <h4 class="director-name"><?php echo get_the_title($director_pid) ?></h4>
    <p class="blurb"><?php echo get_field('blurb', $director_pid) ?></p>
    <div class="social">
        <?php

        // Check rows existexists.
        if( have_rows('social', $director_pid) ):

            // Loop through rows.
            while( have_rows('social', $director_pid) ) : the_row();

                // Load sub field value.
                $fa_icon = get_sub_field('fa_icon', $director_pid);
                $link = get_sub_field('link', $director_pid);
                // Do something...
                echo "<a target='_blank' href='".$link."' class='social-icon' > ".$fa_icon."</a>";

            // End loop.
            endwhile;

        // No value.
        else :
            // Do something...
        endif;

        ?>
    </div>
</div>

<?php 
//close director_pids foreach loop
}

//close director-multiple container
echo "</div>";
