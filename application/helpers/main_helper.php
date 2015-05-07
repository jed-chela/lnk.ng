<?php  
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');	

	/* Input Cleansing/Sterilization */
	function protect($string){
		$string = htmlentities(trim($string ), ENT_QUOTES, 'UTF-8') ;
		return $string;
	}
	function protectNoTrim($string){
		$string = htmlentities($string , ENT_QUOTES, 'UTF-8') ;
		return $string;
	}
	function noProtect($string){
		return trim($string ) ;
	}
    function protectExactText($str){
		$str = protectNoTrim($str);
		return keepStringForm($str) ;
	}
	function decodeURLEncodedString($string){
		return rawurldecode($string) ;
	}
	function ajaxTxtPostProtect($str){
		$str = $str ;
		
		$len = strlen($str) ;
		
		if($str[0] == "#"){
			if($str[$len - 1] == "?"){
				$str = substr($str,1,($len - 2)) ;
				$cryp = new nebula_cryptic() ;
				$str = $cryp->decrypt($str) ;
				$str = protect($str);
				return keepStringForm($str) ;
			}else{ 
				//log error
				return 0; 
			}
		}else{ 
			//log error
			return 0; 
		}
	}
	function keepStringForm($content){
		if($content != ""){
			$content = str_replace("\n","<br>",$content) ;
			$content = str_replace("  "," "."&nbsp;",$content) ;
		}
		return $content ;
	}
	function getStringTextForm($content){
		if($content != ""){
			$content = str_replace("<br>","\n",$content) ;
			$content = str_replace(" "."&nbsp;","  ",$content) ;
		}
		return $content ;
	}
	
	/* Database Table Field Value Simple Updater */
	function simpleDatabaseUpdater($tablename, $set_field_name, $set_field_value, $where_field_name, $where_field_value){
		$ci = get_codeigniter_this() ;
		$query = "UPDATE $tablename SET $set_field_name = '$set_field_value'  WHERE $where_field_name = '$where_field_name' " ;
		$query = $ci->db->query($query) ;
		return true ;
	}
	
	/* Database Table Field Value Checker */
	function checkIfTwoFieldValuesMatchExtra($tablename, $field_name_1, $field_name_2, $value_for_field_1, $value_for_field_2, $extra){
		$ci = get_codeigniter_this() ;
		$query = "SELECT $field_name_1, $field_name_2 FROM $tablename WHERE $field_name_1 = '$value_for_field_1' AND $field_name_2 = '$value_for_field_2' AND $extra" ;
		$query = $ci->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			return true ;
		}
		return false ;
	}
	function checkIfTwoFieldValuesMatchExclude($tablename, $field_name_1, $field_name_2, $value_for_field_1, $value_for_field_2, $exclude_field, $exclude_value){
		$ci = get_codeigniter_this() ;
		$query = "SELECT $field_name_1, $field_name_2 FROM $tablename WHERE $field_name_1 = '$value_for_field_1' AND $field_name_2 = '$value_for_field_2' AND $exclude_field != '$exclude_value'" ;
		$query = $ci->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			return true ;
		}
		return false ;
	}
	function checkIfTwoFieldValuesMatch($tablename, $field_name_1, $field_name_2, $value_for_field_1, $value_for_field_2){
		$ci = get_codeigniter_this() ;
		$query = "SELECT $field_name_1, $field_name_2 FROM $tablename WHERE $field_name_1 = '$value_for_field_1' AND $field_name_2 = '$value_for_field_2' " ;
		$query = $ci->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			return true ;
		}
		return false ;
	}
	function checkAFieldValueExclude($value, $tablename, $fieldname, $exclude_field, $exclude_value){
		$ci = get_codeigniter_this() ;
		$query = "SELECT $fieldname FROM $tablename WHERE $fieldname = '$value' AND $exclude_field != '$exclude_value'" ;
		$query = $ci->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			return true ;
		}
		return false ;
	}
	function checkAFieldValue($value, $tablename, $fieldname){
		$ci = get_codeigniter_this() ;
		$query = "SELECT $fieldname FROM $tablename WHERE $fieldname = '$value' " ;
		$query = $ci->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			return true ;
		}
		return false ;
	}
	
	/* ID Generators */
	function checkAnId($id,$id_tablename,$id_fieldname){
		$ci = get_codeigniter_this() ;
		$query = "SELECT $id_fieldname FROM $id_tablename WHERE $id_fieldname = '$id' " ;
		$query = $ci->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			return true ;
		}
		return false ;
	}
	function createAnId($id_tablename,$id_fieldname){
		return creatAnId($id_tablename,$id_fieldname) ; 
	}
	function creatAnId($id_tablename,$id_fieldname){
		$id = ( mt_rand(10, 999) * 5 ) + mt_rand(1020025005, 1920025005) ; //10 digits max	
		$chkem = checkAnId($id,$id_tablename,$id_fieldname) ;
		while($chkem != false){ 
			$id = ( mt_rand(2, 1000) * 5 ) + mt_rand(100, 9990000999) ;	 //10 digits max
			$chkem = checkAnId($id,$id_tablename,$id_fieldname) ;
			}
		return $id ;
	}
	function checkuserid($userid){
		$ci = get_codeigniter_this() ;
		$query = "SELECT user_id FROM app_users WHERE user_id = '$userid' " ;
		$query = $ci->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			return true ;
		}
		return false ;
		}
	function createuserid(){
		$userid = ( mt_rand(10, 999) * 5 ) + mt_rand(1020025005, 1920025005) ; //10 digits max	
		$chkem = checkuserid($userid) ;
		while($chkem != false){ 
			$userid = ( mt_rand(2, 1000) * 5 ) + mt_rand(2020025005, 5520025005) ;	 //10 digits max
			$chkem = checkuserid($userid) ;
			}
		return $userid ;
	}
	
	/* Regular Session handlers */
	function setLastUrl(){
		$url = $_SERVER['SCRIPT_NAME']."?".$_SERVER['QUERY_STRING'] ;
		setcookie("last_url","$url");
	}
	function getLastUrl($val = 0){
		if(isset($_COOKIE['last_url'])){
			return protect($_COOKIE['last_url'] ) ;
		}else{ 
			if($val == 1){ return "search_projects.php" ;
			}else return 0 ; 
		}
	}
	
	/* Misc */
	function getSelect($name,$lower,$upper,$inc,$class=""){
		$o = "<select name='$name' class='$class'>" ;
			$o .= "<option value=\" \">-- SELECT --</option>" ;
			for($i = $lower; $i <= $upper; $i += $inc){
				$o .= "<option value='$i'>$i</option>" ;
			}
		$o .= "</select>" ;
		return $o ;
	}
	function getSelectOpts($num,$opts,$texts,$name='',$class=''){
		$o  = "<select name='$name' class='$class'>" ;
		 	for($i = 0; $i < $num; $i++){
				$o .= "<option value='".$opts[$i]."'>".$texts[$i]."</option>" ;
		 	}
		$o .= "</select>" ;
		return $o ;
	}
	function firstLetterToCaps($str){
		if($str != ""){
			$str[0] = strtoupper($str[0]) ;
		}
		return $str ;
	}
	function spaceAfterComma($str){
		$str = str_replace(",  ",",",$str);
		$str = str_replace(", ",",",$str);
		$str = str_replace(",",", ",$str);
		return $str ;
	}
	function arrayToCSV($array,$delimiter = ",",$packerOpenTag = "", $packerCloseTag = "",$spacer = ""){
		$str = $packerOpenTag ;
		$count = sizeof($array) ;
		$i = 0 ;
		foreach($array as $val){
			$str .= $val ;
			$i += 1;
			if($i < $count){
				$str .= "$delimiter"."$spacer" ;
			}
		}
		return $str.$packerCloseTag ;
	}
	function CSVToArray($CSV,$delimiter = ",",$packerOpenTag = "", $packerCloseTag = ""){
		$str = $CSV ;
		if($packerOpenTag != ""){
			$str = explode($packerOpenTag,$str) ;
			$str = $str[1] ;
		}
		if($packerCloseTag != ""){
			$str = explode($packerCloseTag,$str) ;
			$str = $str[0] ;
		}
		$array = str_getcsv($str, $delimiter) ;
		return $array ;
	}
	function getFirstAndLastNameFromNameString($name_str){
		$name = explode(" ",$name_str) ;
		$count = sizeof($name) ;
		if($count != 0){
			return array($name[0], $name[$count - 1]) ;
		}else{
			return array($name_str, "") ;
		}
	}
	function getFirstXLetters($str,$x = '240', $ext = ""){
		if(strlen($str) <= $x){
			$ext = "" ;
		}
		return substr($str,0,$x).$ext ;
	}
	function getFirstXLettersNoDefaultX($str, $x, $ext = ""){
		if(strlen($str) <= $x){
			$ext = "" ;
		}
		return substr($str,0,$x).$ext ;
	}
	function addSpaceToCenterOfLongString($str){
		$len = strlen($str) ;
		if($len > 1){
			$half_len = $len / 2 ;
			$str1 = substr($str, 0 , $half_len) ;
			$str2 = substr($str, $half_len) ;
		}
		return $str1." ".$str2 ;
	}
	function mail_utf8($to, $from_user, $from_email, $subject = '(No subject)', $message = ''){ 
//		ini_set('SMTP', "mail.osekee.com") ;
//		ini_set('smtp_port', 26) ;
		
      $from_user = "=?UTF-8?B?".base64_encode($from_user)."?=";
      $subject = "=?UTF-8?B?".base64_encode($subject)."?=";

      $headers = "From: $from_user <$from_email>\r\n". 
               "MIME-Version: 1.0" . "\r\n" . 
               "Content-type: text/html; charset=UTF-8" . "\r\n"; 
		
     return mail($to, $subject, $message, $headers); 
   }
   
   /* Convert An associative Array to a numeric one, discarding all the fieldnames */
   	function array_assoc_to_numeric($assoc_array){
		//$fieldnames_actual = array();
		$values = array();
		
		foreach($assoc_array as $k=>$v)
		{
		  if($k!='fieldnames'){
		//	$fieldnames_actual[] = $k;
			$values[] = $v;
		  }
		}
		return $values ;
	}
	
	/* Get Codeigniter $this Object */
	function get_codeigniter_this(){
		$ci =& get_instance() ;
		return $ci ;
	}
   
	/* Get a CodeIgniter Class from Widget Directory */
	function get_ci_class( $path ){
		$ci =& get_instance() ;
		
		$rootdir = $ci->config->item("base_url") ;
				
//		echo $_SERVER['SCRIPT_NAME']."<br/>" ;
//		echo ($_SERVER['PHP_SELF'])."<br/>" ;
//		echo APPPATH."<br/>" ;
//		echo BASEPATH."<br/>" ;
				
		$full_path = APPPATH.$path.'.php' ;
		
		require_once $full_path  ;
		
		$name = end( explode('/', $path) ) ;
		
		$class = ucfirst( $name ) ;
		
		$ci->$name = new $class() ;
		
		return $ci->$name ;
	}
	function get_wms_model_class( $model_name ){
		return get_ci_class( "models/".$model_name ) ;
	}
	function get_widget_ci_class( $path ){
		return get_ci_class( $path ) ;
	}
	
	/* Returns indicator string specified by int_length */
	function appendIndicator($int_length, $indicator){
		$indicator_string = "" ;
		$int_length = (int)($int_length) ;
		
		for($i = 1; $i <= $int_length; $i++){
			$indicator_string .= $indicator ;
		}
		return $indicator_string ;
	}
	function getAllCountriesBasicInfo(){
		$query = "SELECT country_id, name, iso_code_2, iso_code_3, calling_code FROM country WHERE status = 1 " ;
		
		$ci = get_codeigniter_this() ;
		
		$query = $ci->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$all_templates_array = array() ;
			$result_array = $query->result_array() ;
			for($i = 0; $i < count($result_array) ; $i++){
				
				$num_array = array_assoc_to_numeric($result_array[$i]) ;
				array_push($all_templates_array, $num_array ) ;	
			}
			return $all_templates_array ;
		}
		return false ;
	}

if ( ! function_exists('generate_options')){
	function generate_options($from,$to,$callback=false, $set_select = ""){
		date_default_timezone_set("GMT") ;
		$reverse=false;
		if($from>$to){
			$tmp=$from;
			$from=$to;
			$to=$tmp;
			$reverse=true;
		}
		$return_string=array();
		for($i=$from; $i<=$to; $i++){
			$opt = "";
			$opt .= "<option value='".$i."' " ;
			if($set_select != ""){
				$opt.= " ".set_select($set_select, $i)." " ;
			}
			$opt.='>'.($callback?$callback($i):$i)."</option>";
			$return_string[] = $opt;
		}
		if($reverse){
			$return_string=array_reverse($return_string);
		}
		return join('',$return_string);
	}
}
if ( ! function_exists('callback_month')){
	function callback_month($month)
	{
		return date('F',mktime(0,0,0,$month,1));
	}
}
if ( ! function_exists('format_date')){
	function format_date($date)
	{
		$parts = explode('-',$date);
		return date('F j, Y',mktime(0,0,0,$parts[1],$parts[2],$parts[0]));
	}
}

	function underscored_string($string){
		$string = str_replace(" ", "_", $string) ;
		$string = preg_replace("/[^A-Za-z0-9 ]/", '_', $string) ;
		return $string ;
	}
	
	function getAddressBarValue() {
		if(isset($_SERVER['HTTPS'])){
			$protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
		}else{
			$protocol = "http" ;
		}
		return $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	
	function getDynamicPageMetaTag($this_page_p = "", $this_page_p1 = ""){
		$ci = get_codeigniter_this() ;
		$ci->wms_api 				= get_ci_class('models/wms_api') ;
		$ci->wms_news				= get_ci_class('models/wms_news') ;
		$ci->wms_comments			= get_ci_class('models/wms_comments') ;
		$ci->wms_news_images		= get_ci_class('models/wms_news_images') ;
		
		$content_type = "" ;
		$content_id = "" ;
		
		if(isset($this_page_p) ){	
			if($this_page_p != ""){
				switch(protect($this_page_p)){
					case 'article':  	$content_type = 1 ; break ;
					case 'video':  		$content_type = 2 ;	break ;
					case 'photo_blog':  $content_type = 3 ;	break ;
					default:	$content_type = "" ; break ;	
				}
			}
		}
		
		if(isset($this_page_p1) ){	
			if($this_page_p1 != ""){
				$content_id = protect($this_page_p1) ;
			}
		}
		
		if( ($content_id != "") && ($content_type != "") ){
			//Get Cover Image For Content
			switch($content_type){
				case '1': 	$the_content_info 	= $ci->wms_news->getArticleInfo($content_id) ;
							if($the_content_info !== false){
								$the_content_id 			= $the_content_info->article_id ;	
								$the_cover_image_id 		= $the_content_info->cover_image_id ;
								$image_source 				= $ci->wms_news_images->getImageSourceById($the_cover_image_id, $ci->wms_api->rootdir."images/uploads/") ;
								$content_title 				= $the_content_info->title ;
								$content_description 		= $the_content_info->intro_text ;
								$this_page_url 				= $ci->wms_news->getContentLink('article', $the_content_id, $content_title) ;
								
								$meta = "" ;
								$meta .= "<meta property='og:url' 			content='$this_page_url' />" ;
								$meta .= "<meta property='og:title' 		content='$content_title' />" ;
								$meta .= "<meta property='og:image' 		content='$image_source' />" ;
								$meta .= "<meta property='og:site_name' 	content='AfricanHadithi.com' />" ;
								$meta .= "<meta property='og:description' 	content='$content_description' />" ;
								return $meta ;
							}
							break ;
				
				case '2': 	$the_content_info 	= $ci->wms_news->getVideoInfo($content_id) ;
							if($the_content_info !== false){
								$the_content_id 			= $the_content_info->video_id ;	
								$the_cover_image_id 		= $the_content_info->cover_image_id ;
								$image_source 				= $ci->wms_news_images->getImageSourceById($the_cover_image_id, $ci->wms_api->rootdir."images/uploads/") ;
								$content_title 				= $the_content_info->title ;
								$content_description 		= $the_content_info->intro_text ;
								$this_page_url 				= $ci->wms_news->getContentLink('video', $the_content_id, $content_title) ;
								
								$meta = "" ;
								$meta .= "<meta property='og:url' 			content='$this_page_url' />" ;
								$meta .= "<meta property='og:title' 		content='$content_title' />" ;
								$meta .= "<meta property='og:image' 		content='$image_source' />" ;
								$meta .= "<meta property='og:site_name' 	content='AfricanHadithi.com' />" ;
								$meta .= "<meta property='og:description' 	content='$content_description' />" ;
								return $meta ;
							}
							break ;
				
				case '3': 	$the_content_info 	= $ci->wms_news->getPhotoBlogPostInfo($content_id) ;
							if($the_content_info !== false){
								$the_content_id 			= $the_content_info->photo_blog_post_id ;	
								$the_cover_image_id 		= $the_content_info->cover_image_id ;
								$image_source 				= $ci->wms_news_images->getImageSourceById($the_cover_image_id, $ci->wms_api->rootdir."images/uploads/") ;
								$content_title 				= $the_content_info->title ;
								$content_description 		= $the_content_info->intro_text ;
								$this_page_url 				= $ci->wms_news->getContentLink('photo', $the_content_id, $content_title) ;
								
								$meta = "" ;
								$meta .= "<meta property='og:url' 			content='$this_page_url' />" ;
								$meta .= "<meta property='og:title' 		content='$content_title' />" ;
								$meta .= "<meta property='og:image' 		content='$image_source' />" ;
								$meta .= "<meta property='og:site_name' 	content='AfricanHadithi.com' />" ;
								$meta .= "<meta property='og:description' 	content='$content_description' />" ;
								return $meta ;
							}
							break ;
						
			
			}
		}
	}
	
	function is_bot($user_agent) {
        return preg_match('/(facebook|googlebot|abot|dbot|ebot|hbot|kbot|lbot|mbot|nbot|obot|pbot|rbot|sbot|tbot|vbot|ybot|zbot|bot\.|bot\/|_bot|\.bot|\/bot|\-bot|\:bot|\(bot|crawl|slurp|spider|seek|accoona|acoon|adressendeutschland|ah\-ha\.com|ahoy|altavista|ananzi|anthill|appie|arachnophilia|arale|araneo|aranha|architext|aretha|arks|asterias|atlocal|atn|atomz|augurfind|backrub|bannana_bot|baypup|bdfetch|big brother|biglotron|bjaaland|blackwidow|blaiz|blog|blo\.|bloodhound|boitho|booch|bradley|butterfly|calif|cassandra|ccubee|cfetch|charlotte|churl|cienciaficcion|cmc|collective|comagent|combine|computingsite|csci|curl|cusco|daumoa|deepindex|delorie|depspid|deweb|die blinde kuh|digger|ditto|dmoz|docomo|download express|dtaagent|dwcp|ebiness|ebingbong|e\-collector|ejupiter|emacs\-w3 search engine|esther|evliya celebi|ezresult|falcon|felix ide|ferret|fetchrover|fido|findlinks|fireball|fish search|fouineur|funnelweb|gazz|gcreep|genieknows|getterroboplus|geturl|glx|goforit|golem|grabber|grapnel|gralon|griffon|gromit|grub|gulliver|hamahakki|harvest|havindex|helix|heritrix|hku www octopus|homerweb|htdig|html index|html_analyzer|htmlgobble|hubater|hyper\-decontextualizer|ia_archiver|ibm_planetwide|ichiro|iconsurf|iltrovatore|image\.kapsi\.net|imagelock|incywincy|indexer|infobee|informant|ingrid|inktomisearch\.com|inspector web|intelliagent|internet shinchakubin|ip3000|iron33|israeli\-search|ivia|jack|jakarta|javabee|jetbot|jumpstation|katipo|kdd\-explorer|kilroy|knowledge|kototoi|kretrieve|labelgrabber|lachesis|larbin|legs|libwww|linkalarm|link validator|linkscan|lockon|lwp|lycos|magpie|mantraagent|mapoftheinternet|marvin\/|mattie|mediafox|mediapartners|mercator|merzscope|microsoft url control|minirank|miva|mj12|mnogosearch|moget|monster|moose|motor|multitext|muncher|muscatferret|mwd\.search|myweb|najdi|nameprotect|nationaldirectory|nazilla|ncsa beta|nec\-meshexplorer|nederland\.zoek|netcarta webmap engine|netmechanic|netresearchserver|netscoop|newscan\-online|nhse|nokia6682\/|nomad|noyona|nutch|nzexplorer|objectssearch|occam|omni|open text|openfind|openintelligencedata|orb search|osis\-project|pack rat|pageboy|pagebull|page_verifier|panscient|parasite|partnersite|patric|pear\.|pegasus|peregrinator|pgp key agent|phantom|phpdig|picosearch|piltdownman|pimptrain|pinpoint|pioneer|piranha|plumtreewebaccessor|pogodak|poirot|pompos|poppelsdorf|poppi|popular iconoclast|psycheclone|publisher|python|rambler|raven search|roach|road runner|roadhouse|robbie|robofox|robozilla|rules|salty|sbider|scooter|scoutjet|scrubby|search\.|searchprocess|semanticdiscovery|senrigan|sg\-scout|shai\'hulud|shark|shopwiki|sidewinder|sift|silk|simmany|site searcher|site valet|sitetech\-rover|skymob\.com|sleek|smartwit|sna\-|snappy|snooper|sohu|speedfind|sphere|sphider|spinner|spyder|steeler\/|suke|suntek|supersnooper|surfnomore|sven|sygol|szukacz|tach black widow|tarantula|templeton|\/teoma|t\-h\-u\-n\-d\-e\-r\-s\-t\-o\-n\-e|theophrastus|titan|titin|tkwww|toutatis|t\-rex|tutorgig|twiceler|twisted|ucsd|udmsearch|url check|updated|vagabondo|valkyrie|verticrawl|victoria|vision\-search|volcano|voyager\/|voyager\-hc|w3c_validator|w3m2|w3mir|walker|wallpaper|wanderer|wauuu|wavefire|web core|web hopper|web wombat|webbandit|webcatcher|webcopy|webfoot|weblayers|weblinker|weblog monitor|webmirror|webmonkey|webquest|webreaper|websitepulse|websnarf|webstolperer|webvac|webwalk|webwatch|webwombat|webzinger|wget|whizbang|whowhere|wild ferret|worldlight|wwwc|wwwster|xenu|xget|xift|xirq|yandex|yanga|yeti|yodao|zao\/|zippp|zyborg|\.\.\.\.)/i', $user_agent);
    }

    //example usage
    //if (! is_bot($_SERVER["HTTP_USER_AGENT"])) echo "it's a human hit!";
	
	function prepare_my_domain_url($input, $domain = 'lnk.ng'){	
		// in case scheme relative URI is passed, e.g., //www.google.com/
		$input = trim($input, '/');
		
		// If scheme not included, prepend it
		if (!preg_match('#^http(s)?://#', $input)) {
			$input = 'http://' . $input;
		}
		
		$urlParts = parse_url($input);
		
		// remove www
		$domain = preg_replace('/^www\./', '', $urlParts['host']);
		
//		echo "<h3>".$urlParts['path']."</h3>" ;
		
		$path = "" ;
		
		if(isset($urlParts['path'])){
			$path = preg_replace('/'.$domain.'\//', '', $urlParts['path']);
		}
		
		return $domain.$path ;
	}
	
	
	function fb_share_plugin($this_page_url){
		$fb_share = "" ;
		$fb_share .= "<div class='facebook_recommend_plug'>" ;
			$fb_share .= "<div class='fb-share-button' data-href='".$this_page_url."' data-width='60' data-type='button_count'></div>" ;
		$fb_share .= "</div>" ;
		return $fb_share ;
	}
	function tw_tweet_plugin($this_page_url){
		$tw_tweet = "" ;
		$tw_tweet .= "<div class='twitter_tweet_plug'>" ;
			$tw_tweet .= '<a href="https://twitter.com/share" class="twitter-share-button" data-url="'.$this_page_url.'" data-via="AfricanHadithi" data-lang="en-gb">Tweet</a>' ;
			$tw_tweet .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>" ;
		$tw_tweet .= "</div>" ;
		return $tw_tweet ;
	}
	
	
/* End of file main_helper.php */
/* Location: ./application/helpers/main_helper.php */