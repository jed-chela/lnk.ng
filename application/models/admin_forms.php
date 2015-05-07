<?php

class Admin_forms extends CI_Model{
	// Constructor
	function __construct(){
	// Call the Model constructor
        parent::__construct();
		$this->rootdir = $this->config->item('base_url') ;
	}
	
	public $err ;
	
	/* HTML FORM FIELD GENERATION  */
	
	
	public function getInputText($label, $field_name = "", $field_class = "", $field_id = "", $placehoder = "", $required = "", $disabled = "", $default_value = ""){
		$o = "" ;
		$value = set_value($field_name) ;
		if($default_value != ""){
			$value = $default_value ;
		}
		
		$o .= "<label class='control-label' for='".$field_name."'>".$label."</label>" ;
		$o .= "<input type='text' class='form-control ".$field_class."' name='".$field_name."' id='".$field_id."' value='".$value."' placeholder='".$placehoder."' ".$required." ".$disabled."  />" ;
		return $o ;
	}
	
	public function getInputCheckbox($label, $field_name = "", $field_class = "", $field_id = "", $required = "", $disabled = "", $default_value = ""){
		$o = "" ;
		$value = set_value($field_name) ;
		if($default_value != ""){
			$value = $default_value ;
		}
		
		$o .= "<label class='control-label' for='".$field_name."'>".$label."</label>" ;
		$o .= "<input type='checkbox' class='form-control ".$field_class."' name='".$field_name."' id='".$field_id."' value='".$value."' ".$required." ".$disabled."  />" ;
		return $o ;
	}
	
	public function getInputDate($type, $label, $field_name = "", $field_class = "", $field_id = "", $placehoder = "", $required = "", $disabled = "", $default_value = ""){
		$o = "" ;
		$value = set_value($field_name) ;
		if($default_value != ""){
			$value = $default_value ;
		}
		
		$o .= "<label class='control-label' for='".$field_name."'>".$label."</label>" ;
		$o .= "<input type='".$type."' class='form-control ".$field_class."' name='".$field_name."' id='".$field_id."' value='".$value."' placeholder='".$placehoder."' ".$required." ".$disabled."  />" ;
		return $o ;
	}
	
	public function getInputCustom($type, $label, $field_name = "", $field_class = "", $field_id = "", $placehoder = "", $required = "", $disabled = "", $default_value = "", $use_set_value = true){
		$o = "" ;
		$value = set_value($field_name) ;
		if($use_set_value === false){
			$value = "" ;
		}
		if($default_value != ""){
			$value = $default_value ;
		}
		
		$o .= "<label class='control-label' for='".$field_name."'>".$label."</label>" ;
		$o .= "<input type='".$type."' class='form-control ".$field_class."' name='".$field_name."' id='".$field_id."' value='".$value."' placeholder='".$placehoder."' ".$required." ".$disabled."  />" ;
		return $o ;
	}
	
	public function getInputHidden($field_name = "", $field_id = "", $field_value = ""){
		$o = "" ;
		$o .= "<input type='hidden' name='".$field_name."' id='".$field_id."' value='".$field_value."' />" ;
		return $o ;
	}
	
	public function getTextarea($label, $field_name = "", $field_class = "", $field_id = "", $placehoder = "", $required = "", $disabled = "", $default_value = ""){
		$o = "" ;
		$value = set_value($field_name) ;
		if($default_value != ""){
			$value = $default_value ;
		}
		
		$o .= "<label class='control-label' for='".$field_name."'>".$label."</label>" ;
		$o .= "<textarea class='form-control txt-big ".$field_class."' name='".$field_name."' id='".$field_id."' placeholder='".$placehoder."' ".$required." ".$disabled." >" ;
			$o .= $value ;
		$o .= "</textarea>" ;
		return $o ;
	}
	
	public function getFileUploadField($label, $field_name = "", $field_class = "", $field_id = "", $required = ""){
		$o = "" ;
		$o .= "<label class='control-label' for='".$field_name."'>".$label."</label>" ;
		$o .= "<input type='file' class='form-control ".$field_class."' name='".$field_name."' id='".$field_id."' ".$required." />" ;
		return $o ;
	}
	
	public function getRegularSelect($label, $field_name = "", $field_class = "", $field_id = "", $required = "", $default_options = false, $default_options_array = array(), $values_array = array(), $selected_option = "" ){
		$o = "" ;
			$all_options = array() ;
			$o .= "<label class='control-label' for='".$field_name."'>".$label."</label>" ;
			
			$all_options = array_merge($default_options_array, $values_array) ;
			
			$js = "" ;
			$js .= "class = '".$field_class."'" ;
			
//			$o .= print_r($selected_option, true) ;
			$o .= form_dropdown($field_name, $all_options, $selected_option, $js );

		return $o ;
	}
	
	public function getDatalistSelect($list_id, $label, $field_name = "", $field_class = "", $field_id = "", $placehoder = "", $required = "", $default_options = false, $default_options_array = array(), $values_array = array() ){
		$o = "" ;
			$o .= "<label class='control-label' for='".$field_name."'>".$label."</label>" ;
			$o .= "<input type='text' list='".$list_id."' class='form-control ".$field_class."' name='".$field_name."' value='".set_value($field_name)."' placeholder='".$placehoder."' ".$required."  />" ;
			$o .= "<datalist id='".$list_id."' >" ;
				if($default_options === true){
					for($i = 0; $i < count($default_options_array); $i++){
						$o .= "<option value='".$default_options_array[$i]."' >" ;
					}
				}
				
				for($i = 0; $i < count($values_array); $i++){					
					$o .= "<option value='".$values_array[$i]."' >" ;
				}
			$o .= "</datalist>" ;
		return $o ;
	}
	
	public function getSubmitButtonField($button_name, $field_name = "", $field_class = "", $field_type = "submit", $add_cancel = true, $cancel_url = "", $cancel_button_name = "Cancel"){
		$o = "" ;
			$o .= "<button type='".$field_type."' name='".$field_name."' class='btn btn-success block_left ".$field_class."'>".$button_name."</button>" ;
			$o .= "<span class='block_left'>&nbsp;</span>" ;
			if($add_cancel === true){
			$o .= "<a href='".$cancel_url."' class='btn btn-warning block_left'>".$cancel_button_name."</a>" ;
			}
		return $o ;
	}
	
	public function getDeleteButtonField($button_name, $field_name = "", $field_class = "", $field_type = "submit", $add_cancel = true, $cancel_url = "", $cancel_button_name = "Cancel"){
		$o = "" ;
			$o .= "<button type='".$field_type."' name='".$field_name."' class='btn btn-danger block_left ".$field_class."'>".$button_name."</button>" ;
			$o .= "<span class='block_left'>&nbsp;</span>" ;
			if($add_cancel === true){
			$o .= "<a href='".$cancel_url."' class='btn btn-warning block_left'>".$cancel_button_name."</a>" ;
			}
		return $o ;
	}
	
	public function getSubmitButtonFieldType2($button1_name, $button2_name, $field1_name = "", $field2_name = "", $field1_class = "", $field2_class = "", $field1_type = "submit", $field2_type = "submit", $add_cancel = true, $cancel_url = "", $cancel_button_name = "Cancel"){
		$o = "" ;
			$o .= "<button type='".$field1_type."' name='".$field1_name."' class='btn btn-success block_left ".$field1_class."'>".$button1_name."</button>" ;
			$o .= "<span class='block_left'>&nbsp;</span>" ;
			$o .= "<button type='".$field2_type."' name='".$field2_name."' class='btn btn-success block_left ".$field2_class."'>".$button2_name."</button>" ;
			$o .= "<span class='block_left'>&nbsp;</span>" ;
			if($add_cancel === true){
			$o .= "<a href='".$cancel_url."' class='btn btn-warning block_left'>".$cancel_button_name."</a>" ;
			}
		return $o ;
	}
	
	public function getRegularForm($form_header_name, $fields = array(), $submit_field, $method = "post", $action = "", $enctype = "", $form_box_class = "", $form_group_class = "" ){
		$o = "" ;
			$o .= "<div class='form-box marg_all ".$form_box_class."'>" ;
			$o .= "<form role='form' class='form-vertical' method='".$method."' action='".$action."' enctype='".$enctype."'>" ;
					$o .= "<h4 class='no_bold pad_border_bottom'>".$form_header_name."</h4>" ;
					
					$o .= "<div id='form-error'>" ;
						if(isset($this->err)){
							$o .= $this->err ;
						}
						$o .= validation_errors() ;
					$o .= "</div>" ;
					
					// Fields
					for($i = 0; $i < count($fields); $i++){
						$o .= "<div class='form-group ".$form_group_class."'>" ;
							$o .= $fields[$i] ;
						$o .= "</div>" ;
					}
										
					//Vertical Spacer
					$o .= "<div>&nbsp;</div>" ;
					
					//Form submit button
					$o .= "<div class='block'>" ;
						$o .= $submit_field ;
					$o .= "</div>" ;
			$o .= "</form>" ;
			$o .= "</div>" ;
		return $o ;
	}
	
	public function showFormMessage($msg){
		$o = "" ;
		$o .= "<div id='form-message'>" ;
				$o .= $msg ;
		$o .= "</div>" ;
		return $o ;
	}
	
	public function showFormDefaultMessage(){
		$o = "" ;
		$o .= "<div id='form-error'>" ;
			if(isset($this->err)){
				$o .= $this->err ;
			}
		$o .= "</div>" ;
		return $o ;
	}
	
	
	
	
}
?>