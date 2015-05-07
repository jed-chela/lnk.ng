<?php

class Admin_store extends CI_Model{
	// Constructor
	function __construct(){
	// Call the Model constructor
        parent::__construct();
		$this->rootdir = $this->config->item('base_url') ;
		
		$this->load->model('wms_store') ;
		$this->load->model('admin_images') ;
		
		$this->menu_selection = 'store' ;
		
		define('OPT_VALUE_PREFIX', 'opt_val') ;
	}
	
	private $menu_selection ;
	
	/* STORE MANAGEMENT. For Customers, Products and Orders */
	
	public function showStoreActions($tag){
		$o = "" ;
		$add_new_product 				= "" ;
		$view_all_products 				= "" ;
		$search_products 				= "" ;
		
		$add_new_category				= "" ;
		$view_all_categories			= "" ;
		$add_product_to_category		= "" ;
		
		$add_new_manufacturer			= "" ;
		$view_all_manufacturers			= "" ;
		
		$add_an_image					= "" ;
		$view_all_images				= "" ;
		$edit_an_image					= "" ;
		
		switch($tag){
			case 'add_new_product' 			: $add_new_product 			= "current" ; break ;
			case 'view_all_products' 		: $view_all_products 		= "current" ; break ;
			case 'search_products' 			: $search_products 			= "current" ; break ;
			
			case 'add_new_category' 		: $add_new_category 		= "current" ; break ;
			case 'view_all_categories' 		: $view_all_categories 		= "current" ; break ;
			case 'add_product_to_category' 	: $add_product_to_category 	= "current" ; break ;
			
			
			case 'add_new_manufacturer' 	: $add_new_manufacturer 	= "current" ; break ;
			case 'view_all_manufacturers' 	: $view_all_manufacturers 	= "current" ; break ;
			
			case 'add_an_image' 			: $add_an_image			 	= "current" ; break ;
			case 'view_all_images' 			: $view_all_images			= "current" ; break ;
			case 'edit_an_image' 			: $edit_an_image			= "current" ; break ;
			
		}
			$o .= "<div class='block_in link-list-vertical'>" ;
				$o .= "<a href='".$this->rootdir."admin/index/store/add_new_product' 		id='".$add_new_product."'><span>Add A New Product</span></a>" ;
				$o .= "<a href='".$this->rootdir."admin/index/store/view_all_products' 		id='".$view_all_products."'><span>View All Products</span></a>" ;
				$o .= "<a href='".$this->rootdir."admin/index/store/search_products' 		id='".$search_products."'><span>Search Products</span></a>" ;
			$o .= "</div>" ;
			$o .= "<div class='spacer-x-medium'>&nbsp;</div>" ;
			$o .= "<div class='block_in link-list-vertical'>" ;
				$o .= "<a href='".$this->rootdir."admin/index/store/add_new_category' 			id='".$add_new_category."'><span>Add A New Product Category</span></a>" ;
				$o .= "<a href='".$this->rootdir."admin/index/store/view_all_categories' 		id='".$view_all_categories."'><span>View All Categories</span></a>" ;
			$o .= "</div>" ;
			$o .= "<div class='spacer-x-medium'>&nbsp;</div>" ;
			$o .= "<div class='block_in link-list-vertical'>" ;
				$o .= "<a href='".$this->rootdir."admin/index/store/add_new_manufacturer' 		id='".$add_new_manufacturer."'><span>Add A New Manufacturer</span></a>" ;
				$o .= "<a href='".$this->rootdir."admin/index/store/view_all_manufacturers' 	id='".$view_all_manufacturers."'><span>View All Manufacturers</span></a>" ;
			$o .= "</div>" ;
			$o .= "<div class='spacer-x-medium'>&nbsp;</div>" ;
			$o .= "<div class='block_in link-list-vertical'>" ;
				$o .= "<a href='".$this->rootdir."admin/index/store/add_an_image' 				id='".$add_an_image."'><span>Add A New Image</span></a>" ;
				$o .= "<a href='".$this->rootdir."admin/index/store/view_all_images' 			id='".$view_all_images."'><span>View All Images</span></a>" ;
			$o .= "</div>" ;
		return $o ;
	}
	
	public function handleStoreActionRequest($index_param, $index_function, $index_p1, $index_p2, $index_p3, $index_p4, $index_p5){
		$this->menu_selection = $index_param ;
		switch($index_function){
			case 'add_new_product'			: return $this->add_new_product() 		; break ;
			case 'view_all_products' 		: return $this->view_all_products()		; break ;
			case 'edit_product_options' 	: return $this->edit_product_options($index_p1)		; break ;
			case 'search_products' 			: return $this->search_products()		; break ;
			
			case 'add_new_category' 		: return $this->add_new_category()		; break ;
			case 'view_all_categories' 		: return $this->view_all_categories()	; break ;
			
			case 'add_new_manufacturer' 	: return $this->add_new_manufacturer()		; break ;
			case 'view_all_manufacturers' 	: return $this->view_all_manufacturers()	; break ;
			
			case 'add_product_to_category' 	: return $this->add_product_to_category($index_p1)	; break ;
			
			case 'edit_product_info' 		: return $this->edit_product_info($index_p1)		; break ;
			
			case 'add_an_image' 		: return $this->add_an_image()					; break ;
			case 'view_all_images' 		: return $this->view_all_images()				; break ;
			case 'edit_an_image' 		: return $this->edit_an_image($index_p1)		; break ;
			
			default : return $this->getActionRequestError() ;
		}
	}
	
	
	public function view_all_products(){
		$o = "" ;
		//Get all products from database
		$all_products = $this->wms_store->getAllProducts("(status = 1 OR status = 2)") ;
//		print_r($all_products) ;
		//Show all products in a table format
		
			$o .= "<div class='block marg_all'>" ;
			$o .= "<div class='spacer-large'></div>" ;
			$o .= "<table class='table table-bordered table-condensed'>" ;
				$o .= "<thead>" ;
					$o .= "<tr>" ;
						$o .= "<th>&nbsp;</th>" ;
						$o .= "<th>Product Name</th>" ;
						$o .= "<th>Manufacturer</th>" ;
						$o .= "<th>Price</th>" ;
						$o .= "<th>Description</th>" ;
						$o .= "<th>Times Viewed</th>" ;
						$o .= "<th>Status</th>" ;
						$o .= "<th>&nbsp;</th>" ;
						$o .= "<th>Options</th>" ;
					$o .= "</tr>" ;
				$o .= "</thead>" ;
				$o .= "<tbody>" ;
				$binary = 0 ;
			for($i = 0; $i < count($all_products); $i++){
				$product_id = $all_products[$i]['product_id'] ;
				$product_info = $this->wms_store->getProductInfo($product_id, "(status = 1 OR status = 2)") ;
				
				if($product_info !== false){
					
					$manufacturer_name = "" ;
					if($product_info->manufacturer_id != ""){
						$manufacturer_info = $this->wms_store->getManufacturerInfo($product_info->manufacturer_id) ;
						if($manufacturer_info !== false ){
							$manufacturer_name = $manufacturer_info->name ;
						}
					}
					
					$product_description = "" ;
					if($product_info->description !== false){ $product_description = $product_info->description->description ; }
					
					$product_status = "" ;
					if($product_info->enabled == 1){
						$product_status = "enabled" ;
					}else if($product_info->enabled == 2){
						$product_status = "disabled" ;
					}
					
					$o .= "<tr>" ;
						$o .= "<td>".($i + 1)."</td>" ;
						$o .= "<td>".$product_info->name."</td>" ;
						$o .= "<td>".$manufacturer_name."</td>" ;
						$o .= "<td>".$product_info->price."</td>" ;
						$o .= "<td class='max_width_3'>".$product_description."</td>" ;
						$o .= "<td>".$product_info->times_viewed."</td>" ;
						$o .= "<td>".$product_status."</td>" ;
						
						$o .= "<td>&nbsp;</td>" ;
						$o .= "<td>" ;
							$o .= "<a href='".$this->rootdir."admin/index/store/edit_product_options/".$product_id."' class='btn btn-primary block_left' title='Edit Product Options' >Options</a>" ;
							$o .= "<span class='block_left'>&nbsp;</span>" ;
							$o .= "<a href='".$this->rootdir."admin/index/store/add_product_to_category/".$product_id."' class='btn btn-primary block_left' title='Add Product to Category' >Add to Category</a>" ;
							$o .= "<span class='block_left'>&nbsp;</span>" ;
							$o .= "<a href='".$this->rootdir."admin/index/store/edit_product_info/".$product_id."' class='btn btn-default block_left' title='Edit this Product Information'>Edit</a>" ;
							$o .= "<span class='block_left'>&nbsp;</span>" ;
							$o .= "<a href='".$this->rootdir."admin/index/store/delete_product/".$product_id."' class='btn btn-danger block_left' title='Delete this Product'>Delete</a>" ;
							$o .= "<span class='block_left'>&nbsp;</span>" ;
						$o .= "</td>" ;
					$o .= "</tr>" ;
					
					if($binary == 0){ $binary = 1 ; 
					}else if($binary == 1){ $binary = 0 ; }
				}//end If
			}// end for
				$o .= "</tbody>" ;
			$o .= "</table>" ;
			$o .= "</div>";
		return $o ;
	}
	
	public function view_all_categories(){
		$o = "" ;
		//Get all products from database
		$all_categories = $this->wms_store->getAllProductCategories("(status = 1 OR status = 2)") ;
		//Show all products in a table format
		
			$o .= "<div class='block marg_all'>" ;
			$o .= "<div class='spacer-large'></div>" ;
			$o .= "<table class='table table-bordered table-condensed'>" ;
				$o .= "<thead>" ;
					$o .= "<tr>" ;
						$o .= "<th>&nbsp;</th>" ;
						$o .= "<th>Category Name/<span class='no_bold'>Product Name</span></th>" ;
						$o .= "<th>Description</th>" ;
						$o .= "<th>No of Products</th>" ;
						$o .= "<th>&nbsp;</th>" ;
						$o .= "<th>&nbsp;</th>" ;
						$o .= "<th>Options</th>" ;
					$o .= "</tr>" ;
				$o .= "</thead>" ;
				$o .= "<tbody>" ;
				$binary = 0 ;
			for($k = 0; $k < count($all_categories); $k++){
				$category_id = $all_categories[$k]['category_id'] ;
				$category_info = $this->wms_store->getCategoryInfo($category_id, "(status = 1 OR status = 2)") ;
				
				if($category_info !== false){
					$category_products_info = $this->wms_store->getAllProductInfoForCategory($category_id) ;
					
					$o .= "<tr>" ;
						$o .= "<th>".($k + 1)."</th>" ;
						$o .= "<th>".$category_info->name."</th>" ;
						$o .= "<td>".$category_info->description."</td>" ;
						$o .= "<td>".count($category_products_info)."</td>" ;
						
						$o .= "<td>&nbsp;</td>" ;
						$o .= "<td>&nbsp;</td>" ;
						
						$o .= "<td>" ;
							$o .= "<a href='".$this->rootdir."admin/index/store/remove_product_from_category/".$category_id."' class='btn btn-primary block_left' title='Add Product to Category' >Remove Item From Category</a>" ;
							$o .= "<span class='block_left'>&nbsp;</span>" ;
							$o .= "<a href='".$this->rootdir."admin/index/store/edit_category_info/".$category_id."' class='btn btn-default block_left' title='Edit this Product Information'>Edit</a>" ;
							$o .= "<span class='block_left'>&nbsp;</span>" ;
							$o .= "<a href='".$this->rootdir."admin/index/store/delete_category/".$category_id."' class='btn btn-danger block_left' title='Delete this Product'>Delete</a>" ;
							$o .= "<span class='block_left'>&nbsp;</span>" ;
						$o .= "</td>" ;
					$o .= "</tr>" ;
					
					if( count($category_products_info) > 0 ){ 
					$all_products = $category_products_info ;
			//		print_r($all_products) ;
						for($i = 0; $i < count($all_products); $i++){
							
							if(isset($all_products[$i]->id)){
							$product_id = $all_products[$i]->id ;
							$product_info = $this->wms_store->getProductInfo($product_id, "(status = 1 OR status = 2)") ;
							
							if($product_info !== false){
								
								$manufacturer_name = "" ;
								if($product_info->manufacturer_id != ""){
									$manufacturer_info = $this->wms_store->getManufacturerInfo($product_info->manufacturer_id) ;
									if($manufacturer_info !== false ){
										$manufacturer_name = $manufacturer_info->name ;
									}
								}
								
								$product_description = "" ;
								if($product_info->description !== false){ $product_description = $product_info->description->description ; }
								
								$product_status = "" ;
								if($product_info->enabled == 1){
									$product_status = "enabled" ;
								}else if($product_info->enabled == 2){
									$product_status = "disabled" ;
								}
								
								$o .= "<tr>" ;
									$o .= "<td>".($i + 1)."</td>" ;
									$o .= "<td class='max_width_3'>".$product_info->name."</td>" ;
									$o .= "<td>".$manufacturer_name."</td>" ;
									$o .= "<td>".$product_info->price."</td>" ;
									
									$o .= "<td>&nbsp;</td>" ;
									$o .= "<td>&nbsp;</td>" ;
									
									$o .= "<td>" ;
										$o .= "<a href='".$this->rootdir."admin/index/store/add_product_to_category/".$product_id."' class='btn btn-primary block_left' title='Add Product to Category' >Remove Item From Category</a>" ;
										$o .= "<span class='block_left'>&nbsp;</span>" ;
										$o .= "<a href='".$this->rootdir."admin/index/store/edit_product_info/".$product_id."' class='btn btn-default block_left' title='Edit this Product Information'>Edit</a>" ;
										$o .= "<span class='block_left'>&nbsp;</span>" ;
										$o .= "<a href='".$this->rootdir."admin/index/store/delete_product/".$product_id."' class='btn btn-danger block_left' title='Delete this Product'>Delete</a>" ;
										$o .= "<span class='block_left'>&nbsp;</span>" ;
									$o .= "</td>" ;
								$o .= "</tr>" ;
							}// end if isset
							}// if product_info
						}//for all_products
					}//if count(category_products)
						
					if($binary == 0){ $binary = 1 ; 
					}else if($binary == 1){ $binary = 0 ; }
				}//end If
			}// end for
				$o .= "</tbody>" ;
			$o .= "</table>" ;
			$o .= "</div>";
		return $o ;
	}
	
	public function add_new_product(){
		$o = "" ;
		$this->admin_forms->err = "" ;
		if($this->input->post('add_new_product_submit') !== false){
			
			$this->form_validation->set_rules('add_new_product_name', 'Product Name', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				$o .= $this->getForm_addNewProductForm() ;
			}
			else
			{
				//Process Form Input
				$new_product_name 				= protect($this->input->post('add_new_product_name') ) ;
				$new_product_desc 				= protectExactText($this->input->post('add_new_product_desc') ) ;
				$new_product_category_id 		= protect($this->input->post('add_new_product_category') ) ;
				$new_product_manufacturer_id 	= protect($this->input->post('add_new_product_manufacturer') ) ;
				$image_id 						= protect($this->input->post('add_new_product_image') ) ;
				$price							= protect($this->input->post('add_new_product_price') ) ;
				
				$res = $this->wms_store->addProduct($new_product_name, $new_product_desc, $new_product_category_id, $new_product_manufacturer_id, $image_id, $price) ;
				if($res[0] === true){
					$msg = "<span class='form-box no_border_bottom' id='form-success'><p>Product ($new_product_name) was added successfully!</p></span>" ;
					$o .= $this->admin_forms->showFormMessage($msg) ;
				}else if($res[0] === false){
					$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'><p>".$res[2]."</p></span>" ;
					
					//Show Form Again Along with form errors
					$o .= $this->getForm_addNewProductForm() ;
				}
			}
		}else{
			//Show Form Again Along with form errors
			$o .= $this->getForm_addNewProductForm() ;
		}
		
		return $o ;
	}
	
	public function add_new_category(){
		$o = "" ;
		$this->admin_forms->err = "" ;
		if($this->input->post('add_new_category_submit') !== false){
			
			$this->form_validation->set_rules('add_new_category_name', 'Category Name', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				$o .= $this->getForm_addNewCategoryForm() ;
			}
			else
			{
				//Process Form Input
				$new_category_name 	= protect($this->input->post('add_new_category_name') ) ;
				$new_category_desc 	= protectExactText($this->input->post('add_new_category_desc') ) ;
								
				$res = $this->wms_store->addProductCategory($new_category_name, $new_category_desc) ;
				if($res[0] === true){
					$msg = "<span class='form-box no_border_bottom' id='form-success'><p>Category ($new_category_name) was added successfully!</p></span>" ;
					$o .= $this->admin_forms->showFormMessage($msg) ;
				}else if($res[0] === false){
					$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'><p>".$res[2]."</p></span>" ;
					
					//Show Form Again Along with form errors
					$o .= $this->getForm_addNewCategoryForm() ;
				}
				
			}
		}else{
			//Show Form Again Along with form errors
			$o .= $this->getForm_addNewCategoryForm() ;
		}
		
		return $o ;
	}
	
	public function add_new_manufacturer(){
		$o = "" ;
		$this->admin_forms->err = "" ;
		if($this->input->post('add_new_manufacturer_submit') !== false){
			
			$this->form_validation->set_rules('add_new_manufacturer_name', 'Manafacturer Name', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				$o .= $this->getForm_addNewManufacturerForm() ;
			}
			else
			{
				//Process Form Input
				$new_manufacturer_name 	= protect($this->input->post('add_new_manufacturer_name') ) ;
				$image_id			 	= protect($this->input->post('manufacturer_image') ) ;
								
				$res = $this->wms_store->addManufacturer($new_manufacturer_name, $image_id) ;
				if($res[0] === true){
					$msg = "<span class='form-box no_border_bottom' id='form-success'><p>Manufacturer ($new_manufacturer_name) was added successfully!</p></span>" ;
					$o .= $this->admin_forms->showFormMessage($msg) ;
				}else if($res[0] === false){
					$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'><p>".$res[2]."</p></span>" ;
					
					//Show Form Again Along with form errors
					$o .= $this->getForm_addNewManufacturerForm() ;
				}
				
			}
		}else{
			//Show Form Again Along with form errors
			$o .= $this->getForm_addNewManufacturerForm() ;
		}
		
		return $o ;
	}
	
	
	public function add_product_to_category($product_id){
		$o = "" ;
		$this->admin_forms->err = "" ;
		if($this->input->post('add_product_to_category_submit') !== false){
			
			$this->form_validation->set_rules('add_new_product_category', 'Category Name', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				$o .= $this->getForm_addProductToCategory($product_id) ;
			}
			else
			{
				//Process Form Input
				$product_id 		= protect($this->input->post('add_product_to_category_product_id') ) ;
				$product_name 		= protect($this->input->post('add_product_to_category_product_name') ) ;
				$category_id	 	= protect($this->input->post('add_new_product_category') ) ;
				
				//Get extra details for reporting purpose
				$category_name = "" ;
				$category_info = $this->wms_store->getCategoryInfo($category_id) ;
				if($category_info !== false){
					$category_name = $category_info->name ;
				}
				
				$res = $this->wms_store->addProductToCategory($product_id, $category_id) ;
				if($res[0] === true){
					$msg = "<span class='form-box no_border_bottom' id='form-success'><p>Product (".$product_name.") was successfully added to Category (".$category_name.")!</p></span>" ;
					$o .= $this->admin_forms->showFormMessage($msg) ;
				}else if($res[0] === false){
					$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'>" ;
					$this->admin_forms->err .= "<p>An Error Occurred While Adding the Product (".$product_name.") to the Category (".$category_name.")! ".$res[2]."</p></span>" ;
					
					//Show Form Again Along with form errors
					$o .= $this->getForm_addProductToCategory($product_id) ;
				}
				
			}
		}else{
			//Show Form Again Along with form errors
			$o .= $this->getForm_addProductToCategory($product_id) ;
		}
		
		return $o ;
	}
	
	public function edit_product_info($product_id){
		$o = "" ;
		$this->admin_forms->err = "" ;
		if($this->input->post('edit_product_info_submit') !== false){
			
			$this->form_validation->set_rules('edit_product_info_product_name', 'Product Name', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				$o .= $this->getForm_editProductInfo($product_id) ;
			}
			else
			{
				//Process Form Input
				$product_id 				= protect($this->input->post('edit_product_info_product_id') ) ;
				$name 						= protect($this->input->post('edit_product_info_product_name') ) ;
				
				$description				= protectExactText($this->input->post('edit_product_info_description') ) ;
				
				$model 						= protect($this->input->post('edit_product_info_model') ) ;
				$location 					= protect($this->input->post('edit_product_info_location') ) ;
				$quantity_on_shelf 			= protect($this->input->post('edit_product_info_quantity') ) ;
				$image_id	 				= protect($this->input->post('edit_product_info_image_id') ) ;
				$manufacturer_id			= protect($this->input->post('edit_product_info_manufacturer') ) ;
				$shipping 					= protect($this->input->post('edit_product_info_shipping') ) ;
				
				$price	 					= protect($this->input->post('edit_product_info_price') ) ;
				$points						= protect($this->input->post('edit_product_info_points') ) ;
				$date_available 			= protect($this->input->post('edit_product_info_date_available') ) ;
				
				$weight	 					= protect($this->input->post('edit_product_info_weight') ) ;
				$weight_class_id			= protect($this->input->post('edit_product_info_weight_class_id') ) ;
				
				$length 					= protect($this->input->post('edit_product_info_length') ) ;
				$width 						= protect($this->input->post('edit_product_info_width') ) ;
				$height 					= protect($this->input->post('edit_product_info_height') ) ;
				$length_class_id			= protect($this->input->post('edit_product_info_length_class_id') ) ;
				
				$sort_order 				= protect($this->input->post('edit_product_info_sort_order') ) ;
				$enabled 					= protect($this->input->post('edit_product_info_enabled') ) ;
				$status 					= protect($this->input->post('edit_product_info_status') ) ;
				
				$tax_class_id				= protect($this->input->post('edit_product_info_tax_class') ) ;
				
				$sku 						= protect($this->input->post('edit_product_info_sku') ) ;
				$upc 						= protect($this->input->post('edit_product_info_upc') ) ;
				$ean						= protect($this->input->post('edit_product_info_ean') ) ;
				$jan 						= protect($this->input->post('edit_product_info_jan') ) ;
				$isbn 						= protect($this->input->post('edit_product_info_isbn') ) ;
				$mpn						= protect($this->input->post('edit_product_info_mpn') ) ;
				
				$subtract 					= protect($this->input->post('edit_product_info_subtract') ) ;
				$minimum					= protect($this->input->post('edit_product_info_minimum') ) ;
				
				$times_viewed				= protect($this->input->post('edit_product_info_times_viewed') ) ;
				
				$res_desc = $this->wms_store->editProductDescription($product_id, $description) ;
				$res = $this->wms_store->editProductInfo($product_id, $name, $sku, $upc, $ean, $jan, $isbn, $mpn, $location, $quantity_on_shelf, $image_id, $manufacturer_id, $shipping, $price, $points, $tax_class_id, $date_available, $weight, $weight_class_id, $length, $width, $height, $length_class_id, $subtract, $minimum, $sort_order, $enabled, $status, $times_viewed) ;
				if(($res_desc === true) && ($res === true) ){
					$msg = "<span class='form-box no_border_bottom' id='form-success'><p>Product (".$name.") was successfully edited!</p></span>" ;
					$o .= $this->admin_forms->showFormMessage($msg) ;
				}else if($res === false){
					$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'>" ;
					$this->admin_forms->err .= "<p>An Error Occurred While Editing the Product (".$product_name.")!</p></span>" ;
					
					//Show Form Again Along with form errors
					$o .= $this->getForm_editProductInfo($product_id) ;
				}
				
			}
		}else{
			//Show Form Again Along with form errors
			$o .= $this->getForm_editProductInfo($product_id) ;
		}
		
		return $o ;
	}
	
	public function add_an_image(){
		return $this->admin_images->addAnImage_FormHandler() ;
	}
	
	public function edit_an_image($image_id){
		return $this->admin_images->editAnImage_FormHandler($image_id) ;
	}
	
	public function view_all_images(){
		return $this->admin_images->viewAllImages();
	}
	
	public function edit_product_options($product_id){
		$o = "" ;
		$this->admin_forms->err = "" ;
		if($this->input->post('edit_product_options_submit') !== false){
			
			$this->form_validation->set_rules('edit_product_options_product_id', 'Product ID', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				$o .= $this->getForm_editProductOptions($product_id) ;
			}
			else
			{
				//Process Form Input
				$product_id 				= protect($this->input->post('edit_product_options_product_id') ) ;
				$product_name 				= protect($this->input->post('edit_product_options_product_name') ) ;
				$options 					= array() ;
				
				$available_option_values = $this->wms_store->getAllAvailableOptionValues() ;
				for($i = 0; $i < count($available_option_values) ; $i++){
					$option_value_id 			= $available_option_values[$i]['option_value_id'] ;
					$option_value_field_name 	= OPT_VALUE_PREFIX.$option_value_id ;
					if($this->input->post($option_value_field_name) !== false){
						$option_value_field_value	= $option_value_id ;
						array_push($options, $option_value_id) ;
					}
				}
				
				$res = $this->wms_store->editProductOptions($product_id, $options) ;
				if($res === true){
					$msg = "<span class='form-box no_border_bottom' id='form-success'><p>Product Options for (".$product_name.") were successfully edited!</p></span>" ;
					$o .= $this->admin_forms->showFormMessage($msg) ;
				}else if($res === false){
					$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'>" ;
					$this->admin_forms->err .= "<p>An Error Occurred While Editing the Product Options (".$product_name.")!</p></span>" ;
				}	
				//Show Form Again Along with form errors
				$o .= $this->getForm_editProductOptions($product_id) ;
				
			}
		}else{
			//Show Form Again Along with form errors
			$o .= $this->getForm_editProductOptions($product_id) ;
		}
		
		return $o ;
	}
	
	private function getForm_editProductOptions($product_id){
		$o = "" ;
		if($product_id != ""){
			$product_info = $this->wms_store->getProductInfo($product_id, "(status = 1 OR status = 2)") ;
			if($product_info !== false){
				$available_option_values = $this->wms_store->getAllAvailableOptionValues() ;
				$product_options_value_ids = $this->wms_store->getProductOptionValues($product_info->id) ;
				
				//FIELDS
				$form_fields_html = array() ;
				
					//Form submit button 2
					$field = $this->admin_forms->getSubmitButtonField("Save Changes", "edit_product_options_submit", "", "submit", true, $this->rootdir."admin/index/store/view_all_products/", "Cancel") ;
					array_push($form_fields_html, $field) ;
					
					//Product Name
					$field = $this->admin_forms->getInputText("Product Name", "edit_product_options_product_name", "", "", "View Product Name", "", "readonly", $product_info->name) ;
					array_push($form_fields_html, $field) ;
					
					//Product ID (Hidden Field)
					$field = $this->admin_forms->getInputHidden("edit_product_options_product_id", "", $product_id) ;
					array_push($form_fields_html, $field) ;
					
					for($i = 0 ; $i < count($available_option_values) ; $i++){
						//option_value_id	language_id	option_id	name	status
						$option_value_id 		= $available_option_values[$i]['option_value_id'] ;
						$option_id 				= $available_option_values[$i]['option_id'] ;
						$option_value_name 		= $available_option_values[$i]['name'] ;
						$option_value_status	= $available_option_values[$i]['status'] ;
						
						$option_name = "" ;
						$option_sort_order = "" ;
						$option_info = $this->wms_store->getProductOptionInfo($option_id) ;
						if($option_info !== false){
							$option_name 		= $option_info[0]['name'] ;
							$option_sort_order 	= $option_info[0]['sort_order'] ;
						}
						
						$field_name = OPT_VALUE_PREFIX.$option_value_id ;
						$field_id	= OPT_VALUE_PREFIX.$option_value_id ;
						
						$checked = "" ;
						if($product_options_value_ids !== false){
							for($j = 0; $j < count($product_options_value_ids); $j++){
								if($option_value_id == $product_options_value_ids[$j]['option_value_id']){
									$checked = "checked" ;
									break ;
								}
							}		
						}else{
							//This product has no option values assigned
						}
						
						//Option Value Fields
						$field = $this->admin_forms->getInputCheckbox("$option_name ($option_value_name)", $field_name, "", $field_id, $checked, "", $option_value_id) ;
						array_push($form_fields_html, $field) ;
					
					}//end for loop
			
					//Form submit button 2
					$submit_field = $this->admin_forms->getSubmitButtonField("Save Changes", "edit_product_options_submit", "", "submit", true, $this->rootdir."admin/index/store/view_all_products/", "Cancel") ;
						
				//Get Form HTML	
				$form_html = $this->admin_forms->getRegularForm("Edit Product Options", $form_fields_html, $submit_field, "post", "", "", "inline_label", "block_auto") ;
				$o .= $form_html ;
			}
		}
		return $o ;
	}
	private function getForm_editAnImage($image_id){
		$cancel_url = $this->rootdir."admin/index/store/" ;
		return $this->admin_images->editAnImage_Form($image_id, $cancel_url) ;
	}
	
	private function getForm_addAnImage($product_id){
		$cancel_url = $this->rootdir."admin/index/store/" ;
		return $this->admin_images->addAnImage_Form($cancel_url) ;
	}
	
	private function getForm_editProductInfo($product_id){
		if($product_id != ""){
			$product_info = $this->wms_store->getProductInfo($product_id, "(status = 1 OR status = 2)") ;
			
			if($product_info !== false){
			
			$product_description = "" ;
			$desc_info = $this->wms_store->getProductDecription($product_id) ;
			if($desc_info !== false){
				$product_description = $desc_info->description ;
			}
			
			$manufacturers		= $this->wms_store->getAllManufacturers() ;
			
			$default_manufacturer_options_array = array("" => "-- None Selected --") ;
			$manufacturer_options_array 		= array() ;
			for($i = 0; $i < count($manufacturers); $i++){
				$manufacturer_id = $manufacturers[$i]['manufacturer_id'] ;
				$manufacturer_info = $this->wms_store->getManufacturerInfo($manufacturer_id) ;
				if($manufacturer_info !== false){
					$option_array = array( " ".(string)$manufacturer_info->id." " => (string)($manufacturer_info->name) ) ;
				//	print_r($option_array) ;
					$manufacturer_options_array = array_merge($manufacturer_options_array, $option_array) ;
					
				}
			}
			//print_r($manufacturer_options_array) ;
			
				//FIELDS
				$form_fields_html = array() ;
					//Product Name
					$field = $this->admin_forms->getInputText("Product Name", "edit_product_info_product_name", "", "", "Enter Product Name", "required", "", $product_info->name) ;
					array_push($form_fields_html, $field) ;
					
					//Product ID (Hidden Field)
					$field = $this->admin_forms->getInputHidden("edit_product_info_product_id", "", $product_id) ;
					array_push($form_fields_html, $field) ;
					
					//Product Description
					$field = $this->admin_forms->getTextarea("Product Description", "edit_product_info_description", "", "", "Enter Product Description", "","",$product_description) ;
					array_push($form_fields_html, $field) ;
					
					//Model
					$field = $this->admin_forms->getInputText("Product Model", "edit_product_info_model", "", "", "Enter Product Model", "", "", $product_info->model) ;
					array_push($form_fields_html, $field) ;
					
					//Product Location
					$field = $this->admin_forms->getInputText("Product Location", "edit_product_info_location", "", "", "Enter Product Location", "", "", $product_info->location) ;
					array_push($form_fields_html, $field) ;
					
					//Quantity On Shelf
					$field = $this->admin_forms->getInputText("Quantity Available", "edit_product_info_quantity", "", "", "Enter Quantity Available", "", "", $product_info->quantity_on_shelf) ;
					array_push($form_fields_html, $field) ;
					
					//Image ID
					$field = $this->admin_forms->getInputText("Image ID", "edit_product_info_image_id", "", "", "Enter Image ID", "", "", $product_info->image_id) ;
					array_push($form_fields_html, $field) ;
					
					//Get Manufacturer Info
					$this_manufacturer_name = "" ;
					$this_manufacturer_info = $this->wms_store->getManufacturerInfo($product_info->manufacturer_id) ;
					if($this_manufacturer_info !== false){
						$this_manufacturer_name = $this_manufacturer_info->name ;
					}
					
					$selected_option = " ".$product_info->manufacturer_id." " ;
					
					
					
					//Manufacturer Name(Select Field)
					$field = $this->admin_forms->getRegularSelect("Manufacturer (".$this_manufacturer_name.")", "edit_product_info_manufacturer", "", "", "",true, $default_manufacturer_options_array, $manufacturer_options_array,  $selected_option) ;
					array_push($form_fields_html, $field) ;
					
					$shipping_enabled = "YES" ;
					if($product_info->shipping_enabled != 1){
						$shipping_enabled = "NO" ;
					}
					
					$default_shipping_options_array = array( " 1" => "YES", " 0" => "NO" ) ;
					
					$selected_option = " ".$product_info->shipping_enabled ;
					
					//Shipping Enabled (Select Field)
					$field = $this->admin_forms->getRegularSelect("Enable Shipping (".$shipping_enabled.")", "edit_product_info_shipping", "", "", "",true, $default_shipping_options_array, array(), $selected_option ) ;
					array_push($form_fields_html, $field) ;
					
					
					$current_currency = $this->wms_store->getCurrentCurrency() ;
					
					//Price
					$field = $this->admin_forms->getInputText("Price (".$current_currency->name.")", "edit_product_info_price", "", "", "Enter Price", "", "", $product_info->price) ;
					array_push($form_fields_html, $field) ;
					
					//Points
					$field = $this->admin_forms->getInputText("Points", "edit_product_info_points", "", "", "Enter Points", "", "", $product_info->points) ;
					array_push($form_fields_html, $field) ;
					
					//Date Available
					$field = $this->admin_forms->getInputDate("datetime", "Date Available", "edit_product_info_date_available", "", "", "Enter Date and Time (YYYY MM DD HH:MM:SS)", "", "", $product_info->date_available) ;
					array_push($form_fields_html, $field) ;
					
					//Weight
					$field = $this->admin_forms->getInputText("Weight", "edit_product_info_weight", "", "", "Enter Weight", "", "", $product_info->weight) ;
					array_push($form_fields_html, $field) ;
					
					
					$weight_class_options_array = array() ;
					$weight_class_info = $this->wms_store->getWeightClasses() ;
					if($weight_class_info !== false){
						for($k = 0; $k < count($weight_class_info); $k++){
							array_push($weight_class_options_array, $weight_class_info[$k]['weight_class_id']) ; 
						}
					}
					
					$selected_option = $product_info->weight_class_id ;
					
					//Weight Class ID
					$field = $this->admin_forms->getRegularSelect("Weight Class ID (".$product_info->weight_class_id.")", "edit_product_info_weight_class_id", "", "", "", false, array(), $weight_class_options_array, $selected_option ) ;
					array_push($form_fields_html, $field) ;
					
					//Length
					$field = $this->admin_forms->getInputText("Length", "edit_product_info_length", "", "", "Enter Length", "", "", $product_info->length) ;
					array_push($form_fields_html, $field) ;
					
					//Width
					$field = $this->admin_forms->getInputText("Width", "edit_product_info_width", "", "", "Enter Width", "", "", $product_info->width) ;
					array_push($form_fields_html, $field) ;
					
					//Height
					$field = $this->admin_forms->getInputText("Height", "edit_product_info_height", "", "", "Enter Height", "", "", $product_info->height) ;
					array_push($form_fields_html, $field) ;
					
					//Length Class ID
					$field = $this->admin_forms->getRegularSelect("Length Class ID (".$product_info->length_class_id.")", "edit_product_info_length_class_id", "", "", "", false, array(), $weight_class_options_array ) ;
					array_push($form_fields_html, $field) ;
					
					//Sort Order
					$field = $this->admin_forms->getInputText("Sort Order", "edit_product_info_sort_order", "", "", "Enter Sort Order", "", "", $product_info->sort_order) ;
					array_push($form_fields_html, $field) ;
					
					//Enabled Field Settings
					$status_enabled = "TRUE" ;
					if($product_info->enabled != 1){
						$status_enabled = "FALSE" ;
					}
					
					$default_status_options_array = array( " 1" => "TRUE", " 2" => "FALSE" ) ;
					
					$selected_option = " ".$product_info->enabled ;
					
					//Enabled (Select Field)
					$field = $this->admin_forms->getRegularSelect("Enabled (".$status_enabled.")", "edit_product_info_enabled", "", "", "",true, $default_status_options_array, array(), $selected_option ) ;
					array_push($form_fields_html, $field) ;
					
					
					//Status (Hidden Field)
					$field = $this->admin_forms->getInputHidden("edit_product_info_status", "", $product_info->status) ;
					array_push($form_fields_html, $field) ;
					
					//Date Added
					$field = $this->admin_forms->getInputDate("datetime","Date Added", "", "", "", "Date Added", "", "readonly", $product_info->date_added) ;
					array_push($form_fields_html, $field) ;
					
					//Date Last modified
					$field = $this->admin_forms->getInputDate("datetime","Date Modified", "", "", "", "Date Modified", "", "readonly", $product_info->date_modified) ;
					array_push($form_fields_html, $field) ;
					
					//Times Viewed
					$field = $this->admin_forms->getInputText("Times Viewed", "edit_product_info_times_viewed", "", "", "Times Viewed", "", "", $product_info->times_viewed) ;
					array_push($form_fields_html, $field) ;
					
					//Form submit button 1
					$submit_field = $this->admin_forms->getSubmitButtonField("Save Changes", "edit_product_info_submit", "", "submit", true, $this->rootdir."admin/index/store/view_all_products/", "Cancel") ;
					array_push($form_fields_html, "<div class='block'>".$submit_field."</div>") ;
					
					/*** Advanced Product Info Fields  ***/
					$field = "<h5 class='marg_all'><u>Advanced Product Info</u></h5>" ;
					array_push($form_fields_html, $field) ;
					
					//Tax Class ID
					$field = $this->admin_forms->getInputText("Tax Class ID", "edit_product_info_tax_class", "", "", "Select Tax Class", "", "", $product_info->tax_class_id) ;
					array_push($form_fields_html, $field) ;
					
					//SKU
					$field = $this->admin_forms->getInputText("SKU", "edit_product_info_sku", "", "", "Enter SKU", "", "", $product_info->sku) ;
					array_push($form_fields_html, $field) ;
					
					//UPC
					$field = $this->admin_forms->getInputText("UPC", "edit_product_info_upc", "", "", "Enter UPC", "", "", $product_info->upc) ;
					array_push($form_fields_html, $field) ;
					
					//EAN
					$field = $this->admin_forms->getInputText("EAN", "edit_product_info_ean", "", "", "Enter EAN", "", "", $product_info->ean) ;
					array_push($form_fields_html, $field) ;
					
					//JAN
					$field = $this->admin_forms->getInputText("JAN", "edit_product_info_jan", "", "", "Enter JAN", "", "", $product_info->jan) ;
					array_push($form_fields_html, $field) ;
					
					//ISBN
					$field = $this->admin_forms->getInputText("ISBN", "edit_product_info_isbn", "", "", "Enter ISBN", "", "", $product_info->isbn) ;
					array_push($form_fields_html, $field) ;
					
					//MPN
					$field = $this->admin_forms->getInputText("MPN", "edit_product_info_mpn", "", "", "Enter MPN", "", "", $product_info->mpn) ;
					array_push($form_fields_html, $field) ;
					
					//Subtract
					$field = $this->admin_forms->getInputText("Subtract", "edit_product_info_subtract", "", "", "Enter Subtract", "", "", $product_info->subtract) ;
					array_push($form_fields_html, $field) ;
					
					//Minimum
					$field = $this->admin_forms->getInputText("Minimum", "edit_product_info_minimum", "", "", "Enter Minimum", "", "", $product_info->minimum) ;
					array_push($form_fields_html, $field) ;
					
					
					
			//	id, name, model, location, quantity_on_shelf, image_id, manufacturer_id, shipping_enabled,
			//	price, points, tax_class_id, date_available, weight, weight_class_id, length, width, height, length_class_id,
			//	sku, upc, ean, jan, isbn, mpn, subtract, minimum, sort_order, status, date_added, date_modified, times_viewed
					
						
					//Form submit button 2
					$submit_field = $this->admin_forms->getSubmitButtonField("Save Changes", "edit_product_info_submit", "", "submit", true, $this->rootdir."admin/index/store/view_all_products/", "Cancel") ;
						
				//Get Form HTML	
				$form_html = $this->admin_forms->getRegularForm("Edit Product Information", $form_fields_html, $submit_field, "post", "", "", "inline_label") ;
			}
		}
		return $form_html ;
		
	}
	
	private function getForm_addProductToCategory($product_id){
		if($product_id != ""){
			$product_info = $this->wms_store->getProductInfo($product_id) ;
			
			if($product_info !== false){
				
				$categories			= $this->wms_store->getAllProductCategories() ;
			
				$default_category_options_array = array("" => "-- None Selected --") ;
				$category_options_array 		= array() ;
				for($i = 0; $i < count($categories); $i++){
					$category_id = $categories[$i]['category_id'] ;
					$category_info = $this->wms_store->getCategoryInfo($category_id) ;
					if($category_info !== false){
						$option_array = array(" ".$category_info->category_id." " => $category_info->name) ;
					//	array_push($category_options_array, $option_array) ;
						$category_options_array = array_merge($category_options_array, $option_array) ;
					}
				}
				
				
				//FIELDS
				$form_fields_html = array() ;
					//Product Name
					$field = $this->admin_forms->getInputText("Product Name", "add_product_to_category_product_name", "", "", "This is the Product Name", "required", "readonly", $product_info->name) ;
					array_push($form_fields_html, $field) ;
					
					//Product ID (Hidden Field)
					$field = $this->admin_forms->getInputHidden("add_product_to_category_product_id", "", $product_id) ;
					array_push($form_fields_html, $field) ;
					
					//Category Name (Select Field)
					$field = $this->admin_forms->getRegularSelect("Product Category", "add_new_product_category", "", "", "",true, $default_category_options_array, $category_options_array ) ;
					array_push($form_fields_html, $field) ;
						
					//Form submit button
						$submit_field = $this->admin_forms->getSubmitButtonField("Add Product To Category", "add_product_to_category_submit", "", "submit", true, $this->rootdir."admin/index/store/view_all_products/", "Cancel") ;
						
				//Get Form HTML	
				$form_html = $this->admin_forms->getRegularForm("Add Product To Category", $form_fields_html, $submit_field, "post", "", "", "inline_label") ;
			}
		}
		return $form_html ;
		
	}
	private function getForm_addNewManufacturerForm(){
			//FIELDS
				$form_fields_html = array() ;
				//Manufacturer Name
				$field = $this->admin_forms->getInputText("Manufacturer Name", "add_new_manufacturer_name", "", "", "Enter Name for new Manufacturer", "required") ;
				array_push($form_fields_html, $field) ;
				
				//Manufacturer Description
				$field = $this->admin_forms->getTextarea("Image ID", "manufacturer_image", "", "", "Enter Image ID for new Manufacturer", "") ;
				array_push($form_fields_html, $field) ;
				
			//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonField("Add Manufacturer", "add_new_manufacturer_submit", "", "submit", true, $this->rootdir."admin/index/store/", "Cancel") ;
				
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Add New Manufacturer", $form_fields_html, $submit_field, "post", "", "", "inline_label") ;
					
		return $form_html ;
	}
	
	private function getForm_addNewCategoryForm(){
			//FIELDS
				$form_fields_html = array() ;
				//Category Name
				$field = $this->admin_forms->getInputText("Category Name", "add_new_category_name", "", "", "Enter Name for new Category", "required") ;
				array_push($form_fields_html, $field) ;
				
				//Category Description
				$field = $this->admin_forms->getTextarea("Category Description", "add_new_category_desc", "", "", "Enter Description for new Category", "") ;
				array_push($form_fields_html, $field) ;
				
			//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonField("Add Category", "add_new_category_submit", "", "submit", true, $this->rootdir."admin/index/store/", "Cancel") ;
				
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Add New Category", $form_fields_html, $submit_field, "post", "", "", "inline_label") ;
					
		return $form_html ;
	}
	
	private function getForm_addNewProductForm(){
		$o = "" ;					
			
			$categories			= $this->wms_store->getAllProductCategories() ;
			
			$manufacturers		= $this->wms_store->getAllManufacturers() ;
			
			$default_category_options_array = array(array("-- None Selected --", "")) ;
			$category_options_array 		= array() ;
			for($i = 0; $i < count($categories); $i++){
				$category_id = $categories[$i]['category_id'] ;
				$category_info = $this->wms_store->getCategoryInfo($category_id) ;
				if($category_info !== false){
					$option_array = array($category_info->name, $category_info->category_id) ;
					array_push($category_options_array, $option_array) ;
				}
			}
			
			$default_manufacturer_options_array = array(array("-- None Selected --", "")) ;
			$manufacturer_options_array 		= array() ;
			for($i = 0; $i < count($manufacturers); $i++){
				$manufacturer_id = $manufacturers[$i]['manufacturer_id'] ;
				$manufacturer_info = $this->wms_store->getManufacturerInfo($manufacturer_id) ;
				if($manufacturer_info !== false){
					$option_array = array($manufacturer_info->name, $manufacturer_info->id) ;
					array_push($manufacturer_options_array, $option_array) ;
				}
			}
			
			//FIELDS
				$form_fields_html = array() ;
				//Product Name
				$field = $this->admin_forms->getInputText("Product Name", "add_new_product_name", "", "", "Enter Name for new Product", "required") ;
				array_push($form_fields_html, $field) ;
				
				//Product Description
				$field = $this->admin_forms->getTextarea("Product Description", "add_new_product_desc", "", "", "Enter Description for new Product", "") ;
				array_push($form_fields_html, $field) ;
				
				//Product Category
				$field = $this->admin_forms->getRegularSelect("Product Category", "add_new_product_category", "", "", "",true, $default_category_options_array, $category_options_array ) ;
				array_push($form_fields_html, $field) ;
				
				//Product Manufacturer
				$field = $this->admin_forms->getRegularSelect("Product Manufacturer", "add_new_product_manufacturer", "", "", "",true, $default_manufacturer_options_array, $manufacturer_options_array ) ;
				array_push($form_fields_html, $field) ;
				
				//Product Name
				$field = $this->admin_forms->getInputText("Image ID", "add_new_product_image", "", "", "Enter Image ID for new Product", "") ;
				array_push($form_fields_html, $field) ;
				
				$current_currency = $this->wms_store->getCurrentCurrency() ;
				//Product Name
				$field = $this->admin_forms->getInputText("Price (".$current_currency->name.")", "add_new_product_price", "", "", "Enter Price for new Product", "") ;
				array_push($form_fields_html, $field) ;
				
			//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonField("Add Product", "add_new_product_submit", "", "submit", true, $this->rootdir."admin/index/store/", "Cancel") ;
				
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Add New Product", $form_fields_html, $submit_field, "post", "", "", "inline_label") ;
					
		return $form_html ;
	}
	
	private function getActionRequestError(){
		$o = "" ;
		$o .= "<div class='spacer-very-large'></div>" ;
		$o .= "<div id='form-error' class='pad_all'>The Action you requested for could not be carried out. ";
			$o .= "<a href=".$this->rootdir."admin/index/".$this->menu_selection.">Go Back</a>" ;
		$o .= "</div>" ;
		$o .= "<div class='spacer-very-large'></div>" ;
		return $o ;
	}
	
	
	
}
?>