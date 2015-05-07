<?php
	$this->wms_api 			= get_ci_class('models/wms_api') ;
	$this->wms_pages	= get_ci_class('models/wms_pages') ;
	$widget_path = $this->wms_api->getWidgetDirectoryPath('sys_static_html_page_view') ;
?>

<style>
	.page_page_body{
		border:none ;
		font-size:13px;
		font-family: arial, sans-serif;
		text-align:justify;
		line-height:20px;
		margin:0px;
		padding:30px;
		padding-top:0px;
	}
	.page_page_body h2{
		font-size:20px;
	}
	
</style>

<?php
	
	$default_page_page_name 		= "about" ;
	$default_page_page_section		= "" ;
	
	$selected_page_page_name			= $default_page_page_name ;
	$selected_page_page_section 		= $default_page_page_section ;
	
	if(isset($this_page_p1) && ($this_page_p1 != "") ){
		$selected_page_page_name = $this_page_p1 ;
	}
		
	if(isset($this_page_p2) && ($this_page_p2 != "") ){
		$selected_page_page_section = $this_page_p2 ;
	}
		
	$out = "" ;
	if($selected_page_page_name != ""){
		//FEATURE ONE: Get and Display Product Info
		$page_id =  $this->wms_pages->getPageId(rawurldecode($selected_page_page_name)) ;
		if($page_id !== false){
			$page_info =  $this->wms_pages->getPageInfo($page_id) ;
			if($page_info !== false){
				$page_id = $page_info->page_id ;
				$page_html = $page_info->full_text ;
				$page_publish_status = $page_info->publish_status ;
				
				if($page_publish_status == '1'){
					$page = html_entity_decode($page_html) ;
					$out .= "<div class='page_html_body' >" ;
						$out .= $page ;
					$out .= "</div>" ;
				}
			}else{
				//Page Info does not Exist
				$out .= "<div class='page_html_body' >" ;
					$out .= "This page information is not available! It may have been removed." ;
				$out .= "</div>" ;
			}		
		}else{ /* Page ID not found */ }
	}else{
		//Product ID is missing. No product was selected
	}
	echo $out ;
	
	/*
	If Product ID is input
		Verify Product ID
			Get the following Product Information from DB
				- Description
				
			Display Product Description
	
	*/
				
?>

