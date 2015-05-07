<?php
class Shorten_xhr extends CI_Controller {
	
	var $in_data ;
	
	public function index($params = "")
	{
		$this->load->library('session');
		$this->load->library('form_validation');
		
		$this->load->database();
		
		$this->load->model('user_sessions') ;
		$this->load->model('lnkng') ;
		
		$this->load->helper(array('form', 'url'));
		
		$this->load->helper('main');
		$this->load->helper('time_functions');
		$this->load->helper('encryption');
				
		$data = array(
					  'home_params' => $params
					  ) ;
		
		$this->in_data = $data ;
		
		$res_array = array('success' => false, 'html' => '') ;
		
		if(isset($_POST['long_url'])){
			
			$long_url = protect($_POST['long_url']) ;
			
			$short_url = $this->lnkng->handleShortenRequest($long_url) ;
			if($short_url !== false){
				$html = "" ;
				$html .= "<div class='short_url_box_inner'>" ;
					$html .= "<div class='ref_long_url'>url: $long_url</div>" ;
					$html .= "<div class='ref_short_url'>" ;
						$html .= "<label>Short URL:</label>" ;
						$html .= "<span class='short_url'>".$short_url."</span>" ;
					$html .= "</div>" ;
				$html .= "</div>" ;
				
				$res_array = array(
					'success' => true,
					'html' => $html				
				) ;
			}
		}
		
		echo json_encode($res_array) ;
		
	}
	
	
}
?>