<?php
class Image_json extends CI_Controller {
	
	var $in_data ;
	
	public function index($params = "")
	{
		$this->load->library('session');
		$this->load->library('form_validation');
		
		$this->load->database();
		
		$this->load->model('user_sessions') ;
		$this->load->model('admin_news') ;
		$this->load->model('admin_images') ;
		
		$this->load->helper(array('form', 'url'));
		
		$this->load->helper('main');
		$this->load->helper('time_functions');
		$this->load->helper('encryption');
				
		$data = array(
					  'home_params' => $params
					  ) ;
		
		$this->in_data = $data ;
		
		$rootdir = $this->admin_images->rootdir ;
		
		$my_images = $this->admin_images->getAllImages() ;
			if($my_images !== false){
				$images_array = array() ;
				for($i = 0; $i < count($my_images) ; $i++){
					$image_filename 		= $my_images[$i]['image_filename'] ;
					$image_title 		= $my_images[$i]['title'] ;
					
						array_push($images_array, array(
															'image' 	=> $rootdir."images/uploads/".$image_filename,
															'thumb' 	=> $rootdir."images/uploads/".$image_filename,
															'folder' 	=> "My Images"				
			
			/*											'title' => $image_title, 
                        								'value' => $rootdir."images/uploads/".$image_filename		*/
														) 
						);
					
				}//end for
				
				echo json_encode($images_array) ;
				
				/*echo json_encode( 
						array(
							'image' 	=> "/image1_200x150.jpg",
							'thumb' 	=> "/image1_thumb.jpg",
							'folder' 	=> "Small"				
						)
					) ;	*/
			}//end if
	}
	
	
}
?>