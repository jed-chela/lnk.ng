<?php $rootdir = $this->config->item("base_url") ; ?>
<?php
	
	if($this->user_sessions->userLoggedIn() === true){
		$user_id = $this->user_sessions->getUserId() ;
		if(  ($this->user_sessions->getUserPrivilege($user_id) != '10')  ){
			header("Location:".$rootdir."error");
		}
	}

?>
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
    	<div class="container light-shadow-border">
        	<div class="block_auto">
            	<?php	echo $this->admin_manager->showFrameworkName() ;	?>
            </div>
            <div class="block_auto">
                    <div id='menu' class="block_left">
                        <div class="menu menu-x menu-border">
                        <?php 
                        	echo $this->admin_manager->getAdminNavigationMenu('widgets') ;
						?>
                        </div>
                    </div>
                    <div class="block_right"><?php 
                    	if(isset($login_widget_params)){ 
                            echo $login_widget_params ;  
                        }
                    ?></div>
            </div>
            
            <div class="block_auto">
            	<?php
					echo $this->admin_manager->showAllWidgets() ;
				?>
            </div>
            
		</div>
	</body>
</html>