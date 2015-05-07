<?php

class User_sessions extends CI_Model{	
	//Constructor
	function __construct(){
	// Call the Model constructor
        parent::__construct();
		$this->rootdir = $this->config->item('base_url') ;
	}
	
	private $user_id ;
	
	public function userLoggedIn(){
		//Check Cookies
		
		//Check Sessions
		if($this->session->userdata('usr_logged_in_170107') === true){
			return true ;
		}else{
			return false ;
		}
	}
	public function getUserId(){
		$this->user_id = $this->session->userdata('usr_logged_in_170107user_id') ;
		return $this->user_id ;
	}
	public function getUserIDOwner($second_user_id){
		$this->getUserId() ;
		if($this->user_id == $second_user_id){ 
			return true ; 
		}else{ return false ; }
	}
	public function getUserStatus($user_id){
		$query = "SELECT status FROM app_users WHERE user_id = '$user_id'" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			$a_status 	= $result_obj->status ;
			return array(1, $a_status ) ;
		}
		return array(0) ;
	}
	public function getSuspendedUserStatus($email, $password){
		$password = passEncrypt($password) ;
		$query = "SELECT status FROM app_users WHERE email = '$email' AND password = '$password'" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			$a_status 	= $result_obj->status ;
			return array(1, $a_status ) ;
		}
		return array(0) ;
	}
	public function checkIfUserExists($user_id){
		$query = "SELECT user_id, status FROM app_users WHERE user_id = '$user_id'" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			$a_user_id 	= $result_obj->user_id ;
			$a_status 	= $result_obj->status ;
			return array(1, $a_user_id, $a_status ) ;
		}
		return array(0) ;
	}
	public function checkIfUserExistsBool($user_id){
		$query = "SELECT user_id, status FROM app_users WHERE user_id = '$user_id'" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			$a_user_id 	= $result_obj->user_id ;
			$a_status 	= $result_obj->status ;
			return array(true, $a_user_id, $a_status ) ;
		}
		return false ;
	}
	private function checkIfEmailIsFreeToUse($email){
		$result = $this->checkIfEmailExists($email) ;
		if($result == 1 ){
			return 0 ;
		}else if($result == 0 ){
			return 1 ;
		}
	}
	private function checkIfEmailExists($email){
		$query = "SELECT email FROM app_users WHERE email = '$email'" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			return true ;
		}
		return false ;
	}
	public function getUserUsername($the_user_id){
		$query = "SELECT username FROM app_users WHERE user_id = '$the_user_id'" ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			return $result_obj->username ;
		}
		return false ;
	}
	public function getUserEmail($the_user_id){
		$query = "SELECT email FROM app_users WHERE user_id = '$the_user_id'" ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			return $result_obj->email ;
		}
		return false ;
	}
	public function getUserIdByEmail($email){
		$query = "SELECT user_id FROM app_users WHERE email = '$email'" ;
		$query = $this->db->query($query) ;
		
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			return $result_obj->user_id ;
		}
		return false ;
	}
	private function registerUserRegular($email, $password, $vpassword){
		//CREATE user_id
		//STORE DETAILS IN app_users and user_details
		$user_id = creatAnId('app_users','user_id') ;
		if($password == $vpassword){
			if($this->checkIfEmailIsFreeToUse($email) == 0){
				/* Email Address may have Already been Used */
				return 5 ;
			}
			$privilege = 1 ;
			$res_basic = $this->storeBasicUserData($user_id, $email, $password, $privilege) ;
			if($res_basic == 1){
				//Successful Registration
				return 1 ;
			}else{ return 0 ; }
		}
	}
	public function handleIndividualUserRegistration(){
		$email = strtolower(protect($_POST['reg_email'])) ;
		$password = protect($_POST['reg_pass']) ;
		$vpassword = protect($_POST['reg_vpass']) ;
		$reg_res = $this->registerUserRegular($email, $password, $vpassword) ;
		if($reg_res == 1){
			$this->logUserIn($email, $password) ;
			return array(1, "Registration for $email was sucessfull!", $email, $password) ;
		}else if($reg_res == 5){
			return array(5, "Email may have already been registered on this site!", $email) ;
		}else{
			return array(0, "Registration for $email failed!", $email) ;
		}
	}
	public function registerUserType1($email, $password, $fname, $lname, $telephone, $country_id){
		$user_id = creatAnId('app_users','user_id') ;
		$task1 = $this->user_sessions->storeBasicUserData($user_id, $email, $password) ;
		$task2 = $this->user_sessions->storePersonalUserDataType1($user_id, $fname, $lname) ;
		$task3 = $this->user_sessions->storeUserAddressInformationType1($user_id, $fname, $lname, $telephone, $country_id) ;
		
		if($task1 == 1){
			if($task2 == 1){
				if($task3 == 1){
					return 1 ;
				}
			}
		}
		return 0 ;
	}
	public function registerUserType2($email, $password, $fname, $lname){
		$user_id = creatAnId('app_users','user_id') ;
		$task1 = $this->user_sessions->storeBasicUserData($user_id, $email, $password) ;
		$task2 = $this->user_sessions->storePersonalUserDataType1($user_id, $fname, $lname) ;
		
		if($task1 == 1){
			if($task2 == 1){
				return $user_id ;
			}
		}
		return false ;
	}
	private function storeBasicUserData($user_id, $email, $password, $privilege = 1){
		$status = 1 ;
		$password = passEncrypt($password) ;
		$query = "INSERT INTO app_users(user_id,email,password,privilege,status,time) VALUES ('$user_id','$email','$password','$privilege', '$status', CURRENT_TIMESTAMP)" ;
		$result = $this->db->query($query) ;
		return 1 ;
	}
	private function storePersonalUserDataType1($user_id, $fname, $lname){
		$status = 1 ;
		$query = "INSERT INTO app_user_details(user_id,fname,lname,status,time) VALUES('$user_id','$fname','$lname','$status',CURRENT_TIMESTAMP)" ;
		$result = $this->db->query($query) ;
		return 1 ;
	}
	private function storeUserAddressInformationType1($user_id, $fname, $lname, $phone_number, $country_id){
		$address_id = creatAnId('app_user_address','address_id') ;
		$status = 1 ;
		$query = "INSERT INTO app_user_address(address_id, user_id, fname, lname, telephone_no, country_id, status, time) VALUES('$address_id', '$user_id','$fname','$lname','$phone_number','$country_id','$status',CURRENT_TIMESTAMP)" ;
		$result = $this->db->query($query) ;
		return 1 ;
	}
	public function logUserInDefault($email, $password){
		//Log User In
		//Log this attempt in event log
		$log_res = $this->logUserIn($email, $password) ;
		if($log_res[0] == 1){
			//User has been logged in sucessfully!
			$user_id = $log_res[1] ;
			$user_privilege = $log_res[2] ;
			$user_status = $log_res[3] ;
			if($user_status == 1){
				
				//Enable Sessions
				
				$sess_enable = $this->enableUserSessions($log_res[1]) ;
				if($sess_enable == 1){
					return 1 ;
					//Log this successful action in event log
				}else{ return 0 ; }
			}else if($user_status == 9){
				//This User Account has recently been deleted
				//Log User Out
				$this->logoutCurrentUser() ;
				return 2 ;
			}
		}else if($log_res[0] == 0){
			//Login Failed: Email or Password Combination is Incorrect!
			return 0 ;
		}
		
	}
	public function sendRegSuccessEmail($message, $receiver_email ){
		$sender_email = "" ;
		$m = $message ;
				
		$to = $receiver_email ;
		$from_user = "IvyTracks_noreply" ;
		$from_email = "noreply.ivytracks@ivypeer.com" ;
		$from = "$from_user <$from_email>" ;
		$subject = "IvyTracks Successful Registration" ;
		
		$res = mail_utf8($to, $from_user, $from_email, $subject , $m) ;
		if($res === true){
			//Email Send Successful
			return array(1,"Mail to $to was sent successfully") ;
		}else{
			//Email Send Failed
			return array(0,"An error occured while sending a Mail to $to. Mail send Failed!") ;
		}
	}
	public function logUserIn($email, $password){
		//ENCRYPT password
		//CHECK app_users
		//IF TRUE enableUserSessions($user_id)
		//RETURN 1
		
		$email = strtolower($email) ;
		$password = passEncrypt($password) ;
		$query = "SELECT user_id, privilege,status FROM app_users WHERE (email = '$email') AND (password = '$password')" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			$user_id 	= $result_obj->user_id ;
			$privilege 	= $result_obj->privilege ;
			$status 	= $result_obj->status ;
			return array(1, $user_id, $privilege, $status) ;
		}
		return array(0) ;
	}
	public function confirmloggedInUserPassword($password){
		//ENCRYPT password
		//CHECK app_users
		$user_id = $this->getUserId() ;
		$password = passEncrypt($password) ;
		
		$query = "SELECT user_id FROM app_users WHERE (user_id = '$user_id') AND (password = '$password')" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			return true ;
		}
		return false ;
	}
	public function enableUserSessions($user_id){
		//ENABLE logged_In Session
		//ENABLE logged_In_UserID Session
		$this->session->set_userdata('usr_logged_in_170107',true) ;
		$this->session->set_userdata('usr_logged_in_170107user_id',$user_id) ;
		return 1 ;
	}
	public function logoutCurrentUser(){
		//ENABLE logged_In Session
		//ENABLE logged_In_UserID Session
		$this->session->unset_userdata('usr_logged_in_170107') ;
		$this->session->unset_userdata('usr_logged_in_170107user_id') ;
		return 1 ;
	}
	
	public function handleLogin(){
		$email		= strtolower(protect($_POST['log_email']) ) ;
		$password	= protect($_POST['log_pass']) ;
		$login_res = $this->logUserIn($email, $password) ;

		if($login_res[0] == 1){
			if(isset($login_res[1] ) && ($login_res[3] == '1' )){
				$user_id = $login_res[1] ;
				$login_session = $this->enableUserSessions($user_id) ;
				
				if(isset($_POST['l_remember'])){
					$remember_me = protect($_POST['l_remember']) ;
					if($remember_me == 1){
						echo $this->rememberMe($user_id) ;
					}
				}
				return array(1, $email) ;
			}else if(isset($login_res[1] ) && ($login_res[3] == '8' )){
				//Account has been suspended
				return array(8, $email) ;
			}else if(isset($login_res[1] ) && ($login_res[3] == '9' )){
				//Account has been deleted
				return array(9, $email) ;
			}
		}else{
			//LOGIN FAILED
			$this->unRememberMe() ;
			return array(0, $email) ;
		}
	}
	private function rememberMe($user_id){
			$expire = time()+60*60*24*20;
			setcookie("ivytracktask_usr", "$user_id", $expire,'/');
			return 1 ;
	}
	private function unRememberMe(){
		if(isset($_COOKIE['ivypeer_user'])){
			$expire=time()+60*60*(-24)*20;
			setcookie("ivytracktask_usr", 0, $expire,'/');
		}
		return 1 ;
	}
	public function getUserPrivilege($user_id){
		$query = "SELECT privilege FROM app_users WHERE user_id = '$user_id' " ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			$privilege 	= $result_obj->privilege ;
			return $privilege ;
		}
		return 0 ;
	}
	public function getCurrentUserPrivilege(){
		$user_id = $this->getUserId() ;
		$query = "SELECT privilege FROM app_users WHERE user_id = '$user_id' " ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			$privilege 	= $result_obj->privilege ;
			return $privilege ;
		}
		return 0 ;
	}
	public function getUserInfo($user_id){
		$query = "SELECT * FROM app_user_details WHERE user_id = '$user_id' " ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			//user_id	fname	lname	oname	gender	dateofbirth	profile_image_id	cover_image_id	status	time
			$result_obj = $query->row() ;
			$info_obj->user_id 			= $result_obj->user_id ;
			$info_obj->fname 			= $result_obj->fname ;
			$info_obj->lname 			= $result_obj->lname ;
			$info_obj->oname 			= $result_obj->oname ;
			$info_obj->gender 			= $result_obj->gender ;
			$info_obj->date_of_birth 	= $result_obj->dateofbirth ;
			$info_obj->profile_image_id = $result_obj->profile_image_id ;
			$info_obj->cover_image_id 	= $result_obj->cover_image_id ;
			$info_obj->status 			= $result_obj->status ;
			$info_obj->time_added 		= $result_obj->time ;
			return $info_obj ;
		}
		return false ;
	}
	public function updateUserPrivilege($user_id, $new_privilege){
		$query = "UPDATE app_users SET privilege = '$new_privilege' WHERE user_id = '$user_id' " ;
		$query = $this->db->query($query) ;
		return true ;
	}
	
	public function showForgotPasswordForm($fp_error){
		$o = "<div id='forgot_password_form' class='default_form_holder'>" ;
		$o .= "<form method='post'>" ;
			$o .= "<div id='fp_form_inner'>" ;
				$o .= "<div id='fp_form_errors' >" ;
					$v_errors = validation_errors() ;
					$o .= $v_errors ;
					if($v_errors == ""){
						if($fp_error != NULL){
							$o .= "<p>$fp_error</p>" ;
						}
					}
				$o .= "</div>" ;
				$o .= "<div id='fp_form_instructions'>" ;
					$o .= "<span>Enter your login email address, we will send a password reset link to it!</span>" ;
				$o .= "</div>" ;
				$o .= "<div class='fp_form_field block_noflow'>" ;
					$o .= "<label class='block_left input_label tab_label'>Email:</label>" ;
					$o .= "<span>" ;
						$o .= "<input type='text' class='fp_email input_txt' name='fp_email' id='fp_email' placeholder='' value='".set_value('fp_email')."' />" ;
					$o .= "</span>" ;
				$o .= "</div>" ;
				$o .= "<div class='fp_form_submit'>" ;
					$o .= "<span>" ;
						$o .= "<input type='submit' class='inp_submit' name='fp_submit' id='fp_submit' value='Send Password Reset Link' />" ;
					$o .= "</span>" ;
				$o .= "</div>" ;
			$o .= "</div>" ;
			$o .= "</form>" ;
		$o .= "</div>" ;
		return $o ;
	}
	public function handleForgotPassword(){
		$email		= strtolower(protect($_POST['fp_email']) ) ;
		$fp_res 	= $this->checkIfEmailExists($email) ;
		$account_type = 1 ;
		
		if($fp_res == 1){
			$user_id = $this->getUserIdByEmail($email) ;
			// Save Reset Password Request in Database
			$save_res = $this->saveForgotPasswordRequest($email, $account_type) ;
			if($save_res[0] == 1){
				// Send Link to Email
				$link_send_res = $this->sendResetPasswordLinkEmail($email, $save_res[1]) ;
				if($link_send_res[0] == 1){
					//Log Successful Forgot Password Event
					$this->logs->logSuccessfulForgotPassword($user_id);
					
					return array(1, $link_send_res[1]) ;
				}else{	
					//Log Failed Forgot Password Event
					$this->logs->logFailedForgotPassword($user_id);
					
					return 0 ;	
				}
			}
		}else{
			// Email Does Not Exist!
			return 0 ;
		}
	}
	private function createRandomHash(){
		return $this->creatAHashId('app_user_reset_password','hash') ;
	}
	private function checkAHashId($hash, $id_tablename, $id_fieldname){
		$query = "SELECT $id_fieldname FROM $id_tablename WHERE $id_fieldname = '$hash' " ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			return 1 ;
		}
		return 0 ;
		}
	private function creatAHashId($id_tablename,$id_fieldname){
		$id = ( mt_rand(10, 999) * 5 ) + mt_rand(1020025005, 1920025005) ; //10 digits max
		$hash = hash('ripemd160', "$id");
		$chkem = $this->checkAHashId($hash, $id_tablename, $id_fieldname) ;
		while($chkem != '0'){ 
			$id = ( mt_rand(2, 1000) * 5 ) + mt_rand(2020025005, 5520025005) ;	 //10 digits max
			$hash = hash('ripemd160', "$id");
			$chkem = $this->checkAHashId($hash, $id_tablename, $id_fieldname) ;
			}
		return $hash ;
	}
	private function saveForgotPasswordRequest($email, $type = 1){
		$hash = $this->createRandomHash() ;
		$status = 1 ;
		$query = "INSERT INTO app_user_reset_password VALUES('$email', '$hash', '$type', '$status', CURRENT_TIMESTAMP) " ;
		$query = $this->db->query($query) ;
		return array(1, $hash) ;
		
	}
	private function sendResetPasswordLinkEmail($email, $hash ){
		$url = $this->rootdir."resetpassword/index/$hash" ;
		$display_url = $this->rootdir."resetpassword/index/$hash" ;
		
		$m = "" ;
		$m .= "Here's your ivytracks.com password reset link:\n" ;
		$m .= "<a href='".$url."'>".$display_url."</a>" ;
		$m .= "Click the link OR Copy and paste the link into your web browser to visit your password reset page!" ;
		
		$to = "$email" ;
		$from_user = "ivytracks_noreply" ;
		$from_email = "noreply@ivytracks.com" ;
		$from = "$from_user <$from_email>" ;
		
		$res = mail_utf8($to, $from_user, $from_email, "IvyTracks Reset Password Link", $m) ;
		if($res === true){
			//Email Send Successful
			return array(1,"Mail to $to was sent successfully") ;
		}else{
			//Email Send Failed
			return array(1,"An error occured while sending a Mail to $to. Mail send Failed!") ;
		}

	}
	public function getResetEmailByHash($hash){
		$query = "SELECT email FROM app_user_reset_password WHERE hash = '$hash' AND status = '1'" ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_obj = $query->row() ;
			$email 	= $result_obj->email ;
			return $email ;
		}
		return 0 ;
	}
	public function showResetPasswordForm($fp_error, $hash_email, $hash){
		$o = "<div id='forgot_password_form' class='default_form_holder'>" ;
		$o .= "<form method='post'>" ;
			$o .= "<div id='fp_form_inner'>" ;
				$o .= "<div id='fp_form_errors' >" ;
					$v_errors = validation_errors() ;
					$o .= $v_errors ;
					if($v_errors == ""){
						if($fp_error != NULL){
							$o .= "<p>$fp_error</p>" ;
						}
					}
				$o .= "</div>" ;
				$o .= "<div id='fp_form_instructions'>" ;
					$o .= "<span>Hi ".$hash_email."! Enter the new password for your ivypeer.com account!</span>" ;
				$o .= "</div>" ;
				$o .= "<input type='hidden' name='rp_hash' value='$hash' />" ;
				$o .= "<input type='hidden' name='rp_email' value='$hash_email' />" ;
				$o .= "<div class='block_noflow'>" ;
					$o .= "<label class='block_left input_label tab_label_long'>Password:</label>" ;
					$o .= "<span>" ;
						$o .= "<input type='password' class='rp_pass input_txt' name='rp_pass' id='rp_pass' placeholder='' value='".set_value('rp_pass')."' />" ;
					$o .= "</span>" ;
				$o .= "</div>" ;
				$o .= "<div class='block_noflow'>" ;
					$o .= "<label class='block_left input_label tab_label_long'>Re-type Password:</label>" ;
					$o .= "<span>" ;
						$o .= "<input type='password' class='rp_vpass input_txt' name='rp_vpass' id='rp_vpass' placeholder='' value='' />" ;
					$o .= "</span>" ;
				$o .= "</div>" ;
				$o .= "<div class='fp_form_submit'>" ;
					$o .= "<span>" ;
						$o .= "<input type='submit' class='inp_submit' name='rp_submit' id='fp_submit' value='Reset Password' />" ;
					$o .= "</span>" ;
				$o .= "</div>" ;
			$o .= "</div>" ;
			$o .= "</form>" ;
		$o .= "</div>" ;
		return $o ;
	}
	public function handleResetPassword(){
		$hash 		= protect($_POST['rp_hash']) ;
		$email 		= strtolower(protect($_POST['rp_email']) ) ;
		$password 	= protect($_POST['rp_pass']) ;
		$vpassword 	= protect($_POST['rp_vpass']) ;
		
		if($email != ""){
			$user_id = $this->getUserIdByEmail($email) ;
			if($password == $vpassword){
				$password = passEncrypt($password) ;
				
				$rp_res = $this->resetUserPassword($email, $password) ;
				if($rp_res == 1){
					$rp_final_res = $this->clearUserResetPasswordHash($email, $hash) ;
					//Log Successful Reset Password Event
					$this->logs->logSuccessfulResetPassword($user_id);
					
					return array(1, "Password Successfully reset!") ;
				}else{ 
					//Log Failed Reset Password Event
					$this->logs->logFailedResetPassword($user_id);
					
					return array(0, "Reset password Failed!") ; 
				}
			}
		}
	}
	private function resetUserPassword($email, $password){
		$query = "UPDATE app_users SET password = '$password' WHERE email = '$email' " ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	private function clearUserResetPasswordHash($email, $hash){
		$query = "UPDATE reset_password SET hash = '', status = '9' WHERE (email = '$email') AND (hash = '$hash') " ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	
	//Suspend User Account
	public function suspendUserAccount($user_id, $suspended_status = 8){
		$query = "UPDATE app_users SET status = '$suspended_status' WHERE user_id = '$user_id' " ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	
	//Delete User Account
	public function deleteUserAccount($user_id, $deleted_status = 9){
		$query = "UPDATE app_users SET status = '$deleted_status' WHERE user_id = '$user_id' " ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	
	//Activate User Account
	public function activateUserAccount($user_id, $active_status = 1){
		$query = "UPDATE app_users SET status = '$active_status' WHERE user_id = '$user_id' " ;
		$query = $this->db->query($query) ;
		return 1 ;
	}
	
	
	//Optional User Accounts
	//Get
	public function createOptionalAccountForUser($user_id, $account_type_id){
		$status = 1 ;
		$account_id = createAnId('app_user_account_users','account_id') ;
		$query = "INSERT INTO app_user_account_users(user_id, account_id, account_type_id, status, time) 
				VALUES ('$user_id','$account_id','$account_type_id', '$status', CURRENT_TIMESTAMP)" ;
		$result = $this->db->query($query) ;
		return true ;
	}
	public function getAllOptionalUserAccounts($status = "(status = 1)"){
		
	}
	public function getOptionalUserAccounts($user_id, $status = "(status = 1)"){
		$query = "SELECT * FROM app_user_account_users WHERE user_id = '$user_id' AND ".$status ;
		$query = $this->db->query($query) ;
		if ($query->num_rows() > 0)
		{
			$result_array = $query->result_array() ;
			return $result_array ;
		}
		return false ;
	}
}
?>