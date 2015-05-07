<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

/* WILDCARDS */

$route['shorten_xhr'] = "shorten_xhr/index/$1";
$route['shorten_xhr/(:any)'] = "shorten_xhr/index/$1";

$route['a'] 			= "home/index/$1";
$route['a/(:any)'] 		= "home/index/$1";

$route['admin'] 		= "admin";
$route['admin/(:any)'] 	= "admin/$1";

$route['home/(:any)'] = "home/index/$1";

$route['(:any)'] 	= "redirect/index/$1";





$route['about'] = "about/index/$1";
$route['about/(:any)'] = "about/index/$1";


$route['default_controller'] = 'home';
$route['404_override'] = 'error';



/* End of file routes.php */
/* Location: ./application/config/routes.php */