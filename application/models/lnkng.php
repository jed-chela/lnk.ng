<?php

class Lnkng extends CI_Model{
	//Constructor
	function __construct(){
	// Call the Model constructor
        parent::__construct();
		$this->rootdir = $this->config->item('base_url') ;
		
		$this->load->model("lnkng_engine") ;
	}
	
	private $lnk_id ;
	
	// Validate a URL
	public function validateInputUrl($string){
		$url = filter_var($string, FILTER_VALIDATE_URL ) ;
		if($url !== false){ return $url ; }
		return false ;
	}
	
	private function save_long_url_and_get_record_id($long_url){
		date_default_timezone_set("GMT") ;
		$current_datetime = date( 'Y-m-d H:i:s' ) ;
		
		//auto_id, long_url, short_url, extra, status, time
		$extra = "" ;
		$status = "" ;
		$query = "INSERT INTO url_list(long_url, extra, status, time) VALUES ('$long_url', '$extra', '$status', '$current_datetime') " ;
		$query = $this->db->query($query) ;
		
		$auto_id = mysql_insert_id() ;
		if($auto_id > 0){
			return $auto_id ;
		}
		return false ;
	}
	
	private function get_existing_code_for_long_url($long_url){
		$query = "SELECT code FROM url_list WHERE long_url = '$long_url' " ;
		$query = $this->db->query($query) ;
		if($query->num_rows() > 0){
			$result_array = $query->result_array() ;
			return $result_array[0]['code'] ;
		}
		return false ;
	}
	
	private function encode_integer_id($int_id){
		if(is_numeric($int_id) ){
			$code = $this->lnkng_engine->encode($int_id) ;
			if($code != ""){
				return $code;
			}
		}
		return false ;
	}
	private function decode_coded_string($code){
		if($code != ""){
			$int_id = $this->lnkng_engine->decode($code) ;
			if($int_id != ""){
				return $int_id;
			}
		}
		return false ;
	}
	
	private function store_code_by_id($int_id, $code){
		$query = "UPDATE url_list SET code = '$code' WHERE auto_id = '$int_id' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	private function shorten_long_url($long_url){
		//RESERVED url codes
		$reserved_codes = array('a','g','admin','home','shorten_xhr','about','contact','team','developers','api') ;
		
		$long_url = trim($long_url) ;
		if  ( $ret = parse_url($long_url) ){
			if ( !isset($ret["scheme"]) ){
				$long_url = "http://{$long_url}";
			}
		}
		
		if($this->validateInputUrl($long_url) !== false){
			$existing_code = $this->get_existing_code_for_long_url($long_url) ;
			if($existing_code !== false){
				return $existing_code ;
			}
			
			$int_id = $this->save_long_url_and_get_record_id($long_url) ;
			if($int_id !== false){
				$code = $this->encode_integer_id($int_id) ;
				if($code !== false){
					while(array_search($code, $reserved_codes) !== false ){
						$int_id = $this->save_long_url_and_get_record_id($long_url) ;
						if($int_id !== false){
							$code = $this->encode_integer_id($int_id) ;
						}
					}
					
					if($this->store_code_by_id($int_id, $code) === true){
						return $code ;
					}					
				}
			}
		}
		return false ;
	}
	
	public function handleShortenRequest($long_url, $prefix = "lnk.ng/" ){
		if($long_url != ""){
			$code = $this->shorten_long_url($long_url) ;
			if($code !== false){
				return $prefix.$code ;
			}
		}
		return false ;
	}
	
	public function handleNONAsynchronousShortenRequest($prefix = "lnk.ng/" ){
		if(isset($_POST['long_url_submit'])){
			$long_url = protect($_POST['long_url']) ;
			
			$short_url = $this->handleShortenRequest($long_url) ;
			if($short_url !== false){
				$html = "" ;
				$html .= "<div class='short_url_box_inner'>" ;
					$html .= "<div class='ref_long_url'>url: $long_url</div>" ;
					$html .= "<div class='ref_short_url'>" ;
						$html .= "<label>Short URL:</label>" ;
						$html .= "<span class='short_url'>".$short_url."</span>" ;
					$html .= "</div>" ;
				$html .= "</div>" ;
				
				return array(true, $html) ;
			}else{
				
				$error_message = "<div class='ref_error'>An error occurred! The URL failed to shorten.</div>" ;
			
				return array(false, $error_message, $long_url) ;
			}
		}// end if 
	}
	
	public function getLongURLByCode($code){
		$int_id = $this->decode_coded_string($code) ;
		if($int_id != ""){
			$query = "SELECT long_url FROM url_list WHERE auto_id = '$int_id' " ;
			$query = $this->db->query($query) ;
			if($query->num_rows() > 0){
				$result_array = $query->result_array() ;
				return $result_array[0]['long_url'] ;
			}
		}
		return false ;
	}
	public function redirectShortURL($code){
		$long_url = $this->getLongURLByCode($code) ;
		if($long_url !== false){
			//Store Redirection Info
			if($this->storeRedirectionInfo($code, $long_url) === true){
				//Execute Redirect
				header("Location:".$long_url) ;
			}
		}
	}
	
	public function storeRedirectionInfo($code, $long_url){
		$redirect_url 		= $this->rootdir.$code ;
		$referral_url 		= $redirect_url ;
		
		if(isset($this->agent)){
			if ($this->agent->is_referral()){
				$referral_url = $this->agent->referrer();
			}
		}
		
		$destination_url 	= $long_url ;
		
		if(($redirect_url != "") && ($destination_url != "")){
			$redirect_record_id = $this->checkRedirectionInfo($referral_url, $redirect_url, $destination_url) ;
			if($redirect_record_id === false){
				// INSERT FRESH REDIRECT RECORD
				$count = 1 ; $status = 1 ;
				$query = "INSERT INTO url_redirect(referral_url, redirect_url, destination_url, count, status) 
							VALUES('$referral_url', '$redirect_url', '$destination_url', '$count', '$status') " ;
				$query = $this->db->query($query) ;
				return true ;
			}else{
				// UPDATE EXISTING REDIRECT RECORD
				$query = "UPDATE url_redirect SET count = (count + 1) WHERE id = '$redirect_record_id' " ;
				$query = $this->db->query($query) ;
				return true ;
			}
		}
		return false ;
	}
	public function checkRedirectionInfo($referral_url, $redirect_url, $destination_url){
		$query = "SELECT id FROM url_redirect WHERE referral_url = '$referral_url' AND redirect_url = '$redirect_url' AND destination_url = '$destination_url' " ;
		$query = $this->db->query($query) ;
		if($query->num_rows() > 0){
			$result_array = $query->result_array() ;
			return $result_array[0]['id'] ;
		}
		return false ;
	}
	
}

?>