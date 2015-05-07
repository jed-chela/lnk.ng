<?php $rootdir = $this->config->item("base_url") ; ?>
<!DOCTYPE html>
<html>
	<head>
<?php
	/* 	Load meta for mobile. Load IE8 script include. */
	$this->load->view('header/header') ;
?>
	<!-- Load CSS stylesheets -->
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
    	<div class="container">
			
			<?php
                echo $this->content_loader->getTemplatePositionData(true, "") ;
            ?>
            
            <?php
            //Load Widget: General Page Logger
			$page_logger_widget = $this->content_loader->getWidgetData("sys_general_page_view_logger") ;
			echo $page_logger_widget ;
			?>
            
		</div>
	</body>
</html>