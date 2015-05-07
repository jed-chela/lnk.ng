<?php

class Admin_images extends CI_Model{
	// Constructor
	function __construct(){
	// Call the Model constructor
        parent::__construct();
		$this->rootdir = $this->config->item('base_url') ;
		
		$this->image_uploads_dir = $this->rootdir."images/uploads/" ;
	}
	
	public $image_uploads_dir ;
	public $rootdir ;
	
	public function addAnImage_FormHandler(){
		$o = "" ;
		$this->admin_forms->err = "" ;
		if($this->input->post('add_new_image_submit') !== false){
			
			$this->form_validation->set_rules('add_new_image_file', 'Image Title', '');
			
			$default_upload_path = 'images/uploads' ;
			
			$config['upload_path'] = $default_upload_path;
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']	= '2048';
			
			$this->load->library('upload', $config);
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				$o .= $this->addAnImage_Form() ;
			}
			else
			{
				if ( ! $this->upload->do_upload('add_new_image_file'))
				{
					$error = array('error' => $this->upload->display_errors());
		
					$this->admin_forms->err .= $error['error'] ;
					
					//Show Form Again Along with form errors
					$o .= $this->addAnImage_Form() ;
				}
				else
				{
					$data = $this->upload->data();
					
					/*
					Array
					(
						[file_name]    => mypic.jpg
						[file_type]    => image/jpeg
						[file_path]    => /path/to/your/upload/
						[full_path]    => /path/to/your/upload/jpg.jpg
						[raw_name]     => mypic
						[orig_name]    => mypic.jpg
						[client_name]  => mypic.jpg
						[file_ext]     => .jpg
						[file_size]    => 22.2
						[is_image]     => 1
						[image_width]  => 800
						[image_height] => 600
						[image_type]   => jpeg
						[image_size_str] => width="800" height="200"
					)
					*/
					//user_id	image_id	image_filename image_full_path	length_x	length_y	size	image_type	title description	function	type	status	time
					
					
					//Process Form Input
					$image_description			= protect($this->input->post('add_new_image_description') ) ;
					
					//Get Necessary Values
					$image_title				= $data['file_name'] ;
					$user_id 					= $this->user_sessions->getUserId() ;
					$image_id 					= createAnId("images","image_id") ;
					$image_filename				= $data['file_name'] ;
					$image_full_path			= $data['full_path'] ;
					$length_x					= $data['image_width'] ;
					$length_y					= $data['image_height'] ;
					$size						= $data['file_size'] ;
					$image_type					= $data['image_type'] ;
					
					$image_ext					= $data['file_ext'] ;
					
					//Rename Image
					$new_image_filename = "img_".$image_id.$image_ext ;
					if(rename($image_full_path, $default_upload_path."/".$new_image_filename) === true ){
						$image_filename = $new_image_filename ;
						$realpath = realpath( $default_upload_path) ;
						$realpath = str_replace("\\","/", $realpath) ;
						$image_full_path = $realpath."/".$new_image_filename;	
					}
					

					$res = $this->addANewImageToDB($user_id, $image_id, $image_filename, $image_full_path, $length_x, $length_y, $size, $image_type, $image_title, $image_description) ;
					if($res === true){
						$msg = "<span class='form-box no_border_bottom' id='form-success'><p>The Image (".$image_filename.") was successfully Uploaded!</p></span>" ;
						echo "<script>location.href = '".$this->rootdir."admin/index/news/view_all_images?form_success=add_an_image' ;</script>" ;
						$o .= $this->admin_forms->showFormMessage($msg) ;
					}else if($res === false){
						$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'>" ;
						$this->admin_forms->err .= "<p>An Error Occurred While Uploading the Image!</p></span>" ;
						
						//Show Form Again Along with form errors
						$o .= $this->addAnImage_Form() ;
					}
				}	
			}
		}else{
			//Show Form Again Along with form errors
			$o .= $this->addAnImage_Form() ;
		}
		
		return $o ;
	}
	
	public function editAnImage_FormHandler($image_id){
		$o = "" ;
		if($image_id != ""){
			$this->admin_forms->err = "" ;
			if($this->input->post('edit_image_submit') !== false){
				
				$this->form_validation->set_rules('edit_image_id', 'Image ID', 'required');
				
				if ($this->form_validation->run() == FALSE){
					//Show Form Again Along with form errors
					$o .= $this->editAnImage_Form($image_id) ;
				}
				else
				{
					//Process Form Input
					$image_id					= protect($this->input->post('edit_image_id') ) ;
					$image_title				= protect($this->input->post('edit_image_title') ) ;
					$image_description			= protect($this->input->post('edit_image_description') ) ;				
					
					$res = $this->updateImageInfo($image_id, $image_title, $image_description) ;
					if($res === true){
						$msg = "<span class='form-box no_border_bottom' id='form-success'><p>The Image (".$image_id.") was successfully Edited!</p></span>" ;
						$o .= $this->admin_forms->showFormMessage($msg) ;
					}else if($res === false){
						$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'>" ;
						$this->admin_forms->err .= "<p>An Error Occurred While Editing the Image!</p></span>" ;
						
						//Show Form Again Along with form errors
						$o .= $this->editAnImage_Form($image_id) ;
					}
				}
			}else{
				//Show Form Again Along with form errors
				$o .= $this->editAnImage_Form($image_id) ;
			}
		}else{
			$o .= $this->getActionRequestError() ;
		}
		return $o ;
	}
	
	public function editAnImage_Form($image_id, $cancel_url = ""){	
		$image_info = $this->getImageInfo($image_id) ;
		if($image_info !== false){
			
			//FIELDS
			$form_fields_html = array() ;
				//Image File Name (Disabled)
				$field = $this->admin_forms->getInputText("Image File Name", "", "", "long_input", "Image File Name", "", "disabled", $image_info->image_filename) ;
				array_push($form_fields_html, $field) ;
				
				//Image ID(Hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_image_id", "", $image_info->image_id) ;
				array_push($form_fields_html, $field) ;
				
				//Image Title
				$field = $this->admin_forms->getInputText("Image Title", "edit_image_title", "", "", "Enter Image Title", "maxlength='95'","",$image_info->title) ;
				array_push($form_fields_html, $field) ;
				
				//Image Description
				$field = $this->admin_forms->getTextarea("Image Description", "edit_image_description", "", "", "Enter Image Description", "maxlength='500'","",$image_info->description) ;
				array_push($form_fields_html, $field) ;
				
				//Get Image Group Data
/*				$this_image_group_name = "" ;
				$get_this_image_group = $this->getGroupForImage($image_id) ;
				if($get_this_image_group !== false){
					$this_image_group_name = $get_this_image_group->image_group_name ;
				}
				
				$image_groups = $this->getImageGroups() ;
				
				$default_image_groups_array = array(array("-- None Selected --", "")) ;
				
				$image_groups_options_array 		= array() ;
				for($i = 0; $i < count($image_groups); $i++){
					$image_group_id = $image_groups[$i]['image_group_id'] ;
					$image_group_info = $this->getGroupByID($image_group_id) ;
					if($image_group_info !== false){
						$option_array = array($image_group_info->image_group_name, $image_group_info->image_group_id) ;
						array_push($image_groups_options_array, $option_array) ;
					}
				}
				
				
				//Image Group Name
//				$field = $this->admin_forms->getInputText("Image Group Name (".$this_image_group_name.")", "add_new_image_description", "", "long_input", "", "", "", $image_info->image_id) ;
				$field = $this->admin_forms->getDatalistSelect("image_group_list", "Image Group Name (".$this_image_group_name.")", "add_new_image_description", "long_input", "", "Double Click here to see existing Image Group Names","", true, $default_image_groups_array, $image_groups_options_array ) ;
				
				array_push($form_fields_html, $field) ;
				*/
				
				//Image Preview
				$field = $this->previewImage($image_info->image_filename, $this->image_uploads_dir, "150px") ;
				array_push($form_fields_html, $field) ;
				
				//Form submit button
					$submit_field = $this->admin_forms->getSubmitButtonField("Save Changes", "edit_image_submit", "", "submit", true, $cancel_url, "Cancel") ;
					
				//Get Form HTML	
				$form_html = $this->admin_forms->getRegularForm("Edit Image", $form_fields_html, $submit_field, "post", "", "", "inline_label") ;
		}
		return $form_html ;
	}
	
	public function addAnImage_Form($cancel_url = "./"){
		//FIELDS
		$form_fields_html = array() ;
			
			//Image Description
			$field = $this->admin_forms->getTextarea("Image Description (Not Required)", "add_new_image_description", "", "", "Enter Image Description", "") ;
			array_push($form_fields_html, $field) ;
			
			//Upload Image
			$field = $this->admin_forms->getFileUploadField("Select An Image From Your Computer (Maximum Size allowed is 2 MegaBytes)", "add_new_image_file", "", "", "required") ;
			array_push($form_fields_html, $field) ;
			
			//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonField("Upload Image", "add_new_image_submit", "", "submit", true, $cancel_url, "Cancel") ;
				
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Add New Image", $form_fields_html, $submit_field, "post", "", "multipart/form-data", "inline_label block_auto") ;
					
		return $form_html ;
	}
	public function viewAllImages($this_uri = "admin/index/news/view_all_images/", $page_number = "", $no_of_items = "", $sort_by = "", $display_type = "", $extra = ""){	
		$o = "" ;
		
			$user_id = $this->user_sessions->getUserId() ;
			
			$rootdir 						= $this->rootdir;
			$this_uri 						= $this_uri ;
			$default_page_index				= 0 ;
			$default_no_of_items_to_show 	= 30 ;
			$default_sort_by 				= 'default' ;
			$default_items_display_type		= '' ;
			
			$no_of_items_to_show 		= $default_no_of_items_to_show ;
			$limit_start_no				= $default_page_index ;
			$sort_items_by 				= $default_sort_by ;
			$items_display_type			= $default_items_display_type ;
			
			if(isset($page_number) && ($page_number != "") ){
				if(is_numeric($page_number)){
					$limit_start_no = $page_number ;
				}
			}
			
			if(isset($no_of_items) && ($no_of_items != "") ){
				if(is_numeric($no_of_items)){
					$no_of_items_to_show = $no_of_items ;
				}
			}
			
			if(isset($sort_by) && ($sort_by != "") ){
				$sort_items_by = $sort_by ;
			}
			
			if(isset($display_type) && ($display_type != "") ){
				$items_display_type = $display_type ;
			}
			
			$o .= "<div class='block_auto view-items-filter'>" ;
				$o .= "<div class='view-items-header block_left'>" ;
					$o .= "<span>View All Images</span>" ;
				$o .= "</div>" ;
				$o .= "<div class='view-items-display block_left'>" ;
					$o .= "<span>Display:</span>" ;
					$disp_I = ""; $disp_i = ""; $disp_ii = ""; $disp_iii = "";
					switch($items_display_type){
						case 'I' : 		$disp_I 	= "current"; break ;
						case 'i' : 		$disp_i 	= "current"; break ;
						case 'ii' : 	$disp_ii 	= "current"; break ;
						case 'iii' : 	$disp_iii 	= "current"; break ;
					}
					$permission_result = $this->admin_news->checkUserOptionalAccountPermission($user_id, 'view_all_user_images') ;
					if($permission_result === true){
						$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, 'I')."' class='".$disp_I."' >All</a>" ;
					}
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, 'i')."' class='".$disp_i."'>My Images</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, 'ii')."'  class='".$disp_ii."'>Public Images</a>" ;
				$o .= "</div>" ;
				$o .= "<div class='view-items-limit block_left'>" ;
					$o .= "<span>Show:</span>" ;
				
					$all_options = array() ;
					
					$all_options = array(
	//					$this->getLimitOptionsValue(1, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 1,
	//					$this->getLimitOptionsValue(2, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 2,
	//					$this->getLimitOptionsValue(5, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 5,
	//					$this->getLimitOptionsValue(10, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 10,
						$this->getLimitOptionsValue(30, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 30,
						$this->getLimitOptionsValue(50, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 50,
						$this->getLimitOptionsValue(100, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 100
					) ;
					
					$selected_value = $this->getLimitOptionsValue($no_of_items_to_show, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) ;
					$js = "onchange='location = this.value;'" ;
					$o .= form_dropdown('selected_no_of_items_limit', $all_options, $selected_value, $js );
				
				$o .= "</div>" ;
				$o .= "<div class='view-items-sort block_left'>" ;
					$o .= "<span>Sort By:</span>" ;
				
					$all_options = array() ;
					
					$all_options = array(
					$this->getSortByOptionsValue('default', $rootdir, $this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Default',
					$this->getSortByOptionsValue('i', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Title (A - Z)',
					$this->getSortByOptionsValue('ii', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Title (Z - A)',
					$this->getSortByOptionsValue('iv', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Time (New - Old)',
					$this->getSortByOptionsValue('v', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Time (Old - New)',
					) ;
					
					$selected_value = $this->getSortByOptionsValue($sort_items_by, $rootdir, $this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) ;
					$js = "onchange='location = this.value;'" ;
					$o .= form_dropdown('selected_sort_by', $all_options, $selected_value, $js );
				
				$o .= "</div>" ;
			$o .= "</div>" ;

			//Get Articles Table
			$table = "" ;
				
				$order = $this->getImageSortByValue($sort_items_by) ;
				$start_limit = $no_of_items_to_show * $limit_start_no ;
				$limit = "LIMIT ".$start_limit.",".$no_of_items_to_show ;
				
				$extra_where_clause = "(status = 1)" ;
				if($items_display_type != ''){
					$extra_where_clause = "(status = 1) " ;
				}
				
				$images = false ;
				switch($display_type){
					case "I" 	: 		$permission_result = $this->admin_news->checkUserOptionalAccountPermission($user_id, 'view_all_user_images') ;
										if($permission_result === true){
											$images = $this->getAllImagesAdvanced($extra_where_clause, $order, $limit) ; 	break ;
										}else{
											$images = $this->getAllMyUploadedImagesAdvanced($user_id, $extra_where_clause, $order, $limit) ; 	break ;
										}
										break ;
					case "i" 	: 		$images = $this->getAllMyUploadedImagesAdvanced($user_id, $extra_where_clause, $order, $limit) ; 	break ;
					case "ii" 	: 		$images = $this->getAllPublicImagesAdvanced($extra_where_clause, $order, $limit) ; 					break ; 
					default		:		$images = $this->getAllMyUploadedImagesAdvanced($user_id, $extra_where_clause, $order, $limit) ; 	break ;
				}
				
				if($images !== false){
					$table .= "<table class='table table-bordered table-condensed'>" ;
					$table .= "<tr>" ;
						$table .= "<th>No</th>" ;
						$table .= "<th>Title</th>" ;
						$table .= "<th>Description</th>" ;
						$table .= "<th>Size (KB)</th>" ;
						$table .= "<th>Image Type</th>" ;
						$table .= "<th>Privacy</th>" ;
						$table .= "<th>Image</th>" ;
						$table .= "<th>&nbsp;</th>" ;
						$table .= "<th>Options</th>" ;
					$table .= "</tr>" ;
					for($i = 0; $i < count($images) ; $i++){
						$image_id 			= $images[$i]['image_id'] ;
						$image_info 			= $this->getImageInfo($image_id) ;
						if($image_info !== false){
							$image_title 				= $image_info->title ;
							$image_description			= $image_info->description ;
							
							$image_size 				= $image_info->size ;
							$image_img_type 			= $image_info->image_type ;
							
							$image_privacy_name			= "" ;
							$image_privacy_id 			= $image_info->privacy ;
							
							if($image_privacy_id == 1){
								$image_privacy_name = 'Private' ;
							}else if($image_privacy_id == 2){
								$image_privacy_name = 'Public' ;
							}
							
							$image_status 			= $image_info->status ;
							
							$image_src = $this->getImageImgSrc($image_id, $this->rootdir."images/uploads/") ;
							$image_img = "<img src='".$image_src."' width='150' />" ;
							
						}
				//		$article_body 			= getFirstXLetters(html_entity_decode($article_info->full_text) ) ;					
						
						//ROW COLUMNS
						$row = "" ;
						$row = "<tr>" ;
							$row .= "<td class='view-image-no'><div>".($i + 1 + ($limit_start_no * $no_of_items_to_show) )."</div>" ;
							$row .= "<td class='view-image-name'><div>".$image_title."</div>" ;
							$row .= "<td class='view-image-name'><div>".$image_description."</div>" ;
							$row .= "<td class='view-image-size'><div>".$image_size."</div>" ;
							$row .= "<td class='view-image-type'><div>".$image_img_type."</div>" ;
							$row .= "<td class='view-image-privacy'><div>".$image_privacy_name."</div>" ;
							$row .= "<td class='view-image-img'><div>".$image_img."</div>" ;
							$row .= "<td class='view-image-title'><div>&nbsp;</div>" ;
							$row .= "<td class='view-image-title'><div>";
							
								
								$row .= "<a class='img-view-item img-view-image block_left' id='".$image_id."' href='".$rootdir."admin/index/news/edit_image_info/".$image_id."'></a>" ;
		//						$row .= "<a class='img-edit-item img-edit-image block_left' id='".$image_id."' href='".$rootdir."admin/index/news/edit_image_info/".$image_id."'></a>" ;
								$row .= "<a class='img-delete-item img-delete-image block_left' id='".$image_id."' href='".$rootdir."admin/index/news/delete_a_category/".$image_id."'></a>" ;
								
								
								
							$row .= "</div>" ;
						$row .= "</tr>" ;
						
						$table .= $row ;
						
					}// end for
						$jq = "" ;
						$jq .= "<script>" ;
							$jq .= "var rootdir = '".$rootdir."' ;" ;
							$jq .= "$(document).ready(function() {" ;									
								$jq .= "$('.img-delete-image').click(function(e){" ; 
									$jq .= "e.preventDefault();" ;
									$jq .= "var elem = this;" ;
									$jq .= "var elem_parent_row = $(elem).parent().parent().parent();" ;
									$jq .= "var image_id = elem.id;" ;
									$jq .= "if(confirm('Are you sure you want to Delete this image?')){  " ;
										$jq .= "$.ajax({ type: 'POST', url: rootdir + 'index.php/admin_xhr/image_del', data:{img_id:image_id}, dataType:'json'" ;
										$jq .= " }).done(function(response) { " ;
											$jq .= "if(response){" ;
												$jq .= "if(response.img_del_success == true){" ;
													$jq .= "elem_parent_row.fadeOut('slow',function(){ elem_parent_row.remove(); }) ;" ;
												$jq .= " }else if(response.img_del_success == false){" ;
													$jq .= "alert(response.img_del_msg)" ;
												$jq .= " }" ;
											$jq .= " }" ;
										$jq .= " });" ;
									$jq .= "}" ;
								$jq .= "});" ;
							$jq .= "});" ;
						$jq .= "</script>" ;
						
						$table .= $jq ;
					
					$table .= "</table>" ;
				}
			$o .= $table ;
			
			//Get Sub Pages links
				$p = "" ;
				$p .= "<div class='block_auto view-items-subpages' >" ;
					$extra_where_clause = "(status = 1)" ;
					if($items_display_type != ''){
						$extra_where_clause = "(status = 1) " ;
					}
					
					$full_images = false ;
					switch($display_type){
						case "I" 	: 		$permission_result = $this->admin_news->checkUserOptionalAccountPermission($user_id, 'view_all_user_images') ;
											if($permission_result === true){
												$full_images = $this->getAllImagesAdvanced($extra_where_clause) ; 	break ;
											}else{
												$full_images = $this->getAllMyUploadedImagesAdvanced($user_id, $extra_where_clause) ; 	break ;
											}
											break ;
						case "i" 	: 		$full_images = $this->getAllMyUploadedImagesAdvanced($user_id, $extra_where_clause) ; 	break ;
						case "ii" 	: 		$full_images = $this->getAllPublicImagesAdvanced($extra_where_clause, $order, $limit) ; 					break ; 
						default		:		$full_images = $this->getAllMyUploadedImagesAdvanced($user_id, $extra_where_clause) ; 	break ;
					}
					
					if($full_images !== false){
						$full_count = count($full_images) ;
						$pages_count = $this->getPageIndexes($full_count, $no_of_items_to_show) ;
						for($i = 0; $i <= $pages_count; $i++){
							$page_url = $this->getPageViewUrl( $i, $rootdir, $this_uri, $sort_items_by, $no_of_items_to_show, $items_display_type) ;
							$p .= "<span><a href='".$page_url."' " ;
								if($limit_start_no == $i ){
									$p .= "class = 'current' " ;
								}
							$p .= ">".($i + 1)."</a></span>" ; 
						}
					}
				$p .= "</div>" ;
			$o .= $p ;
			
			
		return $o ;
	}
	private function getPageIndexes($full_count, $limit){
		$inc = -1 ;
		$ans = 2 ;
		if($full_count < 1){
			return 0 ;
		}
		if(is_numeric($full_count) && is_numeric($limit)){
			if($limit > $full_count){
				$limit = $full_count ;
			}
			while($ans >= 1){
				$inc += 1 ;
				$ans = $full_count / $limit ;
				$full_count = $full_count - $limit ;
				
				if($full_count < 1){
					break ;
				}
			}
		}
		return $inc ;
	}
	private function getPageViewUrl($page_index, $rootdir, $this_uri, $sort_items_by, $no_of_items_to_show, $items_display_type){
		$limit_start_no = $page_index ;
		$this_value = $rootdir.$this_uri.$limit_start_no."/".$no_of_items_to_show."/".$sort_items_by."/".$items_display_type ;
		return $this_value ;
	}
	private function getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, $items_display_type){
		$limit_start_no = 0 ;
		$this_value = $rootdir.$this_uri.$limit_start_no."/".$no_of_items_to_show."/".$sort_items_by."/".$items_display_type ;
		return $this_value ;
	}
	private function getLimitOptionsValue($limit_value, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type){
		$limit_start_no = 0 ;
		$no_of_items_to_show = $limit_value;
		$this_value = $rootdir.$this_uri.$limit_start_no."/".$no_of_items_to_show."/".$sort_items_by."/".$items_display_type ;
		return $this_value ;
	}
	private function getSortByOptionsValue($sort_by_value, $rootdir, $this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type){
		$limit_start_no = 0 ;
		$sort_items_by = $sort_by_value;
		$this_value = $rootdir.$this_uri.$limit_start_no."/".$no_of_items_to_show."/".$sort_items_by."/".$items_display_type ;
		return $this_value ;
	}
	
	private function getImageDisplayTypeValue($sort_param){
		switch($sort_param){
			case 'default': 	return "" ; 					break ;
			case 'I':		 	return "(category_type_id = 1 OR category_type_id = 2)" ; 					break ;
			case 'i':		 	return "category_type_id = 1" ; 		break ;
			case 'ii':		 	return "category_type_id = 1" ; 	break ;
			case 'iii':		 	return "category_type_id = 1" ; 		break ;
			case 'iv':		 	return "category_type_id = 2" ; 	break ;
			case 'v':		 	return "category_type_id = 2" ; 	break ;
			case 'vi':		 	return "category_type_id = 2" ; 		break ;
			default: 			return "" ; 					break ;
		}
	}
	private function getImageSortByValue($sort_param){
		switch($sort_param){
			case 'default': 	return "ORDER BY time DESC" ; 					break ;
			case 'i':		 	return "ORDER BY title" ; 		break ;
			case 'ii':		 	return "ORDER BY title DESC" ; 	break ;
			case 'iii':		 	return "ORDER BY publish_status" ; 		break ;
			case 'iv':		 	return "ORDER BY time DESC" ; 	break ;
			case 'v':		 	return "ORDER BY time" ; 		break ;
			default: 			return "" ; 					break ;
		}
	}
	
	public function previewImage($image_filename, $path, $width = '300px', $height = 'auto', $more = ""){
		$o = "" ; 
			$o .= "<div class='block'>" ;
				$o .= "<img src='".$path.$image_filename."' style='width:".$width."; height:".$height."' ".$more." />" ;
			$o .= "</div>" ;
		return $o ;
	}
	public function getImageElement($image_filename, $path, $more = ""){
		$o = "" ; 
			$o .= "<div class='block'>" ;
				$o .= "<img src='".$path.$image_filename."' ".$more." />" ;
			$o .= "</div>" ;
		return $o ;
	}
	public function getImageImgElement($image_id, $path, $more = ""){
		$image_info = $this->getImageInfo($image_id) ;
		if($image_info !== false){
			$image_filename = $image_info->image_filename ;
			return "<img src='".$path.$image_filename."' ".$more." />" ;
		}
		return false ;
	}
	public function getImageImgSrc($image_id, $path){
		$image_info = $this->getImageInfo($image_id) ;
		if($image_info !== false){
			$image_filename = $image_info->image_filename ;
			return $path.$image_filename ;
		}
		return false ;
	}
	
	public function getGroupForImage($image_id, $status = 1){
		$query = "SELECT * FROM images_to_group WHERE image_id = '$image_id' AND status = $status" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
						
			$image_group_id = $result_obj->image_group_id ;
						
			return $this->getGroupByID($image_group_id) ;
		}
		return false ;
	}
	
	public function getGroupByID($image_group_id, $status = 1){
		$query = "SELECT * FROM images_group WHERE image_group_id = '$image_group_id' AND status = $status" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			
			$img_grp_obj->image_group_id 		= $result_obj->image_group_id ;
			$img_grp_obj->image_group_name 		= $result_obj->image_group_name ;
			$img_grp_obj->status 				= $result_obj->status ;
			$img_grp_obj->time_added 			= $result_obj->time ;
			
			return $img_grp_obj ;
		}
		return false ;
	}
	
	public function getImageGroups($status = 1){
		$query = "SELECT * FROM images_group WHERE status = $status" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	
	public function getAllImages($status = 1){
		$query = "SELECT * FROM images WHERE status = $status ORDER BY time DESC" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	public function getAllImagesAdvanced($status = "(status = 1)", $order = "ORDER BY time DESC", $limit = ""){
		$query = "SELECT * FROM images WHERE ".$status." ".$order."  ".$limit ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	public function getAllMyUploadedImages($user_id, $status = 1){
		$query = "SELECT * FROM images WHERE user_id = '".$user_id."' AND status = ".$status ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	public function getAllMyUploadedImagesAdvanced($user_id, $status = "(status = 1)", $order = "ORDER BY time DESC", $limit = ""){
		$query = "SELECT * FROM images WHERE user_id = '".$user_id."' AND ".$status." ".$order."  ".$limit ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	public function getAllPublicImages($status = 1){
		$privacy = 2 ;
		$query = "SELECT * FROM images WHERE privacy = '".$privacy."' AND status = $status ORDER BY time DESC" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	public function getAllPublicImagesAdvanced($status = "(status = 1)", $order = "ORDER BY time DESC", $limit = ""){
		$privacy = 2 ;
		$query = "SELECT * FROM images WHERE privacy = '".$privacy."'  AND ".$status." ".$order."  ".$limit ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	

	public function getImageInfo($image_id, $status = 1){
		$query = "SELECT * FROM images WHERE image_id = '$image_id' AND status = $status" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			@$img_obj->user_id 			= $result_obj->user_id ;
			$img_obj->image_id 			= $result_obj->image_id ;
			$img_obj->image_filename 	= $result_obj->image_filename ;
			$img_obj->image_full_path 	= $result_obj->image_full_path ;
			$img_obj->length_x 			= $result_obj->length_x ;
			$img_obj->length_y 			= $result_obj->length_y ;
			$img_obj->size 				= $result_obj->size ;
			$img_obj->image_type 		= $result_obj->image_type ;
			$img_obj->title 			= $result_obj->title ;
			$img_obj->description 		= $result_obj->description ;
			$img_obj->privacy 			= $result_obj->privacy ;
			$img_obj->type 				= $result_obj->type ;
			$img_obj->status 			= $result_obj->status ;
			$img_obj->time_added 		= $result_obj->time ;
			
			return $img_obj ;
		}
		return false ;
	}
	
	public function addANewImageToDB($user_id, $image_id, $image_filename, $image_full_path, $length_x, $length_y, $size, $image_type, $image_title, $image_description, $privacy = 1, $type = 1, $status = 1){
		if($image_id != ""){
			//user_id, image_id, image_filename, image_full_path, length_x, length_y, size, image_type, image_description, type, status
			$query = "INSERT INTO images VALUES('$user_id', '$image_id', '$image_filename', '$image_full_path', '$length_x', '$length_y', '$size', '$image_type', '$image_title', '$image_description', '$privacy', '$type', '$status', CURRENT_TIMESTAMP) " ;
			$query = $this->db->query($query) ;
			return true ;
		}
		return false ;
	}
	
	public function updateImageInfo($image_id, $title, $description, $status = 1){
		if($image_id != ""){
			$query = "UPDATE images SET title = '$title', description = '$description' WHERE image_id = '$image_id' AND status = $status " ;
			$query = $this->db->query($query) ;
			return true ;
		}
		return false ;
	}
	public function updateImageGroupInfo($image_id, $image_group_id, $status = 1){
		if(($image_id != "") && ($image_group_id != "")){
			if($this->checkImageGroupInfo($image_id) === false){
				//image_id, image_group_id, status, time
				$query = "INSERT INTO images_to_group VALUES('$image_id', '$image_group_id', '$status', CURRENT_TIMESTAMP) " ;
			}else{
				$query = "UPDATE images_to_group SET image_group_id = '$image_group_id' WHERE image_id = '$image_id' AND status = $status " ;
			}
			$query = $this->db->query($query) ;
			return true ;
		}
		return false ;
	}
	
	public function checkImageGroupInfo($image_id){
		if($this->getGroupForImage($image_id) === false){
			return false ;
		}else{ return true ; }
	}
	
	public function updateDeleteImage($image_id){
		
		if($this->user_sessions->userLoggedIn() !== false){
		
		$user_id = $this->user_sessions->getUserId() ;
		
			$image_info = $this->getImageInfo($image_id) ;
			if($image_info !== false){
				$image_user_id 	= $image_info->user_id ;
				$image_filename	= $image_info->image_filename ;
				
				if( 
						($user_id == $image_user_id) 
					|| 	($this->admin_news->checkUserOptionalAccountStatus($user_id, '1100') === true) 
					|| 	($this->user_sessions->getUserPrivilege($user_id) == '10')
				){
					//The User Has Permission to Delete this Image
					
						$deleted_status = 9 ;
						$query = "UPDATE images SET status = '$deleted_status' WHERE image_id = '$image_id' " ;
						$query = $this->db->query($query) ;
						if($query){
							//Move Image File to waste_bin directory
							rename(APPPATH."../images/uploads/".$image_filename, APPPATH."../images/waste_bin/".$image_filename) ;
							return true ;
						}
				}
			}
		}
		return false ;
	}
	
	public function getActionRequestError(){
		$o = "" ;
		$o .= "<div class='spacer-very-large'></div>" ;
		$o .= "<div id='form-error' class='pad_all'>The Action you requested for could not be carried out. ";
			$o .= "<a href=".$this->rootdir."admin/index/>Go Back</a>" ;
		$o .= "</div>" ;
		$o .= "<div class='spacer-very-large'></div>" ;
		return $o ;
	}
	
}
?>