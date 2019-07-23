<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['items/view'] = 'items';
$route['items/(:num)'] = 'items/view/$1';
$route['items/(:num)/view'] = 'items/view/$1';
$route['items/(:num)/edit'] = 'items/edit/$1';
$route['items/(:num)/(:any)'] = 'items/view/$1/$2';

$route['categories/view'] = 'categories';
$route['categories/(:num)'] = 'categories/view/$1';
$route['categories/(:num)/view'] = 'categories/view/$1';
$route['categories/(:num)/edit'] = 'categories/edit/$1';
$route['categories/(:num)/(:any)'] = 'categories/view/$1/$2';

$route['labels/view'] = 'labels';
$route['labels/(:num)'] = 'labels/view/$1';
$route['labels/(:num)/view'] = 'labels/view/$1';
$route['labels/(:num)/edit'] = 'labels/edit/$1';
$route['labels/(:num)/(:any)'] = 'labels/view/$1/$2';

$route['boxes/view'] = 'boxes';
$route['boxes/(:num)'] = 'boxes/view/$1';
$route['boxes/(:num)/view'] = 'boxes/view/$1';
$route['boxes/(:num)/edit'] = 'boxes/edit/$1';
$route['boxes/(:num)/(:any)'] = 'boxes/view/$1/$2';

$route['storages/view'] = 'storages';
$route['storages/(:num)'] = 'storages/view/$1';
$route['storages/(:num)/view'] = 'storages/view/$1';
$route['storages/(:num)/edit'] = 'storages/edit/$1';
$route['storages/(:num)/(:any)'] = 'storages/view/$1/$2';

$route['owners/view'] = 'owners';
$route['owners/(:num)'] = 'owners/view/$1';
$route['owners/(:num)/view'] = 'owners/view/$1';
$route['owners/(:num)/edit'] = 'owners/edit/$1';
$route['owners/(:num)/(:any)'] = 'owners/view/$1/$2';

$route['sectors/(:num)'] = 'sectors/view/$1';
$route['sectors/(:num)/view'] = 'sectors/view/$1';
$route['sectors/(:num)/edit'] = 'sectors/edit/$1';
$route['sectors/(:num)/(:any)'] = 'sectors/view/$1/$2';

$route['sessions/view'] = 'sessions';
$route['sessions/(:num)'] = 'sessions/view/$1';
$route['sessions/(:num)/view'] = 'sessions/view/$1';
$route['sessions/(:num)/edit'] = 'sessions/edit/$1';
$route['sessions/(:num)/(:any)'] = 'sessions/view/$1/$2';

$route['default_controller'] = 'pages';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
