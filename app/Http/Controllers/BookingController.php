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
use Cache;
use Artisan;

//use Illuminate\Http\Request;


class BookingController extends Controller
{
    //

    public function book_view(){
		$lat = Request::get('lat');
		$long = Request::get('long');
		$radius = Request::get('radius');
		$device_client = Request::get('client');
		$site_name = Request::get('site_name');
		$site_lat = Request::get('site_lat');
		$site_long = Request::get('site_long');
		$if_name=Request::get('if_name');
		$capacity=Request::get('capacity');
		


		$get_location_info_query = "SELECT nd.district as 'city',nd.division,nd.district,nd.location,nd.address 
									FROM looking_glass.everest_node_dump nd WHERE nd.host_name = '$site_name'";
		$location_info = \DB::select(\DB::raw($get_location_info_query));



		//print_r($_SESSION);

		$user_id = $_SESSION['dashboard_user_id'];
		$user_name = $_SESSION['dashboard_user_name'];

		// echo $user_id;
		// echo $user_name;

		// $get_interface_list_query = "SELECT `snmp_if_name` FROM looking_glass.`nms_data` WHERE `node` = '$site_name' AND `snmp_if_name` not in (SELECT interface as snmp_if_name FROM looking_glass.`booking_table` WHERE site_name = '$site_name')";
		
		$get_interface_list_query = 
		"SELECT erd.host_name as 'node',erd.resource_name as 'snmp_if_name',(SELECT COUNT(bt.id) FROM looking_glass.`booking_table` bt where bt.site_name = erd.host_name and bt.interface =erd.resource_name) as 'number_of_booking' 
		FROM looking_glass.everest_resource_dump erd 
		WHERE erd.host_name = '$site_name'";


		//echo $location_info[0]->city;

		$interface_lists = \DB::select(\DB::raw($get_interface_list_query));
		return view('Main.book_view',compact('lat','long','radius','device_client','site_name','site_lat','site_long','user_id','interface_lists','if_name','capacity','location_info'));
	}

	public function make_booking(){
		$site_name = addslashes(Request::get('site_name'));
		$existing_device_client = addslashes(Request::get('existing_device_client'));
		// $port = addslashes(Request::get('port'));
		$capacity = addslashes(Request::get('capacity'));
		$latitude = addslashes(Request::get('latitude'));
		$longitude = addslashes(Request::get('longitude'));

		$city = addslashes(Request::get('city')); 
		$division = addslashes(Request::get('division'));
		$district = addslashes(Request::get('district'));
		$location = addslashes(Request::get('location'));
		$address = addslashes(Request::get('address'));


		$client_feasibility = addslashes(Request::get('client_feasibility'));
		$capacity_feasibility = addslashes(Request::get('capacity_feasibility'));
		$comment = addslashes(Request::get('comment'));

		$booked_by = addslashes(Request::get('booked_by'));
		$expiry_date = addslashes(Request::get('expiry_date'));
		$interface = addslashes(Request::get('interface'));
		




		$insert_booking_table_query = "INSERT INTO looking_glass.`booking_table` (`site_name`,`existing_device_client`, `capacity`, `latitude`, `longitude`, `feasibility_client`, `feasibility_mbps`, `comment`,`booked_by`,`expiry_date`,`interface`,`city`,`division`,`district`,`location`,`address`) VALUES ('$site_name','$existing_device_client', '$capacity', '$latitude', '$longitude', '$client_feasibility', '$capacity_feasibility', '$comment', '$booked_by','$expiry_date','$interface','$city','$division','$district','$location','$address')";
		\DB::insert(\DB::raw($insert_booking_table_query));

		//echo $insert_booking_table_query;

		//////////////////////////////////////////////// Send Mail to Planning ///////////////////////////////////////////////////////////////
		$toAddress = "planning@summitcommunications.net";
		$subj = "Booking alert from looking glass";
		$mail_cc = "marketing@summitcommunications.net";
		$msg = "<table border='1' width='100%' cellpadding='0' cellspacing='0' style='min-width:100%;'>
					<tr>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>Site Name</td>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>$site_name</td>
					</tr>

					<tr>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>Interface</td>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>$interface</td>
					</tr>
					
					<tr>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>Port Capacity</td>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>$capacity</td>
					</tr>

					<tr>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>Latitude</td>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>$latitude</td>
					</tr>

					<tr>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>Longitude</td>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>$longitude</td>
					</tr>

					<tr>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>Feasibility Client</td>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>$client_feasibility</td>
					</tr>

					<tr>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>Feasibility Capacity(Mbps)</td>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>$capacity_feasibility</td>
					</tr>

					<tr>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>Feasibility Expiry Date</td>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>$expiry_date</td>
					</tr>

					<tr>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>Comment</td>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>$comment</td>
					</tr>

					<tr>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>Booked By</td>
						<td valign='top' style='padding:5px; font-family: Arial,sans-serif; font-size: 16px; line-height:20px;'>$booked_by</td>
					</tr>				
				</table>";


		$mail_body = $msg;
		$url = "http://172.16.136.35:1347/TextMailApi";
		$myvars = 'subj=' . $subj . '&msg=' . $mail_body. '&toAddress=' . $toAddress. '&mail_cc=' . $mail_cc;
				//echo $myvars;
		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt( $ch, CURLOPT_HEADER, 0);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
				
		$response = curl_exec( $ch );
		// echo "<br>";
		// echo "mail initiated";
		// echo "<br>";
		// echo $toAddress;
		// echo "<br>";
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


		$lat = Request::get('search_lat');
		$long = Request::get('search_long');
		$radius = Request::get('search_radius');
		$msg = "Booking Successful!!!!!!!!";
		//$url = 'LookForSite?lat='.$lat.'&long='.$long.'&radius='.$radius.'&msg='.$msg;

		$url = 'show_booking_table?lat='.$lat.'&long='.$long.'&radius='.$radius.'&msg='.$msg.'&site_name='.$site_name;

		//return $url;
		return redirect($url);


	}

	public function site_particular_booking_table(){
		$site_name = Request::get('site_name');
		$lat = Request::get('lat');
		$long = Request::get('long');
		$radius = Request::get('radius');
		$interface=Request::get('if_name');

		$condition = "";

		if ($site_name) {
			$condition .= "site_name = '$site_name' AND ";			
		}

		if ($interface) {
			$condition .= "interface = '$interface' AND ";			
		}

		$condition .= "1";


		$select_query_for_site_table = "SELECT * FROM looking_glass.booking_table WHERE $condition";
		$site_lists = \DB::select(\DB::raw($select_query_for_site_table));


		$interface_booking_log_query = "";

		return view('Main.booking_table_view',compact('site_lists'));

	}

	public function show_all_site(){

		$select_query_for_site_table = "SELECT * FROM looking_glass.booking_table ORDER BY id DESC";
		$site_lists = \DB::select(\DB::raw($select_query_for_site_table));

		

		return view('Main.booking_table_view',compact('site_lists'));

	}


	public function search_booking_log(){
		$site_name = addslashes(Request::get('site_name'));
		$device_client = addslashes(Request::get('device_client'));
		// $port = addslashes(Request::get('port')); 
		$interface = addslashes(Request::get('interface'));
		$capacity = addslashes(Request::get('capacity'));
		$latitude = addslashes(Request::get('latitude'));
		$longitude = addslashes(Request::get('longitude'));
		$client_feasibility = addslashes(Request::get('client_feasibility'));
		$capacity_feasibility = addslashes(Request::get('capacity_feasibility'));
		$comment = addslashes(Request::get('comment'));
		$booking_date = addslashes(Request::get('booking_date'));
		$expiry_date = addslashes(Request::get('expiry_date'));
		$booked_by = addslashes(Request::get('booked_by'));

		$city = addslashes(Request::get('city'));
		$division = addslashes(Request::get('division'));
		$district = addslashes(Request::get('district'));
		$location = addslashes(Request::get('location'));
		$address = addslashes(Request::get('address'));

		//$select_query_for_site_table = "SELECT * FROM looking_glass.booking_table WHERE";

		$whereQuery = "";

		if ($site_name) {
			$whereQuery .= "site_name = '$site_name' AND ";			
		}
		if ($device_client) {
			$whereQuery .= "existing_device_client = '$device_client' AND ";			
		}
		// if ($port) {
		// 	$whereQuery .= "port = '$port' AND ";			
		// }
		if ($interface) {
			$whereQuery .= "interface = '$interface' AND ";			
		}
		if ($capacity) {
			$whereQuery .= "capacity = '$capacity' AND ";			
		}
		if ($latitude) {
			$whereQuery .= "latitude = '$latitude' AND ";			
		}
		if ($longitude) {
			$whereQuery .= "longitude = '$longitude' AND ";			
		}
		if ($client_feasibility) {
			$whereQuery .= "feasibility_client = '$client_feasibility' AND ";			
		}
		if ($capacity_feasibility) {
			$whereQuery .= "feasibility_mbps = '$client_feasibility' AND ";			
		}
		if ($comment) {
			$whereQuery .= "comment = '$comment' AND ";			
		}
		if ($booking_date) {
			$whereQuery .= "created_at like '$booking_date%' AND ";			
		}
		if ($expiry_date) {
			$whereQuery .= "expiry_date like '$expiry_date%' AND ";			
		}
		if ($booked_by) {
			$whereQuery .= "booked_by = '$booked_by' AND ";			
		}
		if ($city) {
			$whereQuery .= "city = '$city' AND ";			
		}
		if ($location) {
			$whereQuery .= "location = '$location' AND  ";			
		}
		if ($division) {
			$whereQuery .= "division = '$division' AND  ";			
		}
		if ($district) {
			$whereQuery .= "district = '$district' AND  ";			
		}
		if ($address) {
			$whereQuery .= "address like '%$address%' AND  ";			
		}

		
		// if($whereQuery == ""){
		// 	$whereQuery = "1";	
		// }

		$whereQuery .= "1";	

		
		$select_query_for_site_table = "SELECT * FROM looking_glass.booking_table WHERE $whereQuery";

		//echo $select_query_for_site_table;


		//echo $select_query_for_site_table;

		$site_lists = \DB::select(\DB::raw($select_query_for_site_table));

		return view('Main.booking_table_view',compact('site_lists','select_query_for_site_table'));



	}

	public function export_booking_log(){
		$query = Request::get('qry');
		$booking_lists = \DB::select(\DB::raw($query));

		$headerArr = array('id','site_name','existing_device_client','capacity','latitude','longitude','feasibility_client','feasibility_mbps','comment','booked_by','expiry_date','interface','city','division','district','location','address','created_at');
		$bigArr = array();
        array_push($bigArr, $headerArr);

        //echo $query;

        foreach ($booking_lists as $booking_list) {
        	# code...
        	$smallArr = array();
        	array_push($smallArr, $booking_list->id);
        	array_push($smallArr, $booking_list->site_name);
        	array_push($smallArr, $booking_list->existing_device_client);
        	array_push($smallArr, $booking_list->capacity);
        	array_push($smallArr, $booking_list->latitude);
        	array_push($smallArr, $booking_list->longitude);
        	array_push($smallArr, $booking_list->feasibility_client);
        	array_push($smallArr, $booking_list->feasibility_mbps);
        	array_push($smallArr, $booking_list->comment);
        	array_push($smallArr, $booking_list->booked_by);
        	array_push($smallArr, $booking_list->expiry_date);
        	array_push($smallArr, $booking_list->interface);
        	array_push($smallArr, $booking_list->city);
        	array_push($smallArr, $booking_list->division);
        	array_push($smallArr, $booking_list->district);
        	array_push($smallArr, $booking_list->location);
        	array_push($smallArr, $booking_list->address);
        	array_push($smallArr, $booking_list->created_at);


        	 array_push($bigArr, $smallArr);
        }

        $export = fopen('../Uploaded_Files/export.csv','w');
        foreach ($bigArr as $fields) {
                    fputcsv($export, $fields);
        }
        $path = '../Uploaded_Files/export.csv';
        return response()->download($path); 





	} 
}
