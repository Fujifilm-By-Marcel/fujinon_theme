<?php
/*function enqueue_scripts(){
	wp_enqueue_script('owl-carousel', get_template_directory_uri().'/js/owl.carousel/owl.carousel.min.js', array( 'jquery' ), false, true ); 
	wp_enqueue_style('owl-carousel', get_template_directory_uri().'/js/owl.carousel/assets/owl.carousel.min.css');
	wp_enqueue_style('owl-carousel-theme', get_template_directory_uri().'/js/owl.carousel/assets/owl.theme.default.min.css');
}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );*/
get_header();
?>


<section class="product-nav">
	<div class="container">
		<div class="split">
			<div class="underline nav-item active"><a href="#"><h3><span>cinema</span></h3></a></div>
			<div class="underline nav-item"><a href="#"><h3><span>broadcast</span></h3></a></div>
		</div>
	</div>
</section>

<?php 
set_query_var( 'hero-classes', 'standard-spacing-margin' );
get_template_part( 'template-parts/content', 'hero' ); 
?>


<?php

// create & initialize a curl session
$curl = curl_init();
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl,CURLOPT_HTTPHEADER, array (	
	  'X-inRiver-APIKey: ea45402db538119b33cdd314c8c39834',
    'Accept: application/json',
    'Content-Type: application/json',
));


//get list of entities
curl_setopt($curl, CURLOPT_URL, "https://apiuse.productmarketingcloud.com/api/v1.0.0/query");
curl_setopt($curl, CURLOPT_POSTFIELDS, '{ 
   "dataCriteria": [ 
     { 
       "fieldTypeId": "ProductSubCategory1", 
       "value": [ 
         "Lenses"  
       ], 
       "operator": "ContainsAny" 
     } 
   ],  
   "dataCriteria": [ 
     { 
       "fieldTypeId": "ProductSubCategory2", 
       "value": "MK Zoom Lens", 
       "operator": "Equal" 
     } 
   ], 
 }');
$entities = json_encode(json_decode(curl_exec($curl), true)['entityIds']);


//get data from entities
curl_setopt($curl, CURLOPT_URL, "https://apiuse.productmarketingcloud.com/api/v1.0.0/entities:fetchdata");
curl_setopt($curl, CURLOPT_POSTFIELDS, '{
  "entityIds": '.$entities.',
  "objects": "EntitySummary,Media"
}');
$data = json_decode(curl_exec($curl));

//close curl
curl_close($curl);


echo "<pre>";
print_r($data);
echo "</pre>";

?>

<img src="https://asset.productmarketingcloud.com/api/assetstorage/2179_a07da038-6f11-4132-8b6a-23fd5b8a544d">

<?php
get_sidebar();
get_footer();