<?php

class Admin_manager extends CI_Model{	
	// Constructor
	function __construct(){
	// Call the Model constructor
        parent::__construct();
		$this->rootdir = $this->config->item('base_url') ;
		
		$this->load->model('admin_store') ;
		
		$this->load->model('admin_news') ;
		$this->load->model('admin_forms') ;
		
		define("template_base_position_name", "default") ;
		define("blankTemplateValue", "blank") ;
		
		$this->template_positions_tree = array() ;
		
	}
	
	private $user_id ;
	private $template_positions_tree ;
	
	public function paramIsValid($param = ""){
		switch($param){
			case 'dashboard' 	: return true ;
			case 'templates' 	: return true ;
			case 'widgets' 		: return true ;
			case 'store' 		: return true ;
			case 'news' 		: return true ;
			default 			: return false ;
		}
	}
	
	/* SHOW Framework Name */
	public function showFrameworkName(){
		return "<div class='block_auto framework_name'>IVY-WMS: IvyCoders Widget Management System</div>" ;
	}
	
	/* SHOW App Instance Name */
	public function showAppIstanceName(){
		return "<div class='block_left app_instance_name'>MY TEENS LIFE</div>" ;
	}
	
	/* LOAD ADMIN CSS */
	public function getAdminCSS(){
		$o = "" ;
		$o .= "<link rel='stylesheet' href='".$this->rootdir."css/bootstrap.min.css' type='text/css' />" ;
		$o .= "<link rel='stylesheet' href='".$this->rootdir."css/bootstrap-responsive.min.css' type='text/css' />" ;
		$o .= "<link rel='stylesheet' href='".$this->rootdir."css/fonts.css' type='text/css' />" ;
		$o .= "<link rel='stylesheet' href='".$this->rootdir."css/default.css' type='text/css' />" ;
		$o .= "<link rel='stylesheet' href='".$this->rootdir."css/admin_style.css' type='text/css' />" ;
		return $o ;
	}
	
	/* LOAD ADMIN JAVASCRIPTS */
	public function getAdminJavascripts(){
		$o = "" ;
		$o .= "<script src='".$this->rootdir."js/jquery-1.7.1.min.js' type='text/javascript' ></script>" ;
		$o .= "<script src='".$this->rootdir."js/jquery.textarea-expander.js' type='text/javascript' ></script>" ;
		return $o ;
	}
	
	/* LOAD ADMIN NAVIGATION MENU	*/
	public function getAdminNavigationMenu($tag){
		$id_dashboard 	= "" ;
		$id_templates 	= "" ;
		$id_widgets 	= "" ;
		$id_store 		= "" ;
		$id_news	 	= "" ;
		switch($tag){
			case 'dashboard' 	: $id_dashboard = "current" ; break ;
			case 'templates' 	: $id_templates = "current" ; break ;
			case 'widgets' 		: $id_widgets 	= "current" ; break ;
			case 'store' 		: $id_store 	= "current" ; break ;
			case 'news' 		: $id_news 		= "current" ; break ;
		}
		$o = "" ;
		$o .= "<a href='".$this->rootdir."admin/index/dashboard' id='".$id_dashboard."'><span>Dashboard</span></a>" ;
		$o .= "<a href='".$this->rootdir."admin/index/templates' id='".$id_templates."'><span>Templates</span></a>" ;
		$o .= "<a href='".$this->rootdir."admin/index/widgets' 	 id='".$id_widgets."'><span>Widgets</span></a>" ;
//		$o .= "<a href='".$this->rootdir."admin/index/store' 	 id='".$id_store."'><span>Store</span></a>" ;
		$o .= "<a href='".$this->rootdir."admin/index/news' 	 id='".$id_news."'><span>News</span></a>" ;
        return $o ;
	}
	
	
	/* TEMPLATES SECTION */
	public function getAllTemplates(){
		// Get All Template Info from the database
		$all_templates_array = array() ;
		$query = $this->db->query("SELECT * FROM wms_templates WHERE status = 1");
		
		if ($query->num_rows() > 0)
		{
			$count = $query->num_rows() ;
			$result_array = $query->result_array() ;
			for($i = 0; $i < $count; $i++){
				
				$num_array = array_assoc_to_numeric($result_array[$i]) ;
				array_push($all_templates_array, $num_array ) ;	
			}
			return $all_templates_array;
		}
		return false ;
	}
	
	public function getTemplateInfo($template_id, $status = 1){
		// Get Template Info From database
		$query = $this->db->query("SELECT * FROM wms_templates WHERE template_id = '".$template_id."' AND status = ".$status."");
		
		if ($query->num_rows() > 0)
		{
			$result = array() ;
			$result_array = $query->result_array() ;
			for($i = 0; $i < count($result_array) ; $i++){
				
				$num_array = array_assoc_to_numeric($result_array[$i]) ;
				array_push($result, $num_array ) ;	
			}
		
			list($template_id, $template_name, $css_dir, $xml_dir, $type, $status, $time) = $result[0] ;
			//Return the template Info
			@$template_obj->id = @$template_id ;
			$template_obj->name = $template_name ;
			$template_obj->css_dir = $css_dir ;
			$template_obj->xml_dir = $xml_dir ;
			$template_obj->type = $type ;
			$template_obj->status = $status ;
			$template_obj->time_added = $time ;
			return $template_obj ;
		}
		return false;
	}
		
	public function getTemplateName($template_id, $status = 1){
		// Get Template Info From database
		$template_info_obj = $this->getTemplateInfo($template_id, $status) ;
		if($template_info_obj !== false){
			return $template_info_obj->name ;
		}
		return false;
	}
		
	public function getDefaultTemplateId(){
		// Get Default Template Info from the database
		$query = $this->db->query("SELECT template_id FROM wms_template_default WHERE status = 1");
		
		if ($query->num_rows() > 0)
		{
			$res = $query->result_array() ;
			return $res[0]['template_id'] ;
		}
		return false ;
	}
	public function verifyDefaultTemplateId($template_id, $status = 1){
		// Verify Template ID
		$query = $this->db->query("SELECT template_id FROM wms_template_default WHERE template_id = '$template_id' AND status = ".$status."" );
		
		if ($query->num_rows() > 0)
		{
			$res = $query->result_array() ;
			return true ;
		}
		return false ;
	}
	public function verifyTemplateId($template_id, $status = 1){
		// Verify Template ID
		$query = $this->db->query("SELECT template_id FROM wms_templates WHERE template_id = '$template_id' AND status = ".$status."" );
		
		if ($query->num_rows() > 0)
		{
			$res = $query->result_array() ;
			return true ;
		}
		return false ;
	}
	public function verifyTemplateName($template_name, $status = "(status = 1)"){
		// Verify Template ID
		$query = $this->db->query("SELECT template_id FROM wms_templates WHERE template_name = '$template_name' AND ".$status."" );
		
		if ($query->num_rows() > 0)
		{
			$res = $query->result_array() ;
			return true ;
		}
		return false ;
	}
	public function verifyTemplateCSSExists($template_id, $status = "status = 1"){
		// Verify Template ID
		$query = $this->db->query("SELECT template_id FROM wms_template_style WHERE template_id = '$template_id' AND ".$status."" );
		
		if ($query->num_rows() > 0)
		{
			$res = $query->result_array() ;
			return true ;
		}
		return false ;
	}
	public function verifyTemplateModulePositionId($position_id, $group_name_id = "", $template_id = "", $status = 1){
		// Verify Template Module Position Name
		if(($group_name_id == "") && ($template_id == "")){
		$query = $this->db->query("SELECT * FROM wms_template_module_positions WHERE position_id = '$position_id' AND status = $status ");
		}else if($group_name_id == ""){
		$query = $this->db->query("SELECT * FROM wms_template_module_positions WHERE position_id = '$position_id' AND template_id = '$template_id' AND status = $status ");
		}else if($template_id == ""){
		$query = $this->db->query("SELECT * FROM wms_template_module_positions WHERE position_id = '$position_id' AND group_name_id = '$group_name_id' AND status = $status ");
		}else{
		$query = $this->db->query("SELECT * FROM wms_template_module_positions WHERE position_id = '$position_id' AND group_name_id = '$group_name_id' AND template_id = '$template_id' AND status = $status ");
		}
		
		if ($query->num_rows() > 0)
		{
			$res = $query->result_array() ;
			return true ;
		}
		return false ;
	}
	public function verifyTemplateModulePositionName($position_name, $group_name_id = "", $template_id = "", $status = 1){
		// Verify Template Module Position Name
		if(($group_name_id == "") && ($template_id == "")){
		$query = $this->db->query("SELECT * FROM wms_template_module_positions WHERE name_id = '$position_name' AND status = $status");
		}else if($group_name_id == ""){
		$query = $this->db->query("SELECT * FROM wms_template_module_positions WHERE name_id = '$position_name' AND template_id = '$template_id' AND status = $status");
		}else if($template_id == ""){
		$query = $this->db->query("SELECT * FROM wms_template_module_positions WHERE name_id = '$position_name' AND group_name_id = '$group_name_id' AND status = $status");
		}else{
		$query = $this->db->query("SELECT * FROM wms_template_module_positions WHERE name_id = '$position_name' AND group_name_id = '$group_name_id' AND template_id = '$template_id' AND status = $status");
		}
		
		if ($query->num_rows() > 0)
		{
			$res = $query->result_array() ;
			return true ;
		}
		return false ;
	}
	
	public function verifyTemplateModulePositionAvailability($position_id){
		// Verify Template Module Position Name
		$position_content_info = $this->getPositionContentInfo($position_id) ;
		if($position_content_info === false){
			return true ;
		}
		return false ;
	}
	
	public function getTemplateModulePositionGroups($template_id){
		$templ_module_pos_group_array = array() ;
		$query = $this->db->query("SELECT group_name_id FROM wms_template_module_positions WHERE template_id = '$template_id' AND status = 1 ORDER by group_name_id");
		
		if ($query->num_rows() > 0)
		{
			$count = $query->num_rows() ;
			$result_array = $query->result_array() ;
			for($i = 0; $i < $count; $i++){
				
				$num_array = array_assoc_to_numeric($result_array[$i]) ;
				array_push($templ_module_pos_group_array, $num_array ) ;	
			}
			$temp = array() ;
			for($i = 0; $i < count($templ_module_pos_group_array) ; $i++){
				if(array_search($templ_module_pos_group_array[$i], $temp) === false){
					array_push($temp, $templ_module_pos_group_array[$i] ) ;
				}
			}
			$templ_module_pos_group_array = $temp ;
		}
		return $templ_module_pos_group_array;
	}
	
	public function getTemplateModulePositions($template_id, $group_name_id = ""){
		$templ_module_pos_array = array() ;
		if($group_name_id == ""){
		$query = $this->db->query("SELECT * FROM wms_template_module_positions WHERE template_id = '$template_id' AND status = 1");
		}else{
		$query = $this->db->query("SELECT * FROM wms_template_module_positions WHERE template_id = '$template_id' AND group_name_id = '$group_name_id' AND status = 1");
		}
		
		if ($query->num_rows() > 0)
		{
			$count = $query->num_rows() ;
			$result_array = $query->result_array() ;
			for($i = 0; $i < $count; $i++){
				
				$num_array = array_assoc_to_numeric($result_array[$i]) ;
				array_push($templ_module_pos_array, $num_array ) ;	
			}			
		}
		return $templ_module_pos_array;
	}
	
	public function getTemplateModulePositionObjectString($template_id){
		$query = $this->db->query("SELECT name_id, group_name_id FROM wms_template_module_positions WHERE template_id = '$template_id' AND status = 1 ORDER by order_in_group");
		
		if ($query->num_rows() > 0)
		{
			$count = $query->num_rows() ;
			$result_arr = $query->result_array() ;
			
			$result_string = trim(print_r($result_arr, true)) ;
			
		//	$children =  $this->recursiveGetObjectChild("[group_name_id] => ", "top", "[name_id] => ", $result_string ) ;
			
			return $result_string ;
		}
		return false ;
	}
	
	public function getTemplateBasePositionChildPositions($template_id, $object_string){
		
		$query = $this->db->query("SELECT name_id, group_name_id FROM wms_template_module_positions WHERE template_id = '$template_id' AND group_name_id = '".template_base_position_name."' AND status = 1 ORDER by order_in_group");
		
		if ($query->num_rows() > 0)
		{
			$count = $query->num_rows() ;
			$result_arr = $query->result_array() ;
			$result_name_ids = array() ;
			
			for($i = 0; $i < count($result_arr) ; $i++){
				$result_n = array_assoc_to_numeric($result_arr[$i]) ;
				array_push($result_name_ids, $result_n[0]) ;			
			}
			
		//	print_r($object_string) ;
			echo "<hr/>" ;
			
	//		$children =  $this->recursiveGetObjectChild("[group_name_id] => ", "top_a", "[name_id] => ", $object_string ) ;
	//		print_r($children) ;
	//		echo "<hr/>" ;
			
			$this->getCrazyNameIdTree($result_name_ids, $object_string, $res = "")  ;
		//	echo "<pre>".print_r($this->template_positions_tree,true)."</pre>" ;
		}
		return false ;
	}
	
	private function getCrazyNameIdTree($result_name_ids, $object_string, $res = array() , $inc = 0){
		$res = array() ;
		if(!empty($result_name_ids) ){
			for($i = 0; $i < count($result_name_ids) ; $i++){
				
				$the_name_id = trim($result_name_ids[$i]) ;
				
				$children =  $this->recursiveGetObjectChild("[group_name_id] =>", trim($the_name_id)." ", "[name_id] =>", $object_string ) ;
				
				
				//Print Output To Screen ($name_id, $node_level) Or store in a class property of '2D array' type and Array Unique it
				if(!empty($result_name_ids[$i])){
					if (in_array( array($result_name_ids[$i],$inc) , $this->template_positions_tree) === false ) {
						array_push($this->template_positions_tree, array($result_name_ids[$i], $inc) ) ;
						
					//	echo $result_name_ids[$i]." --".$inc."-- " ;
					//	print_r( $children) ;
					//	echo "<hr/>" ;
					}else{
						
					}
				}
				@$this->template_positions_tree = @array_intersect ( $this->template_positions_tree, $this->template_positions_tree ) ;
				
				//Return Result Array
				if(!empty($children) ){
					$inc = $inc + 1 ;
					array_push( $res , $the_name_id ) ;
					$this->getCrazyNameIdTree($children, $object_string, $res, $inc) ;
					$inc = $inc - 1 ;
				}else{
					array_shift($result_name_ids) ;
					$i++ ;
					$this->getCrazyNameIdTree($result_name_ids, $object_string, $res, $inc) ;
					return ;
				}
				
			}
		}else{ 
			return ; 
		}
	}
	
	private function recursiveGetObjectChild($key_holder, $key, $eye_glass, $large_string, $needle_array = array()){
		// "[group_name_id] => top"
		$key_length = strlen($key) ;
		
		$eye_glass_length = strlen($eye_glass) ; 
		
		
		$key_holder_length = strlen($key_holder) ;
		
		$lock = $key.$key_holder ;
		//$lock_length = $key_length + $key_holder_length ;	
		$lock_length = strlen($lock) ;
		
		$key_holder_position = strpos($large_string, $key_holder ) ;
	//	echo "<h3>key = ".$key."</h3>" ;
		if($key_holder_position !== false){
			
			$potential_key = substr($large_string, ($key_holder_position + $key_holder_length), ($key_length + 1) ) ;
	//		echo "<h5>potential_key = substr(large_string, ($key_holder_position + $key_holder_length), $key_length )</h5>" ;
	//		echo "<h4>potential_key = ".$potential_key."</h4>" ;
			$first_part_of_large_string = substr($large_string, 0, ($key_holder_position) ) ;
			$second_part_of_large_string = substr($large_string, ($key_holder_position + 1) ) ;
	//		echo "<pre>second_part_of_large_string = ".$second_part_of_large_string."</pre>" ;
			if(trim($potential_key) == trim($key)){
						
				$eye_glass_position = strpos($first_part_of_large_string, $eye_glass) ;
				
				$eye_glass_end_position = ($eye_glass_position + $eye_glass_length) ;
				
				if($eye_glass_position !== false){
					$length_of_first_part_of_large_string = strlen($first_part_of_large_string) ;
					
					$needle = substr($first_part_of_large_string, $eye_glass_end_position, ($length_of_first_part_of_large_string - $eye_glass_end_position ) ) ;
					
		//			echo "<h3>needle = ".$needle."</h3>" ;
					
					//Store Needle
					array_push($needle_array, trim($needle) ) ;
					
					//Re-run the process using $second_part_of_large_string in place of $large_string
					return $this->recursiveGetObjectChild($key_holder, $key, $eye_glass, $second_part_of_large_string, $needle_array) ;
				}else{
					return $this->recursiveGetObjectChild($key_holder, $key, $eye_glass, $second_part_of_large_string, $needle_array) ;
				}
			}else{
				return $this->recursiveGetObjectChild($key_holder, $key, $eye_glass, $second_part_of_large_string, $needle_array) ;
			}
		}
		return $needle_array ;
	}
	
	public function getTemplateModulePositionInfo($position_id, $status = 1){
		// Get Position Content Info From database
		$query = $this->db->query("SELECT * FROM wms_template_module_positions WHERE position_id = '".$position_id."' AND status = ".$status."");
		
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
	
	public function getTemplateModulePositionInfoByNameId($template_id, $position_name_id, $status = 1){
		// Get Position Content Info From database
		$query = $this->db->query("SELECT * FROM wms_template_module_positions WHERE template_id = '".$template_id."' AND name_id = '".$position_name_id."' AND status = ".$status."");
		
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
			@$pos_obj->id 			= @$position_id ;
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
			@$pos_cnt_obj->id = @$position_id ;
			$pos_cnt_obj->cnt_type = $content_type ;
			$pos_cnt_obj->cnt_id = $content_id ;
			$pos_cnt_obj->type = $type ;
			$pos_cnt_obj->status = $status ;
			$pos_cnt_obj->time_added = $time ;
			return $pos_cnt_obj ;
		}
		return false;
	}
	public function getModuleContentTypeString($type_int){
		switch($type_int){
			case 1: return "Text" 	; break ;
			case 2: return "Widget" ; break ;
			case 3: return "HTML" 	; break ;
		}
	}
	
	/* TEMPLATE SET DEFAULT */
	public function setDefaultTemplate(){
		if($this->input->get('new_default_template') !== false){
			$new_template_id = protect($this->input->get('new_default_template')) ;
			$query1 = $this->db->query("SELECT template_id FROM wms_template_default ") ;
			if($query1->num_rows() > 0){
				$query = $this->db->query("UPDATE wms_template_default SET template_id = '$new_template_id' ");
			}else{
				$type = 1; $status = 1; date_default_timezone_set("GMT") ; $current_datetime = date( 'Y-m-d H:i:s' ) ;
				$query = $this->db->query("INSERT INTO wms_template_default(template_id, type, status, time) VALUES('$new_template_id', '$type', '$status', '$current_datetime') ");
			}
			header("Location:".$this->rootdir."admin/index/templates") ;
		}
	}
	
	/* GET TEMPLATE CSS STYLE STRING */
	public function getTemplateCSSStyle($template_id, $status = "status = 1"){
		$query = $this->db->query("SELECT style FROM wms_template_style WHERE template_id = '".$template_id."' AND ".$status." ");
		
		if ($query->num_rows() > 0)
		{
			$res = $query->result_array() ;
			return $res[0]['style'] ;
		}
		return false ;
	}
	
	/* TEMPLATE SECTION VIEWS */
	public function showAllTemplates(){
		$o = "" ;
		$all_templates 			= $this->getAllTemplates() ;
		$default_template_id 	= $this->getDefaultTemplateId() ;
				
		if($all_templates !== false){
			for($i = 0; $i < count($all_templates); $i++){
				
				list($template_id, $template_name, $css_dir, $xml_dir, $type, $status, $time) = $all_templates[$i] ;
				
				$o .= "<a href='".$this->rootdir."admin/index/templates?new_default_template=".$template_id."' class='block_in hide-link metro_tile_square light-to-dark-border'>" ;
					$o .= "<div>$template_name</div>" ;
					if($template_id == $default_template_id){
						$o .= "<span class='spacer-small'></span>" ;
						$o .= "<div class='block spacer-x-small'><span class='img_check_icon'></span></div>" ;
					}
				$o .= "</a>" ;
				
			}
		}
		return $o ;
	}
	public function showTemplateHomeModulePositionManager($template_id = ""){
		$o = "" ;
		if($template_id == ""){
			$template_id = $this->getDefaultTemplateId() ;
			
		}
		
		$object_string = $this->getTemplateModulePositionObjectString($template_id) ;
		
//		print($object_string) ;
//		$children =  $this->recursiveGetObjectChild("[group_name_id] => ", "top_a"." ", "[name_id] => ", $object_string ) ;
//		print_r($children) ;
//		echo "<hr/>" ;
		
		$this->getTemplateBasePositionChildPositions($template_id, $object_string) ;
		
		$template_info_obj = $this->getTemplateInfo($template_id) ;
		
		$position_groups = $this->getTemplateModulePositionGroups($template_id) ;
		
		$module_positions = $this->getTemplateModulePositions($template_id) ;
		
		//Group and Position Count
		$groups_count = count($position_groups) ;
		$positions_count = count($module_positions) ;
		
		//HTML
		$o .= "<div class='block_auto'>" ;
			$o .= "<div class='block_auto pad_all'>" ;
				$o .= "<div class='block_auto'>" ;
					$o .= "<div class='block_in'>MODULE POSITION MANAGER" ;
					if(isset($template_info_obj->name)){
						$o .= "(Template: ".$template_info_obj->name.")" ;
					}
					$o .= "</div>" ;
					$o .= "<div class='block_right'>" ;
						$o .= "<a href='".$this->rootdir."admin/index/templates/create_a_new_template' class='btn btn-success block_left'>Create a New Template</a>" ;
						$o .= "<span class='spacer-x-smallest'>&nbsp;</span>" ;
						$o .= "<a href='".$this->rootdir."admin/index/templates/add_new_temp_position/".$template_id."' class='btn btn-primary block_left'>Add New Position</a>" ;
						$o .= "<span class='spacer-x-smallest'>&nbsp;</span>" ;
						$o .= "<a href='".$this->rootdir."admin/index/templates/edit_temp_css/".$template_id."' class='btn btn-primary block_left'>Edit Template CSS</a>" ;
					$o .= "</div>" ;
				$o .= "</div>" ;
				$o .= "<table class='table table-bordered table-condensed'>" ;
					$o .= "<thead>" ;
						$o .= "<tr>" ;
							$o .= "<th>Positions</th>" ;
							$o .= "<th>Parent Position</th>" ;
							$o .= "<th>Order in Group</th>" ;
							$o .= "<th>Assigned</th>" ;
							$o .= "<th>Module Content Type</th>" ;
							$o .= "<th>Module Id</th>" ;
							$o .= "<th>&nbsp;</th>" ;
							$o .= "<th>Options</th>" ;
						$o .= "</tr>" ;
					$o .= "</thead>" ;
					$o .= "<tbody>" ;
						//Display each module position info
						
						$binary = 0 ;
						if(!empty($this->template_positions_tree)){
							
							for($j = 0; $j < count($this->template_positions_tree); $j++){
								$position_name_id = $this->template_positions_tree[$j][0] ;
								$position_name_heirachy = $this->template_positions_tree[$j][1] ;
								
								$position_info_obj = $this->getTemplateModulePositionInfoByNameId($template_id, $position_name_id) ;
								
								$position_id		= $position_info_obj->id ;
								$template_id		= $position_info_obj->temp_id ;
								$name_id			= $position_info_obj->name_id ;
								$group_name_id		= $position_info_obj->grp_name_id ;
								$order_in_group		= $position_info_obj->grp_order ;
								$type				= $position_info_obj->type ;
								$status				= $position_info_obj->status ;
								$time				= $position_info_obj->time_added ;
								
								$pos_cnt_obj = $this->getPositionContentInfo($position_id) ;
								$assigned = "No" ; $module_content_type = "" ; $module_content_id = "" ;
								if($pos_cnt_obj != false){
									$assigned = "Yes" ;
									$module_content_type = $this->getModuleContentTypeString($pos_cnt_obj->cnt_type) ;
									$module_content_id = $pos_cnt_obj->cnt_id ;
								}
								
								$o .= "<tr class='bin_trow_".$binary."'>" ;
									$o .= "<td>".appendIndicator($position_name_heirachy, "- ")."<span class='do_bold'>".$name_id."</span></td>" ;
									$o .= "<td><span class='do_italic'>".$group_name_id."</span></td>" ;
									$o .= "<td>".$order_in_group."</td>" ;
									$o .= "<td>".$assigned."</td>" ;
									$o .= "<td>".$module_content_type."</td>" ;
									$o .= "<td>".$module_content_id."</td>" ;
									$o .= "<td>&nbsp;</td>" ;
									$o .= "<td>" ;
										
										$o .= "<a href='".$this->rootdir."admin/index/templates/delete_temp_position/".$position_id."' class='btn btn-danger block_left' title='Permanently Delete this template module position' >Delete</a>" ;
										$o .= "<span class='block_left'>&nbsp;</span>" ;
										$o .= "<a href='".$this->rootdir."admin/index/templates/edit_temp_position/".$position_id."' class='btn btn-default block_left' title='Edit this template module position'>Edit</a>" ;
										$o .= "<span class='block_left'>&nbsp;</span>" ;
										if(($module_content_id == "") && ($module_content_type == "")){
											$o .= "<a href='".$this->rootdir."admin/index/templates/assign_position/".$position_id."' class='btn btn-primary block_left' title='Assign Content to this Module'>Assign</a>" ;
										}else if(($module_content_id != "") && ($module_content_type != "")){
											$o .= "<a href='".$this->rootdir."admin/index/templates/undo_assign_position/".$position_id."' class='btn btn-warning block_left' title='Unassign this Module' >Unassign</a>" ;
										}
									$o .= "</td>" ;
								$o .= "</tr>" ;
								
								if($binary == 0){ $binary = 1 ; 
								}else if($binary == 1){ $binary = 0 ; }
							}//end for
						}// end if
					$o .= "</tbody>" ;
				$o .= "</table>" ;
			$o .= "</div>" ;
		$o .= "</div>" ;
		
		return $o ;
	}
	
	public function handleTemplatesActionRequest($index_param, $index_function, $index_p1, $index_p2, $index_p3, $index_p4, $index_p5){
		$this->menu_selection = $index_param ;
		switch($index_function){
			case 'create_a_new_template': return $this->createTemplate().$this->showAllTemplates() ; break ;
			case 'add_new_temp_position': return $this->addNewTempPosition($index_p1).$this->showTemplateHomeModulePositionManager() ; break ;
			case 'edit_temp_css'		: return $this->edit_temp_css($index_p1) ; break ;
			case 'edit_temp_position' 	: return $this->editTempPosition($index_p1).$this->showTemplateHomeModulePositionManager() ; break ;
			case 'delete_temp_position' : return $this->deleteTempPosition($index_p1).$this->showTemplateHomeModulePositionManager() ; break ;
			case 'assign_position' 		: return $this->assignTempPositionToContent($index_p1).$this->showTemplateHomeModulePositionManager() ; break ;
			case 'undo_assign_position' : return $this->undoAssignTempPositionToContent($index_p1).$this->showTemplateHomeModulePositionManager() ; break ;
			default : return $this->getActionRequestError() ;
		}
	}
	
	public function undoAssignTempPositionToContent($position_id){
		$o = "" ;
		$this->err = "" ;
		
		if($this->input->post('undo_asg_content_submit') !== false){
				
			$this->form_validation->set_rules('undo_asg_position_id', 'Position Id', 'required');
			$this->form_validation->set_rules('undo_asg_content_id', 'Content Id', 'required');
			$this->form_validation->set_rules('undo_asg_content_type', 'Content Type', 'required');
			
			if ($this->form_validation->run() == FALSE){
				$o .= $this->getForm_undoAssignTempPositionToContent($position_id) ;
			}
			else
			{
				$undo_asg_position_id 		= protect($this->input->post('undo_asg_position_id') ) ;
				$undo_asg_content_id 		= protect($this->input->post('undo_asg_content_id') ) ;
				$undo_asg_content_type_int 	= protect($this->input->post('undo_asg_content_type') ) ;
				
				//Authenticate Values
				if( $this->verifyTemplateModulePositionId($undo_asg_position_id) === true){
					$undo_asg_position_info = $this->getTemplateModulePositionInfo($undo_asg_position_id) ;
					
					//Ensure that Position is Currently Unavailable
					if($this->verifyTemplateModulePositionAvailability($undo_asg_position_id) === false){
							
						//Update Details in DB
						if( $this->updateUndoAssignTempPositionToContent($undo_asg_position_id, $undo_asg_content_id, $undo_asg_content_type_int) == 1){
							$this->err .= "<span class='form-box no_border_bottom' id='form-success'><p>Position (".$undo_asg_position_info->name_id.") has been unassigned successfully!</p></span>" ;
						}else{
							$this->err .= "<span class='form-box no_border_bottom' id='form-error'><p>An error occured while unassigning this Position (".$undo_asg_position_info->name.")!</p></span>" ;
						}
					}else{
						$this->err .= "<span class='form-box no_border_bottom' id='form-error'><p>This Position (".$undo_asg_position_info->name_id.") has not yet been assigned, so we can't unassign it!</p></span>" ;
					}
				}else{
					$this->err .= "<span class='form-box no_border_bottom' id='form-error'>" ;
					$this->err .= "<p>This Position Id, \"".$pos_position_id."\" does not exist!</p></span>" ;
				}
				
				$o .= $this->showActionResultMessage() ;
			}
		}else{
			if( $this->verifyTemplateModulePositionId($position_id) === true){
				$o .= $this->getForm_undoAssignTempPositionToContent($position_id) ;
			}else{
				$o .= $this->getActionRequestError() ;
			}
		}
		
		return $o ;
	}
	
	public function assignTempPositionToContent($position_id){
		$o = "" ;
		$this->err = "" ;
		
		if($this->input->post('assign_content_submit') !== false){
			
			$this->form_validation->set_rules('asg_position_id', 'Position Id', 'required');
			$this->form_validation->set_rules('asg_content_id', 'Content Id', 'required');
			
			if($this->form_validation->run() == FALSE){
				$o .= $this->getForm_assignTempPositionToContent($position_id) ;
			}else{
				$asg_position_id 		= protect($this->input->post('asg_position_id') ) ;
				$asg_content_id_pack 	= protect($this->input->post('asg_content_id') ) ;
				
				$asg_content_id_pack = explode(",", $asg_content_id_pack) ;
				if(count($asg_content_id_pack) == 2){
					$asg_content_type 	= $asg_content_id_pack[0] ;
					$asg_content_id 	= $asg_content_id_pack[1] ;
					
					//Authenticate Values
					if( $this->verifyTemplateModulePositionId($asg_position_id) === true){
						$asg_position_info = $this->getTemplateModulePositionInfo($asg_position_id) ;
						
						//Check Position Availability
						if($this->verifyTemplateModulePositionAvailability($position_id) === true){
						
							//Update Details in DB
							if( $this->insertAssignTempPositionToContent($asg_position_id, $asg_content_id, $asg_content_type) == 1){
								$this->err .= "<span class='form-box no_border_bottom' id='form-success'><p>The Content, $asg_content_id, has been successfully assigned to this Position (".$asg_position_info->name_id.")!</p></span>" ;
							}else{
								$this->err .= "<span class='form-box no_border_bottom' id='form-error'><p>An error occured while assigning the content ($asg_content_id) to this Position!</p></span>" ;
							}
						}else{
							$this->err .= "<span class='form-box no_border_bottom' id='form-error'>" ;
							$this->err .= "<p>Content has already been assigned to this Position ( \"".$asg_position_info->name_id."\")!</p></span>" ;
						}
					}else{
						$this->err .= "<span class='form-box no_border_bottom' id='form-error'>" ;
						$this->err .= "<p>This Position Id, \"".$asg_position_id."\" does not exist!</p></span>" ;
					}
				}else{ 
					$this->err .= "<span class='form-box no_border_bottom' id='form-error'>" ;
					$this->err .= "<p>The Content Info, \"".$asg_content_id_pack."\" was incomplete!</p></span>" ;
				}
				
				$o .= $this->showActionResultMessage() ;
			}
			
		}else{
			$o .= $this->getForm_assignTempPositionToContent($position_id) ;
		}
		
		return $o ;
	}
	
	public function deleteTempPosition($position_id){
		$o = "" ;
		$this->err = "" ;
		
		if($this->input->post('del_pos_submit') !== false){
				
			$this->form_validation->set_rules('del_position_id', 'Position Id', 'required');
			
			if ($this->form_validation->run() == FALSE){
				$o .= $this->getForm_deleteTempPosition($position_id) ;
			}
			else
			{
				$pos_position_id 	= protect($this->input->post('del_position_id') ) ;
				
				//Authenticate Values
				
				if( $this->verifyTemplateModulePositionId($pos_position_id) === true){
					//Update Details in DB
					if( $this->deleteUpdateTempPosition($pos_position_id) == 1){
						$this->err .= "<span class='form-box no_border_bottom' id='form-success'><p>Position deleted successfully!</p></span>" ;
					}else{
						$this->err .= "<span class='form-box no_border_bottom' id='form-error'><p>Position Delete failed!</p></span>" ;
					}
				}else{
					$this->err .= "<span class='form-box no_border_bottom' id='form-error'>" ;
					$this->err .= "<p>This Position Id, \"".$pos_position_id."\" does not exist!</p></span>" ;
				}
				
				$o .= $this->showActionResultMessage() ;
			}
		}else{
			if( $this->verifyTemplateModulePositionId($position_id) === true){
				$o .= $this->getForm_deleteTempPosition($position_id) ;
			}else{
				$o .= $this->getActionRequestError() ;
			}
		}
		
		return $o ;
	}
	
	public function editTempPosition($position_id){
		$o = "" ;
		$this->err = "" ;
		if( $this->verifyTemplateModulePositionId($position_id) === true){
			if($this->input->post('edit_pos_submit') !== false){
				
				$this->form_validation->set_rules('edit_pos_group', 'Position Group Name', 'required');
				$this->form_validation->set_rules('edit_pos_name', 'Position Name', 'required');
				$this->form_validation->set_rules('edit_pos_order', 'Order in Group', 'required|integer');
				
				if ($this->form_validation->run() == FALSE)
				{
					$o .= $this->getForm_editTempPosition($position_id) ;
				}
				else
				{
					$pos_position_id 	= protect($this->input->post('edit_position_id') ) ;
					$pos_template_id 	= protect($this->input->post('edit_temp_id') ) ;
					$pos_grp_name 		= strtolower(protect($this->input->post('edit_pos_group') ) ) ;
					$pos_name 			= strtolower(protect($this->input->post('edit_pos_name') ) ) ;
					$pos_order 			= protect($this->input->post('edit_pos_order') ) ;
					
					//Authenticate Values
					if( $this->verifyDefaultTemplateId($pos_template_id) === true){
						//Update Details in DB
						if( $this->verifyTemplateModulePositionId($pos_position_id, $pos_grp_name, $pos_template_id) === true){
							if( $this->updateTempPosition($pos_position_id, $pos_order) == 1){
								$this->err .= "<span class='form-box no_border_bottom' id='form-success'><p>Position (".$pos_name.") edited successfully!</p></span>" ;
								$o .= $this->showActionResultMessage() ;
							}else{
								$this->err .= "<span class='form-box no_border_bottom' id='form-error'><p>Position Edit failed!</p></span>" ;
								$o .= $this->getForm_editTempPosition($position_id) ;
							}
						}else{
							$this->err .= "<span class='form-box no_border_bottom' id='form-error'>" ;
							$this->err .= "<p>This Position Id, \"".$pos_position_id."\" does not exist in Position Group \"".$pos_grp_name."\" of template \"".$this->getTemplateName($pos_template_id)."\"!</p></span>" ;
							$o .= $this->getForm_editTempPosition($position_id) ;
						}
					}else{
						$this->err .= "<span class='form-box no_border_bottom' id='form-error'><p>This Template doesn't exist!</p></span>" ;
						$o .= $this->getForm_editTempPosition($position_id) ;
					}
					
					
				}
					
				
			}else{
				$o .= $this->getForm_editTempPosition($position_id) ;
			}
		}else{
			$o .= $this->getActionRequestError() ;
		}
		return $o ;
	}
	
	public function addNewTempPosition($template_id){
		$o = "" ;
		$this->err = "" ;
		if( $this->verifyDefaultTemplateId($template_id) === true){
			if($this->input->post('add_pos_submit') !== false){
				
				$this->form_validation->set_rules('pos_group', 'Position Group Name', 'required');
				$this->form_validation->set_rules('pos_name', 'Position Name', 'required');
				$this->form_validation->set_rules('pos_order', 'Order in Group', 'required|integer');
				
				if ($this->form_validation->run() == FALSE)
				{
					$o .= $this->getForm_addNewTempPosition($template_id) ;
				}
				else
				{
					$pos_template_id 	= protect($this->input->post('temp_id') ) ;
					$pos_grp_name 		= strtolower(protect($this->input->post('pos_group') ) ) ;
					$pos_name 			= strtolower(protect($this->input->post('pos_name') ) ) ;
					$pos_order 			= protect($this->input->post('pos_order') ) ;
					
					//Authenticate Values
					if( $this->verifyDefaultTemplateId($pos_template_id) === true){
						//Store Details into DB
						if( $this->verifyTemplateModulePositionName($pos_name, "", $pos_template_id) === false){
							if( $this->insertNewTempPosition($pos_template_id, $pos_name, $pos_grp_name, $pos_order) == 1){
								$this->err .= "<span class='form-box no_border_bottom' id='form-success'><p>Position (".$pos_name.") added successfully!</p></span>" ;
								$o .= $this->showActionResultMessage() ;
							}else{
								$this->err .= "<span class='form-box no_border_bottom' id='form-error'><p>Position Add failed!</p></span>" ;
								$o .= $this->getForm_addNewTempPosition($template_id) ;
							}
						}else{
							$this->err .= "<span class='form-box no_border_bottom' id='form-error'>" ;
							$this->err .= "<p>This Position Name, \"".$pos_name."\" already exists in Template (".$this->getTemplateName($template_id).") . Position names in a template must be unique!</p></span>" ;
							$o .= $this->getForm_addNewTempPosition($template_id) ;
						}
					}else{
						$this->err .= "<span class='form-box no_border_bottom' id='form-error'><p>This Template doesn't exist!</p></span>" ;
					}
					
				}
					
				
			}else{
				$o .= $this->getForm_addNewTempPosition($template_id) ;
			}
		}else{
			$o .= $this->getActionRequestError() ;
		}
		return $o ;
	}
	
	public function edit_temp_css($template_id){
		$o = "" ;
		$this->admin_forms->err = "" ;
		if( $this->verifyDefaultTemplateId($template_id) === true){
			if($this->input->post('edit_temp_css_submit') !== false){
				
				$this->form_validation->set_rules('edit_temp_css_id', 'Template ID Hidden Field', 'required');
				$this->form_validation->set_rules('edit_temp_css_name', 'Template Name Hidden Field', 'required');
				
				if ($this->form_validation->run() == FALSE)
				{
					$o .= $this->getForm_editTempCSS($template_id) ;
				}
				else
				{
					$edit_temp_css_id 		= protect($this->input->post('edit_temp_css_id') ) ;
					$edit_temp_css_text 	= protectExactText($this->input->post('edit_temp_css_text') ) ;
					$edit_temp_css_name 	= protect($this->input->post('edit_temp_css_name') ) ;
					
					//Authenticate Values
					if( $this->verifyDefaultTemplateId($edit_temp_css_id) === true){
						//Store Details into DB
							if( $this->updateEditTemplateCSSInfo($edit_temp_css_id, $edit_temp_css_text) == 1){
								$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-success'><p>Template CSS Changes Saved Successfully!</p></span>" ;
							}else{
								$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'><p>Template CSS edit failed!</p></span>" ;
							}
					}else{
						$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'><p>This Template doesn't exist!</p></span>" ;
					}
					
					$o .= $this->getForm_editTempCSS($template_id) ;
				}
					
				
			}else{
				$o .= $this->getForm_editTempCSS($template_id) ;
			}
		}else{
			$o .= $this->getActionRequestError() ;
		}
		return $o ;
	}
	
	public function createTemplate(){
		$o = "" ;
		$this->admin_forms->err = "" ;
			if($this->input->post('create_template_submit') !== false){
				
				$this->form_validation->set_rules('create_template_name', 'Template Name', 'required');
				$this->form_validation->set_rules('create_template_options', 'Create Options', 'required');
				
				if ($this->form_validation->run() == FALSE)
				{
					$o .= $this->getForm_createTemplate() ;
				}
				else
				{
					
					$new_template_name 		= protect($this->input->post('create_template_name') ) ;
					$new_template_options 	= protect(trim($this->input->post('create_template_options')) ) ;
					
					//Authenticate Values
						//Store Details into DB
						if( $this->verifyTemplateName($new_template_name) === false){
							if( $this->executeCreateNewTemplate($new_template_name, $new_template_options) == true){
								$msg = "<span class='form-box no_border_bottom' id='form-success'><p>New Template (".$new_template_name.") created successfully!</p></span>" ;
								$o .= $this->admin_forms->showFormMessage($this->admin_forms->err.$msg) ;
							}else{
								$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'><p>New Template Create Add failed!</p></span>" ;
								$o .= $this->getForm_createTemplate() ;
							}
						}else{
							$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'>" ;
							$this->admin_forms->err .= "<p>This Name, \"".$new_template_name."\" has already been assigned to a template. Template names must be unique!</p></span>" ;
							$o .= $this->getForm_createTemplate() ;
						}
					
				}
					
				
			}else{
				$o .= $this->getForm_createTemplate() ;
			}
		return $o ;
	}
	
	private function executeCreateNewTemplate($new_template_name, $new_template_options){
		if($new_template_name != ""){
			if($new_template_options == blankTemplateValue){
				return $this->simpleCreateTemplate($new_template_name) ;
			}else{
				$existing_template_id = $new_template_options ;
				return $this->createTemplateFromExisting($new_template_name, $existing_template_id) ;
			}
		}
		return false ;
	}
	
	private function simpleCreateTemplate($new_template_name){
		$template_id = createAnId('wms_templates', 'template_id') ;
		$res1 = $this->insertNewTemplateDetails($template_id, $new_template_name) ;
		if($res1 === true){
			return true ;
		}
	}
	
	private function createTemplateFromExisting($new_template_name, $existing_template_id){
		//Verify Existing Template
		if($this->verifyTemplateId($existing_template_id) === true){
			//Get Existing Template Info
			$template_info = $this->getTemplateInfo($existing_template_id) ; //Object
			if($template_info !== false){
				//Save New Template Info
				$new_template_id = createAnId('wms_templates', 'template_id') ;
				$res1 = $this->insertNewTemplateDetails($new_template_id, $new_template_name) ;
				
				if($res1 === true){
					/** Save Template Info was Successful **/
					//Get Existing Template CSS if available and Save New Template CSS
					$existing_css = $this->getTemplateCSSStyle($existing_template_id) ;
					if($existing_css !== false){
						if( $this->updateEditTemplateCSSInfo($new_template_id, $existing_css) == 1){
							//Template CSS copied successfully!
						}else{
							$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'><p>Template CSS was not copied!</p></span>" ;
						}
					}
					
					//$template_info->id, $template_info->name
					//Get Existing Template Module Position Info
					$existing_temp_positions = $this->getTemplateModulePositions($existing_template_id) ; //Array of Numerical Arrays
					if(count($existing_temp_positions) > 0){
						
						for($i = 0; $i < count($existing_temp_positions); $i++ ){
							//Save New Template Position Info using this Existing position info
							
							list($e_position_id, $e_template_id, $e_name_id, $e_group_name_id, $e_order_in_group, $e_type, $e_status, $e_time) = $existing_temp_positions[$i] ;
							//Create Position ID for new Template Position
							$n_position_id 			= createAnId('wms_template_module_positions', 'position_id') ;
							$n_template_id 			= $new_template_id ;
							$n_name_id 				= $e_name_id ;
							$n_group_name_id 		= $e_group_name_id ;
							$n_order_in_group 		= $e_order_in_group ;
							
							$res2 = $this->specialInsertNewTempPosition($n_position_id, $n_template_id, $n_name_id, $n_group_name_id, $n_order_in_group) ;
							if($res2 === true){
								//Get Existing Template Position Content Info
								$content_positioning_info = $this->getPositionContentInfo($e_position_id) ;
								if($content_positioning_info !== false){
									//Save Position Content Info
									$c_position_id 	= $n_position_id ;
									$c_content_type = $content_positioning_info->cnt_type ;
									$c_content_id 	= $content_positioning_info->cnt_id ;
									
									$res3 = $this->insertAssignTempPositionToContent($c_position_id, $c_content_id, $c_content_type ) ;
									if($res3 == 1){
										//Continue
									}else{
										//The contents in existing position (e_position_id) was not copied to new position (n_position_id)
										$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'><p>" ;
										$this->admin_forms->err .= "The contents in existing position (".$e_position_id.") was not copied to new position (".$n_position_id.")!</p></span>" ;
									}
									//Get Existing Template Widget Settings Instance Info if available (Version 2)
									//Save Position Widget Settings info if available (Version 2)
								}
							}else{
								//A New Module Position could not be created using its corresponding Existing module position
								$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'><p>" ;
								$this->admin_forms->err .= "A New Module Position (".$n_position_id.") could not be created using its corresponding Existing module position (".$e_position_id.")!</p></span>" ;
							}
						}// end for count(existing_temp_positions)
						return true ;
					}else{
						//No Module Positions are available for the existing template
						$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'><p>No Module Positions are available for the existing template (ID='".$existing_template_id."')!</p></span>" ;
					}
				}else{
					//The new Template's information was not saved
					$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'><p>The new Template's information ('".$new_template_name."') was not Saved!</p></span>" ;
				}
			}else{
				//This existing template has no info OR an error occurred while getting it.
				$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'><p>This existing template (ID='".$existing_template_id."') has no info OR an error occurred while getting it!</p></span>" ;
			}
		}else{
			//The existing Template chosen does not Exist.
			$this->admin_forms->err .= "<span class='form-box no_border_bottom' id='form-error'><p>The existing Template Chosen does not Exist (ID='".$existing_template_id."')!</p></span>" ;
		}
		return false;
	}
	
	private function insertNewTemplateDetails($template_id, $new_template_name, $css_dir = "", $xml_dir = ""){
		$type = 1; $status = 1 ;
		$query = $this->db->query("INSERT INTO wms_templates VALUES ('$template_id', '$new_template_name', '$css_dir', '$xml_dir', '$type', '$status', CURRENT_TIMESTAMP) ");
		return true ;
	}
	
	private function insertNewTempPosition($pos_template_id, $pos_name, $pos_grp_name, $pos_order){
		$position_id = createAnId('wms_template_module_positions', 'position_id') ;
		$type = 1; $status = 1 ;
		$query = $this->db->query("INSERT INTO wms_template_module_positions VALUES ('$position_id', '$pos_template_id', '$pos_name', '$pos_grp_name', '$pos_order', '$type', '$status', CURRENT_TIMESTAMP) ");
		return 1 ;
	}
	
	private function specialInsertNewTempPosition($position_id, $pos_template_id, $pos_name, $pos_grp_name, $pos_order){
		$type = 1; $status = 1 ;
		$query = $this->db->query("INSERT INTO wms_template_module_positions VALUES ('$position_id', '$pos_template_id', '$pos_name', '$pos_grp_name', '$pos_order', '$type', '$status', CURRENT_TIMESTAMP) ");
		return true ;
	}
	
	private function updateEditTemplateCSSInfo($template_id, $css_text, $status = 1){
		if($this->verifyTemplateCSSExists($template_id) === true ){
			$query = "UPDATE wms_template_style SET style = '$css_text', status = '$status' WHERE template_id = '$template_id' " ;
		}else{
			$query = "INSERT INTO wms_template_style VALUES ('$template_id', '$css_text', '$status') " ;
		}
		$query = $this->db->query($query);
		return 1 ;
	}
	
	private function updateTempPosition($position_id, $pos_order, $type = 1, $status = 1){
		$query = $this->db->query("UPDATE wms_template_module_positions SET order_in_group = '$pos_order', type = '$type', status = '$status' WHERE position_id = '$position_id' ");
		return 1 ;
	}
	private function deleteUpdateTempPosition($position_id ){
		$status = 9 ;
		$query = $this->db->query("UPDATE wms_template_module_positions SET status = '$status' WHERE position_id = '$position_id' ");
		return 1 ;
	}
	private function insertAssignTempPositionToContent($position_id, $content_id, $content_type){
		$type = 1; $status = 1 ;
		$query = $this->db->query("INSERT INTO wms_template_module_positioning VALUES ('$position_id', '$content_type', '$content_id', '$type', '$status', CURRENT_TIMESTAMP) ");
		return 1 ;
	}
	private function updateUndoAssignTempPositionToContent($position_id, $content_id, $content_type){
		$status = 9 ;
		$query = $this->db->query("UPDATE wms_template_module_positioning SET status = '$status' WHERE position_id = '$position_id' AND content_id = '$content_id' AND content_type = '$content_type' ");
		return 1 ;
	}
	
	/* Forms */
	private function getForm_createTemplate(){
		$o = "" ;
		
		$templates_options = array() ;
		$all_templates = $this->getAllTemplates() ;
		
		if($all_templates !== false){
			for($i = 0; $i < count($all_templates); $i++){
				
				list($template_id, $template_name, $css_dir, $xml_dir, $type, $status, $time) = $all_templates[$i] ;
				$value_array = array(" ".$template_id." " => "copy \"".$template_name."\"" ) ;
				$templates_options = array_merge($templates_options, $value_array) ;
			}
		}else{ /* There is no existing template */ }
				
		//FIELDS
			$form_fields_html = array() ;
				
				//Template Name
				$field = $this->admin_forms->getInputText("Template Name:","create_template_name", "long_input", "", "Enter a Name for the New Template", "required") ;
				array_push($form_fields_html, $field) ;
				
				$default_options_array = array(blankTemplateValue => "Create A Blank Template") ;
				
				//Create Options
				$field = $this->admin_forms->getRegularSelect("Create Options", "create_template_options", "long_input", "", "required", true, $default_options_array, $templates_options ) ;
				array_push($form_fields_html, $field) ;
				
				//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonField("Create Template", "create_template_submit", "", "submit", true, $this->rootdir."admin/index/templates", "Cancel") ;
					
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("CREATE A NEW TEMPLATE", $form_fields_html, $submit_field, "post", "", "", "block inline_label") ;
			
			$o .= $form_html ;
		
		return $o ;
	}
	
	private function getForm_editTempCSS($template_id){
		$o = "" ;
		
		$template_obj = $this->getTemplateInfo($template_id) ;
		
		$template_style = $this->getTemplateCSSStyle($template_id) ;
		if($template_style === false){
			$template_style = "" ;
		}
		
		$object_string = $this->getTemplateModulePositionObjectString($template_id) ;
		
		$this->getTemplateBasePositionChildPositions($template_id, $object_string) ;
		
		//Position Name IDs
			$o .= "<div class='form-group block_left'>" ;
											
				$unique_position_name_ids = $this->template_positions_tree ;
		//		$o .= "<div>&nbsp;#default</div>" ;
				for($i = 0; $i < count($unique_position_name_ids); $i++){
					$b_name_id = $unique_position_name_ids[$i][0] ;
					$b_heirachy = $unique_position_name_ids[$i][1] ;
					
					$o .= "<div class='pad_all_tiny light-border'>".appendIndicator($b_heirachy + 1, "&nbsp; &nbsp; &nbsp;")."#"."wms-pos-".$b_name_id."</div>" ;
				}
					
			$o .= "</div>" ;
			
		//FIELDS
			$form_fields_html = array() ;
				
				//Form submit button
				$field = "<div class='block pad_all'>" ;
				$field .= $this->admin_forms->getSubmitButtonField("Save Changes", "edit_temp_css_submit", "", "submit", true, $this->rootdir."admin/index/templates", "Cancel") ;
				$field .= "</div>" ;
				array_push($form_fields_html, $field) ;
				
				//Template Name (Hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_temp_css_name", "", $template_obj->name) ;
				array_push($form_fields_html, $field) ;
				
				//Template ID (Hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_temp_css_id", "", $template_id) ;
				array_push($form_fields_html, $field) ;
				
				//CSS Style
				$field = $this->admin_forms->getTextarea("Template CSS:", "edit_temp_css_text", "template_css_editor_box", "", "Type your CSS Here", "", "", getStringTextForm($template_style) ) ;
				array_push($form_fields_html, $field) ;
					
				//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonField("Save Changes", "edit_temp_css_submit", "", "submit", true, $this->rootdir."admin/index/templates", "Cancel") ;
					
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("EDIT TEMPLATE CSS FOR \" ".$template_obj->name." \" ", $form_fields_html, $submit_field, "post", "", "", "block_left") ;
			
			$o .= $form_html ;
		
		return $o ;
	}
	
	private function getForm_addNewTempPosition($template_id){
		$o = "" ;
		
		$template_obj = $this->getTemplateInfo($template_id) ;
				
		$object_string = $this->getTemplateModulePositionObjectString($template_id) ;
				
		$this->getTemplateBasePositionChildPositions($template_id, $object_string) ;
		
		$o .= "<div class='form-box marg_all'>" ;
		$o .= "<form role='form' class='form-vertical' method='post' action=''>" ;
				$o .= "<h4 class='no_bold pad_border_bottom'>ADD A NEW MODULE POSITION</h4>" ;
				
				$o .= "<div id='form-error'>" ;
					if(isset($this->err)){
						$o .= $this->err ;
					}
					$o .= validation_errors() ;
				$o .= "</div>" ;
				
				//Template Name and ID Info
				$o .= "<div class='form-group'>" ;
					$o .= "<label class='control-label' for='temp_name'>Select Template</label>" ;
					$o .= "<input type='text' disabled='disabled' class='form-control long_input' name='temp_name' id='temp_name' value='".$template_obj->name."' >" ;
					$o .= "<input type='hidden' name='temp_id' id='temp_id' value='".$template_id."' >" ;
				$o .= "</div>" ;
				
				//Position Group Name
				$o .= "<div class='form-group'>" ;
					$o .= "<label class='control-label' for='pos_group'>Select Parent Position</label>" ;
				//	$o .= "<input type='text' list='pos_group' class='form-control long_input' name='pos_group' value='".set_value('pos_group')."' placeholder='DOUBLE CLICK here to see existing Group names' required  />" ;
					$o .= "<select name='pos_group' id='pos_group' required>" ;
						
						
						$o .= "<option value='".template_base_position_name."' >".template_base_position_name."</option>" ;
						$unique_position_name_ids = $this->template_positions_tree ;
						
						for($i = 0; $i < count($unique_position_name_ids); $i++){
							$b_name_id = $unique_position_name_ids[$i][0] ;
							$b_heirachy = $unique_position_name_ids[$i][1] ;
							
							$value = appendIndicator($b_heirachy + 1, "&nbsp; ").$b_name_id ;
							$o .= "<option value='".$b_name_id."' >".$value."</option>" ;
						//	$o .= "<option value='".$b_name_id."' >" ;
						}
						
					$o .= "</select>" ;
				$o .= "</div>" ;
				
				//Position Name
				$o .= "<div class='form-group'>" ;
					$o .= "<label class='control-label' for='pos_name'>Position Name</label>" ;
					$o .= "<input type='text' class='form-control long_input' name='pos_name' id='pos_name' value='".set_value('pos_name')."' placeholder='Enter new position&rsquo;s name' required  />" ;
				$o .= "</div>" ;
				
				//Position Order in Group
				$o .= "<div class='form-group'>" ;
					$o .= "<label class='control-label' for='pos_order'>Order in Group</label>" ;
					$o .= "<input type='text' class='form-control long_input' name='pos_order' id='pos_order' value='".set_value('pos_order',0)."' placeholder='Enter new position&rsquo;s sort order' required  />" ;
				$o .= "</div>" ;
				
				//Form submit button
					$o .= "<div class='block'>" ;
						$o .= "<button type='submit' name='add_pos_submit' class='btn btn-success block_left'>Add Position</button>" ;
						$o .= "<span class='block_left'>&nbsp;</span>" ;
						$o .= "<a href='".$this->rootdir."admin/index/templates/' class='btn btn-warning block_left'>Cancel</a>" ;
					$o .= "</div>" ;
		$o .= "</form>" ;
		$o .= "</div>" ;
		return $o ;
	}
	
	/* Edit Template Module Position Form */
	private function getForm_editTempPosition($position_id){
		$o = "" ;
		
		$position_obj = $this->getTemplateModulePositionInfo($position_id) ;
		if($position_obj !== false){
				
			$template_name = $this->getTemplateName($position_obj->temp_id) ;
						
			$o .= "<div class='form-box marg_all'>" ;
			$o .= "<form role='form' class='form-vertical' method='post' action=''>" ;
					$o .= "<h4 class='no_bold pad_border_bottom'>EDIT A MODULE POSITION</h4>" ;
					
					$o .= "<div id='form-error'>" ;
						if(isset($this->err)){
							$o .= $this->err ;
						}
						$o .= validation_errors() ;
					$o .= "</div>" ;
					
					//Position ID Info
					$o .= "<div>" ;
						$o .= "<input type='hidden' name='edit_position_id' id='edit_position_id' value='".$position_obj->id."' >" ;
					$o .= "</div>" ;
					
					//Template Name and ID Info
					$o .= "<div class='form-group'>" ;
						$o .= "<label class='control-label' for='temp_name'>Template Name</label>" ;
						$o .= "<input type='text' disabled='disabled' class='form-control long_input' name='temp_name' id='temp_name' value='".$template_name."' >" ;
						$o .= "<input type='hidden' name='edit_temp_id' id='edit_temp_id' value='".$position_obj->temp_id."' >" ;
					$o .= "</div>" ;
					
					//Position Group Info
					$o .= "<div class='form-group'>" ;
						$o .= "<label class='control-label' for='temp_name'>Position Group</label>" ;
						$o .= "<input type='text' disabled='disabled' class='form-control long_input' value='".$position_obj->grp_name_id."' >" ;
						$o .= "<input type='hidden' name='edit_pos_group' id='edit_pos_group' value='".$position_obj->grp_name_id."' >" ;
					$o .= "</div>" ;
					
					//Position Name Info
					$o .= "<div class='form-group'>" ;
						$o .= "<label class='control-label' for='temp_name'>Position Name</label>" ;
						$o .= "<input type='text' disabled='disabled' class='form-control long_input' value='".$position_obj->name_id."' >" ;
						$o .= "<input type='hidden' name='edit_pos_name' id='edit_pos_name' value='".$position_obj->name_id."' >" ;
					$o .= "</div>" ;
					
					//Position Group order of display Info
					$o .= "<div class='form-group'>" ;
						$o .= "<label class='control-label' for='pos_order'>Order of display in Group (Current order is '".$position_obj->grp_order."')</label>" ;
						$o .= "<input type='text' class='form-control long_input' name='edit_pos_order' id='pos_order' value='".$position_obj->grp_order."' placeholder='Enter new position&rsquo;s sort order' required  />" ;
					$o .= "</div>" ;
					
					//Form submit button
					$o .= "<div class='block'>" ;
						$o .= "<button type='submit' name='edit_pos_submit' class='btn btn-success block_left'>Save Changes</button>" ;
						$o .= "<span class='block_left'>&nbsp;</span>" ;
						$o .= "<a href='".$this->rootdir."admin/index/templates/' class='btn btn-warning block_left'>Cancel</a>" ;
					$o .= "</div>" ;
			$o .= "</form>" ;
			$o .= "</div>" ;
		}
		
		return $o ;
	}
	
	private function getForm_deleteTempPosition($position_id){
		$o = "" ;
		
		$position_obj = $this->getTemplateModulePositionInfo($position_id) ;
		if($position_obj !== false){
				
			$template_name = $this->getTemplateName($position_obj->temp_id) ;
				
			//Position Name, Group Name ID, Template Name, and Position Order Info
			$info_string = "" ;
			$info_string .= "Position Name: ".$position_obj->name_id." <br/> " ;
			$info_string .= "Position Group Name: ".$position_obj->grp_name_id." <br/> " ;
			$info_string .= "Template Name: ".$template_name." <br/> " ;
			$info_string .= "Position Order: ".$position_obj->grp_order ;
			
			$o .= "<div class='form-box marg_all'>" ;
			$o .= "<form role='form' class='form-vertical' method='post' action=''>" ;
					$o .= "<h4 class='no_bold pad_border_bottom'>CONFIRM DELETE MODULE POSITION</h4>" ;
					
					$o .= "<div id='form-error'>" ;
						if(isset($this->err)){
							$o .= $this->err ;
						}
						$o .= validation_errors() ;
					$o .= "</div>" ;
					
					//Position ID Info
					$o .= "<div>" ;
						$o .= "<input type='hidden' name='del_position_id' id='del_position_id' value='".$position_obj->id."' >" ;
					$o .= "</div>" ;
					
					//Position Name, Group Name ID, Template Name, and Position Order Info
					
					$o .= "<div class='form-group'>" ;
						$o .= "<label class='control-label' for='temp_name'>".$info_string."</label>" ;
					$o .= "</div>" ;
					
					//Form submit button
					$o .= "<div class='block'>" ;
						$o .= "<button type='submit' name='del_pos_submit' class='btn btn-danger block_left'>Delete Position</button>" ;
						$o .= "<span class='block_left'>&nbsp;</span>" ;
						$o .= "<a href='".$this->rootdir."admin/index/templates/' class='btn btn-warning block_left'>Cancel</a>" ;
					$o .= "</div>" ;
			$o .= "</form>" ;
			$o .= "</div>" ;
		}
		
		return $o ;
	}
	
	private function getForm_assignTempPositionToContent($position_id){
		$o = "" ;
			//Get position Object for position id
			$position_obj = $this->getTemplateModulePositionInfo($position_id) ;
			if($position_obj !== false){
					
				$template_name = $this->getTemplateName($position_obj->temp_id) ;
				
				$all_widgets = $this->getAllWidgets() ;
				
				$o .= "<div class='form-box marg_all'>" ;
				$o .= "<form role='form' class='form-vertical' method='post' action=''>" ;
						$o .= "<h4 class='no_bold pad_border_bottom'>ASSIGN CONTENT TO A POSITION</h4>" ;
						
						$o .= "<div id='form-error'>" ;
							if(isset($this->err)){
								$o .= $this->err ;
							}
							$o .= validation_errors() ;
						$o .= "</div>" ;
						
						//Position ID Info
						$o .= "<div>" ;
							$o .= "<input type='hidden' name='asg_position_id' id='asg_position_id' value='".$position_obj->id."' >" ;
						$o .= "</div>" ;
						
						//Show Template Name
						$o .= "<div class='form-group'>" ;
							$o .= "<label class='control-label' for='temp_name'>Template Name</label>" ;
							$o .= "<input type='text' disabled='disabled' class='form-control long_input' value='".$template_name."' >" ;
						$o .= "</div>" ;
						
						//Show Position Parent Name
						$o .= "<div class='form-group'>" ;
							$o .= "<label class='control-label' for='temp_name'>Position Parent Name</label>" ;
							$o .= "<input type='text' disabled='disabled' class='form-control long_input' value='".$position_obj->grp_name_id."' >" ;
						$o .= "</div>" ;
						
						//Show Position Name
						$o .= "<div class='form-group'>" ;
							$o .= "<label class='control-label' for='temp_name'>Position Name</label>" ;
							$o .= "<input type='text' disabled='disabled' class='form-control long_input' value='".$position_obj->name_id."' >" ;
							$o .= "<input type='hidden' name='asg_temp_id' id='asg_temp_id' value='".$position_obj->id."' >" ;
						$o .= "</div>" ;
						
						//Select Content Id
						$o .= "<div class='form-group'>" ;
							$o .= "<label class='control-label' for='temp_name'>Content ID</label>" ;
							$o .= "<select name='asg_content_id' id='asg_content_id'' >" ;
								$o .= "<option value='' >-- None Selected --</option>" ;
							for($i = 0; $i < count($all_widgets) ; $i++){
								$o .= "<option value='2,".$all_widgets[$i]."' >".$all_widgets[$i]."</option>" ;
							}
							$o .= "</select>" ;
						$o .= "</div>" ;
						
						//Form submit button
						$o .= "<div class='block'>" ;
							$o .= "<button type='submit' name='assign_content_submit' class='btn btn-success block_left'>Assign</button>" ;
							$o .= "<span class='block_left'>&nbsp;</span>" ;
							$o .= "<a href='".$this->rootdir."admin/index/templates/' class='btn btn-warning block_left'>Cancel</a>" ;
						$o .= "</div>" ;
				$o .= "</form>" ;
				$o .= "</div>" ;
			}
		return $o ;
	}
	
	private function getForm_undoAssignTempPositionToContent($position_id){
		$o = "" ;
		
		$position_obj = $this->getTemplateModulePositionInfo($position_id) ;
		if($position_obj !== false){
				
			$template_name = $this->getTemplateName($position_obj->temp_id) ;
			
			$content_info = $this->getPositionContentInfo($position_id) ;
			if($content_info !== false){
							
				//Position Name, Group Name ID, Template Name, and Position Order Info
				$info_string = "" ;
				$info_string .= "Position Name: ".$position_obj->name_id." <br/> " ;
				$info_string .= "Position Group Name: ".$position_obj->grp_name_id." <br/> " ;
				$info_string .= "Template Name: ".$template_name." <br/> " ;
				$info_string .= "Position Order: ".$position_obj->grp_order." <br/> " ;
				
				//Position Content type, Position Content Info
				$info_string .= "Content Type: ".$this->getContentTypeString($content_info->cnt_type)." <br/> " ;
				$info_string .= "Content Id: ".$content_info->cnt_id." <br/> " ;
				
				$o .= "<div class='form-box marg_all'>" ;
				$o .= "<form role='form' class='form-vertical' method='post' action=''>" ;
						$o .= "<h4 class='no_bold pad_border_bottom'>CONFIRM UN-ASSIGN CONTENT FROM POSITION</h4>" ;
						
						$o .= "<div id='form-error'>" ;
							if(isset($this->err)){
								$o .= $this->err ;
							}
							$o .= validation_errors() ;
						$o .= "</div>" ;
						
						//Position ID Info
						$o .= "<div>" ;
							$o .= "<input type='hidden' name='undo_asg_position_id' id='undo_asg_position_id' value='".$position_obj->id."' >" ;
							$o .= "<input type='hidden' name='undo_asg_content_id' id='undo_asg_content_id' value='".$content_info->cnt_id."' >" ;
							$o .= "<input type='hidden' name='undo_asg_content_type' id='undo_asg_content_type' value='".$content_info->cnt_type."' >" ;
						$o .= "</div>" ;
						
						//Position Name, Group Name ID, Template Name, and Position Order Info
						//and Position Content type, Position Content Info
						$o .= "<div class='form-group'>" ;
							$o .= "<label class='control-label' for='temp_name'>".$info_string."</label>" ;
						$o .= "</div>" ;
						
						//Form submit button
						$o .= "<div class='block'>" ;
							$o .= "<button type='submit' name='undo_asg_content_submit' class='btn btn-danger block_left'>Unassign Content From Position</button>" ;
							$o .= "<span class='block_left'>&nbsp;</span>" ;
							$o .= "<a href='".$this->rootdir."admin/index/templates/' class='btn btn-warning block_left'>Cancel</a>" ;
						$o .= "</div>" ;
				$o .= "</form>" ;
				$o .= "</div>" ;
			}
		}
		
		return $o ;
	}
	
	private function showActionResultMessage($error = ""){
		$o = "" ;
		if($error != ""){
			$this->err = $error ;
		}
		$o .= "<div class='marg_all'>" ;
			if(isset($this->err)){
				$o .= $this->err ;
			}
		$o .= "</div>" ;
		return $o ;
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
	
	/* Get Content Type String	*/
	public function getContentTypeString($content_type_int){
		switch($content_type_int){
			case '1': return "Text" ;
			case '2': return "Widget" ;
			case '3': return "HTML" ;
			case '4': return "Article" ;
			case '5': return "Product" ;
			default : return "Unknown Content Type" ;
		}
	}
	
	/* WIDGETS SECTION */
	public function getAllWidgets($widget_dir = "widgets", $system = 'system' ){
		//Get widget folder paths
		$system_dir_path = $widget_dir."/".$system."/" ;
		
		//Scan for files and folders in widget paths
		$system_dir_contents = scandir($system_dir_path) ;
		
		//Initialize an array to store the final results into
		$all_widget_dir_names = array() ;
		
		//Get all system Widget Directory Names
		for($i = 0; $i < count($system_dir_contents); $i++){
			if(is_dir($system_dir_path."/".$system_dir_contents[$i])){
				if( ($system_dir_contents[$i] != "." ) && ($system_dir_contents[$i] != ".." ) ){
					//Widget Name Must not Include a comma (,)
					$exploded_widget_name = explode(",", $system_dir_contents[$i]) ;
					if($exploded_widget_name[0] == $system_dir_contents[$i]){
						array_push($all_widget_dir_names, $system_dir_contents[$i]) ;
					}
				}
			}
		}
				
		return $all_widget_dir_names ;
	}
	public function getWidgetAssignedPositionsInfo(){
		
	}
	public function getWidgetXMLInfo($widget_id, $type = "system" ){
		$file_dir = "widgets/".$type."/".$widget_id."/widgetDetails.xml" ;
		$xml = simplexml_load_file($file_dir);
		return $xml;
	}
	
	/* WIDGET SECTION VIEWS */
	public function showAllWidgets(){
		$o = "" ;
		$widget_names = $this->getAllWidgets() ;
		$count = count($widget_names) ;
		if($count > 0){
			$o .= "<div class='block marg_all'>" ;
			$o .= "<div class='spacer-large'></div>" ;
			$o .= "<table class='table table-bordered table-condensed'>" ;
				$o .= "<thead>" ;
					$o .= "<tr>" ;
						$o .= "<th>ID</th>" ;
						$o .= "<th>Name</th>" ;
						$o .= "<th>Type</th>" ;
						$o .= "<th>&nbsp;</th>" ;
						$o .= "<th>Options</th>" ;
					$o .= "</tr>" ;
				$o .= "</thead>" ;
				$o .= "<tbody>" ;
				$binary = 0 ;
			for($i = 0; $i < $count; $i++){
					$o .= "<tr class='bin_trow_".$binary."'>" ;
						$o .= "<td>&nbsp;&nbsp;".$widget_names[$i]."</td>" ;
						$o .= "<td>&nbsp;</td>" ;
						$o .= "<td>&nbsp;</td>" ;
						$o .= "<td>&nbsp;</td>" ;
						$o .= "<td>" ;
							$o .= "<span class='block_left'>&nbsp;</span>" ;
							$o .= "<a href='".$this->rootdir."admin/index/widgets/view_widget_info/".$widget_names[$i]."' class='btn btn-default block_left'>View Widget Information</a>" ;
						$o .= "</td>" ;
					$o .= "</tr>" ;
					
					if($binary == 0){ $binary = 1 ; 
					}else if($binary == 1){ $binary = 0 ; }
			}
				$o .= "</tbody>" ;
			$o .= "</table>" ;
			$o .= "</div>"; 
		}
		return $o ;
	}
	
	
	/* STORE MANAGEMENT. For Customers, Products and Orders 
		
		SEE Admin_store Class in the applications/models directory
	*/
	
	
	
	
	
	
}
?>