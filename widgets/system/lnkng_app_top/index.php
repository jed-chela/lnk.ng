

<?php
	$this->wms_api 	= get_ci_class('models/wms_api') ;
	$widget_path 	= $this->wms_api->getWidgetDirectoryPath('lnkng_app_top') ;
?>
<?php ?>
<div class="app_top">
	<div class="app_top_inner">
        <div class='app_banner'>
            <div class='app_name'>LNK.NG</div>
            <div class='app_subtitle'>Shorten URLs Fast</div>
        </div>
        <div class='social_links'>
            <span><a href='https://www.facebook.com/lnk.ng.url' target="_blank"><img src='<?php echo  $this->wms_api->rootdir ; ?>images/logos/facebook_logo_1.png' /></a></span>
            <span><a href='https://plus.google.com/b/110100784009971818439/' target="_blank"><img src='<?php echo  $this->wms_api->rootdir ; ?>images/logos/google_plus_logo_1.png' /></a></span>
        </div>
    </div>
</div> 
	