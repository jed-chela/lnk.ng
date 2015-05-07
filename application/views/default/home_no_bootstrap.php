<?php $rootdir = $this->config->item("base_url") ; ?>
<!DOCTYPE html>
<html>
	<head>
<?php
	/* 	Load meta for mobile. Load IE8 script include. */
	$this->load->view('header/header') ;
?>
	<!-- Load CSS stylesheets -->
    <link rel="stylesheet" href="<?php echo $rootdir ; ?>css/default.css" type="text/css" />
    
<!--    <link rel="stylesheet" href="<?php // echo $rootdir."templates/".$template_info_obj->name."/css/" ; ?>style.css" type="text/css" />	-->
    
	<!-- Load JavaScripts -->
<?php
	// Get Admin imposed CSS Style for this template
	$this_template_id =  $this->content_loader->get_this_page_template() ;
	if($this_template_id !== false){
		echo $this->content_loader->getThisTemplateCSS($this_template_id) ;
	}
?>
    
<?php
	$this->load->view("header/shortcut_icon_view") ;
?>
	</head>
    <body>
    	<div class="container light-shadow-border">
			
			<?php
                echo $this->content_loader->getTemplatePositionData(true, "block_in") ;
            ?>
            
		</div>
	</body>
</html>