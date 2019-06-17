<?php

namespace App\Http\Controllers;
use File;
use DB;
use Input;
use DateTime;
use Zipper;
use Request;
use App\InfoTable;
session_start();

class ReadController extends Controller
{
    public function get_distance($latitudeFrom, $longitudeFrom,$latitudeTo,$longitudeTo){
	    
	    $theta = $longitudeFrom - $longitudeTo;
	    $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
	    $dist = acos($dist);
	    $dist = rad2deg($dist);
	    $miles = $dist * 60 * 1.1515;
	    return ($miles * 1.609344);
	    
	}

	public function show_nearby_site_list_view(){

		$client_list_query = "SELECT DISTINCT client FROM `nodes`" ;
		$client_lists = \DB::connection('mysql3')->select(\DB::raw($client_list_query));

		$robi_site_id_query = "SELECT node_name FROM scl_map_db.nodes WHERE client='ROBI'";
		$robi_site_ids = \DB::connection('mysql3')->select(\DB::raw($robi_site_id_query));

		$gp_site_id_query = "SELECT node_name FROM scl_map_db.nodes WHERE client='GP'";
		$gp_site_ids = \DB::connection('mysql3')->select(\DB::raw($gp_site_id_query));

		$bl_site_id_query = "SELECT node_name FROM scl_map_db.nodes WHERE client='BANGLALINK'";
		$bl_site_ids = \DB::connection('mysql3')->select(\DB::raw($bl_site_id_query));

		return view('Read.nearby_site_list_view',compact('client_lists','robi_site_ids','gp_site_ids','bl_site_ids'));
	}

	public function test(){
		return "success";
	}

	public function show_nearby_site_list(){

		$client = Request::get('client');//Request::get('client');
		$site_id = Request::get('site_id');

		$select_lat_long_site_query = "SELECT longitude,lattitude as latitude FROM scl_map_db.nodes WHERE client='$client' AND node_name='$site_id'";
		$select_lat_long_single_lists =  \DB::connection('mysql3')->select(\DB::raw($select_lat_long_site_query));


		if(count($select_lat_long_single_lists)>0){
		$from_lat = $select_lat_long_single_lists[0]->latitude;
		$from_long = $select_lat_long_single_lists[0]->longitude;

		$select_all_sites_query = "SELECT node_name,longitude,lattitude as latitude FROM scl_map_db.nodes WHERE client='$client'";
		$select_lat_long_lists =  \DB::connection('mysql3')->select(\DB::raw($select_all_sites_query));

		$total_site_lists = array();	
		foreach($select_lat_long_lists as $select_lat_long_list){
			$to_lat = $select_lat_long_list->latitude;
			$to_long = $select_lat_long_list->longitude;

			$distance = $this->get_distance($from_lat, $from_long,$to_lat,$to_long);

			if($distance <= 6){
				//$site_info_temp_arr = array();
				$total_site_lists[$select_lat_long_list->node_name] = $distance;
			}

		}
		asort($total_site_lists);
		//return $total_site_lists;
		//print_r($total_site_lists);
		$count = count($total_site_lists)-1;
		}
		else{

			$total_site_lists = array();
			$count = 0;

		}

		 

		
		return view('Read.site_table_view',compact('total_site_lists','site_id','count'));
	}
}
