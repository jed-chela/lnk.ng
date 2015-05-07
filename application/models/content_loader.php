<?php

class Content_loader extends CI_Model{
	// Constructor
	function __construct(){
	// Call the Model constructor
        parent::__construct();
		$this->rootdir = $this->config->item('base_url') ;
		
		$this->default_template_position_parent_name_id = 'default' ;
		$this->template_positions_tree = array() ;
	}
	
	private $page_template_id ;
	private $default_template_position_parent_name_id ;
	private $user_id ;
	private $template_positions_tree ;
	private $pos_data ;
	
	public function set_page_template($template_name){
		$this->page_template_id = $this->getTemplateId($template_name) ;
	}
	
	public function get_this_page_template(){
		if(isset($this->page_template_id)){
			return $this->page_template_id ;
		}
		return false ;
	}
	
	public function getDefaultTemplateInfo(){
		// Get Template Info From database
		$query = $this->db->query("SELECT * FROM wms_templates WHERE template_id = '".$this->page_template_id."'");
		
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			
			//Return the template Info
			$template_obj->id 			= $result_obj->template_id ;
			$template_obj->name			= $result_obj->template_name ;
			$template_obj->css_dir 		= $result_obj->css_dir ;
			$template_obj->xml_dir 		= $result_obj->xml_dir ;
			$template_obj->type 		= $result_obj->type ;
			$template_obj->status 		= $result_obj->status ;
			$template_obj->time_added 	= $result_obj->time ;
			return $template_obj ;
		}
		return false;
	}
	public function loadTemplateController($template_obj){
		$file_dir = "templates/".$template_obj->name."/controllers/template_home.php" ;
		$html = $this->load->file($file_dir, true) ;
		return $html ;
	}
	public function loadTemplateViewFile($template_obj, $view){
		$file_dir = "templates/".$template_obj->name."/views/".$view.".php" ;
		$html = $this->load->file($file_dir, true) ;
		return $html ;
	}
	public function getTemplateId($template_name){
		// Get Template ID From database
		$query = $this->db->query("SELECT template_id FROM wms_templates WHERE template_name = '$template_name'");

		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			
			//Return the template ID
			return $result_obj->template_id ;
		}
		return false;
	}
	public function getThisTemplateCSS($template_id){
		$o = "" ;
		$style = $this->getTemplateCSSStyleFromDB($template_id) ;
		if($style === false){
			$style = "/* No style found for this template */" ;
		}
		$o .= "<style>" ;
			$o .= getStringTextForm($style) ;
		$o .= "</style>" ;
		return $o ;
	}
	private function getTemplateCSSStyleFromDB($template_id){
		// Get Template ID From database
		$query = $this->db->query("SELECT style FROM wms_template_style WHERE template_id = '$template_id'");

		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			
			//Return the template ID
			return $result_obj->style ;
		}
		return false;
	}
	private function getTemplateModulePositionInfoByNameId($template_id, $position_name_id, $status = 1){
		// Get Position Content Info From database
		$query = $this->db->query("SELECT * FROM wms_template_module_positions WHERE name_id = '".$position_name_id."' AND template_id = '".$template_id."' AND status = ".$status."");
		
		if ($query->num_rows() > 0)
		{
			$result = array() ;
			$result_array = $query->result_array() ;
			for($i = 0; $i < count($result_array) ; $i++){
				
				$num_array = array_assoc_to_numeric($result_array[$i]) ;
				array_push($result, $num_array ) ;	
			}
		
			list($position_id, $template_id, $name_id, $group_name_id, $order_in_group, $type, $status, $time) = $result[0] ;
			//Return the Position Content Info
			@$pos_obj->id 			= $position_id ;
			$pos_obj->temp_id 		= $template_id ;
			$pos_obj->name_id 		= $name_id ;
			$pos_obj->grp_name_id 	= $group_name_id ;
			$pos_obj->grp_order 	= $order_in_group ;
			$pos_obj->type 			= $type ;
			$pos_obj->status 		= $status ;
			$pos_obj->time_added 	= $time ;
			return $pos_obj ;
		}
		return false;
	}
	
	public function getMajorTemplatePositions($template_id, $status = "(status = 1)", $order = "ORDER BY order_in_group" ){
		// Get Position Content Info From database
		$default_parent_name_id = $this->default_template_position_parent_name_id ;
		$query = "SELECT * FROM wms_template_module_positions WHERE template_id = '".$template_id."' AND group_name_id = '".$default_parent_name_id."' AND ".$status." ".$order." " ;
		$query = $this->db->query($query);
		
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->result() ;
			return $result_obj ;
		}
		return false ;
	}
	
	public function getTemplatePositionData($return_error = true, $child_class = ""){
		$page_data = "" ;
		
		$this_page_template_id = $this->get_this_page_template() ;
		
		if($this_page_template_id !== false){
			$all_major_positions = $this->getMajorTemplatePositions($this_page_template_id) ;
			if($all_major_positions !== false){
				for($i = 0; $i < count($all_major_positions); $i++){
					$position_name_id = $all_major_positions[$i]->name_id ;
					
		//			$page_data .= "<div class='block'>" ;
					
						$page_data .= $this->getAnyPositionData($position_name_id, $return_error, $child_class) ;
					
		//			$page_data .= "</div>" ;
				}
			}else{
				$page_data .= "No Level 1 Positions found for this page template ($this_page_template_id)!" ;
			}
		}else{
			$page_data .= "This page template is unknown!" ;
		}
		return $page_data ;
	}
	
	public function getAnyPositionData($position_name_id, $return_error = true, $child_class = ""){
		$page_data = "" ;
		
		$this->template_positions_tree = array() ;
		
		$template_id = $this->page_template_id ;
		
		$page_data .= $this->showTreeContent($template_id, $position_name_id, $return_error, $child_class) ;
		
		return $page_data ;
	}
	
	public function showTreeContent($template_id, $name_id, $return_error, $child_class){
		$o = "" ;
		$module_group_data = "" ;
		
		$position_info 		= $this->getTemplateModulePositionInfoByNameId($template_id, $name_id) ;
		if($position_info !== false){
			$content_data 		= $this->getPositionContentInfo($position_info->id) ;
			
			$content_children 	= $this->getGroupChildPositionNames($name_id) ;
			
//			$module_group_data .= "<div id='"."wms-pos-".$name_id."' class='$child_class' >" ;
			$module_group_data .= "<!-- Open: ".$name_id."' -->" ;
			if($content_data !== false){
//				$module_group_data .= "<div id='"."wms-pos-".$name_id."' class='$child_class' >" ;
				$module_group_data .= $this->getData($position_info->id, $return_error) ;
//				$module_group_data .= "</div>" ;
			}
			
			
			if($content_children !== false){
				foreach($content_children as $content_child){
					if (in_array( array($content_child[0], NULL) , $this->template_positions_tree) === false ) {					
						$o = $this->showTreeContent($template_id, $content_child[0], $return_error, $child_class) ;
						
						array_push($this->template_positions_tree, array($content_child[0], NULL) ) ;
						
						$module_group_data .= $o ;
					}
				}
			}
			
			$module_group_data .= "<!-- Close: ".$name_id." -->" ;
		}
		return $module_group_data ;
	}
	
	public function getGroupPositions($position_group_name){
		// Get Content Text From database
		$query = $this->db->query("SELECT position_id FROM wms_template_module_positions WHERE group_name_id = '$position_group_name' AND template_id = '".$this->page_template_id."' ORDER by order_in_group");

		if ($query->num_rows() > 0)
		{
			$result = array() ;
			$result_array = $query->result_array() ;
			for($i = 0; $i < count($result_array) ; $i++){
				
				$num_array = array_assoc_to_numeric($result_array[$i]) ;
				array_push($result, $num_array ) ;	
			}
		
			return $result ;
		}
		
		return false ;
	}
	
	public function getGroupChildPositionNames($position_group_name){
		// Get Content Text From database
		$query = $this->db->query("SELECT name_id FROM wms_template_module_positions WHERE group_name_id = '$position_group_name' AND template_id = '".$this->page_template_id."' ORDER by order_in_group");

		if ($query->num_rows() > 0)
		{
			$result = array() ;
			$result_array = $query->result_array() ;
			for($i = 0; $i < count($result_array) ; $i++){
				
				$num_array = array_assoc_to_numeric($result_array[$i]) ;
				array_push($result, $num_array ) ;	
			}
		
			return $result ;
		}
		
		return false ;
	}
	
	public function getPositionContentInfo($position_id, $status = 1){
		// Get Position Content Info From database
		$query = $this->db->query("SELECT * FROM wms_template_module_positioning WHERE position_id = '".$position_id."' AND status = ".$status."");
		
		if ($query->num_rows() > 0)
		{
			$result = array() ;
			$result_array = $query->result_array() ;
			for($i = 0; $i < count($result_array) ; $i++){
				
				$num_array = array_assoc_to_numeric($result_array[$i]) ;
				array_push($result, $num_array ) ;	
			}
		
			list($position_id, $content_type, $content_id, $type, $status, $time) = $result[0] ;
			//Return the Position Content Info
			@$pos_cnt_obj->id = $position_id ;
			$pos_cnt_obj->cnt_type = $content_type ;
			$pos_cnt_obj->cnt_id = $content_id ;
			$pos_cnt_obj->type = $type ;
			$pos_cnt_obj->status = $status ;
			$pos_cnt_obj->time_added = $time ;
			return $pos_cnt_obj ;
		}
		return false;
	}
	public function getData($position_id, $return_error = true){
		// Get Content Data from DB using position
		$pos_data = $this->getPositionContent($position_id) ;
		
		if($pos_data != ""){
			if($pos_data->pos_cnt_type == 1 ){
				// Position Data is a Database Content Text
				$text_obj = $this->getContentText($pos_data->pos_cnt_id) ;
				if($text_obj != ""){
					return $text_obj->cnt_text ;	
				}else{ 
					if($return_error === true){
						return "Data for position '".$position_id."', content_text '".$pos_data->pos_cnt_id."' was not found" ;
					}else{ return "" ; }
				}
				
			}else if($pos_data->pos_cnt_type == 2){
				// Position Data is a widget
				return $this->getWidgetData($pos_data->pos_cnt_id) ;
			}
		}else{
			if($return_error === true){
				return "Data for position '".$position_id."' was not found" ;
			}else{ return "" ; }
		}
	}
	public function getWidgetData($widget_id, $type = 'system'){
		// Get Widget Data
		if($widget_id != ""){
			try {
				//Execute widget php file
				$file_dir = "widgets/".$type."/".$widget_id."/index.php" ;
				$html = $this->load->file($file_dir, true) ;
				return $html ;
			} catch (Exception $e) {
				return 0 ;
			}
		}else{ return 0 ; }
	}
	public function getFile($widget, $type = "html"){
		// Get Position Data
		
		
	}
	public function getScript($widget, $type = "js"){
		// Get Javacript File
		
		
	}
	public function getStylesheet($widget, $type = "css"){
		// Get CSS Stylesheet File
		
		
	}
	public function getPositionContent($position_id, $status = 1){
		$this->pos_data = "" ;
		// Check if position is valid
		
		// Get Position Content From database
		$query = $this->db->query("SELECT * FROM wms_template_module_positioning WHERE position_id = '$position_id' AND status = $status ");

		if ($query->num_rows() > 0)
		{
			$result = $query->result_jed(); 
		
			list($position_id, $pos_cnt_type, $pos_cnt_id, $type, $status, $time) = mysql_fetch_row($result) ;
			// Store necessary position data in an Object
			@$this->pos_data->position_id 		= $position_id ;
			$this->pos_data->pos_cnt_type 		= $pos_cnt_type ;
			$this->pos_data->pos_cnt_id 		= $pos_cnt_id ;
		}
		
		return $this->pos_data ;
	}
	public function getContentText($content_text_id){
		$this->text = "" ;

		// Get Content Text From database
		$query = $this->db->query("SELECT * FROM wms_content_text WHERE content_id = '$content_text_id'");

		if ($query->num_rows() > 0)
		{
			$result = $query->result_jed(); 
		
			list($cnt_id, $cnt_text, $type, $status, $time_added) = mysql_fetch_row($result) ;
			// Store necessary position data in an Object
			$this->text->cnt_id 		= $cnt_id ;
			$this->text->cnt_text 		= $cnt_text ;
			$this->text->type 			= $type ;
			$this->text->status 		= $status ;
			$this->text->time_added 	= $time_added ;
		}
		
		return $this->text ;
	}
	public function getDefaultCSS(){
		$o = "" ;
		$o .= "<link rel='stylesheet' href='".$this->rootdir."css/bootstrap.min.css' type='text/css' />" ;
    	$o .= "<link rel='stylesheet' href='".$this->rootdir."css/bootstrap-responsive.min.css' type='text/css' />" ;
    	$o .= "<link rel='stylesheet' href='".$this->rootdir."css/default.css' type='text/css' />" ;
		return $o ;
	}
	
}
?>