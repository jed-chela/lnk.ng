<?php $rootdir = $this->config->item("base_url") ; ?>
<!DOCTYPE html>
<html>
	<head>
<?php
	/* 	Load meta for mobile. Load IE8 script include. */
	$this->load->view('header/header') ;
?>
	<!-- Load CSS stylesheets -->
    <?php
    	echo $this->admin_manager->getAdminCSS() ;
    ?>
    
	<!-- Load JavaScripts -->
	
<?php
	$this->load->view("header/shortcut_icon_view") ;
?>
	</head>
    <body>
    	<?php
			echo $this->admin_news->showStoreActions($index_function) ;
		?>
    	<div class="container light-shadow">
        	<div class="block_auto app_name_header">
            	<?php	echo $this->admin_manager->showAppIstanceName() ;	?>
                <div class="block_right"><?php 
                    if(isset($login_widget_params)){ 
                        echo $login_widget_params ;  
                    }
                ?></div>
            </div>
            <div class="block_auto">
            	
            </div>
            
            <div class="block_auto">
            	<?php
					if($index_function != ""){
						echo $this->admin_news->handleNewsActionRequest($index_param, $index_function, $index_p1, $index_p2, $index_p3, $index_p4, $index_p5) ;
					}
				?>
            </div>

		</div>
	</body>
</html>