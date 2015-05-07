<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');	

class nebula_cryptic{
    
	//Constructor
	public function __construct(){	}
	
	private $word ;
	
	public function encrypt($word){
		$this->word = $word ;
                $partA ; $partB ;
                $len = strlen($this->word);
                $partA = substr($this->word,0,($len/2));
                $partB = substr($this->word,($len/2));
                $subst1 = "";
                $wordarr = "";
                $wordenc = "";
                for($count = 1;$count<=2; $count++){ //for loop to loop str1 and 2
                    if($count == 1){ //begin if for part A and part B
                        $subst1= $partA;
                    }
                    else if($count == 2)
                    { //begin if for part A and part B
                        $subst1= $partB;
                     }
                        $wordarr = "";
        $broken = 0;
        for($i= strlen($subst1)-1 ; $i>=0 ; $i--)
        { // for loop to break and convert string char by char
            $broken= $subst1[$i];
       //     Concatenate
            $wordarr = $wordarr.$broken;
            
             }// END for loop to break and convert string char by char
        $wordenc .= $wordarr;
                    }
           return $wordenc;
           }
   public function decrypt($word){
	   $this->word = $word ;
                $partA ; $partB ;
                $len = strlen($this->word) ;
                $partA = substr($this->word,0,($len/2));
                $partB = substr($this->word,($len/2));
                $subst1 = "";
                $wordarr = "";
                $wordenc = "";
                for( $count = 1; $count <= 2 ; $count++ ){ //for loop to loop str1 and 2
                    if($count == 1){ //begin if for part A and part B
                        $subst1 = $partA;
                    }
                    else if($count == 2){ 
					//begin if for part A and part B
                        $subst1 = $partB;
                    }
        $wordarr = "";
        $broken = 0;
        for($i = strlen($subst1)-1 ; $i >= 0 ; $i--)
        {
            $broken = $subst1[$i];
			   //   Concatenate
			$wordarr = $wordarr.$broken ;
             }// END for loop to break and convert string char by char
        $wordenc .= $wordarr;
                    }
           return $wordenc;
           }
}

?>