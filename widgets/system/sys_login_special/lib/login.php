<?php

class Login extends CI_Model{	
	//Constructor
	function __construct(){
	// Call the Model constructor
        parent::__construct();
		$this->rootdir = $this->config->item('base_url') ;
	}
	
	private $user_id ;
	
	public function getLoginForm(){
		$o = "" ;
		$o .= "<div class='form-box'>" ;
		$o .= "<form role='form' class='form-vertical' method='post' action=''>" ;
				$o .= "<div id='form-error'>" ; 
					$o .= validation_errors() ;
				$o .= "</div>" ;
				
				$o .= "<div class='form-group'>" ;
					$o .= "<label for='log_email'>Email address</label>" ;
					$o .= "<input type='email' class='form-control' name='log_email' id='log_email' placeholder='Enter email'>" ;
				$o .= "</div>" ;
				$o .= "<div class='form-group'>" ;
					$o .= "<label for='log_pass'>Password</label>" ;
					$o .= "<input type='password' class='form-control' name='log_pass' id='log_pass' placeholder='Password'>" ;
				$o .= "</div>" ;
				$o .= "<button type='submit' name='log_submit' class='btn btn-default'>Login</button>" ;
		$o .= "</form>" ;
		$o .= "</div>" ;
		
		return $o ;
	}
	public function getWelcomeInfo(){
		$this->user_id = $this->user_sessions->getUserId() ;
		$user_email = $this->user_sessions->getUserEmail($this->user_id) ;
		$o = "" ;
		$o .= "<div class='login-welcome-info'>" ;
			$o .= "Welcome, ".$user_email."!" ;
		$o .= "</div>" ;
		return $o ;
	}
	public function getLogoutForm(){
		$o = "" ;
		$o .= "<div class='special-logout-box'>" ;
		$o .= "<form role='form' class='form-vertical' method='post' action=''>" ;
				$o .= "<div id='form-error'>" ; 
					$o .= validation_errors() ;
				$o .= "</div>" ;
				$o .= "<button type='submit' name='log_user_out' class='btn btn-default'>Logout</button>" ;
		$o .= "</form>" ;
		$o .= "</div>" ;
		
		return $o ;
	}
	
	public function validateLoginForm(){
		
		/* Validate REGISTER INDIVIDUAL */
		if($this->input->post('log_submit') !== false){
			$this->form_validation->set_rules('log_email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('log_pass', 'Password', 'required');
			
			if ($this->form_validation->run() == FALSE){
				return false ;
			}else{
				return true ;
			}
		}
	}
	public function executeLogin(){
		$email = protect($this->input->post('log_email') ) ;
		$password = protect($this->input->post('log_pass') ) ;
		
		return $this->user_sessions->logUserInDefault($email, $password) ;
	}
	public function validateLogoutForm(){
		/* Validate REGISTER INDIVIDUAL */
		if($this->input->post('log_user_out') !== false ){
			$this->user_sessions->logoutCurrentUser() ;
			unset($this->user_id) ;
			return true ;
		}
	}
	
}