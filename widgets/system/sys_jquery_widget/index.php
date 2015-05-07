<?php
	$this->wms_api 			= get_ci_class('models/wms_api') ;
	$this->wms_store_gui	= get_ci_class('models/wms_store_gui') ;
	$widget_path 			= $this->wms_api->getWidgetDirectoryPath('sys_jquery_widget') ;
?>


<script type='text/javascript' src='<?php echo $widget_path ; ?>js/jquery-1.10.2.min.js' ></script>
<script type='text/javascript' src='<?php echo $widget_path ; ?>js/jquery.textarea-expander.js' ></script>