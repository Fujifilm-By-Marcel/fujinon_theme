<?php
function enqueue_scripts(){
	wp_enqueue_script('owl-carousel', get_template_directory_uri().'/js/owl.carousel/owl.carousel.min.js', array( 'jquery' ), false, true ); 
	wp_enqueue_style('owl-carousel', get_template_directory_uri().'/js/owl.carousel/assets/owl.carousel.min.css');
	wp_enqueue_style('owl-carousel-theme', get_template_directory_uri().'/js/owl.carousel/assets/owl.theme.default.min.css');
}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );
get_header();

//get top level categories
$args = array(
   'taxonomy' => 'inriver_categories',
   'orderby' => 'name',
   'order'   => 'ASC',
   'parent' => '0',
);

$top_cats = get_categories($args);

function echo_filters($value){	
	
	//save parent id for use in loop
	$parent_id = $value->term_id;

	//query based on term_id of parent
	$query_args = array(
	   'taxonomy' => 'inriver_categories',
	   'orderby' => 'name',
	   'order'   => 'ASC',
	   'parent' => $parent_id,
	);
	$child_cats = get_categories($query_args);

	//if there are query results results
	if( count($child_cats) ){

		//echo "<pre>";
		//print_r($child_cats);
		//echo "</pre>";

		echo '<div class="filter" data-index="',$parent_id,'">';
		foreach($child_cats as $value){						
			echo '<a href="#" class="button" data-index="', $value->term_id, '">', $value->name, '</a>';
		} 
		echo '</div>';
	}

	foreach($child_cats as $value){
		echo_filters($value);
	}
}
?>
<section id="product-nav">
	<div class="container">
		<div class="split">
			<?php foreach($top_cats as $key => $value){ ?>
			<div  class="underline nav-item <?php echo ($key ? "" : "active"); ?>"><a class='category-switcher' href="#" data-index='<?php echo $value->term_id ?>' ><h3><span><?php echo $value->name ?></span></h3></a></div>			
			<?php } ?>
		</div>
	</div>
</section>
<?php 
//echo hero
set_query_var( 'hero-classes', 'standard-spacing-margin' );
get_template_part( 'template-parts/content', 'hero' );
?>


<?php foreach($top_cats as $key => $value){ ?>
	<section class="product-category" data-index=<?php echo $value->term_id ?> style="<?php echo $key ? 'display:none;' : "" ?>">
		<div class="container">
			<div class="container header-block" style="">
				<!-- todo:get field from victor -->
			</div>
			<div class="product-filters">
				<?php				
				echo_filters($value);
				?>			
			</div>
			<div class="products">
				<?php //get_template_part( 'products/content', 'products', array('data'=>$value) ); ?>
			</div>
		</div>
	</section>
<?php } ?>
<script>
(function($) {
	//onclick for category

	$(".category-switcher").click(function (){
		var index = $(this).data('index');
		$(".category-switcher").parent().removeClass('active');
		$(this).parent().addClass('active');
		$(".product-category").hide();
		$(".product-category[data-index="+index+"]").show();
		return false;
	});


	//onclick for filters
	$('.filter .button').click(function (){
		var filter = $(this).parent();
		var index = $(this).data('index');
		var parentIndex = filter.data('index');
		var parentFilter = $( ".button[data-index="+parentIndex+"]").parent();
		//console.log(".button[data-index="+parentIndex+"]")//.parent();
		var categoryindex = $(this).closest('.product-category').data('index');
		$(".product-category[data-index="+categoryindex+"] .filter").not(filter).not(parentFilter).hide();
		$(".product-category[data-index="+categoryindex+"] .filter[data-index="+index+"]").show();
		return false;
	});

})( jQuery );
</script>
<?php
get_sidebar();
get_footer();