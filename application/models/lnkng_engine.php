<?php

class Lnkng_engine extends CI_Model{
	//Constructor
	function __construct(){
	// Call the Model constructor
        parent::__construct();
		$this->rootdir = $this->config->item('base_url') ;
	}
	
	var $chars = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","0","1","2","3","4","5","6","7","8","9");
   
   function hash($num)
   {
       $digit = array();
       while(floor($num) > 0){
           $rem = fmod($num, 62);
           array_push($digit, floor($rem));
           $num = $num / 62;
       }
       return array_reverse($digit);
   }
   
   function hash2($code)
   {
       $num = array(); 
       for($i = 0; $i < strlen($code); $i++) {
           array_push($num, array_search($code[$i], $this->chars));
       }
       return $num;
   }
   
   function encode($num)
   {
        $out = "";
        $digit = $this->hash($num);
        foreach ($digit as $d) {
            $out .= $this->chars[$d];
        }
        return $out;
   } 
      
   function decode($code)
   {
       $b62 = $this->hash2($code);
       $num = 0; $l = count($b62)-1;
       for($i = $l; $i > -1; $i--) {
           $num = $num + ($b62[$l-$i] * pow(62, $i));
       }
       return $num;
   }
   
} 

?>
