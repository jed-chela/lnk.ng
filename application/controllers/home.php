<?php
class Home extends CI_Controller {
	
	var $in_data ;
	
	public function index($params = "")
	{
		$this->load->library('session');
		$this->load->library('form_validation');
		
		$this->load->database();
		
		$this->load->model('user_sessions') ;
		$this->load->model('content_loader') ;
		
		$this->load->helper(array('form', 'url'));
		
		$this->load->helper('main');
		$this->load->helper('time_functions');
		$this->load->helper('encryption');
		
		$data = array(
					  'home_params' => $params
					  ) ;
		
		$this->in_data = $data ;
		
		/*** CONFIGURE PAGE SETTINGS ***/
		/* Enter the Main View File Name for this page */
		$view_dir_name = 'default/home' ;
		
		// Choose The Page Template to load the unique Module Positions for this page.
		$this->content_loader->set_page_template("homepage") ;
		
		//Load Page for App
		$this->load->view($view_dir_name, $data);
	}
	
	
}
?>