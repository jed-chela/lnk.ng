<?php

class Wms_news extends CI_Model{
	//Constructor
	function __construct(){
	// Call the Model constructor
        parent::__construct();
		$this->rootdir = $this->config->item('base_url') ;
	}
	
	private $article_id ;
	
	//
	
	//GETTING NEWS RELATED INFO
	public function getAllArticles($status = "(status = 1)", $order = "", $limit = ""){
		return $this->retrieveAllArticleIDsFromDB($status, $order, $limit) ; //returns an Array of Associative Arrays OR boolean false
	}
	
	public function getArticleInfo($article_id){
		return $this->retrieveArticleInfoFromDB($article_id) ; //returns an Object OR boolean false
	}
		
	public function getAuthorUserInfo($author_user_id){
		return $this->user_sessions->getUserInfo($author_user_id) ; //returns an Object OR boolean false
	}
	
	public function getIDsOfAllArticlesByAuthor($author_user_id, $publish_status = "", $order = "", $limit = ""){
		return $this->retrieveAllArticlesByAuthor($author_user_id, $publish_status, $order, $limit) ; //returns an Array of Associative Arrays OR boolean false
	}
	
	public function getInfoAboutAllArticlesByAuthor($author_user_id, $publish_status = "", $order = "", $limit = ""){
		$articles = $this->retrieveAllArticlesByAuthor($author_user_id, $publish_status, $order, $limit) ; //returns an Array of Associative Arrays OR boolean false
		if($articles != false){
			$all_articles_info = array() ;
			for($i = 0; $i < count($articles) ; $i++){
				$this_article_info = $this->retrieveArticleInfoFromDB($articles[$i]['article_id']) ;
				if($this_article_info !== false){
					array_push($all_articles_info, $this_article_info) ;
				}
			}
			return $all_articles_info ;	//returns an Array of Objects OR boolean false
		}
		return false ;
	}
	
	public function getAllArticleCategories($status = "(status = 1)", $order = "", $limit = ""){
		return $this->retrieveAllCategoryIDsFromDB($status, $order, $limit) ; //returns an Array of Associative Arrays OR boolean false
	}
	
	public function getAllArticleCategoriesByCategoryType($category_type_id, $status = "(status = 1)", $order = "", $limit = ""){
		return $this->retrieveAllCategoryIDsFromDBByCategoryType($category_type_id, $status, $order, $limit) ; //returns an Array of Associative Arrays OR boolean false
	}
	
	public function getAllCategoriesInfoForAnArticle($article_id, $status = "(article_categories.status = 1)", $order = "", $limit = ""){
		$categories = $this->retrieveAllCategoryRelationsForArticle($article_id, $status, $order, $limit) ;
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
	
	public function getAllArticleInfoForCategory($category_id, $status = "(article_to_category.status = 1)", $order = "", $limit = ""){
		$articles = $this->retrieveAllArticleRelationsForCategory($category_id, $status, $order, $limit) ; 
		if($articles != false){
			$all_articles_info = array() ;
			for($i = 0; $i < count($articles) ; $i++){
				$this_article_info = $this->retrieveArticleInfoFromDB($articles[$i]['article_id']) ;
				if($this_article_info !== false){
					array_push($all_articles_info, $this_article_info) ;
				}
			}
			return $all_articles_info ;	//returns an Array of Objects OR boolean false
		}
		return false ;
	}
	public function getCategoryTypes(){
		return $this->retrieveCategoryTypesFromDB() ;
	}
	public function getCategoryTypeInfo($category_type_id){
		return $this->retrieveCategoryTypeInfoFromDB($category_type_id) ;
	}
	public function getCoverImageIDFromImageString($cover_image_string){
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
	
	//EDITING STORE RELATED INFO
	public function editArticleInfo($article_id, $article_type_id, $title, $full_text, $cover_image_id){
		if($title != ""){
			return $this->updateArticleInfo($article_id, $article_type_id, $title, $full_text, $cover_image_id) ;
		}
		return false ;
	}
	public function editFullArticleInfo($article_id, $article_type_id, $title, $alias, $introtext, $full_text, $publish_status, $time_created, $author_user_id, $author_user_alias, $time_modified, $editor_user_id, $time_edited, $auto_publish, $time_published, $time_unpublished, $cover_image_id, $urls, $attribs, $ordering, $metakeywords, $metadesc, $privacy, $article_views, $metadata, $featured, $language_id, $xreference){
		if($title != ""){
			return $this->updateFullArticleInfo($article_id, $article_type_id, $title, $alias, $introtext, $full_text, $publish_status, $time_created, $author_user_id, $author_user_alias, $time_modified, $editor_user_id, $time_edited, $auto_publish, $time_published, $time_unpublished, $cover_image_id, $urls, $attribs, $ordering, $metakeywords, $metadesc, $privacy, $article_views, $metadata, $featured, $language_id, $xreference) ;
		}
		return false ;
	}
	public function editArticlePublishStatus($article_id, $publish_status){
	if($article_id != ""){
			return $this->updateArticlePublishStatus($article_id, $publish_status) ;
		}
		return false ;
	}
	
	public function editCategoryInfo($category_id, $category_type_id, $name, $description, $modified_user_id, $type = 1, $status = 1){
		if($name != ""){
			return $this->updateCategoryInfo($category_id, $category_type_id, $name, $description, $modified_user_id, $type, $status) ;
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
	
	public function editUserAccountInfo(){
		return false ;
	}

	
	
	//ADDING STORE RELATED INFO
	public function incrementArticleNumberOfTimesViewed($article_id){
		$res = $this->updateIncrementArticleNumberOfTimesViewed($article_id) ;
		if($res == 1){ return true ; }
		return false ;
	}
	
	public function addArticleToCategory($article_id, $category_id, $type = 1, $status = 1){
		//Ensure parameter values are not empty
		if( ($article_id != "") && ($category_id != "") ){
			//Check If this Article To Category Relationship already exists
			if($this->checkIfArticleToCategoryRelationshipExists($article_id, $category_id) === false){
				//Insert New Article To Category Relationship
				$res = $this->insertArticleToCategoryRelationship($article_id, $category_id, $type = 1, $status = 1) ;
				if($res == 1){ return array(true) ; }
			}else{ return array(false, 2, "This article is Already linked to this Category!") ; }
		}
		return array(false, 1, "Article ID (".$article_id.") or the Category ID (".$category_id.") is missing!") ;
	}
	
	public function addArticle($author_user_id, $article_title, $article_type_id, $full_text, $category_id = "", $cover_image_id = 0, $publish_status = 2){
		if($article_title != ""){
			//Check if name has been previously used
			if($this->checkIfArticleTitleExists($article_title) === false){
				$article_id = $this->createArticleID();
				$res = $this->insertIntoArticleTable($author_user_id, $article_id, $article_title, $article_type_id, $full_text, $cover_image_id, $publish_status) ;
				if($res == 1){
					if($category_id != ""){
						//Add Article To Category Relationship
						$p_c_res = $this->addArticleToCategory($article_id, $category_id) ;
					}
					return array(true, "The Article (".$article_title.") has been successfully added!") ;
				}else{ return array(false, 3, "An error occured while saving to database. The Article ($article_title) may not have been added!") ; }
			}
			return array(false, 2, "This Article title (".$article_title.") has been previously used!") ;
		}
		return array(false, 1, '"Article Title" value is empty!') ;
	}
	
	public function addCategory($author_user_id, $category_name, $category_type_id, $description = "", $type = 1, $status = 1){
		if($category_name != ""){
			//Check if name has been previously used
			if($this->checkIfCategoryNameExistsForCategoryType($category_name, $category_type_id) === false){
				$category_id = $this->createCategoryID();
				$res = $this->insertIntoCategoryTable($author_user_id, $category_id, $category_type_id, $category_name, $description, $type, $status);
				if($res == 1){ return array(true, "The Category ($category_name) has been successfully added!") ; 
				}else{ return array(false, 3, "An error occured while saving to database. The Category ($category_name) may not have been added!") ; }
			}
			return array(false, 2, "This Category name ($category_name) has been previously used!") ;
		}
		return array(false, 1, '$category_name value is empty!') ;
	}
	
	
	//INSERTING INTO THE DATABASE
	private function insertArticleToCategoryRelationship($article_id, $category_id, $type = 1, $status = 1){
		//article_id, category_id, type, status, time
		$query = "INSERT INTO article_to_category(article_id, category_id, type, status, time) VALUES('$article_id', '$category_id', '$type', '$status', CURRENT_TIMESTAMP) " ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	private function insertIntoArticleTable($author_user_id, $article_id, $article_title, $article_type_id, $full_text = "", $cover_image_id = 0,  $publish_status = 2){
		//author_user_id, article_id, title, full_text, status, time_created
		$query = "INSERT INTO articles(author_user_id, article_id, article_type_id, title, full_text, cover_image_id, publish_status, time_created) 
					VALUES('$author_user_id', '$article_id', '$article_type_id', '$article_title', '$full_text', '$cover_image_id', '$publish_status', CURRENT_TIMESTAMP) " ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	private function insertIntoCategoryTable($author_user_id, $category_id, $category_type_id, $category_name, $description = "", $type = 1, $status = 1 ){
		//author_user_id, category_id, category_type_id, name, description, type, status, time_created
		$query = "INSERT INTO article_categories(author_user_id, category_id, category_type_id, name, description, type, status, time_created) 
					VALUES('$author_user_id', '$category_id', '$category_type_id', '$category_name', '$description', '$type', '$status', CURRENT_TIMESTAMP) " ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	
	
	// SELECTING FROM DATABASE
	private function retrieveCategoryIdByName($category_name, $status = "(status = 1)"){
		$query = "SELECT category_id FROM article_categories WHERE name = '$category_name' AND $status" ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row() ;
			return $row->category_id ;
		}
		return false ;
	}
	private function retrieveThisCategoryInfo($category_id, $status = "(status = 1)"){
		$query = "SELECT * FROM article_categories WHERE category_id = '$category_id' AND $status" ;
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
	private function retrieveAllArticleRelationsForCategory($category_id, $status = "(article_to_category.status = 1)", $order = "", $limit = ""){
		$query = "SELECT article_to_category.article_id FROM article_to_category 
					JOIN articles ON article_to_category.article_id = articles.article_id
					WHERE article_to_category.category_id = '$category_id' AND $status $order $limit
					" ;
					//WHERE article_to_category.category_id = '$category_id' AND $status $order $limit
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	
	private function retrieveAllCategoryRelationsForArticle($article_id, $status = "(article_categories.status = 1)", $order = "", $limit = ""){
		$query = "SELECT article_to_category.category_id FROM article_to_category 
					JOIN  article_categories ON article_to_category.category_id = article_categories.category_id
					WHERE article_to_category.article_id = '$article_id' AND $status $order $limit
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
		$query = "SELECT category_id FROM article_categories WHERE ".$status." ".$order." ".$limit ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	private function retrieveAllCategoryIDsFromDBByCategoryType($category_type_id, $status = "(status = 1)", $order = "", $limit = ""){
		$query = "SELECT category_id FROM article_categories WHERE category_type_id = '$category_type_id' AND ".$status." ".$order." ".$limit ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	
	private function retrieveArticleInfoFromDB($article_id){
		$query = "SELECT * FROM articles WHERE article_id = '$article_id' ";
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			
			//article_id, article_type_id, title, alias, introtext, full_text, publish_status, time_created, author_user_id, author_user_alias, time_modified, editor_user_id, time_edited, auto_publish, time_published, time_unpublished, cover_image_id, urls, attribs, ordering, metakeywords, metadesc, privacy, article_views, metadata, featured, language_id, xreference 
			$result_obj = $query->row() ;
			$art_obj->article_id 			= $result_obj->article_id ;
			$art_obj->article_type_id 		= $result_obj->article_type_id ;
			$art_obj->title 				= $result_obj->title ;
			$art_obj->alias 				= $result_obj->alias ;
			$art_obj->introtext 			= $result_obj->introtext ;
			$art_obj->full_text 				= $result_obj->full_text ;
			$art_obj->publish_status 		= $result_obj->publish_status ;
			$art_obj->time_created 			= $result_obj->time_created ;
			$art_obj->author_user_id 		= $result_obj->author_user_id ;
			
			$art_obj->author_user_alias 	= $result_obj->author_user_alias ;
			$art_obj->time_modified 		= $result_obj->time_modified ;
			$art_obj->editor_user_id 		= $result_obj->editor_user_id ;
			$art_obj->time_edited 			= $result_obj->time_edited ;
			
			$art_obj->auto_publish 			= $result_obj->auto_publish ;
			$art_obj->time_published 		= $result_obj->time_published ;
			$art_obj->time_unpublished 		= $result_obj->time_unpublished ;
			
			$art_obj->cover_image_id 		= $result_obj->cover_image_id ;
			$art_obj->urls 					= $result_obj->urls ;
			
			$art_obj->attribs 				= $result_obj->attribs ;
			$art_obj->ordering 				= $result_obj->ordering ;
			$art_obj->metakeywords 			= $result_obj->metakeywords ;
			$art_obj->metadesc 				= $result_obj->metadesc ;
			$art_obj->privacy 				= $result_obj->privacy ;
			$art_obj->article_views 		= $result_obj->article_views ;
			$art_obj->metadata 				= $result_obj->metadata ;
			$art_obj->featured 				= $result_obj->featured ;
			$art_obj->language_id 			= $result_obj->language_id ;
			$art_obj->xreference 			= $result_obj->xreference ;
			
			return $art_obj ;
		}
		return false ;
	}
	
	private function retrieveAllArticlesByAuthor($author_user_id, $publish_status = "", $order = "", $limit = ""){
		$query = "SELECT article_id FROM articles WHERE author_user_id = '".$author_user_id."' ".$publish_status." ".$order." ".$limit ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	
	private function retrieveAllArticleIDsFromDB($status = "(status = 1)", $order = "", $limit = ""){
		$query = "SELECT article_id FROM articles WHERE ".$status." ".$order." ".$limit ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	private function retrieveCategoryTypesFromDB($status = "(status = 1)"){
		$query = "SELECT * FROM article_category_types WHERE ".$status ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
	private function retrieveCategoryTypeInfoFromDB($category_type_id, $status = "(status = 1)"){
		$query = "SELECT * FROM article_category_types WHERE category_type_id = '$category_type_id' AND ".$status ;
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
	public function checkIfArticleTitleExists($article_title){
		return checkAFieldValue($article_title, 'articles', 'title') ; 
		//Returns boolean true if value exists AND false if value does not exist
	}
	public function checkIfArticleTitleExistsExclude($article_title, $article_id){
		return checkAFieldValueExclude($article_title, 'articles', 'title', 'article_id', $article_id) ; 
		//Returns boolean true if value exists AND false if value does not exist
	}
	public function checkIfCategoryNameExists($category_name){
		return checkAFieldValue($category_name, 'article_categories', 'name') ;
		//Returns boolean true if value exists AND false if value does not exist
	}
	public function checkIfCategoryNameExistsForCategoryType($category_name, $category_type_id){
		return checkIfTwoFieldValuesMatch('article_categories', 'name', 'category_type_id', $category_name, $category_type_id) ;
		//Returns boolean true if values exist AND false if value does not exist
	}
	public function checkIfCategoryNameExistsForCategoryTypeExclude($category_name, $category_type_id, $category_id){
		return checkIfTwoFieldValuesMatchExclude('article_categories', 'name', 'category_type_id', $category_name, $category_type_id, 'category_id', $category_id) ;
		//Returns boolean true if values exist AND false if value does not exist
	}
	public function checkIfArticleToCategoryRelationshipExists($article_id, $category_id){
		return checkIfTwoFieldValuesMatch('article_to_category', 'article_id', 'category_id', $article_id, $category_id) ;
		//Returns boolean true if values exist AND false if value does not exist
	}
	public function checkIfAuthorCreatedArticle($author_user_id, $article_id){
		return checkIfTwoFieldValuesMatch('articles', 'author_user_id', 'article_id', $author_user_id, $article_id) ;
		//Returns boolean true if values exist AND false if value does not exist
	}
	
	
	//CREATING Integer IDs For a DATABASE TABLE FIELD
	public function createArticleID(){
		return createAnId('articles', 'article_id') ; 
		//Returns boolean true if value exists AND false if value does not exist
	}
	public function createCategoryID(){
		return createAnId('article_categories', 'category_id') ; 
		//Returns boolean true if value exists AND false if value does not exist
	}	
	
	
	//UPDATING INFO IN THE DATABASE
	//category_id, category_type_id, parent_id, name, alias, note, description, publish_status, editor_user_id, time_edited, privacy, metadesc, metakeywords, metadata, author_user_id, time_created, modified_user_id, modified_time, hits, language, type, status
	private function updateCategoryInfo($category_id, $category_type_id, $name, $description, $modified_user_id, $type, $status){
		$query = "UPDATE article_categories SET category_type_id = '$category_type_id', name = '$name', description = '$description', 
				modified_user_id = '$modified_user_id', modified_time = CURRENT_TIMESTAMP, type = '$type', status = '$status'
				WHERE category_id = '$category_id'" ;
		$query = $this->db->query($query) ;
		return true ;
	}
	private function updateFullCategoryInfo($category_id, $category_type_id, $parent_id, $name, $alias, $note, $description, $publish_status, $editor_user_id, $time_edited, $privacy, $metadesc, $metakeywords, $metadata, $author_user_id, $time_created, $modified_user_id, $hits, $language, $type, $status){
		$query = "UPDATE article_categories SET category_type_id = '$category_type_id', parent_id = '$parent_id', name = '$name', alias = '$alias', note = '$note', 
				description = '$description', publish_status = '$publish_status', editor_user_id = '$editor_user_id', time_edited = '$time_edited', privacy = '$privacy',
				metadesc = '$metadesc', metakeywords = '$metakeywords', metadata = '$metadata', author_user_id = '$author_user_id', time_created = '$time_created', 
				modified_user_id = '$modified_user_id', modified_time = CURRENT_TIMESTAMP, hits = '$hits', language = '$language', type = '$type', status = '$status'
				WHERE category_id = '$category_id'" ;
		$query = $this->db->query($query) ;
		return true ;
	}
	private function updateIncrementArticleNumberOfTimesViewed($article_id){
		$query = "UPDATE articles SET article_views =  (article_views + 1) WHERE article_id = '$article_id'" ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	//article_id, article_type_id, title, alias, introtext, full_text, publish_status, time_created, author_user_id, author_user_alias, time_modified, editor_user_id, time_edited, auto_publish, time_published, time_unpublished, cover_image_id, urls, attribs, ordering, metakeywords, metadesc, privacy, article_views, metadata, featured, language_id, xreference 
	private function updateArticleInfo($article_id, $article_type_id, $title, $full_text, $cover_image_id){
		$query = "UPDATE articles SET article_type_id = '$article_type_id', title = '$title', full_text = '$full_text', cover_image_id = '$cover_image_id'
				WHERE article_id = '$article_id' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	private function updateFullArticleInfo($article_id, $article_type_id, $title, $alias, $introtext, $full_text, $publish_status, $time_created, $author_user_id, $author_user_alias, $time_modified, $editor_user_id, $time_edited, $auto_publish, $time_published, $time_unpublished, $cover_image_id, $urls, $attribs, $ordering, $metakeywords, $metadesc, $privacy, $article_views, $metadata, $featured, $language_id, $xreference){
		$query = "UPDATE articles SET article_type_id = '$article_type_id', title = '$title', alias = '$alias', introtext = '$introtext', full_text = '$full_text',
				publish_status = '$publish_status', time_created = '$time_created', author_user_id = '$author_user_id', author_user_alias = '$author_user_alias', time_modified = '$time_modified',
				editor_user_id = '$editor_user_id', time_edited = '$time_edited', auto_publish = '$auto_publish', time_published = '$time_published', time_unpublished = '$time_unpublished',
				cover_image_id = '$cover_image_id', urls = '$urls', attribs = '$attribs', ordering = '$ordering', metakeywords = '$metakeywords', metadesc = '$metadesc', privacy = '$privacy',
				article_views = '$article_views', metadata = '$metadata', featured = '$featured', language_id = '$language_id', xreference = '$xreference'
				WHERE article_id = '$article_id' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	private function updateCategoryPublishStatus($category_id, $publish_status){
		$query = "UPDATE article_categories SET publish_status = '$publish_status' WHERE category_id = '$category_id' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	private function updateArticlePublishStatus($article_id, $publish_status){
		$query = "UPDATE articles SET publish_status = '$publish_status' WHERE article_id = '$article_id' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	
	
	public function checkUserOptionalAccountStatus($user_id, $account_type_id){
		$optional_user_account_info = array() ;
		$optional_user_account_status = $this->user_sessions->getOptionalUserAccounts($user_id, "(status = 1)") ;
		if($optional_user_account_status !== false){
			$optional_user_account_info = $optional_user_account_status ;
		}
		
		for($i = 0; $i < count($optional_user_account_info); $i++ ){
			if( ($optional_user_account_info[$i]['account_type_id'] == $account_type_id) ){
				return true ;
			}
		}
		return false ;
	}
	public function deleteArticle($user_id, $article_id){
		$user_privilege = $this->user_sessions->getUserPrivilege($user_id) ;
		$article_info 	= $this->getArticleInfo($article_id) ;
		if($article_info !== false){
			$article_author_user_id = $article_info->author_user_id ;
			if( ($user_privilege == 10) || ($user_id == $article_author_user_id) ){
				//Delete Article
				$publish_status_deleted = 9 ;
				$query = "UPDATE articles SET publish_status = '".$publish_status_deleted."' WHERE article_id = '$article_id' " ;
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
			$query = "UPDATE article_categories SET publish_status = '".$publish_status_deleted."', status = '".$status_deleted."' WHERE category_id = '$category_id' " ;
			$query = $this->db->query($query) ;
			
				$query2 = "UPDATE article_to_category SET status = '".$status_deleted."' WHERE category_id = '$category_id' " ;
				$query2 = $this->db->query($query2) ;
			
				return true ;
		}
		return false ;
	}
	
	
	
}
			
?>