<style>
	.special-login-box{
		border:none;
		overflow:auto ;
		padding:4px;
		margin:0px;
	}
	
	.special-login-box .login-welcome-info{
		display:block;
		float:left;
		margin:0px;
		margin-top:6px;
		padding:0px;
	}
	
	.special-login-box .special-logout-box{
		display:block;
		float:left;
		margin:0px;
		margin-left:10px;
		padding:0px;
	}
	.special-login-box .special-logout-box form{
		margin:0px;
		padding:0px;
	}
</style>

<?php
	$login_form = "" ;
?>


<?php
	$this->login = get_widget_ci_class('../widgets/system/sys_login_special/lib/login') ;
?>

<?php
	//Log User Out if logout button is clicked
	if($this->login->validateLogoutForm() === true ){
		// User has been Logged Out
	}
	
	//Log user In if (1)User is logged out and (2)Login button is clicked
	if($this->user_sessions->userLoggedIn() === false){
		
			if($this->login->validateLoginForm() === true ){
				
				$login_result = $this->login->executeLogin() ;
				if($login_result == 1){
					echo "<span class='no_border_bottom' id='form-success' ><p>Login Successful!</p></span>" ;
				}else if($login_result == 2){
					echo "<span class='form-box no_border_bottom' id='form-error' ><p>Login Failed: This User Account has recently been deleted</p></span>" ;
					echo $this->login->getLoginForm() ;
				}else if($login_result == 0){
					echo "<span class='form-box no_border_bottom' id='form-error' ><p>Login Failed: Email or Password Combination is Incorrect!</p></span>" ;
					echo $this->login->getLoginForm() ;
				}
			}else{
				echo $this->login->getLoginForm() ;
			}
	}
	
	//If User is Logged in (1)Show Welcome Info, (2)Show Logout Form
	if($this->user_sessions->userLoggedIn() === true){
		$o = "" ;
		$o .= "<div class='form-box special-login-box'>" ;
				
		//Show Welcome Info
		$o .= $this->login->getWelcomeInfo() ;
				
		//Show logout Form
		$o .= $this->login->getLogoutForm() ;
		
		$o .= "</div>" ;
		echo $o ;
	}
		

?>