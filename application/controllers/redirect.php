<?php
class Redirect extends CI_Controller {
	
	var $in_data ;
	
	public function index($params = "")
	{
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('user_agent');
		
		$this->load->database();
		
		$this->load->model('user_sessions') ;
		$this->load->model('content_loader') ;
		
		$this->load->model('lnkng') ;
		
		$this->load->helper(array('form', 'url'));
		
		$this->load->helper('main');
		$this->load->helper('time_functions');
		$this->load->helper('encryption');
		
		$data = array(
					  'home_params' => $params,
					  'this_page_p'	 => 'listen'
					  ) ;
		
		$this->in_data = $data ;
		
		/*** LOG PAGE VISIT INFO ***/
		
		//Load Widget: General Page Logger
		$page_logger_widget = $this->content_loader->getWidgetData("sys_general_page_view_logger") ;
	//	echo $page_logger_widget ;
		
		$code = $params ;
		
		$this->lnkng->redirectShortURL($code) ;
		
	}
	
	
}
?>