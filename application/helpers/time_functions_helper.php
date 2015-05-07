<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//Time functions
	//integer == seconds
	function days_to_seconds($days){
		return ($days * 24 * 60 * 60) ;
		}
	function hours_to_seconds($hours){
		return ($hours * 60 * 60) ;
		}
	function integer_to_timestamp($integer_time){
		return date('Y-m-d H:i:s', $integer_time) ;
		}
	function timestamp_to_integer($timestamp){
		return strtotime($timestamp) ;
		}
	function microtime_float(){
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec);
	}	
	function getdateinfo($timestamp){
		//Function to return date and time in the format below
		$time = date('M, d Y',strtotime($timestamp) );	//Y=year M='month:Jan' n=month	D='day:Tue'	d='day:2' G=hrs:04 i=min:23	s=sec:18
		return $time;
		}
	function getdateyearinfo($timestamp){
		//Function to return the year from timestamp
		$time = date('Y',strtotime($timestamp) );	//Y=year
		return $time;
		}
	function getfulldateinfo($timestamp){
		//Function to return date and time in the format below
		$time = date('D, jS M, Y. g:ia',strtotime($timestamp) );//D=Fri j=6 S='th/nd' F=Oct	Y=2011	 g(12hrsclock)=6 i(min)=23 a=pm/am
		return $time;
		}
	function getfulldateinfo_Type1($timestamp){
		//Function to return date and time in the format below
		$time = date('D, M j, Y. g:ia',strtotime($timestamp) );//D=Fri j=6 S='th/nd' F=Oct	Y=2011	 g(12hrsclock)=6 i(min)=23 a=pm/am
		return $time;
		}
	function getdateinfo_newstype1($timestamp){
		//Function to return date and time in the format below
		$time = date('j/m g:i a',strtotime($timestamp) );//D=Fri j=6 S='th/nd' F=Oct	Y=2011	 g(12hrsclock)=6 i(min)=23 a=pm/am
		return $time;
		}
	function getdateinfo_newstype2($timestamp){
		//Function to return date and time in the format below
		$time = date('F j, Y',strtotime($timestamp) );//D=Fri j=6 S='th/nd' F=Oct	Y=2011	 g(12hrsclock)=6 i(min)=23 a=pm/am
		return $time;
		}
	function getcurrtimeinfo(){
		//Function to return current date and time in integer format
		$time = time() ;
		return $time;
		}
	function timegone($seconds){
		$seconds2 = $seconds ; //$seconds2 = $seconds - 3599 ; 
		//The modification commented above was due to an error of 3600 seconds in my system's time
		if($seconds2 > (3600*24*7*4*12)){
			$sum = (integer)($seconds2 / (3600*24*7*4*12)) ;
			if($sum == 1){$ans = "a year ago";					}else{
			$ans = "$sum years ago" ;	}//end inner else
		}else if($seconds2 > (3600*24*7*4)){
			$sum = (integer)($seconds2 / (3600*24*7*4)) ;
			if($sum == 1){$ans = "last month";					}else{
			$ans = "$sum months ago" ;	}//end inner else
		}else if($seconds2 > (3600*24*7)){
			$sum = (integer)($seconds2 / (3600*24*7)) ;
			if($sum == 1){$ans = "last week";					}else{
			$ans = "$sum weeks ago" ;	}//end inner else
		}else if($seconds2 > (3600*24)){
			$sum = (integer)($seconds2 / (3600*24)) ;
			if($sum == 1){$ans = "yesterday";					}else{
			$ans = "$sum days ago" ;	}//end inner else
		}else if($seconds2 > 3600){
			$sum = (integer)($seconds2 / 3600) ;
			if($sum == 1){$ans = "an hour ago";					}else{
			$ans = "$sum hours ago" ;	}//end inner else
		}else if($seconds2 > 300){
			$sum = (integer)($seconds2 / 60) ;
			if($sum == 1){$ans = "a minute ago";				}else{
			$ans = "$sum minutes ago" ;	}//end inner else
		}else{
			if($seconds2 == 1){$ans = "$seconds2 second ago";	}else{
			$ans = "just now" ; }//end inner else
			}
		return $ans ;
		}
		function tinyTimegone($seconds){
		$seconds2 = $seconds ; //$seconds2 = $seconds - 3599 ; 
		//The modification commented above was due to an error of 3600 seconds in my system's time
		if($seconds2 > (3600*24*7*4*12)){
			$sum = (integer)($seconds2 / (3600*24*7*4*12)) ;
			if($sum == 1){$ans = "1Yr";					}else{
			$ans = "$sum"."Yrs" ;	}//end inner else
		}else if($seconds2 > (3600*24*7*4)){
			$sum = (integer)($seconds2 / (3600*24*7*4)) ;
			if($sum == 1){$ans = "1Mn";					}else{
			$ans = "$sum"."Mns" ;	}//end inner else
		}else if($seconds2 > (3600*24*7)){
			$sum = (integer)($seconds2 / (3600*24*7)) ;
			if($sum == 1){$ans = "1Wk";					}else{
			$ans = "$sum"."Wks" ;	}//end inner else
		}else if($seconds2 > (3600*24)){
			$sum = (integer)($seconds2 / (3600*24)) ;
			if($sum == 1){$ans = "1D";					}else{
			$ans = "$sum"."Ds" ;	}//end inner else
		}else if($seconds2 > 3600){
			$sum = (integer)($seconds2 / 3600) ;
			if($sum == 1){$ans = "1Hr";					}else{
			$ans = "$sum"."Hrs" ;	}//end inner else
		}else if($seconds2 > 300){
			$sum = (integer)($seconds2 / 60) ;
			if($sum == 1){$ans = "1Min";				}else{
			$ans = "$sum"."Mins" ;	}//end inner else
		}else{
			if($seconds2 == 1){$ans = "$seconds2"."Sec";	}else{
			$ans = "just now" ; }//end inner else
			}
		return $ans ;
		}
		function timeElapsed($timestamp){
			return timegone( getcurrtimeinfo() - timestamp_to_integer($timestamp) ) ;
		}
		function tinyTimeElapsed($timestamp){
			return tinyTimegone( getcurrtimeinfo() - timestamp_to_integer($timestamp) ) ;
		}
		function daySelect($name = "",$id = "",$class = ""){
			$d = "<select name='$name' id='$id' class='$class'>" ;
				$d .= "<option value='0'>Day:</option>" ;
				$d .= "<option value='1'>1</option>" ; $d .= "<option value='2'>2</option>" ; $d .= "<option value='3'>3</option>" ;
				$d .= "<option value='4'>4</option>" ; $d .= "<option value='5'>5</option>" ; $d .= "<option value='6'>6</option>" ;
				$d .= "<option value='7'>7</option>" ; $d .= "<option value='8'>8</option>" ; $d .= "<option value='9'>9</option>" ;
				$d .= "<option value='10'>10</option>" ; $d .= "<option value='11'>11</option>" ; $d .= "<option value='12'>12</option>" ;
				$d .= "<option value='13'>13</option>" ; $d .= "<option value='14'>14</option>" ; $d .= "<option value='15'>15</option>" ;
				$d .= "<option value='16'>16</option>" ; $d .= "<option value='17'>17</option>" ; $d .= "<option value='18'>18</option>" ;
				$d .= "<option value='19'>19</option>" ; $d .= "<option value='20'>20</option>" ; $d .= "<option value='21'>21</option>" ;
				$d .= "<option value='22'>22</option>" ; $d .= "<option value='23'>23</option>" ; $d .= "<option value='24'>24</option>" ;
				$d .= "<option value='25'>25</option>" ; $d .= "<option value='26'>26</option>" ; $d .= "<option value='27'>27</option>" ;
				$d .= "<option value='28'>28</option>" ; $d .= "<option value='29'>29</option>" ; $d .= "<option value='30'>30</option>" ;
				$d .= "<option value='31'>31</option>" ;
			$d .= "</select>" ;
			return $d ;
		}
		function yearSelect($name = "",$id = "",$class = ""){
			$d = "<select name='$name' id='$id' class='$class'>" ;
			$d .= "<option value='0'>Year:</option>" ; $d .= "<option value='2012'>2012</option>" ; $d .= "<option value='2011'>2011</option>" ;
			$d .= "<option value='2010'>2010</option>" ; $d .= "<option value='2009'>2009</option>" ; $d .= "<option value='2008'>2008</option>" ;
			$d .= "<option value='2007'>2007</option>" ; $d .= "<option value='2006'>2006</option>" ; $d .= "<option value='2005'>2005</option>" ;
			$d .= "<option value='2004'>2004</option>" ; $d .= "<option value='2003'>2003</option>" ; $d .= "<option value='2002'>2002</option>" ;
			$d .= "<option value='2001'>2001</option>" ; $d .= "<option value='2000'>2000</option>" ; $d .= "<option value='1999'>1999</option>" ;
			$d .= "<option value='1998'>1998</option>" ; $d .= "<option value='1997'>1997</option>" ; $d .= "<option value='1996'>1996</option>" ;
			$d .= "<option value='1995'>1995</option>" ; $d .= "<option value='1994'>1994</option>" ; $d .= "<option value='1993'>1993</option>" ;
			$d .= "<option value='1992'>1992</option>" ; $d .= "<option value='1991'>1991</option>" ; $d .= "<option value='1990'>1990</option>" ;
			$d .= "<option value='1989'>1989</option>" ; $d .= "<option value='1988'>1988</option>" ; $d .= "<option value='1987'>1987</option>" ;
			$d .= "<option value='1986'>1986</option>" ; $d .= "<option value='1985'>1985</option>" ; $d .= "<option value='1984'>1984</option>" ;
			$d .= "<option value='1983'>1983</option>" ; $d .= "<option value='1982'>1982</option>" ; $d .= "<option value='1981'>1981</option>" ;
			$d .= "<option value='1980'>1980</option>" ; $d .= "<option value='1979'>1979</option>" ; $d .= "<option value='1978'>1978</option>" ;
			$d .= "<option value='1977'>1977</option>" ; $d .= "<option value='1976'>1976</option>" ; $d .= "<option value='1975'>1975</option>" ;
			$d .= "<option value='1974'>1974</option>" ; $d .= "<option value='1973'>1973</option>" ; $d .= "<option value='1972'>1972</option>" ;
			$d .= "<option value='1971'>1971</option>" ; $d .= "<option value='1970'>1970</option>" ; $d .= "<option value='1969'>1969</option>" ;
			$d .= "<option value='1968'>1968</option>" ; $d .= "<option value='1967'>1967</option>" ; $d .= "<option value='1966'>1966</option>" ;
			$d .= "<option value='1965'>1965</option>" ; $d .= "<option value='1964'>1964</option>" ; $d .= "<option value='1963'>1963</option>" ;
			$d .= "<option value='1962'>1962</option>" ; $d .= "<option value='1961'>1961</option>" ; $d .= "<option value='1960'>1960</option>" ;
			$d .= "<option value='1959'>1959</option>" ; $d .= "<option value='1958'>1958</option>" ; $d .= "<option value='1957'>1957</option>" ;
			$d .= "<option value='1956'>1956</option>" ; $d .= "<option value='1955'>1955</option>" ; $d .= "<option value='1954'>1954</option>" ;
			$d .= "<option value='1953'>1953</option>" ; $d .= "<option value='1952'>1952</option>" ; $d .= "<option value='1951'>1951</option>" ;
			$d .= "<option value='1950'>1950</option>" ; $d .= "<option value='1949'>1949</option>" ; $d .= "<option value='1948'>1948</option>" ;
			$d .= "<option value='1947'>1947</option>" ; $d .= "<option value='1946'>1946</option>" ; $d .= "<option value='1945'>1945</option>" ;
			$d .= "<option value='1944'>1944</option>" ; $d .= "<option value='1943'>1943</option>" ; $d .= "<option value='1942'>1942</option>" ;
			$d .= "<option value='1941'>1941</option>" ; $d .= "<option value='1940'>1940</option>" ; $d .= "<option value='1939'>1939</option>" ;
			$d .= "<option value='1938'>1938</option>" ; $d .= "<option value='1937'>1937</option>" ; $d .= "<option value='1936'>1936</option>" ;
			$d .= "<option value='1935'>1935</option>" ; $d .= "<option value='1934'>1934</option>" ; $d .= "<option value='1933'>1933</option>" ;
			$d .= "<option value='1932'>1932</option>" ; $d .= "<option value='1931'>1931</option>" ; $d .= "<option value='1930'>1930</option>" ;
			$d .= "<option value='1929'>1929</option>" ; $d .= "<option value='1928'>1928</option>" ;
			$d .= "<option value='1927'>1927</option>" ; $d .= "<option value='1926'>1926</option>" ;
			$d .= "<option value='1925'>1925</option>" ; $d .= "<option value='1924'>1924</option>" ;
			$d .= "<option value='1923'>1923</option>" ; $d .= "<option value='1922'>1922</option>" ;
			$d .= "<option value='1921'>1921</option>" ; $d .= "<option value='1920'>1920</option>" ;
			$d .= "<option value='1919'>1919</option>" ; $d .= "<option value='1918'>1918</option>" ;
			$d .= "<option value='1917'>1917</option>" ; $d .= "<option value='1916'>1916</option>" ;
			$d .= "<option value='1915'>1915</option>" ; $d .= "<option value='1914'>1914</option>" ;
			$d .= "<option value='1913'>1913</option>" ; $d .= "<option value='1912'>1912</option>" ;
			$d .= "<option value='1911'>1911</option>" ; $d .= "<option value='1910'>1910</option>" ;
			$d .= "<option value='1909'>1909</option>" ; $d .= "<option value='1908'>1908</option>" ;
			$d .= "<option value='1907'>1907</option>" ; $d .= "<option value='1906'>1906</option>" ;
			$d .= "<option value='1905'>1905</option>" ;
		$d .= "</select>" ;
		return $d ;
		}
		function monthSelect($name = "",$id = "",$class = "",$ex = ""){
			$d = "<select name='$name' id='$id' class='$class'>" ;
			if($ex == ""){
				$d .= "<option value='0'>Month:</option>" ;
			}else{
				
			}
				$d .= "<option value='January'>Jan</option>" ;
				$d .= "<option value='February'>Feb</option>" ;
				$d .= "<option value='March'>Mar</option>" ;
				$d .= "<option value='April'>Apr</option>" ;
				$d .= "<option value='May'>May</option>" ;
				$d .= "<option value='June'>Jun</option>" ;
				$d .= "<option value='July'>Jul</option>" ;
				$d .= "<option value='August'>Aug</option>" ;
				$d .= "<option value='September'>Sep</option>" ;
				$d .= "<option value='October'>Oct</option>" ;
				$d .= "<option value='November'>Nov</option>" ;
				$d .= "<option value='December'>Dec</option>" ;
			$d .= "</select>" ;
			return $d ;
		}
		function betterYearSelect($name = "",$id = "",$class = "", $total_years = 80, $start_year = 2030, $placeholder = "- Year -"){
			$d = "<select name='$name' id='$id' class='$class'>" ;
				$d .= "<option value='0'>$placeholder</option>" ;
				for($i = 0; $i < $total_years; $i++ ){
					$the_year = $start_year - $i ;
					$d .= "<option value='".$the_year."'>".$the_year."</option>" ;
				}
			$d .= "</select>" ;
			return $d ;
		}
	//End Time functions
/* End of file time_functions_helper.php */
/* Location: ./application/helpers/time_functions_helper.php */