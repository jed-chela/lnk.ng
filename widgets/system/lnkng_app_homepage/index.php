

<?php
	$this->wms_api 	= get_ci_class('models/wms_api') ;
	$this->lnkng 	= get_ci_class('models/lnkng') ;
	$widget_path 	= $this->wms_api->getWidgetDirectoryPath('lnkng_app_homepage') ;
?>
<?php ?>
<script type="text/javascript" src="<?php echo $this->wms_api->rootdir ; ?>js/main.js"></script>
<script>
	var CONST_rootdir = "<?php echo $this->wms_api->rootdir ; ?>" ;
</script>
<div class="app_body">
	<div class="app_body_inner">
    	<?php
			$o = "" ;
			
			$o .= "<div class='long_url_box' >" ;
				$o .= "<div class='long_url_box_inner' >" ;
				
				$last_long_url 		= "" ;
				$short_url_HTML 	= "" ;
				
					$o .= "<form method='post' action='' >" ;
						$o .= "<label>URL to shorten:</label>" ;
							$o .= "<div class='long_url_box_control' >" ;
							
					$res = $this->lnkng->handleNONAsynchronousShortenRequest() ;
					if($res[0] === true){
						$short_url_HTML 	= $res[1] ;
					}else if($res[0] === false){
						$short_url_HTML 	= $res[1] ;
						$last_long_url 		= $res[2] ;
					}
					
							$o .= "<input type='text' name='long_url' class='long_url' value='".$last_long_url."' placeholder='' />" ;
							$o .= "<input type='button' name='long_url_submit' class='long_url_submit' id='long_url_ajax_button' value='Get Short URL' />" ;
							$o .= "<input type='submit' name='long_url_submit' class='long_url_submit' id='long_url_form_button' value='Go' />" ;
						$o .= "</div>" ;
					$o .= "</form>" ;
				
				$o .= "</div>" ;
			$o .= "</div>" ;
			
			
			$short_url = "" ;
			
			if($short_url !== false){
				
				$o .= "<div class='short_url_box' >" ;
					$o .= $short_url_HTML ;
				$o .= "</div>" ;
				
			}
			
			echo $o ;
		?>
    </div>
</div> 
	