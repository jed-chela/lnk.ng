<?php
class Pages extends CI_Controller {
	
	var $in_data ;
	
	public function index($p1 = "", $p2 = "", $p3 = "", $p4 = "", $p5 = "", $p6 = "")
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
		
		$rootdir = $this->config->item("base_url") ;	
		
		$data = array(
					  'this_page_p'	 => 'pages', //The name of this controller
					  'params' => 'params',			
					  'this_page_p0' => 'index',	//The name of the function called within this controller
					  'this_page_p1' => $p1,
					  'this_page_p2' => $p2,
					  'this_page_p3' => $p3,
					  'this_page_p4' => $p4,
					  'this_page_p5' => $p5,
					  'this_page_p6' => $p6
					  ) ;
		
		$this->in_data = $data ;
		
		/*** CONFIGURE PAGE SETTINGS ***/
		/* Enter the Main View File Name for this page */
		$view_dir_name = 'home' ;
		
		// Choose The Page Template to load the unique Module Positions for this page.
		$this->content_loader->set_page_template("static_html_pages") ;
		
		
		// Enter App extension if your Views Folder Contains views for Multiple Apps
		$app_ext = 'default/' ;
		
		//Load Page for App
		$this->load->view($app_ext.$view_dir_name, $data);
	}
	
	
}
?>