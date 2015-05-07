<?php
/*
	System API version 1.0
*/
class Wms_logger extends CI_Model{
	
	//Constructor
	function __construct(){
	// Call the Model constructor
        parent::__construct();
		$this->rootdir = $this->config->item('base_url') ;
	//	$this->rootdir = "http://myteenslife.com/" ;
		
		$this->load->library('user_agent');
		
		$this->load->model('user_sessions') ;
	}
	
	private $user_id ;
	public $rootdir ;
	
	
	/** GENERAL PAGE LOGGING **/
	/* Returns true on sucess */
	public function logThisPageVisit($user_id, $page_url, $page_name, $page_description, $ip, $forwarded_ip, $user_agent){
		date_default_timezone_set("GMT") ;
		$current_datetime = date( 'Y-m-d H:i:s' ) ;
		
		$page_view_id = creatAnId('general_page_view_log', 'page_view_id') ;
		$status = 1 ;
		$query = "INSERT INTO general_page_view_log VALUES('$page_view_id', '$user_id', '$ip', '$forwarded_ip', '$user_agent', '$page_url', '$page_name', '$page_description', '$status', '$current_datetime')" ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	
	public function getTotalOverallPageHits($year, $month = "", $day = "", $order_by = "hits DESC"){
		$o = "" ;
		$hits = 0 ;
		date_default_timezone_set("GMT") ;
				
		if($year != ""){
			$query = "SELECT SUM(hits) AS total_page_hits FROM site_usage_pages_yearly WHERE year = '$year' ORDER BY ".$order_by." " ;
			if( ($month != '') && ($day != '') ){
				$query = "SELECT  SUM(hits) AS total_page_hits FROM site_usage_pages_daily WHERE year = '$year' AND month = '$month' AND day = '$day' ORDER BY ".$order_by." " ;
			}else if($month != ''){
				$query = "SELECT  SUM(hits) AS total_page_hits FROM site_usage_pages_monthly WHERE year = '$year' AND month = '$month' ORDER BY ".$order_by." " ;
			}
			$query = $this->db->query($query) ;
			if($query->num_rows() > 0){
				$res = $query->result_array() ;
				$hits = $res[0]['total_page_hits'] ;
				
				return $hits ;
			}
		}
		return $hits ;
	}
	
	public function getTotalPageHits(){
		$o = "" ;
		$query = "SELECT SUM(times_viewed) as total_page_hits FROM site_usage_page_view_count " ;
		$query = $this->db->query($query) ;
		if($query->num_rows() > 0){
			$res = $query->result_array() ;
			return $res[0]['total_page_hits'] ;
		}
		return $o ;
	}
	public function getTotalPageHitsByTimeLimit($time_limit = ""){
		//	$total_hit_info = array() ;
		
		return $this->getHitCountByOnlyTimeLimit($time_limit) ;
	}
	
	public function getTotalPageHitsByPageName($page_name){
		$o = "" ;
		
		if($page_name != ""){
			$query1 = "SELECT page_id FROM site_usage_page_info WHERE page_name = '".$page_name."'" ;
			$query1 = $this->db->query($query1) ;
			if($query1->num_rows() > 0){
				$res = $query1->result_array() ;
				$page_id = $res[0]['page_id'] ;
				
				if($page_id != ""){
					$query = "SELECT SUM(times_viewed) as total_page_hits FROM site_usage_page_view_count WHERE page_id = ".$page_id." " ;
					$query = $this->db->query($query) ;
					if($query->num_rows() > 0){
						$res = $query->result_array() ;
						return $res[0]['total_page_hits'] ;
					}
				}
			}
		}
		return $o ;
	}
	
	public function getTotalPageHitsByPageNameAndYearMonthDay($page_name, $year, $month = "", $day = "", $order_by = "hits DESC"){
		$o = "" ;
		$hits = 0 ;
		date_default_timezone_set("GMT") ;
				
		if($page_name != ""){
			$query = "SELECT hits FROM site_usage_pages_yearly WHERE page_name = '$page_name' AND year = '$year' ORDER BY ".$order_by." " ;
			if( ($month != '') && ($day != '') ){
				$query = "SELECT hits FROM site_usage_pages_daily WHERE page_name = '$page_name' AND year = '$year' AND month = '$month' AND day = '$day' ORDER BY ".$order_by." " ;
			}else if($month != ''){
				$query = "SELECT hits FROM site_usage_pages_monthly WHERE page_name = '$page_name' AND year = '$year' AND month = '$month' ORDER BY ".$order_by." " ;
			}
			$query = $this->db->query($query) ;
			if($query->num_rows() > 0){
				$res = $query->result_array() ;
				$hits = $res[0]['hits'] ;
				
				return $hits ;
			}
		}
		return $hits ;
	}
	
	
	public function getTotalPageHitsByPageURLExact($string, $prefix = 'http://myteenslife.com/'){
		$url = $prefix.$string ;
		
		$total_page_hits = 0 ;
		
		if($url != ""){
			$query1 = "SELECT DISTINCT page_id FROM site_usage_page_info WHERE page_url = '".$url."'" ;
			$query1 = $this->db->query($query1) ;
			if($query1->num_rows() > 0){
				$res1 = $query1->result_array() ;
				
				for($i = 0; $i < $query1->num_rows(); $i++){
					$page_id = $res1[$i]['page_id'] ;
					
					if($page_id != ""){
						$query = "SELECT SUM(times_viewed) as total_page_hits FROM site_usage_page_view_count WHERE page_id = ".$page_id." " ;
						$query = $this->db->query($query) ;
						if($query->num_rows() > 0){
							$res = $query->result_array() ;
							$total_page_hits += $res[0]['total_page_hits'] ;
						}		
					}	
				}
			}	
		}
		return $total_page_hits ;
	}
	
	public function getTotalPageHitsByPageURLExactAndTimeLimit($string, $prefix = 'http://myteenslife.com/'){
		$url = $prefix.$string ;
		
		$total_page_hits = 0 ;
		
		if($url != ""){
			$query1 = "SELECT DISTINCT page_id FROM site_usage_page_info WHERE page_url = '".$url."'" ;
			$query1 = $this->db->query($query1) ;
			if($query1->num_rows() > 0){
				$res1 = $query1->result_array() ;
				
				for($i = 0; $i < $query1->num_rows(); $i++){
					$page_id = $res1[$i]['page_id'] ;
					
					if($page_id != ""){
						$total_page_hits = $this->getHitCountByPageIDAndTimeLimit($page_id, $time_limit = "" ) ;	
					}	
				}
			}	
		}
		return $total_page_hits ;
	}
	
	
	public function getHitCountByOnlyTimeLimit($time_limit = ""){
		$hits = 0 ;
		date_default_timezone_set("GMT") ;
		
		$query = "SELECT unique_user_and_page_id FROM site_usage_page_view_count " ;
		$query = $this->db->query($query) ;
		if($query->num_rows() > 0){
			$res 	= $query->result_array() ;
			$hits 	=  0 ;
			
			$query4 = "SELECT COUNT(unique_user_and_page_id) as hits FROM site_usage_page_view WHERE (" ;
			$query_count = $query->num_rows() ;
			for($j = 0; $j < $query_count; $j++){
				$unique_user_and_page_id 	= $res[$j]['unique_user_and_page_id'] ;
				$query4 .= " unique_user_and_page_id = '".$unique_user_and_page_id."' " ;
				if($j < ($query_count - 1) ){
					$query4 .= " OR " ;
				}else{
					$query4 .= " ) " ;
				}
			}
			if($time_limit != ""){
				$limited_time = integer_to_timestamp( getcurrtimeinfo() - $time_limit ) ;
				$query4 .= " AND time > '$limited_time' " ;
			}
			
//				echo "<h4>$query4</h4>" ;
			
			$query4 = $this->db->query($query4) ;
			if($query4->num_rows() > 0){
				$res4 	= $query4->result_array() ;
				$hits 	= $res4[0]['hits'] ;
//					echo "<h4>hits: $hits</h4>" ;
			}
			
//			echo "hits:$hits<hr/>" ;
			
			return $hits ;
			
		}// end if($query->num_rows() > 0)
		return $hits ;
	}
	
	public function getHitCountByPageIDAndTimeLimit($page_id, $time_limit = "" ){
		$hits = 0 ;
		date_default_timezone_set("GMT") ;
		
		if( ($page_id != "") ){
			$query = "SELECT unique_user_and_page_id FROM site_usage_page_view_count WHERE page_id = '".$page_id."' " ;
			$query = $this->db->query($query) ;
			if($query->num_rows() > 0){
				$res 	= $query->result_array() ;
				$hits 	=  0 ;
				
				$query4 = "SELECT COUNT(unique_user_and_page_id) as hits FROM site_usage_page_view WHERE (" ;
				$query_count = $query->num_rows() ;
				for($j = 0; $j < $query_count; $j++){
					$unique_user_and_page_id 	= $res[$j]['unique_user_and_page_id'] ;
					$query4 .= " unique_user_and_page_id = '".$unique_user_and_page_id."' " ;
					if($j < ($query_count - 1) ){
						$query4 .= " OR " ;
					}else{
						$query4 .= " ) " ;
					}
				}
				if($time_limit != ""){
					$limited_time = integer_to_timestamp( getcurrtimeinfo() - $time_limit ) ;
					$query4 .= " AND time > '$limited_time' " ;
				}
								
				$query4 = $this->db->query($query4) ;
				if($query4->num_rows() > 0){
					$res4 	= $query4->result_array() ;
					$hits 	= $res4[0]['hits'] ;
				}
				
//				echo "hits:$hits<hr/>" ;
				
				return $hits ;
				
			}// end if($query->num_rows() > 0)
		}
		return $hits ;
	}
	
	
	
	public function getListOfContentViewedByPageURLWildcard($content_type, $string, $prefix = 'http://myteenslife.com/'){
		$o = "" ;
		
		$url = $prefix.$string ;
		
		$content_page_hit_info = array() ;
		
		if($url != ""){
			$query1 = "SELECT DISTINCT page_id, page_description FROM site_usage_page_info WHERE page_url LIKE '%".$url."%'" ;
			$query1 = $this->db->query($query1) ;
			if($query1->num_rows() > 0){
				$res1 = $query1->result_array() ;
				
				$content_id_array = array() ;
				for($i = 0; $i < $query1->num_rows(); $i++){
					$page_id 			= $res1[$i]['page_id'] ;
					$page_description 	= $res1[$i]['page_description'] ;
					
					if($page_description != ""){
						$desc_string 		= explode("|", $page_description) ;
						$content_id 		= trim(end($desc_string)) ;
						
						if(array_search($content_id, $content_id_array) === false){
							$content_id_array[] = $content_id ;
						}else{
							continue ;
						}
						
						if( ($content_id != "") && (is_numeric($content_id) ) ){
							
							$query = "SELECT SUM(times_viewed) as total_page_hits FROM site_usage_page_view_count WHERE page_id = ".$page_id." " ;
							$query = $this->db->query($query) ;
							if($query->num_rows() > 0){
								$res 				= $query->result_array() ;
								$content_page_hits 	= $res[0]['total_page_hits'] ;
								
						//		$content_info_crosscheck 	= array() ;
						//		$content_info_id_backer 	= array() ;
								
								if($content_page_hits > 0){
								
									$content_info 	= $this->customLogGetContentInfo($content_type, $content_id) ;
									if($content_info != ""){
										
						//				echo "content_title:$content_title , content_page_hits:$content_page_hits" ;
										// Title, Author Name, Date Published, Contributor Profile Link
								
								/*		
										$search_key = array_search($content_id, $content_info_crosscheck) ;
								//		echo "<h2>".$search_key."</h2>" ;
										if($search_key !== false){
											$arr_key = $content_info_id_backer[$content_id] ;
											$content_page_hit_info[$search_key]['hits'] += $content_page_hits ;
										}else{
								*/
										$content_page_hit_info[] = array(
																	"title" 	=> $content_info[0],
																	"author" 	=> $content_info[1],
																	"date" 		=> $content_info[2],
																	"link" 		=> $content_info[3],
																	"hits" 		=> $content_page_hits
										) ;
								/*			$content_info_crosscheck[] = array($content_id ) ;
											$content_info_id_backer[] = array($content_id => count($content_page_hit_info)) ;
										}
								*/
									}
								}
							}// end if($query->num_rows() > 0)
						}// end if($content_id != "" )
					}// end if($page_description != "")	
				}
				return $content_page_hit_info ;
			}	
		}
		return $o ;
	}
	
/*	public function getListOfContentViewedByPageURLWildcardAndTimeLimit($content_type, $string, $time_limit = "", $prefix = 'http://myteenslife.com/'){
		$o = "" ;
		date_default_timezone_set("GMT") ;
		
		$url = $prefix.$string ;
		
		$content_page_hit_info = array() ;
		
		if($url != ""){
			$query1 = "SELECT DISTINCT page_id, page_description FROM site_usage_page_info WHERE page_url LIKE '%".$url."%'" ;
			$query1 = $this->db->query($query1) ;
			if($query1->num_rows() > 0){
				$res1 = $query1->result_array() ;
				
				for($i = 0; $i < $query1->num_rows(); $i++){
					$page_id 			= $res1[$i]['page_id'] ;
					$page_description 	= $res1[$i]['page_description'] ;
					
					if($page_description != ""){
						$desc_string 		= explode("|", $page_description) ;
						$content_id 		= trim(end($desc_string)) ;
						
						if( ($content_id != "") && (is_numeric($content_id) ) ){
							
							$query = "SELECT unique_user_and_page_id FROM site_usage_page_view_count WHERE page_id = ".$page_id." " ;
							$query = $this->db->query($query) ;
							if($query->num_rows() > 0){
								$res 				= $query->result_array() ;
								$content_page_hits 	= 0 ;
								
								$query4 = "SELECT COUNT(unique_user_and_page_id) as content_page_hits FROM site_usage_page_view WHERE (" ;
								$query_count = $query->num_rows() ;
								for($j = 0; $j < $query_count; $j++){
									$unique_user_and_page_id 	= $res[$j]['unique_user_and_page_id'] ;
									$query4 .= " unique_user_and_page_id = '".$unique_user_and_page_id."' " ;
									if($j < ($query_count - 1) ){
										$query4 .= " OR " ;
									}else{
										$query4 .= " ) " ;
									}
								}
								if($time_limit != ""){
									$limited_time = integer_to_timestamp( getcurrtimeinfo() - $time_limit ) ;
									$query4 .= " AND time > '$limited_time' " ;
								}
								
				//				echo "<h4>$query4</h4>" ;
								
								$query4 = $this->db->query($query4) ;
								if($query4->num_rows() > 0){
									$res4 				= $query4->result_array() ;
									$content_page_hits 	= $res4[0]['content_page_hits'] ;
				//					echo "<h4>content_page_hits: $content_page_hits</h4>" ;
								}
				
								if($content_page_hits > 0){
								
									$content_info 	= $this->customLogGetContentInfo($content_type, $content_id) ;
									
									if($content_info != ""){
									
						//				echo "content_title:$content_title , content_page_hits:$content_page_hits" ;
										// Title, Author Name, Date Published, Contributor Profile Link
										
										$content_page_hit_info[] = array(
																	"title" 	=> $content_info[0],
																	"author" 	=> $content_info[1],
																	"date" 		=> $content_info[2],
																	"link" 		=> $content_info[3],
																	"hits" 		=> $content_page_hits
										) ;	
									}
									
								}
							}// end if($query->num_rows() > 0)
						}// end if($content_id != "" )
					}// end if($page_description != "")	
				}
				return $content_page_hit_info ;
			}	
		}
		return $o ;
	}
*/	

	public function getListOfContentViewedByYearMonthDay($content_type_string, $year, $month = "", $day = "", $order_by = "hits DESC"){
		$o = "" ;
		date_default_timezone_set("GMT") ;
				
		$content_page_hit_info = array() ;
		
		$content_type = "" ;
		
		switch($content_type_string){
			case	'article' 	: $content_type = 1; break ;
			case	'video' 	: $content_type = 2; break ;
			case	'photo' 	: $content_type = 3; break ;
			default				: break ;
		}
		
		if($content_type != ""){
			
			$query = "SELECT content_id, content_type, content_title, author_user_id, author_name, date_published, hits FROM site_usage_content_yearly WHERE content_type = '$content_type' AND year = '$year' ORDER BY ".$order_by." " ;
			if( ($month != '') && ($day != '') ){
				$query = "SELECT content_id, content_type, content_title, author_user_id, author_name, date_published, hits FROM site_usage_content_daily WHERE content_type = '$content_type' AND year = '$year' AND month = '$month' AND day = '$day' ORDER BY ".$order_by." " ;
			}else if($month != ''){
				$query = "SELECT content_id, content_type, content_title, author_user_id, author_name, date_published, hits FROM site_usage_content_monthly WHERE content_type = '$content_type' AND year = '$year' AND month = '$month' ORDER BY ".$order_by." " ;
			}
			$query = $this->db->query($query) ;
			if($query->num_rows() > 0){
				$content_info = $query->result_array() ;
				for($i = 0; $i < $query->num_rows(); $i++){	
					// content_id, content_type, content_title, author_user_id, author_name, date_published, hits
					// Title, Author Name, Date Published, Contributor Profile Link
					
					$content_page_hit_info[] = array(
						"title" 			=> $content_info[$i]['content_title'],
						"author" 			=> $content_info[$i]['author_name'],
						"date" 				=> $content_info[$i]['date_published'],
						"author_user_id" 	=> $content_info[$i]['author_user_id'],
						"hits" 				=> $content_info[$i]['hits']
					) ;
				}
				
		//		print_r($content_page_hit_info) ;
				
				return $content_page_hit_info ;
			}	
		}
		return $o ;
	}
	
	
	public function customLogGetContentTitle($content_type, $content_id){
		if( ($content_type != "") && ($content_id != "") ){
			switch($content_type){
				case 'article' :	$query2 = "SELECT title FROM articles WHERE article_id = ".$content_id." " ;
									$query2 = $this->db->query($query2) ;
									if($query2->num_rows() > 0){
										$res2 	= $query2->result_array() ;
										$content_title 	= $res2[0]['title'] ;
										return $content_title ;
									}
									break ;
				
				case 'video' :		$query2 = "SELECT title FROM videos WHERE video_id = ".$content_id." " ;
									$query2 = $this->db->query($query2) ;
									if($query2->num_rows() > 0){
										$res2 	= $query2->result_array() ;
										$content_title 	= $res2[0]['title'] ;
										return $content_title ;
									}
									break ;
				
				case 'photo' :		$query2 = "SELECT title FROM photo_blog WHERE photo_blog_post_id = ".$content_id." " ;
									$query2 = $this->db->query($query2) ;
									if($query2->num_rows() > 0){
										$res2 	= $query2->result_array() ;
										$content_title 	= $res2[0]['title'] ;
										return $content_title ;
									}
									break ;
			}
		}
		return "" ;
	}
	
	
	public function customLogGetContentInfo($content_type, $content_id){
		if( ($content_type != "") && ($content_id != "") ){
			switch($content_type){
				case 'article' :	$the_content_info 	= $this->wms_news->getArticleInfo($content_id) ;
									if($the_content_info !== false){
										//Title, Author Name, Date Published, Contributor Profile Link, Contributor User ID
										$content_title	 	= $the_content_info->title ;
										
										$content_author = "" ;
										$the_author_info = $this->wms_news->getAuthorUserInfo($the_content_info->author_user_id) ;
										if($the_author_info !== false){
											
											$content_author = $the_author_info->fname." ".$the_author_info->lname ;
											if($the_author_info->contributor !== false){
												if($the_author_info->contributor->name != ""){
													$content_author = $the_author_info->contributor->name ;
												}
											}
										}
										
										$content_contributor_profile_link = $this->wms_news->rootdir."contributors/".$the_content_info->author_user_id."/".underscored_string($content_author) ;
										
										date_default_timezone_set('GMT');
										$content_date_published 	= getdateinfo_newstype2( $the_content_info->time_published ) ;
										
										return array($content_title, $content_author, $content_date_published, $content_contributor_profile_link, $the_content_info->author_user_id) ;
									}
									break ;
				
				case 'video' :		$the_content_info 	= $this->wms_news->getVideoInfo($content_id) ;
									if($the_content_info !== false){
										//Title, Author Name, Date Published, Contributor Profile Link, Contributor User ID
										$content_title	 	= $the_content_info->title ;
										
										$content_author = "" ;
										$the_author_info = $this->wms_news->getAuthorUserInfo($the_content_info->author_user_id) ;
										if($the_author_info !== false){
											
											$content_author = $the_author_info->fname." ".$the_author_info->lname ;
											if($the_author_info->contributor !== false){
												if($the_author_info->contributor->name != ""){
													$content_author = $the_author_info->contributor->name ;
												}
											}
										}
										
										$content_contributor_profile_link = $this->wms_news->rootdir."contributors/".$the_content_info->author_user_id."/".underscored_string($content_author) ;
										
										date_default_timezone_set('GMT');
										$content_date_published 	= getdateinfo_newstype2( $the_content_info->time_published ) ;
										
										return array($content_title, $content_author, $content_date_published, $content_contributor_profile_link, $the_content_info->author_user_id) ;
									}
									break ;
				
				case 'photo' :		$the_content_info 	= $this->wms_news->getPhotoBlogPostInfo($content_id) ;
									if($the_content_info !== false){
										//Title, Author Name, Date Published, Contributor Profile Link, Contributor User ID
										$content_title	 	= $the_content_info->title ;
										
										$content_author = "" ;
										$the_author_info = $this->wms_news->getAuthorUserInfo($the_content_info->author_user_id) ;
										if($the_author_info !== false){
											
											$content_author = $the_author_info->fname." ".$the_author_info->lname ;
											if($the_author_info->contributor !== false){
												if($the_author_info->contributor->name != ""){
													$content_author = $the_author_info->contributor->name ;
												}
											}
										}
										
										$content_contributor_profile_link = $this->wms_news->rootdir."contributors/".$the_content_info->author_user_id."/".underscored_string($content_author) ;
										
										date_default_timezone_set('GMT');
										$content_date_published 	= getdateinfo_newstype2( $the_content_info->time_published ) ;
										
										return array($content_title, $content_author, $content_date_published, $content_contributor_profile_link, $the_content_info->author_user_id) ;
									}
									break ;
			}
		}
		return "" ;
	}
	
	public function getPageVisitData($time_logged_param = "" ){
		date_default_timezone_set("GMT") ;
		$not_like_array = array('Yahoo','Facebook','Google','Twitter','Pinterest','Bot','Robot','SiteLockSpider','Spider','msnbot') ;
		$not_likes = "" ; 
		$not_like_count = count($not_like_array) ;
		if($not_like_count > 0){
			for($i = 0; $i < count($not_like_array); $i++){
				$not_likes .= " user_agent NOT LIKE '%".$not_like_array[$i]."%' " ;
				if($i < ($not_like_count - 1) ){
					$not_likes .= "AND " ;
				}
			}
		}
		
		$query = "SELECT page_view_id, user_id, page_url, page_name, status, time_logged FROM general_page_view_log WHERE ".$not_likes ;
		if($time_logged_param != "" ){
			$query = "SELECT page_view_id, user_id, page_url, page_name, status, time_logged FROM general_page_view_log WHERE ".$not_likes." AND ".$time_logged_param ;
		}
		$query = $this->db->query($query) ;
		$all_log_info = array() ;
		
		$query_count = $query->num_rows() ;
		$result_array = $query->result_array() ;
				
		for($i = 0; $i < $query_count ; $i++){				
			//page_view_id, user_id, ip_address, forwarded_ip, user_agent, page_url, page_name, page_description, status, time_logged
			$result_obj = $result_array[$i] ;
						
			$log_obj = array() ;
	
			$log_obj['page_view_id']		= $result_obj['page_view_id'] ;
			$log_obj['user_id']				= $result_obj['user_id'] ;
	//		$log_obj['ip_address'] 			= $result_obj['ip_address'] ;
	//		$log_obj['forwarded_ip'] 		= $result_obj['forwarded_ip'] ;
	//		$log_obj['user_agent'] 			= $result_obj['user_agent'] ;
			$log_obj['page_url'] 			= $result_obj['page_url'] ;
			$log_obj['page_name'] 			= $result_obj['page_name'] ;
	//		$log_obj['page_description'] 	= $result_obj['page_description'] ;
			$log_obj['status'] 				= $result_obj['status'] ;
			$log_obj['time_logged'] 		= $result_obj['time_logged'] ;
			
			$all_log_info[] = $log_obj ;
		}
		
		if(count($all_log_info) > 0){
			return $all_log_info ;
		}
		return false ;
	}
	
	public function compareIndexOfURLForIncrement($page_url, $tag, $current_page_count){
		$needle = $this->rootdir.$tag ;
		if(strstr($page_url, $needle) !== false){
			$current_page_count += 1 ;
		}
		return $current_page_count ;
	}
	public function compareExactURLForIncrement($page_url, $tag, $current_page_count){
		$needle = $this->rootdir.$tag ;
		if( $page_url == $needle){
			$current_page_count += 1 ;
		}
		return $current_page_count ;
	}
	public function compareExactValueForIncrement($value, $other_value, $current_page_count){
//		echo "$value == $other_value" ;
		if($value == $other_value){
			$current_page_count += 1 ;
		}
		return $current_page_count ;
	}
	
	public function comparePageForIncrement($tag, $current_page_count){
		$needle = $this->rootdir.$tag ;
		if(strstr($page_url, $needle) !== false){
			$current_page_count += 1 ;
		}
		return $current_page_count ;
	}
	
	public function ah_site_usage_populator($min, $max){
		
		$query_1 	= "SELECT * FROM general_page_view_log ORDER BY time_logged LIMIT $min, $max" ;
		$query_1 = $this->db->query($query_1) ;
		
		$records_count = $query_1->num_rows() ;
		
		if($records_count > 0){
			
			$result_array = $query_1->result_array() ;
			
			for($i = 0; $i < $records_count; $i++ ){
				$row = $result_array[$i] ;
				// page_view_id, user_id, ip_address, forwarded_ip, user_agent, page_url, page_name, page_description, status, time_logged
	//			echo "<p>$i ".$row['user_id'], $row['page_url'], $row['page_name'], $row['page_description'], $row['ip_address'], $row['forwarded_ip'], $row['user_agent'], $row['time_logged']."<br/></p>" ;
				
	//			$this->advancedLogThisPageVisit($row['user_id'], $row['page_url'], $row['page_name'], $row['page_description'], $row['ip_address'], $row['forwarded_ip'], $row['user_agent'], $row['time_logged']);
	//			echo "<p>".$i."</p>" ;
			}
		}
		
		echo "<h2>".$records_count."<h2>" ;
	}
	
	public function advancedLogThisPageVisit($user_id, $page_url, $page_name, $page_description, $ip, $forwarded_ip, $user_agent, $referring_url, $time_logged = ""){
		
		if(!is_bot($user_agent) ){
		
			$page_url = prepare_my_domain_url($page_url) ;
			
			$page_name = $this->getPageNameFromPageDetails($page_url, $page_description, $page_name) ;
			
			// LOG CONTENT VISIT (Only works for visits to article, video, and photo view pages)
			$this->logThisPageContentInfo($page_name, $page_description) ;
			
			/*
				-> Check If Page Info Exists in site_usage_page_info Table
				-- If No
					- Insert Page Info Record into site_usage_page_info Table
				-- Get Page Id
				
				-> Check If user Info Exists in site_usage_user_info Table
				-- If No
					- Insert User Info
				-- Get Unique User ID
				
				-> Check If User has ever viewed page in site_usage_page_view_count Table
				-- If No
					- Insert Fresh Page View Count Record for Unique User into site_usage_page_view_count Table
				-- Get unique_user_and_page_id
				
				-> Update Page Times Viewed by User
				
				-> Insert Page View Info
				
				-> Insert User Agent Info
				
			*/
			
			// -> Check If Page Info Exists in site_usage_page_info Table
			// -- Get Page Id
			$page_id = $this->getPageIdByPageInfo($page_url, $page_name, $page_description) ;
			if($page_id === false){
				// -- If No, Insert Page Info Record into site_usage_page_info Table
				$res = $this->insertPageInfoRecord($page_url, $page_name, $page_description) ;
				if($res === true){
					$page_id = $this->getPageIdByPageInfo($page_url, $page_name, $page_description) ;
				}
			}
			
			if($page_id !== false){
				
				$this->logThisPageHitInfo($page_id, $page_name) ;
				
				// -> Check If user Info Exists in site_usage_user_info Table
				// -- Get Unique User ID
				$unique_user_id = $this->getUniqueUserId($user_id, $ip, $forwarded_ip) ;
				if($unique_user_id === false){
					// -- If No, Insert User Info
					$res2 = $this->insertUserInfoRecord($user_id, $ip, $forwarded_ip) ;
					if($res2 === true){
						$unique_user_id = $this->getUniqueUserId($user_id, $ip, $forwarded_ip) ;
					}
				}
				
				if($unique_user_id !== false){
					
					// -> Check If User has ever viewed page in site_usage_page_view_count Table
					// -- Get Unique User And Page ID
					$unique_user_and_page_id = $this->getUniqueUserAndPageId($unique_user_id, $page_id) ;
					if($unique_user_and_page_id === false){
						// -- If No, Insert User Info
						$res3 = $this->insertPageViewCountRecordForUniqueUser($unique_user_id, $page_id) ;
						if($res3 === true){
							$unique_user_and_page_id = $this->getUniqueUserAndPageId($unique_user_id, $page_id) ;
						}
					}
					
					if($unique_user_and_page_id !== false){
						
						// -> Update Page Times Viewed by User
						$upd_res = $this->updatePageViewCountRecordForUniqueUser($unique_user_and_page_id) ;
						if($upd_res === true){
							
							// -> Insert New Page View Info
							$pg_view_ins_res = $this->insertNewPageViewInfo($unique_user_and_page_id, $time_logged, $referring_url) ;
							if($pg_view_ins_res[0] === true){
								
								// -> Insert User Agent Info
								if($user_agent != ""){
									$page_view_id 		= $pg_view_ins_res[1] ;
									$ua_info_ins_res 	= $this->insertUserAgentInfo($page_view_id, $user_agent) ;
									if($ua_info_ins_res === true){
										return true ;
									}
								}
							}
							
						}
					}
				}
				
			}
		} //if !is_bot(user_agent) 
	}
	
	public function logThisPageHitInfo($page_id, $page_name){
		
		if( ($page_id != "") && ($page_name != "")){
			// Check If Record Aready Exists in each of daily, monthly, and yearly tables.
			// If Yes Update
			// If No Insert
			
			// Daily Log
			if($this->checkCustomPageLog("site_usage_pages_daily", $page_id, $page_name, true, true) === true){
				// Update
				$this->updateCustomPageDailyLog($page_id, $page_name) ;
			}else{
				// Insert
				$this->insertCustomPageDailyLog($page_id, $page_name) ;
			}
			
			// Monthly Log
			if($this->checkCustomPageLog("site_usage_pages_monthly", $page_id, $page_name, true) === true){
				// Update
				$this->updateCustomPageMonthlyLog($page_id, $page_name) ;
			}else{
				// Insert
				$this->insertCustomPageMonthlyLog($page_id, $page_name) ;
			}
			
			// Yearly Log
			if($this->checkCustomPageLog("site_usage_pages_yearly", $page_id, $page_name) === true){
				// Update
				$this->updateCustomPageYearlyLog($page_id, $page_name) ;
			}else{
				// Insert
				$this->insertCustomPageYearlyLog($page_id, $page_name) ;
			}
					
		}
	}
	
	public function checkCustomPageLog($table_name, $page_id, $page_name, $check_month = false, $check_day = false){
		date_default_timezone_set("GMT") ;
		$current_year	= date( 'Y' ) ;
		$current_month	= date( 'n' ) ;
		$current_day	= date( 'j' ) ;
		
		$query = "SELECT page_id, page_name FROM ".$table_name." WHERE page_id = '".$page_id."' AND page_name = '".$page_name."' AND year = '".$current_year."' " ;
		if( ($check_month === true) && ($check_day === true) ){
			$query = "SELECT page_id, page_name FROM ".$table_name." WHERE page_id = '".$page_id."' 
						AND page_name = '".$page_name."' AND year = '".$current_year."' AND month = '".$current_month."' AND day = '".$current_day."' " ;
		}else if($check_month === true){
			$query = "SELECT page_id, page_name FROM ".$table_name." WHERE page_id = '".$page_id."' AND page_name = '".$page_name."' 
						AND year = '".$current_year."' AND month = '".$current_month."' " ;
		}
		
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			return true ;
		}
		return false ;
	}
	
	public function updateCustomPageDailyLog($page_id, $page_name){
		date_default_timezone_set("GMT") ;
		$current_datetime = date( 'Y-m-d H:i:s' ) ;
		
		$current_year	= date( 'Y' ) ;
		$current_month	= date( 'n' ) ;
		$current_day	= date( 'j' ) ;
		
		$query = "UPDATE site_usage_pages_daily SET hits = (hits + 1), time_last_updated = '$current_datetime'  WHERE page_id = '$page_id' AND page_name = '$page_name' AND year = '$current_year' AND month = '$current_month' AND day = '$current_day' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	public function insertCustomPageDailyLog($page_id, $page_name){
		$status 		= 1 ;
		$hits			= 1 ;
		date_default_timezone_set("GMT") ;
		$current_datetime = date( 'Y-m-d H:i:s' ) ;
		
		$current_year	= date( 'Y' ) ;
		$current_month	= date( 'n' ) ;
		$current_day	= date( 'j' ) ;
		
		//page_id, page_name, hits, year, month, day, status, time_last_updated
		$query = "INSERT INTO site_usage_pages_daily(page_id, page_name, hits, year, month, day, status, time_last_updated) 
					VALUES('$page_id', '$page_name', '$hits', '$current_year', '$current_month', '$current_day', '$status', '$current_datetime') " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	
	public function updateCustomPageMonthlyLog($page_id, $page_name){
		date_default_timezone_set("GMT") ;
		$current_datetime = date( 'Y-m-d H:i:s' ) ;
		
		$current_year	= date( 'Y' ) ;
		$current_month	= date( 'n' ) ;
		
		$query = "UPDATE site_usage_pages_monthly SET hits = (hits + 1), time_last_updated = '$current_datetime'  WHERE page_id = '$page_id' AND page_name = '$page_name' AND year = '$current_year' AND month = '$current_month' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	public function insertCustomPageMonthlyLog($page_id, $page_name){
		$status 		= 1 ;
		$hits			= 1 ;
		date_default_timezone_set("GMT") ;
		$current_datetime = date( 'Y-m-d H:i:s' ) ;
		
		$current_year	= date( 'Y' ) ;
		$current_month	= date( 'n' ) ;
		
		//page_id, page_name, hits, year, month, day, status, time_last_updated
		$query = "INSERT INTO site_usage_pages_monthly(page_id, page_name, hits, year, month, status, time_last_updated) 
					VALUES('$page_id', '$page_name', '$hits', '$current_year', '$current_month', '$status', '$current_datetime') " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	
	public function updateCustomPageYearlyLog($page_id, $page_name){
		date_default_timezone_set("GMT") ;
		$current_datetime = date( 'Y-m-d H:i:s' ) ;
		
		$current_year	= date( 'Y' ) ;
		
		$query = "UPDATE site_usage_pages_yearly SET hits = (hits + 1), time_last_updated = '$current_datetime'  WHERE page_id = '$page_id' AND page_name = '$page_name' AND year = '$current_year' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	public function insertCustomPageYearlyLog($page_id, $page_name){
		$status 		= 1 ;
		$hits			= 1 ;
		date_default_timezone_set("GMT") ;
		$current_datetime = date( 'Y-m-d H:i:s' ) ;
		
		$current_year	= date( 'Y' ) ;
		
		//page_id, page_name, hits, year, month, day, status, time_last_updated
		$query = "INSERT INTO site_usage_pages_yearly(page_id, page_name, hits, year, status, time_last_updated) 
					VALUES('$page_id', '$page_name', '$hits', '$current_year', '$status', '$current_datetime') " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	
	
	
	public function logThisPageContentInfo($page_name, $page_description){
		
		if( ($page_name != "") && ($page_description != "")){
			
			$desc_string 		= explode("|", $page_description) ;
			$content_id 		= trim(end($desc_string)) ;
			
			$content_type_string = $page_name ;
			$content_type = "" ;
			
			//id, content_id, content_type, content_title, author_user_id, author_name, date_published, hits, year, month, day, status, time_last_updated
			
	//		echo "$content_id | ".$page_name ;
			
			if(is_numeric($content_id) ){
				switch($page_name){
					case	'article' 	: 	$content_type = 1; 
											
											break ;
					case	'video' 	: $content_type = 2; break ;
					case	'photo' 	: $content_type = 3; break ;
					default				: return; break ;
				}
				
				$content_info = $this->customLogGetContentInfo($content_type_string, $content_id) ;
				if($content_info != ""){
					//$content_title, $content_author, $content_date_published, $content_contributor_profile_link, $author_user_id
					$content_title				= $content_info[0] ;	
					$content_author				= $content_info[1] ;
					$content_date_published		= $content_info[2] ;
					$author_profile_link		= $content_info[3] ;
					$author_user_id				= $content_info[4] ;
					
					// Check If Record Aready Exists in each of daily, monthly, and yearly tables.
					// If Yes Update
					// If No Insert
					
					// Daily Log
					if($this->checkCustomContentLog("site_usage_content_daily", $content_id, $content_type, true, true) === true){
						// Update
						$this->updateCustomContentDailyLog($content_id, $content_type, $content_title, $content_author, $content_date_published) ;
					}else{
						// Insert
						$this->insertCustomContentDailyLog($content_id, $content_type, $content_title, $author_user_id, $content_author, $content_date_published) ;
					}
					
					// Monthly Log
					if($this->checkCustomContentLog("site_usage_content_monthly", $content_id, $content_type, true) === true){
						// Update
						$this->updateCustomContentMonthlyLog($content_id, $content_type, $content_title, $content_author, $content_date_published) ;
					}else{
						// Insert
						$this->insertCustomContentMonthlyLog($content_id, $content_type, $content_title, $author_user_id, $content_author, $content_date_published) ;
					}
					
					// Yearly Log
					if($this->checkCustomContentLog("site_usage_content_yearly", $content_id, $content_type) === true){
						// Update
						$this->updateCustomContentYearlyLog($content_id, $content_type, $content_title, $content_author, $content_date_published) ;
					}else{
						// Insert
						$this->insertCustomContentYearlyLog($content_id, $content_type, $content_title, $author_user_id, $content_author, $content_date_published) ;
					}
					
					
				}
			}
		}
	}
	
	public function checkCustomContentLog($table_name, $content_id, $content_type, $check_month = false, $check_day = false){
		$current_year	= date( 'Y' ) ;
		$current_month	= date( 'n' ) ;
		$current_day	= date( 'j' ) ;
		
		$query = "SELECT content_id, content_type FROM ".$table_name." WHERE content_id = '".$content_id."' AND content_type = '".$content_type."' AND year = '".$current_year."' " ;
		if( ($check_month === true) && ($check_day === true) ){
			$query = "SELECT content_id, content_type FROM ".$table_name." WHERE content_id = '".$content_id."' 
						AND content_type = '".$content_type."' AND year = '".$current_year."' AND month = '".$current_month."' AND day = '".$current_day."' " ;
		}else if($check_month === true){
			$query = "SELECT content_id, content_type FROM ".$table_name." WHERE content_id = '".$content_id."' AND content_type = '".$content_type."' 
						AND year = '".$current_year."' AND month = '".$current_month."' " ;
		}
		
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			return true ;
		}
		return false ;
	}
	
	public function updateCustomContentDailyLog($content_id, $content_type, $content_title, $content_author, $content_date_published){
		date_default_timezone_set("GMT") ;
		$current_datetime = date( 'Y-m-d H:i:s' ) ;
		
		$current_year	= date( 'Y' ) ;
		$current_month	= date( 'n' ) ;
		$current_day	= date( 'j' ) ;
		
		$query = "UPDATE site_usage_content_daily SET hits = (hits + 1), content_title = '$content_title', author_name = '$content_author', date_published = '$content_date_published', time_last_updated = '$current_datetime'  WHERE content_id = '$content_id' AND content_type = '$content_type' AND year = '$current_year' AND month = '$current_month' AND day = '$current_day' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	public function insertCustomContentDailyLog($content_id, $content_type, $content_title, $author_user_id, $content_author, $content_date_published){
		$status 		= 1 ;
		$hits			= 1 ;
		date_default_timezone_set("GMT") ;
		$current_datetime = date( 'Y-m-d H:i:s' ) ;
		
		$current_year	= date( 'Y' ) ;
		$current_month	= date( 'n' ) ;
		$current_day	= date( 'j' ) ;
		
		//content_id, content_type, content_title, author_user_id, author_name, date_published, hits, year, month, day, status, time_last_updated
		$query = "INSERT INTO site_usage_content_daily(content_id, content_type, content_title, author_user_id, author_name, date_published, hits, year, month, day, status, time_last_updated) 
					VALUES('$content_id', '$content_type', '$content_title', '$author_user_id', '$content_author', '$content_date_published', '$hits', '$current_year', '$current_month', '$current_day', '$status', '$current_datetime') " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	
	public function updateCustomContentMonthlyLog($content_id, $content_type, $content_title, $content_author, $content_date_published){
		date_default_timezone_set("GMT") ;
		$current_datetime = date( 'Y-m-d H:i:s' ) ;
		
		$current_year	= date( 'Y' ) ;
		$current_month	= date( 'n' ) ;
		
		$query = "UPDATE site_usage_content_monthly SET hits = (hits + 1), content_title = '$content_title', author_name = '$content_author', date_published = '$content_date_published', time_last_updated = '$current_datetime'  WHERE content_id = '$content_id' AND content_type = '$content_type' AND year = '$current_year' AND month = '$current_month' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	public function insertCustomContentMonthlyLog($content_id, $content_type, $content_title, $author_user_id, $content_author, $content_date_published){
		$status 		= 1 ;
		$hits			= 1 ;
		date_default_timezone_set("GMT") ;
		$current_datetime = date( 'Y-m-d H:i:s' ) ;
		
		$current_year	= date( 'Y' ) ;
		$current_month	= date( 'n' ) ;
		
		//content_id, content_type, content_title, author_user_id, author_name, date_published, hits, year, month, status, time_last_updated
		$query = "INSERT INTO site_usage_content_monthly(content_id, content_type, content_title, author_user_id, author_name, date_published, hits, year, month, status, time_last_updated) 
					VALUES('$content_id', '$content_type', '$content_title', '$author_user_id', '$content_author', '$content_date_published', '$hits', '$current_year', '$current_month', '$status', '$current_datetime') " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	
	public function updateCustomContentYearlyLog($content_id, $content_type, $content_title, $content_author, $content_date_published){
		date_default_timezone_set("GMT") ;
		$current_datetime = date( 'Y-m-d H:i:s' ) ;
		
		$current_year	= date( 'Y' ) ;
		
		$query = "UPDATE site_usage_content_yearly SET hits = (hits + 1), content_title = '$content_title', author_name = '$content_author', date_published = '$content_date_published', time_last_updated = '$current_datetime'  WHERE content_id = '$content_id' AND content_type = '$content_type' AND year = '$current_year' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	public function insertCustomContentYearlyLog($content_id, $content_type, $content_title, $author_user_id, $content_author, $content_date_published){
		$status 		= 1 ;
		$hits			= 1 ;
		date_default_timezone_set("GMT") ;
		$current_datetime = date( 'Y-m-d H:i:s' ) ;
		
		$current_year	= date( 'Y' ) ;
		
		//content_id, content_type, content_title, author_user_id, author_name, date_published, hits, year, status, time_last_updated
		$query = "INSERT INTO site_usage_content_yearly(content_id, content_type, content_title, author_user_id, author_name, date_published, hits, year, status, time_last_updated) 
					VALUES('$content_id', '$content_type', '$content_title', '$author_user_id', '$content_author', '$content_date_published', '$hits', '$current_year', '$status', '$current_datetime') " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	public function getPageNameFromPageDetails($page_url, $page_description, $page_name){
		$page_url = prepare_my_domain_url($page_url) ;
		
		$prefix = "myteenslife.com/" ;
//		$prefix = "localhost/myteens_wms/" ;
		
		if($page_name != ""){
			switch($page_name){
				case	'home' 				: 	return "home" ; 				break ;
				
				if($page_name == 'user_account'){
					// Edit Account Successful Page
					$res = $this->findExactNeedleInHaystack($page_url, $prefix."user_account?edit=true", 'user_account_edit') ;
					if($res !== false){ return $res ; }
					
					// Change Password Successful Page
					$res = $this->findExactNeedleInHaystack($page_url, $prefix."user_account?change_pass=true", 'user_password_change') ;
					if($res !== false){ return $res ; }
					
					return 'user_account' ;
				}
			}
		}
		
		if($page_url != ""){
			// Login
			$res = $this->findIndexOfNeedleInHaystack($page_url, $prefix."login", 'login') ;
			if($res !== false){ return $res ; }
			
			// Signup
			$res = $this->findIndexOfNeedleInHaystack($page_url, $prefix."signup", 'signup') ;
			if($res !== false){ return $res ; }
			
			// Listen Page
			$res = $this->findIndexOfNeedleInHaystack($page_url, $prefix."listen", 'listen') ;
			if($res !== false){ return $res ; }
			
			// Our Team Page
			$res = $this->findIndexOfNeedleInHaystack($page_url, $prefix."pages/our_team", 'our_team') ;
			if($res !== false){ return $res ; }
			
			// Events Page
			$res = $this->findIndexOfNeedleInHaystack($page_url, $prefix."pages/events", 'events') ;
			if($res !== false){ return $res ; }
			
			// Listen to Recording Page
			$res = $this->findIndexOfNeedleInHaystack($page_url, $prefix."listen?rec_num", 'listen_recording') ;
			if($res !== false){ return $res ; }
			
			// Contributor View Page
			$res = $this->findIndexOfNeedleInHaystack($page_url, $prefix."contributors/", 'contributor_view_page') ;
			if($res !== false){ return $res ; }
			
			// Contributor List Page
			$res = $this->findIndexOfNeedleInHaystack($page_url, $prefix."contributors", 'contributors_list_page') ;
			if($res !== false){ return $res ; }
			
			// Video View Page
			$res = $this->findIndexOfNeedleInHaystack($page_url, $prefix."video/", 'video') ;
			if($res !== false){ return $res ; }
			
			// Videos List Page
			$res = $this->findIndexOfNeedleInHaystack($page_url, $prefix."videos", 'videos_list_page') ;
			if($res !== false){ return $res ; }
			
			// Photo View Page
			$res = $this->findIndexOfNeedleInHaystack($page_url, $prefix."photo/", 'photo') ;
			if($res !== false){ return $res ; }
			
			// Photos List Page
			$res = $this->findIndexOfNeedleInHaystack($page_url, $prefix."photos", 'photos_list_page') ;
			if($res !== false){ return $res ; }
			
			// MORE PAGES
			
			// Terms
			$res = $this->findIndexOfNeedleInHaystack($page_url, $prefix."pages/terms", 'terms') ;
			if($res !== false){ return $res ; }
			
			// Privacy
			$res = $this->findIndexOfNeedleInHaystack($page_url, $prefix."pages/privacy", 'privacy') ;
			if($res !== false){ return $res ; }
			
			// Comment Policy
			$res = $this->findIndexOfNeedleInHaystack($page_url, $prefix."pages/comment_policy", 'comment_policy') ;
			if($res !== false){ return $res ; }
			
			// Contact
			$res = $this->findIndexOfNeedleInHaystack($page_url, $prefix."pages/contact", 'contact') ;
			if($res !== false){ return $res ; }
			
			// Advertising
			$res = $this->findIndexOfNeedleInHaystack($page_url, $prefix."pages/advertising", 'advertising') ;
			if($res !== false){ return $res ; }
			
		}
		
	}
	
	public function findExactNeedleInHaystack($haystack, $needle, $needle_name){
		if($haystack == $needle){
			return $needle_name ;
		}
		return false ;
	}
	public function findIndexOfNeedleInHaystack($haystack, $needle, $needle_name){
		if(strstr($haystack, $needle) !== false){
			return $needle_name ;
		}
		return false ;
	}
	
	public function getPageIdByPageInfo($page_url, $page_name, $page_description){
		
		$query = "SELECT page_id FROM site_usage_page_info WHERE page_url = '$page_url' " ;
		if($page_name != ""){
			$query = "SELECT page_id FROM site_usage_page_info WHERE page_name = '$page_name' " ;	
		}
		
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			
			return $result_array[0]['page_id'] ;
		}
		return false ;
	}
	public function insertPageInfoRecord($page_url, $page_name, $page_description){
		$page_id 	= createAnId("site_usage_page_info", "page_id") ;
		$status 	= 1 ;
		
		$query 		= "INSERT INTO site_usage_page_info(page_id, page_url, page_name, page_description, status) 
						VALUES('$page_id', '$page_url', '$page_name', '$page_description', '$status')" ; 
		$query = $this->db->query($query) ;
		return true ;
	}
	
	public function getUniqueUserId($user_id, $ip_address, $forwarded_ip){
		$query = "SELECT unique_user_id FROM site_usage_user_info WHERE ip_address = '$ip_address' AND forwarded_ip = '$forwarded_ip' " ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			
			return $result_array[0]['unique_user_id'] ;
		}
		return false ;
	}
	public function insertUserInfoRecord($user_id, $ip_address, $forwarded_ip){
		$unique_user_id = createAnId("site_usage_user_info", "unique_user_id") ;
		$status 	= 1 ;
		date_default_timezone_set("GMT") ;
		$current_datetime = date( 'Y-m-d H:i:s' ) ;
		
		$query 		= "INSERT INTO site_usage_user_info(unique_user_id, ip_address, forwarded_ip, user_id, status, time_added) 
						VALUES('$unique_user_id', '$ip_address', '$forwarded_ip', '$user_id', '$status', '$current_datetime')" ; 
		$query = $this->db->query($query) ;
		return true ;
	}
	
	/* PAGE VIEW COUNT FUNCTIONS */
	public function getUniqueUserAndPageId($unique_user_id, $page_id){
		$query = "SELECT unique_user_and_page_id FROM site_usage_page_view_count WHERE unique_user_id = '$unique_user_id' AND page_id = '$page_id' " ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			
			return $result_array[0]['unique_user_and_page_id'] ;
		}
		return false ;
	}
	public function insertPageViewCountRecordForUniqueUser($unique_user_id, $page_id){
		$unique_user_and_page_id = createAnId("site_usage_page_view_count", "unique_user_and_page_id") ;
		$status 		= 1 ;
		$times_viewed 	= 0 ;
		
		$query 		= "INSERT INTO site_usage_page_view_count(unique_user_and_page_id, unique_user_id, page_id, times_viewed, status) 
						VALUES('$unique_user_and_page_id', '$unique_user_id', '$page_id', '$times_viewed', '$status')" ; 
		$query = $this->db->query($query) ;
		return true ;
	}
	public function updatePageViewCountRecordForUniqueUser($unique_user_and_page_id){
		$query = "UPDATE site_usage_page_view_count SET times_viewed = (times_viewed + 1)  WHERE unique_user_and_page_id = '$unique_user_and_page_id' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	/* PAGE VIEW FUNCTIONS */
	public function insertNewPageViewInfo($unique_user_and_page_id, $time_logged = "", $referring_url = ""){
		$page_view_id 	= createAnId("site_usage_page_view", "page_view_id") ;
		$status 		= 1 ;
		date_default_timezone_set("GMT") ;
		$current_datetime = date( 'Y-m-d H:i:s' ) ;
		
		if($time_logged != ""){ $current_datetime = $time_logged ; }
		
		$query 		= "INSERT INTO site_usage_page_view(page_view_id, unique_user_and_page_id, referring_url, status, time) 
						VALUES('$page_view_id', '$unique_user_and_page_id', '$referring_url','$status', '$current_datetime')" ; 
		$query = $this->db->query($query) ;
		return array(true, $page_view_id) ;
	}
	
	/* USER AGENT INFO FUNCTIONS */
	public function insertUserAgentInfo($page_view_id, $user_agent_string){
		$status = 1 ;
		
		$query 	= "INSERT INTO site_usage_user_agent(page_view_id, user_agent_string, status) 
					VALUES('$page_view_id', '$user_agent_string', '$status')" ; 
		$query 	= $this->db->query($query) ;
		return true ;
	}
}

?>