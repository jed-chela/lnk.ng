<?php

class Admin_news extends CI_Model{
	// Constructor
	function __construct(){
	// Call the Model constructor
        parent::__construct();
		$this->rootdir = $this->config->item('base_url') ;
		
		$this->load->model('wms_news') ;
		$this->load->model('wms_pages') ;
		$this->load->model('admin_images') ;
		
		$this->menu_selection = 'news' ;
		
	}
	
	public $menu_selection ;
	
	/* News MANAGEMENT. For Publishers, Articles and Categories */
	
	public function handleFlashNotifications(){
		if($this->input->get('form_success') !== false){
			$o = "" ;
			$function_name = $this->input->get('form_success') ;
			
			$o .= "<div id='form-success' class='flash_message pad_all'>" ; 
				$o .= "<p>" ;
					$o .= $this->getFlashFunctionSuccessMessage($function_name) ;
				$o .= "</p>" ;
			$o .= "</div>" ;
			
			$o .= "<script>" ;
				$o .= "$('.flash_message').fadeOut(7000) ;" ;
			$o .= "</script>" ;
			
			return $o ;
		}
		if($this->input->get('form_error') !== false){
			$o = "" ;
			$function_name = $this->input->get('form_error') ;
			
			$o .= "<div id='form-error' class='flash_message pad_all'>" ; 
				$o .= "<p>" ;
					$o .= $this->getFlashFunctionErrorMessage($function_name) ;
				$o .= "</p>" ;
			$o .= "</div>" ;
			
			$o .= "<script>" ;
				$o .= "$('.flash_message').fadeOut(7000) ;" ;
			$o .= "</script>" ;
			
			return $o ;
		}
	}
	public function getFlashFunctionSuccessMessage($function_name){
		switch($function_name){
			//Success
			case 'create_account' 			: return "The account was successfully created!" ; break ;
			case 'edit_my_contributor_info' : return "Your Contributor Info Was Edited Successfully!" ; break ;
			case 'add_new_category' 		: return "The New Category was added successfully!" ; break ; 
			case 'add_an_image' 			: return "The Image was uploaded successfully!" ; break ; 
			
		}
	}
	public function getFlashFunctionErrorMessage($function_name){
		switch($function_name){
			//Error
			case 'create_account' 			: return "An error occured while creating the account!" ; break ;
			case 'edit_my_contributor_info' : return "An error occured while editing your Contributor Info!" ; break ;
			case 'add_new_category' 		: return "An error occured while adding the New Category!" ; break ; 
			case 'add_an_image' 			: return "An error occured while uploading the Image!" ; break ; 
		}
	}
	
	public function showStoreActions($tag){
		$o = "" ;
		
		$dashboard_link					= "" ;
		$edit_my_contributor_info		= "" ;
		$edit_my_account_info			= "" ;
		$edit_my_profile				= "" ;
		
		$add_new_article 				= "" ;
		$add_new_category 				= "" ;
		$add_new_video 					= "" ;
		$add_new_photo_blog 			= "" ;
		
		$view_all_articles				= "" ;
		$view_all_publisher_articles	= "" ;
		$view_all_categories			= "" ;
		$view_all_videos				= "" ;
		$view_all_photo_blog			= "" ;
		
		$edit_article_info				= "" ;
		$edit_article_pub_status		= "" ;
		$edit_video_info				= "" ;
		$edit_video_pub_status			= "" ;
		$edit_photo_blog_post_info			= "" ;
		$edit_photo_blog_post_pub_status	= "" ;
		$edit_category_info				= "" ;
		$edit_article_to_category_info	= "" ;
		$edit_a_video					= "" ;
		$edit_photo_blog				= "" ;
		
		$search_articles				= "" ;
		$search_categories				= "" ;
		
		$add_an_image					= "" ;
		$view_all_images				= "" ;
		$edit_an_image					= "" ;
		
		$create_account 				= "" ;
		$create_account_pub				= "" ;
		
		$view_all_accounts 				= "" ;
		$view_all_accounts_pub			= "" ;
		
		$edit_account_info 				= "" ;
		$edit_account_info_pub			= "" ;
		
		$articles_link 				= "" ;
		$images_link 				= "" ;
		$pm_link 					= "" ;
		$super_pm_link 				= "" ;
		$administrator_link 		= "" ;
		
		switch($tag){
			case 'edit_my_contributor_info'	: $edit_my_contributor_info		= "current" ; $dashboard_link = "current" ; break ;
			case 'edit_my_account_info'		: $edit_my_account_info		= "current" ; $dashboard_link = "current" ; break ;
			case 'edit_my_profile'			: $edit_my_profile				= "current" ; $dashboard_link = "current" ; break ;
			
			case 'add_new_article'			: $add_new_article				= "current" ; $articles_link = "current" ; break ;
			case 'add_new_category'			: $add_new_category				= "current" ; $super_pm_link = "current" ; break ;
			case 'add_new_video'			: $add_new_video				= "current" ; $articles_link = "current" ; break ;
			case 'add_new_photo_blog'		: $add_new_photo_blog			= "current" ; $articles_link = "current" ; break ;
			
	//		$add_new_video 					= "" ;
	//		$add_new_photo_blog 			= "" ;
			
			case 'view_all_articles' 		: $view_all_articles			= "current" ; $articles_link = "current" ; break ;
			case 'view_all_videos' 			: $view_all_videos				= "current" ; $articles_link = "current" ; break ;
			case 'view_all_photo_blog' 		: $view_all_photo_blog			= "current" ; $articles_link = "current" ; break ;
			
			case 'view_all_publisher_articles': 	$view_all_publisher_articles	= "current" ; $pm_link		 = "current" ; break ;
			case 'view_all_publisher_videos': 		$view_all_publisher_videos		= "current" ; $pm_link		 = "current" ; break ;
			case 'view_all_publisher_photo_blog': 	$view_all_publisher_photo_blog	= "current" ; $pm_link		 = "current" ; break ;
			
			case 'view_all_categories' 		: $view_all_categories			= "current" ; $super_pm_link = "current" ; break ;
						
			case 'edit_article_info' 				: $edit_article_info				= "current" ; $pm_link = "current" ; break ;
			case 'edit_article_pub_status' 			: $edit_article_pub_status			= "current" ; $pm_link = "current" ; break ;
			case 'edit_video_info' 					: $edit_video_info					= "current" ; $pm_link = "current" ; break ;
			case 'edit_video_pub_status' 			: $edit_video_pub_status			= "current" ; $pm_link = "current" ; break ;
			case 'edit_photo_blog_post_info'		: $edit_photo_blog_post_info		= "current" ; $pm_link = "current" ; break ;
			case 'edit_photo_blog_post_pub_status' 	: $edit_photo_blog_post_pub_status	= "current" ; $pm_link = "current" ; break ;
			
			case 'edit_category_info' 				: $edit_category_info				= "current" ; $super_pm_link = "current" ; break ;
			case 'edit_category_pub_status' 		: $edit_category_pub_status			= "current" ; $super_pm_link = "current" ; break ;
			
			case 'edit_article_to_category' 		: $edit_article_to_category			= "current" ; $pm_link 		 = "current" ; break ;
			case 'edit_video_to_category' 			: $edit_video_to_category			= "current" ; $pm_link 		 = "current" ; break ;
			case 'edit_photo_blog_post_to_category' : $edit_photo_blog_post_to_category	= "current" ; $pm_link 		 = "current" ; break ;
			
			case 'delete_a_category' 				: $delete_a_category				= "current" ; $super_pm_link = "current" ; break ;
			
			case 'search_articles' 			: $search_articles				= "current" ; $articles_link = "current" ; break ;
			case 'search_categories' 		: $search_categories			= "current" ; $pm_link 		 = "current" ; break ;
			
			/* Images */
			case 'add_an_image' 			: $add_an_image					= "current" ; $images_link		= "current" ; break ;
			case 'view_all_images' 			: $view_all_images				= "current" ; $images_link		= "current" ; break ;
			case 'edit_an_image' 			: $edit_an_image				= "current" ; $images_link		= "current" ; break ;
			
			/* User Accounts */
			case 'create_account' 			: $create_account				= "current" ; $administrator_link 	= "current" ; break ;
			case 'create_account_pub' 		: $create_account_pub			= "current" ; $super_pm_link		= "current" ; break ;
			
			case 'view_all_accounts' 		: $view_all_accounts				= "current" ; $administrator_link 	= "current" ; break ;
			case 'view_all_accounts_pub' 	: $view_all_accounts_pub			= "current" ; $super_pm_link		= "current" ; break ;
			
			case 'edit_account_info' 		: $edit_account_info			= "current" ; $administrator_link		= "current" ; break ;
			case 'edit_account_info_pub' 	: $edit_account_info_pub		= "current" ; $super_pm_link			= "current" ; break ;
			
			case 'add_new_page'				: $add_new_page					= "current" ; $administrator_link = "current" ; break ;
			case 'view_all_pages'			: $view_all_pages				= "current" ; $administrator_link = "current" ; break ;
		}
		
		$user_id = $this->user_sessions->getUserId() ;
		
		$o .= "<div id='nav-x-menu'>" ;
			$o .= "<ul>" ;
			
			if(  ($this->user_sessions->getUserPrivilege($user_id) == '10')  ){
				
				$o .= "<li class=''><a href='".$this->rootdir."admin/index/dashboard'><span class='home_icon'>Admin Dashboard</span></a></li>" ;
				$o .= "<li class=''><a href='".$this->rootdir."admin/index/templates'>Templates</a></li>" ;
				$o .= "<li class=''><a href='".$this->rootdir."admin/index/widgets'>Widgets</a></li>" ;
			
			}
			
				$o .= "<li class=''><a>&nbsp;</a></li>" ;
			
			if(  ($this->checkUserOptionalAccountStatus($user_id, '1003') === true) 
					|| ($this->checkUserOptionalAccountStatus($user_id, '1005') === true) 
					|| ($this->checkUserOptionalAccountStatus($user_id, '1007') === true) 
					|| ($this->checkUserOptionalAccountStatus($user_id, '1100') === true) 
					|| ($this->user_sessions->getUserPrivilege($user_id) == '10')  ){
				$o .= "<li class=''><a href='".$this->rootdir."admin/index/news/view_dashboard' id='".$dashboard_link."'>Dashboard</a>" ;
					$o .= "<ul>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/edit_my_contributor_info' id='".$edit_my_contributor_info."'>Edit My Contributor Info</a></li>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/edit_account_info/".$user_id."' id='".$edit_my_account_info."'>Edit My Account Info</a></li>" ;
					$o .= "</ul>" ;
				$o .= "</li>" ;
				$o .= "<li class=''><a id='".$articles_link."'>Content</a>" ;
					$o .= "<ul>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/add_new_article' id='".$add_new_article."'>Add New Article</a></li>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/view_all_articles' id='".$view_all_articles."'>View My Articles</a></li>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/add_new_video' id='".$add_new_video."'>Add New Video</a></li>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/view_all_videos' id='".$view_all_videos."'>View All Videos</a></li>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/add_new_photo_blog' id='".$add_new_photo_blog."'>Add New Cartoon/Photo Post</a></li>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/view_all_photo_blog' id='".$view_all_photo_blog."'>View All Cartoon/Photo Posts</a></li>" ;
					$o .= "</ul>" ;
				$o .= "</li>" ;
				$o .= "<li class=''><a id='".$images_link."'>Images</a>" ;
					$o .= "<ul>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/add_an_image' id='".$add_an_image."'>Upload An Image</a></li>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/view_all_images' id='".$view_all_images."'>View Images</a></li>" ;
					$o .= "</ul>" ;
				$o .= "</li>" ;
			
			}
			
			if(  ($this->checkUserOptionalAccountStatus($user_id, '1005') === true) 
					|| ($this->checkUserOptionalAccountStatus($user_id, '1007') === true) 
					|| ($this->checkUserOptionalAccountStatus($user_id, '1100') === true) 
					|| ($this->user_sessions->getUserPrivilege($user_id) == '10')  ){
				
				$o .= "<li class=''><a id='".$pm_link."'>Publisher Manager</a>" ;
					$o .= "<ul>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/view_all_publisher_articles'>View Publisher Articles</a></li>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/view_all_publisher_videos'>View Publisher Videos</a></li>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/view_all_publisher_photo_blog'>View Publisher Cartoon Posts</a></li>" ;
					$o .= "</ul>" ;
				$o .= "</li>" ;
			
			}
			
			if(  ($this->checkUserOptionalAccountStatus($user_id, '1007') === true) 
					|| ($this->checkUserOptionalAccountStatus($user_id, '1100') === true) 
					|| ($this->user_sessions->getUserPrivilege($user_id) == '10')  ){
			
				$o .= "<li class=''><a id='".$super_pm_link."'>Super PM</a>" ;
					$o .= "<ul>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/add_new_category'>Add New Categories</a></li>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/view_all_categories'>View & Edit Categories</a></li>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/view_activity_logs'>View Activity Logs</a></li>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/create_account_pub'>Create Publisher Account</a></li>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/view_all_accounts_pub'>View & Edit Publisher Accounts</a></li>" ;
					$o .= "</ul>" ;
				$o .= "</li>" ;
				
			}
			
			if(  ($this->checkUserOptionalAccountStatus($user_id, '1100') === true) || ($this->user_sessions->getUserPrivilege($user_id) == '10')  ){
				
				$o .= "<li class=''><a id='".$administrator_link."'>Administrator</a>" ;
					$o .= "<ul>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/create_account'>Create User Accounts</a></li>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/view_all_accounts'>View & Edit User Accounts</a></li>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/add_new_page'>Add A New Page</a></li>" ;
						$o .= "<li><a href='".$this->rootdir."admin/index/news/view_all_pages'>View All Pages</a></li>" ;
					$o .= "</ul>" ;
				$o .= "</li>" ;
			
			}
			
			$o .= "</ul>" ;
		$o .= "</div>" ;
		return $o ;
	}
	
	public function handleNewsActionRequest($index_param, $index_function, $index_p1, $index_p2, $index_p3, $index_p4, $index_p5){
		$this->menu_selection = $index_param ;
		switch($index_function){
			case 'view_dashboard'			: return $this->view_dashboard() 				; break ;
			case 'edit_my_contributor_info'	: return $this->edit_my_contributor_info() 		; break ;
			case 'edit_my_profile'			: return $this->edit_my_profile() 				; break ;
			
			case 'add_new_article'			: return $this->add_new_article() 					; break ;
			case 'add_new_category'			: return $this->add_new_category() 					; break ;
			case 'add_new_video'			: return $this->add_new_video() 					; break ;
			case 'add_new_photo_blog'		: return $this->add_new_photo_blog() 				; break ;
			
			case 'view_all_articles' 		: return $this->view_all_articles($index_p1, $index_p2, $index_p3, $index_p4, $index_p5) 	; break ;
			case 'view_all_videos' 			: return $this->view_all_videos($index_p1, $index_p2, $index_p3, $index_p4, $index_p5) 		; break ;
			case 'view_all_photo_blog' 		: return $this->view_all_photo_blog($index_p1, $index_p2, $index_p3, $index_p4, $index_p5) 	; break ;
			case 'view_individual_article' 	: return $this->view_individual_article($index_p1) 	; break ;
			case 'view_all_publisher_articles'	: return $this->view_all_publisher_articles($index_p1, $index_p2, $index_p3, $index_p4, $index_p5) 	; break ;
			case 'view_all_publisher_videos'	: return $this->view_all_publisher_videos($index_p1, $index_p2, $index_p3, $index_p4, $index_p5) 	; break ;
			case 'view_all_publisher_photo_blog': return $this->view_all_publisher_photo_blog($index_p1, $index_p2, $index_p3, $index_p4, $index_p5) 	; break ;
			case 'view_all_categories' 		: return $this->view_all_categories($index_p1, $index_p2, $index_p3, $index_p4, $index_p5)				; break ;
						
			case 'edit_article_info' 		: return $this->edit_article_info($index_p1, $index_p2)		; break ;
			case 'edit_article_pub_status' 	: return $this->edit_article_pub_status($index_p1)			; break ;
			
			case 'edit_video_info' 			: return $this->edit_video_info($index_p1, $index_p2)		; break ;
			case 'edit_video_pub_status' 	: return $this->edit_video_pub_status($index_p1)			; break ;
			
			case 'edit_photo_blog_post_info' 		: return $this->edit_photo_blog_post_info($index_p1, $index_p2)		; break ;
			case 'edit_photo_blog_post_pub_status' 	: return $this->edit_photo_blog_post_pub_status($index_p1)			; break ;
			
			case 'edit_category_info' 		: return $this->edit_category_info($index_p1)		; break ;
			case 'edit_category_pub_status' : return $this->edit_category_pub_status($index_p1)	; break ;
			case 'edit_article_to_category' : return $this->edit_article_to_category($index_p1)	; break ;
			case 'edit_video_to_category' 	: return $this->edit_video_to_category($index_p1)	; break ;
			case 'edit_photo_blog_post_to_category' : return $this->edit_photo_blog_post_to_category($index_p1)	; break ;
			
			case 'delete_an_article' 		: return $this->delete_an_article($index_p1)		; break ;
			case 'delete_a_video' 			: return $this->delete_a_video($index_p1)			; break ;
			case 'delete_a_photo_blog_post'	: return $this->delete_a_photo_blog_post($index_p1)	; break ;
			case 'delete_a_category' 		: return $this->delete_a_category($index_p1)		; break ;
			
			case 'search_articles' 			: return $this->search_articles()					; break ;
			case 'search_categories' 		: return $this->search_categories()					; break ;
			
			/* Images */
			case 'add_an_image' 			: return $this->add_an_image()						; break ;
			case 'view_all_images' 			: return $this->view_all_images($index_p1, $index_p2, $index_p3, $index_p4, $index_p5)					; break ;
			case 'edit_an_image' 			: return $this->edit_an_image($index_p1)			; break ;
			
			/* User Accounts */
			case 'create_account' 			: return $this->create_account()					; break ;
			case 'create_account_pub' 		: return $this->create_account()					; break ;
			
			case 'view_all_accounts' 		: return $this->view_all_accounts($index_p1, $index_p2, $index_p3, $index_p4, $index_p5)					; break ;
			case 'view_all_accounts_pub' 	: return $this->view_all_accounts($index_p1, $index_p2, $index_p3, $index_p4, $index_p5)					; break ;
			
			case 'edit_account_info' 		: return $this->edit_account_info($index_p1)		; break ;
			case 'edit_account_info_pub'	: return $this->edit_account_info($index_p1)		; break ;
			
			case 'add_new_page'				: return $this->add_new_page() 						; break ;
			case 'view_all_pages' 			: return $this->view_all_pages($index_p1, $index_p2, $index_p3, $index_p4, $index_p5)					; break ;
			case 'edit_page_info' 			: return $this->edit_page_info($index_p1)					; break ;
			
			
			default : return $this->getActionRequestError() ;
		}
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
	public function checkUserOptionalAccountPermission($user_id, $action_name){
		$privilege = $this->user_sessions->getUserPrivilege($user_id) ;
		$optional_user_account_info = array() ;
		$optional_user_account_status = $this->user_sessions->getOptionalUserAccounts($user_id, "(status = 1)") ;
		if($optional_user_account_status !== false){
			$optional_user_account_info = $optional_user_account_status ;
		}
		
		switch($action_name){
			case 'view_all_user_images'	: 	if($privilege == '10'){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1100') === true){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1007') === true){
											return true ;
										}
										return false ;
										break;
			case 'create_account'	: 	if($privilege == '10'){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1100') === true){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1007') === true){
											return true ;
										}
										return false ;
										break;
			
			case 'add_new_category'	: 	if($privilege == '10'){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1100') === true){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1007') === true){
											return true ;
										}
										return false ;
										break;
										
			case 'edit_category_info':	if($privilege == '10'){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1100') === true){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1007') === true){
											return true ;
										}
										return false ;
										break;
			case 'edit_category_pub_status': if($privilege == '10'){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1100') === true){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1007') === true){
											return true ;
										}
										return false ;
										break;
			
			case 'add_new_article'	: 	if($privilege == '10'){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1100') === true){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1007') === true){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1005') === true){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1003') === true){
											return true ;
										}
										return false ;
										break;
										
			case 'edit_article_info'	: 	if($privilege == '10'){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1100') === true){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1007') === true){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1005') === true){
											return true ;
										}
										return false ;
										break;
			case 'edit_article_pub_status': if($privilege == '10'){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1100') === true){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1007') === true){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1005') === true){
											return true ;
										}
										return false ;
										break;
			case 'edit_article_to_category': if($privilege == '10'){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1100') === true){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1007') === true){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1005') === true){
											return true ;
										}
										return false ;
										break;
			
			case 'delete_an_article'	: 	if($privilege == '10'){
											return true ;
										}
										return false ;
										break;
			case 'delete_a_category'	: 	if($privilege == '10'){
											return true ;
										}
										return false ;
										break;
										
			case 'view_individual_article':if($privilege == '10'){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1100') === true){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1007') === true){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1005') === true){
											return true ;
										}
										return false ;
										break;
			
			case 'add_new_page':		if($privilege == '10'){
											return true ;
										}
										if($this->checkUserOptionalAccountStatus($user_id, '1100') === true){
											return true ;
										}
										
			default					:	return false ;
										break;
		}
	}
	
	public function create_account(){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		$user_id = $this->user_sessions->getUserId() ;
		$permission_result = $this->checkUserOptionalAccountPermission($user_id, 'create_account') ;
		if($permission_result === true){
			//Continue
			if($this->validate_n_process_create_account_form() === true){
				//Show Success Message
				$msg = "The Account was Created successfully!" ;
				$o .= $this->prepareSuccessMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_all_accounts?form_success=create_account") ;
				
				//Show Form Again if requested
				if($this->input->post('create_account_submit') !== false){
					//Show Create Account Form
					$o .= $this->showForm_create_account() ;
				}
			}else{
				//Show Create Account Form
				$o .= $this->showForm_create_account() ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_all_accounts?form_error=create_account") ;
		}
		return $o ;
	}
	private function validate_n_process_create_account_form(){
		if( ($this->input->post('create_account_submit') !== false) || ($this->input->post('create_account_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('create_account_email', 'New User Email', 'required|email|is_unique[app_users.email]');
			$this->form_validation->set_rules('create_account_password', 'New User Password', 'required|min_length[5]|matches[create_account_vpass]');
			$this->form_validation->set_rules('create_account_vpass', "Verify User's password", 'required');
			$this->form_validation->set_rules('create_account_account_type', 'Select An Account Type', 'required|email');
			$this->form_validation->set_rules('create_account_user_fname', 'New User First Name', 'required');
			$this->form_validation->set_rules('create_account_user_lname', 'New User Last Name', 'required');
			
			$this->form_validation->set_rules('create_account_your_password', "Your own Password", 'required|min_length[5]');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$new_user_email 	= protect($this->input->post('create_account_email')) ;
				$new_user_pass 		= protect($this->input->post('create_account_password')) ;
				$new_user_vpass 	= protect($this->input->post('create_account_vpass')) ;
				$new_user_acc_type 	= protect($this->input->post('create_account_account_type')) ;
				$new_user_fname 	= protect($this->input->post('create_account_user_fname')) ;
				$new_user_lname 	= protect($this->input->post('create_account_user_lname')) ;
				
				$ah_email_address 	= protect($this->input->post('create_account_ah_email_address')) ;
				$ah_email_password	= protect($this->input->post('create_account_ah_email_password')) ;
				
				$your_password 		= protect($this->input->post('create_account_your_password')) ;
				
				$new_user_acc_type = explode('_', $new_user_acc_type) ;
				$new_user_privilege = $new_user_acc_type[0] ;
				$new_user_optional_account_type_id = $new_user_acc_type[1] ;
					
				//Confirm logged In user Password
				if($this->user_sessions->confirmloggedInUserPassword($your_password) === false){
					return false;
				}
				
				//Register the User
				//Normal Registration, Privilege == 1
				$reg_user_res = $this->user_sessions->registerUserType2($new_user_email, $new_user_pass, $new_user_fname, $new_user_lname) ;
				if( $reg_user_res !== false){
					$new_user_id = $reg_user_res ;
					
					//Update The User's Privilege
					if($this->user_sessions->updateUserPrivilege($new_user_id, $new_user_privilege) === true){
						//Prepare Email Info
						@$email_info->new_user_fname 	= $new_user_fname ;
						$email_info->new_user_fullname	= $new_user_fname." ".$new_user_lname ;
						$email_info->new_user_email		= $new_user_email ;
						$email_info->new_user_pass		= $new_user_pass ;
						$email_info->new_user_id		= $new_user_id ;
						
						$email_info->ah_email_address	= $ah_email_address ;
						$email_info->ah_email_password	= $ah_email_password ;
						
						$email_info->new_user_is_contributor = false ;
						
						//Create An Optional Account for the User
						if($new_user_optional_account_type_id != ""){
							if($this->user_sessions->createOptionalAccountForUser($new_user_id, $new_user_optional_account_type_id) === true){
								//Reset Email User Info relating to a contributor
								$email_info->new_user_is_contributor = true ;
								
							}
						}else{
							//No Optional Account is Required
							
						}
						
						$this->sendRegistrationCompletedEmail_adminVersion($email_info, $email_info->new_user_email) ;
							
						$special_recipients = $this->getSpecialEmailRecipients() ;
						if(count($special_recipients) > 0){
							for($i = 0; $i < count($special_recipients) ; $i++){
								$the_recipient_email = $special_recipients[$i] ;
								$this->sendRegistrationCompletedEmail_adminVersion($email_info, $the_recipient_email) ;
							}		
						}
						
						return true ;
						
						
					}//if updateUserPrivilege
				}//if reg_user_res
			}
			
		}else{
			return false ;
		}
	}
	public function getSpecialEmailRecipients(){
		$arr =  array('jedhppc@gmail.com', 'lamisolfang@gmail.com') ;
		return $arr ;
	}
	private function showForm_create_account(){			
		$user_id = $this->user_sessions->getUserId() ;
		$creatable_user_accounts = array() ;
		
		if($this->checkUserOptionalAccountStatus($user_id, '1007') === true){
			$creatable_user_accounts = array(
				'1_1003' => 'Publisher',
				'1_1005' => 'Publisher Manager'
			) ;
		}
		if($this->checkUserOptionalAccountStatus($user_id, '1100') === true){
			$creatable_user_accounts = array(
				'1_1007' => 'Super Publisher Manager',
				'1_1005' => 'Publisher Manager',
				'1_1003' => 'Publisher',
				'1_1100' => 'Administrator'
			) ;
		}
		if($this->user_sessions->getUserPrivilege($user_id) == '10'){
			$creatable_user_accounts = array(
				'1_1100' => 'Administrator',
				'1_1007' => 'Super Publisher Manager',
				'1_1005' => 'Publisher Manager',
				'1_1003' => 'Publisher',
				'10_' => 'SUPER Administrator'
			) ;
		}
		
		//FIELDS
		$form_fields_html = array() ;
			//New User Email
			$field = $this->admin_forms->getInputCustom("email", "New User Email", "create_account_email", "", "", "Enter New User Email", "required", "", "") ;
			array_push($form_fields_html, $field) ;
			
			//New User Password
			$field = $this->admin_forms->getInputCustom("password", "New User Password", "create_account_password", "", "", "Enter New User Password", "required", "", "") ;
			array_push($form_fields_html, $field) ;
			
			//Verify New User's Password
			$field = $this->admin_forms->getInputCustom("password", "Verify User's Password", "create_account_vpass", "", "", "Verify User's Password", "required", "", "") ;
			array_push($form_fields_html, $field) ;
			
			//Account Type (Select Field)
			$field = $this->admin_forms->getRegularSelect("Select An Account Type", "create_account_account_type", "", "", "", false, array(), $creatable_user_accounts ) ;
			array_push($form_fields_html, $field) ;
			
			//New User First Name
			$field = $this->admin_forms->getInputCustom("text", "New User First Name", "create_account_user_fname", "", "", "Enter New User First Name", "required", "", "") ;
			array_push($form_fields_html, $field) ;
			
			//New User Last Name
			$field = $this->admin_forms->getInputCustom("text", "New User Last Name", "create_account_user_lname", "", "", "Enter New User Last Name", "required", "", "") ;
			array_push($form_fields_html, $field) ;
			
			//New User AH Email Address
			$field = $this->admin_forms->getInputCustom("text", "AH Email Address", "create_account_ah_email_address", "", "", "Enter AH Email Address", "", "", "") ;
			array_push($form_fields_html, $field) ;
			
			//New User AH Email Account Password
			$field = $this->admin_forms->getInputCustom("text", "AH Email Account Password", "create_account_ah_email_password", "", "", "AH Email Account Password", "", "", "") ;
			array_push($form_fields_html, $field) ;
			
			$field = "<hr/>Security:<br/><br/>" ;
			array_push($form_fields_html, $field) ;
			
			//Your Own Password
			$field = $this->admin_forms->getInputCustom("password", "Your Own Password", "create_account_your_password", "", "", "Enter Your Own Password", "required", "", "") ;
			array_push($form_fields_html, $field) ;
			
			//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "create_account_submit", "create_account_submit_and_close", "", "", "submit", "submit", true, $this->rootdir."admin/index/news/view_all_accounts", "Cancel") ;
				//$button1_name, $button2_name, $field1_name = "", $field2_name = "", $field1_class = "", $field2_class = "", $field1_type = "submit", $field2_type = "submit", $add_cancel = true, $cancel_url = "", $cancel_button_name = "Cancel"
				
		//Get Form HTML	
		$form_html = $this->admin_forms->getRegularForm("Create An Account", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		
		return $form_html ;
	}
	
	
	public function sendRegistrationCompletedEmail_adminVersion($email_info, $recipient_email, $site_url_name = "AfricanHadithi.com", $site_url = "http://www.africanhadithi.com", $site_name = "African Hadithi", $contact_email = "info@africanhadithi.com"){
		if(count($email_info) > 0){
		
		$receiver_fname 	= $email_info->new_user_fname ;
		$receiver_fullname 	= $email_info->new_user_fullname ;
		$receiver_email 	= $email_info->new_user_email ;
		$receiver_pass 		= $email_info->new_user_pass ;
		$receiver_user_id	= $email_info->new_user_id ;
		
		$ah_email_address 	= $email_info->ah_email_address ;
		$ah_email_password	= $email_info->ah_email_password ;
		
		$is_contributor 	= $email_info->new_user_is_contributor ;
		
			$m = "" ;
			
			$m .= "<div style='max-width:600px; padding:20px; margin-left:20px; background:#F2F2F2; border:3px double orange; box-shadow:-10px 10px 15px #CCC; border-radius:30px; -moz-border-radius:30px; -webkit-border-radius:30px;' >" ;
			$m .= "<table id='default_email_container' border='0' >" ;
				$m .= "<tr id='default_email_header'>" ;
					$m .= "<td align='right'><img width='110px' height='110px' src='".$this->rootdir."images/logos/sitelogo_pic.png' /></td>" ;
				$m .= "</tr>" ;
				
				$m .= "<tr id='default_email_body'>" ;
					$m .= "<td >" ;
						$m .= "<p>Dear ".$receiver_fname."!</p>" ; 
						
						if($is_contributor === true){
							$m .= "<p>Welcome, and thank you for becoming a Contributor  of AfricanHadithi. We are indeed excited to have you on board. Please review your log-in information below, and save this e-mail for future reference.</p>" ;					
						}else{
						}
						
						$m .= "<p>&nbsp;</p>" ;
						
						$m .= "<p><b>Display Name: <i>".$receiver_fullname."</i></b></p>" ;
						$m .= "<p><b>Login Email: <i>".$receiver_email."</i></b></p>" ;
						$m .= "<p><b>Login Password: <i>".$receiver_pass."</i></b></p>" ;
						if($is_contributor === true){
							$m .= "<p><b>Webmail Url: <i><a href='http://mail.africanhadithi.com' >mail.africanhadithi.com</a></i></b></p>" ;
							$m .= "<p><b>Webmail Email: <i>".$ah_email_address."</i></b></p>" ;
							$m .= "<p><b>Webmail Password: <i>".$ah_email_password."</i></b></p>" ;
						}else{
						}
						
						$m .= "<p>&nbsp;</p>" ;
						if($is_contributor === true){
							$acc_update_url = $this->rootdir."admin/index/news/edit_account_info/".$receiver_user_id ;
							$m .= "<p>If, at any time, you want to change your password or update your contact information, please visit the <a href='".$acc_update_url."'>Contributors Portal.</a></p>" ;
						}else{
							$acc_update_url = '#' ;
							$m .= "<p>If, at any time, you want to change your password or update your contact information, please visit the <a href='".$acc_update_url."'>Edit Account Page.</a></p>" ;
						}
						
						$m .= "<p>Thank you and welcome to The AfricanHadithi community.</p>" ;
						
					$m .= "</td>" ;
				$m .= "</tr>" ;
					
				$m .= "<tr id='default_email_footer'>" ;
					$m .= "<td>" ;
						$m .= "<div style='background:#FFF; padding:20px; font-size:11px;text-align:center'>" ;
							$m .= "<p>This email was sent to ".$recipient_email."</p>" ;
							$m .= "<p>If you feel that you received this email in error, pls contact us at &nbsp;<a href='mailto:".$contact_email."' target='_top'>".$contact_email."</a></p>" ;
						$m .= "</div>" ;
					$m .= "</td>" ;
				$m .= "</tr>" ;
				
			$m .= "</table>" ;
			$m .= "</div>" ;
			
			$sender_name 	= "AfricanHadithi" ;
			$sender_email	= "info@africanhadithi.com" ;
			$subject		= "AfricanHadithi Sign Up Notification" ;
			$message		= $m ;
				
//			echo $message ;
			
			
			$res = $this->sendAnEmail_adminVersion($sender_name, $sender_email, $recipient_email, $subject, $message ) ;
			if($res === true){
				//Email Send Successful
				return true ;
			}else{
				//Email Send Failed
				return false ;
			}
			
		}
	}
	public function sendAnEmail_adminVersion($sender_name, $sender_email, $recipient_email, $subject, $message ){
		$to = $recipient_email ;
		$from_user = $sender_name ;
		$from_email = $sender_email ;
		$from = "$from_user <$from_email>" ;
		$subject = $subject ;
		$m = $message ;
		
//		echo $m."<hr/>" ;
		
		$res = mail_utf8($to, $from_user, $from_email, $subject , $m) ;
		if($res === true){
			//Email Send Successful
			return true ;
		}else{
			//Email Send Failed
			return false ;
		}

	}
	
	
	
	public function view_all_accounts($page_number = "", $no_of_items = "", $sort_by = "", $display_type = ""){	
		$o = "" ;
		
			$user_id = $this->user_sessions->getUserId() ;
			
			$rootdir 						= $this->rootdir;
			$this_uri 						= "admin/index/news/view_all_accounts/" ;
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
					$o .= "<span>View All User Accounts</span>" ;
				$o .= "</div>" ;
				$o .= "<div class='view-items-display block_left'>" ;
					$o .= "<span>Display:</span>" ;
					$disp_ = ""; $disp_1 = ""; $disp_0 = "";
					switch($items_display_type){
						case '' : 	$disp_ 		= "current"; break ;
						case '1' : 	$disp_1 	= "current"; break ;
						case '0' : 	$disp_0 	= "current"; break ;
					}
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '')."'  class='".$disp_."' >All</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '1')."' class='".$disp_1."' >Enabled</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '0')."' class='".$disp_0."' >Suspended</a>" ;
				$o .= "</div>" ;
				$o .= "<div class='view-items-limit block_left'>" ;
					$o .= "<span>Show:</span>" ;
				
					$all_options = array() ;
					
					$all_options = array(
		//				$this->getLimitOptionsValue(1, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 1,
		//				$this->getLimitOptionsValue(2, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 2,
		//				$this->getLimitOptionsValue(5, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 5,
		//				$this->getLimitOptionsValue(10, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 10,
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
					$this->getSortByOptionsValue('i', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'First Name (A - Z)',
					$this->getSortByOptionsValue('ii', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'First Name (Z - A)',
					$this->getSortByOptionsValue('iii', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Last Name (A - Z)',
					$this->getSortByOptionsValue('iv', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Last Name (Z - A)',
					$this->getSortByOptionsValue('v', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'User Status',
					$this->getSortByOptionsValue('vi', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Time Added (New - Old)',
					$this->getSortByOptionsValue('vii', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Time Added (Old - New)',
					) ;
					
					$selected_value = $this->getSortByOptionsValue($sort_items_by, $rootdir, $this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) ;
					$js = "onchange='location = this.value;'" ;
					$o .= form_dropdown('selected_sort_by', $all_options, $selected_value, $js );
				
				$o .= "</div>" ;
			$o .= "</div>" ;

			//Get Pages Table
			$table = "" ;
				
				$order = $this->getOptionalUserSortByValue($sort_items_by) ;
				$start_limit = $no_of_items_to_show * $limit_start_no ;
				$limit = "LIMIT ".$start_limit.",".$no_of_items_to_show ;
				
				$extra_where_clause = "app_user_account_users.status != 9" ;
				if($items_display_type != ''){
					$extra_where_clause = "app_user_account_users.status = ".$display_type."" ;
				}
				$users = $this->user_sessions->getAllOptionalUserAccountInfo($extra_where_clause, $order, $limit) ;
				if($users !== false){
					$table .= "<table class='table table-bordered table-condensed'>" ;
					$table .= "<tr>" ;
						$table .= "<th>No</th>" ;
						$table .= "<th>Name</th>" ;
						$table .= "<th>Status</th>" ;
						$table .= "<th>Email</th>" ;
						$table .= "<th>Privilege</th>" ;
						$table .= "<th>&nbsp;</th>" ;
						$table .= "<th>Options</th>" ;
					$table .= "</tr>" ;
					for($i = 0; $i < count($users) ; $i++){
						$user_info 		= $users[$i] ;
						if($user_info !== false){
							$user_id 				= $user_info['user_id'] ;
							$user_account_id		= $user_info['account_id'] ;
							$user_account_type_id	= $user_info['account_type_id'] ;
							
							$user_status 			= $user_info['status'] ;
							
							$opt_acc_info = $this->user_sessions->getOptionalUserAccounts($user_id, "(status != '9')") ;
							if($opt_acc_info !== false){
								$user_opt_info 	= $opt_acc_info[0] ;
								$user_status	= $user_opt_info['status'] ;
							}
							
							$user_time 				= $user_info['time'] ;
							
							$user_fname 			= $user_info['fname'] ;
							$user_lname 			= $user_info['lname'] ;
							$user_oname 			= $user_info['oname'] ;
							$user_gender 			= $user_info['gender'] ;
							$user_dateofbirth		= $user_info['dateofbirth'] ;
							$user_profile_image_id	= $user_info['profile_image_id'] ;
							$user_cover_image_id	= $user_info['cover_image_id'] ;
							
							$user_email				= $this->user_sessions->getUserEmail($user_id) ;
							
							$user_name 			= $user_fname." ".$user_lname ;
							
							$this_user_status_name = "" ;
							if($user_status == '1'){
								$this_user_status_name = 'Enabled' ;
							}else if($user_status == '2'){
								$this_user_status_name = 'Suspended' ;
							}
							
							$user_account_type_id_name = "" ;
							if($user_account_type_id == '1100'){
								$user_account_type_id_name = 'Administrator' ;
							}else if($user_account_type_id == '1007'){
								$user_account_type_id_name = 'Super PM' ;
							}else if($user_account_type_id == '1005'){
								$user_account_type_id_name = 'Publisher Manager' ;
							}else if($user_account_type_id == '1003'){
								$user_account_type_id_name = 'Publisher' ;
							}
							
							
					//		$pages_body 			= getFirstXLetters(html_entity_decode($pages_info->full_text) ) ;					
							
							//ROW COLUMNS
							$row = "" ;
							$row = "<tr>" ;
								$row .= "<td class='view-item-title'><div>".($i + 1 + ($limit_start_no * $no_of_items_to_show) )."</div>" ;
								$row .= "<td class='view-item-title'><div>".$user_name."</div>" ;
								$row .= "<td class='view-item-title'><div>".$this_user_status_name."</div>" ;
								$row .= "<td class='view-item-title'><div>".$user_email."</div>" ;
								$row .= "<td class='view-item-title'><div>".$user_account_type_id_name."</div>" ;
								$row .= "<td class='view-item-title'><div>&nbsp;</div>" ;
								$row .= "<td class='view-item-title'><div>";
								
									$row .= "<a class='img-view-item block_left' target='_blank' href='".$rootdir."admin/index/news/view_individual_account/".$user_id."' title='View'></a>" ;
									
									$row .= "<a class='img-edit-item block_left' href='".$rootdir."admin/index/news/edit_account_info/".$user_id."' title='Edit'></a>" ;
									
									
									if($this->user_sessions->getUserPrivilege($user_id) == '10'){
										$row .= "<a class='img-delete-item block_left' href='".$rootdir."admin/index/news/delete_an_account/".$user_id."' title='Delete'></a>" ;
										
									}
									
								$row .= "</div>" ;
							$row .= "</tr>" ;
							
							$table .= $row ;
							
						}//end if page_info
					}// end for
					$table .= "</table>" ;
				}
			$o .= $table ;
			
			//Get Sub Pages links
				$p = "" ;
				$p .= "<div class='block_auto view-items-subpages' >" ;
					$extra_where_clause = "app_user_account_users.status != 9" ;
					if($items_display_type != ''){
						$extra_where_clause = "app_user_account_users.status = ".$display_type."" ;
					}
					$full_pages = $this->user_sessions->getAllOptionalUserAccountInfo($extra_where_clause ) ;
					if($full_pages !== false){
						$full_count = count($full_pages) ;
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
	private function getOptionalUserSortByValue($sort_param){
		switch($sort_param){
			case 'default': 	return "ORDER BY app_user_account_users.time DESC" ; 			break ;
			case 'i':		 	return "ORDER BY app_user_details.fname" ; 						break ;
			case 'ii':		 	return "ORDER BY app_user_details.fname DESC" ; 				break ;
			case 'iii':		 	return "ORDER BY app_user_details.lname" ; 						break ;
			case 'iv':		 	return "ORDER BY app_user_details.lname DESC" ; 				break ;
			case 'v':		 	return "ORDER BY app_user_account_users.status" ; 				break ;
			case 'vi':		 	return "ORDER BY app_user_account_users.time DESC" ; 			break ;
			case 'vii':			return "ORDER BY app_user_account_users.time" ; 					break ;
			default: 			return "" ; 										break ;
		}
	}
	private function getMainUserSortByValue($sort_param){
		switch($sort_param){
			case 'default': 	return "ORDER BY app_users.time DESC" ; 			break ;
			case 'i':		 	return "ORDER BY app_user_details.fname" ; 			break ;
			case 'ii':		 	return "ORDER BY app_user_details.fname DESC" ; 	break ;
			case 'iii':		 	return "ORDER BY app_user_details.lname" ; 			break ;
			case 'iv':		 	return "ORDER BY app_user_details.lname DESC" ; 	break ;
			case 'v':		 	return "ORDER BY publish_status" ; 					break ;
			case 'vi':		 	return "ORDER BY app_users.time DESC" ; 			break ;
			case 'vii':		 	return "ORDER BY app_users.time" ; 					break ;
			default: 			return "" ; 										break ;
		}
	}
	
	
	
	
	public function edit_account_info($account_user_id = ""){	
		$o = "" ;
		
			$user_id = $this->user_sessions->getUserId() ;
			//Get User Privilege
			$user_privilege = $this->user_sessions->getUserPrivilege($user_id) ;
			
			//Get Account User Privilege
			$account_user_privilege = $this->user_sessions->getUserPrivilege($account_user_id) ;
			
			//Check User Authority
			if($account_user_privilege == '10'){
				//Account User is a Super Administrator
			}
			
			if( ($account_user_id == $user_id) || ($user_privilege == '10') ){
				//The Current User Owns the Acount
				//OR The Current User is a Super Administrator
				
				if($this->edit_saveAccountInfo() === true){
					$o .= "<div class='block_auto' id='form-success'><p>Changes have been Saved!</p></div>" ;
				}
				if($this->edit_resetUserAccountPassword() === true){
					$o .= "<div class='block_auto' id='form-success'><p>The New Password has been Successfully Saved!</p></div>" ;
				}else{
					$o .= validation_errors();
				}
				
				
				$account_user_info = $this->user_sessions->getUserInfo($account_user_id) ;
								
				$account_user_email = $this->user_sessions->getUserEmail($account_user_id) ;
				if( ($account_user_info !== false) ){
					
					$acc_user_name 	= $account_user_info->fname." ".$account_user_info->lname ;
										
					$email 			= $account_user_email;
										
					$gender			= $account_user_info->gender ;
					
					$date_of_birth	= $account_user_info->date_of_birth ;
					
					$o .= "<div class='account-info-display-box'>" ;
						$o .= "<div class='account-info-main-header'>Account Information</div>" ;
						$o .= "<div class='account-info-main-tab'>" ;
							$highlight_view = "" ;
							$highlight_edit = "" ;
							$highlight_pass = "" ;
							if($this->input->get('edit') == 'true'){ $highlight_edit = 'current' ; 
							}else if($this->input->get('change_pass') == 'true'){$highlight_pass = 'current' ; 
							}else{ $highlight_view = "current" ; }
							
							$o .= "<a href='?' class='account-info-main-tab-view' id='".$highlight_view."'>View</a>" ;
							$o .= "<a href='?edit=true' class='account-info-main-tab-edit' id='".$highlight_edit."'>Edit</a>" ;
							$o .= "<a href='?change_pass=true' class='account-info-main-tab-pass' id='".$highlight_pass."'>Reset Password</a>" ;
						$o .= "</div>" ;
						if($this->input->get('edit') == 'true'){
							$o .= "<div class='account-info-form-body'>" ;
							$o .= "<form method='post' >" ;
								$o .= "<div class='account-info-row'>" ;
									$o .= "<input type='hidden' class='text_input' name='edit_user_info_account_user_id' value='".$account_user_info->user_id."' />" ;
								$o .= "</div>" ;
								$o .= "<div class='account-info-row'>" ;
									$o .= "<label>Firstname</label>" ;
									$o .= "<input type='text' class='text_input' name='edit_user_info_fname' required value='".$account_user_info->fname."' />" ;
								$o .= "</div>" ;
								$o .= "<div class='account-info-row'>" ;
									$o .= "<label>Lastname</label>" ;
									$o .= "<input type='text' class='text_input' name='edit_user_info_lname' required value='".$account_user_info->lname."' />" ;
								$o .= "</div>" ;
								$o .= "<div class='account-info-row'>" ;
									$o .= "<label>Gender</label>" ;
									$select_male = "";
									$select_female = "";
									if($gender == 'male'){ $select_male = "checked" ; }
									else if($gender == 'female'){ $select_female = "checked" ; }
									$o .= "<span>" ;
										$o .= "<input type='radio' name='edit_user_info_gender' ".$select_male." value='male' />" ;
										$o .= "<label>Male</label>" ;
									$o .= "</span>" ;
									$o .= "<span>" ;
										$o .= "<input type='radio' name='edit_user_info_gender' ".$select_female." value='female' />" ;
										$o .= "<label>Female</label>" ;
									$o .= "</span>" ;
								$o .= "</div>" ;
								$o .= "<div class='account-info-row'>" ;
									$o .= "<label>Date Of Birth</label>" ;
									if($date_of_birth != ""){
										$o .= "<label>".$date_of_birth."</label>" ;
									}
									$o .= "<span>" ;
										$o .= "<input type='hidden' name='edit_user_info_prev_dob' value='".$date_of_birth."' />" ;
										$o .= "<select name='edit_user_info_month'><option value='' ".set_select('edit_user_info_month', '0', TRUE).">Month:</option>" ; 
											$o .= generate_options(1,12,'callback_month', 'edit_user_info_month')."</select>" ;
										$o .= "<select name='edit_user_info_day'><option value='' ".set_select('edit_user_info_day', '0', TRUE).">Day:</option>" ;
											$o .= generate_options(1,31,false, 'edit_user_info_day')."</select>" ;
										date_default_timezone_set("GMT") ;
										$o .= "<select name='edit_user_info_year'><option value='' ".set_select('edit_user_info_year', '0', TRUE).">Year:</option>" ;
											$o .= generate_options((date('Y') - 4), 1900, false, 'edit_user_info_year')."</select>" ;
									$o .= "</span>" ;
								$o .= "</div>" ;
								
								$o .= "<div class='account-info-submit'>" ;
									$o .= "<input type='submit' class='btn btn-danger' name='edit_user_info_submit' value='SAVE CHANGES' />" ;
								$o .= "</div>" ;
							$o .= "</form>" ;
							$o .= "</div>" ;
						}else if($this->input->get('change_pass') == 'true'){
							$o .= "<div class='account-info-form-body'>" ;
							$o .= "<div class='account-info-pass-status-message'>" ;
								
								if($this->checkIfPasswordExists($account_user_id) === true ){
									$o .= "<span>This Account already has a password!</span>" ;
								}else{
									/* User record has no password defined. User must have signed up through an alternative registration method
									*/
									$o .= "<span>No Password has been defined for this Account!</span>" ;
								}
								
							$o .= "</div>" ;
							$o .= "<form method='post' class='edit_user_reset_password_submit_form' >" ;
								$o .= "<div class='account-info-row'>" ;
									$o .= "<input type='hidden' class='text_input' name='edit_user_info_account_user_id' value='".$account_user_info->user_id."' />" ;
								$o .= "</div>" ;
								$o .= "<div class='account-info-row'>" ;
									$o .= "<label>New Password</label>" ;
									$o .= "<input type='password' class='text_input' name='edit_user_info_rst_pass' required value='' />" ;
								$o .= "</div>" ;
								$o .= "<div class='account-info-row'>" ;
									$o .= "<label>Re-type Password</label>" ;
									$o .= "<input type='password' class='text_input' name='edit_user_info_rst_vpass' required value='' />" ;
								$o .= "</div>" ;
								$o .= "<div class='account-info-submit'>" ;
									$o .= "<input type='submit' class='btn btn-danger edit_user_reset_password_submit' name='edit_user_reset_password_submit' value='Reset ".$account_user_info->fname."&rsquo;s Password' />" ;
								$o .= "</div>" ;
							$o .= "</form>" ;
							$o .= "</div>" ;
							$o .= "<script>
									$(document).ready(function(){ 
										$('.edit_user_reset_password_submit').click(function(ev){
											if(confirm('Are you sure you want to reset ".$account_user_info->fname."\'s Password?')){
											}
										}) ;
									}) ;
									</script>" ;
						}else{
							$o .= "<div class='account-info-body'>" ;
								$o .= "<div class='account-info-row'>" ;
									$o .= "<label>User Name</label>" ;
									$o .= "<span>".$acc_user_name."</span>" ;
								$o .= "</div>" ;
								$o .= "<div class='account-info-row'>" ;
									$o .= "<label>Email</label>" ;
									$o .= "<span>".$email."</span>" ;
								$o .= "</div>" ;
								$o .= "<div class='account-info-row'>" ;
									$o .= "<label>Gender</label>" ;
									$o .= "<span>".$gender."</span>" ;
								$o .= "</div>" ;
								$o .= "<div class='account-info-row'>" ;
									$o .= "<label>Date Of birth</label>" ;
									if($date_of_birth != ""){
										$o .= "<span>".$date_of_birth."</span>" ;
									}else{
										$o .= "<span>None</span>" ;
									}
								$o .= "</div>" ;
							$o .= "</div>" ;
						}
					$o .= "</div>" ;
					
				}else// end if user_info
				{
					$o .= "<div class='account-info-error-msg_box'>" ;
						$o .= "<span>An Error Occurred while fetching your Account Information!</span>" ;
					$o .= "</div>" ;
				}
				
			}
			
			//Get Editable Accounts
			$editable_user_accounts = array() ;
			if($this->checkUserOptionalAccountStatus($user_id, '1007') === true){
				$editable_user_accounts = array('1003','1005') ;
			}
			if($this->checkUserOptionalAccountStatus($user_id, '1100') === true){
				$editable_user_accounts = array('1007', '1005', '1003') ;
			}
			if($this->user_sessions->getUserPrivilege($user_id) == '10'){
				$editable_user_accounts = array( '1100', '1007', '1005', '1003') ;
			}
			
			
			//Show Optional Account
			$account_user_info = $this->user_sessions->getUserInfo($account_user_id) ;
			if($account_user_info !== false){
				$account_user_fname = $account_user_info->fname ;
				$account_user_lname = $account_user_info->lname ;
				$account_user_fullname = $account_user_fname." ".$account_user_lname ;
				
				$optional_accounts = $this->user_sessions->getOptionalUserAccounts($account_user_id) ;
				if($optional_accounts !== false){
					$optional_account_info 		= $optional_accounts[0] ;
					$optional_account_id 		= $optional_account_info['account_type_id'] ;
					$optional_account_status 	= $optional_account_info['status'] ;
					
					$optional_account_status_name = "" ;
					if($optional_account_status == '1'){
						$optional_account_status_name = "Enabled" ;
					}else if($optional_account_status == '2'){
						$optional_account_status_name = "Suspended" ;
					}
					
					$account_type_name = "" ;
					$account_type_info = $this->user_sessions->getOptionalAccountTypeInfo($optional_account_id) ;
					if($account_type_info !== false){
						$account_type_name = $account_type_info->description ;
					}
					
					if(array_search($optional_account_id, $editable_user_accounts) !== false ){
						//Display Account Info and Edit Options
						$o .= "<div class='account-info-display-box'>" ;
							$o .= "<div class='account-info-main-header'>Edit Special Account Information</div>" ;
							$o .= "<div class='view-list-box-body'>" ;
								$o .= "<div class='view-list-item'>" ;
									$o .= "<span class='view-list-item-inline-label'>User Name: </span>" ;
									$o .= "<span class='view-list-item-inline-value'>".$account_user_fullname."</span>" ;
									$o .= "<input type='hidden' class='change_account_user_identifier' value='".$account_user_id."' />";
								$o .= "</div>" ;
								
								$o .= "<div class='view-list-item-group'>" ;
									$o .= "<div class='view-list-item'>" ;
										$o .= "<span class='view-list-item-inline-label'>Account Type: </span>" ;
										$o .= "<span class='view-list-item-inline-value'>".$account_type_name."</span>" ;
									$o .= "</div>" ;
									
									$o .= "<div class='view-list-item'>" ;
										$o .= "<span class='view-list-item-inline-label'>&nbsp;</span>" ;
										$o .= "<span class='view-list-item-inline-value'>" ;
											$o .= "<select class='change_account_type_input'>" ;
												$o .= "<option value=''>-- Change Account Type --</option>" ;
												foreach($editable_user_accounts as $val){
													$editable_account_type_info = $this->user_sessions->getOptionalAccountTypeInfo($val) ;
													if($editable_account_type_info !== false){
														$editable_account_type_name = $editable_account_type_info->description ;
														$o .= "<option value='".$val."'>".$editable_account_type_name."</option>" ;
													}
												}
											$o .= "</select>" ;									
										$o .= "</span>" ;
									$o .= "</div>" ;
								$o .= "</div>" ;
								
								$o .= "<div class='view-list-item-group'>" ;
									$o .= "<div class='view-list-item'>" ;
										$o .= "<span class='view-list-item-inline-label'>Account Status: </span>" ;
										$o .= "<span class='view-list-item-inline-value'>".$optional_account_status_name."</span>" ;
									$o .= "</div>" ;
									
									$o .= "<div class='view-list-item'>" ;
										$o .= "<span class='view-list-item-inline-label'>&nbsp;</span>" ;
										$o .= "<span class='view-list-item-inline-value'>" ;
											$o .= "<select class='change_account_status_input'>" ;
												$o .= "<option value=''>-- Change Account Status --</option>" ;
												$o .= "<option value='1'>Enable</option>" ;
												$o .= "<option value='2'>Suspend Account</option>" ;
											$o .= "</select>" ;									
										$o .= "</span>" ;
									$o .= "</div>" ;
								$o .= "</div>" ;
								
								$o .= "<div class='view-list-item'>" ;
									$o .= "<span class='view-list-item-inline-label'>&nbsp;</span>" ;
									$o .= "<span class='view-list-item-inline-value'>" ;
										$o .= "<input type='button' class='btn btn-success item-view-button save_special_acc_changes' value='Save Changes' />" ;
										$o .= "<a href='".$this->rootdir."admin/index/news/view_all_accounts' >" ;
											$o .= "<input type='button' class='btn btn-warning item-view-button' value='Cancel' />" ;	
										$o .= "</a>" ;				
									$o .= "</span>" ;
								$o .= "</div>" ;
								
							$o .= "</div>" ;
						$o .= "</div>" ;
						$o .= "
							<script>
								$(document).ready(function() {
									$('.save_special_acc_changes').click(function(){
										var rootdir = '".$this->rootdir."' ;
										var acc_uid = $('.change_account_user_identifier').val() ;
										var type_val = $('.change_account_type_input').val() ;
										var stat_val = $('.change_account_status_input').val() ;
										
										elem = this ;
										
										$(elem).val('Saving...') ;
																				
										$.ajax({ type: 'POST', url: rootdir + 'index.php/admin_xhr/user_info_upd', data:{usr_opt_acc_uid:acc_uid, usr_opt_type_val:type_val, usr_opt_stat_val:stat_val}, dataType:'json'
										}).done(function(response) {
											if(response){
												$(elem).val('Changes Saved!') ;
												location.href = rootdir+'admin/index/news/edit_account_info/".$account_user_id."' + '/rand' + new Date().getTime() ;
											}
										})	
									}) ;
								}) ;
							</script>
						" ;
					}
				}
			}
			
			$o .= "" ;
			
		return $o ;
	}
	
	public function edit_saveAccountInfo(){
		/* Validate Register Form */
		if($this->input->post('edit_user_info_submit') !== false){
			$this->form_validation->set_rules('edit_user_info_account_user_id', 'Account User Id', 	'required');
			$this->form_validation->set_rules('edit_user_info_fname', 	'Firstname', 				'required');
			$this->form_validation->set_rules('edit_user_info_lname', 	'Lastname', 				'required');
			
			if ($this->form_validation->run() == FALSE){
			}else{
				$account_user_id = protect($this->input->post('edit_user_info_account_user_id') ) ;
				
				$fname 			= protect($this->input->post('edit_user_info_fname') ) ;
				$lname 			= protect($this->input->post('edit_user_info_lname') ) ;
				$gender 		= protect($this->input->post('edit_user_info_gender') ) ;
				
				$previous_dob	= protect($this->input->post('edit_user_info_prev_dob') ) ;
				
				$month			= protect($this->input->post('edit_user_info_month') ) ;
				$day			= protect($this->input->post('edit_user_info_day') ) ;
				$year			= protect($this->input->post('edit_user_info_year') ) ;
				
				$full_date_of_birth = $previous_dob ;
				if($month != "" && $day != "" && $year != ""){
					$full_date_of_birth = $month."/".$day."/".$year ;
				}
				
				$user_id = $this->user_sessions->getUserId() ;
				//Get User Privilege
				$user_privilege = $this->user_sessions->getUserPrivilege($user_id) ;
				
				if( ($account_user_id == $user_id) || ($user_privilege == '10') ){
					
					$res = $this->updateUserInfoType1($account_user_id, $fname, $lname, $full_date_of_birth, $gender) ;
					if($res === true){
						return true ;
					}
				}
			}
		}
		return false ;
	}
	public function edit_resetUserAccountPassword(){
		/* Validate Register Form */
		if($this->input->post('edit_user_reset_password_submit') !== false){			
			$this->form_validation->set_rules('edit_user_info_account_user_id', 'Account User ID', 	'required');
			$this->form_validation->set_rules('edit_user_info_rst_pass', 'Password', 			'required|min_length[5]');
			$this->form_validation->set_rules('edit_user_info_rst_vpass', 'Re-type Password', 	'required|min_length[5]|matches[edit_user_info_rst_pass]');
			
			if ($this->form_validation->run() == FALSE){
			}else{
				$account_user_id 	= protect($this->input->post('edit_user_info_account_user_id') ) ;
				if($account_user_id != "" ){
					$new_password 				= protect($this->input->post('edit_user_info_rst_pass') ) ;
					$new_password_verify		= protect($this->input->post('edit_user_info_rst_vpass') ) ;
					if($new_password == $new_password_verify){
						//Reset Password
						
						$user_id = $this->user_sessions->getUserId() ;
						//Get User Privilege
						$user_privilege = $this->user_sessions->getUserPrivilege($user_id) ;
						
						if( ($account_user_id == $user_id) || ($user_privilege == '10') ){
							
							if($this->user_sessions->resetUserPasswordByUserId($account_user_id, $new_password) === true){
								return true ;
							}
							
						}
					}
				}
			}
		}
		return false ;
	}
	
	private function updateUserInfoType1($user_id, $fname, $lname, $dateofbirth, $gender){
		$query = "UPDATE app_user_details SET fname = '$fname', lname = '$lname', dateofbirth = '$dateofbirth', gender = '$gender' WHERE user_id = '$user_id' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	private function checkIfPasswordExists($user_id){
		$query = "SELECT password FROM app_users WHERE user_id = '$user_id' " ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			//password
			$result_obj = $query->row() ;
			@$usr_obj->pass 		= @$result_obj->password ;
			if($usr_obj->pass != ""){
				return true ;
			}
		}
		return false ;
	}
	
	
	public function view_dashboard(){
		$o = "" ;
		
		$user_id = $this->user_sessions->getUserId() ;
		
			$o .= "<div class='news_dashboard_box'>" ;
				$o .= "<div class='news_dashboard_box_section news_dashboard_contributor_info_box'>" ;
					$contributor_info = $this->wms_news->getContributorInfo($user_id) ;
					if($contributor_info !== false){
						$contributors_designation 	= "Contributor" ;
						
						$contributor_name 			= $contributor_info->name ;
						$contributor_biography 		= html_entity_decode($contributor_info->biography) ;
						$contributor_image 			= "" ;
						$contributor_image_id 		= $contributor_info->image_id ;
						if($contributor_image_id != 0){
							$contributor_image = $this->admin_images->getImageImgElement($contributor_image_id, $this->rootdir."images/uploads/") ;
						}
						$contributor_status 		= $contributor_info->status ;
						
						$o .= "<div class='news_d_cntrbtr_info_box'>" ;
							$o .= "<div class='news_d_cntrbtr_info_box_inner'>" ;
								$o .= "<div class='news_d_cntrbtr_info_box_top'>" ;
									$o .= "<div class='news_d_cntrbtr_info_box_title'><span>".$contributors_designation."</span></div>" ;
									if($contributor_status == 0){
										$o .= "<div class='news_d_cntrbtr_info_box_disable_notice'><span>Your Contributor Account is Not Enabled!</span></div>" ;
									}
									$o .= "<div class='news_d_cntrbtr_info_box_name'><span>".$contributor_name."</span></div>" ;
								$o .= "</div>" ;
								$o .= "<div class='news_d_cntrbtr_info_box_body'>" ;
									$o .= "<div class='news_d_cntrbtr_info_box_image'>".$contributor_image."</div>" ;
									$o .= "<span class='news_d_cntrbtr_info_box_text'>".$contributor_biography."</span>" ;
								$o .= "</div>" ;
							$o .= "</div>" ;
						$o .= "</div>" ;
					}
				$o .= "</div>" ;
				$o .= "<div class='news_dashboard_box_section news_dashboard_articles_info_box'>" ;
					
				$o .= "</div>" ;
				
				//USER INFO
				$o .= "<div class='news_dashboard_box_section news_dashboard_user_info_box'>" ;
				
				$account_user_id = $user_id ;
				
				$account_user_info = $this->user_sessions->getUserInfo($account_user_id) ;
								
				$account_user_email = $this->user_sessions->getUserEmail($account_user_id) ;
				if( ($account_user_info !== false) ){
					
					$acc_user_name 	= $account_user_info->fname." ".$account_user_info->lname ;
										
					$email 			= $account_user_email;
										
					$gender			= $account_user_info->gender ;
					
					$date_of_birth	= $account_user_info->date_of_birth ;
					
					$o .= "<div class='account-info-main-header'>General Account Information</div>" ;
					$o .= "<div class='account-info-body'>" ;
						$o .= "<div class='account-info-row'>" ;
							$o .= "<label>User Name</label>" ;
							$o .= "<span>".$acc_user_name."</span>" ;
						$o .= "</div>" ;
						$o .= "<div class='account-info-row'>" ;
							$o .= "<label>Email</label>" ;
							$o .= "<span>".$email."</span>" ;
						$o .= "</div>" ;
						$o .= "<div class='account-info-row'>" ;
							$o .= "<label>Gender</label>" ;
							$o .= "<span>".$gender."</span>" ;
						$o .= "</div>" ;
						$o .= "<div class='account-info-row'>" ;
							$o .= "<label>Date Of birth</label>" ;
							if($date_of_birth != ""){
								$o .= "<span>".$date_of_birth."</span>" ;
							}else{
								$o .= "<span>None</span>" ;
							}
						$o .= "</div>" ;
					$o .= "</div>" ;
				}
				
			$o .= "</div>" ;
		return $o ;
	}
	
	public function edit_my_contributor_info(){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		$user_id = $this->user_sessions->getUserId() ;
		$permission_result = $this->checkUserOptionalAccountPermission($user_id, 'add_new_article') ;
		if($permission_result === true){
			//Continue
			if($this->validate_n_process_edit_my_contributor_info_form() === true){
				//Show Success Message
				$msg = "Your Contributor Info Was Edited Successfully!" ;
				$o .= $this->prepareSuccessMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_dashboard?form_success=edit_my_contributor_info") ;
				
				//Show Form Again if requested
				if($this->input->post('edit_my_contributor_info_submit') !== false){
					//Show Form
					$o .= $this->showForm_edit_my_contributor_info() ;
				}else{
					header("Location:".$this->rootdir."admin/index/".$this->menu_selection."/view_dashboard?form_success=edit_my_contributor_info") ;
				}
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_dashboard?form_error=edit_my_contributor_info") ;
				}
				$o .= $this->showForm_edit_my_contributor_info() ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_dashboard?form_error=edit_my_contributor_info") ;
		}
		return $o ;
	}
	private function validate_n_process_edit_my_contributor_info_form(){
		if( ($this->input->post('edit_my_contributor_info_submit') !== false) || ($this->input->post('edit_my_contributor_info_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('edit_my_contributor_info_status', 'Contributor Account Status', 'required');
			$this->form_validation->set_rules('edit_my_contributor_info_name', 'Contributor Name', 'required');
			$this->form_validation->set_rules('edit_my_contributor_info_biography', 'Contributor Biography', 'required');
			
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$new_contributor_status		= protect($this->input->post('edit_my_contributor_info_status')) ;
				$new_contributor_name 		= protect($this->input->post('edit_my_contributor_info_name')) ;
				$new_contributor_biography 	= protect($this->input->post('edit_my_contributor_info_biography')) ;
				
				$new_image_string 			= protect($this->input->post('edit_my_contributor_info_image')) ;
				
				$new_image_id	=	$this->wms_news->getCoverImageIDFromImageString($new_image_string) ;
				
				$user_id = $this->user_sessions->getUserId() ;
				
				// Edit Contributor Info
				$res = $this->wms_news->editContributorInfo($user_id, $new_contributor_name, $new_contributor_biography, $new_image_id, $new_contributor_status) ;
				if($res === true){
					return true;
				}else{
					return false;
				}
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_edit_my_contributor_info(){			
		$user_id = $this->user_sessions->getuserId() ;
		
		$user_info = $this->user_sessions->getUserInfo($user_id) ;
		if($user_info !== false){
			$user_name = $user_info->fname." ".$user_info->lname ;
			
		}
		
		$contributor_name 		= "" ;
		$contributor_biography 	= "" ;
		$contributor_image		= "" ;
		$contributor_status 	= 0 ;
		
		$contributor_info = $this->wms_news->getContributorInfo($user_id) ;
		if($contributor_info !== false){
			$contributor_name 		= $contributor_info->name ;
			$contributor_biography 	= html_entity_decode($contributor_info->biography) ;
			$contributor_image_id = $contributor_info->image_id ;
			if($contributor_image_id != 0){
				$contributor_image = $this->admin_images->getImageImgElement($contributor_image_id, $this->rootdir."images/uploads/") ;
			}
			$contributor_status 	= $contributor_info->status ;
		}
		
		$checked_enabled 	= "" ;
		$checked_disabled 	= "" ;
		
		$contributor_status_name = "" ;
		if($contributor_status == 1){
			$contributor_status_name = 'Enabled' ;
			$checked_enabled = "checked" ;
		}else if($contributor_status == 0){
			$contributor_status_name = 'Disabled' ;
			$checked_disabled = "checked" ;
		}else{
			$contributor_status_name = 'Disabled' ;
			$checked_disabled = "checked" ;
		}
		
		
		//FIELDS
		$form_fields_html = array() ;
			
			//Contributor Status
			$field = $this->admin_forms->getInputCustom("text", "Contributor Status:", "", "long_input", "", "Contributor Status", "disabled", "", $contributor_status_name, false) ;
			array_push($form_fields_html, $field) ;
			
			$field = "" ;
			$field .= "<div class='block_auto'>" ;
				$field .= "<input type='radio' name='edit_my_contributor_info_status' id='contributor_enable_status' class='block_left' value='1' ".$checked_enabled." />" ;
				$field .= "<label for='contributor_enable_status' >Enabled</label>" ;
			$field .= "</div>" ;
			
			$field .= "<div class='block_auto'>" ;
				$field .= "<input type='radio' name='edit_my_contributor_info_status' id='contributor_disable_status' class='block_left' value='0' ".$checked_disabled." />" ;
				$field .= "<label for='contributor_disable_status' >Disabled</label>" ;
			$field .= "</div>" ;
			
			$field .= "<hr/>" ;
			
			array_push($form_fields_html, $field) ;
			
			//Contributor Name
			$field = $this->admin_forms->getInputCustom("text", "Contributor Name", "edit_my_contributor_info_name", "long_input", "", "Enter Contributor Name", "required", "", $contributor_name, false) ;
			array_push($form_fields_html, $field) ;
			
			if($contributor_name == ""){
				$field 	= "" ;
				$field .= "<div class='block_auto'>" ;
				$field .= "<label>Substitute Contributor Name: </label>" ;
				$field .= "<span>".$user_name."</span>" ;
				$field .= "</div>" ;
				array_push($form_fields_html, $field) ;
			}
			
			//Contributor Biography
			$field = "" ;
			$field .= "<label class='control-label' for='edit_my_contributor_info_biography'>Contributor Biography:</label><br/><br/>" ;
			$field .= "<textarea class='form-control txt-big' name='edit_my_contributor_info_biography' id='edit_my_contributor_info_biography' placeholder='Enter the Contributor Biography' required>".$contributor_biography."</textarea>" ;	
			$field .= "<script src='".$this->rootdir."js/ckeditor/ckeditor.js'></script>
							
						<!-- place in body of your html document -->
						<script>							
							CKEDITOR.replace( 'edit_my_contributor_info_biography', {
							
							'extraPlugins': 'imagebrowser',
							'imageBrowser_listUrl': '".$this->rootdir."image_json',
							
							// NOTE: Remember to leave 'toolbar' property with the default value (null).
							});		
						</script>
			" ;
			array_push($form_fields_html, $field) ;
			
			//Cover Image ID
			$field = "<br/><br/><label class='control-label block_auto' style='width:auto' for='edit_my_contributor_info_image'>Contributor Image (Image used must be Uploaded prior to Selection):</label><br/><br/>" ;
			$field .= "<textarea class='form-control txt-big' name='edit_my_contributor_info_image' id='edit_my_contributor_info_image' placeholder='Insert an Image'>".$contributor_image."</textarea>" ;
			$field .= "<script src='".$this->rootdir."js/ckeditor/ckeditor.js'></script>
						<script>
						
						CKEDITOR.replace( 'edit_my_contributor_info_image', {
						
						'extraPlugins': 'imagebrowser',
						'imageBrowser_listUrl': '".$this->rootdir."image_json',
						
						toolbar :
							[
								{ name: 'insert', items : [ 'Image' ] },
							]
					
						// NOTE: Remember to leave 'toolbar' property with the default value (null).
					});		
					</script>
			" ;
			array_push($form_fields_html, $field) ;
			
			
			//Form submit button
			$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "edit_my_contributor_info_submit", "edit_my_contributor_info_submit_and_close", "", "", "submit", "submit", true, $this->rootdir."admin/index/news/view_dashboard", "Cancel") ;
			
		//Get Form HTML	
		$form_html = $this->admin_forms->getRegularForm("Edit My Contributor Information", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		
		return $form_html ;
	}
	
	public function edit_my_profile(){
		
	}
	
	
	
	
	
	public function add_new_category(){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		$user_id = $this->user_sessions->getUserId() ;
		$permission_result = $this->checkUserOptionalAccountPermission($user_id, 'add_new_category') ;
		if($permission_result === true){
			//Continue
			if($this->validate_n_process_add_new_category_form() === true){
				//Show Success Message
				$msg = "The New Category was Added successfully!" ;
				$o .= $this->prepareSuccessMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_all_categories?form_success=add_new_category") ;
				
				//Show Form Again if requested
				if($this->input->post('add_new_category_submit') !== false){
					//Show Form
					$o .= $this->showForm_add_new_category() ;
				}
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_all_categories?form_error=add_new_category") ;
				}
				$o .= $this->showForm_add_new_category() ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_all_categories?form_error=add_new_category") ;
		}
		return $o ;
	}
	private function validate_n_process_add_new_category_form(){
		if( ($this->input->post('add_new_category_submit') !== false) || ($this->input->post('add_new_category_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('add_new_category_name', 'Category Name', 'required');
			$this->form_validation->set_rules('add_new_category_alias', 'Category Alias', 'required');
			$this->form_validation->set_rules('add_new_category_type', 'Category Type', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$new_category_name 			= protect($this->input->post('add_new_category_name')) ;
				$new_category_alias 			= protect($this->input->post('add_new_category_alias')) ;
				$new_category_type_id 		= trim(protect($this->input->post('add_new_category_type')) ) ;
				$new_category_description 	= protect($this->input->post('add_new_category_description')) ;
				
				$user_id = $this->user_sessions->getUserId() ;
				
				// Check If Category Name Exists For this Category Type
				if($this->wms_news->checkIfCategoryNameExistsForCategoryType($new_category_name, $new_category_type_id) === false){
					// Add Category
					$res = $this->wms_news->addCategory($user_id, $new_category_name, $new_category_alias, $new_category_type_id, $new_category_description) ;
					if($res[0] === true){
						return true;
					}else{
						return false;
					}
				}else{
					//The Category Name has already been used for this Category type.
					$this->admin_forms->err .= "The Category Name '".$new_category_name."' has already been used for this Category type!" ;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_add_new_category(){			
		$user_id = $this->user_sessions->getuserId() ;
		
		$category_type_arr = array() ;
		$category_type_info = $this->wms_news->getCategoryTypes() ;
		if($category_type_info !== false){
			for($i = 0; $i < count($category_type_info); $i++){
				$category_type_id = $category_type_info[$i]['category_type_id'] ;
				$category_type_name = $category_type_info[$i]['name'] ;
				$option_array = array( " ".$category_type_id." " => $category_type_name ) ;
				$category_type_arr = array_merge($category_type_arr, $option_array) ;
			}
		}
		
		//FIELDS
		$form_fields_html = array() ;
			//Category Name
			$field = $this->admin_forms->getInputCustom("text", "Category Name", "add_new_category_name", "long_input", "add_new_category_name", "Enter New Category Name", "required", "", "", false) ;
			array_push($form_fields_html, $field) ;
			
			//Category Alias
			$field = $this->admin_forms->getInputCustom("text", "Category Alias", "add_new_category_alias", "long_input", "add_new_category_alias", "Enter New Category Alias", "required readonly", "", "", false) ;
			array_push($form_fields_html, $field) ;
			
			$script = "" ;
			$script .= "<script>" ;
				$script .= "$(document).ready(function() {" ;
					$script .= "$('#add_new_category_name').keyup( function(){" ;
						$script .= "var the_alias = $('#add_new_category_name').val().toLowerCase() ;" ;
						$script .= "the_alias = the_alias.replace(/[^a-zA-Z 0-9]+/g, '');" ;
						$script .= "the_alias = the_alias.replace(/ /g,'_') ;" ;
						$script .= "$('#add_new_category_alias').val(the_alias) ;" ;
					$script .= "});" ;
				$script .= "});" ;
			$script .= "</script>" ;
			array_push($form_fields_html, $script) ;
			
			//Category Type (Select Field)
			$field = $this->admin_forms->getRegularSelect("Category Type", "add_new_category_type", "long_input", "", "", false, array(), $category_type_arr ) ;
			array_push($form_fields_html, $field) ;
			
			//Category Description
			$field = $this->admin_forms->getTextarea("Category Description", "add_new_category_description", "", "", "Enter a description for the Category", "", "", "") ;			
			array_push($form_fields_html, $field) ;
			
			//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "add_new_category_submit", "add_new_category_submit_and_close", "", "", "submit", "submit", true, $this->rootdir."admin/index/news", "Cancel") ;
			
		//Get Form HTML	
		$form_html = $this->admin_forms->getRegularForm("Add A New Category", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		
		return $form_html ;
	}
	
	
	
	
	public function add_new_article(){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		$user_id = $this->user_sessions->getUserId() ;
		$permission_result = $this->checkUserOptionalAccountPermission($user_id, 'add_new_article') ;
		if($permission_result === true){
			//Continue
			if($this->validate_n_process_add_new_article_form() === true){
				//Show Success Message
				$msg = "The New Article was Added successfully!" ;
				$o .= $this->prepareSuccessMessage($msg) ;
				
				//Show Form Again if requested
				if($this->input->post('add_new_article_submit') !== false){
					//Show Form
					$o .= $this->showForm_add_new_article() ;
				}
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg) ;
				}
				$o .= $this->showForm_add_new_article() ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg) ;
		}
		return $o ;
	}
	private function validate_n_process_add_new_article_form(){
		if( ($this->input->post('add_new_article_submit') !== false) || ($this->input->post('add_new_article_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('add_new_article_title', 'Article Name', 'required');
			$this->form_validation->set_rules('add_new_article_intro', 'Article Introductory Text', 'required');
			$this->form_validation->set_rules('add_new_article_body', 'Article Body', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$new_article_title 			= protect($this->input->post('add_new_article_title')) ;
				$new_article_intro 			= protect($this->input->post('add_new_article_intro')) ;
				$new_article_body 			= protect($this->input->post('add_new_article_body')) ;
				$new_article_category_id	= trim(protect($this->input->post('add_new_article_category')) ) ;
				$new_cover_image_string		= protect($this->input->post('add_new_article_cover_image')) ;
				
				$new_cover_image_id 		= $this->wms_news->getCoverImageIDFromImageString($new_cover_image_string) ;
				
				$user_id = $this->user_sessions->getUserId() ;
				
				$new_article_type_id = 1 ;
				
				// Add Article
				$res = $this->wms_news->addArticle($user_id, $new_article_title, $new_article_type_id, $new_article_intro, $new_article_body, $new_article_category_id, $new_cover_image_id) ;
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
	private function showForm_add_new_article(){			
		$user_id = $this->user_sessions->getuserId() ;
		$category_type_arr = array() ;
		
		$category_type_id = 1 ;
		
		$category_arr = array() ;
		$all_category_info = $this->wms_news->getAllArticleCategoriesByCategoryType($category_type_id) ;
		if($all_category_info !== false){
			for($i = 0; $i < count($all_category_info); $i++){
				$category_info = $this->wms_news->getCategoryInfo($all_category_info[$i]['category_id']) ;
				if($category_info !==false){
					$category_id 	= $category_info->category_id ;
					$category_name 	= $category_info->name ;
					$option_array 	= array( " ".$category_id." " => $category_name ) ;
					$category_arr 	= array_merge($category_arr, $option_array) ;
				}
			}
		}
		
		//FIELDS
		$form_fields_html = array() ;
			//Article Title
			$field = $this->admin_forms->getInputCustom("text", "Article Title:", "add_new_article_title", "long_input", "", "Enter New Article Title", "required", "", "", false) ;
			array_push($form_fields_html, $field) ;
			
			//Article Introductory Text
			$maxlen = 150 ;
			$field = "<label class='control-label long_input' for='add_new_article_intro'>Article Introductory Text( maximum length is ".$maxlen." characters):</label><br/><br/>" ;
			$field .= "<textarea class='form-control txt-big' maxlength='".$maxlen."' name='add_new_article_intro' id='' placeholder='Enter the Article Introductory Text' required></textarea>" ;	
			array_push($form_fields_html, $field) ;
			
			//Article Body
			$field = "<label class='control-label' for='add_new_article_body'>Article Body:</label><br/><br/>" ;
			$field .= "<textarea class='form-control txt-big' name='add_new_article_body' id='add_new_article_body' placeholder='Enter the Article Body'></textarea>" ;	
			$field .= "<script src='".$this->rootdir."js/ckeditor/ckeditor.js'></script>
							
						<!-- place in body of your html document -->
						<script>							
							CKEDITOR.replace( 'add_new_article_body', {
							
							'extraPlugins': 'imagebrowser',
							'imageBrowser_listUrl': '".$this->rootdir."image_json',
							
							// NOTE: Remember to leave 'toolbar' property with the default value (null).
							});		
						</script>
			" ;
			array_push($form_fields_html, $field) ;
			
			//Cover Image ID
			$field = "<br/><br/><label class='control-label block_auto' style='width:auto' for='add_new_article_cover_image'>Article Cover Image (Image used must be Uploaded prior to Selection):</label><br/><br/>" ;
			$field .= "<textarea class='form-control txt-big' name='add_new_article_cover_image' id='add_new_article_cover_image' placeholder='Insert a Cover Image for your Article'></textarea>" ;
			$field .= "<script src='".$this->rootdir."js/ckeditor/ckeditor.js'></script>
						<script>
						
						
				/*	    CKEDITOR.replace( 'add_new_article_cover_image' );			*/
						
						CKEDITOR.replace( 'add_new_article_cover_image', {
						
						'extraPlugins': 'imagebrowser',
						'imageBrowser_listUrl': '".$this->rootdir."image_json',
						
						
						toolbar :
							[
								{ name: 'insert', items : [ 'Image' ] },
							]
					
						// NOTE: Remember to leave 'toolbar' property with the default value (null).
					});	
					</script>
			" ;
			array_push($form_fields_html, $field) ;
			
			//Category Type (Select Field)
			$field = "<br/><br/>" ;
			$field .= $this->admin_forms->getRegularSelect("Select Article Category:", "add_new_article_category", "long_input", "", "", true, array(""=>"-- none selected --"), $category_arr ) ;
			array_push($form_fields_html, $field) ;
			
			
			//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "add_new_article_submit", "add_new_article_submit_and_close", "", "", "submit", "submit", true, $this->rootdir."admin/index/news", "Cancel") ;
			
		//Get Form HTML	
		$form_html = $this->admin_forms->getRegularForm("Add A New Article", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		
		return $form_html ;
	}
	
	
	public function add_new_video(){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		$user_id = $this->user_sessions->getUserId() ;
		$permission_result = $this->checkUserOptionalAccountPermission($user_id, 'add_new_article') ;
		if($permission_result === true){
			//Continue
			if($this->validate_n_process_add_new_video_form() === true){
				//Show Success Message
				$msg = "The New Video was Added successfully!" ;
				$o .= $this->prepareSuccessMessage($msg, $this->rootdir."admin/index/news/view_all_videos") ;
				
				//Show Form Again if requested
				if($this->input->post('add_new_video_submit') !== false){
					//Show Form
					$o .= $this->showForm_add_new_video() ;
				}
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg, $this->rootdir."admin/index/news/view_all_videos") ;
				}
				$o .= $this->showForm_add_new_video() ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg, $this->rootdir."admin/index/news/view_all_videos") ;
		}
		return $o ;
	}
	private function validate_n_process_add_new_video_form(){
		if( ($this->input->post('add_new_video_submit') !== false) || ($this->input->post('add_new_video_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('add_new_video_title', 'Video Title', 'required');
			$this->form_validation->set_rules('add_new_video_intro', 'Video Introductory Text', 'required');
			$this->form_validation->set_rules('add_new_video_html_link', 'Video HTML Link', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$new_video_title 			= protect($this->input->post('add_new_video_title')) ;
				$new_video_intro 			= protect($this->input->post('add_new_video_intro')) ;
				$new_video_html_link		= protect($this->input->post('add_new_video_html_link')) ;
				$new_video_category_id	= trim(protect($this->input->post('add_new_video_category')) ) ;
				$new_cover_image_string		= protect($this->input->post('add_new_video_cover_image')) ;
				
				$new_cover_image_id 		= $this->wms_news->getCoverImageIDFromImageString($new_cover_image_string) ;
				
				$user_id = $this->user_sessions->getUserId() ;
				
				$new_video_type_id = 1 ;
				
				// Add Video
				$res = $this->wms_news->addVideo($user_id, $new_video_title, $new_video_type_id, $new_video_intro, $new_video_html_link, $new_video_category_id, $new_cover_image_id) ;
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
	private function showForm_add_new_video(){			
		$user_id = $this->user_sessions->getuserId() ;
		$category_type_arr = array() ;
		
		$category_type_id = 1 ;
		
		$category_arr = array() ;
		$all_category_info = $this->wms_news->getAllArticleCategoriesByCategoryType($category_type_id) ;
		if($all_category_info !== false){
			for($i = 0; $i < count($all_category_info); $i++){
				$category_info = $this->wms_news->getCategoryInfo($all_category_info[$i]['category_id']) ;
				if($category_info !==false){
					$category_id 	= $category_info->category_id ;
					$category_name 	= $category_info->name ;
					$option_array 	= array( " ".$category_id." " => $category_name ) ;
					$category_arr 	= array_merge($category_arr, $option_array) ;
				}
			}
		}
		
		//FIELDS
		$form_fields_html = array() ;
			//Video Title
			$field = $this->admin_forms->getInputCustom("text", "Video Title:", "add_new_video_title", "long_input", "", "Enter New Video Title", "required", "", "", false) ;
			array_push($form_fields_html, $field) ;
			
			//Video Introductory Text
			$maxlen = 150 ;
			$field = "<label class='control-label long_input' for='add_new_video_intro'>Video Introductory Text( maximum length is ".$maxlen." characters):</label><br/><br/>" ;
			$field .= "<textarea class='form-control txt-big' maxlength='".$maxlen."' name='add_new_video_intro' id='' placeholder='Enter the Video Introductory Text' required></textarea>" ;	
			array_push($form_fields_html, $field) ;
			
			//Video HTML LINK
			$field = "<label class='control-label' for='add_new_video_html_link'>Video HTML Link:</label><br/><br/>" ;
			$field .= "<textarea class='form-control txt-big' name='add_new_video_html_link' id='add_new_video_html_link' placeholder='Enter the Video HTML Link'></textarea>" ;	
			array_push($form_fields_html, $field) ;
			
			//Cover Image ID
			$field = "<br/><br/><label class='control-label block_auto' style='width:auto' for='add_new_video_cover_image'>Video Cover Image (Image used must be Uploaded prior to Selection):</label><br/><br/>" ;
			$field .= "<textarea class='form-control txt-big' name='add_new_video_cover_image' id='add_new_video_cover_image' placeholder='Insert a Cover Image for your Video'></textarea>" ;
			$field .= "<script src='".$this->rootdir."js/ckeditor/ckeditor.js'></script>
						<script>
				/*	    CKEDITOR.replace( 'add_new_video_cover_image' );			*/
						
						CKEDITOR.replace( 'add_new_video_cover_image', {
						
						'extraPlugins': 'imagebrowser',
						'imageBrowser_listUrl': '".$this->rootdir."image_json',
						
						toolbar :
							[
								{ name: 'insert', items : [ 'Image' ] },
							]
					
						// NOTE: Remember to leave 'toolbar' property with the default value (null).
					});		
					</script>
			" ;
			array_push($form_fields_html, $field) ;
			
			//Category Type (Select Field)
			$field = "<br/><br/>" ;
			$field .= $this->admin_forms->getRegularSelect("Select Video Category:", "add_new_video_category", "long_input", "", "", true, array(""=>"-- none selected --"), $category_arr ) ;
			array_push($form_fields_html, $field) ;
			
			
			//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "add_new_video_submit", "add_new_video_submit_and_close", "", "", "submit", "submit", true, $this->rootdir."admin/index/news", "Cancel") ;
			
		//Get Form HTML	
		$form_html = $this->admin_forms->getRegularForm("Add A New Video", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		
		return $form_html ;
	}
	
	
	public function add_new_photo_blog(){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		$user_id = $this->user_sessions->getUserId() ;
		$permission_result = $this->checkUserOptionalAccountPermission($user_id, 'add_new_article') ;
		if($permission_result === true){
			//Continue
			if($this->validate_n_process_add_new_photo_blog_form() === true){
				//Show Success Message
				$msg = "The New Photo Blog Post was Added successfully!" ;
				$o .= $this->prepareSuccessMessage($msg, $this->rootdir."admin/index/news/view_all_photo_blog") ;
				
				//Show Form Again if requested
				if($this->input->post('add_new_photo_blog_submit') !== false){
					//Show Form
					$o .= $this->showForm_add_new_photo_blog() ;
				}
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg, $this->rootdir."admin/index/news/view_all_photo_blog") ;
				}
				$o .= $this->showForm_add_new_photo_blog() ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg, $this->rootdir."admin/index/news/view_all_photo_blog") ;
		}
		return $o ;
	}
	private function validate_n_process_add_new_photo_blog_form(){
		if( ($this->input->post('add_new_photo_blog_submit') !== false) || ($this->input->post('add_new_photo_blog_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('add_new_photo_blog_title', 'Post Title', 'required');
			$this->form_validation->set_rules('add_new_photo_blog_intro', 'Post Introductory Text', 'required');
			$this->form_validation->set_rules('add_new_photo_blog_cover_image', 'Post Cover image', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$new_photo_blog_title 			= protect($this->input->post('add_new_photo_blog_title')) ;
				$new_photo_blog_intro 			= protect($this->input->post('add_new_photo_blog_intro')) ;
				$new_photo_blog_body			= "" ;
				$new_photo_blog_category_id		= trim(protect($this->input->post('add_new_photo_blog_category')) ) ;
				$new_cover_image_string			= protect($this->input->post('add_new_photo_blog_cover_image')) ;
				
				$new_cover_image_id 		= $this->wms_news->getCoverImageIDFromImageString($new_cover_image_string) ;
				
				$user_id = $this->user_sessions->getUserId() ;
				
				$new_photo_blog_type_id = 1 ;
				
				// Add Photo Blog Post
				$res = $this->wms_news->addPhotoBlogPost($user_id, $new_photo_blog_title, $new_photo_blog_type_id, $new_photo_blog_intro, $new_photo_blog_body, $new_photo_blog_category_id, $new_cover_image_id) ;
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
	private function showForm_add_new_photo_blog(){			
		$user_id = $this->user_sessions->getuserId() ;
		$category_type_arr = array() ;
		
		$category_type_id = 1 ;
		
		$category_arr = array() ;
		$all_category_info = $this->wms_news->getAllArticleCategoriesByCategoryType($category_type_id) ;
		if($all_category_info !== false){
			for($i = 0; $i < count($all_category_info); $i++){
				$category_info = $this->wms_news->getCategoryInfo($all_category_info[$i]['category_id']) ;
				if($category_info !==false){
					$category_id 	= $category_info->category_id ;
					$category_name 	= $category_info->name ;
					$option_array 	= array( " ".$category_id." " => $category_name ) ;
					$category_arr 	= array_merge($category_arr, $option_array) ;
				}
			}
		}
		
		//FIELDS
		$form_fields_html = array() ;
			//Photo Blog Post Title
			$field = $this->admin_forms->getInputCustom("text", "Post Title:", "add_new_photo_blog_title", "long_input", "", "Enter New Photo Blog Post Title", "required", "", "", false) ;
			array_push($form_fields_html, $field) ;
			
			//Photo Blog Post Introductory Text
			$maxlen = 150 ;
			$field = "<label class='control-label long_input' for='add_new_photo_blog_intro'>Post Introductory Text( maximum length is ".$maxlen." characters):</label><br/><br/>" ;
			$field .= "<textarea class='form-control txt-big' maxlength='".$maxlen."' name='add_new_photo_blog_intro' id='' placeholder='Enter the Photo Blog Post Introductory Text' required></textarea>" ;	
			array_push($form_fields_html, $field) ;
			
			//Cover Image ID
			$field = "<br/><br/><label class='control-label block_auto' style='width:auto' for='add_new_photo_blog_cover_image'>Photo Blog Post Cover Image (Image used must be Uploaded prior to Selection):</label><br/><br/>" ;
			$field .= "<textarea class='form-control txt-big' name='add_new_photo_blog_cover_image' id='add_new_photo_blog_cover_image' placeholder='Insert a Cover Image for your Photo Blog'></textarea>" ;
			$field .= "<script src='".$this->rootdir."js/ckeditor/ckeditor.js'></script>
						<script>
				/*	    CKEDITOR.replace( 'add_new_photo_blog_cover_image' );			*/
						
						CKEDITOR.replace( 'add_new_photo_blog_cover_image', {
						
						'extraPlugins': 'imagebrowser',
						'imageBrowser_listUrl': '".$this->rootdir."image_json',
						
						toolbar :
							[
								{ name: 'insert', items : [ 'Image' ] },
							]
					
						// NOTE: Remember to leave 'toolbar' property with the default value (null).
					});		
					</script>
			" ;
			array_push($form_fields_html, $field) ;
			
			//Category Type (Select Field)
			$field = "<br/><br/>" ;
			$field .= $this->admin_forms->getRegularSelect("Select Photo Blog Post Category:", "add_new_photo_blog_category", "long_input", "", "", true, array(""=>"-- none selected --"), $category_arr ) ;
			array_push($form_fields_html, $field) ;
			
			
			//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "add_new_photo_blog_submit", "add_new_photo_blog_submit_and_close", "", "", "submit", "submit", true, $this->rootdir."admin/index/news", "Cancel") ;
			
		//Get Form HTML	
		$form_html = $this->admin_forms->getRegularForm("Add A New Cartoon/Photo Post", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		
		return $form_html ;
	}
	
	
	public function edit_article_info($article_id, $caller_tag = ""){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		//Allow If User Created The Article OR if User Privilege is Greater than Publisher
		$user_id = $this->user_sessions->getUserId() ;
		$creator_permission_result = $this->wms_news->checkIfAuthorCreatedArticle($user_id, $article_id) ;
		$greater_permission_result = $this->checkUserOptionalAccountPermission($user_id, 'edit_article_info') ;
		if(($creator_permission_result === true) || ($greater_permission_result === true) ){			
			//Continue
			
			$return_page = "view_all_articles" ;
			if($caller_tag == "pub"){ $return_page = "view_all_publisher_articles" ; }
			
			$return_page_url = $this->rootdir."admin/index/".$this->menu_selection."/".$return_page ;
			
			if($this->validate_n_process_edit_article_info_form() === true){
				//Show Success Message
				$msg = "The Article has been successfully Edited!" ;
				$o .= $this->prepareSuccessMessage($msg, $return_page_url) ;
				
				//Show Form Again if requested
				if($this->input->post('edit_article_info_submit') !== false){
					//Show Form
					$o .= $this->showForm_edit_article_info($article_id, $return_page_url) ;
				}
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg, $return_page_url) ;
				}
				$o .= $this->showForm_edit_article_info($article_id, $return_page_url) ;
			}
		}else{
			$return_page = "view_all_articles" ;
			if($caller_tag == "pub"){ $return_page = "view_all_publisher_articles" ; }
			$return_page_url = $this->rootdir."admin/index/".$this->menu_selection."/".$return_page ;
			
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg, $return_page_url) ;
		}
		return $o ;
	}
	private function validate_n_process_edit_article_info_form(){
		if( ($this->input->post('edit_article_info_submit') !== false) || ($this->input->post('edit_article_info_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('edit_article_info_article_id', 'Article ID', 'required');
			$this->form_validation->set_rules('edit_article_info_title', 'Article Name', 'required');
			$this->form_validation->set_rules('edit_article_info_intro', 'Article Introductory Text', 'required');
			$this->form_validation->set_rules('edit_article_info_body', 'Article Body', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$the_article_id 			= protect($this->input->post('edit_article_info_article_id')) ;
				$the_article_title 			= protect($this->input->post('edit_article_info_title')) ;
				$the_article_intro 			= protect($this->input->post('edit_article_info_intro')) ;
				$the_article_body 			= protect($this->input->post('edit_article_info_body')) ;
				$the_cover_image_string		= protect($this->input->post('edit_article_info_cover_image')) ;
				
				$the_cover_image_id 		= $this->wms_news->getCoverImageIDFromImageString($the_cover_image_string) ;
				
				$user_id = $this->user_sessions->getUserId() ;
				
				$the_article_type_id = 1 ;
				
				// Edit Article
				if($this->wms_news->checkIfArticleTitleExistsExclude($the_article_title, $the_article_id) === false){
					$res = $this->wms_news->editArticleInfo($the_article_id, $the_article_type_id, $the_article_title, $the_article_intro, $the_article_body, $the_cover_image_id) ;
					if($res === true){
						return true;
					}else{
						//Store Error
						$this->admin_forms->err .= "An error Occured While Editing The Article '$the_article_title'" ;
					}
				}else{
					//Check Error
					$this->admin_forms->err .= "This article Name '$the_article_title' has been used for another Article!" ;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_edit_article_info($article_id, $return_page_url){
		$form_html = "" ;			
		$user_id = $this->user_sessions->getuserId() ;
		
		$article_info = $this->wms_news->getArticleInfo($article_id) ;
		if($article_info !== false){
			$article_title = $article_info->title ;
			$article_intro = $article_info->intro_text ;
			$article_body = $article_info->full_text ;
			$article_cover_image_id = $article_info->cover_image_id ;
			
			$cover_image_element = "" ;
			$article_cover_image_info = $this->admin_images->getImageInfo($article_cover_image_id) ;
			if($article_cover_image_info !== false){
				$image_filename = $article_cover_image_info->image_filename ;
				$cover_image_element = $this->admin_images->getImageElement($image_filename, $this->rootdir."images/uploads/") ;
			}
			
			//FIELDS
			$form_fields_html = array() ;
			
			
			
				//Article ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_article_info_article_id", "", $article_id) ;
				array_push($form_fields_html, $field) ;
				
				//Article Title
				$field = $this->admin_forms->getInputCustom("text", "Article Title:", "edit_article_info_title", "long_input", "", "Edit Article Title", "required", "", $article_title, false) ;
				array_push($form_fields_html, $field) ;
				
				//Article Introductory Text
				$maxlen = 150 ;
				$field = "" ;
				$field .= "<label class='control-label long_input' for='edit_article_info_intro'>Article Introductory Text (maximum length is ".$maxlen." characters):</label><br/><br/>" ;
				$field .= "<textarea class='form-control txt-big' name='edit_article_info_intro' id='' maxlength='".$maxlen."' placeholder='Enter the Article Introductory Text' required>".$article_intro."</textarea>" ;	
				array_push($form_fields_html, $field) ;
				
				//Article Body
				$field = "" ;
				$field .= "<label class='control-label' for='edit_article_info_body'>Article Body:</label><br/><br/>" ;
				$field .= "<textarea class='form-control txt-big' name='edit_article_info_body' id='edit_article_info_body' placeholder='Enter the Article Body' required>".$article_body."</textarea>" ;	
				$field .= "<script src='".$this->rootdir."js/ckeditor/ckeditor.js'></script>
								
						<!-- place in body of your html document -->
						<script>							
							CKEDITOR.replace( 'edit_article_info_body', {
							
							'extraPlugins': 'imagebrowser',
							'imageBrowser_listUrl': '".$this->rootdir."image_json',
							
							// NOTE: Remember to leave 'toolbar' property with the default value (null).
							});		
						</script>
				" ;
				array_push($form_fields_html, $field) ;
				
				//Existing Article Cover Image ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_article_info_existing_cover_id", "", $article_id) ;
				array_push($form_fields_html, $field) ;
				
				//Cover Image ID
				$field = "<br/><br/><label class='control-label block_auto' style='width:auto' for='edit_article_info_cover_image'>Article Cover Image (Image used must be Uploaded prior to Selection):</label><br/><br/>" ;
				$field .= "<textarea class='form-control txt-big' name='edit_article_info_cover_image' id='edit_article_info_cover_image' placeholder='Enter the Article Body' required>".$cover_image_element."</textarea>" ;	
				$field .= "<script src='".$this->rootdir."js/ckeditor/ckeditor.js'></script>
							<script>
					/*	    CKEDITOR.replace( 'edit_article_info_cover_image' );			*/
							
							CKEDITOR.replace( 'edit_article_info_cover_image', {
							
							'extraPlugins': 'imagebrowser',
							'imageBrowser_listUrl': '".$this->rootdir."image_json',
							
							
							toolbar :
								[
									{ name: 'insert', items : [ 'Image' ] },
								]
						
							// NOTE: Remember to leave 'toolbar' property with the default value (null).
						});		
						</script>
				" ;
				array_push($form_fields_html, $field) ;
				
				
				//Form submit button
					$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "edit_article_info_submit", "edit_article_info_submit_and_close", "", "", "submit", "submit", true, $return_page_url, "Cancel") ;
				
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Edit An Article", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		}
		
		return $form_html ;
	}
	
	
	
	public function edit_video_info($video_id, $caller_tag = ""){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		//Allow If User Created The Video OR if User Privilege is Greater than Publisher
		$user_id = $this->user_sessions->getUserId() ;
		$creator_permission_result = $this->wms_news->checkIfAuthorCreatedVideo($user_id, $video_id) ;
		$greater_permission_result = $this->checkUserOptionalAccountPermission($user_id, 'edit_article_info') ;
		if(($creator_permission_result === true) || ($greater_permission_result === true) ){			
			//Continue
			
			$return_page = "view_all_videos" ;
			if($caller_tag == "pub"){ $return_page = "view_all_publisher_videos" ; }
			
			$return_page_url = $this->rootdir."admin/index/".$this->menu_selection."/".$return_page ;
			
			if($this->validate_n_process_edit_video_info_form() === true){
				//Show Success Message
				$msg = "The Video has been successfully Edited!" ;
				$o .= $this->prepareSuccessMessage($msg, $return_page_url) ;
				
				//Show Form Again if requested
				if($this->input->post('edit_video_info_submit') !== false){
					//Show Form
					$o .= $this->showForm_edit_video_info($video_id, $return_page_url) ;
				}
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg, $return_page_url) ;
				}
				$o .= $this->showForm_edit_video_info($video_id, $return_page_url) ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg, $return_page_url) ;
		}
		return $o ;
	}
	private function validate_n_process_edit_video_info_form(){
		if( ($this->input->post('edit_video_info_submit') !== false) || ($this->input->post('edit_video_info_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('edit_video_info_video_id', 'Video ID', 'required');
			$this->form_validation->set_rules('edit_video_info_title', 'Video Title', 'required');
			$this->form_validation->set_rules('edit_video_info_intro', 'Video Introductory Text', 'required');
			$this->form_validation->set_rules('edit_video_info_html_link', 'Video HTML Link', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$the_video_id 			= protect($this->input->post('edit_video_info_video_id')) ;
				$the_video_title 			= protect($this->input->post('edit_video_info_title')) ;
				$the_video_intro 			= protect($this->input->post('edit_video_info_intro')) ;
				$the_video_html_link 			= protect($this->input->post('edit_video_info_html_link')) ;
				$the_cover_image_string		= protect($this->input->post('edit_video_info_cover_image')) ;
				
				$the_cover_image_id 		= $this->wms_news->getCoverImageIDFromImageString($the_cover_image_string) ;
				
				$user_id = $this->user_sessions->getUserId() ;
				
				$the_video_type_id = 1 ;
				
				// Edit Video
				if($this->wms_news->checkIfVideoTitleExistsExclude($the_video_title, $the_video_id) === false){
					$res = $this->wms_news->editVideoInfo($the_video_id, $the_video_type_id, $the_video_title, $the_video_intro, $the_video_html_link, $the_cover_image_id) ;
					if($res === true){
						return true;
					}else{
						//Store Error
						$this->admin_forms->err .= "An error Occured While Editing The Video '$the_video_title'" ;
					}
				}else{
					//Check Error
					$this->admin_forms->err .= "This video Title '$the_video_title' has been used for another Video!" ;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_edit_video_info($video_id, $return_page_url){
		$form_html = "" ;
		$user_id = $this->user_sessions->getuserId() ;
		
		$video_info = $this->wms_news->getVideoInfo($video_id) ;
		if($video_info !== false){
			$video_title 			= $video_info->title ;
			$video_intro 			= $video_info->intro_text ;
			$video_html_link 		= $video_info->html_link ;
			$video_cover_image_id 	= $video_info->cover_image_id ;
			
			$cover_image_element = "" ;
			$video_cover_image_info = $this->admin_images->getImageInfo($video_cover_image_id) ;

			if($video_cover_image_info !== false){
				$image_filename = $video_cover_image_info->image_filename ;
				$cover_image_element = $this->admin_images->getImageElement($image_filename, $this->rootdir."images/uploads/") ;
			}
			
			//FIELDS
			$form_fields_html = array() ;
			
				//Video ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_video_info_video_id", "", $video_id) ;
				array_push($form_fields_html, $field) ;
				
				//Video Title
				$field = $this->admin_forms->getInputCustom("text", "Video Title:", "edit_video_info_title", "long_input", "", "Edit Video Title", "required", "", $video_title, false) ;
				array_push($form_fields_html, $field) ;
				
				//Video Introductory Text
				$maxlen = 150 ;
				$field = "" ;
				$field .= "<label class='control-label long_input' for='edit_video_info_intro'>Video Introductory Text (maximum length is ".$maxlen." characters):</label><br/><br/>" ;
				$field .= "<textarea class='form-control txt-big' name='edit_video_info_intro' id='' maxlength='".$maxlen."' placeholder='Enter the Video Introductory Text' required>".$video_intro."</textarea>" ;	
				array_push($form_fields_html, $field) ;
				
				//Video HTML Link
				$field = "" ;
				$field .= "<label class='control-label' for='edit_video_info_html_link'>Video HTML Link:</label><br/><br/>" ;
				$field .= "<textarea class='form-control txt-big' name='edit_video_info_html_link' id='edit_video_info_html_link' placeholder='Enter the Video HTML Link' required>".$video_html_link."</textarea>" ;	
				array_push($form_fields_html, $field) ;
				
				//Existing Video Cover Image ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_video_info_existing_cover_id", "", $video_id) ;
				array_push($form_fields_html, $field) ;
				
				//Cover Image ID
				$field = "<br/><br/><label class='control-label block_auto' style='width:auto' for='edit_video_info_cover_image'>Video Cover Image (Image used must be Uploaded prior to Selection):</label><br/><br/>" ;
				$field .= "<textarea class='form-control txt-big' name='edit_video_info_cover_image' id='edit_video_info_cover_image' placeholder='Select a Cover Image' required>".$cover_image_element."</textarea>" ;
				$field .= "<script src='".$this->rootdir."js/ckeditor/ckeditor.js'></script>
							<script>
					/*	    CKEDITOR.replace( 'edit_video_info_cover_image' );			*/
							
							CKEDITOR.replace( 'edit_video_info_cover_image', {
							
							'extraPlugins': 'imagebrowser',
							'imageBrowser_listUrl': '".$this->rootdir."image_json',
							
							toolbar :
								[
									{ name: 'insert', items : [ 'Image' ] },
								]
						
							// NOTE: Remember to leave 'toolbar' property with the default value (null).
						});		
						</script>
				" ;
				array_push($form_fields_html, $field) ;
				
				
				//Form submit button
					$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "edit_video_info_submit", "edit_video_info_submit_and_close", "", "", "submit", "submit", true, $return_page_url, "Cancel") ;
			
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Edit A Video", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		}
		return $form_html ;
	}
	
	
	
	
	public function edit_photo_blog_post_info($photo_blog_post_id, $caller_tag = ""){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		//Allow If User Created The Video OR if User Privilege is Greater than Publisher
		$user_id = $this->user_sessions->getUserId() ;
		$creator_permission_result = $this->wms_news->checkIfAuthorCreatedPhotoBlogPost($user_id, $photo_blog_post_id) ;
		$greater_permission_result = $this->checkUserOptionalAccountPermission($user_id, 'edit_article_info') ;
		if(($creator_permission_result === true) || ($greater_permission_result === true) ){			
			//Continue
			
			$return_page = "view_all_photo_blog" ;
			if($caller_tag == "pub"){ $return_page = "view_all_publisher_photo_blog" ; }
			
			$return_page_url = $this->rootdir."admin/index/".$this->menu_selection."/".$return_page ;
			
			if($this->validate_n_process_edit_photo_blog_post_info_form() === true){
				//Show Success Message
				$msg = "The Photo Blog Post has been successfully Edited!" ;
				$o .= $this->prepareSuccessMessage($msg, $return_page_url) ;
				
				//Show Form Again if requested
				if($this->input->post('edit_photo_blog_post_info_submit') !== false){
					//Show Form
					$o .= $this->showForm_edit_photo_blog_post_info($photo_blog_post_id, $return_page_url) ;
				}
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg, $return_page_url) ;
				}
				$o .= $this->showForm_edit_photo_blog_post_info($photo_blog_post_id, $return_page_url) ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg, $return_page_url) ;
		}
		return $o ;
	}
	private function validate_n_process_edit_photo_blog_post_info_form(){
		if( ($this->input->post('edit_photo_blog_post_info_submit') !== false) || ($this->input->post('edit_photo_blog_post_info_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('edit_photo_blog_post_info_post_id', 'Photo Blog Post ID', 'required');
			$this->form_validation->set_rules('edit_photo_blog_post_info_title', 'Photo Blog Post Title', 'required');
			$this->form_validation->set_rules('edit_photo_blog_post_info_intro', 'Photo Blog Post Introductory Text', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$the_photo_blog_post_id 		= protect($this->input->post('edit_photo_blog_post_info_post_id')) ;
				$the_photo_blog_post_title 		= protect($this->input->post('edit_photo_blog_post_info_title')) ;
				$the_photo_blog_post_intro 		= protect($this->input->post('edit_photo_blog_post_info_intro')) ;
				$the_photo_blog_post_body 		= "" ;
				$the_cover_image_string			= protect($this->input->post('edit_photo_blog_post_info_cover_image')) ;
				
				$the_cover_image_id 			= $this->wms_news->getCoverImageIDFromImageString($the_cover_image_string) ;
				
				$user_id = $this->user_sessions->getUserId() ;
				
				$the_photo_blog_post_type_id = 1 ;
				
				// Edit Photo Blog Post
				if($this->wms_news->checkIfPhotoBlogPostTitleExistsExclude($the_photo_blog_post_title, $the_photo_blog_post_id) === false){
					$res = $this->wms_news->editPhotoBlogPostInfo($the_photo_blog_post_id, $the_photo_blog_post_type_id, $the_photo_blog_post_title, $the_photo_blog_post_intro, $the_photo_blog_post_body, $the_cover_image_id) ;
					if($res === true){
						return true;
					}else{
						//Store Error
						$this->admin_forms->err .= "An error Occured While Editing The Photo Blog Post '$the_photo_blog_post_title'" ;
					}
				}else{
					//Check Error
					$this->admin_forms->err .= "This Post Title '$the_photo_blog_post_title' has been used for another Photo blog Post!" ;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_edit_photo_blog_post_info($photo_blog_post_id, $return_page_url){
		$form_html = "" ;
		$user_id = $this->user_sessions->getuserId() ;
		
		$photo_blog_post_info = $this->wms_news->getPhotoBlogPostInfo($photo_blog_post_id) ;
		if($photo_blog_post_info !== false){
			$photo_blog_post_title 				= $photo_blog_post_info->title ;
			$photo_blog_post_intro 				= $photo_blog_post_info->intro_text ;
			$photo_blog_post_body 				= $photo_blog_post_info->full_text ;
			$photo_blog_post_cover_image_id 	= $photo_blog_post_info->cover_image_id ;
			
			$cover_image_element = "" ;
			$photo_blog_post_cover_image_info = $this->admin_images->getImageInfo($photo_blog_post_cover_image_id) ;

			if($photo_blog_post_cover_image_info !== false){
				$image_filename = $photo_blog_post_cover_image_info->image_filename ;
				$cover_image_element = $this->admin_images->getImageElement($image_filename, $this->rootdir."images/uploads/") ;
			}
			
			//FIELDS
			$form_fields_html = array() ;
			
				//Post ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_photo_blog_post_info_post_id", "", $photo_blog_post_id) ;
				array_push($form_fields_html, $field) ;
				
				//Post Title
				$field = $this->admin_forms->getInputCustom("text", "Post Title:", "edit_photo_blog_post_info_title", "long_input", "", "Edit Post Title", "required", "", $photo_blog_post_title, false) ;
				array_push($form_fields_html, $field) ;
				
				//Post Introductory Text
				$maxlen = 150 ;
				$field = "" ;
				$field .= "<label class='control-label long_input' for='edit_photo_blog_post_info_intro'>Photo Blog Post Introductory Text (maximum length is ".$maxlen." characters):</label><br/><br/>" ;
				$field .= "<textarea class='form-control txt-big' name='edit_photo_blog_post_info_intro' id='' maxlength='".$maxlen."' placeholder='Enter the Photo Blog Post Introductory Text' required>".$photo_blog_post_intro."</textarea>" ;	
				array_push($form_fields_html, $field) ;
				
				
				//Existing Video Cover Image ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_photo_blog_post_info_existing_cover_id", "", $photo_blog_post_id) ;
				array_push($form_fields_html, $field) ;
				
				//Cover Image ID
				$field = "<br/><br/><label class='control-label block_auto' style='width:auto' for='edit_photo_blog_post_info_cover_image'>Photo Blog Post Cover Image (Image used must be Uploaded prior to Selection):</label><br/><br/>" ;
				$field .= "<textarea class='form-control txt-big' name='edit_photo_blog_post_info_cover_image' id='edit_photo_blog_post_info_cover_image' placeholder='Select a Cover Image' required>".$cover_image_element."</textarea>" ;
				$field .= "<script src='".$this->rootdir."js/ckeditor/ckeditor.js'></script>
							<script>
					/*	    CKEDITOR.replace( 'edit_photo_blog_post_info_cover_image' );			*/
							
							CKEDITOR.replace( 'edit_photo_blog_post_info_cover_image', {
							
							'extraPlugins': 'imagebrowser',
							'imageBrowser_listUrl': '".$this->rootdir."image_json',
							
							toolbar :
								[
									{ name: 'insert', items : [ 'Image' ] },
								]
						
							// NOTE: Remember to leave 'toolbar' property with the default value (null).
						});		
						</script>
				" ;
				array_push($form_fields_html, $field) ;
				
				
				//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "edit_photo_blog_post_info_submit", "edit_photo_blog_post_info_submit_and_close", "", "", "submit", "submit", true, $return_page_url, "Cancel") ;
			
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Edit A Cartoon Post", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		}
		return $form_html ;
	}
	
	
	
	
	public function delete_an_article($article_id){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		//Allow If User Created The Article OR if User Privilege is Super Administrator
		$user_id = $this->user_sessions->getUserId() ;
		$creator_permission_result = $this->wms_news->checkIfAuthorCreatedArticle($user_id, $article_id) ;
		$greater_permission_result = $this->checkUserOptionalAccountPermission($user_id, 'delete_an_article') ;
		if(($creator_permission_result === true) || ($greater_permission_result === true) ){			
			//Continue
			if($this->validate_n_process_delete_an_article_form() === true){
				//Show Success Message
				$msg = "The Article has been successfully Deleted!" ;
				$o .= $this->prepareSuccessMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_all_articles") ;
				
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg) ;
				}
				$o .= $this->showForm_delete_an_article($article_id) ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg) ;
		}
		return $o ;
	}
	private function validate_n_process_delete_an_article_form(){
		if($this->input->post('delete_an_article_submit') !== false ){
			
			$this->form_validation->set_rules('delete_an_article_article_id', 'Article ID', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$del_article_id 			= protect($this->input->post('delete_an_article_article_id')) ;
				$del_article_title 			= protect($this->input->post('delete_an_article_title')) ;
				
				$user_id = $this->user_sessions->getUserId() ;
				
				// Delete Article
				$res = $this->wms_news->deleteArticle($user_id, $del_article_id ) ;
				if($res === true){
					return true;
				}else{
					//Store Error
					$this->admin_forms->err .= "An error Occured While Deleting The Article '".$del_article_title."'" ;
					return false;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_delete_an_article($article_id){
		$form_html = "" ;			
		$user_id = $this->user_sessions->getuserId() ;
		
		$article_info = $this->wms_news->getArticleInfo($article_id) ;
		if($article_info !== false){
			$article_title = $article_info->title ;
			
			//FIELDS
			$form_fields_html = array() ;
			
				//Article ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("delete_an_article_article_id", "", $article_id) ;
				array_push($form_fields_html, $field) ;
				
				//Article Title (hidden Field)
				$field = $this->admin_forms->getInputHidden("delete_an_article_title", "", $article_id) ;
				array_push($form_fields_html, $field) ;
				
				//Article Title
				$field = $this->admin_forms->getInputCustom("text", "Article Title:", "", "long_input", "", "", "disabled", "", $article_title, false) ;
				array_push($form_fields_html, $field) ;
				
				$field = "Are You sure you want to delete the Article '".$article_title."'?" ;
				array_push($form_fields_html, $field) ;
					
				//Form submit button
					$submit_field = $this->admin_forms->getDeleteButtonField("Delete Article", "delete_an_article_submit", "", "submit", true, $this->rootdir."admin/index/news/view_all_articles",  "Cancel") ;
				
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Delete An Article", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		}
		
		return $form_html ;
	}
	
	
	
	public function delete_a_video($video_id){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		//Allow If User Created The Video OR if User Privilege is Super Administrator
		$user_id = $this->user_sessions->getUserId() ;
		$creator_permission_result = $this->wms_news->checkIfAuthorCreatedVideo($user_id, $video_id) ;
		$greater_permission_result = $this->checkUserOptionalAccountPermission($user_id, 'delete_an_article') ;
		if(($creator_permission_result === true) || ($greater_permission_result === true) ){			
			//Continue
			if($this->validate_n_process_delete_a_video_form() === true){
				//Show Success Message
				$msg = "The Video has been successfully Deleted!" ;
				$o .= $this->prepareSuccessMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_all_videos") ;
				
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_all_videos") ;
				}
				$o .= $this->showForm_delete_a_video($video_id) ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_all_videos") ;
		}
		return $o ;
	}
	private function validate_n_process_delete_a_video_form(){
		if($this->input->post('delete_a_video_submit') !== false ){
			
			$this->form_validation->set_rules('delete_a_video_video_id', 'Video ID', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$del_video_id 			= protect($this->input->post('delete_a_video_video_id')) ;
				$del_video_title 			= protect($this->input->post('delete_a_video_title')) ;
				
				$user_id = $this->user_sessions->getUserId() ;
				
				// Delete Video
				$res = $this->wms_news->deleteVideo($user_id, $del_video_id ) ;
				if($res === true){
					return true;
				}else{
					//Store Error
					$this->admin_forms->err .= "An error Occured While Deleting The Video '".$del_video_title."'" ;
					return false;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_delete_a_video($video_id){
		$form_html = "" ;			
		$user_id = $this->user_sessions->getuserId() ;
		
		$video_info = $this->wms_news->getVideoInfo($video_id) ;
		if($video_info !== false){
			$video_title = $video_info->title ;
			
			//FIELDS
			$form_fields_html = array() ;
			
				//Video ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("delete_a_video_video_id", "", $video_id) ;
				array_push($form_fields_html, $field) ;
				
				//Video Title (hidden Field)
				$field = $this->admin_forms->getInputHidden("delete_a_video_title", "", $video_id) ;
				array_push($form_fields_html, $field) ;
				
				//Video Title
				$field = $this->admin_forms->getInputCustom("text", "Video Title:", "", "long_input", "", "", "disabled", "", $video_title, false) ;
				array_push($form_fields_html, $field) ;
				
				$field = "Are You sure you want to delete the Video '".$video_title."'?" ;
				array_push($form_fields_html, $field) ;
					
				//Form submit button
					$submit_field = $this->admin_forms->getDeleteButtonField("Delete Video", "delete_a_video_submit", "", "submit", true, $this->rootdir."admin/index/news/view_all_videos",  "Cancel") ;
				
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Delete A Video", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		}
		
		return $form_html ;
	}
	
	
	
	public function delete_a_photo_blog_post($photo_blog_post_id){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		//Allow If User Created The Photo Blog Post OR if User Privilege is Super Administrator
		$user_id = $this->user_sessions->getUserId() ;
		$creator_permission_result = $this->wms_news->checkIfAuthorCreatedPhotoBlogPost($user_id, $photo_blog_post_id) ;
		$greater_permission_result = $this->checkUserOptionalAccountPermission($user_id, 'delete_an_article') ;
		if(($creator_permission_result === true) || ($greater_permission_result === true) ){			
			//Continue
			if($this->validate_n_process_delete_a_photo_blog_post_form() === true){
				//Show Success Message
				$msg = "The Photo Blog Post has been successfully Deleted!" ;
				$o .= $this->prepareSuccessMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_all_photo_blog") ;
				
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_all_photo_blog") ;
				}
				$o .= $this->showForm_delete_a_photo_blog_post($photo_blog_post_id) ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_all_photo_blog") ;
		}
		return $o ;
	}
	private function validate_n_process_delete_a_photo_blog_post_form(){
		if($this->input->post('delete_a_photo_blog_post_submit') !== false ){
			
			$this->form_validation->set_rules('delete_a_photo_blog_post_id', 'Post ID', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$del_photo_blog_post_id 			= protect($this->input->post('delete_a_photo_blog_post_id')) ;
				$del_photo_blog_post_title 			= protect($this->input->post('delete_a_photo_blog_post_title')) ;
				
				$user_id = $this->user_sessions->getUserId() ;
				
				// Delete Photo Blog Post
				$res = $this->wms_news->deletePhotoBlogPost($user_id, $del_photo_blog_post_id ) ;
				if($res === true){
					return true;
				}else{
					//Store Error
					$this->admin_forms->err .= "An error Occured While Deleting The Photo Blog Post '".$del_photo_blog_post_title."'" ;
					return false;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_delete_a_photo_blog_post($photo_blog_post_id){
		$form_html = "" ;			
		$user_id = $this->user_sessions->getuserId() ;
		
		$photo_blog_post_info = $this->wms_news->getPhotoBlogPostInfo($photo_blog_post_id) ;
		if($photo_blog_post_info !== false){
			$photo_blog_post_title = $photo_blog_post_info->title ;
			
			//FIELDS
			$form_fields_html = array() ;
			
				//Photo Blog Post ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("delete_a_photo_blog_post_id", "", $photo_blog_post_id) ;
				array_push($form_fields_html, $field) ;
				
				//Post Title (hidden Field)
				$field = $this->admin_forms->getInputHidden("delete_a_photo_blog_post_title", "", $photo_blog_post_id) ;
				array_push($form_fields_html, $field) ;
				
				//Post Title
				$field = $this->admin_forms->getInputCustom("text", "Post Title:", "", "long_input", "", "", "disabled", "", $photo_blog_post_title, false) ;
				array_push($form_fields_html, $field) ;
				
				$field = "Are You sure you want to delete the Photo Blog Post '".$photo_blog_post_title."'?" ;
				array_push($form_fields_html, $field) ;
					
				//Form submit button
					$submit_field = $this->admin_forms->getDeleteButtonField("Delete Photo Blog Post", "delete_a_photo_blog_post_submit", "", "submit", true, $this->rootdir."admin/index/news/view_all_photo_blog",  "Cancel") ;
				
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Delete A Photo Blog Post", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		}
		
		return $form_html ;
	}
	
	
	
	
	public function view_all_articles($page_number = "", $no_of_items = "", $sort_by = "", $display_type = ""){	
		$o = "" ;
		
			$user_id = $this->user_sessions->getUserId() ;
			
			$rootdir 						= $this->rootdir;
			$this_uri 						= "admin/index/news/view_all_articles/" ;
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
					$o .= "<span>View All Articles</span>" ;
				$o .= "</div>" ;
				$o .= "<div class='view-items-display block_left'>" ;
					$o .= "<span>Display:</span>" ;
					$disp_ = ""; $disp_1 = ""; $disp_2 = ""; $disp_0 = "";
					switch($items_display_type){
						case '' : 	$disp_ 		= "current"; break ;
						case '1' : 	$disp_1 	= "current"; break ;
						case '2' : 	$disp_2 	= "current"; break ;
						case '0' : 	$disp_0 	= "current"; break ;
					}
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '')."'  class='".$disp_."' >All</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '1')."' class='".$disp_1."' >Published</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '2')."' class='".$disp_2."' >Pending</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '0')."' class='".$disp_0."' >Suspended</a>" ;
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
					$this->getSortByOptionsValue('iii', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Publish Status',
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
				
				$order = $this->getArticleSortByValue($sort_items_by) ;
				$start_limit = $no_of_items_to_show * $limit_start_no ;
				$limit = "LIMIT ".$start_limit.",".$no_of_items_to_show ;
				
				$extra_where_clause = "AND publish_status != 9" ;
				if($items_display_type != ''){
					$extra_where_clause = "AND publish_status = ".$display_type."" ;
				}
				$articles = $this->wms_news->getInfoAboutAllArticlesByAuthor($user_id, $extra_where_clause, $order, $limit) ;
				if($articles !== false){
					$table .= "<table class='table table-bordered table-condensed'>" ;
					$table .= "<tr>" ;
						$table .= "<th>No</th>" ;
						$table .= "<th>Title</th>" ;
						$table .= "<th>Status</th>" ;
						$table .= "<th>Categories</th>" ;
						$table .= "<th>Cover Image</th>" ;
						$table .= "<th>&nbsp;</th>" ;
						$table .= "<th>Options</th>" ;
					$table .= "</tr>" ;
					for($i = 0; $i < count($articles) ; $i++){
						$article_info 			= $articles[$i] ;
						$article_id 			= $article_info->article_id ;
						$article_publish_status = $article_info->publish_status ;
						$article_title 			= $article_info->title ;
						$article_cover_image_preview = "" ;
						$article_cover_image_id = $article_info->cover_image_id ;
						if($article_cover_image_id != ""){
							$article_cover_image_info = $this->admin_images->getImageInfo($article_cover_image_id) ;
							if($article_cover_image_info !== false){
								$article_cover_image_filename 	= $article_cover_image_info->image_filename ;
								$article_cover_image_preview 	= $this->admin_images->previewImage($article_cover_image_filename, $this->rootdir."images/uploads/", '100px', '', "title = '".$article_title."'") ;
							}
						}
						$article_all_category_names = "" ;
						$article_all_category_info	= $this->wms_news->getAllCategoriesInfoForAnArticle($article_id) ;
						if($article_all_category_info !== false){
							for($j = 0; $j < count($article_all_category_info) ; $j++){
								$this_category_name = $article_all_category_info[$j]->name ;
								$this_category_status = $article_all_category_info[$j]->status ;
								if($this_category_status == 1){
									$article_all_category_names .= $this_category_name ;
									if(($j + 1) < count($article_all_category_info)){
										$article_all_category_names .= " | " ;
									}
								}
							}
						}
						
						$this_article_publish_status_name = "" ;
						if($article_publish_status == 1){
							$this_article_publish_status_name = 'Published' ;
						}else if($article_publish_status == 2){
							$this_article_publish_status_name = 'Pending' ;
						}else if($article_publish_status == 0){
							$this_article_publish_status_name = 'Suspended' ;
						}
							
						
				//		$article_body 			= getFirstXLetters(html_entity_decode($article_info->full_text) ) ;					
						
						//ROW COLUMNS
						$row = "" ;
						$row = "<tr>" ;
							$row .= "<td class='view-article-title'><div>".($i + 1 + ($limit_start_no * $no_of_items_to_show) )."</div>" ;
							$row .= "<td class='view-article-title'><div>".$article_title."</div>" ;
							$row .= "<td class='view-article-title'><div>".$this_article_publish_status_name."</div>" ;
							$row .= "<td class='view-article-title'><div>".$article_all_category_names."</div>" ;
							$row .= "<td class='view-article-title'><div>".$article_cover_image_preview."</div>" ;
							$row .= "<td class='view-article-title'><div>&nbsp;</div>" ;
							$row .= "<td class='view-article-title'><div>";
							
								$row .= "<a class='img-view-item block_left' target='_blank' href='".$rootdir."admin/index/news/view_individual_article/".$article_id."' title='View'></a>" ;
								
								$row .= "<a class='img-edit-item block_left' href='".$rootdir."admin/index/news/edit_article_info/".$article_id."' title='Edit'></a>" ;
								
								
								if($this->user_sessions->getUserPrivilege($user_id) == '10'){
									$row .= "<a class='img-delete-item block_left' href='".$rootdir."admin/index/news/delete_an_article/".$article_id."' title='Delete'></a>" ;
									
								}
								
				/*				if( ($this->checkUserOptionalAccountStatus($user_id, '1005') === true) 
									|| ($this->checkUserOptionalAccountStatus($user_id, '1007') === true)
									|| ($this->checkUserOptionalAccountStatus($user_id, '1100') === true) 
									|| ($this->user_sessions->getUserPrivilege($user_id) == '10')  ){
										*/
									
										$row .= "<a class='img-edit-item-category block_left' href='".$rootdir."admin/index/news/edit_article_to_category/".$article_id."' title='Add/Remove Article From Category'></a>" ;
																			
					//				}
								
							$row .= "</div>" ;
						$row .= "</tr>" ;
						
						$table .= $row ;
						
					}// end for
					$table .= "</table>" ;
				}
			$o .= $table ;
			
			//Get Sub Pages links
				$p = "" ;
				$p .= "<div class='block_auto view-items-subpages' >" ;
					$extra_where_clause = "AND publish_status != 9" ;
					if($items_display_type != ''){
						$extra_where_clause = "AND publish_status = ".$display_type."" ;
					}
					$full_articles = $this->wms_news->getInfoAboutAllArticlesByAuthor($user_id, $extra_where_clause ) ;
					if($full_articles !== false){
						$full_count = count($full_articles) ;
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
	
	public function view_all_videos($page_number = "", $no_of_items = "", $sort_by = "", $display_type = ""){	
		$o = "" ;
		
			$user_id = $this->user_sessions->getUserId() ;
			
			$rootdir 						= $this->rootdir;
			$this_uri 						= "admin/index/news/view_all_videos/" ;
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
					$o .= "<span>View All Videos</span>" ;
				$o .= "</div>" ;
				$o .= "<div class='view-items-display block_left'>" ;
					$o .= "<span>Display:</span>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '')."' >All</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '1')."' >Published</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '2')."' >Pending</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '0')."' >Suspended</a>" ;
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
						$this->getLimitOptionsValue(100, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 100,
						$this->getLimitOptionsValue(200, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 200
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
					$this->getSortByOptionsValue('iii', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Publish Status',
					$this->getSortByOptionsValue('iv', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Time (New - Old)',
					$this->getSortByOptionsValue('v', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Time (Old - New)',
					) ;
					
					$selected_value = $this->getSortByOptionsValue($sort_items_by, $rootdir, $this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) ;
					$js = "onchange='location = this.value;'" ;
					$o .= form_dropdown('selected_sort_by', $all_options, $selected_value, $js );
				
				$o .= "</div>" ;
			$o .= "</div>" ;

			//Get Videos Table
			$table = "" ;
				
				$order = $this->getArticleSortByValue($sort_items_by) ;
				$start_limit = $no_of_items_to_show * $limit_start_no ;
				$limit = "LIMIT ".$start_limit.",".$no_of_items_to_show ;
				
				$extra_where_clause = "AND publish_status != 9" ;
				if($items_display_type != ''){
					$extra_where_clause = "AND publish_status = ".$display_type."" ;
				}
				
				$videos = $this->wms_news->getInfoAboutAllVideosByAuthor($user_id, $extra_where_clause, $order, $limit) ;
				if($videos !== false){
					$table .= "<table class='table table-bordered table-condensed'>" ;
					$table .= "<tr>" ;
						$table .= "<th>No</th>" ;
						$table .= "<th>Title</th>" ;
						$table .= "<th>Status</th>" ;
						$table .= "<th>Categories</th>" ;
						$table .= "<th>Cover Image</th>" ;
						$table .= "<th>&nbsp;</th>" ;
						$table .= "<th>Options</th>" ;
					$table .= "</tr>" ;
					for($i = 0; $i < count($videos) ; $i++){
						$video_info 			= $videos[$i] ;
						$video_id 				= $video_info->video_id ;
						$video_publish_status 	= $video_info->publish_status ;
						$video_title 			= $video_info->title ;
						$video_cover_image_preview = "" ;
						$video_cover_image_id = $video_info->cover_image_id ;
						if($video_cover_image_id != ""){
							$video_cover_image_info = $this->admin_images->getImageInfo($video_cover_image_id) ;
							if($video_cover_image_info !== false){
								$video_cover_image_filename 	= $video_cover_image_info->image_filename ;
								$video_cover_image_preview 	= $this->admin_images->previewImage($video_cover_image_filename, $this->rootdir."images/uploads/", '100px', '', "title = '".$video_title."'") ;
							}
						}
						$video_all_category_names 	= "" ;
						$video_all_category_info	= $this->wms_news->getAllCategoriesInfoForAVideo($video_id) ;
						if($video_all_category_info !== false){
							for($j = 0; $j < count($video_all_category_info) ; $j++){
								$this_category_name 	= $video_all_category_info[$j]->name ;
								$this_category_status 	= $video_all_category_info[$j]->status ;
								if($this_category_status == 1){
									$video_all_category_names .= $this_category_name ;
									if(($j + 1) < count($video_all_category_info)){
										$video_all_category_names .= " | " ;
									}
								}
							}
						}
						
						$this_video_publish_status_name = "" ;
						if($video_publish_status == 1){
							$this_video_publish_status_name = 'Published' ;
						}else if($video_publish_status == 2){
							$this_video_publish_status_name = 'Pending' ;
						}else if($video_publish_status == 0){
							$this_video_publish_status_name = 'Suspended' ;
						}
							
						
				//		$video_body 			= getFirstXLetters(html_entity_decode($video_info->full_text) ) ;					
						
						//ROW COLUMNS
						$row = "" ;
						$row = "<tr>" ;
							$row .= "<td class='view-item-title'><div>".($i + 1 + ($limit_start_no * $no_of_items_to_show) )."</div>" ;
							$row .= "<td class='view-item-title'><div>".$video_title."</div>" ;
							$row .= "<td class='view-item-title'><div>".$this_video_publish_status_name."</div>" ;
							$row .= "<td class='view-item-title'><div>".$video_all_category_names."</div>" ;
							$row .= "<td class='view-item-title'><div>".$video_cover_image_preview."</div>" ;
							$row .= "<td class='view-item-title'><div>&nbsp;</div>" ;
							$row .= "<td class='view-item-title'><div>";
							
								$row .= "<a class='img-view-item img-view-play-item block_left' target='_blank' href='".$rootdir."admin/index/news/view_individual_video/".$video_id."' title='View'></a>" ;
								
								$row .= "<a class='img-edit-item block_left' href='".$rootdir."admin/index/news/edit_video_info/".$video_id."' title='Edit'></a>" ;
								
								
								if($this->user_sessions->getUserPrivilege($user_id) == '10'){
									$row .= "<a class='img-delete-item block_left' href='".$rootdir."admin/index/news/delete_a_video/".$video_id."' title='Delete'></a>" ;
									
								}
								
							$row .= "</div>" ;
						$row .= "</tr>" ;
						
						$table .= $row ;
						
					}// end for
					$table .= "</table>" ;
				}
			$o .= $table ;
			
			//Get Sub Pages links
				$p = "" ;
				$p .= "<div class='block_auto view-items-subpages' >" ;
					$extra_where_clause = "AND publish_status != 9" ;
					if($items_display_type != ''){
						$extra_where_clause = "AND publish_status = ".$display_type."" ;
					}
					$full_videos = $this->wms_news->getInfoAboutAllVideosByAuthor($user_id, $extra_where_clause ) ;
					if($full_videos !== false){
						$full_count = count($full_videos) ;
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
	
	public function view_all_photo_blog($page_number = "", $no_of_items = "", $sort_by = "", $display_type = ""){	
		$o = "" ;
		
			$user_id = $this->user_sessions->getUserId() ;
			
			$rootdir 						= $this->rootdir;
			$this_uri 						= "admin/index/news/view_all_photo_blog/" ;
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
					$o .= "<span>View All Cartoon/Photo Posts</span>" ;
				$o .= "</div>" ;
				$o .= "<div class='view-items-display block_left'>" ;
					$o .= "<span>Display:</span>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '')."' >All</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '1')."' >Published</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '2')."' >Pending</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '0')."' >Suspended</a>" ;
				$o .= "</div>" ;
				$o .= "<div class='view-items-limit block_left'>" ;
					$o .= "<span>Show:</span>" ;
				
					$all_options = array() ;
					
					$all_options = array(
						$this->getLimitOptionsValue(30, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 30,
						$this->getLimitOptionsValue(50, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 50,
						$this->getLimitOptionsValue(100, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 100,
						$this->getLimitOptionsValue(200, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 200
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
					$this->getSortByOptionsValue('iii', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Publish Status',
					$this->getSortByOptionsValue('iv', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Time (New - Old)',
					$this->getSortByOptionsValue('v', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Time (Old - New)',
					) ;
					
					$selected_value = $this->getSortByOptionsValue($sort_items_by, $rootdir, $this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) ;
					$js = "onchange='location = this.value;'" ;
					$o .= form_dropdown('selected_sort_by', $all_options, $selected_value, $js );
				
				$o .= "</div>" ;
			$o .= "</div>" ;

			//Get Photo Blog Post Table
			$table = "" ;
				
				$order = $this->getArticleSortByValue($sort_items_by) ;
				$start_limit = $no_of_items_to_show * $limit_start_no ;
				$limit = "LIMIT ".$start_limit.",".$no_of_items_to_show ;
				
				$extra_where_clause = "AND publish_status != 9" ;
				if($items_display_type != ''){
					$extra_where_clause = "AND publish_status = ".$display_type."" ;
				}
				$photo_blog_posts = $this->wms_news->getInfoAboutAllPhotoBlogPostsByAuthor($user_id, $extra_where_clause, $order, $limit) ;
				if($photo_blog_posts !== false){
					$table .= "<table class='table table-bordered table-condensed'>" ;
					$table .= "<tr>" ;
						$table .= "<th>No</th>" ;
						$table .= "<th>Title</th>" ;
						$table .= "<th>Status</th>" ;
						$table .= "<th>Categories</th>" ;
						$table .= "<th>Cover Image</th>" ;
						$table .= "<th>&nbsp;</th>" ;
						$table .= "<th>Options</th>" ;
					$table .= "</tr>" ;
					for($i = 0; $i < count($photo_blog_posts) ; $i++){
						$photo_blog_post_info 			= $photo_blog_posts[$i] ;
						$photo_blog_post_id 			= $photo_blog_post_info->photo_blog_post_id ;
						$photo_blog_post_publish_status = $photo_blog_post_info->publish_status ;
						$photo_blog_post_title 			= $photo_blog_post_info->title ;
						$photo_blog_post_cover_image_preview = "" ;
						$photo_blog_post_cover_image_id = $photo_blog_post_info->cover_image_id ;
						if($photo_blog_post_cover_image_id != ""){
							$photo_blog_post_cover_image_info = $this->admin_images->getImageInfo($photo_blog_post_cover_image_id) ;
							if($photo_blog_post_cover_image_info !== false){
								$photo_blog_post_cover_image_filename 	= $photo_blog_post_cover_image_info->image_filename ;
								$photo_blog_post_cover_image_preview 	= $this->admin_images->previewImage($photo_blog_post_cover_image_filename, $this->rootdir."images/uploads/", '100px', '', "title = '".$photo_blog_post_title."'") ;
							}
						}
						$photo_blog_post_all_category_names = "" ;
						$photo_blog_post_all_category_info	= $this->wms_news->getAllCategoriesInfoForAPhotoBlogPost($photo_blog_post_id) ;
						if($photo_blog_post_all_category_info !== false){
							for($j = 0; $j < count($photo_blog_post_all_category_info) ; $j++){
								$this_category_name = $photo_blog_post_all_category_info[$j]->name ;
								$this_category_status = $photo_blog_post_all_category_info[$j]->status ;
								if($this_category_status == 1){
									$photo_blog_post_all_category_names .= $this_category_name ;
									if(($j + 1) < count($photo_blog_post_all_category_info)){
										$photo_blog_post_all_category_names .= " | " ;
									}
								}
							}
						}
						
						$this_photo_blog_post_publish_status_name = "" ;
						if($photo_blog_post_publish_status == 1){
							$this_photo_blog_post_publish_status_name = 'Published' ;
						}else if($photo_blog_post_publish_status == 2){
							$this_photo_blog_post_publish_status_name = 'Pending' ;
						}else if($photo_blog_post_publish_status == 0){
							$this_photo_blog_post_publish_status_name = 'Suspended' ;
						}
							
						
				//		$photo_blog_post_body 			= getFirstXLetters(html_entity_decode($photo_blog_post_info->full_text) ) ;					
						
						//ROW COLUMNS
						$row = "" ;
						$row = "<tr>" ;
							$row .= "<td class='view-item-title'><div>".($i + 1 + ($limit_start_no * $no_of_items_to_show) )."</div>" ;
							$row .= "<td class='view-item-title'><div>".$photo_blog_post_title."</div>" ;
							$row .= "<td class='view-item-title'><div>".$this_photo_blog_post_publish_status_name."</div>" ;
							$row .= "<td class='view-item-title'><div>".$photo_blog_post_all_category_names."</div>" ;
							$row .= "<td class='view-item-title'><div>".$photo_blog_post_cover_image_preview."</div>" ;
							$row .= "<td class='view-item-title'><div>&nbsp;</div>" ;
							$row .= "<td class='view-item-title'><div>";
							
								$row .= "<a class='img-view-item block_left' target='_blank' href='".$rootdir."admin/index/news/view_individual_photo_blog_post/".$photo_blog_post_id."' title='View'></a>" ;
								
								$row .= "<a class='img-edit-item block_left' href='".$rootdir."admin/index/news/edit_photo_blog_post_info/".$photo_blog_post_id."' title='Edit'></a>" ;
								
								
								if($this->user_sessions->getUserPrivilege($user_id) == '10'){
									$row .= "<a class='img-delete-item block_left' href='".$rootdir."admin/index/news/delete_a_photo_blog_post/".$photo_blog_post_id."' title='Delete'></a>" ;
									
								}
								
							$row .= "</div>" ;
						$row .= "</tr>" ;
						
						$table .= $row ;
						
					}// end for
					$table .= "</table>" ;
				}
			$o .= $table ;
			
			//Get Sub Pages links
				$p = "" ;
				$p .= "<div class='block_auto view-items-subpages' >" ;
					$extra_where_clause = "AND publish_status != 9" ;
					if($items_display_type != ''){
						$extra_where_clause = "AND publish_status = ".$display_type."" ;
					}
					$full_photo_blog_posts = $this->wms_news->getInfoAboutAllPhotoBlogPostsByAuthor($user_id, $extra_where_clause ) ;
					if($full_photo_blog_posts !== false){
						$full_count = count($full_photo_blog_posts) ;
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
	
	public function view_all_publisher_articles($page_number = "", $no_of_items = "", $sort_by = "", $display_type = ""){	
		$o = "" ;
		
			$user_id = $this->user_sessions->getUserId() ;
			
			$rootdir 						= $this->rootdir;
			$this_uri 						= "admin/index/news/view_all_publisher_articles/" ;
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
					$o .= "<span>View All Publishers Articles</span>" ;
				$o .= "</div>" ;
				$o .= "<div class='view-items-display block_left'>" ;
					$o .= "<span>Display:</span>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '')."' >All</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '1')."' >Published</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '2')."' >Pending</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '0')."' >Suspended</a>" ;
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
					$this->getSortByOptionsValue('iii', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Publish Status',
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
				
				$order = $this->getArticleSortByValue($sort_items_by) ;
				$start_limit = $no_of_items_to_show * $limit_start_no ;
				$limit = "LIMIT ".$start_limit.",".$no_of_items_to_show ;
				
				$extra_where_clause = "publish_status != 9" ;
				if($items_display_type != ''){
					$extra_where_clause = "publish_status = ".$display_type."" ;
				}
				$articles = $this->wms_news->getAllArticles($extra_where_clause, $order, $limit) ;
				if($articles !== false){
					$table .= "<table class='table table-bordered table-condensed'>" ;
					$table .= "<tr>" ;
						$table .= "<th>No</th>" ;
						$table .= "<th>Title</th>" ;
						$table .= "<th>Status</th>" ;
						$table .= "<th>Author</th>" ;
						$table .= "<th>Categories</th>" ;
						$table .= "<th>Cover Image</th>" ;
						$table .= "<th>&nbsp;</th>" ;
						$table .= "<th>Options</th>" ;
					$table .= "</tr>" ;
					for($i = 0; $i < count($articles) ; $i++){
						$article_info 			= $this->wms_news->getArticleInfo($articles[$i]['article_id']) ;
						$article_id 			= $article_info->article_id ;
						$article_publish_status = $article_info->publish_status ;
						$article_title 			= $article_info->title ;
						$article_cover_image_preview = "" ;
						$article_cover_image_id = $article_info->cover_image_id ;
						if($article_cover_image_id != ""){
							$article_cover_image_info = $this->admin_images->getImageInfo($article_cover_image_id) ;
							if($article_cover_image_info !== false){
								$article_cover_image_filename 	= $article_cover_image_info->image_filename ;
								$article_cover_image_preview 	= $this->admin_images->previewImage($article_cover_image_filename, $this->rootdir."images/uploads/", '100px', '', "title = '".$article_title."'") ;
							}
						}
						$article_all_category_names = "" ;
						$article_all_category_info	= $this->wms_news->getAllCategoriesInfoForAnArticle($article_id) ;
						if($article_all_category_info !== false){
							for($j = 0; $j < count($article_all_category_info) ; $j++){
								$this_category_name = $article_all_category_info[$j]->name ;
								$this_category_status = $article_all_category_info[$j]->status ;
								if($this_category_status == 1){
									$article_all_category_names .= $this_category_name ;
									if(($j + 1) < count($article_all_category_info)){
										$article_all_category_names .= " | " ;
									}
								}
							}
						}
						
						$this_article_publish_status_name = "" ;
						if($article_publish_status == 1){
							$this_article_publish_status_name = 'Published' ;
						}else if($article_publish_status == 2){
							$this_article_publish_status_name = 'Pending' ;
						}else if($article_publish_status == 0){
							$this_article_publish_status_name = 'Suspended' ;
						}
						
						$author_name = "" ;
						$author_info = $this->wms_news->getAuthorUserInfo($article_info->author_user_id) ;
						if($author_info !== false){
							$author_name = $author_info->fname." ".$author_info->lname ;
						}
						
				//		$article_body 			= getFirstXLetters(html_entity_decode($article_info->full_text) ) ;					
						
						//ROW COLUMNS
						$row = "" ;
						$row = "<tr>" ;
							$row .= "<td class='view-article-title'><div>".($i + 1 + ($limit_start_no * $no_of_items_to_show) )."</div>" ;
							$row .= "<td class='view-article-title'><div>".$article_title."</div>" ;
							$row .= "<td class='view-article-title'><div>".$this_article_publish_status_name."</div>" ;
							$row .= "<td class='view-article-title'><div>".$author_name."</div>" ;
							$row .= "<td class='view-article-title'><div>".$article_all_category_names."</div>" ;
							$row .= "<td class='view-article-title'><div>".$article_cover_image_preview."</div>" ;
							$row .= "<td class='view-article-title'><div>&nbsp;</div>" ;
							$row .= "<td class='view-article-title'><div>";
							
								$row .= "<a class='img-view-item block_left' target='_blank' href='".$rootdir."admin/index/news/view_individual_article/".$article_id."' title='View'></a>" ;
								
								$row .= "<a class='img-edit-item block_left' href='".$rootdir."admin/index/news/edit_article_info/".$article_id."/pub' title='Edit'></a>" ;
								
								
								if($this->user_sessions->getUserPrivilege($user_id) == '10'){
									$row .= "<a class='img-delete-item block_left' href='".$rootdir."admin/index/news/delete_an_article/".$article_id."/pub' title='Delete'></a>" ;
									
								}
								
								if( ($this->checkUserOptionalAccountStatus($user_id, '1005') === true) 
									|| ($this->checkUserOptionalAccountStatus($user_id, '1007') === true)
									|| ($this->checkUserOptionalAccountStatus($user_id, '1100') === true) 
									|| ($this->user_sessions->getUserPrivilege($user_id) == '10')  ){
									
										$row .= "<a class='img-publish-item block_left' href='".$rootdir."admin/index/news/edit_article_pub_status/".$article_id."' title='Change Publish Status'></a>" ;
																			
									}
								
								if( ($this->checkUserOptionalAccountStatus($user_id, '1005') === true) 
									|| ($this->checkUserOptionalAccountStatus($user_id, '1007') === true)
									|| ($this->checkUserOptionalAccountStatus($user_id, '1100') === true) 
									|| ($this->user_sessions->getUserPrivilege($user_id) == '10')  ){
									
										$row .= "<a class='img-edit-item-category block_left' href='".$rootdir."admin/index/news/edit_article_to_category/".$article_id."/pub' title='Add/Remove Article From Category'></a>" ;
																			
									}
								
								
							$row .= "</div>" ;
						$row .= "</tr>" ;
						
						$table .= $row ;
						
					}// end for
					$table .= "</table>" ;
				}
			$o .= $table ;
			
			//Get Sub Pages links
				$p = "" ;
				$p .= "<div class='block_auto view-items-subpages' >" ;
					$extra_where_clause = "publish_status != 9" ;
					if($items_display_type != ''){
						$extra_where_clause = "publish_status = ".$display_type."" ;
					}
					$full_articles = $this->wms_news->getAllArticles($extra_where_clause ) ;
					if($full_articles !== false){
						$full_count = count($full_articles) ;
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
	
	private function getArticleSortByValue($sort_param){
		switch($sort_param){
			case 'default': 	return "ORDER BY time_created DESC" ; 					break ;
			case 'i':		 	return "ORDER BY title" ; 		break ;
			case 'ii':		 	return "ORDER BY title DESC" ; 	break ;
			case 'iii':		 	return "ORDER BY publish_status" ; 		break ;
			case 'iv':		 	return "ORDER BY time_created DESC" ; 	break ;
			case 'v':		 	return "ORDER BY time_created" ; 		break ;
			default: 			return "" ; 					break ;
		}
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
	
	
	
	public function view_all_publisher_videos($page_number = "", $no_of_items = "", $sort_by = "", $display_type = ""){	
		$o = "" ;
		
			$user_id = $this->user_sessions->getUserId() ;
			
			$rootdir 						= $this->rootdir;
			$this_uri 						= "admin/index/news/view_all_publisher_videos/" ;
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
					$o .= "<span>View All Publishers Videos</span>" ;
				$o .= "</div>" ;
				$o .= "<div class='view-items-display block_left'>" ;
					$o .= "<span>Display:</span>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '')."' >All</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '1')."' >Published</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '2')."' >Pending</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '0')."' >Suspended</a>" ;
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
					$this->getSortByOptionsValue('iii', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Publish Status',
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
				
				$order = $this->getArticleSortByValue($sort_items_by) ;
				$start_limit = $no_of_items_to_show * $limit_start_no ;
				$limit = "LIMIT ".$start_limit.",".$no_of_items_to_show ;
				
				$extra_where_clause = "publish_status != 9" ;
				if($items_display_type != ''){
					$extra_where_clause = "publish_status = ".$display_type."" ;
				}
				$videos = $this->wms_news->getAllVideos($extra_where_clause, $order, $limit) ;
				if($videos !== false){
					$table .= "<table class='table table-bordered table-condensed'>" ;
					$table .= "<tr>" ;
						$table .= "<th>No</th>" ;
						$table .= "<th>Title</th>" ;
						$table .= "<th>Status</th>" ;
						$table .= "<th>Author</th>" ;
						$table .= "<th>Categories</th>" ;
						$table .= "<th>Cover Image</th>" ;
						$table .= "<th>&nbsp;</th>" ;
						$table .= "<th>Options</th>" ;
					$table .= "</tr>" ;
					for($i = 0; $i < count($videos) ; $i++){
						$video_info 			= $this->wms_news->getVideoInfo($videos[$i]['video_id']) ;
						$video_id 				= $video_info->video_id ;
						$video_publish_status 	= $video_info->publish_status ;
						$video_title 			= $video_info->title ;
						
						$video_cover_image_preview = "" ;
						$video_cover_image_id 	= $video_info->cover_image_id ;
						if($video_cover_image_id != ""){
							$video_cover_image_info = $this->admin_images->getImageInfo($video_cover_image_id) ;
							if($video_cover_image_info !== false){
								$video_cover_image_filename 	= $video_cover_image_info->image_filename ;
								$video_cover_image_preview 		= $this->admin_images->previewImage($video_cover_image_filename, $this->rootdir."images/uploads/", '100px', '', "title = '".$video_title."'") ;
							}
						}
						
						$video_all_category_names = "" ;
						$video_all_category_info	= $this->wms_news->getAllCategoriesInfoForAVideo($video_id) ;
						if($video_all_category_info !== false){
							for($j = 0; $j < count($video_all_category_info) ; $j++){
								$this_category_name = $video_all_category_info[$j]->name ;
								$this_category_status = $video_all_category_info[$j]->status ;
								if($this_category_status == 1){
									$video_all_category_names .= $this_category_name ;
									if(($j + 1) < count($video_all_category_info)){
										$video_all_category_names .= " | " ;
									}
								}
							}
						}
						
						$this_video_publish_status_name = "" ;
						if($video_publish_status == 1){
							$this_video_publish_status_name = 'Published' ;
						}else if($video_publish_status == 2){
							$this_video_publish_status_name = 'Pending' ;
						}else if($video_publish_status == 0){
							$this_video_publish_status_name = 'Suspended' ;
						}
						
						$author_name = "" ;
						$author_info = $this->wms_news->getAuthorUserInfo($video_info->author_user_id) ;
						if($author_info !== false){
							$author_name = $author_info->fname." ".$author_info->lname ;
						}
						
				//		$video_body 			= getFirstXLetters(html_entity_decode($video_info->full_text) ) ;					
						
						//ROW COLUMNS
						$row = "" ;
						$row = "<tr>" ;
							$row .= "<td class='view-item-title'><div>".($i + 1 + ($limit_start_no * $no_of_items_to_show) )."</div>" ;
							$row .= "<td class='view-item-title'><div>".$video_title."</div>" ;
							$row .= "<td class='view-item-title'><div>".$this_video_publish_status_name."</div>" ;
							$row .= "<td class='view-item-title'><div>".$author_name."</div>" ;
							$row .= "<td class='view-item-title'><div>".$video_all_category_names."</div>" ;
							$row .= "<td class='view-item-title'><div>".$video_cover_image_preview."</div>" ;
							$row .= "<td class='view-item-title'><div>&nbsp;</div>" ;
							$row .= "<td class='view-item-title'><div>";
							
								$row .= "<a class='img-view-item block_left' target='_blank' href='".$rootdir."admin/index/news/view_individual_video/".$video_id."' title='View'></a>" ;
								
								$row .= "<a class='img-edit-item block_left' href='".$rootdir."admin/index/news/edit_video_info/".$video_id."/pub' title='Edit'></a>" ;
								
								
								if($this->user_sessions->getUserPrivilege($user_id) == '10'){
									$row .= "<a class='img-delete-item block_left' href='".$rootdir."admin/index/news/delete_an_video/".$video_id."/pub' title='Delete'></a>" ;
									
								}
								
								if( ($this->checkUserOptionalAccountStatus($user_id, '1005') === true) 
									|| ($this->checkUserOptionalAccountStatus($user_id, '1007') === true)
									|| ($this->checkUserOptionalAccountStatus($user_id, '1100') === true) 
									|| ($this->user_sessions->getUserPrivilege($user_id) == '10')  ){
									
										$row .= "<a class='img-publish-item block_left' href='".$rootdir."admin/index/news/edit_video_pub_status/".$video_id."' title='Change Publish Status'></a>" ;
																			
									}
								
								if( ($this->checkUserOptionalAccountStatus($user_id, '1005') === true) 
									|| ($this->checkUserOptionalAccountStatus($user_id, '1007') === true)
									|| ($this->checkUserOptionalAccountStatus($user_id, '1100') === true) 
									|| ($this->user_sessions->getUserPrivilege($user_id) == '10')  ){
									
										$row .= "<a class='img-edit-item-category block_left' href='".$rootdir."admin/index/news/edit_video_to_category/".$video_id."' title='Add/Remove Video From Category'></a>" ;
										
									}
								
								
							$row .= "</div>" ;
						$row .= "</tr>" ;
						
						$table .= $row ;
						
					}// end for
					$table .= "</table>" ;
				}
			$o .= $table ;
			
			//Get Sub Pages links
				$p = "" ;
				$p .= "<div class='block_auto view-items-subpages' >" ;
					$extra_where_clause = "publish_status != 9" ;
					if($items_display_type != ''){
						$extra_where_clause = "publish_status = ".$display_type."" ;
					}
					$full_videos = $this->wms_news->getAllVideos($extra_where_clause ) ;
					if($full_videos !== false){
						$full_count = count($full_videos) ;
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
	
	
	
	public function view_all_publisher_photo_blog($page_number = "", $no_of_items = "", $sort_by = "", $display_type = ""){	
		$o = "" ;
		
			$user_id = $this->user_sessions->getUserId() ;
			
			$rootdir 						= $this->rootdir;
			$this_uri 						= "admin/index/news/view_all_publisher_photo_blog/" ;
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
					$o .= "<span>View All Publishers Cartoon Posts</span>" ;
				$o .= "</div>" ;
				$o .= "<div class='view-items-display block_left'>" ;
					$o .= "<span>Display:</span>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '')."' >All</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '1')."' >Published</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '2')."' >Pending</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '0')."' >Suspended</a>" ;
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
					$this->getSortByOptionsValue('iii', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Publish Status',
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
				
				$order = $this->getArticleSortByValue($sort_items_by) ;
				$start_limit = $no_of_items_to_show * $limit_start_no ;
				$limit = "LIMIT ".$start_limit.",".$no_of_items_to_show ;
				
				$extra_where_clause = "publish_status != 9" ;
				if($items_display_type != ''){
					$extra_where_clause = "publish_status = ".$display_type."" ;
				}
				$photo_blog_posts = $this->wms_news->getAllPhotoBlogPosts($extra_where_clause, $order, $limit) ;
				if($photo_blog_posts !== false){
					$table .= "<table class='table table-bordered table-condensed'>" ;
					$table .= "<tr>" ;
						$table .= "<th>No</th>" ;
						$table .= "<th>Title</th>" ;
						$table .= "<th>Status</th>" ;
						$table .= "<th>Author</th>" ;
						$table .= "<th>Categories</th>" ;
						$table .= "<th>Cover Image</th>" ;
						$table .= "<th>&nbsp;</th>" ;
						$table .= "<th>Options</th>" ;
					$table .= "</tr>" ;
					for($i = 0; $i < count($photo_blog_posts) ; $i++){
						$photo_blog_post_info 			= $this->wms_news->getPhotoBlogPostInfo($photo_blog_posts[$i]['photo_blog_post_id']) ;
						$photo_blog_post_id 			= $photo_blog_post_info->photo_blog_post_id ;
						$photo_blog_post_publish_status = $photo_blog_post_info->publish_status ;
						$photo_blog_post_title 			= $photo_blog_post_info->title ;
						$photo_blog_post_cover_image_preview = "" ;
						$photo_blog_post_cover_image_id = $photo_blog_post_info->cover_image_id ;
						if($photo_blog_post_cover_image_id != ""){
							$photo_blog_post_cover_image_info = $this->admin_images->getImageInfo($photo_blog_post_cover_image_id) ;
							if($photo_blog_post_cover_image_info !== false){
								$photo_blog_post_cover_image_filename 	= $photo_blog_post_cover_image_info->image_filename ;
								$photo_blog_post_cover_image_preview 	= $this->admin_images->previewImage($photo_blog_post_cover_image_filename, $this->rootdir."images/uploads/", '100px', '', "title = '".$photo_blog_post_title."'") ;
							}
						}
						$photo_blog_post_all_category_names = "" ;
						$photo_blog_post_all_category_info	= $this->wms_news->getAllCategoriesInfoForAPhotoBlogPost($photo_blog_post_id) ;
						if($photo_blog_post_all_category_info !== false){
							for($j = 0; $j < count($photo_blog_post_all_category_info) ; $j++){
								$this_category_name = $photo_blog_post_all_category_info[$j]->name ;
								$this_category_status = $photo_blog_post_all_category_info[$j]->status ;
								if($this_category_status == 1){
									$photo_blog_post_all_category_names .= $this_category_name ;
									if(($j + 1) < count($photo_blog_post_all_category_info)){
										$photo_blog_post_all_category_names .= " | " ;
									}
								}
							}
						}
						
						$this_photo_blog_post_publish_status_name = "" ;
						if($photo_blog_post_publish_status == 1){
							$this_photo_blog_post_publish_status_name = 'Published' ;
						}else if($photo_blog_post_publish_status == 2){
							$this_photo_blog_post_publish_status_name = 'Pending' ;
						}else if($photo_blog_post_publish_status == 0){
							$this_photo_blog_post_publish_status_name = 'Suspended' ;
						}
						
						$author_name = "" ;
						$author_info = $this->wms_news->getAuthorUserInfo($photo_blog_post_info->author_user_id) ;
						if($author_info !== false){
							$author_name = $author_info->fname." ".$author_info->lname ;
						}
						
				//		$photo_blog_post_body 			= getFirstXLetters(html_entity_decode($photo_blog_post_info->full_text) ) ;					
						
						//ROW COLUMNS
						$row = "" ;
						$row = "<tr>" ;
							$row .= "<td class='view-item-title'><div>".($i + 1 + ($limit_start_no * $no_of_items_to_show) )."</div>" ;
							$row .= "<td class='view-item-title'><div>".$photo_blog_post_title."</div>" ;
							$row .= "<td class='view-item-title'><div>".$this_photo_blog_post_publish_status_name."</div>" ;
							$row .= "<td class='view-item-title'><div>".$author_name."</div>" ;
							$row .= "<td class='view-item-title'><div>".$photo_blog_post_all_category_names."</div>" ;
							$row .= "<td class='view-item-title'><div>".$photo_blog_post_cover_image_preview."</div>" ;
							$row .= "<td class='view-item-title'><div>&nbsp;</div>" ;
							$row .= "<td class='view-item-title'><div>";
							
								$row .= "<a class='img-view-item block_left' target='_blank' href='".$rootdir."admin/index/news/view_individual_photo_blog_post/".$photo_blog_post_id."' title='View'></a>" ;
								
								$row .= "<a class='img-edit-item block_left' href='".$rootdir."admin/index/news/edit_photo_blog_post_info/".$photo_blog_post_id."/pub' title='Edit'></a>" ;
								
								
								if($this->user_sessions->getUserPrivilege($user_id) == '10'){
									$row .= "<a class='img-delete-item block_left' href='".$rootdir."admin/index/news/delete_a_photo_blog_post/".$photo_blog_post_id."/pub' title='Delete'></a>" ;
									
								}
								
								if( ($this->checkUserOptionalAccountStatus($user_id, '1005') === true) 
									|| ($this->checkUserOptionalAccountStatus($user_id, '1007') === true)
									|| ($this->checkUserOptionalAccountStatus($user_id, '1100') === true) 
									|| ($this->user_sessions->getUserPrivilege($user_id) == '10')  ){
									
										$row .= "<a class='img-publish-item block_left' href='".$rootdir."admin/index/news/edit_photo_blog_post_pub_status/".$photo_blog_post_id."' title='Change Publish Status'></a>" ;
																			
									}
								
								if( ($this->checkUserOptionalAccountStatus($user_id, '1005') === true) 
									|| ($this->checkUserOptionalAccountStatus($user_id, '1007') === true)
									|| ($this->checkUserOptionalAccountStatus($user_id, '1100') === true) 
									|| ($this->user_sessions->getUserPrivilege($user_id) == '10')  ){
									
										$row .= "<a class='img-edit-item-category block_left' href='".$rootdir."admin/index/news/edit_photo_blog_post_to_category/".$photo_blog_post_id."' title='Add/Remove Post From Category'></a>" ;
																			
									}
								
								
							$row .= "</div>" ;
						$row .= "</tr>" ;
						
						$table .= $row ;
						
					}// end for
					$table .= "</table>" ;
				}
			$o .= $table ;
			
			//Get Sub Pages links
				$p = "" ;
				$p .= "<div class='block_auto view-items-subpages' >" ;
					$extra_where_clause = "publish_status != 9" ;
					if($items_display_type != ''){
						$extra_where_clause = "publish_status = ".$display_type."" ;
					}
					$full_articles = $this->wms_news->getAllPhotoBlogPosts($extra_where_clause ) ;
					if($full_articles !== false){
						$full_count = count($full_articles) ;
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
	
	
	
	public function view_individual_article($article_id){
		$o = "" ;
		
		//Check User Permission
		//Allow If User Created The Article OR if User Privilege is Super Administrator
		$user_id = $this->user_sessions->getUserId() ;
		$creator_permission_result = $this->wms_news->checkIfAuthorCreatedArticle($user_id, $article_id) ;
		$greater_permission_result = $this->checkUserOptionalAccountPermission($user_id, 'view_individual_article') ;
		if(($creator_permission_result === true) || ($greater_permission_result === true) ){			
				//Continue
						
			$user_id = $this->user_sessions->getuserId() ;
			
			$article_info = $this->wms_news->getArticleInfo($article_id) ;
			if($article_info !== false){
				$article_title = $article_info->title ;
				$article_body = $article_info->full_text ;
				$article_cover_image_id = $article_info->cover_image_id ;
				
				$cover_image_element = "" ;
				$article_cover_image_info = $this->admin_images->getImageInfo($article_cover_image_id) ;
				if($article_cover_image_info !== false){
					$image_filename = $article_cover_image_info->image_filename ;
					$cover_image_element = $this->admin_images->getImageElement($image_filename, $this->rootdir."images/uploads/","width='100px'") ;
				}
				
				$o .= "<div class='article_individual_view_box' >" ;
					
					$o .= "<div class='block_auto article_individual_title' >" ;
						$o .= $article_title ;
					$o .= "</div>" ;
					
					
					$o .= "<div class='block_auto article_individual_body' >" ;
						$o .= html_entity_decode($article_body) ;
					$o .= "</div>" ;
				$o .= "</div>" ;
			//	$o .= $cover_image_element ;
				
			}
		}
		
		return $o ;
	}
	
	
	public function view_all_categories($page_number = "", $no_of_items = "", $sort_by = "", $display_type = ""){	
		$o = "" ;
		
			$user_id = $this->user_sessions->getUserId() ;
			
			$rootdir 						= $this->rootdir;
			$this_uri 						= "admin/index/news/view_all_categories/" ;
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
					$o .= "<span>View All Categories</span>" ;
				$o .= "</div>" ;
				$o .= "<div class='view-items-display block_left'>" ;
					$o .= "<span>Display:</span>" ;
					$disp_I = ""; $disp_i = ""; $disp_ii = ""; $disp_iii = ""; $disp_iv = ""; $disp_v = ""; $disp_vi = "";
					switch($items_display_type){
						case 'I' : 		$disp_I 	= "current"; break ;
						case 'i' : 		$disp_i 	= "current"; break ;
						case 'ii' : 	$disp_ii 	= "current"; break ;
						case 'iii' : 	$disp_iii 	= "current"; break ;
						case 'iv' : 	$disp_iv 	= "current"; break ;
						case 'v' : 		$disp_v 	= "current"; break ;
						case 'vi' : 	$disp_vi 	= "current"; break ;
					}
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, 'I')."' class='".$disp_I."' >All</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, 'i')."' class='".$disp_i."'>Site</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, 'ii')."'  class='".$disp_ii."'>Site Published</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, 'iii')."'  class='".$disp_iii."'>Site Suspended</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, 'iv')."'  class='".$disp_iv."'>Blog</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, 'v')."'  class='".$disp_v."'>Blog Published</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, 'vi')."'  class='".$disp_vi."'>Blog Suspended</a>" ;
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
					$this->getSortByOptionsValue('i', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Name (A - Z)',
					$this->getSortByOptionsValue('ii', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Name (Z - A)',
					$this->getSortByOptionsValue('iii', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Publish Status',
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
				
				$order = $this->getCategorySortByValue($sort_items_by) ;
				$start_limit = $no_of_items_to_show * $limit_start_no ;
				$limit = "LIMIT ".$start_limit.",".$no_of_items_to_show ;
				
				$extra_where_clause = "(status = 1) AND publish_status != 9" ;
				if($items_display_type != ''){
					$extra_where_clause = "(status = 1) AND ".$this->getCategoryDisplayTypeValue($display_type)."" ;
				}
				$categories = $this->wms_news->getAllArticleCategories($extra_where_clause, $order, $limit) ;
				if($categories !== false){
					$table .= "<table class='table table-bordered table-condensed'>" ;
					$table .= "<tr>" ;
						$table .= "<th>No</th>" ;
						$table .= "<th>Title</th>" ;
						$table .= "<th>Alias</th>" ;
						$table .= "<th>Status</th>" ;
						$table .= "<th>Articles</th>" ;
						$table .= "<th>Type</th>" ;
						$table .= "<th>&nbsp;</th>" ;
						$table .= "<th>Options</th>" ;
					$table .= "</tr>" ;
					for($i = 0; $i < count($categories) ; $i++){
						$category_id 			= $categories[$i]['category_id'] ;
						$category_info 			= $this->wms_news->getCategoryInfo($category_id) ;
						if($category_info !== false){
							$this_category_name 			= $category_info->name ;
							$this_category_alias 			= $category_info->alias ;
							
							$this_category_type_name		= "" ;
							$this_category_type_id 			= $category_info->category_type_id ;
							
							if($this_category_type_id == 1){
								$this_category_type_name = 'Exclusive for Site' ;
							}else if($this_category_type_id == 2){
								$this_category_type_name = 'Blog Category' ;
							}
							
							$this_category_publish_status_name = "" ;
							$this_category_publish_status 	= $category_info->publish_status ;
							
							if($this_category_publish_status == 1){
								$this_category_publish_status_name = 'Published' ;
							}else if($this_category_publish_status == 0){
								$this_category_publish_status_name = 'Suspended' ;
							}
							
							$this_category_subtype_name = "General" ;
							$this_category_subtype 		= $category_info->type ;
							if($this_category_subtype == 2){
								$this_category_subtype_name = "Special" ;
							}
							$this_category_status 			= $category_info->status ;
							
							
						}
				//		$article_body 			= getFirstXLetters(html_entity_decode($article_info->full_text) ) ;					
						
						//ROW COLUMNS
						$row = "" ;
						$row = "<tr>" ;
							$row .= "<td class='view-category-no'><div>".($i + 1 + ($limit_start_no * $no_of_items_to_show) )."</div>" ;
							$row .= "<td class='view-category-name'><div>".$this_category_name."</div>" ;
							$row .= "<td class='view-category-name'><div>".$this_category_alias."</div>" ;
							$row .= "<td class='view-category-status'><div>".$this_category_publish_status_name."</div>" ;
							$row .= "<td class='view-category-type'><div>".$this_category_type_name."</div>" ;
							$row .= "<td class='view-category-subtype'><div>".$this_category_subtype_name."</div>" ;
							$row .= "<td class='view-category-title'><div>&nbsp;</div>" ;
							$row .= "<td class='view-category-title'><div>";
							
								$row .= "<a class='img-edit-category block_left' href='".$rootdir."admin/index/news/edit_category_info/".$category_id."'>Edit</a>" ;
								$row .= "<div class='spacer-small block_left' >&nbsp;</div>" ;
								$row .= "<a class='img-delete-category block_left' href='".$rootdir."admin/index/news/delete_a_category/".$category_id."'>Delete</a>" ;
								$row .= "<div class='spacer-small block_left' >&nbsp;</div>" ;
								
								if( ($this->checkUserOptionalAccountStatus($user_id, '1007') === true) 
									|| ($this->checkUserOptionalAccountStatus($user_id, '1100') === true) 
									|| ($this->user_sessions->getUserPrivilege($user_id) == '10')  ){
									
										$row .= "<a class='img-publish-category block_left' href='".$rootdir."admin/index/news/edit_category_pub_status/".$category_id."'>Change Publish Status</a>" ;
										$row .= "<div class='spacer-small block_left' >&nbsp;</div>" ;									
									}
								
								
							$row .= "</div>" ;
						$row .= "</tr>" ;
						
						$table .= $row ;
						
					}// end for
					$table .= "</table>" ;
				}
			$o .= $table ;
			
			//Get Sub Pages links
				$p = "" ;
				$p .= "<div class='block_auto view-items-subpages' >" ;
					$extra_where_clause = "AND (status = 1) AND publish_status != 9" ;
					if($items_display_type != ''){
						$extra_where_clause = "AND (status = 1) AND ".$this->getCategoryDisplayTypeValue($display_type)."" ;
					}
					$full_categories = $this->wms_news->getAllArticleCategories($user_id, $extra_where_clause ) ;
					if($full_categories !== false){
						$full_count = count($full_categories) ;
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
	
	private function getCategoryDisplayTypeValue($sort_param){
		switch($sort_param){
			case 'default': 	return "" ; 					break ;
			case 'I':		 	return "(category_type_id = 1 OR category_type_id = 2)" ; 					break ;
			case 'i':		 	return "category_type_id = 1" ; 		break ;
			case 'ii':		 	return "category_type_id = 1 AND publish_status = 1" ; 	break ;
			case 'iii':		 	return "category_type_id = 1 AND publish_status = 0" ; 		break ;
			case 'iv':		 	return "category_type_id = 2" ; 	break ;
			case 'v':		 	return "category_type_id = 2 AND publish_status = 1" ; 	break ;
			case 'vi':		 	return "category_type_id = 2 AND publish_status = 0" ; 		break ;
			default: 			return "" ; 					break ;
		}
	}
	private function getCategorySortByValue($sort_param){
		switch($sort_param){
			case 'default': 	return "ORDER BY time_created DESC" ; 					break ;
			case 'i':		 	return "ORDER BY name" ; 		break ;
			case 'ii':		 	return "ORDER BY name DESC" ; 	break ;
			case 'iii':		 	return "ORDER BY publish_status" ; 		break ;
			case 'iv':		 	return "ORDER BY time_created DESC" ; 	break ;
			case 'v':		 	return "ORDER BY time_created" ; 		break ;
			default: 			return "" ; 					break ;
		}
	}
	
	
	
	
	
	public function edit_category_info($category_id){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		//Allow If User Created The Article OR if User Privilege is Greater than Publisher
		$user_id = $this->user_sessions->getUserId() ;
		$permission_result = $this->checkUserOptionalAccountPermission($user_id, 'edit_category_info') ;
		if( $permission_result === true ){			
			//Continue
			if($this->validate_n_process_edit_category_info_form() === true){
				//Show Success Message
				$msg = "The Category has been successfully Edited!" ;
				$o .= $this->prepareSuccessMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_all_categories") ;
				
				//Show Form Again if requested
				if($this->input->post('edit_category_info_submit') !== false){
					//Show Form
					$o .= $this->showForm_edit_category_info($category_id) ;
				}
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg) ;
				}
				$o .= $this->showForm_edit_category_info($category_id) ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg) ;
		}
		return $o ;
	}
	private function validate_n_process_edit_category_info_form(){
		if( ($this->input->post('edit_category_info_submit') !== false) || ($this->input->post('edit_category_info_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('edit_category_info_category_id', 'Category ID', 'required');
			$this->form_validation->set_rules('edit_category_info_name', 'Category Name', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$the_category_id 			= protect($this->input->post('edit_category_info_category_id')) ;
				$the_category_name 			= protect($this->input->post('edit_category_info_name')) ;
				$the_category_alias 		= protect($this->input->post('edit_category_info_alias')) ;
				$the_category_description	= protect($this->input->post('edit_category_info_description')) ;
				$the_category_type_id		= protect($this->input->post('edit_category_info_category_type_id')) ;
				$the_category_subtype		= protect($this->input->post('edit_category_info_category_subtype_id')) ;
				
				$user_id = $this->user_sessions->getUserId() ;
								
				// Edit Category
				if($this->wms_news->checkIfCategoryNameExistsForCategoryTypeExclude($the_category_name, $the_category_type_id, $the_category_id) === false){
					$res = $this->wms_news->editCategoryInfo($the_category_id, $the_category_type_id, $the_category_name, $the_category_alias, $the_category_description, $the_category_subtype, $user_id ) ;
					if($res === true){
						return true;
					}else{
						//Store Error
						$this->admin_forms->err .= "An error Occured While Editing The Category '$the_category_name'" ;
					}
				}else{
					//Check Error
					$this->admin_forms->err .= "This Category Name '$the_category_name' has been used for another Category with the same Category Type!" ;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_edit_category_info($category_id){
		$form_html = "" ;			
		$user_id = $this->user_sessions->getuserId() ;
		
		$category_info = $this->wms_news->getCategoryInfo($category_id) ;
		if($category_info !== false){
			$category_name 			= $category_info->name ;
			$category_alias			= $category_info->alias ;
			$category_description 	= $category_info->description ;
			$category_type_id 		= $category_info->category_type_id ;
			$category_subtype		= $category_info->type ;
			
			$category_type_arr = array() ;
			$category_type_info = $this->wms_news->getCategoryTypes() ;
			if($category_type_info !== false){
				for($i = 0; $i < count($category_type_info); $i++){
					$this_category_type_id = $category_type_info[$i]['category_type_id'] ;
					$this_category_type_name = $category_type_info[$i]['name'] ;
					$option_array = array( " ".$this_category_type_id." " => $this_category_type_name ) ;
					$category_type_arr = array_merge($category_type_arr, $option_array) ;
				}
			}
			
			$selected_category_type 	= " ".$category_type_id." " ;
			
			$selected_category_subtype 	= " ".$category_subtype." " ;
			
			//FIELDS
			$form_fields_html = array() ;
			
				//Category ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_category_info_category_id", "", $category_id) ;
				array_push($form_fields_html, $field) ;
				
				//Category Name
				$field = $this->admin_forms->getInputCustom("text", "Category Name:", "edit_category_info_name", "long_input", "", "Edit Category Name", "required", "", $category_name, false) ;
				array_push($form_fields_html, $field) ;
				
				//Category Alias (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_category_info_alias", "", $category_alias) ;
				array_push($form_fields_html, $field) ;
				
				//Category Description
				$field = $this->admin_forms->getTextarea("Category Description", "edit_category_info_description", "", "", "Edit Category Description", "", "", $category_description) ;			
				array_push($form_fields_html, $field) ;
				
				//Category Type (Select Field)
				$field = $this->admin_forms->getRegularSelect("Category Type", "edit_category_info_category_type_id", "long_input", "", "", false, array(), $category_type_arr, $selected_category_type ) ;
				array_push($form_fields_html, $field) ;
				
				//Regular Category (Select Field)
				$category_subtype_arr = array(
					' 2 ' => 'Yes',
					' 1 ' => 'No'
				) ;
				
				$field = "" ;
				$field .= "<br/><div class='block_auto long_input'>Category Is Special: (Set to Yes for Categories containing media like Videos. This prevents content of these categories from showing up in General sections like 'latest articles') :</div>" ;
				$field .= $this->admin_forms->getRegularSelect("", "edit_category_info_category_subtype_id", "long_input", "", "", false, array(), $category_subtype_arr, $selected_category_subtype, "block_auto" ) ;
				array_push($form_fields_html, $field) ;
								
				//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "edit_category_info_submit", "edit_category_info_submit_and_close", "", "", "submit", "submit", true, $this->rootdir."admin/index/news/view_all_categories", "Cancel") ;
				
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Edit A Category", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		}
		
		return $form_html ;
	}
	
	
	
	
	public function delete_a_category($category_id){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		//Allow If User Created The Article OR if User Privilege is Super Administrator
		$user_id = $this->user_sessions->getUserId() ;
		$permission_result = $this->checkUserOptionalAccountPermission($user_id, 'delete_a_category') ;
		if($permission_result === true){			
			//Continue
			if($this->validate_n_process_delete_a_category_form() === true){
				//Show Success Message
				$msg = "The Category has been successfully Deleted!" ;
				$o .= $this->prepareSuccessMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_all_categories") ;
				
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg) ;
				}
				$o .= $this->showForm_delete_a_category($category_id) ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg) ;
		}
		return $o ;
	}
	private function validate_n_process_delete_a_category_form(){
		if($this->input->post('delete_a_category_submit') !== false ){
			
			$this->form_validation->set_rules('delete_a_category_category_id', 'Category ID', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$del_category_id 			= protect($this->input->post('delete_a_category_category_id')) ;
				$del_category_name 			= protect($this->input->post('delete_a_category_name')) ;
				$del_category_type_id 		= protect($this->input->post('delete_a_category_type_id')) ;
				
				$user_id = $this->user_sessions->getUserId() ;
				
				// Delete Article
				
				$res = $this->wms_news->deleteCategory($user_id, $del_category_id, $del_category_type_id ) ;
				if($res === true){
					return true;
				}else{
					//Store Error
					$this->admin_forms->err .= "An error Occured While Deleting The Article '".$del_category_name."'" ;
					return false;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_delete_a_category($category_id){
		$form_html = "" ;			
		$user_id = $this->user_sessions->getuserId() ;
		
		$category_info = $this->wms_news->getCategoryInfo($category_id) ;
		if($category_info !== false){
			$category_name 		= $category_info->name ;
			
			$category_type_name = "" ;
			$category_type_id	= $category_info->category_type_id ;
			$category_type_info = $this->wms_news->getCategoryTypeInfo($category_type_id) ;
			if($category_type_info !== false){
				$category_type_name = $category_type_info->name ;
				
				//FIELDS
				$form_fields_html = array() ;
				
					//Category ID (hidden Field)
					$field = $this->admin_forms->getInputHidden("delete_a_category_category_id", "", $category_id) ;
					array_push($form_fields_html, $field) ;
					
					//Category Name (hidden Field)
					$field = $this->admin_forms->getInputHidden("delete_a_category_name", "", $category_name) ;
					array_push($form_fields_html, $field) ;
					
					//Category Type ID (hidden Field)
					$field = $this->admin_forms->getInputHidden("delete_a_category_type_id", "", $category_type_id) ;
					array_push($form_fields_html, $field) ;
					
					//Category Name
					$field = $this->admin_forms->getInputCustom("text", "Category Name:", "", "long_input", "", "", "disabled", "", $category_name, false) ;
					array_push($form_fields_html, $field) ;
					
					//Category Type
					$field = $this->admin_forms->getInputCustom("text", "Category Type:", "", "long_input", "", "", "disabled", "", $category_type_name, false) ;
					array_push($form_fields_html, $field) ;
					
					$field = "Are You sure you want to delete the Category '".$category_name."'?" ;
					array_push($form_fields_html, $field) ;
						
					//Form submit button
						$submit_field = $this->admin_forms->getDeleteButtonField("Delete Category", "delete_a_category_submit", "", "submit", true, $this->rootdir."admin/index/news/view_all_categories",  "Cancel") ;
					
				//Get Form HTML	
				$form_html = $this->admin_forms->getRegularForm("Delete A Category", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
			}
		}
		
		return $form_html ;
	}
	
	
	
	
	public function edit_category_pub_status($category_id){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		$user_id = $this->user_sessions->getUserId() ;
		$permission_result = $this->checkUserOptionalAccountPermission($user_id, 'edit_category_pub_status') ;
		if( $permission_result === true ){			
			//Continue
			if($this->validate_n_process_edit_category_pub_status_form() === true){
				//Show Success Message
				$msg = "The Category Publish Status has been successfully Saved!" ;
				$o .= $this->prepareSuccessMessage($msg, $this->rootdir."admin/index/".$this->menu_selection."/view_all_categories") ;
				
				//Show Form Again if requested
				if($this->input->post('edit_category_pub_status_submit') !== false){
					//Show Form
					$o .= $this->showForm_edit_category_pub_status($category_id) ;
				}
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg) ;
				}
				$o .= $this->showForm_edit_category_pub_status($category_id) ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg) ;
		}
		return $o ;
	}
	private function validate_n_process_edit_category_pub_status_form(){
		if( ($this->input->post('edit_category_pub_status_submit') !== false) || ($this->input->post('edit_category_pub_status_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('edit_category_pub_status_category_id', 'Category ID', 'required');
			$this->form_validation->set_rules('edit_category_pub_status_publish_status', 'Category Publish Status', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$the_category_id 			= protect($this->input->post('edit_category_pub_status_category_id')) ;
				$the_category_name 			= protect($this->input->post('edit_category_pub_status_name')) ;
				$category_publish_status	= protect($this->input->post('edit_category_pub_status_publish_status')) ;
								
				$user_id = $this->user_sessions->getUserId() ;
								
				// Save Category Publish Status
				$res = $this->wms_news->editCategoryPublishStatus($the_category_id, $category_publish_status) ;
				if($res === true){
					return true;
				}else{
					//Store Error
					$this->admin_forms->err .= "An error Occured While Saving the Publish Status of the Category '".$the_category_name."'" ;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_edit_category_pub_status($category_id){
		$form_html = "" ;			
		$user_id = $this->user_sessions->getuserId() ;
		
		$category_info = $this->wms_news->getCategoryInfo($category_id) ;
		if($category_info !== false){
			$category_name 			= $category_info->name ;
					
			$this_category_publish_status_name = "" ;
			$this_category_publish_status 	= $category_info->publish_status ;
			$checked_published = "" ;
			$checked_suspended = "" ;
			
			if($this_category_publish_status == 1){
				$this_category_publish_status_name = 'Published' ;
				$checked_published = "checked" ;
			}else if($this_category_publish_status == 0){
				$this_category_publish_status_name = 'Suspended' ;
				$checked_suspended = "checked" ;
			}	
			
			//FIELDS
			$form_fields_html = array() ;
			
				//Category ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_category_pub_status_category_id", "", $category_id) ;
				array_push($form_fields_html, $field) ;
				
				//Category Name (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_category_pub_status_name", "", $category_name) ;
				array_push($form_fields_html, $field) ;
				
				//Category Name
				$field = $this->admin_forms->getInputCustom("text", "Category Name:", "", "long_input", "", "Category Name", "disabled", "", $category_name, false) ;
				array_push($form_fields_html, $field) ;
				
				//Category Publish Status
				$field = $this->admin_forms->getInputCustom("text", "Category Publish Status:", "", "long_input", "", "Category Publish Status", "disabled", "", $this_category_publish_status_name, false) ;
				array_push($form_fields_html, $field) ;
				
				$field = "" ;
				$field .= "<div class='block_auto'>" ;
					$field .= "<input type='radio' name='edit_category_pub_status_publish_status' id='pub_publish_status' class='block_left' value='1' ".$checked_published." />" ;
					$field .= "<label for='pub_publish_status' >Published</label>" ;
				$field .= "</div>" ;
				
				$field .= "<div class='block_auto'>" ;
					$field .= "<input type='radio' name='edit_category_pub_status_publish_status' id='pub_suspend_status' class='block_left' value='0' ".$checked_suspended." />" ;
					$field .= "<label for='pub_suspend_status' >Suspended</label>" ;
				$field .= "</div>" ;
				
				array_push($form_fields_html, $field) ;
				
				//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "edit_category_pub_status_submit", "edit_category_pub_status_submit_and_close", "", "", "submit", "submit", true, $this->rootdir."admin/index/news/view_all_categories", "Cancel") ;
				
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Edit the Publish Status of a Category", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		}
		
		return $form_html ;
	}
	
	
	
	
	
	
	public function edit_article_pub_status($article_id){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		$user_id = $this->user_sessions->getUserId() ;
		$permission_result = $this->checkUserOptionalAccountPermission($user_id, 'edit_article_pub_status') ;
		if( $permission_result === true ){			
			//Continue
			
			$return_url = $this->rootdir."admin/index/".$this->menu_selection."/view_all_publisher_articles" ;
			
			if($this->validate_n_process_edit_article_pub_status_form() === true){
				//Show Success Message
				$msg = "The Article Publish Status has been successfully Saved!" ;
				$o .= $this->prepareSuccessMessage($msg, $return_url) ;
				
				//Show Form Again if requested
				if($this->input->post('edit_article_pub_status_submit') !== false){
					//Show Form
					$o .= $this->showForm_edit_article_pub_status($article_id, $return_url) ;
				}
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg, $return_url) ;
				}
				$o .= $this->showForm_edit_article_pub_status($article_id, $return_url) ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg, $return_url) ;
		}
		return $o ;
	}
	private function validate_n_process_edit_article_pub_status_form(){
		if( ($this->input->post('edit_article_pub_status_submit') !== false) || ($this->input->post('edit_article_pub_status_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('edit_article_pub_status_article_id', 'Article ID', 'required');
			$this->form_validation->set_rules('edit_article_pub_status_publish_status', 'Article Publish Status', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$the_article_id 			= protect($this->input->post('edit_article_pub_status_article_id')) ;
				$the_article_title 			= protect($this->input->post('edit_article_pub_status_title')) ;
				$article_publish_status		= protect($this->input->post('edit_article_pub_status_publish_status')) ;
								
				$user_id = $this->user_sessions->getUserId() ;
				
				// Save Article Publish Status
				$res = $this->wms_news->editArticlePublishStatus($the_article_id, $article_publish_status) ;
				if($res === true){
					return true;
				}else{
					//Store Error
					$this->admin_forms->err .= "An error Occured While Saving the Publish Status of the Article '".$the_article_title."'" ;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_edit_article_pub_status($article_id, $return_url){
		$form_html = "" ;			
		$user_id = $this->user_sessions->getuserId() ;
		
		$article_info = $this->wms_news->getArticleInfo($article_id) ;
		if($article_info !== false){
			$article_title 			= $article_info->title ;
					
			$this_article_publish_status_name = "" ;
			$this_article_publish_status 	= $article_info->publish_status ;
			$checked_published = "" ;
			$checked_suspended = "" ;
			$checked_pending = "" ;
			
			if($this_article_publish_status == 1){
				$this_article_publish_status_name = 'Published' ;
				$checked_published = "checked" ;
			}else if($this_article_publish_status == 0){
				$this_article_publish_status_name = 'Suspended' ;
				$checked_suspended = "checked" ;
			}else if($this_article_publish_status == 2){
				$this_article_publish_status_name = 'Pending' ;
				$checked_pending = "checked" ;
			}	
			
			//FIELDS
			$form_fields_html = array() ;
			
				//Article ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_article_pub_status_article_id", "", $article_id) ;
				array_push($form_fields_html, $field) ;
				
				//Article Title (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_article_pub_status_title", "", $article_title) ;
				array_push($form_fields_html, $field) ;
				
				//Article Title
				$field = $this->admin_forms->getInputCustom("text", "Article Title:", "", "long_input", "", "Article Title", "disabled", "", $article_title, false) ;
				array_push($form_fields_html, $field) ;
				
				//Article Publish Status
				$field = $this->admin_forms->getInputCustom("text", "Article Publish Status:", "", "long_input", "", "Article Publish Status", "disabled", "", $this_article_publish_status_name, false) ;
				array_push($form_fields_html, $field) ;
				
				$field = "" ;
				$field .= "<div class='block_auto'>" ;
					$field .= "<input type='radio' name='edit_article_pub_status_publish_status' id='pub_publish_status' class='block_left' value='1' ".$checked_published." />" ;
					$field .= "<label for='pub_publish_status' >Published</label>" ;
				$field .= "</div>" ;
				
				$field .= "<div class='block_auto'>" ;
					$field .= "<input type='radio' name='edit_article_pub_status_publish_status' id='pub_suspend_status' class='block_left' value='0' ".$checked_suspended." />" ;
					$field .= "<label for='pub_suspend_status' >Suspended</label>" ;
				$field .= "</div>" ;
				
				$field .= "<div class='block_auto'>" ;
					$field .= "<input type='radio' name='edit_article_pub_status_publish_status' id='pub_pending_status' class='block_left' value='2' ".$checked_pending." />" ;
					$field .= "<label for='pub_pending_status' >Pending</label>" ;
				$field .= "</div>" ;
				
				array_push($form_fields_html, $field) ;
				
				//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "edit_article_pub_status_submit", "edit_article_pub_status_submit_and_close", "", "", "submit", "submit", true, $return_url, "Cancel") ;
				
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Edit the Publish Status of an Article", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		}
		
		return $form_html ;
	}
	
	
	public function edit_video_pub_status($video_id){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		$user_id = $this->user_sessions->getUserId() ;
		$permission_result = $this->checkUserOptionalAccountPermission($user_id, 'edit_article_pub_status') ;
		if( $permission_result === true ){			
			//Continue
			
			$return_url = $this->rootdir."admin/index/".$this->menu_selection."/view_all_publisher_videos" ;
			
			if($this->validate_n_process_edit_video_pub_status_form() === true){
				//Show Success Message
				$msg = "The Video Publish Status has been successfully Saved!" ;
				$o .= $this->prepareSuccessMessage($msg, $return_url) ;
				
				//Show Form Again if requested
				if($this->input->post('edit_video_pub_status_submit') !== false){
					//Show Form
					$o .= $this->showForm_edit_video_pub_status($video_id, $return_url) ;
				}
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg, $return_url) ;
				}
				$o .= $this->showForm_edit_video_pub_status($video_id, $return_url) ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg, $return_url) ;
		}
		return $o ;
	}
	private function validate_n_process_edit_video_pub_status_form(){
		if( ($this->input->post('edit_video_pub_status_submit') !== false) || ($this->input->post('edit_video_pub_status_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('edit_video_pub_status_video_id', 'Video ID', 'required');
			$this->form_validation->set_rules('edit_video_pub_status_publish_status', 'Video Publish Status', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$the_video_id 			= protect($this->input->post('edit_video_pub_status_video_id')) ;
				$the_video_title 		= protect($this->input->post('edit_video_pub_status_title')) ;
				$video_publish_status	= protect($this->input->post('edit_video_pub_status_publish_status')) ;
								
				$user_id = $this->user_sessions->getUserId() ;
				
				// Save Article Publish Status
				$res = $this->wms_news->editVideoPublishStatus($the_video_id, $video_publish_status) ;
				if($res === true){
					return true;
				}else{
					//Store Error
					$this->admin_forms->err .= "An error Occured While Saving the Publish Status of the Video '".$the_video_title."'" ;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_edit_video_pub_status($video_id, $return_url){
		$form_html = "" ;			
		$user_id = $this->user_sessions->getuserId() ;
		
		$video_info = $this->wms_news->getVideoInfo($video_id) ;
		if($video_info !== false){
			$video_title 			= $video_info->title ;
					
			$this_video_publish_status_name = "" ;
			$this_video_publish_status 	= $video_info->publish_status ;
			$checked_published = "" ;
			$checked_suspended = "" ;
			$checked_pending = "" ;
			
			if($this_video_publish_status == 1){
				$this_video_publish_status_name = 'Published' ;
				$checked_published = "checked" ;
			}else if($this_video_publish_status == 0){
				$this_video_publish_status_name = 'Suspended' ;
				$checked_suspended = "checked" ;
			}else if($this_video_publish_status == 2){
				$this_video_publish_status_name = 'Pending' ;
				$checked_pending = "checked" ;
			}	
			
			//FIELDS
			$form_fields_html = array() ;
			
				//Video ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_video_pub_status_video_id", "", $video_id) ;
				array_push($form_fields_html, $field) ;
				
				//Video Name (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_video_pub_status_title", "", $video_title) ;
				array_push($form_fields_html, $field) ;
				
				//Video Name
				$field = $this->admin_forms->getInputCustom("text", "Video Title:", "", "long_input", "", "Video Title", "disabled", "", $video_title, false) ;
				array_push($form_fields_html, $field) ;
				
				//Video Publish Status
				$field = $this->admin_forms->getInputCustom("text", "Video Publish Status:", "", "long_input", "", "Video Publish Status", "disabled", "", $this_video_publish_status_name, false) ;
				array_push($form_fields_html, $field) ;
				
				$field = "" ;
				$field .= "<div class='block_auto'>" ;
					$field .= "<input type='radio' name='edit_video_pub_status_publish_status' id='pub_publish_status' class='block_left' value='1' ".$checked_published." />" ;
					$field .= "<label for='pub_publish_status' >Published</label>" ;
				$field .= "</div>" ;
				
				$field .= "<div class='block_auto'>" ;
					$field .= "<input type='radio' name='edit_video_pub_status_publish_status' id='pub_suspend_status' class='block_left' value='0' ".$checked_suspended." />" ;
					$field .= "<label for='pub_suspend_status' >Suspended</label>" ;
				$field .= "</div>" ;
				
				$field .= "<div class='block_auto'>" ;
					$field .= "<input type='radio' name='edit_video_pub_status_publish_status' id='pub_pending_status' class='block_left' value='2' ".$checked_pending." />" ;
					$field .= "<label for='pub_pending_status' >Pending</label>" ;
				$field .= "</div>" ;
				
				array_push($form_fields_html, $field) ;
				
				//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "edit_video_pub_status_submit", "edit_video_pub_status_submit_and_close", "", "", "submit", "submit", true, $return_url, "Cancel") ;
				
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Edit the Publish Status of a Video", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		}
		
		return $form_html ;
	}
	
	
	public function edit_photo_blog_post_pub_status($photo_blog_post_id){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		$user_id = $this->user_sessions->getUserId() ;
		$permission_result = $this->checkUserOptionalAccountPermission($user_id, 'edit_article_pub_status') ;
		if( $permission_result === true ){			
			//Continue
			
			$return_url = $this->rootdir."admin/index/".$this->menu_selection."/view_all_publisher_photo_blog" ;
			
			if($this->validate_n_process_edit_photo_blog_post_pub_status_form() === true){
				//Show Success Message
				$msg = "The Photo Blog Post Publish Status has been successfully Saved!" ;
				$o .= $this->prepareSuccessMessage($msg, $return_url) ;
				
				//Show Form Again if requested
				if($this->input->post('edit_photo_blog_post_pub_status_submit') !== false){
					//Show Form
					$o .= $this->showForm_edit_photo_blog_post_pub_status($photo_blog_post_id, $return_url) ;
				}
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg, $return_url) ;
				}
				$o .= $this->showForm_edit_photo_blog_post_pub_status($photo_blog_post_id, $return_url) ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg, $return_url) ;
		}
		return $o ;
	}
	private function validate_n_process_edit_photo_blog_post_pub_status_form(){
		if( ($this->input->post('edit_photo_blog_post_pub_status_submit') !== false) || ($this->input->post('edit_photo_blog_post_pub_status_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('edit_photo_blog_post_pub_status_post_id', 'Post ID', 'required');
			$this->form_validation->set_rules('edit_photo_blog_post_pub_status_publish_status', 'Post Publish Status', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$the_photo_blog_post_id 			= protect($this->input->post('edit_photo_blog_post_pub_status_post_id')) ;
				$the_photo_blog_post_title 			= protect($this->input->post('edit_photo_blog_post_pub_status_title')) ;
				$photo_blog_post_publish_status		= protect($this->input->post('edit_photo_blog_post_pub_status_publish_status')) ;
								
				$user_id = $this->user_sessions->getUserId() ;
				
				// Save Article Publish Status
				$res = $this->wms_news->editPhotoBlogPostPublishStatus($the_photo_blog_post_id, $photo_blog_post_publish_status) ;
				if($res === true){
					return true;
				}else{
					//Store Error
					$this->admin_forms->err .= "An error Occured While Saving the Publish Status of the Article '".$the_photo_blog_post_title."'" ;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_edit_photo_blog_post_pub_status($photo_blog_post_id, $return_url){
		$form_html = "" ;			
		$user_id = $this->user_sessions->getuserId() ;
		
		$photo_blog_post_info = $this->wms_news->getPhotoBlogPostInfo($photo_blog_post_id) ;
		if($photo_blog_post_info !== false){
			$photo_blog_post_title 			= $photo_blog_post_info->title ;
					
			$this_photo_blog_post_publish_status_name = "" ;
			$this_photo_blog_post_publish_status 	= $photo_blog_post_info->publish_status ;
			$checked_published 	= "" ;
			$checked_suspended 	= "" ;
			$checked_pending 	= "" ;
			
			if($this_photo_blog_post_publish_status == 1){
				$this_photo_blog_post_publish_status_name = 'Published' ;
				$checked_published = "checked" ;
			}else if($this_photo_blog_post_publish_status == 0){
				$this_photo_blog_post_publish_status_name = 'Suspended' ;
				$checked_suspended = "checked" ;
			}else if($this_photo_blog_post_publish_status == 2){
				$this_photo_blog_post_publish_status_name = 'Pending' ;
				$checked_pending = "checked" ;
			}
			
			//FIELDS
			$form_fields_html = array() ;
			
				//Photo Blog Post ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_photo_blog_post_pub_status_post_id", "", $photo_blog_post_id) ;
				array_push($form_fields_html, $field) ;
				
				//Photo Blog Post Title (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_photo_blog_post_pub_status_title", "", $photo_blog_post_title) ;
				array_push($form_fields_html, $field) ;
				
				//Photo Blog Post Title
				$field = $this->admin_forms->getInputCustom("text", "Post Title:", "", "long_input", "", "Post Title", "disabled", "", $photo_blog_post_title, false) ;
				array_push($form_fields_html, $field) ;
				
				//Photo Blog Post Publish Status
				$field = $this->admin_forms->getInputCustom("text", "Post Publish Status:", "", "long_input", "", "Post Publish Status", "disabled", "", $this_photo_blog_post_publish_status_name, false) ;
				array_push($form_fields_html, $field) ;
				
				$field = "" ;
				$field .= "<div class='block_auto'>" ;
					$field .= "<input type='radio' name='edit_photo_blog_post_pub_status_publish_status' id='pub_publish_status' class='block_left' value='1' ".$checked_published." />" ;
					$field .= "<label for='pub_publish_status' >Published</label>" ;
				$field .= "</div>" ;
				
				$field .= "<div class='block_auto'>" ;
					$field .= "<input type='radio' name='edit_photo_blog_post_pub_status_publish_status' id='pub_suspend_status' class='block_left' value='0' ".$checked_suspended." />" ;
					$field .= "<label for='pub_suspend_status' >Suspended</label>" ;
				$field .= "</div>" ;
				
				$field .= "<div class='block_auto'>" ;
					$field .= "<input type='radio' name='edit_photo_blog_post_pub_status_publish_status' id='pub_pending_status' class='block_left' value='2' ".$checked_pending." />" ;
					$field .= "<label for='pub_pending_status' >Pending</label>" ;
				$field .= "</div>" ;
				
				array_push($form_fields_html, $field) ;
				
				//Form submit button
				$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "edit_photo_blog_post_pub_status_submit", "edit_photo_blog_post_pub_status_submit_and_close", "", "", "submit", "submit", true, $return_url, "Cancel") ;
				
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Edit the Publish Status of a Photo Blog Post", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		}
		
		return $form_html ;
	}
	
	
	
	
	
	public function edit_article_to_category($article_id, $caller_tag = ""){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		//Allow If User Created The Article OR if User Privilege is Greater than Publisher
		$user_id = $this->user_sessions->getUserId() ;
		$creator_permission_result = $this->wms_news->checkIfAuthorCreatedArticle($user_id, $article_id) ;
		$permission_result = $this->checkUserOptionalAccountPermission($user_id, 'edit_article_to_category') ;
		if( ($creator_permission_result === true) || ($permission_result === true) ){
			//Continue
			
			$return_page = "view_all_articles" ;
			if($caller_tag == "pub"){ $return_page = "view_all_publisher_articles" ; }
			$return_url = $this->rootdir."admin/index/".$this->menu_selection."/".$return_page ;
						
			if($this->validate_n_process_edit_article_to_category_form() === true){
				
				//Show Success Message
				$msg = "The Article to Category Relationship has been successfully Edited!" ;
				$o .= $this->prepareSuccessMessage($msg, $return_url) ;
				
				//Show Form Again if requested
				if($this->input->post('edit_article_to_category_submit') !== false){
					//Show Form
					$o .= $this->showForm_edit_article_to_category($article_id, $return_url) ;
				}
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg, $return_url) ;
				}
				$o .= $this->showForm_edit_article_to_category($article_id, $return_url) ;
			}
		}else{
			$return_page = "view_all_articles" ;
			if($caller_tag == "pub"){ $return_page = "view_all_publisher_articles" ; }
			$return_url = $this->rootdir."admin/index/".$this->menu_selection."/".$return_page ;
			
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg, $return_url) ;
		}
		return $o ;
	}
	private function validate_n_process_edit_article_to_category_form(){
		if( ($this->input->post('edit_article_to_category_submit') !== false) || ($this->input->post('edit_article_to_category_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('edit_article_to_category_article_id', 'Article ID', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$the_article_id 					= protect($this->input->post('edit_article_to_category_article_id')) ;
				$the_article_title 					= protect($this->input->post('edit_article_to_category_article_title')) ;
				$the_related_categories				= unserialize(base64_decode(protect($this->input->post('edit_article_to_category_article_related_category_ids')) ) ) ;
				$the_modified_existing_categories	= $this->input->post('edit_article_to_category_existing_cats') ;
				$the_new_category_id				= protect($this->input->post('edit_article_to_category_new_category')) ;
				
				
				$user_id = $this->user_sessions->getUserId() ;
				
				
				$sub = $the_related_categories ;
				if(is_array($the_modified_existing_categories) && ( count($the_modified_existing_categories) > 0 )){
					$sub = array_diff($the_related_categories, $the_modified_existing_categories) ;
				}
				
				$final_sub = array() ;
				foreach($sub as $val){
					array_push($final_sub, $val) ;
				}
				
				// Edit Article Category Relations
				$res = $this->wms_news->editArticleCategoryRelationsInfo($the_article_id, $final_sub, $the_new_category_id) ;
				if($res === true){
					return true;
				}else{
					//Log the Error
					$this->admin_forms->err .= "An error Occured While Editing The Article '$the_article_title'" ;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_edit_article_to_category($article_id, $return_url){
		$form_html = "" ;			
		$user_id = $this->user_sessions->getuserId() ;
		
		$article_info = $this->wms_news->getArticleInfo($article_id) ;
		if($article_info !== false){
			$article_title = $article_info->title ;
			$article_cover_image_id = $article_info->cover_image_id ;
			
			$cover_image_element = "" ;
			$article_cover_image_info = $this->admin_images->getImageInfo($article_cover_image_id) ;
			if($article_cover_image_info !== false){
				$image_filename = $article_cover_image_info->image_filename ;
				$cover_image_element = $this->admin_images->getImageElement($image_filename, $this->rootdir."images/uploads/") ;
			}
			
			$article_all_category_names = "" ;
			$article_all_category_info_category_ids = array() ;
			$article_all_category_info	= $this->wms_news->getAllCategoriesInfoForAnArticle($article_id) ;
			if($article_all_category_info !== false){
				for($j = 0; $j < count($article_all_category_info) ; $j++){
					$this_category_id 		= $article_all_category_info[$j]->category_id ;
					$this_category_name 	= $article_all_category_info[$j]->name ;
					$this_category_status 	= $article_all_category_info[$j]->status ;
					if($this_category_status == 1){
						$article_all_category_names .= $this_category_name ;
						array_push($article_all_category_info_category_ids, $this_category_id) ;
					}
				}
			}
			
			$category_type_id = 1 ;
			$category_arr = array() ;
			$all_category_info = $this->wms_news->getAllInfoCategoriesByCategoryType($category_type_id) ;
			if($all_category_info !== false){
				for($i = 0; $i < count($all_category_info); $i++){
					$category_info = $this->wms_news->getCategoryInfo($all_category_info[$i]['category_id']) ;
					if($category_info !==false){
						$category_id 	= $category_info->category_id ;
						$category_name 	= $category_info->name ;
						$option_array 	= array( " ".$category_id." " => $category_name ) ;
						
						if(array_search($category_id, $article_all_category_info_category_ids) === false ){
							$category_arr 	= array_merge($category_arr, $option_array) ;
						}
						
													
					}
				}//end for all_category_info
			}
			
			//FIELDS
			$form_fields_html = array() ;
			
				//Article ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_article_to_category_article_id", "", $article_id) ;
				array_push($form_fields_html, $field) ;
				
				//Article Title (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_article_to_category_article_title", "", $article_title) ;
				array_push($form_fields_html, $field) ;
				
				//Existing Category Relations (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_article_to_category_article_related_category_ids", "", base64_encode(serialize($article_all_category_info_category_ids))) ;
				array_push($form_fields_html, $field) ;
				
				//Article Title
				$field = $this->admin_forms->getInputCustom("text", "Article Title:", "", "long_input", "", "Article Title", "disabled", "", $article_title, false) ;
				array_push($form_fields_html, $field) ;
				
				$field = "" ;
				if($article_all_category_info !== false){
					if(count($article_all_category_info) > 0){
						$field .= "<u>Related Categories:</u>" ;
						
						for($j = 0; $j < count($article_all_category_info) ; $j++){
							$this_category_id = $article_all_category_info[$j]->category_id ;
							$this_category_name = $article_all_category_info[$j]->name ;
							$this_category_status = $article_all_category_info[$j]->status ;
							if($this_category_status == 1){
								$article_all_category_names .= $this_category_name ;
								$field .= "<div class='block_auto'>" ;
									$field .= "<input type='checkbox' name='edit_article_to_category_existing_cats[]' value='".$this_category_id."' checked />" ;
									$field .= "<label class=''>".$this_category_name."</label>" ;
								$field .= "</div>" ;
							}
						}//end for
						$field .= "<br/>" ;
					}
				}
				array_push($form_fields_html, $field) ;
				
				
				//Category Type (Select Field)
				$field = $this->admin_forms->getRegularSelect("Select A Site Info Category (Optional):", "edit_article_to_category_new_category", "long_input", "", "", true, array(""=>"-- Select A Category --"), $category_arr ) ;
				array_push($form_fields_html, $field) ;
				
				
				//Form submit button
					$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "edit_article_to_category_submit", "edit_article_to_category_submit_and_close", "", "", "submit", "submit", true, $return_url, "Cancel") ;
				
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Edit Article to Category Relationship", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		}
		
		return $form_html ;
	}
	
	
	
	public function edit_video_to_category($video_id){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		
		//Check User Permission
		//Allow If User Created The Article OR if User Privilege is Greater than Publisher
		$user_id = $this->user_sessions->getUserId() ;
		$permission_result = $this->checkUserOptionalAccountPermission($user_id, 'edit_article_to_category') ;
		if($permission_result === true ){
			//Continue
			
			$return_url = $this->rootdir."admin/index/".$this->menu_selection."/view_all_publisher_videos" ;
			
			if($this->validate_n_process_edit_video_to_category_form() === true){
				//Show Success Message
				$msg = "The Video to Category Relationship has been successfully Edited!" ;
				$o .= $this->prepareSuccessMessage($msg, $return_url) ;
				
				//Show Form Again if requested
				if($this->input->post('edit_video_to_category_submit') !== false){
					//Show Form
					$o .= $this->showForm_edit_video_to_category($video_id, $return_url) ;
				}
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg, $return_url) ;
				}
				$o .= $this->showForm_edit_video_to_category($video_id, $return_url) ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg, $return_url) ;
		}
		return $o ;
	}
	private function validate_n_process_edit_video_to_category_form(){
		if( ($this->input->post('edit_video_to_category_submit') !== false) || ($this->input->post('edit_video_to_category_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('edit_video_to_category_video_id', 'Video ID', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$the_video_id 					= protect($this->input->post('edit_video_to_category_video_id')) ;
				$the_video_title 					= protect($this->input->post('edit_video_to_category_video_title')) ;
				$the_related_categories				= unserialize(base64_decode(protect($this->input->post('edit_video_to_category_video_related_category_ids')) ) ) ;
				$the_modified_existing_categories	= $this->input->post('edit_video_to_category_existing_cats') ;
				$the_new_category_id				= protect($this->input->post('edit_video_to_category_new_category')) ;
				
				
				$user_id = $this->user_sessions->getUserId() ;
				
				
				$sub = $the_related_categories ;
				if(is_array($the_modified_existing_categories) && ( count($the_modified_existing_categories) > 0 )){
					$sub = array_diff($the_related_categories, $the_modified_existing_categories) ;
				}
				
				$final_sub = array() ;
				foreach($sub as $val){
					array_push($final_sub, $val) ;
				}
				
				// Edit Article Category Relations
				$res = $this->wms_news->editVideoCategoryRelationsInfo($the_video_id, $final_sub, $the_new_category_id) ;
				if($res === true){
					return true;
				}else{
					//Log the Error
					$this->admin_forms->err .= "An error Occured While Editing The Video '$the_video_title'" ;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_edit_video_to_category($video_id, $return_url){
		$form_html = "" ;			
		$user_id = $this->user_sessions->getuserId() ;
		
		$video_info = $this->wms_news->getVideoInfo($video_id) ;
		if($video_info !== false){
			$video_title = $video_info->title ;
			$video_cover_image_id = $video_info->cover_image_id ;
			
			$cover_image_element = "" ;
			$video_cover_image_info = $this->admin_images->getImageInfo($video_cover_image_id) ;
			if($video_cover_image_info !== false){
				$image_filename = $video_cover_image_info->image_filename ;
				$cover_image_element = $this->admin_images->getImageElement($image_filename, $this->rootdir."images/uploads/") ;
			}
			
			$video_all_category_names = "" ;
			$video_all_category_info_category_ids = array() ;
			$video_all_category_info	= $this->wms_news->getAllCategoriesInfoForAVideo($video_id) ;
			if($video_all_category_info !== false){
				for($j = 0; $j < count($video_all_category_info) ; $j++){
					$this_category_id 		= $video_all_category_info[$j]->category_id ;
					$this_category_name 	= $video_all_category_info[$j]->name ;
					$this_category_status 	= $video_all_category_info[$j]->status ;
					if($this_category_status == 1){
						$video_all_category_names .= $this_category_name ;
						array_push($video_all_category_info_category_ids, $this_category_id) ;
					}
				}
			}
			
			$category_type_id = 1 ;
			$category_arr = array() ;
			$all_category_info = $this->wms_news->getAllInfoCategoriesByCategoryType($category_type_id) ;
			if($all_category_info !== false){
				for($i = 0; $i < count($all_category_info); $i++){
					$category_info = $this->wms_news->getCategoryInfo($all_category_info[$i]['category_id']) ;
					if($category_info !==false){
						$category_id 	= $category_info->category_id ;
						$category_name 	= $category_info->name ;
						$option_array 	= array( " ".$category_id." " => $category_name ) ;
						
						if(array_search($category_id, $video_all_category_info_category_ids) === false ){
							$category_arr 	= array_merge($category_arr, $option_array) ;
						}
						
													
					}
				}//end for all_category_info
			}
			
			//FIELDS
			$form_fields_html = array() ;
			
				//Video ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_video_to_category_video_id", "", $video_id) ;
				array_push($form_fields_html, $field) ;
				
				//Video Title (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_video_to_category_video_title", "", $video_title) ;
				array_push($form_fields_html, $field) ;
				
				//Existing Category Relations (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_video_to_category_video_related_category_ids", "", base64_encode(serialize($video_all_category_info_category_ids))) ;
				array_push($form_fields_html, $field) ;
				
				//Video Title
				$field = $this->admin_forms->getInputCustom("text", "Article Title:", "", "long_input", "", "Article Title", "disabled", "", $video_title, false) ;
				array_push($form_fields_html, $field) ;
				
				$field = "" ;
				if($video_all_category_info !== false){
					if(count($video_all_category_info) > 0){
						$field .= "<u>Related Categories:</u>" ;
						
						for($j = 0; $j < count($video_all_category_info) ; $j++){
							$this_category_id = $video_all_category_info[$j]->category_id ;
							$this_category_name = $video_all_category_info[$j]->name ;
							$this_category_status = $video_all_category_info[$j]->status ;
							if($this_category_status == 1){
								$video_all_category_names .= $this_category_name ;
								$field .= "<div class='block_auto'>" ;
									$field .= "<input type='checkbox' name='edit_video_to_category_existing_cats[]' value='".$this_category_id."' checked />" ;
									$field .= "<label class=''>".$this_category_name."</label>" ;
								$field .= "</div>" ;
							}
						}//end for
						$field .= "<br/>" ;
					}
				}
				array_push($form_fields_html, $field) ;
				
				
				//Category Type (Select Field)
				$field = $this->admin_forms->getRegularSelect("Select A Site Info Category (Optional):", "edit_video_to_category_new_category", "long_input", "", "", true, array(""=>"-- Select A Category --"), $category_arr ) ;
				array_push($form_fields_html, $field) ;
				
				//Form submit button
					$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "edit_video_to_category_submit", "edit_video_to_category_submit_and_close", "", "", "submit", "submit", true, $return_url, "Cancel") ;
				
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Edit Video to Category Relationship", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		}
		
		return $form_html ;
	}
	
	
	
	public function edit_photo_blog_post_to_category($photo_blog_post_id){
		$o = "" ;
		$this->admin_forms->err = "" ;
		
		//Check User Permission
		//Allow If User Created The Article OR if User Privilege is Greater than Publisher
		$user_id = $this->user_sessions->getUserId() ;
		$permission_result = $this->checkUserOptionalAccountPermission($user_id, 'edit_article_to_category') ;
		if($permission_result === true ){
			//Continue
			
			$return_url = $this->rootdir."admin/index/".$this->menu_selection."/view_all_publisher_photo_blog" ;
			
			if($this->validate_n_process_edit_photo_blog_post_to_category_form() === true){
				//Show Success Message
				$msg = "The Photo Blog Post to Category Relationship has been successfully Edited!" ;
				$o .= $this->prepareSuccessMessage($msg, $return_url) ;
				
				//Show Form Again if requested
				if($this->input->post('edit_photo_blog_post_to_category_submit') !== false){
					//Show Form
					$o .= $this->showForm_edit_photo_blog_post_to_category($photo_blog_post_id, $return_url) ;
				}
			}else{
				//Show Form
				if($this->admin_forms->err != "" ){
					$msg = $this->admin_forms->err ;
					$o .= $this->prepareErrorMessage($msg, $return_url) ;
				}
				$o .= $this->showForm_edit_photo_blog_post_to_category($photo_blog_post_id, $return_url) ;
			}
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg, $return_url) ;
		}
		return $o ;
	}
	private function validate_n_process_edit_photo_blog_post_to_category_form(){
		if( ($this->input->post('edit_photo_blog_post_to_category_submit') !== false) || ($this->input->post('edit_photo_blog_post_to_category_submit_and_close') !== false) ){
			
			$this->form_validation->set_rules('edit_photo_blog_post_to_category_photo_blog_post_id', 'Post ID', 'required');
			
			if ($this->form_validation->run() == FALSE){
				//Show Form Again Along with form errors
				return false ;
			}
			else
			{
				$the_photo_blog_post_id 			= protect($this->input->post('edit_photo_blog_post_to_category_photo_blog_post_id')) ;
				$the_photo_blog_post_title 			= protect($this->input->post('edit_photo_blog_post_to_category_photo_blog_post_title')) ;
				$the_related_categories				= unserialize(base64_decode(protect($this->input->post('edit_photo_blog_post_to_category_photo_blog_post_related_category_ids')) ) ) ;
				$the_modified_existing_categories	= $this->input->post('edit_photo_blog_post_to_category_existing_cats') ;
				$the_new_category_id				= protect($this->input->post('edit_photo_blog_post_to_category_new_category')) ;
				
				
				$user_id = $this->user_sessions->getUserId() ;
				
				
				$sub = $the_related_categories ;
				if(is_array($the_modified_existing_categories) && ( count($the_modified_existing_categories) > 0 )){
					$sub = array_diff($the_related_categories, $the_modified_existing_categories) ;
				}
				
				$final_sub = array() ;
				foreach($sub as $val){
					array_push($final_sub, $val) ;
				}
				
				// Edit Article Category Relations
				$res = $this->wms_news->editPhotoBlogPostCategoryRelationsInfo($the_photo_blog_post_id, $final_sub, $the_new_category_id) ;
				if($res === true){
					return true;
				}else{
					//Log the Error
					$this->admin_forms->err .= "An error Occured While Editing The Photo Blog Post '$the_photo_blog_post_title'" ;
				}
				return false ;
			}
			
		}else{
			return false ;
		}
	}
	private function showForm_edit_photo_blog_post_to_category($photo_blog_post_id, $return_url){
		$form_html = "" ;			
		$user_id = $this->user_sessions->getuserId() ;
		
		$photo_blog_post_info = $this->wms_news->getPhotoBlogPostInfo($photo_blog_post_id) ;
		if($photo_blog_post_info !== false){
			$photo_blog_post_title = $photo_blog_post_info->title ;
			$photo_blog_post_cover_image_id = $photo_blog_post_info->cover_image_id ;
			
			$cover_image_element = "" ;
			$photo_blog_post_cover_image_info = $this->admin_images->getImageInfo($photo_blog_post_cover_image_id) ;
			if($photo_blog_post_cover_image_info !== false){
				$image_filename = $photo_blog_post_cover_image_info->image_filename ;
				$cover_image_element = $this->admin_images->getImageElement($image_filename, $this->rootdir."images/uploads/") ;
			}
			
			$photo_blog_post_all_category_names = "" ;
			$photo_blog_post_all_category_info_category_ids = array() ;
			$photo_blog_post_all_category_info	= $this->wms_news->getAllCategoriesInfoForAPhotoBlogPost($photo_blog_post_id) ;
			if($photo_blog_post_all_category_info !== false){
				for($j = 0; $j < count($photo_blog_post_all_category_info) ; $j++){
					$this_category_id 		= $photo_blog_post_all_category_info[$j]->category_id ;
					$this_category_name 	= $photo_blog_post_all_category_info[$j]->name ;
					$this_category_status 	= $photo_blog_post_all_category_info[$j]->status ;
					if($this_category_status == 1){
						$photo_blog_post_all_category_names .= $this_category_name ;
						array_push($photo_blog_post_all_category_info_category_ids, $this_category_id) ;
					}
				}
			}
			
			$category_type_id = 1 ;
			$category_arr = array() ;
			$all_category_info = $this->wms_news->getAllInfoCategoriesByCategoryType($category_type_id) ;
			if($all_category_info !== false){
				for($i = 0; $i < count($all_category_info); $i++){
					$category_info = $this->wms_news->getCategoryInfo($all_category_info[$i]['category_id']) ;
					if($category_info !==false){
						$category_id 	= $category_info->category_id ;
						$category_name 	= $category_info->name ;
						$option_array 	= array( " ".$category_id." " => $category_name ) ;
						
						if(array_search($category_id, $photo_blog_post_all_category_info_category_ids) === false ){
							$category_arr 	= array_merge($category_arr, $option_array) ;
						}
						
													
					}
				}//end for all_category_info
			}
			
			//FIELDS
			$form_fields_html = array() ;
			
				//Post ID (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_photo_blog_post_to_category_photo_blog_post_id", "", $photo_blog_post_id) ;
				array_push($form_fields_html, $field) ;
				
				//Post Title (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_photo_blog_post_to_category_photo_blog_post_title", "", $photo_blog_post_title) ;
				array_push($form_fields_html, $field) ;
				
				//Post Category Relations (hidden Field)
				$field = $this->admin_forms->getInputHidden("edit_photo_blog_post_to_category_photo_blog_post_related_category_ids", "", base64_encode(serialize($photo_blog_post_all_category_info_category_ids))) ;
				array_push($form_fields_html, $field) ;
				
				//Post Title
				$field = $this->admin_forms->getInputCustom("text", "Post Title:", "", "long_input", "", "Post Title", "disabled", "", $photo_blog_post_title, false) ;
				array_push($form_fields_html, $field) ;
				
				$field = "" ;
				if($photo_blog_post_all_category_info !== false){
					if(count($photo_blog_post_all_category_info) > 0){
						$field .= "<u>Related Categories:</u>" ;
						
						for($j = 0; $j < count($photo_blog_post_all_category_info) ; $j++){
							$this_category_id = $photo_blog_post_all_category_info[$j]->category_id ;
							$this_category_name = $photo_blog_post_all_category_info[$j]->name ;
							$this_category_status = $photo_blog_post_all_category_info[$j]->status ;
							if($this_category_status == 1){
								$photo_blog_post_all_category_names .= $this_category_name ;
								$field .= "<div class='block_auto'>" ;
									$field .= "<input type='checkbox' name='edit_photo_blog_post_to_category_existing_cats[]' value='".$this_category_id."' checked />" ;
									$field .= "<label class=''>".$this_category_name."</label>" ;
								$field .= "</div>" ;
							}
						}//end for
						$field .= "<br/>" ;
					}
				}
				array_push($form_fields_html, $field) ;
				
				
				//Category Type (Select Field)
				$field = $this->admin_forms->getRegularSelect("Select A Site Info Category (Optional):", "edit_photo_blog_post_to_category_new_category", "long_input", "", "", true, array(""=>"-- Select A Category --"), $category_arr ) ;
				array_push($form_fields_html, $field) ;
				
				
				//Form submit button
					$submit_field = $this->admin_forms->getSubmitButtonFieldType2("Save", "Save & Close", "edit_photo_blog_post_to_category_submit", "edit_photo_blog_post_to_category_submit_and_close", "", "", "submit", "submit", true, $return_url, "Cancel") ;
				
			//Get Form HTML	
			$form_html = $this->admin_forms->getRegularForm("Edit Photo Blog Post to Category Relationship", $form_fields_html, $submit_field, "post", "", "", "block_auto inline_label") ;
		}
		
		return $form_html ;
	}
	
	
	
	
	
	public function add_an_image(){
		return $this->admin_images->addAnImage_FormHandler() ;
	}
	
	public function edit_an_image($image_id){
		return $this->admin_images->editAnImage_FormHandler($image_id) ;
	}
	
	public function view_all_images($index_p1, $index_p2, $index_p3, $index_p4, $index_p5){
		return $this->admin_images->viewAllImages("admin/index/news/view_all_images/", $index_p1, $index_p2, $index_p3, $index_p4, $index_p5);
	}
	
	
	public function add_new_page(){
		//Check User Permission
		$user_id = $this->user_sessions->getUserId() ;
		$permission_result = $this->checkUserOptionalAccountPermission($user_id, 'add_new_page') ;
		if($permission_result === true){
			return $this->wms_pages_gui->add_new_page() ;
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg) ;
		}
	}
	public function edit_page_info($page_id){
		//Check User Permission
		$user_id = $this->user_sessions->getUserId() ;
		$permission_result = $this->checkUserOptionalAccountPermission($user_id, 'add_new_page') ;
		if($permission_result === true){
			return $this->wms_pages_gui->edit_page_info($page_id) ;
		}else{
			// This User Does Not Have Permission To Carry Out this Action
			$msg = "This User ".$this->user_sessions->getUserEmail($this->user_sessions->getUserId())." does Not Have Permission To Carry Out this Action" ;
			$o .= $this->prepareErrorMessage($msg) ;
		}
	}
	public function view_all_pages($page_number = "", $no_of_items = "", $sort_by = "", $display_type = ""){	
		$o = "" ;
		
			$user_id = $this->user_sessions->getUserId() ;
			
			$rootdir 						= $this->rootdir;
			$this_uri 						= "admin/index/news/view_all_pages/" ;
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
					$o .= "<span>View All Pages</span>" ;
				$o .= "</div>" ;
				$o .= "<div class='view-items-display block_left'>" ;
					$o .= "<span>Display:</span>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '')."' >All</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '1')."' >Published</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '2')."' >Pending</a>" ;
					$o .= "<a href='".$this->getDisplayTypeUrl($rootdir, $this_uri, $limit_start_no, $sort_items_by, $no_of_items_to_show, '0')."' >Suspended</a>" ;
				$o .= "</div>" ;
				$o .= "<div class='view-items-limit block_left'>" ;
					$o .= "<span>Show:</span>" ;
				
					$all_options = array() ;
					
					$all_options = array(
		//				$this->getLimitOptionsValue(1, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 1,
		//				$this->getLimitOptionsValue(2, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 2,
		//				$this->getLimitOptionsValue(5, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 5,
		//				$this->getLimitOptionsValue(10, $rootdir, $this_uri, $limit_start_no, $sort_items_by, $items_display_type) => 10,
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
					$this->getSortByOptionsValue('iii', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Publish Status',
					$this->getSortByOptionsValue('iv', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Time (New - Old)',
					$this->getSortByOptionsValue('v', $rootdir, 	$this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) => 'Time (Old - New)',
					) ;
					
					$selected_value = $this->getSortByOptionsValue($sort_items_by, $rootdir, $this_uri, $limit_start_no, $no_of_items_to_show, $items_display_type) ;
					$js = "onchange='location = this.value;'" ;
					$o .= form_dropdown('selected_sort_by', $all_options, $selected_value, $js );
				
				$o .= "</div>" ;
			$o .= "</div>" ;

			//Get Pages Table
			$table = "" ;
				
				$order = $this->getPageSortByValue($sort_items_by) ;
				$start_limit = $no_of_items_to_show * $limit_start_no ;
				$limit = "LIMIT ".$start_limit.",".$no_of_items_to_show ;
				
				$extra_where_clause = "AND publish_status != 9" ;
				if($items_display_type != ''){
					$extra_where_clause = "AND publish_status = ".$display_type."" ;
				}
				$pages = $this->wms_pages->getAllPages($user_id, $extra_where_clause, $order, $limit) ;
				if($pages !== false){
					$table .= "<table class='table table-bordered table-condensed'>" ;
					$table .= "<tr>" ;
						$table .= "<th>No</th>" ;
						$table .= "<th>Title</th>" ;
						$table .= "<th>Status</th>" ;
						$table .= "<th>Cover Image</th>" ;
						$table .= "<th>&nbsp;</th>" ;
						$table .= "<th>Options</th>" ;
					$table .= "</tr>" ;
					for($i = 0; $i < count($pages) ; $i++){
						$page_info 		= $this->wms_pages->getPageInfo($pages[$i]['page_id']) ;
						if($page_info !== false){
							$page_id 				= $page_info->page_id ;
							$page_publish_status 	= $page_info->publish_status ;
							$page_title 			= $page_info->title ;
							$page_cover_image_preview = "" ;
							$page_cover_image_id 	= $page_info->cover_image_id ;
							if($page_cover_image_id != ""){
								$page_cover_image_info = $this->admin_images->getImageInfo($page_cover_image_id) ;
								if($page_cover_image_info !== false){
									$page_cover_image_filename 	= $page_cover_image_info->image_filename ;
									$page_cover_image_preview 	= $this->admin_images->previewImage($page_cover_image_filename, $this->rootdir."images/uploads/", '100px', '', "title = '".$page_title."'") ;
								}
							}
							
							
							$this_page_publish_status_name = "" ;
							if($page_publish_status == 1){
								$this_page_publish_status_name = 'Published' ;
							}else if($page_publish_status == 2){
								$this_page_publish_status_name = 'Pending' ;
							}else if($page_publish_status == 0){
								$this_page_publish_status_name = 'Suspended' ;
							}
								
							
					//		$pages_body 			= getFirstXLetters(html_entity_decode($pages_info->full_text) ) ;					
							
							//ROW COLUMNS
							$row = "" ;
							$row = "<tr>" ;
								$row .= "<td class='view-item-title'><div>".($i + 1 + ($limit_start_no * $no_of_items_to_show) )."</div>" ;
								$row .= "<td class='view-item-title'><div>".$page_title."</div>" ;
								$row .= "<td class='view-item-title'><div>".$this_page_publish_status_name."</div>" ;
								$row .= "<td class='view-item-title'><div>".$page_cover_image_preview."</div>" ;
								$row .= "<td class='view-item-title'><div>&nbsp;</div>" ;
								$row .= "<td class='view-item-title'><div>";
								
									$row .= "<a class='img-view-item block_left' target='_blank' href='".$rootdir."admin/index/news/view_individual_page/".$page_id."' title='View'></a>" ;
									
									$row .= "<a class='img-edit-item block_left' href='".$rootdir."admin/index/news/edit_page_info/".$page_id."' title='Edit'></a>" ;
									
									
									if($this->user_sessions->getUserPrivilege($user_id) == '10'){
										$row .= "<a class='img-delete-item block_left' href='".$rootdir."admin/index/news/delete_a_page/".$page_id."' title='Delete'></a>" ;
										
									}
									
								$row .= "</div>" ;
							$row .= "</tr>" ;
							
							$table .= $row ;
							
						}//end if page_info
					}// end for
					$table .= "</table>" ;
				}
			$o .= $table ;
			
			//Get Sub Pages links
				$p = "" ;
				$p .= "<div class='block_auto view-items-subpages' >" ;
					$extra_where_clause = "AND publish_status != 9" ;
					if($items_display_type != ''){
						$extra_where_clause = "AND publish_status = ".$display_type."" ;
					}
					$full_pages = $this->wms_pages->getAllPages($user_id, $extra_where_clause ) ;
					if($full_pages !== false){
						$full_count = count($full_pages) ;
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
	private function getPageSortByValue($sort_param){
		switch($sort_param){
			case 'default': 	return "ORDER BY time_created DESC" ; 					break ;
			case 'i':		 	return "ORDER BY title" ; 		break ;
			case 'ii':		 	return "ORDER BY title DESC" ; 	break ;
			case 'iii':		 	return "ORDER BY publish_status" ; 		break ;
			case 'iv':		 	return "ORDER BY time_created DESC" ; 	break ;
			case 'v':		 	return "ORDER BY time_created" ; 		break ;
			default: 			return "" ; 					break ;
		}
	}
	
	
	public function getActionRequestError(){
		$o = "" ;
		$o .= "<div class='spacer-very-large'></div>" ;
		$o .= "<div id='form-error' class='pad_all'>The Action you requested for could not be carried out. ";
			$o .= "<a href=".$this->rootdir."admin/index/".$this->menu_selection.">Go Back</a>" ;
		$o .= "</div>" ;
		$o .= "<div class='spacer-very-large'></div>" ;
		return $o ;
	}
	
	public function prepareErrorMessage($msg, $back_link = ""){
		$o = "" ;
		
		if($back_link == ""){
			$back_link = $this->rootdir."admin/index/".$this->menu_selection ;
		}
		
		$o .= "<div class='spacer-large'></div>" ;
		$o .= "<div id='form-error' class='pad_all'><p>".$msg."</p> ";
			$o .= "<a href=".$back_link.">Back</a>" ;
		$o .= "</div>" ;
		$o .= "<div class='spacer-large'></div>" ;
		return $o ;
	}
	public function prepareSuccessMessage($msg, $back_link = ""){
		$o = "" ;
		
		if($back_link == ""){
			$back_link = $this->rootdir."admin/index/".$this->menu_selection ;
		}
		
		$o .= "<div class='spacer-large'></div>" ;
		$o .= "<div id='form-success' class='pad_all'><p>".$msg."</p> ";
			$o .= "<a href='".$back_link."' >Back</a>" ;
		$o .= "</div>" ;
		$o .= "<div class='spacer-large'></div>" ;
		return $o ;
	}
	
}
?>