<?php 

class Inriver {

	public function __construct() {
        add_action( 'buildProductData', array( $this, 'build_product_data' ) );
        add_action( 'uploadImage', array( $this, 'upload_image' ), 10, 1 );
        add_action( 'addProduct', array( $this, 'add_product' ), 10, 1 );
        //add_action( 'cleanDatabase', array( $this, 'clean_database' ) );
    }

	private function curlInriver($isPost, $url, $postFields){

		$header = array (	
			'X-inRiver-APIKey: ea45402db538119b33cdd314c8c39834',
		    'Accept: application/json',
		    'Content-Type: application/json',
		);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
		curl_setopt($curl, CURLOPT_POST, $isPost);
		$result = json_decode(curl_exec($curl));
		curl_close($curl);
		return $result;
	}


	private function recursively_build($url, $parent_entity_id = null){

		$data = $this->curlInriver(0, $url, false);
		$var = [];
		$containsChannelNode = false;


	  	if($data->entityList->count != 0){		
			
			//grab data
			$i=0;		
			foreach ($data->content as $key => $value){	

				//assign data			
				$var[$i] = new \stdClass;
				$var[$i]->name = $value->name;
				$var[$i]->type = $value->entityTypeId;    
				$var[$i]->path = $value->path;
				$var[$i]->entityId = $value->entityId;	
				$var[$i]->parentEntityId = $parent_entity_id;						

				//if it's a channel - assign children
				if( $value->entityTypeId == "ChannelNode"){

					$this->add_term($var[$i]);

					$url = "https://apiuse.productmarketingcloud.com/api/v1.0.0/channels/content/".urlencode($value->path);
					$var[$i]->children = $this->recursively_build($url, $var[$i]->entityId);
				}	

				//else - it is a product - assign product data
				else{			
					wp_schedule_single_event( time(), 'addProduct', array( $var[$i] ) );					
					//$this->add_product($var[$i]);
				}
				$i++;
			}
		}
	  	return $var;
	}

	
	private function add_term($var){
		
		$postId = $this->get_term_id_from_entity_id( $var->entityId );;
		$parentId = $this->get_term_id_from_entity_id( $var->parentEntityId );	

		//Define the category
		$cat = array(
			'cat_ID' => $postId ? $postId : '0',
			'taxonomy' => 'inriver_categories',
			'cat_name' => $var->name, 			
			'category_nicename' => sanitize_title($var->name), 
			'category_parent' => $parentId ? $parentId : '',
		);
		 
		// Create the category
		$cat_id = $this->wp_insert_category($cat);

		if($cat_id){
			add_term_meta( $cat_id, 'entity_id', $var->entityId );
			add_term_meta( $cat_id, 'path', $var->path );
		}

		
	}
	
	private function wp_insert_category( $catarr, $wp_error = false ) {
	    $cat_defaults = array(
	        'cat_ID'               => 0,
	        'taxonomy'             => 'category',
	        'cat_name'             => '',
	        'category_description' => '',
	        'category_nicename'    => '',
	        'category_parent'      => '',
	    );
	    $catarr       = wp_parse_args( $catarr, $cat_defaults );
	 
	    if ( '' === trim( $catarr['cat_name'] ) ) {
	        if ( ! $wp_error ) {
	            return 0;
	        } else {
	            return new WP_Error( 'cat_name', __( 'You did not enter a category name.' ) );
	        }
	    }
	 
	    $catarr['cat_ID'] = (int) $catarr['cat_ID'];
	 
	    // Are we updating or creating?
	    $update = ! empty( $catarr['cat_ID'] );
	 
	    $name        = $catarr['cat_name'];
	    $description = $catarr['category_description'];
	    $slug        = $catarr['category_nicename'];
	    $parent      = (int) $catarr['category_parent'];
	    if ( $parent < 0 ) {
	        $parent = 0;
	    }
	 
	    if ( empty( $parent )
	        || ! term_exists( $parent, $catarr['taxonomy'] )
	        || ( $catarr['cat_ID'] && term_is_ancestor_of( $catarr['cat_ID'], $parent, $catarr['taxonomy'] ) ) ) {
	        $parent = 0;
	    }
	 
	    $args = compact( 'name', 'slug', 'parent', 'description' );
	 
	    if ( $update ) {
	        $catarr['cat_ID'] = wp_update_term( $catarr['cat_ID'], $catarr['taxonomy'], $args );
	    } else {
	        $catarr['cat_ID'] = wp_insert_term( $catarr['cat_name'], $catarr['taxonomy'], $args );
	    }
	 
	    if ( is_wp_error( $catarr['cat_ID'] ) ) {
	        if ( $wp_error ) {
	            return $catarr['cat_ID'];
	        } else {
	            return 0;
	        }
	    }
	    return $catarr['cat_ID']['term_id'];
	}


	public function get_term_from_entity_id($entity_id){
		if($entity_id){
			$results = get_terms( array(
			    'taxonomy' => 'inriver_categories',
			    'meta_key' => 'entity_id',
			    'meta_value' => $entity_id,
			    'hide_empty' => false
			) );
			return !empty( $results ) && !is_wp_error($results) ? $results[0] : false;
		} else {
			return false;
		}
	}	

	public function get_term_id_from_entity_id($entity_id){
		$results = $this->get_term_from_entity_id($entity_id);
		return $results ? $results->term_id : false;
	}	

	public function get_term_path_from_entity_id($entity_id){
		$termId = $this->get_term_id_from_entity_id($entity_id);
		$path = get_term_meta($termId, "path", true);
		return $path;
	}	

	public function add_product($var){	

		//get links and fields
		$url = "https://apiuse.productmarketingcloud.com/api/v1.0.0/entities/".$var->entityId."/linksandfields";
		$linksandfields = $this->curlInriver(0, $url, false);    	
		//$var[$i]->displayDescription = $linksandfields->summary->displayDescription;
		//$var[$i]->image = $linksandfields->summary->resourceUrl;

		$var->oneLineDescription = $this->find_object_by_id($linksandfields->fields, "ProductOneLineDescription")[0]->en;
		$var->longDescription = $this->find_object_by_id($linksandfields->fields, "ProductLongDescription")[0]->en;
		$var->pageURL = $this->find_object_by_id($linksandfields->fields, "ProductProductPageURL")[0];

		$var->bullet1 = $this->find_object_by_id($linksandfields->fields, "ProductShortBulletPoint1")[0]->en;				
		$var->bullet2 = $this->find_object_by_id($linksandfields->fields, "ProductShortBulletPoint2")[0]->en;				
		$var->bullet3 = $this->find_object_by_id($linksandfields->fields, "ProductShortBulletPoint3")[0]->en;				

		$var->catCopy = $this->find_object_by_id($linksandfields->fields, "ProductCinemaBroadcastSubCategoryDescription")[0]->en;
		$var->catHeader = $this->find_object_by_id($linksandfields->fields, "ProductCinemaBroadcastSubCategory1")[0];

		//get images
		$url = "https://apiuse.productmarketingcloud.com/api/v1.0.0/entities/".$var->entityId."/mediadetails";
		$mediadetails = $this->curlInriver(0, $url, false);

		$images = [];

		foreach($mediadetails as $value){
			
			$url = "https://apiuse.productmarketingcloud.com/api/v1.0.0/entities/".$value->entityId."/linksandfields";
			$imagedata = $this->curlInriver(0, $url, false);  
			$imageobject = new \stdClass;
			$imageobject->entityId = $imagedata->summary->id;
			$imageobject->displayName = $imagedata->summary->displayName;
			$imageobject->displayDescription = $imagedata->summary->displayDescription;
			$imageobject->resourceUrl = $imagedata->summary->resourceUrl;
			$imageobject->parentEntityId = $var->entityId;
			array_push( $images, $imageobject );
			
			$imageobject->displayDescription == 'Web Category Header Image'? $imageobject->isCategoryImage = true : $imageobject->isCategoryImage = false;

			wp_schedule_single_event( time(), 'uploadImage', array( $imageobject ) );
			//$this->upload_image( $imageobject );
		}				

		$var->images = $images;	

		$postId = $this->get_post_id_from_entity_id( $var->entityId, 'inriver_products', 'publish' );

		// Create post object
		$my_post = array(
		  'ID' => $postId ? $postId : '0',
		  'post_title'    => $var->name,
		  'post_type'=> 'inriver_products',
		  'post_content'  => $var->longDescription,
		  'post_excerpt' => $var->oneLineDescription,
		  'post_status'   => 'publish',
		  'meta_input'   => array(
		  	'page_url' => $var->pageURL,
		  	'one_line_description' => $var->oneLineDescription,
		  	'bullet_1' => $var->bullet1,
		  	'bullet_2' => $var->bullet2,
		  	'bullet_3' => $var->bullet3,
		  	'cat_copy' => $var->catCopy,
		  	'cat_header' => $var->catHeader,
		  	'entity_id' => $var->entityId,
		  	'path' => $var->path,
		   ),
		);

		if ( isset($linksandfields->outbound[0]) ){
			//close circle -- image circle -- weight
			$my_post['meta_input']['item_minimum_focusing_distance_in'] = $this->find_object_by_id($linksandfields->outbound[0]->fields, "ItemMinimumFocusingDistanceIn")[0];
			$my_post['meta_input']['item_corresponding_image_size_diagonal'] = $this->find_object_by_id($linksandfields->outbound[0]->fields, "ItemCorrespondingImageSizeDiagonal")[0];
			$my_post['meta_input']['item_lens_weight_lb'] = $this->find_object_by_id($linksandfields->outbound[0]->fields, "ItemLensWeightlb")[0];
		}
		
		//echo "<pre>";
		//print_r($linksandfields->outbound[0]);
		//echo "</pre>";

		// Insert the post into the database
		$postId = wp_insert_post( $my_post );
		$parentId = $this->get_term_id_from_entity_id( $var->parentEntityId );
		wp_set_object_terms( $postId, $parentId, 'inriver_categories', false );				
	}



	public function upload_image($imageobject){
		$upload_dir = wp_upload_dir();
		$image_data = file_get_contents($imageobject->resourceUrl);
		$filename = basename($imageobject->displayName);
		$title = $imageobject->displayName;
		if(wp_mkdir_p($upload_dir['path']))
		    $file = $upload_dir['path'] . '/' . $filename;
		else
		    $file = $upload_dir['basedir'] . '/' . $filename;
		

		$wp_filetype = wp_check_filetype($filename, null );
		$attachment_data = array(
		    'post_mime_type' => $wp_filetype['type'],
		    'post_title' => sanitize_file_name($filename),
		    'post_content' => '',
		    'post_status' => 'inherit'
		);

		// Does the attachment already exist ?
		//get the post id of the attachment with the entity id
		$postId = $this->get_post_id_from_entity_id( $imageobject->entityId, 'attachment', 'inherit' );						
		if( $postId ){
			//get the attachment data
		  	$attachment = get_page( $postId, OBJECT, 'attachment');


		  //check if the files are identical
		  $md5image1 = md5($image_data);
		  $md5image2 = md5(file_get_contents(wp_get_attachment_url($postId)));
		  //flag if images are not identical
		  $md5image1 == $md5image2 ? $issame = true : $issame = false;
		  
		  //if the attachment exists and images are the same
		  if( !empty( $attachment ) && $issame ){		  	
		  	//set the id on the attachment data so that a new attachment is not created
		    $attachment_data['ID'] = $attachment->ID;
		  } 
		  
		  //if the images are different
		  if ( !$issame ){ 
		  	//delete the old attachment
		  	wp_delete_attachment( $postId, true);

		  	//add the new attachment
		  	file_put_contents($file, $image_data);
		  }

		} else {
			//if the post id does not exist it means the file hasn't been uploaded - upload the file
			file_put_contents($file, $image_data);
		}

		$attach_id = wp_insert_attachment( $attachment_data, $file );
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
		
		//get the parent's post id
		$parentPostId = $this->get_post_id_from_entity_id($imageobject->parentEntityId, 'inriver_products', 'publish');

		
		if($imageobject->isCategoryImage){
			//get the category 
			$term_id = get_the_terms( $parentPostId, 'inriver_categories' )[0]->term_id;
			//set metadata for category
			update_term_meta($term_id, 'header_image', $attach_id );

		} else {			

			//attach to post
			wp_update_attachment_metadata( $attach_id, $attach_data, $parentPostId );			

			//set thumbnail for post
			set_post_thumbnail( $parentPostId, $attach_id  );
		}

		//add entity id as metadata
		update_post_meta($attach_id, 'entity_id', $imageobject->entityId);
	}

	public function get_post_from_entity_id($value,$post_type,$post_status = ''){
		$args = array(
		    'post_type'   => $post_type,
		    'post_status' => $post_status,
		    'meta_query'  => array(
		        array(
		            'key'     => 'entity_id',
		            'value'   => $value
		        )
		    )
		);
		$query = new WP_Query($args);		
		return $query->posts ? $query->posts[0] : false;
	}

	//use post status - inherit for attachment / publish for posts
	//post type will usually be inriver_products or attachment
	public function get_post_id_from_entity_id($value,$post_type,$post_status = ''){
		$post = $this->get_post_from_entity_id($value,$post_type,$post_status);		
		return $post ? $post->ID : false;		
	}

	public function get_post_path_from_entity_id($value,$post_type,$post_status = ''){
		$post = $this->get_post_id_from_entity_id($value,$post_type,$post_status);		
		$path = $post ? get_post_meta( $post, "path", true) : false;		
		return $path;
	}	

	private function find_object_by_id($array, $id){
		$elements = [];
		$i=0;
	    foreach ( $array as $element ) {
	        if ( $id == $element->fieldTypeId ) {
	            $elements[$i] = $element->value;
	        }
	        $i++;
	    }

	    //return $elements->value;
	    return array_values($elements);
	}

	private function save_data($json){
		$myfile = fopen(get_template_directory()."/data/logs.txt", "a") or die("Unable to open file!");
		$txt = "";
		if ( file_put_contents( get_template_directory()."/data/product-data.json", json_encode($json) ) ){
		    $txt .= date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ).": JSON file created successfully...\n";
		}
		else {
		    $txt .= date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ).": Oops! Error creating json file...\n";
		}

		fwrite($myfile, $txt);
		fclose($myfile);
	}

	private function clean_database($taxonomies_array = false, $list = false, $parent_term_id = 0, $counter = 0){

		//get list of terms
		if(!$taxonomies_array){
			$taxonomies = get_terms( array(
			    'taxonomy' => 'inriver_categories',
			    'hide_empty' => false
			) );

			//replace keys with term_id
			$taxonomies_array = [];
			foreach($taxonomies as $value){
				$taxonomies_array[$value->term_id] = $value;
			}			
		}		


		//get relevant taxonomies by matching parent
		$relevant_taxonomies = [];
		foreach($taxonomies_array as $value){
			if($value->parent == $parent_term_id){
				$relevant_taxonomies[$value->term_id] = $value;
			}
		}	

		//echo "REL_TAX"." - ".$counter." - ".$parent_term_id;echo "<br>";echo "<pre>";print_r($relevant_taxonomies);echo "</pre>";echo "<br>";echo "<br>";
		
		//get products with term id
		$args = array(			  
		  'post_type'   => 'inriver_products',
		  'tax_query' => array(
			array (
		            'taxonomy' => 'inriver_categories',
		            'field' => 'id',
		            'terms' => $parent_term_id,
		            'include_children' => false
		        )
		    ),
		);			 
		$product_array = get_posts( $args );

		//replace keys with term_id
		if( $product_array ){	
			$temp_product_array = [];
			foreach($product_array as $value){
				$temp_product_array[$value->ID] = $value;
			}	
			$product_array = $temp_product_array;
		}
		
		//echo "PROD_BEFORE - "; print_r($taxonomies_array[$parent_term_id]->name); echo "<br>"; echo "<pre>"; print_r($product_array); echo "</pre>";	
		
		//get the list of current products and terms from inriver json file
		if(!$list){
			$list = json_decode(file_get_contents(get_template_directory()."/data/product-data.json"));
		}

		//iterate list
		foreach($list as $value){
			//remove products on list from arrays
			if( $value->type == "ChannelNode"){
				$postId = $this->get_term_id_from_entity_id( $value->entityId );			
				unset($relevant_taxonomies[$postId]);
				$this->clean_database($taxonomies_array, $value->children, $postId, $counter+=1);
			}			
			else if( $value->type == "Product" ){			
				$postId = $this->get_post_id_from_entity_id( $value->entityId, 'inriver_products', 'publish' );				
				unset($product_array[$postId]);
			} 
		}

		//terms to remove
		//echo "TAX - ";print_r($taxonomies_array[$parent_term_id]->name);echo "<br>";echo "<pre>";print_r($relevant_taxonomies);echo "</pre>";echo "<br>";echo "<br>";

		//products to remove
		//echo "PROD_AFTER - ";print_r($taxonomies_array[$parent_term_id]->name);echo "<br>";echo "<pre>";print_r($product_array);echo "</pre>";echo "<br>";echo "<br>";

		//remove terms
		foreach ($relevant_taxonomies as $value){
			$pages = get_posts(array(
			  'post_type' => 'inriver_products',
			  'numberposts' => -1,
			  'tax_query' => array(
			    array(
			      'taxonomy' => 'inriver_categories',
			      'field' => 'term_id', 
			      'terms' => $value->term_id,
			      'include_children' => false
			    )
			  )
			));
			
			//first remove term children
			foreach($pages as $value){				
				wp_delete_post( $value->ID );				
			}

			//then remove terms
			wp_delete_term( $value->term_id, 'inriver_categories' );

		}

		//remove products
		foreach($product_array as $value){
			wp_delete_post( $value->ID );
		}


	}

	public function build_product_data(){
		$url = "https://apiuse.productmarketingcloud.com/api/v1.0.0/channels/content/6527";
		$list = $this->recursively_build($url);
		$this->save_data($list);
		$this->clean_database();
	}

	public function get_product_data(){
		$contents = file_get_contents(get_template_directory()."/data/product-data.json");
		$list = json_decode($contents);
		return $list;
	}
	
}