<?php 

class Inriver {

	public function __construct() {
        add_action( 'buildProductData', array( $this, 'buildProductData' ) );
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


	private function recursivelyBuild($url){

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

				//if it's a channel - assign children
				if( $value->entityTypeId == "ChannelNode"){
					$url = "https://apiuse.productmarketingcloud.com/api/v1.0.0/channels/content/".urlencode($value->path);
					$var[$i]->children = $this->recursivelyBuild($url);				
				}	

				//else - it is a product - assign product data
				else{			
					
					//get links and fields
					$url = "https://apiuse.productmarketingcloud.com/api/v1.0.0/entities/".$value->entityId."/linksandfields";
					$data = $this->curlInriver(0, $url, false);    	
					//$var[$i]->displayDescription = $data->summary->displayDescription;
					//$var[$i]->image = $data->summary->resourceUrl;

					$var[$i]->oneLineDescription = $this->findObjectById($data->fields, "ProductOneLineDescription");
					$var[$i]->longDescription = $this->findObjectById($data->fields, "ProductLongDescription");
					$var[$i]->pageURL = $this->findObjectById($data->fields, "ProductProductPageURL");

					$var[$i]->ItemMinimumFocusingDistanceIn = $this->findObjectById($data->outbound[0]->fields, "ItemMinimumFocusingDistanceIn");
					$var[$i]->ItemCorrespondingImageSizeDiagonal = $this->findObjectById($data->outbound[0]->fields, "ItemCorrespondingImageSizeDiagonal");
					$var[$i]->ItemLensWeightlb = $this->findObjectById($data->outbound[0]->fields, "ItemLensWeightlb");				

					$var[$i]->bullet1 = $this->findObjectById($data->fields, "ProductShortBulletPoint1");				
					$var[$i]->bullet2 = $this->findObjectById($data->fields, "ProductShortBulletPoint2");				
					$var[$i]->bullet3 = $this->findObjectById($data->fields, "ProductShortBulletPoint3");				
					$var[$i]->bullet4 = $this->findObjectById($data->fields, "ProductShortBulletPoint4");				

					$var[$i]->catCopy = $this->findObjectById($data->fields, "ProductCinemaBroadcastSubCategoryDescription");
					$var[$i]->catHeader = $this->findObjectById($data->fields, "ProductCinemaBroadcastSubCategory1");

					//get images
					$url = "https://apiuse.productmarketingcloud.com/api/v1.0.0/entities/".$value->entityId."/mediadetails";
					$data = $this->curlInriver(0, $url, false);    	

					$images = [];

					foreach($data as $value){
						
						$url = "https://apiuse.productmarketingcloud.com/api/v1.0.0/entities/".$value->entityId."/linksandfields";
						$imagedata = $this->curlInriver(0, $url, false);  
						$imageobject = new \stdClass;
						$imageobject->entityId = $imagedata->summary->id;
						$imageobject->displayName = $imagedata->summary->displayName;
						$imageobject->displayDescription = $imagedata->summary->displayDescription;
						$imageobject->resourceUrl = $imagedata->summary->resourceUrl;
						array_push( $images, $imageobject );

					}				

					$var[$i]->images = $images;	
				}
				$i++;
			}
		}
	  	return $var;
	}


	private function findObjectById($array, $id){
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

	private function saveData($json){
		//echo "saving...";
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


	public function buildProductData(){
		//echo "building...";
		$url = "https://apiuse.productmarketingcloud.com/api/v1.0.0/channels/content/6527";
		$list = $this->recursivelyBuild($url);
		$this->saveData($list);
	}

	private function getProductData(){
		$contents = file_get_contents(get_template_directory()."/data/product-data.json");
		$list = json_decode($contents);
		return $list;
	}
	
}