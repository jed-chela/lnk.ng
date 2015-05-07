<?php

class Wms_store_gui extends CI_Model{
	//Constructor
	function __construct(){
	// Call the Model constructor
        parent::__construct();
		$this->rootdir = $this->config->item('base_url') ;
		
		$this->load->model('admin_images') ;
	}
	
	private $product_id ;
	
	public function showProductPreview($product_info, $cart_button = "Add To Cart"){
		$o = "" ;
		
		$item_id 		= $product_info->id ;
		$image_id 		= $product_info->image_id ;
		$item_name 		= $product_info->name ;
		$item_price 	= (int)($product_info->price) ;
		$currency_info = $this->wms_store->getCurrentCurrency() ;
		
		$item_image = "" ;
		if($image_id != 0){
			$item_image_info 	= $this->admin_images->getImageInfo($image_id) ;
			if($item_image_info !== false){
				$item_image 		= $this->admin_images->getImageElement($item_image_info->image_filename, $this->rootdir.'images/uploads/') ;
			}
		}
		
		//$o .= "$item_id, $item_image, $item_name, $item_price" ;
		$o .= "" ;
		$o .= "<div class='wms_item_prvw'>" ;
			$o .= "<div class='wms_item_prvw_img'>" ;
				$o .= "<a href='".$this->rootdir."product/view/".$item_id."/".$item_name."'>" ;
					$o .= $item_image ;
				$o .= "</a>" ;
			$o .= "</div>" ;
			$o .= "<div class='wms_item_prvw_name'>" ;
				$o .= "<a href='".$this->rootdir."product/view/".$item_id."/".$item_name."'>" ;
					$o .= $item_name ;
				$o .= "</a>" ;
			$o .= "</div>" ;
			$o .= "<div class='wms_item_prvw_bottom'>" ;
				$o .= "<div class='wms_item_prvw_price'>" ;
					$o .= $currency_info->symbol_left.$item_price.".00".$currency_info->symbol_right ;
				$o .= "</div>" ;	
				$o .= "<div class='wms_item_prvw_cart'>" ;
					$o .= "<span>".$cart_button."</span>" ;
				$o .= "</div>" ;
			$o .= "</div>" ;
		$o .= "</div>" ;
		return $o ;
	}
	
	public function showShoppingCartPreview($user_login_status, $items = array() ){
		$o = "" ;
		
		$currency_info = $this->wms_store->getCurrentCurrency() ;
		
		if(count($items) > 0){
			$items_count = count($items) ;
			$total_price = 0 ;
			for($pr = 0; $pr < $items_count; $pr++){
				$item_id = $items[$pr]['item_id'] ;
				$item_info = $this->wms_api->getProductInfo($item_id) ;
				if($item_info !== false){
					$total_price += $item_info->price ;
				}
			}
			
			$price_zero = $currency_info->symbol_left."".$total_price.".00".$currency_info->symbol_right ;
			$o .= "<span>".$items_count." item(s) - ".$price_zero."</span>" ;
		}else{
			$price_zero = $currency_info->symbol_left."0.00".$currency_info->symbol_right ;
			$o .= "<span>0 item(s) - ".$price_zero."</span>" ;
		}
		return $o ;
	}
	
	public function showIndividualProductInfo($product_info){
		$o = "" ;
		/*
			- Name
			- Price
			- Manufacturer Name (optional, if not empty)
			- Model (optional, if not empty)
			- Availablity (Yes (If no available > 1) or No)
		*/
		$currency_info = $this->wms_store->getCurrentCurrency() ;
		
		$product_name = $product_info->name ;
		$product_price = $currency_info->symbol_left.$product_info->price.$currency_info->symbol_right ;
		
		$manufacturer_name = "" ;
		$manufacturer_info = $this->wms_api->getManufacturerInfo($product_info->manufacturer_id) ;
		if($manufacturer_info !== false){
			$manufacturer_name = $manufacturer_info->name ;
		}else{
			//$o .= "Manufacturer Info Not Found! $product_info->manufacturer_id" ;
		}
		
		$product_model = $product_info->model ;
		$product_availability = "No" ;
		$product_quantity_on_shelf = $product_info->quantity_on_shelf ;
		if($product_quantity_on_shelf >= 0){
			$product_availability = "Yes" ;
		}else{
			//$o .= "Availability Info Not Found!" ;
		}
		
		$brand_href = "" ;
		if($manufacturer_name != ""){
			$brand_href = $this->rootdir."category/m/".$manufacturer_name ;
		}
		$o .= "<div class='wms-store-ind-product-name'>".$product_name."</div>" ;
		$o .= "<div class='wms-store-ind-product-price'>"."<span>Price: </span><b>".$product_price."</b></div>" ;
		$o .= "<div class='wms-store-ind-product-maker'>"."<span>Brand: </span><a href='".$brand_href."'>".$manufacturer_name."</a></div>" ;
		$o .= "<div class='wms-store-ind-product-model'>".$product_model."</div>" ;
		$o .= "<div class='wms-store-ind-product-availability'>"."<span>Available: </span>".$product_availability."</div>" ;
		
		return $o ;
	}
	
	public function showShoppingCartList($items = array() ){
		$o = "" ;
		$cart_table = "" ;
		$cart_table .= "<div class='table-title-header block_auto'>Shopping Cart</div>" ;
		
		$wish_table = "" ;
		$wish_table .= "<div class='table-title-header block_auto'>Wish List</div>" ;
		
		$cart_rows = "" ;
		$wish_rows = "" ;
		
		$currency_info = $this->wms_store->getCurrentCurrency() ;
		
		$items_count = count($items) ;
		if($items_count > 0){
			$table = "" ;
			$table .= "<table class='table table-bordered table-condensed'>" ;
			$table .= "<tr>" ;
				$table .= "<th>Image</th>" ;
				$table .= "<th>Product Name</th>" ;
		//		$table .= "<th>Model</th>" ;
				$table .= "<th>Quantity</th>" ;
				$table .= "<th>&nbsp;</th>" ;
				$table .= "<th>&nbsp;</th>" ;
				$table .= "<th>Unit Price</th>" ;
				$table .= "<th>Total</th>" ;
			$table .= "</tr>" ;
			
			$cart_table .= $table ;
			$wish_table .= $table ;
			
			for($i = 0; $i < $items_count; $i++){
				//Image	Product Name	Model	Quantity	Unit Price	Total
				$item_id 			= $items[$i]['item_id'] ;
				$item_quantity 		= $items[$i]['item_quantity'] ;
				if($item_quantity < 1){ $item_quantity = 1 ; }
				$item_options 		= $items[$i]['item_options'] ;
				$cart_type 			= $items[$i]['cart_type'] ;
				$item_add_time_int 	= $items[$i]['item_add_time'] ;
				
				$product_info	= $this->wms_store->getProductInfo($item_id) ;
				if($product_info !== false){
					$image_id 			= $product_info->image_id ;
					$item_name 			= $product_info->name ;
					$item_model 		= $product_info->model ;
					$item_unit_price 	= (int)($product_info->price) ;
					
					$item_total_price 	= $item_quantity * $item_unit_price ;
					
					$item_unit_price_string = $currency_info->symbol_left."".$item_unit_price.".00".$currency_info->symbol_right ;
					$item_total_price_string = $currency_info->symbol_left."".$item_total_price.".00".$currency_info->symbol_right ;
					
					$item_image = "" ;
					if($image_id != 0){
						$item_image_info = $this->admin_images->getImageInfo($image_id) ;
						if($item_image_info !== false){
							$item_image = $this->admin_images->getImageElement($item_image_info->image_filename, $this->rootdir.'images/uploads/') ;
						}
					}
					
					$options_string = "" ;
					for($j = 0; $j < count($item_options) ; $j++){
						$item_option_value_info = $this->wms_store->getProductOptionValueInfo($item_options[$j]) ;
						if($item_option_value_info !== false){
							$option_value_id 		= $item_option_value_info[0]['option_value_id'] ;
							$option_id 				= $item_option_value_info[0]['option_id'] ;
							$option_value_name 		= $item_option_value_info[0]['name'] ;
							$option_value_status	= $item_option_value_info[0]['status'] ;
							
							$option_name = "" ;
							$option_info = $this->wms_store->getProductOptionInfo($option_id) ;
							if($option_info !== false){
								$option_name 		= $option_info[0]['name'] ;
								
								$options_string .= "<span>$option_name : $option_value_name</span>" ;
							}
						}
					}//end for count(item_options)
					
					$dat = "" ;
					$dat .= "<input type='text' name='cart_mgt_product_quantity' class='cart_mgt_product_quantity' placeholder='Enter Quantity' value='".$item_quantity."' required=''>" ;
					$dat .= "<input type='hidden' name='cart_mgt_product_id' class='cart_mgt_product_id' value='".$item_id."'>" ;
					$dat .= "<input type='hidden' name='cart_mgt_product_options' class='cart_mgt_product_options' value='".rawurlencode(serialize($item_options))."'>" ;
					$dat .= "<input type='hidden' name='cart_mgt_cart_type' class='cart_mgt_cart_type' value='".$cart_type."'>" ;
					
					$dat .= "<span class='cart_mgt_product_update' title='Update Quantity'>&nbsp;</span>" ;
					$dat .= "<span class='cart_mgt_product_delete' title='Remove Item'>&nbsp;</span>" ;
					if($cart_type == 'cart'){
						$dat .= "<span class='cart_mgt_product_switch' id='cart_mgt_product_sw_wish' title='Add to Wish list'>&nbsp;</span>" ;
						$dat .= "<input type='hidden' name='cart_mgt_new_cart_type' class='cart_mgt_new_cart_type' value='wish'>" ;
					}else if($cart_type == 'wish'){
						$dat .= "<span class='cart_mgt_product_switch' id='cart_mgt_product_sw_cart' title='Add to Shopping Cart'>&nbsp;</span>" ;
						$dat .= "<input type='hidden' name='cart_mgt_new_cart_type' class='cart_mgt_new_cart_type' value='cart'>" ;
					}
					
					
					
					//ROW COLUMNS
					$row = "" ;
					$row = "<tr>" ;
						$row .= "<td class='cart_list_item_image'>".$item_image."</td>" ;
						$row .= "<td class='cart_list_item_name'><div>".$item_name."</div>" ;
							$row .= "<div class='cart_list_item_options'>".$options_string."</div></td>" ;
			//			$row .= "<td class='cart_list_item_model'>".$item_model."</td>" ;
						$row .= "<td class='cart_list_item_quantity'>".$dat."</td>" ;
						$row .= "<td>&nbsp;</td>" ;
						$row .= "<td>&nbsp;</td>" ;
						$row .= "<td class='cart_list_item_unit_price'>".$item_unit_price_string."</td>" ;
						$row .= "<td class='cart_list_item_total'>".$item_total_price_string."</td>" ;
					$row .= "</tr>" ;
					
					if($cart_type == 'cart'){
						$cart_rows .= $row ;
					}else if($cart_type == 'wish'){
						$wish_rows .= $row ;
					}
					
				}//end if product_info
			}//end for
			
			if($cart_rows != ""){
				$cart_table .= $cart_rows."</table>" ;
			}else{ 
				$cart_table .= "<td colspan='6'>There are no items to Display!</td>"."</table>" ; 
			}
			
			if($wish_rows != ""){
				$wish_table .= $wish_rows."</table>" ;
			}else{ 
				$wish_table .= "<td colspan='6'>There are no items to Display!</td>"."</table>" ; 
			}
						
			$o .= $cart_table ;
			$o .= $wish_table ;
		}
		return $o ;
	}
}
			
?>