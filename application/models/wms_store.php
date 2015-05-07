<?php

class Wms_store extends CI_Model{
	//Constructor
	function __construct(){
	// Call the Model constructor
        parent::__construct();
		$this->rootdir = $this->config->item('base_url') ;
	}
	
	private $product_id ;
	
	//GETTING STORE RELATED INFO
	public function getAllProducts($status = "(status = 1)"){
		return $this->retrieveAllProductIDsFromDB($status) ; //returns an Array of Associative Arrays OR boolean false
	}
	
	public function getProductsAdvanced($limit_length = 0, $limit_start = "", $order = "date_added", $status = "(status = 1)"){
		return $this->retrieveRecentProductIDsFromDB($limit_length, $limit_start, $order, $status) ; //returns an Array of Associative Arrays OR boolean false
	}
	
	public function getProductInfo($product_id, $status = "(status = 1)"){
		return $this->retrieveProductInfoFromDB($product_id, $status) ; //returns an Object OR boolean false
	}
	
	public function getProductId($product_name){
		return $this->retrieveProductIDsByName($product_name) ; //returns an Associative Array OR boolean false
	}
	
	public function getProductName($product_id){
		return $this->retrieveProductNameById($product_id) ; //returns a String OR boolean false
	}
	
	public function getManufacturerInfo($manufacturer_id){
		return $this->retrieveManufacturerInfo($manufacturer_id) ; //returns an Object OR boolean false
	}
	
	public function getAllManufacturers($status = 1){
		return $this->retrieveAllManufacturerIDsFromDB($status) ; //returns an Array of Associative Arrays OR boolean false
	}
	
	public function getAllProductIDsForManufacturer($manufacturer_id, $status = "(status = 1)"){
		return $this->retrieveAllProductRelationsForManufacturer($manufacturer_id, $status) ; 
	}
	
	public function getAllProductInfoForManufacturer($manufacturer_id, $status = "(status = 1)"){
		$products = $this->retrieveAllProductRelationsForManufacturer($manufacturer_id, $status) ; 
		if($products != false){
			$all_products_info = array() ;
			for($i = 0; $i < count($products) ; $i++){
				$this_product_info = $this->retrieveProductInfoFromDB($products[$i]['product_id']) ;
				if($this_product_info !== false){
					array_push($all_products_info, $this_product_info) ;
				}
			}
			return $all_products_info ;	//returns an Array of Objects OR boolean false
		}
		return false ;
	}
	
	public function getAllProductCategories($status = "(status = 1)"){
		return $this->retrieveAllCategoryIDsFromDB($status) ; //returns an Array of Associative Arrays OR boolean false
	}
	
	public function getProductCategoryInfo($product_id){
		$categories = $this->retrieveAllCategoryRelationsForProduct($product_id) ; 
		if($categories != false){
			$all_categories_info = array() ;
			for($i = 0; $i < count($categories) ; $i++){
				$this_category_info = $this->retrieveThisCategoryInfo($categories[$i]['category_id']) ;
				if($this_category_info !== false){
					array_push($all_categories_info, $this_category_info) ;
				}
			}
			return $all_categories_info ;	//returns an Array of Objects OR boolean false
		}
		return false ;
	}
	
	public function getCategoryInfo($category_id){
		return $this->retrieveThisCategoryInfo($category_id) ; //Returns an Object OR boolean false
	}
	
	public function getCategoryId($category_name){
		return $this->retrieveCategoryIdByName($category_name) ; //Returns an Integer OR boolean false
	}
	
	public function getAllProductInfoForCategory($category_id, $status = "(status = 1)"){
		$products = $this->retrieveAllProductRelationsForCategory($category_id, $status) ; 
		if($products != false){
			$all_products_info = array() ;
			for($i = 0; $i < count($products) ; $i++){
				$this_product_info = $this->retrieveProductInfoFromDB($products[$i]['product_id']) ;
				if($this_product_info !== false){
					array_push($all_products_info, $this_product_info) ;
				}
			}
			return $all_products_info ;	//returns an Array of Objects OR boolean false
		}
		return false ;
	}
	
	public function getAllProductInfoForCategoryAdvanced($category_id, $status = "(status = 1)", $order = "", $limit = ""){
		$products = $this->retrieveAllProductRelationsForCategoryAdvanced($category_id, $status, $order, $limit) ; 
		if($products != false){
			$all_products_info = array() ;
			for($i = 0; $i < count($products) ; $i++){
				$this_product_info = $this->retrieveProductInfoFromDB($products[$i]['product_id']) ;
				if($this_product_info !== false){
					array_push($all_products_info, $this_product_info) ;
				}
			}
			return $all_products_info ;	//returns an Array of Objects OR boolean false
		}
		return false ;
	}
	
	public function getCurrentCurrency(){
		return $this->retrieveCurrentCurrencyInfoFromDB() ;
	}
	public function getProductDecription($product_id, $status = 1){
		return $this->retrieveProductDescriptionFromDB($product_id, $status) ;	//returns an Object OR boolean false
	}
	public function getWeightClasses(){
		return $this->retrieveWeightClassInfoFromDB() ;	//Returns an Associative Array OR boolean false
	}
	
	public function getAllAvailableOptionValues(){
		return $this->retrieveAvailableOptionValuesInfo() ;	//returns an Array of Associative Arrays OR boolean false
	}
	public function getProductOptionValues($product_id){
		return $this->retrieveProductOptionValueIDs($product_id) ;	//returns an Array of Associative Arrays OR boolean false
	}
	public function getProductOptionValueInfo($option_value_id){
		return $this->retrieveProductOptionValueInfo($option_value_id) ;	//returns an Array of Associative Arrays OR boolean false
	}
	public function getProductOptionInfo($option_id){
		return $this->retrieveProductOptionInfo($option_id) ;	//returns an Array of Associative Arrays OR boolean false
	}
	
	public function getShoppingCartInfo(){
		if($this->user_sessions->userLoggedIn() === true){
			//Get Cart Details from Database
			$user_id = $this->user_sessions->getUserId() ;
			$user_cart_array = $this->getUserCartInfo($user_id) ;
			//Check If Session Cart is in use
			if($this->session->userdata("shopping_cart") !== FALSE ){
				//Combine Session Cart to user Cart
				$user_cart_array = $this->mergeSessionCartIntoUserCart($user_cart_array) ;
				//Save User Cart to Database
				$save_res = $this->wms_api->saveUserCartInfoToDB($user_id, $user_cart_array) ;
				if($save_res === true){
					//Delete Session Cart
					$this->session->unset_userdata('shopping_cart' );
				}
			}
			return $user_cart_array ;
		}else{
			//Get Cart Details from Session
			return $this->session->userdata("shopping_cart") ;
		}
		return false ;
	}
	public function getUserCartInfo($user_id, $status = "(status = 1)"){
		$cart_info = $this->retrieveUserCartInfoFromDB($user_id, $status) ;
		if($cart_info !== false){
			return $cart_info->cart_array ;
		}
		return false ;
	}
	
	
	//EDITING STORE RELATED INFO
	public function editProductInfo($product_id, $name, $sku, $upc, $ean, $jan, $isbn, $mpn, $location, $quantity_on_shelf, $image_id, $manufacturer_id, $shipping, $price, $points, $tax_class_id, $date_available, $weight, $weight_class_id, $length, $width, $height, $length_class_id, $subtract, $minimum, $sort_order, $enabled, $status, $times_viewed){
		if($name != ""){
			return $this->updateProductInfo($product_id, $name, $sku, $upc, $ean, $jan, $isbn, $mpn, $location, $quantity_on_shelf, $image_id, $manufacturer_id, $shipping, $price, $points, $tax_class_id, $date_available, $weight, $weight_class_id, $length, $width, $height, $length_class_id, $subtract, $minimum, $sort_order, $enabled, $status, $times_viewed) ;
		}
		return false ;
	}
	public function editProductCategoryInfo($category_id, $category_name, $category_desc, $type, $status){
		if($category_name != ""){
			return $this->updateCategoryInfo($category_id, $category_name, $category_desc, $type, $status) ;
		}
		return false ;
	}
	public function editManufacturerInfo($manufacturer_id, $manufacturer_name, $image_id, $sort_order, $status){
		if($manufacturer_name != ""){
			return $this->updateManufacturerInfo($manufacturer_id, $manufacturer_name, $image_id, $sort_order, $status) ;
		}
		return false ;
	}
	public function editProductDescription($product_id, $description){
		//Ensure parameter values are not empty
		if($product_id != ""){
			return $this->updateProductDescription($product_id, $description) ;
		}
		return false ;
	}
	public function editProductOptions($product_id, $options){
		if($product_id != ""){
			//Update all relations with product to status 9
			$res1 = $this->updateDeleteProductOptions($product_id) ;
			if($res1 === true){
				for($i = 0; $i < count($options) ; $i++){
					//Get option_value_id
					//Create new relations with product
					$option_value_id = $options[$i] ;
					$res2 = $this->insertNewProductOptionValue($product_id, $option_value_id) ;
					if($res2 === true){
						//New Product Option Value Inserted Sucessfully
					}else{
						//An error occured
					}
				}
				return true ;
			}
		}
		return false ;
	}
	
	
	//ADDING STORE RELATED INFO
	public function incrementProductNumberOfTimesViewed($product_id){
		$res = $this->updateIncrementProductNumberOfTimesViewed($product_id) ;
		if($res == 1){ return true ; }
		return false ;
	}
	
	public function addProductDescription($product_id, $description, $status = 1, $language_id = 1, $meta_description = "", $meta_keyword = "", $tag = ""){
		//Ensure parameter values are not empty
		if($product_id != ""){
			//Check If this Product Description already exists
			if($this->checkIfProductDescriptionExists($product_id) === false){
				//Insert New Product Description
				$res = $this->insertProductDescription($product_id, $description, $status, $language_id, $meta_description, $meta_keyword, $tag) ;
				if($res == 1){ return true ; }
			}
		}
		return false ;
	}
	
	public function addProductToCategory($product_id, $category_id, $type = 1, $status = 1){
		//Ensure parameter values are not empty
		if( ($product_id != "") && ($category_id != "") ){
			//Check If this Product To category Relationship already exists
			if($this->checkIfProductToCategoryRelationshipExists($product_id, $category_id) === false){
				//Insert New Product To Category Relationship
				$res = $this->insertProductToCategoryRelationship($product_id, $category_id, $type = 1, $status = 1) ;
				if($res == 1){ return array(true) ; }
			}else{ return array(false, 2, "This product is Already linked to this Category!") ; }
		}
		return array(false, 1, "Product ID (".$product_id.") or the Category ID (".$category_id.") is missing!") ;
	}
	
	public function addProduct($product_name, $description = "", $category_id, $manufacturer_id = "", $image_id = "", $price = 1.0, $status = 1){
		if($product_name != ""){
			//Check if name has been previously used
			if($this->checkIfProductNameExists($product_name) === false){
				$product_id = $this->createProductID();
				$res = $this->insertIntoProductTable($product_id, $product_name, $manufacturer_id, $image_id, $price, $status) ;
				if($res == 1){
					if($category_id != ""){
						//Add Product To Category Relationship
						$p_c_res = $this->addProductToCategory($product_id, $category_id) ;
					}
					if($description != ""){
						//Add Product Description
						$p_desc_res = $this->addProductDescription($product_id, $description) ;
					}
					return array(true, "The Product ($product_name) has been successfully added!") ;
				}else{ return array(false, 3, "An error occured while saving to database. The Product ($product_name) may not have been added!") ; }
			}
			return array(false, 2, "This Product name ($product_name) has been previously used!") ;
		}
		return array(false, 1, '$product_name value is empty!') ;
	}
	
	public function addProductCategory($category_name, $description = "", $type = 1, $status = 1){
		if($category_name != ""){
			//Check if name has been previously used
			if($this->checkIfCategoryNameExists($category_name) === false){
				$category_id = $this->createCategoryID();
				$res = $this->insertIntoCategoryTable($category_id, $category_name, $description, $type, $status ) ;
				if($res == 1){ return array(true, "The Category ($category_name) has been successfully added!") ; 
				}else{ return array(false, 3, "An error occured while saving to database. The Category ($category_name) may not have been added!") ; }
			}
			return array(false, 2, "This Category name ($category_name) has been previously used!") ;
		}
		return array(false, 1, '$category_name value is empty!') ;
	}
	
	public function addManufacturer($name, $image_id = "", $sort_order = 1, $status = 1){
		if($name != ""){
			//Check if name has been previously used
			if($this->checkIfManufacturerNameExists($name) === false){
				$manufacturer_id = $this->createManufacturerID();
				$res = $this->insertIntoManufacturerTable($manufacturer_id, $name, $image_id, $sort_order, $status ) ;
				if($res == 1){ return array(true, "The Manufacturer ($name) has been successfully added!") ; 
				}else{ return array(false, 3, "An error occured while saving to database. The Manufacturer ($name) may not have been added!") ; }
			}
			return array(false, 2, "This Manufacturer name ($name) has been previously used!") ;
		}
		return array(false, 1, '$name value is empty!') ;
	}
	
	public function saveUserCartInfoToDB($user_id, $cart_array){
		$serialized_cart_array = serialize($cart_array) ;
		$user_cart_info = $this->getUserCartInfo($user_id) ;
		if($user_cart_info !== false){
			//Update
			return $this->updateUserCartInfo($user_id, $serialized_cart_array) ;
		}else if($user_cart_info === false){
			//Insert
			return $this->insertUserCartInfo($user_id, $serialized_cart_array) ;
		}
		return false ;
	}
	
	public function mergeSessionCartIntoUserCart($user_cart_array){
		if($this->session->userdata("shopping_cart") !== FALSE ){
			$session_cart = $this->session->userdata("shopping_cart") ;
			if(count($session_cart) > 0){
				//Items Exist in the Session Cart
				
				if($user_cart_array !== false){
					//For each Item in session cart, add item to User Cart
					for($i = 0; $i < count($session_cart); $i++){
						$i_item_info = array() ;
						$i_item_info = array_merge($i_item_info, array('item_id' 		=> $session_cart[$i]['item_id']) ) ;
						$i_item_info = array_merge($i_item_info, array('item_quantity' 	=> $session_cart[$i]['item_quantity']) ) ;
						$i_item_info = array_merge($i_item_info, array('item_options' 	=> $session_cart[$i]['item_options']) ) ;
						$i_item_info = array_merge($i_item_info, array('cart_type' 		=> $session_cart[$i]['cart_type']) ) ;
						$i_item_info = array_merge($i_item_info, array('item_add_time' 	=> $session_cart[$i]['item_add_time']) ) ;
						
						$user_cart_array = $this->addItemToExistingCartArray($i_item_info, $user_cart_array) ;
					}
				}else{
					//If there's no User Cart, Store All session cart Contents into User Cart
					$user_cart_array = $session_cart ;
				}
			}//if count($session_cart)
		}//if session_cart exists
		return $user_cart_array ;
	}
	private function addItemToExistingCartArray($new_item_info, $existing_shopping_cart_array){
		$o = "" ;
		//Modify Initial Shopping Cart Data
		if($existing_shopping_cart_array === false){
			$existing_shopping_cart_array = array() ;
		}
		if(count($existing_shopping_cart_array) > 0){
			$match_found = false ;
			for($i = 0; $i < count($existing_shopping_cart_array) ; $i++){
				if($existing_shopping_cart_array[$i]['item_id'] == $new_item_info['item_id']){
					$match_found = true ;
				}
			}
			
			$o .= "\n Search Results = ".(string)($match_found) ;
			if($match_found === true){
				//Item has previously been added to cart
				for($i = 0; $i < count($existing_shopping_cart_array) ; $i++){
					$the_item_id 		= $existing_shopping_cart_array[$i]['item_id'] ;
					$the_item_options 	= $existing_shopping_cart_array[$i]['item_options'] ;
					$the_cart_type		= $existing_shopping_cart_array[$i]['cart_type'] ;
					$the_item_add_time 	= $existing_shopping_cart_array[$i]['item_add_time'] ;
					if($the_item_id == $new_item_info['item_id']){
						if(($the_item_options == $new_item_info['item_options'])  ){//&& ($the_cart_type == $new_item_info['cart_type'])
							if($the_item_add_time < $new_item_info['item_add_time']){
								array_splice($existing_shopping_cart_array, $i, 1, array($new_item_info));
							}
						}else{
							//Item Options OR cart type is different so add new instance of item if appropriate
							//array_push($existing_shopping_cart_array, $new_item_info) ;
							$o .= "\n"."Item Options OR cart type is different" ;
							$exact_match_found = false ;
							for($j = 0; $j < count($existing_shopping_cart_array) ; $j++){
								$the_item_id2		= $existing_shopping_cart_array[$j]['item_id'] ;
								$the_item_options2 	= $existing_shopping_cart_array[$j]['item_options'] ;
								$the_cart_type2		= $existing_shopping_cart_array[$j]['cart_type'] ;
								if(($the_item_options2 == $new_item_info['item_options'])  ){//&& ($the_cart_type2 == $new_item_info['cart_type'])
									//Exact Match for Options and Cart Type found So don't add new
									$exact_match_found = true ;
								}
							}//end for $j
							if($exact_match_found === false){
								//Exact Match for Options and Cart Type NOT found Add new
								array_push($existing_shopping_cart_array, $new_item_info) ;
							}
						//	break ;
						}
					}
				}//end for $i
			}else{
				//Item has not yet been added to cart
				array_push($existing_shopping_cart_array, $new_item_info) ;
			}
		}else{
			array_push($existing_shopping_cart_array, $new_item_info) ;
		}
		return $existing_shopping_cart_array ;
	}
	
	//INSERTING INTO THE DATABASE
	private function insertProductDescription($product_id, $description, $status = 1, $language_id = 1, $meta_description = "", $meta_keyword = "", $tag = ""){
		//product_id, language_id, description, meta_description, meta_keyword, tag, status, time
		$query = "INSERT INTO wms_product_description(product_id, language_id, description, meta_description, meta_keyword, tag, status, time) 
					VALUES('$product_id', '$language_id', '$description', '$meta_description', '$meta_keyword', '$tag', $status, CURRENT_TIMESTAMP) " ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	private function insertProductToCategoryRelationship($product_id, $category_id, $type = 1, $status = 1){
		//product_id, category_id, type, status, time
		$query = "INSERT INTO wms_product_item_category(product_id, category_id, type, status, time) VALUES('$product_id', '$category_id', '$type', '$status', CURRENT_TIMESTAMP) " ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	private function insertIntoProductTable($product_id, $product_name, $manufacturer_id = "", $image_id = "", $price = 1.0, $status = 1){
		//product_id, name, manufacturer_id, image_id, price, status, date_added, date_modified
		$query = "INSERT INTO wms_product_item(product_id, name, manufacturer_id, image_id, price, status, date_added, date_modified) VALUES('$product_id', '$product_name', '$manufacturer_id', '$image_id', '$price', '$status', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP) " ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	private function insertIntoCategoryTable($category_id, $category_name, $description = "", $type = 1, $status = 1 ){
		//category_id, category_name, category_desc, type, status, time
		$query = "INSERT INTO wms_product_categories VALUES('$category_id', '$category_name', '$description', '$type', '$status', CURRENT_TIMESTAMP) " ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	private function insertIntoManufacturerTable($manufacturer_id, $name, $image_id = "", $sort_order = 1, $status = 1 ){
		//manufacturer_id, name, image_id, sort_order, status, time
		$query = "INSERT INTO wms_product_manufacturer VALUES('$manufacturer_id', '$name', '$image_id', '$sort_order', '$status', CURRENT_TIMESTAMP) " ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	private function insertNewProductOptionValue($product_id, $option_value_id, $status = 1 ){
		//product_id, option_value_id status
		$query = "INSERT INTO wms_item_option_value_product VALUES('$product_id', '$option_value_id', '$status') " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	private function insertUserCartInfo($user_id, $serialized_cart_array){
		//user_id, cart_array, status, time
		$status = 1 ;
		$query = "INSERT INTO wms_product_shopping_cart VALUES('$user_id', '$serialized_cart_array', '$status', CURRENT_TIMESTAMP) " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	
	
	// SELECTING FROM DATABASE
	private function retrieveCategoryIdByName($category_name, $status = 1){
		$query = "SELECT category_id FROM wms_product_categories WHERE category_name = '$category_name' AND status = $status" ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row() ;
			return $row->category_id ;
		}
		return false ;
	}
	private function retrieveThisCategoryInfo($category_id, $status = 1){
		$query = "SELECT * FROM wms_product_categories WHERE category_id = '$category_id' AND status = $status" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			
			$categ_obj->category_id 	= $result_obj->category_id ;
			$categ_obj->name 			= $result_obj->category_name ;
			$categ_obj->description 	= getStringTextForm($result_obj->category_desc) ;
			$categ_obj->type 			= $result_obj->type ;
			$categ_obj->status 			= $result_obj->status ;
			$categ_obj->time_added 		= $result_obj->time ;
			
			return $categ_obj ;
		}
		return false ;
	}
	private function retrieveAllProductRelationsForCategory($category_id, $status = "(status = 1)", $order = "", $limit = ""){
		$query = "SELECT product_id FROM wms_product_item_category WHERE category_id = '$category_id' AND $status $order $limit" ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	private function retrieveAllProductRelationsForCategoryAdvanced($category_id, $status = "(wms_product_item_category.status = 1)", $order = "", $limit = ""){
		$query = "SELECT wms_product_item_category.product_id FROM wms_product_item_category 
					JOIN wms_product_item ON wms_product_item_category.product_id = wms_product_item.product_id
					WHERE wms_product_item_category.category_id = '$category_id' AND $status $order $limit
					" ;
					//WHERE wms_product_item_category.category_id = '$category_id' AND $status $order $limit
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	
	
	private function retrieveAllCategoryRelationsForProduct($product_id, $status = 1){
		$query = "SELECT category_id FROM wms_product_item_category WHERE product_id = '$product_id' AND status = $status" ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	private function retrieveAllCategoryIDsFromDB($status = "(status = 1)"){
		$query = "SELECT category_id FROM wms_product_categories WHERE ".$status."" ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	private function retrieveManufacturerInfo($manufacturer_id, $status = 1){
		$query = "SELECT * FROM wms_product_manufacturer WHERE manufacturer_id = '$manufacturer_id' AND status = $status" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			
			$manufac_obj->id 			= $result_obj->manufacturer_id ;
			$manufac_obj->name 			= $result_obj->name ;
			$manufac_obj->image_id 		= $result_obj->image_id ;
			$manufac_obj->sort_order 	= $result_obj->sort_order ;
			$manufac_obj->status 		= $result_obj->status ;
			$manufac_obj->time_added 	= $result_obj->time ;
			
			return $manufac_obj ;
		}
		return false ;
	}
	
	private function retrieveAllManufacturerIDsFromDB($status = 1){
		$query = "SELECT manufacturer_id FROM wms_product_manufacturer WHERE status = $status" ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	
	private function retrieveAllProductRelationsForManufacturer($manufacturer_id, $status = "(status = 1)"){
		$query = "SELECT product_id FROM wms_product_item WHERE manufacturer_id = '$manufacturer_id' AND $status" ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	
	private function retrieveProductNameById($product_id, $status = 1){
		$query = "SELECT name FROM wms_product_item WHERE product_id = '$product_id' AND status = $status" ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row() ;
			return $row->name ;
		}
		return false ;
	}
	private function retrieveProductIDsByName($product_name, $status = 1){
		$query = "SELECT product_id FROM wms_product_item WHERE name = '$product_name' AND status = $status" ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	private function retrieveProductInfoFromDB($product_id, $status = "(status = 1)"){
		$query = "SELECT * FROM wms_product_item WHERE product_id = '$product_id' AND $status" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			$prod_obj->id 			= $result_obj->product_id ;
			$prod_obj->name 		= $result_obj->name ;
			$prod_obj->model 		= $result_obj->model ;
			
			//International Standard Product Id's
			$prod_obj->sku 			= $result_obj->sku ;
			$prod_obj->upc 			= $result_obj->upc ;
			$prod_obj->ean 			= $result_obj->ean ;
			$prod_obj->jan 			= $result_obj->jan ;
			$prod_obj->isbn 		= $result_obj->isbn ;
			$prod_obj->mpn 			= $result_obj->mpn ;
			
			$prod_obj->location 			= $result_obj->location ;
			$prod_obj->quantity_on_shelf	= $result_obj->quantity_on_shelf ;
			$prod_obj->image_id 			= $result_obj->image_id ;
			
			$prod_obj->manufacturer_id 		= $result_obj->manufacturer_id ;
			$prod_obj->shipping_enabled		= $result_obj->shipping ;
			$prod_obj->price 				= $result_obj->price ;
			
			//Can be used for manual ratings
			$prod_obj->points 				= $result_obj->points ;
			
			//I don't grab what these two fields are for
			$prod_obj->tax_class_id 		= $result_obj->tax_class_id ;
			$prod_obj->date_available		= $result_obj->date_available ;
			
			$prod_obj->weight 				= $result_obj->weight ;
			$prod_obj->weight_class_id 		= $result_obj->weight_class_id ;
			
			$prod_obj->length				= $result_obj->length ;
			$prod_obj->width 				= $result_obj->width ;
			$prod_obj->height				= $result_obj->height ;
			$prod_obj->length_class_id 		= $result_obj->length_class_id ;
			
			//I don't grab what these two fields are for either
			$prod_obj->subtract 			= $result_obj->subtract ;
			$prod_obj->minimum				= $result_obj->minimum ;
			
			$prod_obj->sort_order 			= $result_obj->sort_order ;
			$prod_obj->enabled 				= $result_obj->enabled ;
			$prod_obj->status 				= $result_obj->status ;
			$prod_obj->date_added			= $result_obj->date_added ;
			$prod_obj->date_modified 		= $result_obj->date_modified ;
			$prod_obj->times_viewed 		= $result_obj->times_viewed ;
			
			//More Info
			$prod_obj->description 			= $this->getProductDecription($result_obj->product_id) ;
			
			return $prod_obj ;
		}
		return false ;
	}
	private function retrieveRecentProductIDsFromDB($limit_length = 0, $limit_start = "", $order = "date_added", $status = "(status = 1 AND enabled = 1)"){
		$limit = "LIMIT ".$limit_length ;
		if($limit_start != ""){
			$limit = "LIMIT ".$limit_start.",".$limit_length ;
		}
		$query = "SELECT product_id FROM wms_product_item WHERE ".$status." ORDER BY ".$order." ".$limit."" ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	private function retrieveAllProductIDsFromDB($status = "(status = 1)"){
		$query = "SELECT product_id FROM wms_product_item WHERE $status" ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	
	private function retrieveCurrentCurrencyInfoFromDB(){
		$query = "SELECT * FROM wms_currency WHERE status = 1 " ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			
			$currency_obj->id 				= $result_obj->currency_id ;
			$currency_obj->name 			= $result_obj->title ;
			$currency_obj->code 			= $result_obj->code ;
			$currency_obj->symbol_left 		= $result_obj->symbol_left ;
			$currency_obj->symbol_right 	= $result_obj->symbol_right ;
			$currency_obj->decimal_place 	= $result_obj->decimal_place ;
			$currency_obj->value 			= $result_obj->value ;
			$currency_obj->status 			= $result_obj->status ;
			$currency_obj->time_added 		= $result_obj->time ;
			
			return $currency_obj ;
		}
		return false ;	
	}
	private function retrieveProductDescriptionFromDB($product_id, $status = 1){
		$query = "SELECT * FROM wms_product_description WHERE product_id = '$product_id' AND status = $status " ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			//product_id	language_id	description	meta_description	meta_keyword	tag	status	time
			$desc_obj->product_id 			= $result_obj->product_id ;
			$desc_obj->language_id 			= $result_obj->language_id ;
			$desc_obj->description 			= getStringTextForm($result_obj->description) ;
			$desc_obj->meta_description 	= $result_obj->meta_description ;
			$desc_obj->meta_keyword 		= $result_obj->meta_keyword ;
			$desc_obj->tag 					= $result_obj->tag ;
			$desc_obj->status 				= $result_obj->status ;
			$desc_obj->time_added 			= $result_obj->time ;
			
			return $desc_obj ;
		}
		return false ;
	}
	private function retrieveWeightClassInfoFromDB($status = 1){
		$query = "SELECT * FROM wms_weight_classes WHERE status = $status " ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	private function retrieveAvailableOptionValuesInfo($status = "(status = 1)"){
		$query = "SELECT * FROM wms_item_option_value WHERE $status " ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	private function retrieveProductOptionValueIDs($product_id, $status = "(status = 1)"){
		$query = "SELECT * FROM wms_item_option_value_product WHERE product_id = '".$product_id."' AND ".$status ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	private function retrieveProductOptionValueInfo($option_value_id, $status = "(status = 1)"){
		$query = "SELECT * FROM wms_item_option_value WHERE option_value_id = '".$option_value_id."' AND ".$status ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	private function retrieveProductOptionInfo($option_id, $status = "(status = 1)"){
		$query = "SELECT * FROM wms_item_option WHERE option_id = '".$option_id."' AND ".$status ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	
	private function retrieveUserCartInfoFromDB($user_id, $status = "(status = 1)"){
		$query = "SELECT * FROM wms_product_shopping_cart WHERE user_id = '$user_id' AND $status " ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			//user_id	cart_array	status	time
			$cart_info->user_id 			= $result_obj->user_id ;
			$cart_info->cart_array			= unserialize($result_obj->cart_array) ;
			$cart_info->status 				= $result_obj->status ;
			$cart_info->time_added 			= $result_obj->time ;
			return $cart_info ;
		}
		return false ;
	}
	
	
	//CHECKING INFO IN THE DATABASE
	public function checkIfProductNameExists($product_name){
		return checkAFieldValue($product_name, 'wms_product_item', 'name') ; 
		//Returns boolean true if value exists AND false if value does not exist
	}
	public function checkIfCategoryNameExists($category_name){
		return checkAFieldValue($category_name, 'wms_product_categories', 'category_name') ;
		//Returns boolean true if value exists AND false if value does not exist
	}
	public function checkIfManufacturerNameExists($manufacturer_name){
		return checkAFieldValue($manufacturer_name, 'wms_product_manufacturer', 'name') ; 
		//Returns boolean true if value exists AND false if value does not exist
	}
	public function checkIfProductToCategoryRelationshipExists($product_id, $category_id){
		return checkIfTwoFieldValuesMatch('wms_product_item_category', 'product_id', 'category_id', $product_id, $category_id) ;
		//Returns boolean true if values exist AND false if value does not exist
	}
	public function checkIfProductDescriptionExists($product_id){
		return checkAFieldValue($product_id, 'wms_product_description', 'product_id') ;
		//Returns boolean true if values exist AND false if value does not exist
	}
	
	
	//CREATING Integer IDs For a DATABASE TABLE FIELD
	public function createProductID(){
		return createAnId('wms_product_item', 'product_id') ; 
		//Returns boolean true if value exists AND false if value does not exist
	}
	public function createCategoryID(){
		return createAnId('wms_product_categories', 'category_id') ; 
		//Returns boolean true if value exists AND false if value does not exist
	}
	public function createManufacturerID(){
		return createAnId('wms_product_manufacturer', 'manufacturer_id') ; 
		//Returns boolean true if value exists AND false if value does not exist
	}
	
	
	
	//UPDATING INFO IN THE DATABASE
	private function updateCategoryInfo($category_id, $category_name, $category_desc, $type, $status){
		$query = "UPDATE wms_product_categories SET category_name = '$category_name', category_desc = '$category_desc', type = '$type', status = '$status' WHERE category_id = '$category_id'" ;
		$query = $this->db->query($query) ;
		return true ;
	}
	private function updateManufacturerInfo($manufacturer_id, $manufacturer_name, $image_id, $sort_order, $status){
		$query = "UPDATE wms_product_manufacturer SET name = '$manufacturer_name', image_id = '$image_id', sort_order = '$sort_order', status = '$status' WHERE manufacturer_id = '$manufacturer_id'" ;
		$query = $this->db->query($query) ;
		return true ;
	}
	private function updateIncrementProductNumberOfTimesViewed($product_id){
		$query = "UPDATE wms_product_item SET times_viewed =  (times_viewed + 1) WHERE product_id = '$product_id'" ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	private function updateProductInfo($product_id, $name, $sku, $upc, $ean, $jan, $isbn, $mpn, $location, $quantity_on_shelf, $image_id, $manufacturer_id, $shipping, $price, $points, $tax_class_id, $date_available, $weight, $weight_class_id, $length, $width, $height, $length_class_id, $subtract, $minimum, $sort_order, $enabled, $status, $times_viewed){
		$query = "UPDATE wms_product_item SET name = '$name', sku = '$sku', upc = '$upc', ean = '$ean', jan = '$jan', isbn = '$isbn', mpn = 'mpn', location = '$location', 
				quantity_on_shelf = '$quantity_on_shelf', image_id = '$image_id', manufacturer_id = '$manufacturer_id', shipping = '$shipping', price = '$price', 
				points = '$points', tax_class_id = '$tax_class_id', date_available = '$date_available', weight = '$weight', weight_class_id = '$weight_class_id', 
				length = '$length', width = '$width', height = '$height', length_class_id = '$length_class_id', subtract = '$subtract', minimum = '$minimum',
				sort_order = '$sort_order', enabled = '$enabled', status = '$status', date_modified = CURRENT_TIMESTAMP, times_viewed = '$times_viewed' 
				WHERE product_id = '$product_id' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	private function updateManufacturerTable(){
	//	$query = "UPDATE wms_product_manufacturer SET password = '$password' WHERE email = '$email' " ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	private function updateProductDescription($product_id, $description){
		$query = "UPDATE wms_product_description SET description = '$description' WHERE product_id = '$product_id' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	private function updateDeleteProductOptions($product_id){
		$status = 9 ;
		$query = "UPDATE wms_item_option_value_product SET status = '$status' WHERE product_id = '$product_id' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	private function updateUserCartInfo($user_id, $serialized_cart_array){
		$status = 1 ;
		$query = "UPDATE wms_product_shopping_cart SET cart_array = '$serialized_cart_array', status = '$status', time = CURRENT_TIMESTAMP WHERE user_id = '$user_id' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
}
			
?>