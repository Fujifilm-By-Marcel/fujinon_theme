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
   'order'   => 'DESC',
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

			//if grandfather is not empty and doesn't have products -- underline and no owl
			$underlined = !$grandfather_id && !$child_cats[0]->count;		
			
			//if first child -- active
			$style = $value->key?"display:none;":"";
			echo '<div class="product-category-filter" data-index="',$parent_id,'" style="', $style ,'">';
			$class = $underlined ?"underlined-category-filter":"";		
			echo '<div class="buttons ',$class,'">';
			$class = "";
			echo !$underlined ? '<div class="owl-carousel owl-theme">': '';
			foreach($child_cats as $key => $value){						

				//if first child -- active
				$class = !$key?"active ":"";

				//if grandfather is not empty and doesn't have products -- underline				
				echo '<a href="#" class="button ',$class,'" data-index="', $value->term_id, '" >', $value->name, '</a>';
				$class = "";
			} 
			echo !$underlined ? '</div>': ''; //owl
			echo '</div>'; //buttons

			//if the category filter contains products -- show the products
			if($child_cats[0]->count > 0){
				echo '<div class="products-container-outer">';
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
		echo "<div class='product-category-filter' data-index='",$parent_id,"' style='",$style,"'>";

		//echo "<pre>";
		//print_r($value);
		//echo "</pre>";

		//todo echo category header 
		echo "<div class='header split'>";
		echo "<div>";
		echo "<div>";
		the_field("header_block", "term_".$value->term_id);
		echo "</div>";
		echo "</div>";
		echo "<div>";
		echo wp_get_attachment_image(get_field( 'header_image', "term_".$value->term_id), 'large');
		echo "</div>";
		echo '</div>';

		echo "<div class='products-container-inner'>";
		foreach($products as $key => $value){
			echo '<div class="product" data-index="',$value->ID,'">';
			//echo "<pre>";
			//print_r($value);
			//echo "</pre>";			
			echo '<div class="info mobile-only">';
			echo "<h3>",$category_name,"</h3>";
			echo "<h3>",$value->post_title,"</h3>";
			echo "</div>";
			echo get_the_post_thumbnail($value->ID, 'large');
			echo '<div class="info">';
			echo "<h3 class='desktop-only'>",$category_name,"</h3>";
			echo "<h3 class='desktop-only'>",$value->post_title,"</h3>";
			echo "<p class='cta underline'>EXPLORE ></p>";
			echo "</div>";
			echo '</div>';
		}
		echo "</div>";
		echo "</div>";

	}

	public function echo_banners($value){
		foreach($value as $key => $value){ 
			//echo hero
			set_query_var( 'hero-classes', 'reduced-spacing-margin broadcast-hero' );
			set_query_var('hero-id', "term_".$value->term_id );
			$style = $key ? "display:none;":"";

			echo "<div class='hero-toggle' data-index='",$value->term_id,"' style='",$style,"'>";
			get_template_part( 'template-parts/content', 'hero' );
			echo '<div class="container">';
			echo '<div class="header-block">';
			the_field( "header_block","term_".$value->term_id );
			echo '</div>';
			echo '</div>';	
			echo "</div>";
		} 
	}

}
$products = new products();
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

<section class="banners">
<?php 
	$products->echo_banners($top_cats);
?>
</section>

<?php foreach($top_cats as $key => $value){ ?>
	<section class="product-category standard-spacing-margin" data-index=<?php echo $value->term_id ?> style="<?php echo $key ? 'display:none;' : "" ?>">
		<div class="container">
			<div class="product-filters ">
				<?php	
				
				$products->echo_filters($value); 
				?>
			</div>			
		</div>
	</section>
<?php } ?>

<section class="modals">

	<?php 
	$query_args = array (
		//'category__in' => $value->term_id,
		'post_type' => 'inriver_products',
		'orderby' => 'name',
	   'order'   => 'ASC',
	   'numberposts' => -1,
	);
	$products = get_posts($query_args);

	foreach($products as $value){ 
		
		$post_meta = get_post_meta($value->ID);
		/*echo "<pre>";
		print_r($value);
		echo "</pre>";
		echo "<pre>";
		print_r($post_meta);
		echo "</pre>";*/
		$bullet1 = "";
		$bullet2 = "";
		$bullet3 = "";

		isset($post_meta['item_minimum_focusing_distance_in'][0]) && $post_meta['item_minimum_focusing_distance_in'][0] != "" ? 
		$bullet1= "Minimum Focusing Distance: ".$post_meta['item_minimum_focusing_distance_in'][0] : 
		$bullet1= $post_meta['bullet_1'][0];

		isset($post_meta['item_corresponding_image_size_diagonal'][0]) && $post_meta['item_corresponding_image_size_diagonal'][0] != "" ? 
		$bullet2= "Image Circle: ".$post_meta['item_corresponding_image_size_diagonal'][0] : 
		$bullet2= $post_meta['bullet_2'][0];

		isset($post_meta['item_lens_weightlb'][0]) && $post_meta['item_lens_weightlb'][0] != "" ? 
		$bullet3= "Weight: ".post_meta['item_lens_weightlb'][0] : 
		$bullet3= $post_meta['bullet_3'][0];

		$bullet1 = preg_replace("/\.?\s*([^\.]+):/", "<strong>$1:</strong>", $bullet1);
		$bullet2 = preg_replace("/\.?\s*([^\.]+):/", "<strong>$1:</strong>", $bullet2);
		$bullet3 = preg_replace("/\.?\s*([^\.]+):/", "<strong>$1:</strong>", $bullet3);

		?>
	<div class="modal" data-index="<?php echo $value->ID; ?>"  style="display:none;">
		<div class="modal-content container">
			<div class="close"><i class="fal fa-times"></i></div>
			<h3 class="title mobile-only"><?php echo $value->post_title; ?></h3>
			<?php echo get_the_post_thumbnail($value->ID, "large") ?>			
			<h3 class="title desktop-only"><?php echo $value->post_title; ?></h3>
			<div class="desktop-columns">
				<div class="content">
					<div class="bullets">
						<div class="bullet1"><?php echo $bullet1 ?></div>
						<div class="bullet2"><?php echo $bullet2 ?></div>
						<div class="bullet3"><?php echo $bullet3 ?></div>
					</div>			
					<p><?php echo $value->post_content ?></p>
				</div>
				<div class="buttons">
					<div class="mobile-only"><a href="<?php echo $post_meta['page_url'][0] ?>" target="_blank" class="button reduced-padding">LEARN MORE</a></div>
					<div class="mobile-only"><a href="/lens-services/" class="cta">FIND A LENS ></a></div>

					<div  class="desktop-only"><a href="<?php echo $post_meta['page_url'][0] ?>" target="_blank" class="button">LEARN MORE</a></div>
					<div class="desktop-only"><a href="/lens-services/" class="button inverse">FIND A LENS</a></div>					
				</div>
			</div>
		</div>
	</div>
	<?php } ?>


</section>


<?php get_template_part( 'template-parts/content', 'discover-block' ); ?>
<script>
(function($) {

	$(".category-switcher").click(function (){
		var index = $(this).data('index');
		$(".category-switcher").parent().removeClass('active');
		$(this).parent().addClass('active');
		$(".product-category").hide();
		$(".product-category[data-index="+index+"]").show();
		$(".hero-toggle").hide();
		$(".hero-toggle[data-index="+index+"]").show();		
		return false;
	});

	$('.product-category-filter').on('click', '.button', function () {
		var thisItem = $(this);
		var thisFilter = thisItem.closest('.product-category-filter');
		var index = thisItem.data('index');
		var parentIndex = thisFilter.data('index');
		var parentFilter = $( ".button[data-index="+parentIndex+"]").closest('.product-category-filter');
		var categoryindex = thisItem.closest('.product-category').data('index');
		var targetFilter = $(".product-category[data-index="+categoryindex+"] .product-category-filter[data-index="+index+"]");
		thisItem.addClass('active');		
		thisFilter.find('.button').not(thisItem).removeClass('active');
		targetFilter.siblings().not(thisFilter).not(parentFilter).hide();
		targetFilter.show();
		$(this).closest('.owl-carousel').trigger('to.owl.carousel', $(this).parent().prevAll().length);
		return false;
	});

	
	
	

	$('.product').click(function(){
		var id = $(this).data('index');
		var modal = $('.modal[data-index='+id+']');

		modal.siblings().hide();
		modal.show();
		
	});

	$('.modal .close').click(function(){
		$(this).closest('.modal').hide();
	});

	$('.modal').click(function(event){
		if ( $(event.target).hasClass('modal') ) {
			$(this).hide();
		}		
	});



})( jQuery );

jQuery(document).ready(function( $ ) {
	let owl  = $(".owl-carousel");
	owl.owlCarousel({
		center:true,
		items:3,
		dots: false,		
		margin:10,
		loop:true,
		responsive:{
			0:{
				items:2,				
			},
			600:{
				items:3,				
			},
			1000:{
				items:5,
				loop:false,
			}
		}
	});
});
</script>
<?php
get_sidebar();
get_footer();