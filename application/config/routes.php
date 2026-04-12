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
|	http://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route["access/css"]["GET"] = "misc/check_session_status";
$route['access/login']['POST'] = "home/login";
$route['dbmanagement'] = "misc/dbmanagement";
$route['bulkoperations/importItems']['POST'] = "bulkoperations/importItems";
$route['bulkoperations/exportItems']['GET'] = "bulkoperations/exportItems";
$route['bulkoperations/bulkUpdatePrices']['POST'] = "bulkoperations/bulkUpdatePrices";
$route['bulkoperations/bulkStockAdjustment']['POST'] = "bulkoperations/bulkStockAdjustment";
$route['bulkoperations/bulkDelete']['POST'] = "bulkoperations/bulkDelete";
$route['search/getSuggestions']['GET'] = "search/getSuggestions";
$route['activitylog/loadLogs']['GET'] = "activitylog/loadLogs";
$route['activitylog/getStats']['GET'] = "activitylog/getStats";
$route['activitylog/export']['GET'] = "activitylog/export";
$route['reports/sales']['GET'] = "reports/sales";
$route['reports/products']['GET'] = "reports/products";
$route['reports/exportSales']['GET'] = "reports/exportSales";
$route['reports/getChartData']['GET'] = "reports/getChartData";
$route['administrators/resetPassword']['POST'] = "administrators/resetPassword";
