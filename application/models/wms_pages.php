<?php

class Wms_pages extends CI_Model{
	//Constructor
	function __construct(){
	// Call the Model constructor
        parent::__construct();
		
		$this->load->model('wms_pages_gui') ;
		
		$this->rootdir = $this->config->item('base_url') ;
	}
	
	private $page_id ;
	
	
	//GETTING HTML PAGES RELATED INFO
	public function getPageId($page_title){
		return $this->retrievePageIDInfoFromDB($page_title) ; //returns an Object OR boolean false
	}
	
	public function getAllPages($status = "(publish_status = 1)", $order = "", $limit = ""){
		return $this->retrieveAllPageIDsFromDB($status, $order, $limit) ; //returns an Array of Associative Arrays OR boolean false
	}
	
	public function getPageInfo($page_id){
		return $this->retrievePageInfoFromDB($page_id) ; //returns an Object OR boolean false
	}
		
	public function getAuthorUserInfo($author_user_id){
		return $this->user_sessions->getUserInfo($author_user_id) ; //returns an Object OR boolean false
	}
	
	public function getIDsOfAllPagesByAuthor($author_user_id, $publish_status = "", $order = "", $limit = ""){
		return $this->retrieveAllPagesByAuthor($author_user_id, $publish_status, $order, $limit) ; //returns an Array of Associative Arrays OR boolean false
	}
	
	public function getInfoAboutAllPagesByAuthor($author_user_id, $publish_status = "", $order = "", $limit = ""){
		$pages = $this->retrieveAllPagesByAuthor($author_user_id, $publish_status, $order, $limit) ; //returns an Array of Associative Arrays OR boolean false
		if($pages != false){
			$all_pages_info = array() ;
			for($i = 0; $i < count($pages) ; $i++){
				$this_article_info = $this->retrievePageInfoFromDB($pages[$i]['page_id']) ;
				if($this_article_info !== false){
					array_push($all_pages_info, $this_article_info) ;
				}
			}
			return $all_pages_info ;	//returns an Array of Objects OR boolean false
		}
		return false ;
	}
	
	public function getAllPageCategories($status = "(status = 1)", $order = "", $limit = ""){
		return $this->retrieveAllCategoryIDsFromDB($status, $order, $limit) ; //returns an Array of Associative Arrays OR boolean false
	}
	
	public function getAllPageCategoriesByCategoryType($category_type_id, $status = "(status = 1)", $order = "", $limit = ""){
		return $this->retrieveAllCategoryIDsFromDBByCategoryType($category_type_id, $status, $order, $limit) ; //returns an Array of Associative Arrays OR boolean false
	}
	public function getAllCategoryIDsForAPage($page_id, $status = "(page_to_page_category.status = 1)", $order = "", $limit = ""){
		$categories = $this->retrieveAllCategoryRelationsForPage($page_id, $status, $order, $limit) ;
		if($categories != false){
			$all_categories_ids = array() ;
			for($i = 0; $i < count($categories) ; $i++){
				array_push($all_categories_ids, $categories[$i]['category_id']) ;
			}
			return $all_categories_ids ;	//returns an Array of integers OR boolean false
		}
		return false ;
	}
	public function getAllCategoriesInfoForAPage($page_id, $status = "(page_to_page_category.status = 1)", $order = "", $limit = ""){
		$categories = $this->retrieveAllCategoryRelationsForPage($page_id, $status, $order, $limit) ;
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
	
	public function getCategoryId($category_name, $status = "(status = 1)"){
		return $this->retrieveCategoryIdByName($category_name, $status) ; //Returns an Integer OR boolean false
	}
	
	public function getAllPageInfoForCategory($category_id, $status = "(page_to_page_category.status = 1)", $order = "", $limit = ""){
		$pages = $this->retrieveAllPageRelationsForCategory($category_id, $status, $order, $limit) ; 
		if($pages != false){
			$all_pages_info = array() ;
			for($i = 0; $i < count($pages) ; $i++){
				$this_article_info = $this->retrievePageInfoFromDB($pages[$i]['page_id']) ;
				if($this_article_info !== false){
					array_push($all_pages_info, $this_article_info) ;
				}
			}
			return $all_pages_info ;	//returns an Array of Objects OR boolean false
		}
		return false ;
	}
	public function getCategoryTypes(){
		return $this->retrieveCategoryTypesFromDB() ;
	}
	public function getCategoryTypeInfo($category_type_id){
		return $this->retrieveCategoryTypeInfoFromDB($category_type_id) ;
	}
	public function getFirstImageIDFromImageString($cover_image_string){
		if($cover_image_string != ""){
			$arrs = explode("img_", $cover_image_string) ;
			if(count($arrs) > 1){
				$arrs = explode(".", $arrs[1]) ;
				$image_id = trim($arrs[0]) ;
				if( is_numeric($image_id) && ($image_id != 0) ){
					return $image_id ;
				}
			}
		}
		return 0;
	}
	
	
	//EDITING HTML PAGE RELATED INFO
	public function editPageInfo($page_id, $page_type_id, $title, $intro_text, $full_text, $cover_image_id = 0, $publish_status = 2){
		if($title != ""){
			return $this->updatePageInfo($page_id, $page_type_id, $title, $intro_text, $full_text, $cover_image_id, $publish_status) ;
		}
		return false ;
	}
	public function editFullPageInfo($page_id, $page_type_id, $title, $alias, $intro_text, $full_text, $publish_status, $time_created, $author_user_id, $author_user_alias, $time_modified, $editor_user_id, $time_edited, $auto_publish, $time_published, $time_unpublished, $cover_image_id, $urls, $attribs, $ordering, $metakeywords, $metadesc, $privacy, $page_views, $metadata, $featured, $language_id, $xreference){
		if($title != ""){
			return $this->updateFullPageInfo($page_id, $page_type_id, $title, $alias, $intro_text, $full_text, $publish_status, $time_created, $author_user_id, $author_user_alias, $time_modified, $editor_user_id, $time_edited, $auto_publish, $time_published, $time_unpublished, $cover_image_id, $urls, $attribs, $ordering, $metakeywords, $metadesc, $privacy, $page_views, $metadata, $featured, $language_id, $xreference) ;
		}
		return false ;
	}
	public function editPagePublishStatus($page_id, $publish_status){
	if($page_id != ""){
			return $this->updatePagePublishStatus($page_id, $publish_status) ;
		}
		return false ;
	}	
	
	public function editCategoryInfo($category_id, $category_type_id, $name, $description, $type, $modified_user_id, $status = 1){
		if($name != ""){
			return $this->updateCategoryInfo($category_id, $category_type_id, $name, $description, $type, $modified_user_id, $status) ;
		}
		return false ;
	}
	public function editFullCategoryInfo($category_id, $category_type_id, $parent_id, $name, $alias, $note, $description, $publish_status, $editor_user_id, $time_edited, $privacy, $metadesc, $metakeywords, $metadata, $author_user_id, $time_created, $modified_user_id, $hits, $language, $type, $status){
		if($category_name != ""){
			return $this->updateFullCategoryInfo($category_id, $category_type_id, $parent_id, $name, $alias, $note, $description, $publish_status, $editor_user_id, $time_edited, $privacy, $metadesc, $metakeywords, $metadata, $author_user_id, $time_created, $modified_user_id, $hits, $language, $type, $status) ;
		}
		return false ;
	}
	public function editCategoryPublishStatus($category_id, $publish_status){
		if($category_id != ""){
			return $this->updateCategoryPublishStatus($category_id, $publish_status) ;
		}
		return false ;
	}
	public function editPageCategoryRelationsInfo($page_id, $relations_to_remove, $new_category_id){
		if($page_id != ""){
			for($i = 0; $i < count($relations_to_remove) ; $i++){
				$the_category_id = $relations_to_remove[$i] ;
				$this->updateDeletePageToCategoryRelationship($page_id, $the_category_id) ;
			}
			if($new_category_id != ""){
				if($this->checkIfPageToCategoryRelationshipExists($page_id, $new_category_id) === false){			
					return $this->insertPageToCategoryRelationship($page_id, $new_category_id) ;
				}
			}
			return true ;
		}
		return false;
	}
	
	
	//ADDING HTML PAGES RELATED INFO
	public function incrementPageNumberOfTimesViewed($page_id){
		$res = $this->updateIncrementPageNumberOfTimesViewed($page_id) ;
		if($res == 1){ return true ; }
		return false ;
	}
	
	
	public function addPageToCategory($page_id, $category_id, $type = 1, $status = 1){
		//Ensure parameter values are not empty
		if( ($page_id != "") && ($category_id != "") ){
			//Check If this Page To Category Relationship already exists
			if($this->checkIfPageToCategoryRelationshipExists($page_id, $category_id) === false){
				//Insert New Page To Category Relationship
				$res = $this->insertPageToCategoryRelationship($page_id, $category_id) ;
				if($res === true){ return array(true) ; }
			}else{ return array(false, 2, "This Page is Already linked to this Page Category!") ; }
		}
		return array(false, 1, "The Page ID (".$page_id.") or the Page Category ID (".$category_id.") is missing!") ;
	}
	
	public function addPage($author_user_id, $page_title, $page_type_id, $intro_text, $full_text, $category_id = "", $cover_image_id = 0, $publish_status = 2){
		if($page_title != ""){
			//Check if title has been previously used
			if($this->checkIfPageTitleExists($page_title) === false){
				$page_id = $this->createPageID();
				$res = $this->insertIntoPageTable($author_user_id, $page_id, $page_title, $page_type_id, $intro_text, $full_text, $cover_image_id, $publish_status) ;
				if($res == 1){
					if($category_id != ""){
						//Add Page To Category Relationship
						$p_c_res = $this->addPageToCategory($page_id, $category_id) ;
					}
					return array(true, "The Page (".$page_title.") has been successfully added!") ;
				}else{ return array(false, 3, "An error occured while saving to database. The Page ('".$page_title."') may not have been added!") ; }
			}
			return array(false, 2, "This Page title (".$page_title.") has been previously used!") ;
		}
		return array(false, 1, '"Page Title" value is empty!') ;
	}
	
	public function addCategory($author_user_id, $category_name, $category_type_id, $description = "", $type = 1, $status = 1){
		if($category_name != ""){
			//Check if name has been previously used
			if($this->checkIfCategoryNameExistsForCategoryType($category_name, $category_type_id) === false){
				$category_id = $this->createCategoryID();
				$res = $this->insertIntoCategoryTable($author_user_id, $category_id, $category_type_id, $category_name, $description, $type, $status);
				if($res == 1){ return array(true, "The Page Category ($category_name) has been successfully added!") ; 
				}else{ return array(false, 3, "An error occured while saving to database. The Page Category ($category_name) may not have been added!") ; }
			}
			return array(false, 2, "This Page Category name ($category_name) has been previously used!") ;
		}
		return array(false, 1, '$category_name value is empty!') ;
	}
	
	
	//INSERTING INTO THE DATABASE
	private function insertPageToCategoryRelationship($page_id, $category_id, $type = 1, $status = 1){
		//page_id, category_id, type, status, time
		$query = "INSERT INTO page_to_page_category(page_id, category_id, type, status, time) VALUES('$page_id', '$category_id', '$type', '$status', CURRENT_TIMESTAMP) " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	private function insertIntoPageTable($author_user_id, $page_id, $page_title, $page_type_id, $intro_text, $full_text, $cover_image_id = 0,  $publish_status = 2){
		//author_user_id, page_id, title, full_text, status, time_created
		$query = "INSERT INTO pages(author_user_id, page_id, page_type_id, title, intro_text, full_text, cover_image_id, publish_status, time_created) 
					VALUES('$author_user_id', '$page_id', '$page_type_id', '$page_title', '$intro_text', '$full_text', '$cover_image_id', '$publish_status', CURRENT_TIMESTAMP) " ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	
	private function insertIntoCategoryTable($author_user_id, $category_id, $category_type_id, $category_name, $description = "", $type = 1, $status = 1 ){
		//author_user_id, category_id, category_type_id, name, description, type, status, time_created
		$query = "INSERT INTO page_categories(author_user_id, category_id, category_type_id, name, description, type, status, time_created) 
					VALUES('$author_user_id', '$category_id', '$category_type_id', '$category_name', '$description', '$type', '$status', CURRENT_TIMESTAMP) " ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	
	
	// SELECTING FROM DATABASE
	private function retrievePageIDInfoFromDB($page_title){
		$query = "SELECT page_id FROM pages WHERE title = '".$page_title."'" ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			return $result_obj->page_id ;
		}
		return false ;
	}
	private function retrieveCategoryIdByName($category_name, $status = "(status = 1)"){
		$query = "SELECT category_id FROM page_categories WHERE name = '".$category_name."' AND $status" ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row() ;
			return $row->category_id ;
		}
		return false ;
	}
	private function retrieveThisCategoryInfo($category_id, $status = "(status = 1)"){
		$query = "SELECT * FROM page_categories WHERE category_id = '$category_id' AND $status" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			
			//category_id, category_type_id, parent_id, name, alias, note, description, publish_status, editor_user_id, time_edited, privacy, metadesc, metakeywords, metadata, author_user_id, time_created, modified_user_id, modified_time, hits, language, type, status
			$categ_obj->category_id 		= $result_obj->category_id ;
			$categ_obj->category_type_id 	= $result_obj->category_type_id ;
			$categ_obj->parent_id 			= $result_obj->parent_id ;
			$categ_obj->name 				= $result_obj->name ;
			$categ_obj->alias 				= $result_obj->alias ;
			$categ_obj->note 				= $result_obj->note ;
			$categ_obj->description 		= getStringTextForm($result_obj->description) ;
			$categ_obj->publish_status 		= $result_obj->publish_status ;
			$categ_obj->editor_user_id 		= $result_obj->editor_user_id ;
			$categ_obj->time_edited 		= $result_obj->time_edited ;
			$categ_obj->privacy 			= $result_obj->privacy ;
			$categ_obj->metadesc 			= $result_obj->metadesc ;
			$categ_obj->metakeywords 		= $result_obj->metakeywords ;
			$categ_obj->metadata 			= $result_obj->metadata ;
			$categ_obj->author_user_id 		= $result_obj->author_user_id ;
			$categ_obj->time_created 		= $result_obj->time_created ;
			$categ_obj->modified_user_id 	= $result_obj->modified_user_id ;
			$categ_obj->modified_time 		= $result_obj->modified_time ;
			$categ_obj->hits 				= $result_obj->hits ;
			$categ_obj->language 			= $result_obj->language ;
			
			$categ_obj->type 				= $result_obj->type ;
			$categ_obj->status 				= $result_obj->status ;
			
			return $categ_obj ;
		}
		return false ;
	}
	private function retrieveAllPageRelationsForCategory($category_id, $status = "(page_to_page_category.status = 1)", $order = "", $limit = ""){
		$query = "SELECT page_to_page_category.page_id FROM page_to_page_category 
					JOIN pages ON page_to_page_category.page_id = pages.page_id
					WHERE page_to_page_category.category_id = '$category_id' AND $status $order $limit
					" ;
					//WHERE page_to_page_category.category_id = '$category_id' AND $status $order $limit
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	
	private function retrieveAllCategoryRelationsForPage($page_id, $status = "(page_to_page_category.status = 1)", $order = "", $limit = ""){
		$query = "SELECT page_to_page_category.category_id FROM page_to_page_category 
					JOIN  page_categories ON page_to_page_category.category_id = page_categories.category_id
					WHERE page_to_page_category.page_id = '$page_id' AND $status $order $limit
					" ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	
	private function retrieveAllCategoryIDsFromDB($status = "(status = 1)", $order = "", $limit = ""){
		$query = "SELECT category_id FROM page_categories WHERE ".$status." ".$order." ".$limit ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	private function retrieveAllCategoryIDsFromDBByCategoryType($category_type_id, $status = "(status = 1)", $order = "", $limit = ""){
		$query = "SELECT category_id FROM page_categories WHERE category_type_id = '$category_type_id' AND ".$status." ".$order." ".$limit ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	
	private function retrievePageInfoFromDB($page_id){
		$query = "SELECT * FROM pages WHERE page_id = '$page_id' ";
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			
			//page_id, page_type_id, title, alias, intro_text, full_text, publish_status, time_created, author_user_id, author_user_alias, time_modified, editor_user_id, time_edited, auto_publish, time_published, time_unpublished, cover_image_id, urls, attribs, ordering, metakeywords, metadesc, privacy, page_views, metadata, featured, language_id, xreference 
			$result_obj = $query->row() ;
			@$page_obj->page_id 			= $result_obj->page_id ;
			$page_obj->page_type_id 		= $result_obj->page_type_id ;
			$page_obj->title 				= $result_obj->title ;
			$page_obj->alias 				= $result_obj->alias ;
			$page_obj->intro_text 			= $result_obj->intro_text ;
			$page_obj->full_text 			= $result_obj->full_text ;
			$page_obj->publish_status 		= $result_obj->publish_status ;
			$page_obj->time_created 		= $result_obj->time_created ;
			$page_obj->author_user_id 		= $result_obj->author_user_id ;
			
			$page_obj->author_user_alias 	= $result_obj->author_user_alias ;
			$page_obj->time_modified 		= $result_obj->time_modified ;
			$page_obj->editor_user_id 		= $result_obj->editor_user_id ;
			$page_obj->time_edited 			= $result_obj->time_edited ;
			
			$page_obj->auto_publish 		= $result_obj->auto_publish ;
			$page_obj->time_published 		= $result_obj->time_published ;
			$page_obj->time_unpublished 	= $result_obj->time_unpublished ;
			
			$page_obj->cover_image_id 		= $result_obj->cover_image_id ;
			$page_obj->urls 				= $result_obj->urls ;
			
			$page_obj->attribs 				= $result_obj->attribs ;
			$page_obj->ordering 			= $result_obj->ordering ;
			$page_obj->metakeywords 		= $result_obj->metakeywords ;
			$page_obj->metadesc 			= $result_obj->metadesc ;
			$page_obj->privacy 				= $result_obj->privacy ;
			$page_obj->page_views 			= $result_obj->page_views ;
			$page_obj->metadata 			= $result_obj->metadata ;
			$page_obj->featured 			= $result_obj->featured ;
			$page_obj->language_id 			= $result_obj->language_id ;
			$page_obj->xreference 			= $result_obj->xreference ;
			
			return $page_obj ;
		}
		return false ;
	}
	
	
	private function retrieveAllPagesByAuthor($author_user_id, $publish_status = "", $order = "", $limit = ""){
		$query = "SELECT page_id FROM pages WHERE author_user_id = '".$author_user_id."' ".$publish_status." ".$order." ".$limit ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	
	private function retrieveAllPageIDsFromDB($status = "(publish_status = 1)", $order = "", $limit = ""){
		$query = "SELECT page_id FROM pages WHERE ".$status." ".$order." ".$limit ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	private function retrieveCategoryTypesFromDB($status = "(status = 1)"){
		$query = "SELECT * FROM page_category_types WHERE ".$status ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	private function retrieveCategoryTypeInfoFromDB($category_type_id, $status = "(status = 1)"){
		$query = "SELECT * FROM page_category_types WHERE category_type_id = '$category_type_id' AND ".$status ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			//category_type_id, name, status
			$result_obj = $query->row() ;
			$cat_type_obj->category_type_id 	= $result_obj->category_type_id ;
			$cat_type_obj->name 				= $result_obj->name ;
			$cat_type_obj->status 				= $result_obj->status ;
			
			return $cat_type_obj ;
		}
		return false ;
	}
	
	//CHECKING INFO IN THE DATABASE
	public function checkIfPageTitleExists($page_title){
		return checkAFieldValue($page_title, 'pages', 'title') ; 
		//Returns boolean true if value exists AND false if value does not exist
	}
	public function checkIfPageTitleExistsExclude($page_title, $page_id){
		return checkAFieldValueExclude($page_title, 'pages', 'title', 'page_id', $page_id) ; 
		//Returns boolean true if value exists AND false if value does not exist
	}
		
	public function checkIfCategoryNameExists($category_name){
		return checkAFieldValue($category_name, 'page_categories', 'name') ;
		//Returns boolean true if value exists AND false if value does not exist
	}
	public function checkIfCategoryNameExistsForCategoryType($category_name, $category_type_id){
		return checkIfTwoFieldValuesMatch('page_categories', 'name', 'category_type_id', $category_name, $category_type_id) ;
		//Returns boolean true if values exist AND false if value does not exist
	}
	public function checkIfCategoryNameExistsForCategoryTypeExclude($category_name, $category_type_id, $category_id){
		return checkIfTwoFieldValuesMatchExclude('page_categories', 'name', 'category_type_id', $category_name, $category_type_id, 'category_id', $category_id) ;
		//Returns boolean true if values exist AND false if value does not exist
	}
	public function checkIfPageToCategoryRelationshipExists($page_id, $category_id, $extra = "(status = 1)"){
		return checkIfTwoFieldValuesMatchExtra('page_to_page_category', 'page_id', 'category_id', $page_id, $category_id, $extra) ;
		//Returns boolean true if values exist AND false if value does not exist
	}
	
	public function checkIfAuthorCreatedPage($author_user_id, $page_id){
		return checkIfTwoFieldValuesMatch('pages', 'author_user_id', 'page_id', $author_user_id, $page_id) ;
		//Returns boolean true if values exist AND false if value does not exist
	}
	
	
	//CREATING Integer IDs For a DATABASE TABLE FIELD
	public function createPageID(){
		return createAnId('pages', 'page_id') ; 
		//Returns boolean true if value exists AND false if value does not exist
	}
	
	public function createCategoryID(){
		return createAnId('page_categories', 'category_id') ; 
		//Returns boolean true if value exists AND false if value does not exist
	}	
	
	
	//UPDATING INFO IN THE DATABASE
	//category_id, category_type_id, parent_id, name, alias, note, description, publish_status, editor_user_id, time_edited, privacy, metadesc, metakeywords, metadata, author_user_id, time_created, modified_user_id, modified_time, hits, language, type, status
	private function updateCategoryInfo($category_id, $category_type_id, $name, $description, $type, $modified_user_id, $status){
		$query = "UPDATE page_categories SET category_type_id = '$category_type_id', name = '$name', description = '$description', 
				modified_user_id = '$modified_user_id', modified_time = CURRENT_TIMESTAMP, type = '$type', status = '$status'
				WHERE category_id = '$category_id'" ;
		$query = $this->db->query($query) ;
		return true ;
	}
	private function updateFullCategoryInfo($category_id, $category_type_id, $parent_id, $name, $alias, $note, $description, $publish_status, $editor_user_id, $time_edited, $privacy, $metadesc, $metakeywords, $metadata, $author_user_id, $time_created, $modified_user_id, $hits, $language, $type, $status){
		$query = "UPDATE page_categories SET category_type_id = '$category_type_id', parent_id = '$parent_id', name = '$name', alias = '$alias', note = '$note', 
				description = '$description', publish_status = '$publish_status', editor_user_id = '$editor_user_id', time_edited = '$time_edited', privacy = '$privacy',
				metadesc = '$metadesc', metakeywords = '$metakeywords', metadata = '$metadata', author_user_id = '$author_user_id', time_created = '$time_created', 
				modified_user_id = '$modified_user_id', modified_time = CURRENT_TIMESTAMP, hits = '$hits', language = '$language', type = '$type', status = '$status'
				WHERE category_id = '$category_id'" ;
		$query = $this->db->query($query) ;
		return true ;
	}
	private function updateIncrementPageNumberOfTimesViewed($page_id){
		$query = "UPDATE pages SET page_views =  (page_views + 1) WHERE page_id = '$page_id'" ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	//page_id, page_type_id, title, alias, intro_text, full_text, publish_status, time_created, author_user_id, author_user_alias, time_modified, editor_user_id, time_edited, auto_publish, time_published, time_unpublished, cover_image_id, urls, attribs, ordering, metakeywords, metadesc, privacy, page_views, metadata, featured, language_id, xreference 
	private function updatePageInfo($page_id, $page_type_id, $title, $intro_text, $full_text, $cover_image_id, $publish_status){
		$query = "UPDATE pages SET page_type_id = '$page_type_id', title = '$title', intro_text = '$intro_text', full_text = '$full_text', 
				cover_image_id = '$cover_image_id', publish_status = '$publish_status'
				WHERE page_id = '$page_id' " ;
		$query = $this->db->query($query) ;
		return true ;
	}	
	
	
	private function updateFullPageInfo($page_id, $page_type_id, $title, $alias, $intro_text, $full_text, $publish_status, $time_created, $author_user_id, $author_user_alias, $time_modified, $editor_user_id, $time_edited, $auto_publish, $time_published, $time_unpublished, $cover_image_id, $urls, $attribs, $ordering, $metakeywords, $metadesc, $privacy, $page_views, $metadata, $featured, $language_id, $xreference){
		$query = "UPDATE pages SET page_type_id = '$page_type_id', title = '$title', alias = '$alias', intro_text = '$intro_text', full_text = '$full_text',
				publish_status = '$publish_status', time_created = '$time_created', author_user_id = '$author_user_id', author_user_alias = '$author_user_alias', time_modified = '$time_modified',
				editor_user_id = '$editor_user_id', time_edited = '$time_edited', auto_publish = '$auto_publish', time_published = '$time_published', time_unpublished = '$time_unpublished',
				cover_image_id = '$cover_image_id', urls = '$urls', attribs = '$attribs', ordering = '$ordering', metakeywords = '$metakeywords', metadesc = '$metadesc', privacy = '$privacy',
				page_views = '$page_views', metadata = '$metadata', featured = '$featured', language_id = '$language_id', xreference = '$xreference'
				WHERE page_id = '$page_id' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	private function updateCategoryPublishStatus($category_id, $publish_status){
		$query = "UPDATE page_categories SET publish_status = '$publish_status' WHERE category_id = '$category_id' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	private function updatePagePublishStatus($page_id, $publish_status){
		$query = "UPDATE pages SET publish_status = '$publish_status' WHERE page_id = '$page_id' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	private function updateDeletePageToCategoryRelationship($page_id, $category_id){
		$status = 9 ;
		$query = "UPDATE page_to_page_category SET status = '".$status."' WHERE page_id = '$page_id' AND category_id = '$category_id' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	
	
	public function deletePage($user_id, $page_id){
		$user_privilege = $this->user_sessions->getUserPrivilege($user_id) ;
		$article_info 	= $this->getPageInfo($page_id) ;
		if($article_info !== false){
			$article_author_user_id = $article_info->author_user_id ;
			if( ($user_privilege == 10) || ($user_id == $article_author_user_id) ){
				//Delete Page
				$publish_status_deleted = 9 ;
				$query = "UPDATE pages SET publish_status = '".$publish_status_deleted."' WHERE page_id = '$page_id' " ;
				$query = $this->db->query($query) ;
				return true ;
			}
		}
		return false ;
	}
	
	
	public function deleteCategory($user_id, $category_id){
		$user_privilege = $this->user_sessions->getUserPrivilege($user_id) ;
		if( $user_privilege == 10 ){
			//Delete Category
			$status_deleted = 9 ;
			$publish_status_deleted = 9 ;
			$query = "UPDATE page_categories SET publish_status = '".$publish_status_deleted."', status = '".$status_deleted."' WHERE category_id = '$category_id' " ;
			$query = $this->db->query($query) ;
			
				$query2 = "UPDATE page_to_page_category SET status = '".$status_deleted."' WHERE category_id = '$category_id' " ;
				$query2 = $this->db->query($query2) ;
			
				return true ;
		}
		return false ;
	}
	
	
	
}
			
?>