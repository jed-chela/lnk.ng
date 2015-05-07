<?php $rootdir = $this->config->item('base_url') ; ?>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php
		$page_url = $_SERVER['REQUEST_URI'] ;
		$page_title = explode('/', $page_url) ;
		$page_title_count = count($page_title) ;
		$page_title = $page_title[$page_title_count - 2]."/".end($page_title) ;
		
		
		if(isset($this_page_p)){
			
			$page_name = $this_page_p ;
			echo "<title>".firstLetterToCaps($page_name)." ~ linking</title>" ;
		}else{
			echo "<title>Linking ~ URL Shortener Utility</title>" ;
		}
		
	?>
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->