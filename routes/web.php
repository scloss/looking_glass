<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


//Route::get('/', 'ReadController@show_nearby_site_list_view');
//Route::get('/', 'AuthController@view_login');
Route::post('authenticate', 'AuthController@authenticate');

Route::get('DataInputView','CreateController@data_input_view');
Route::post('DataInput','CreateController@data_input');

Route::get('DataUpdateView','UpdateController@data_update_view');
Route::post('DataUpdate','UpdateController@data_update');

Route::get('DataReadView','ReadController@data_read_view');
Route::get('ShowNearBySiteList','ReadController@show_nearby_site_list_view');
Route::post('search_site', 'ReadController@show_nearby_site_list');
//Route::post('search_site', 'ReadController@test');

Route::post('DataDelete','DeleteController@data_delete');

//----------------------------------- App Main Routes ---------------------
Route::get('/', 'MainController@index')->middleware('classA');
Route::get('LookForSite', 'MainController@look_for_sites')->middleware('classA');
Route::get('/device_info', 'MainController@device_info')->middleware('classA');
Route::get('/bbshow', 'MainController@bb_info')->middleware('classA');
Route::get('/ringshow', 'MainController@ring_info')->middleware('classA');
Route::get('/update_alarm', 'MainController@update_alarm_table')->middleware('classA');

Route::get('/book_view', 'BookingController@book_view')->middleware('classA');
Route::get('make_booking', 'BookingController@make_booking')->middleware('classA');
Route::get('show_booking_table', 'BookingController@site_particular_booking_table')->middleware('classA');
Route::get('show_all_booking_table', 'BookingController@show_all_site')->middleware('classA');
Route::get('search_booking_log', 'BookingController@search_booking_log')->middleware('classA');
Route::post('export_booking_log', 'BookingController@export_booking_log')->middleware('classA');

//---------------------------------	 Backbone -------------------------------
Route::get('/backbone_data', 'BB_Data_Update_Controller@index');
// Route::get('/backbone_data', 'BackboneController_data_update@index');
Route::get('/insert_bb_data_view', 'BB_Data_Update_Controller@insert_bb_data_view');

Route::post('/insert_bb_data', 'BB_Data_Update_Controller@insert_bb_data');
Route::get('/hopwise_link', 'BB_Data_Update_Controller@hopwise_link');
Route::get('/districtwise_link', 'BB_Data_Update_Controller@districtwise_link');
Route::get('/livesearch_hop', 'BB_Data_Update_Controller@livesearch_hop');
Route::get('/get_node_resources_list', 'BB_Data_Update_Controller@get_node_resources_list');
Route::get('/get_district_division', 'BB_Data_Update_Controller@get_district_division');

Route::get('/bb_update_view', 'BB_Data_Update_Controller@bb_update_view');

Route::get('/change_host_name', 'BB_Data_Update_Controller@change_host_name');
Route::get('/change_resource', 'BB_Data_Update_Controller@change_resource');

Route::post('/update_bb_data', 'BB_Data_Update_Controller@update_bb_data');

Route::post('/bb_destroy', 'BB_Data_Update_Controller@bb_destroy');

//------------------------------------------------------------------------------------
Route::get('/bb_map', 'BackboneController@findActiveBBWithSellableFreeCapacityView');
//------------------------------------ Flood Gate ------------------------------------
Route::get('/planning', 'MainController@planning_work')->middleware('classA');

//------------------------------------------------------------------------------------
Route::get('/update_db', 'UpdateController@update_db_view');
Route::post('/update_db_post', 'UpdateController@update_db_post');
