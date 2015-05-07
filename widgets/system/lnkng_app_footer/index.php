

<?php
	$this->wms_api 	= get_ci_class('models/wms_api') ;
	$widget_path 	= $this->wms_api->getWidgetDirectoryPath('lnkng_app_footer') ;
?>
<?php ?>
<div class="app_footer">
	<?php date_default_timezone_set("GMT") ; ?>
	<div class="app_footer_inner">
		&copy; <?php echo date("Y") ; ?> Lnk.ng. All Rights Reserved.
    </div>
</div>