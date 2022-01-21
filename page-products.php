<?php
function enqueue_scripts(){
	wp_enqueue_script('owl-carousel', get_template_directory_uri().'/js/owl.carousel/owl.carousel.min.js', array( 'jquery' ), false, true ); 
	wp_enqueue_style('owl-carousel', get_template_directory_uri().'/js/owl.carousel/assets/owl.carousel.min.css');
	wp_enqueue_style('owl-carousel-theme', get_template_directory_uri().'/js/owl.carousel/assets/owl.theme.default.min.css');
}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );
get_header();

class products{

	public $path;
	protected $top_cats;

	public function __construct() {

		//get path
		$this->path = $this->getPath();

		//get top level categories
		$args = array(
			'taxonomy' => 'inriver_categories',		   
			'parent' => '0',
			'meta_query' => array(
			    'relation' => 'OR',
			    array(
			        'key' => 'menu_order',
			        'compare' => 'EXISTS'
			    ),
			    array(
			        'key' => 'menu_order',
			        'compare' => 'NOT EXISTS'
			    )
			),		   
			'orderby' => 'meta_value title',
			'order'   => 'ASC',
		);
		$this->top_cats = get_categories($args);
	}

	private function getPath(){
		$productPath = isset($_GET['path']) ? $_GET['path'] : false;
		return $productPath ? $productPath : false;			
	}

	public function echo_filters($value){	

		//echo "<pre>";
		//print_r($value);
		//echo "</pre>";

		//query based on term_id of parent
		$query_args = array(
			'taxonomy' => 'inriver_categories',		   
			'parent' => $value->term_id,
			'meta_query' => array(
			    'relation' => 'OR',
			    array(
			        'key' => 'menu_order',
			        'compare' => 'EXISTS'
			    ),
			    array(
			        'key' => 'menu_order',
			        'compare' => 'NOT EXISTS'
			    )
			),		   
			'orderby' => 'meta_value title',
			'order'   => 'ASC',
		);
		$child_cats = get_categories($query_args);

		//if there are query results results
		if( count($child_cats) ){

			//if parent is not empty and first child doesn't have products -- underline and no owl
			$underlined = !$value->parent && !$child_cats[0]->count;
			$style = $value->key?"display:none;":"";
			echo '<div class="product-category-filter" data-index="',$value->term_id,'" style="', $style ,'" entity-id="'.get_term_meta($value->term_id, "entity_id", true).'">';
			$class = $underlined ?"underlined-category-filter":"";
			echo '<div class="buttons ',$class,'">';
			echo !$underlined ? '<div class="owl-custom-container">': '';
			echo !$underlined ? '<div class="loader-container"><div class="loader"></div></div>': '';
			echo !$underlined ? '<div class="owl-carousel owl-theme">': '';
			foreach($child_cats as $key => $value){
				$class = !$key?"active ":"";
				//if grandfather is not empty and doesn't have products -- underline
				echo '<a href="#" class="button ',$class,'" data-index="', $value->term_id, '" entity-id="'.get_term_meta($value->term_id, "entity_id", true).'" >', $value->name, '</a>';				
			} 
			echo !$underlined ? '</div>': ''; //owl
			echo !$underlined ? '<div class="owl-custom-nav desktop-only"><div class="left disabled"><i class="fas fa-chevron-left"></i></div><div class="right disabled"><i class="fas fa-chevron-right wiggle-right"></i></div></div>': '';
			echo !$underlined ? '</div>': ''; //owl-custom-container
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
		$query_args = array (		   
		    'post_type' => 'inriver_products',		    
		    'numberposts' => -1,
		    'tax_query' => array(
	            array(
	                'taxonomy' => 'inriver_categories',
	                'field' => 'id',
	                'terms' => array($value->term_id),                
	            ),
	        ),
            'meta_query' => array(
	            'relation' => 'OR',
	            array(
	                'key' => 'menu_order',
	                'compare' => 'EXISTS'
	            ),
	            array(
	                'key' => 'menu_order',
	                'compare' => 'NOT EXISTS'
	            )
	        ),		   
		    'orderby' => 'meta_value title',
		    'order'   => 'ASC',
         
		);
		$products = get_posts($query_args);
		$style = $value->key?"display:none;":"";
		echo "<div class='product-category-filter' data-index='",$value->term_id,"' style='",$style,"' entity-id='".get_term_meta($value->term_id, "entity_id", true)."'>";

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
			echo '<div class="product" data-index="',$value->ID,'" entity-id="'.get_post_meta($value->ID, "entity_id", true).'">';
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

	public function echo_banners(){
		foreach($this->top_cats as $key => $value){ 
			//echo hero
			set_query_var( 'hero-classes', 'reduced-spacing-margin broadcast-hero' );
			set_query_var('hero-id', "term_".$value->term_id );
			$style = $key ? "display:none;":"";
			echo "<div class='hero-toggle' data-index='",$value->term_id,"' style='",$style,"' entity-id='".get_term_meta($value->term_id, "entity_id", true)."'>";
			get_template_part( 'template-parts/content', 'hero' );
			echo '<div class="container">';
			echo '<div class="header-block">';
			the_field( "header_block","term_".$value->term_id );
			echo '</div>';
			echo '</div>';	
			echo "</div>";
		} 
	}

	public function echo_top_cats(){
		foreach($this->top_cats as $key => $value){			
			$class = $key ? "" : "active";
			?>
			<div  class="underline nav-item <?php echo $class; ?>">
				<a class='category-switcher' href="#" data-index='<?php echo $value->term_id ?>' entity-id='<?php echo get_term_meta($value->term_id, "entity_id", true)?>' ><h3><span><?php echo $value->name ?></span></h3></a>
			</div>			
		<?php 
		}
	}

	public function echo_product_categories(){		
		foreach($this->top_cats as $key => $value){ 
			$style = $key ? 'display:none;' : "";
			?>
			<div class="product-category" data-index=<?php echo $value->term_id ?> style="<?php echo $style; ?>" entity-id='<?php echo get_term_meta($value->term_id, "entity_id", true)?>'>
				<div class="container">
					<div class="product-filters ">
						<?php				
						$this->echo_filters($value, 1); 
						?>
					</div>			
				</div>
			</div>
			<?php 
		}
	}

}
$_products = new products();
?>
<section id="product-nav">
	<div class="container">
		<div class="split">
			<?php $_products->echo_top_cats(); ?>
		</div>
	</div>
</section>

<section class="banners">
	<?php $_products->echo_banners(); ?>
</section>

<section class="product-categories standard-spacing-margin">
	<?php $_products->echo_product_categories(); ?>
</section>

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
		$bullet1= "Minimum Focusing Distance: ".$post_meta['item_minimum_focusing_distance_in'][0]."in" : 
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
	<div class="modal product-modal" data-index="<?php echo $value->ID; ?>"  style="display:none;">
		<div class="modal-content container">
			<div class="close"><i class="fal fa-times"></i></div>
			<h3 class="title mobile-only"><?php echo $value->post_title; ?></h3>
			<?php echo get_the_post_thumbnail($value->ID, "large") ?>			
			<h3 class="title desktop-only"><?php echo $value->post_title; ?></h3>
			<div class="desktop-columns">
				<div class="content">
					<div class="bullets">
						<div class="bullet"><?php echo $bullet1 ?></div>
						<div class="bullet"><?php echo $bullet2 ?></div>
						<div class="bullet"><?php echo $bullet3 ?></div>
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

})( jQuery );

jQuery(document).ready(function( $ ) {
	var owl  = $(".owl-carousel");
	owl.on({
	    'refreshed.owl.carousel': function (event) {	    	
	    	$(event.target).find('.owl-item').show();			
	        $(event.target).siblings('.loader-container').hide();
	    }
	}).on({
	    'changed.owl.carousel': function (event) {
	    	var thisCarousel = $(event.target);
	    	var left = thisCarousel.siblings('.owl-custom-nav').children('.left');
			var right = thisCarousel.siblings('.owl-custom-nav').children('.right');
			var width;
			if (window.innerWidth) {
				width = window.innerWidth;
			} else if (document.documentElement && document.documentElement.clientWidth) {
				width = document.documentElement.clientWidth;
			} else {
				console.warn('Can not detect viewport width.');
			}
	    	if( (event.item.count < 3 && width < 1400) || (event.item.count < 4 && width >= 1400)  ){
				left.addClass('disabled');
				right.addClass('disabled');
			} else if (event.item.index == 0 || event.item.index == null){
				left.addClass('disabled');
				right.removeClass('disabled');				
			} else if(event.item.index == event.item.count-1){				
				left.removeClass('disabled');			
				right.addClass('disabled');				
			} else{
				left.removeClass('disabled');				
				right.removeClass('disabled');								
			}					

			if(event.item.index > 0){			
				thisCarousel.siblings('.owl-custom-nav').find('.wiggle-right').removeClass('wiggle-right');
			}
	    }
	}).owlCarousel({
		center:true,
		items:4,		
		dots: false,		
		margin:10,		
		responsive:{
			0:{
				items:2,				
			},
			600:{
				items:4,				
			},
			1400:{
				items:6,
			}
		}
	});

	$('.owl-custom-nav').click(function(event) {
		var target = event.target;
		var thisCarousel = $(this).siblings('.owl-carousel');
		if(target.closest('.left')){
			thisCarousel.trigger('prev.owl.carousel');
		}
		if(target.closest('.right')){
			thisCarousel.trigger('next.owl.carousel');			
		}
	});



	//trigger path if exists
	var path = "<?php echo $_products->path; ?>";
	if( path != "" ){
		var pathArray = path.split("/");
		for (i=0;i<pathArray.length;i++){
			var ele = $("[entity-id="+pathArray[i]+"]");
			var offset;	
			ele.click();
			console.log(ele);
			if ($(ele[0]).offset() !== undefined){
				if($(ele[0]).closest('.owl-custom-container').length){
					//console.log($(ele[0]).closest('.owl-custom-container'));	
					offset = $($(ele[0]).closest('.owl-custom-container')).offset().top;						
				} else {
					offset = $(ele[0]).offset().top;
					//console.log('b');	
				}
				//console.log(offset);	
			}			
		}
		
		setTimeout(function(){
			$(window).scrollTop( parseInt(offset)-250 );
		}, 1000);
		
	}


});



</script>

<?php
get_sidebar();
get_footer();