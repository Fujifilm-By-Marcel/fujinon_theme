<?php

/* Template Name: Promotions */

get_header(); ?>



<?php
$mobile_hero = get_field( 'mobile_hero_image' );
$desktop_hero = get_field( 'desktop_hero_image' );
?>

<!--  Hero Section  -->
<section class="promotions-hero hero-cntr">
    <picture>
        <source
            media="(max-width: 500px)"
            srcset="<?= wp_get_attachment_image_srcset( $mobile_hero, 'full' ); ?>">
        <source
            media="(min-width: 501px)"
            srcset="<?= wp_get_attachment_image_srcset( $desktop_hero, 'full' ); ?>">
        <?= wp_get_attachment_image( $desktop_hero, 'full' );  ?>
    </picture>
</section>




<?php
//check if is before first promo
$isBeforePromo = 1;
$firstPromoStartDate = 0;
$dateRangeIndex = -1;
$countRange = 0;
$prevEndDate = 0;

$startDate      = get_field( 'promotion_start_date' );
$endDate        = get_field( 'promotion_end_date' );
$date           = new DateTime("now", new DateTimeZone('America/New_York') );
$currentDate    = $date->format('Y-m-d');
$cols           = get_field( 'columns' ) ?: 'col-6 col-md-3';


if( $date->getTimestamp() > strtotime($startDate) && $startDate != "" ){
    $isBeforePromo = 0;
}

if( check_in_range($startDate, $endDate, $currentDate) ){
    $dateRangeIndex = $countRange;
}

if( check_out_range($prevEndDate, $startDate, $currentDate) ){
    $dateRangeIndex = $countRange-.5;
}

$prevEndDate = $endDate;
$countRange++;

if( $dateRangeIndex == -1 ) {
    $dateRangeIndex = $countRange-.5;
}

?>



<!--  Main Section   -->
<section class="main">


    <?php
    $countRange = 0;

    if( $countRange+.5 == $dateRangeIndex ) : ?>

        <!--  Expired Promotion Notice   -->
        <div class="row">

            <div class="col s12">
                <p style="text-align: center; font-size: 30px;"><?= get_field( "expired_text" ); ?></p>
            </div>

        </div>

    <?php endif; ?>




    <!--  Tabbed Content   -->
    <div class="container">

        <?php
        // Check if main tabs should be hidden...
        $rowstyle = get_field( 'hide_tab_filters' ) ? " style='display:none'" : ""; ?>


        <!--   Primary Tabs    -->
        <div class="tab-filters primary-tab-filters" <?= $rowstyle ?>>

            <ul class="tabs promotion-tabs promotion-main-tabs">

                <?php
                $countRange = 0;
                if( $countRange == $dateRangeIndex && have_rows( 'tabs' ) ) : ?>

                    <li class="tab promotion-tab default-tab tablinks defaultOpen" onclick="openTab(this, 'show-all')">
                        Show All
                    </li>


                    <?php
                    $tabCount = 0;
                    $product_ids = [];
                    while ( have_rows( 'tabs' ) ) : the_row();
                    $products = get_sub_field( 'choose_product_offer' ); // For each row of tabs, get the products selected

                    $tabCount++;
                    $tabid = "tabid-" . $tabCount;

                    foreach ($products as $product) :

                        // Add theme to the array, at the current tab index using $count
                        $product_ids[$tabCount][] = $product->ID;

                    endforeach;


                    // For this array of product ids, at this index, remove any duplicates.
                    $product_ids[$tabCount] = array_unique( $product_ids[$tabCount] );
                    ?>

                        <li class="tab promotion-tab tablinks" onclick="openTab(this, '<?php echo $tabid; ?>')">
                            <?= get_sub_field('title'); ?>
                        </li>

                    <?php endwhile;

                endif; ?>
            </ul>



        </div>



        <div class="tabs-wrapper">

            <?php
            $countRange = 0;
            if( $countRange == $dateRangeIndex && have_rows('tabs') ) :

                $count = 0;
                while ( have_rows('tabs') ) : the_row();
                $this_tabs_terms = []; // Reset for each tab
                $count++;
                $tabid = "tabid-".$count;
                $tabTitle = get_sub_field('title');
                $hide_title = get_sub_field('hide_title');

                $this_tabs_products = $product_ids[$count]; // Get the array of product IDs at the tab index

                foreach( $this_tabs_products as $id ) :

                    // for each unique id, get the terms for the filter tabs below
                    $this_tabs_terms[] = get_the_terms( $id, 'fujinon-product-type' );

                endforeach;

                // get the terms returns an array of term objects, so we flatten this with array merge
                $this_tabs_terms = array_merge( ...$this_tabs_terms  );
                ?>



                    <div id="<?= $tabid; ?>" class="tabcontent tab text-center">


                        <?php if ( ! $hide_title ) : ?>
                            <h2 class="tab-header" ><?= $tabTitle; ?></h2>
                        <?php endif; ?>



                        <?php  if( get_sub_field( 'add_subfilter' ) ) : ?>

                            <div class="tab-filters secondary-tab-filters">

                                <ul class="tabs promotion-tabs secondary-tabs-<?= $count; ?> promotion-secondary-tabs">
                                    <li class="tabfilters defaultOpen" data-filter="all" onclick="toggleActiveClass(this);">
                                        Show All
                                    </li>

                                    <?php foreach( $this_tabs_terms as $term ) : ?>

                                        <li class="tabfilters" data-filter="<?= $term->slug; ?>" onclick="toggleActiveClass(this);">
                                            <?= $term->name; ?>
                                        </li>

                                    <?php endforeach; ?>

                                </ul>

                            </div>

                        <?php endif; ?>




                        <?php
                        $products = get_sub_field( 'choose_product_offer' );


                        if ( $products ) : ?>


                            <!--  Product Grid  -->
                            <div class="deal-row row product-grid product-grid-<?= $count; ?> same-height-all-group">

                                <?php foreach( $products as $product ):
                                $productTitle       = get_field ( 'product_title', $product->ID );
                                $images             = get_field ( 'images', $product->ID );
                                $regular_price      = get_sub_field( 'was_text_override' ) ?: get_field ( 'regular_price', $product->ID );
                                $sale_price         = get_sub_field( 'now_text_override' ) ?: get_field ( 'sale_price', $product->ID );
                                $savings            = get_sub_field( "save_text_override" ) ?: get_field ( 'savings', $product->ID );
                                $incentive          = get_field ( 'additional_incentive', $product->ID );
                                $btn1               = get_sub_field('button_1') ?: get_field ( 'button_1', $product->ID );
                                $btn2               = get_sub_field('button_2') ?: get_field ( 'button_2', $product->ID );

                                $filterFunc = function( $obj ) {
                                    return $obj->slug;
                                };

                                // Get array of term slugs for the item-cards below
                                $subfilterCategories = array_map( $filterFunc,  get_the_terms( $product->ID, 'fujinon-product-type' ) );
                                ?>



                                    <!--  Single Product   -->
                                    <div class="single-promotional-product filtr-item-<?= $count; ?> single-product single-product-<?= get_row_index(); ?> <?= $cols; ?>" data-category="<?= implode( ', ', $subfilterCategories );  ?>">


                                        <div class="deal relative promotional-item same-height4 item-card <?= implode( ' ', $subfilterCategories );  ?>">

                                            <div class="card-wrapper relative">

                                                <div class="main-card-body">


                                                    <h3 class="deal-title same-height"><?= $productTitle; ?></h3>


                                                    <div class="product-images swiper">
                                                        <div class="swiper-wrapper">
                                                            <?php
                                                            $i = 1;
                                                            foreach ( $images as $k => $image ) : ?>
                                                                <div class="product-image swiper-slide product-image-<?= $i; ?>" data-label="<?= $image['swatch_label']; ?>" data-hex="<?= $image['swatch_hex_color'] ?>">
                                                                    <?= wp_get_attachment_image( $image['image'], 'full' ); ?>
                                                                </div>
                                                                <?php
                                                                $i++;
                                                            endforeach; ?>
                                                        </div>

                                                        <p class="current-swatch-title same-height2"></p>
                                                        <div class="swatches same-height3"></div>
                                                    </div>


                                                    <div class="price-text">
                                                        <p class="regular-price"><?= $regular_price; ?></p>
                                                        <p class="sale-price"><?= $sale_price ?></p>
                                                    </div>

                                                </div>



                                                <div class="savings-text bg-secondary">
                                                    <p class="savings"><?= $savings; ?></p>
                                                </div>


                                                <?php if( $incentive ) : ?>
                                                    <div class="incentive-text bg-dark-grey">
                                                        <p class="additional-incentive">
                                                            <?= $incentive; ?>
                                                        </p>
                                                    </div>
                                                <?php endif; ?>




                                                <div class="overlay same-height4">
                                                    <div class="overlay-content">

                                                        <?php if ( isset( $btn1['url'] ) ) : ?>
                                                            <a class="cta-button btn btn-primary" href="<?= $btn1['url']; ?>" target="<?= $btn1['target']; ?>">
                                                                <span><?= $btn1['title']; ?></span>
                                                            </a>
                                                        <?php endif; ?>

                                                        <?php if ( isset( $btn2['url'] ) ) : ?>
                                                            <a class="cta-button btn btn-secondary" href="<?= $btn2['url']; ?>" target="<?= $btn2['target']; ?>">
                                                                <span><?= $btn2['title']; ?></span>
                                                            </a>
                                                        <?php endif; ?>

                                                    </div>
                                                </div>



                                            </div>


                                        </div>

                                    </div>


                                <?php endforeach; ?>


                            </div>

                        <?php endif; ?>


                        <?php if ( get_sub_field( 'tab_disclaimer' ) ) : ?>
                            <div class="tab-disclaimer disclaimer">
                                <?= get_sub_field( 'tab_disclaimer' ); ?>
                            </div>
                        <?php endif; ?>


                    </div>



                <?php endwhile; //tabs ?>



                <?php if ( get_sub_field( 'disclaimer' ) ) : ?>
                    <div class="disclaimer">
                        <?= get_sub_field( 'disclaimer' ); ?>
                    </div>
                <?php endif; ?>



            <?php endif; ?>


        </div>


    </div>


</section>



<?php get_footer(); ?>
