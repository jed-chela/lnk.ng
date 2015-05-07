<?php
class Admin extends CI_Controller {

	public function index($param = "news", $function = "", $p1 = "", $p2 = "", $p3 = "", $p4 = "", $p5 = "")
	{
		$this->load->library('session');
		$this->load->library('form_validation');
		
		$this->load->database();
		
		$this->load->model('user_sessions') ;
		$this->load->model('content_loader') ;
		$this->load->model('admin_manager') ;
		
		$this->load->helper(array('form', 'url'));
		
		$this->load->helper('main');
		$this->load->helper('time_functions');
		$this->load->helper('encryption');
		
		$rootdir = $this->config->item("base_url") ;
				
		//Load Widget: Show Login Form
		$login_widget = $this->content_loader->getWidgetData("sys_login_special") ;
				
		$data = array(
					  'index_param' => $param,
					  'index_function' => $function,
					  'index_p1' => $p1,
					  'index_p2' => $p2,
					  'index_p3' => $p3,
					  'index_p4' => $p4,
					  'index_p5' => $p5,
					  'login_widget_params' => $login_widget
					  ) ;
		
		if($this->user_sessions->userLoggedIn() === true){
			//Check If User is Admin
			$privilege = $this->user_sessions->getCurrentUserPrivilege() ;
			$optional_accounts = $this->user_sessions->getOptionalUserAccounts($this->user_sessions->getUserId()) ;
			
			if($privilege == 10){
				//User Has the Authorization to Access the Admin Section
				if($this->admin_manager->paramIsValid($param) === true){
					$this->load->view('admin/'.$param, $data);
				}else{
					$this->load->view('error');
				}
				
			}else if($optional_accounts !== false){
				$this->load->view('admin/'.$param, $data);
			}else{
				//User Doesn't have admin privileges
				$o = "" ;
				$o .= "<!DOCTYPE html>" ;
				$o .= "<html><head>" ;
				$o .= $this->content_loader->getDefaultCSS() ;
				$o .= "</head><body>" ;
				
				$o .= "<div class='block'><div id='form-error'>" ;
					$o .= "<div class='spacer-medium'></div>" ;
					$o .= "<p class='pad_left_medium'>You are not logged in with an Admin Account!</p>" ;
					$o .= "<div class='spacer-medium'></div>" ;
				$o .= "</div></div>" ;
				
				//Get Widget Data: Show Login Form
				$o .= "<div class='block'><div class='block_in pad_left_medium'>" ;
					$o .= $login_widget ;
				$o .= "</div></div></body>" ;
				
				$o .= "</body>" ;
				echo $o ;
			}
			
		}else{
			//USER is not Logged In
			$o = "" ;
			
			$o .= "<!DOCTYPE html>" ;
			$o .= "<html><head>" ;
			$o .= $this->content_loader->getDefaultCSS() ;
			$o .= "</head><body>" ;
			
			$o .= "<div class='block'><div id='form-error'>" ;
				$o .= "<div class='spacer-medium'></div>" ;
				$o .= "<p class='pad_left_medium'>ADMIN: Log in to your Account!</p>" ;
				$o .= "<div class='spacer-medium'></div>" ;
			$o .= "</div></div>" ;
			
			//Get Widget Data: Show Login Form
			$o .= "<div class='block'><div class='block_in pad_left_medium'>" ;
				$o .= $login_widget ;
			$o .= "</div></div></body>" ;
			echo $o ;
		}
	}
	
	
}
?>