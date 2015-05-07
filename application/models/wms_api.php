<?php
/*
	System API version 1.0
*/
class Wms_api extends CI_Model{
	
	//Constructor
	function __construct(){
	// Call the Model constructor
        parent::__construct();
		$this->rootdir = $this->config->item('base_url') ;
		
		$this->load->model('user_sessions') ;
		$this->load->model('wms_store') ;
	}
	
	private $user_id ;
	public $rootdir ;
	
	/** GET WIDGET DIRECTORY FULL PATH 
			Returns a String
	**/
	public function getWidgetDirectoryPath($widget_identifier){
		return $this->rootdir."widgets/system/".$widget_identifier."/" ;
	}
	
	/** CHECK IF USER HAS SIGNED IN 
			Returns boolean true if User is logged in
			Returns boolean false if user is NOT logged in
	**/
	public function userLoggedIn(){
		return $this->user_sessions->userLoggedIn() ;
	}
	
	
	
	/** GET USER ID. Better used only when a User has been confirmed to be logged in 
			Returns an integer if User is logged in
			Returns boolean false if user id is NOT available. i.e. The user is not logged in
	**/
	public function getUserId(){
		return $this->user_sessions->getUserId() ;
	}
	
	
	
	/** GET USER EMAIL.
			Returns the email of the user if Email was stored during registration
			Returns boolean false if the email is NOT available.
	**/
	public function getUserEmail($the_user_id){
		return $this->user_sessions->getUserEmail($the_user_id) ;
	}
	
	
	
	/** GET USER USERNAME.
			Returns the Username String if Username was stored during registration
			Returns boolean false if username is not available.
	**/
	public function getUserUsername($the_user_id){
		return $this->user_sessions->getUserUsername($the_user_id) ;
	}
	
	
	
	/** GET USER PRIVILEGE. 
			Returns an integer ranging from 1 to 10. 
			1 - Standard User
			10 - SuperAdmin
			Returns boolean false if user privilege is not available.
	**/
	public function getUserPrivilege($the_user_id){
		$privilege = $this->user_sessions->getUserPrivilege($the_user_id) ;
		if($privilege != 0){ return $privilege; }else{return false ; }
	}
	
	
	
	/** GET USER STATUS. 
			Returns an integer value.
			Default Status Settings
			1 - Status OK
			2 - Registration Not Yet Confirmed
			8 - Account Suspended
			9 - Account Deleted
			Returns boolean false if user privilege is not available.
	**/
	public function getUserStatus($the_user_id){
		$status = $this->user_sessions->getUserStatus($the_user_id) ;
		if($status[0] != 0){ return $status[1]; }else{return false ; }
	}
	
	
	
	/** GET USER INFO. 
			Returns an object containing the following properties:
				user_id, fname, lname, oname, gender, date_of_birth, profile_image_id, cover_image_id, status, time_added
			Returns boolean false if user info is not available.
	**/
	public function getUserInfo($the_user_id){
		return $this->user_sessions->getUserInfo($the_user_id) ;
	}
	
	
	
	/** LOG USER IN Via Email and Password. 
			Returns array(1, user_id, privilege, status) on success
			Returns boolean false on failure.
	**/
	public function logUserInViaEmailAndPassword($email, $password){
		$log_in_result = $this->user_sessions->logUserIn($email, $password) ;
		if($log_in_result[0] == 0){
			return false ;
		}else{ return $log_in_result ; }
	}
	
	
	
	
	
	/** CHECK IF USER EMAIL IS A REGISTERED EMAIL. 
			Returns boolean true on success
			Returns boolean false on failure.
	**/
	public function checkIfEmailIsRegistered($email){
		return $this->user_sessions->checkIfEmailExists($email) ;
	}
	
	
	
	/** CHECK IF USER IS A REGISTERED USER. 
			Returns array(true, user_id, status ) ; on success
			Returns boolean false on failure.
	**/
	public function checkIfUserIsRegistered($user_id){
		return $this->user_sessions->checkIfUserExistsBool($user_id) ;
	}
	
	
	/******** 	GENERAL FUNCTIONS	*********/
	
	/** PROTECT INPUT STRING */
	/** 	Filters and trims String using htmlentities() and trim()
			Returns the encoded string
	**/
	public function protect($string){
		return protect($string) ;
	}
	
	
	/** PROTECT INPUT STRING AND KEEP LINES AND SPACES */
	/** 	Filters and trims String using htmlentities() and trim()
			Returns the encoded string with \n converted to <br/> and "  " converted to " &nbsp;"
	**/
	public function protectPlus($string){
		return protectExactText($string) ;
	}
	
	
	
	/** CREATE AN ID FOR SPECIFIC DATABASE TABLE */
	/** 	Creates an Integer Id for Any Database Table and respective ID field. Table(Table Name, Field Name). 
			Returns an Integer value on success
			It will always succeed or die trying! :)
	**/
	public function createAnId($tablename, $id_fieldname){
		return creatAnId($tablename,$id_fieldname) ;
	}
	
//	Protect input String
//	Protect input String Keep Lines
//	Create An Integer Id for Database Table(Table Name, Field Name)
//	First Letter To Capitals
//	Get First X letters of a String
//	Convert An Associative Array To numeric Array
	
	/** CAPITALIZE THE FIRST LETTER OF A STRING */
	/** 	Returns the String with the First Letter CAPITALIZED	**/
	public function capFirstLetter($the_string){
		return firstLetterToCaps($the_string) ;
	}
	
	
	/** GET THE FIRST X LETTERS OF A STRING */
	/** 	Returns the first X letters of the input String.	
			If X exceeds the string length, the whole string is returned.
			An optional third parameter 'extension' is allowed. 
			It can be used to include String breakers like '...read more'
	**/
	public function getFirstXLetters($the_string ,$x , $extension = ""){
		return getFirstXLettersNoDefaultX($the_string ,$x ,$extension) ;
	}
	
	
	/** CONVERT AN ASSOCIATIVE ARRAY TO A NUMERIC ARRAY **/
	/* Don't forget to debug validity of the resulting numeric array with print_r()! :)*/
	public function convertAssocToNumeric($associative_array){
		return array_assoc_to_numeric($associative_array) ;
	}
	
	
	
}

?>