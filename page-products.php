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


class products{
	public function echo_filters($value){	

		//echo "<pre>";
		//print_r($value);
		//echo "</pre>";
		
		//save values for use in loop
		$parent_id = $value->term_id;
		$grandfather_id = $value->parent;

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
			
			//if first child -- active
			$style = $value->key?"display:none;":"";
			echo '<div class="product-category-filter" data-index="',$parent_id,'" style="', $style ,'">';
			echo '<div class="buttons">';
			foreach($child_cats as $key => $value){						

				//if first child -- active
				$class = !$key?"active ":"";

				//if grandfather is not empty and doesn't have products -- underline
				$class .= !$grandfather_id && !$value->count ?"underlined-category-filter":"";		
				echo '<a href="#" class="button ',$class,'" data-index="', $value->term_id, '" >', $value->name, '</a>';
			} 
			echo '</div>'; //buttons

			//if the category filter contains products -- show the products
			if($child_cats[0]->count > 0){
				echo '<div class="products">';
				foreach($child_cats as $key => $value){		
					//save key for later use -- to determine first child
					$value->key = $key;				
					$this->echo_products($value);
				}
				echo '</div>'; //products
			}
			echo '</div>'; //product-category-filter
		}


		//continue the loop for all children.
		foreach($child_cats as $key => $value){
			//save key for later use -- to determine first child
			$value->key = $key;
			$this->echo_filters($value);
		}

	}

	private function echo_products($value){

		//echo "<pre>";
		//print_r($value);
		//echo "</pre>";


		$category_name = $value->name;
		$parent_id = $value->term_id;

		$query_args = array (
			//'category__in' => $value->term_id,
			'post_type' => 'inriver_products',
			'orderby' => 'name',
		   'order'   => 'ASC',
		   'numberposts' => -1,
		   'tax_query' => array(
            array(
                'taxonomy' => 'inriver_categories',
                'field' => 'id',
                'terms' => array($value->term_id),                
            )
         )
		);
		$products = get_posts($query_args);


		$style = $value->key?"display:none;":"";

		//echo "<pre>";
		//print_r($products);
		//echo "</pre>";
		echo "<div class='product-category-filter' data-index='",$parent_id,"' style='",$style,"'>";
		foreach($products as $key => $value){
			echo '<div class="product">';
			//echo "<pre>";
			//print_r($value);
			//echo "</pre>";			
			echo "<h3 class='mobile-only'>",$category_name,"</h3>";
			echo "<h3 class='mobile-only'>",$value->post_title,"</h3>";
			echo get_the_post_thumbnail($value->ID, 'medium');
			echo '<div class="info">';
			echo "<h3 class='desktop-only'>",$category_name,"</h3>";
			echo "<h3 class='desktop-only'>",$value->post_title,"</h3>";
			echo "<p class='cta underline'>EXPLORE ></p>";
			echo "</div>";
			echo '</div>';
		}
		echo "</div>";

	}

}

/*
function echo_products($products){
	$parent_id = "";


	echo '<div class="product-category-content" data-index="',$parent_id,'">';
	foreach($products as $value){						
		echo '<a href="#" class="button" data-index="', $value->term_id, '">', $value->name, '</a>';
	} 
	echo '</div>';
}
*/

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
	<section class="product-category standard-spacing-margin" data-index=<?php echo $value->term_id ?> style="<?php echo $key ? 'display:none;' : "" ?>">
		<div class="container">
			<div class="container header-block" style="">
				<!-- todo:get field from victor -->
			</div>
			<div class="product-filters ">
				<?php	
				$products = new products();
				$products->echo_filters($value); 
				?>
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
	$('.product-category-filter .button').click(function (){
		var thisFilter = $(this).closest('.product-category-filter');
		var index = $(this).data('index');
		var parentIndex = thisFilter.data('index');
		var parentFilter = $( ".button[data-index="+parentIndex+"]").closest('.product-category-filter');
		var categoryindex = $(this).closest('.product-category').data('index');
		var targetFilter = $(".product-category[data-index="+categoryindex+"] .product-category-filter[data-index="+index+"]");
		$(this).addClass('active');
		$(this).siblings().removeClass('active');
		


		//$(".product-category[data-index="+categoryindex+"] .product-category-filter").not(thisFilter).not(parentFilter).hide();
		targetFilter.siblings().not(thisFilter).not(parentFilter).hide();
		targetFilter.show();

		

		return false;
	});

})( jQuery );
</script>
<?php
get_sidebar();
get_footer();