<?php

class Wms_pages_gui extends CI_Model{
	//Constructor
	function __construct(){
	// Call the Model constructor
        parent::__construct();
		
		$this->rootdir = $this->config->item('base_url') ;
	}
	
	private $page_id ;
	
	
	
	
	public function add_new_page(){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Continue
		if($this->validate_n_process_add_new_page_form() === true){
			//Show Success Message
			$msg = "The New Page was Added successfully!" ;
			$o .= $this->admin_news->prepareSuccessMessage($msg, $this->rootdir."admin/index/".$this->admin_news->menu_selection."/view_all_pages") ;
			
			//Show Form Again if requested
			if($this->input->post('add_new_page_submit') !== false){
				//Show Form
				$o .= $this->showForm_add_new_page() ;
			}
		}else{
			//Show Form
			if($this->admin_forms->err != "" ){
				$msg = $this->admin_forms->err ;
				$o .= $this->admin_news->prepareErrorMessage($msg, $this->rootdir."admin/index/".$this->admin_news->menu_selection."/view_all_pages") ;
			}
			$o .= $this->showForm_add_new_page() ;
		}
		return $o ;
	}
	private function validate_n_process_add_new_page_form(){
		if( ($this->input->post('add_new_page_submit') !== false) || ($this->input->post('add_new_page_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('add_new_page_title', 'Page Name', 'required');
			$this->form_validation->set_rules('add_new_page_intro', 'Page Introductory Text', '');
			$this->form_validation->set_rules('add_new_page_body', 'Page Body', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$new_page_title 			= protect($this->input->post('add_new_page_title')) ;
				$new_page_intro 			= protect($this->input->post('add_new_page_intro')) ;
				$new_page_body 				= protect($this->input->post('add_new_page_body')) ;
				
				$new_page_category_id		= "" ;
				$new_cover_image_id 		= "" ;
				
				$user_id = $this->user_sessions->getUserId() ;
				
				$new_page_type_id = 1 ;
				
				// Add Page
				$res = $this->wms_pages->addPage($user_id, $new_page_title, $new_page_type_id, $new_page_intro, $new_page_body, $new_page_category_id, $new_cover_image_id) ;
				if($res[0] === true){
					return true;
				}else{
					//Store Error
					$this->admin_forms->err .= $res[2] ;
					return false;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_add_new_page(){			
		$user_id = $this->user_sessions->getuserId() ;
		
		
		//FIELDS
		$form_fields_html = array() ;
			//Page Title
			$field = $this->admin_forms->getInputCustom("text", "Page Title:", "add_new_page_title", "long_input", "", "Enter New Page Title", "required", "", "", false) ;
			array_push($form_fields_html, $field) ;
			
			//Page Introductory Text
			$maxlen = 150 ;
			$field = "<label class='control-label long_input' for='add_new_page_intro'>Page Introductory Text( maximum length is ".$maxlen." characters):</label><br/><br/>" ;
			$field .= "<textarea class='form-control txt-big' maxlength='".$maxlen."' name='add_new_page_intro' id='' placeholder='Enter the Page Introductory Text' required></textarea>" ;	
			array_push($form_fields_html, $field) ;
			
			//Page Body
			$field = "<label class='control-label' for='add_new_page_body'>Page Body:</label><br/><br/>" ;
			$field .= "<textarea class='form-control txt-big' name='add_new_page_body' id='add_new_page_body' placeholder='Enter the Page Body'></textarea>" ;	
			$field .= "<script src='".$this->rootdir."js/ckeditor/ckeditor.js'></script>
								
						<!-- place in body of your html document -->
						<script>							
							CKEDITOR.replace( 'add_new_page_body', {
							
							'extraPlugins': 'imagebrowser',
							'imageBrowser_listUrl': '".$this->rootdir."image_json',
							
							// NOTE: Remember to leave 'toolbar' property with the default value (null).
							});		
						</script>
			" ;
			array_push($form_fields_html, $field) ;
			

			
			
			
			//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "add_new_page_submit", "add_new_page_submit_and_close", "", "", "submit", "submit", true, $this->rootdir."admin/index/news", "Cancel") ;
			
		//Get Form HTML	
		$form_html = $this->admin_forms->getRegularForm("Add A New Page", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		
		return $form_html ;
	}
	
	
	public function edit_page_info($page_id){
		$o = "" ;
		$this->admin_forms->err = "" ;
				
		//Continue
		if($this->validate_n_process_edit_page_info_form() === true){
			//Show Success Message
			$msg = "The Page has been successfully Edited!" ;
			$o .= $this->admin_news->prepareSuccessMessage($msg, $this->rootdir."admin/index/".$this->admin_news->menu_selection."/view_all_pages") ;
			
			//Show Form Again if requested
			if($this->input->post('edit_page_info_submit') !== false){
				//Show Form
				$o .= $this->showForm_edit_page_info($page_id) ;
			}
		}else{
			//Show Form
			if($this->admin_forms->err != "" ){
				$msg = $this->admin_forms->err ;
				$o .= $this->admin_news->prepareErrorMessage($msg, $this->rootdir."admin/index/".$this->admin_news->menu_selection."/view_all_pages") ;
			}
			$o .= $this->showForm_edit_page_info($page_id) ;
		}
		
		return $o ;
	}
	private function validate_n_process_edit_page_info_form(){
		if( ($this->input->post('edit_page_info_submit') !== false) || ($this->input->post('edit_page_info_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('edit_page_info_page_id', 'Page ID', 'required');
			$this->form_validation->set_rules('edit_page_info_title', 'Page Name', 'required');
			$this->form_validation->set_rules('edit_page_info_intro', 'Page Introductory Text', 'required');
			$this->form_validation->set_rules('edit_page_info_body', 'Page Body', 'required');
			$this->form_validation->set_rules('edit_page_info_publish_status', 'Page Publish Status', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$the_page_id 				= protect($this->input->post('edit_page_info_page_id')) ;
				$the_page_title 			= protect($this->input->post('edit_page_info_title')) ;
				$the_page_intro 			= protect($this->input->post('edit_page_info_intro')) ;
				$the_page_body 				= protect($this->input->post('edit_page_info_body')) ;
				$the_page_publish_status	= protect($this->input->post('edit_page_info_publish_status')) ;
				
				$the_cover_image_string		= "" ;
				
				$the_cover_image_id 		= "" ;
				
				$user_id = $this->user_sessions->getUserId() ;
				
				$the_page_type_id = 1 ;
				
				// Edit Page
				if($this->wms_pages->checkIfPageTitleExistsExclude($the_page_title, $the_page_id) === false){
					$res = $this->wms_pages->editPageInfo($the_page_id, $the_page_type_id, $the_page_title, $the_page_intro, $the_page_body, $the_cover_image_id, $the_page_publish_status) ;
					if($res === true){
						return true;
					}else{
						//Store Error
						$this->admin_forms->err .= "An error Occured While Editing The Page '$the_page_title'" ;
					}
				}else{
					//Check Error
					$this->admin_forms->err .= "This page Name '$the_page_title' has been used for another Page!" ;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_edit_page_info($page_id){
		$form_html = "" ;			
		$user_id = $this->user_sessions->getuserId() ;
		
		$page_info = $this->wms_pages->getPageInfo($page_id) ;
		if($page_info !== false){
			$page_title = $page_info->title ;

			$page_intro = $page_info->intro_text ;
			$page_body = $page_info->full_text ;
			
			$this_page_publish_status_name = "" ;
			$this_page_publish_status 	= $page_info->publish_status ;
			$checked_published = "" ;
			$checked_suspended = "" ;
			$checked_pending = "" ;
			
			if($this_page_publish_status == 1){
				$this_page_publish_status_name = 'Published' ;
				$checked_published = "checked" ;
			}else if($this_page_publish_status == 0){
				$this_page_publish_status_name = 'Suspended' ;
				$checked_suspended = "checked" ;
			}else if($this_page_publish_status == 2){
				$this_page_publish_status_name = 'Pending' ;
				$checked_pending = "checked" ;
			}	
			
			
			//FIELDS
			$form_fields_html = array() ;
			
				//Page ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_page_info_page_id", "", $page_id) ;
				array_push($form_fields_html, $field) ;
				
				//Page Title
				$field = $this->admin_forms->getInputCustom("text", "Page Title:", "edit_page_info_title", "long_input", "", "Edit Page Title", "required", "", $page_title, false) ;
				array_push($form_fields_html, $field) ;
				
				//Page Introductory Text
				$maxlen = 150 ;
				$field = "" ;
				$field .= "<label class='control-label long_input' for='edit_page_info_intro'>Page Introductory Text (maximum length is ".$maxlen." characters):</label><br/><br/>" ;
				$field .= "<textarea class='form-control txt-big' name='edit_page_info_intro' id='' maxlength='".$maxlen."' placeholder='Enter the Page Introductory Text' required>".$page_intro."</textarea>" ;	
				array_push($form_fields_html, $field) ;
				
				//Page Body
				$field = "" ;
				$field .= "<label class='control-label' for='edit_page_info_body'>Page Body:</label><br/><br/>" ;
				$field .= "<textarea class='form-control txt-big' name='edit_page_info_body' id='edit_page_info_body' placeholder='Enter the Page Body' required>".$page_body."</textarea>" ;	
				$field .= "<script src='".$this->rootdir."js/ckeditor/ckeditor.js'></script>
									
						<!-- place in body of your html document -->
						<script>							
							CKEDITOR.replace( 'edit_page_info_body', {
							
							'extraPlugins': 'imagebrowser',
							'imageBrowser_listUrl': '".$this->rootdir."image_json',
							
							// NOTE: Remember to leave 'toolbar' property with the default value (null).
							});		
						</script>
				" ;
				array_push($form_fields_html, $field) ;
				
				//Page Publish Status
				$field = "" ;
				$field .= "<br/><br/>" ;
				$field .= $this->admin_forms->getInputCustom("text", "Page Publish Status:", "", "long_input", "", "Page Publish Status", "disabled", "", $this_page_publish_status_name, false) ;
				array_push($form_fields_html, $field) ;
				
				$field = "" ;
				$field .= "<div class='block_auto'>" ;
					$field .= "<input type='radio' name='edit_page_info_publish_status' id='pub_publish_status' class='block_left' value='1' ".$checked_published." />" ;
					$field .= "<label for='pub_publish_status' >Published</label>" ;
				$field .= "</div>" ;
				
				$field .= "<div class='block_auto'>" ;
					$field .= "<input type='radio' name='edit_page_info_publish_status' id='pub_suspend_status' class='block_left' value='0' ".$checked_suspended." />" ;
					$field .= "<label for='pub_suspend_status' >Suspended</label>" ;
				$field .= "</div>" ;
				
				$field .= "<div class='block_auto'>" ;
					$field .= "<input type='radio' name='edit_page_info_publish_status' id='pub_pending_status' class='block_left' value='2' ".$checked_pending." />" ;
					$field .= "<label for='pub_pending_status' >Pending</label>" ;
				$field .= "</div>" ;
				
				array_push($form_fields_html, $field) ;
				
				//Form submit button
					$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "edit_page_info_submit", "edit_page_info_submit_and_close", "", "", "submit", "submit", true, $this->rootdir."admin/index/news", "Cancel") ;
				
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Edit A Page", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		}
		
		return $form_html ;
	}
	
	
}

?>