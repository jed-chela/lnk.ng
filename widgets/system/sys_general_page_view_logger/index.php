<?php
	$this->wms_api 		= get_ci_class('models/wms_api') ;
	$this->wms_logger 	= get_ci_class('models/wms_logger') ;
	$this->wms_news 	= get_ci_class('models/wms_news') ;
	$widget_path 		= $this->wms_api->getWidgetDirectoryPath('sys_general_page_view_logger') ;
?>

<?php
	/* <style> See gwp_big_css_file widget */
?>

<?php
	$page_info_one 	= "" ;
	$page_info_two 	= "" ;
	
	if(isset($home_params) ){
		if($home_params != ""){
			$page_info_one 	= $home_params ;	
		}
	}
	if(isset($this_page_p1) ){
		if($this_page_p1 != ""){
			$page_info_two = $this_page_p1 ;
		}
	}
	
?>

<?php
	$o = "" ;
	
	$user_id = "" ;
	
	if($this->user_sessions->userLoggedIn() === true){
		$user_id = $this->user_sessions->getUserId() ;
	}
	
	$page_url = getAddressBarValue() ;
	
	$page_name = $page_info_one ;
	
	$page_description = $page_info_one."|".$page_info_two ;
	
	$ip 			= @$_SERVER['REMOTE_ADDR']; 
	$forwarded_ip 	= @$HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR']; 
	$user_agent 	= "" ;
	$url_referrer 	= "" ;
	
	if(isset($this->agent)){
		$user_agent 	= $this->agent->agent_string() ;
		
		if ($this->agent->is_referral()){
			$url_referrer = $this->agent->referrer();
		}
	}
	
	
	$this->wms_logger->advancedLogThisPageVisit($user_id, $page_url, $page_name, $page_description, $ip, $forwarded_ip, $user_agent, $url_referrer) ;
	
//	echo $o ;
	
?>