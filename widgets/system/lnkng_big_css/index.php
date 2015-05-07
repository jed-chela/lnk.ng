
<?php 

$this->wms_api 	= get_ci_class('models/wms_api') ;
$widget_path 	= $this->wms_api->getWidgetDirectoryPath('lnkng_big_css') ;

?>
<link type="text/css" rel="stylesheet" href="<?php echo $widget_path ; ?>css/style.css" />

