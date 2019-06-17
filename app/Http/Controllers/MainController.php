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
//use Illuminate\Support\Facades\Redis;

// use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(){

    	$lat = Request::get('lat');
		$long = Request::get('long');
		$radius = Request::get('radius');

		//$_SESSION['dashboard_user_id'] = 'ahnaf.muttaki';

		$alarm_dump = "SELECT `created_time` FROM looking_glass.everest_alarm_dump ORDER BY `created_time` DESC LIMIT 1";

		$alarm = \DB::select(\DB::raw($alarm_dump));

		$node_dump = "SELECT `created_time` FROM looking_glass.everest_node_dump ORDER BY `created_time` ASC LIMIT 1";

		$node = \DB::select(\DB::raw($node_dump));

		$resource_dump = "SELECT `created_time` FROM looking_glass.everest_resource_dump ORDER BY `created_time` ASC LIMIT 1";

		$resource = \DB::select(\DB::raw($resource_dump));

		$throughput_dump = "SELECT `created_time` FROM looking_glass.everest_throughput_dump ORDER BY `created_time` ASC LIMIT 1";
		
		$throughput = \DB::select(\DB::raw($throughput_dump));

		$telco_data = "SELECT `created_time` FROM looking_glass.telco_data ORDER BY `created_time` ASC LIMIT 1";

		$telco = \DB::select(\DB::raw($telco_data));

		$access_issue = "SELECT `created_time` FROM looking_glass.access_issue_table ORDER BY `created_time` ASC LIMIT 1";

		$access = \DB::select(\DB::raw($access_issue));

    	return view('Main.home',compact('lat','long','radius','alarm','node','resource','throughput','telco','access'));
    }

    public function look_for_sites(){
		$user_id = 
    	$from_lat = Request::get('lat');
		$from_long = Request::get('long');		
		$radius = Request::get('radius');
		$user_id = $_SESSION['dashboard_user_id'];

		$insert_search_log_query = "INSERT INTO looking_glass.search_log_table (user_id,lat,lon,radius) VALUES ('$user_id','$from_lat','$from_long','$radius')";
		$insert_search_log = \DB::insert(\DB::raw($insert_search_log_query));

		


    	if(Request::get('msg')){
    		$msg = Request::get('msg');	
    	}
    	else{
    		$msg = "";
    	}
    	

    	$key = $from_lat.'-'.$from_long.'-'.$radius;

	    $sort_key = $key.'-'.'total_site_lists';
	    $info_key = $key.'-'.'site_infos';


	    if (Cache::has($sort_key)){

	    	$total_site_lists = Cache::get($sort_key);
	    	$site_infos = Cache::get($info_key);

	    	$booking_infos_query = "SELECT `site_name`,COUNT(`id`) as 'number_of_booking' FROM `booking_table` GROUP BY `site_name`";
	    	$booking_info_lists = \DB::select(\DB::raw($booking_infos_query));

	    	return view('Main.site_table',compact('total_site_lists','site_infos','from_lat','from_long','radius','msg','booking_info_lists'));	

	    } 
	    else {			
			// $select_lat_long_site_query = 	"SELECT nd.lat as 'latitude',nd.lon as 'longitude',nd.host_name as 'device_name',nd.ip_addr as 'device_ip',nd.district,nd.division,nd.location,nd.address,nd.model as 'device_model',nd.client as 'device_info_client',td.po_vol,td.client,ait.access_issue
			// 								FROM looking_glass.everest_node_dump nd 
			// 								LEFT JOIN looking_glass.telco_data td ON nd.host_name = td.node_name
			// 								LEFT JOIN looking_glass.access_issue_table ait ON nd.host_name = ait.host_name";

			$black_list_array = array();

			$select_blacklist_nodes = "SELECT * FROM looking_glass.blacklist_nodes_table";
			$black_list_node = \DB::select(\DB::raw($select_blacklist_nodes));

			foreach($black_list_node as $row){
				$host_name_temp = "'".$row->site_name."'";
				array_push($black_list_array,$host_name_temp);

				$host_name_temp = "'".$row->unms_host_name."'";
				array_push($black_list_array,$host_name_temp);
			}

			$black_list_array = array_unique($black_list_array);
			$black_list_node_string = implode(",",$black_list_array);

			$select_lat_long_site_query = 	"SELECT nd.lat as 'latitude',nd.lon as 'longitude',nd.host_name as 'device_name',nd.ip_addr as 'device_ip',nd.district,nd.division,nd.location,nd.address,nd.model as 'device_model',nd.client as 'device_info_client',td.po_vol,td.client,ait.access_issue,hnt.tag 
											FROM looking_glass.everest_node_dump nd 
											LEFT JOIN looking_glass.telco_data td ON nd.host_name = td.node_name 
											LEFT JOIN 
											(SELECT ai.host_name,GROUP_CONCAT(ai.access_issue) as access_issue FROM looking_glass.access_issue_table ai GROUP BY ai.host_name) as ait ON nd.host_name = ait.host_name  
											LEFT JOIN
											(SELECT tt.host_name,GROUP_CONCAT(tt.tag) as tag FROM looking_glass.host_name_tag tt GROUP BY tt.host_name) as hnt ON nd.host_name = hnt.host_name
											WHERE nd.host_name not in ($black_list_node_string)
											ORDER BY `ait`.`access_issue`  DESC";

			//return $select_lat_long_site_query;
			//return $select_lat_long_site_query;
	    	$select_all_lat_long_lists =  \DB::select(\DB::raw($select_lat_long_site_query));

	    	$booking_infos_query = "SELECT `site_name`,COUNT(`id`) as 'number_of_booking' FROM looking_glass.`booking_table` GROUP BY `site_name`";
	    	$booking_info_lists = \DB::select(\DB::raw($booking_infos_query));

	    	$total_site_lists = array();
	    	$site_infos = array();	
	    	foreach($select_all_lat_long_lists as $select_all_lat_long_list){
	    		$to_lat = $select_all_lat_long_list->latitude;
	    		$to_long = $select_all_lat_long_list->longitude;

	    		$distance = $this->get_distance($from_lat, $from_long,$to_lat,$to_long);

	    		if($distance <= $radius){
	    			$total_site_lists[$select_all_lat_long_list->device_name] = $distance;
	    			$site_infos[$select_all_lat_long_list->device_name] = $select_all_lat_long_list;
	    		}


	    	}
	    	asort($total_site_lists); 

	    	Cache::put($sort_key,$total_site_lists, 30);
	    	Cache::put($info_key,$site_infos, 30);
		   
	    	return view('Main.site_table',compact('total_site_lists','site_infos','from_lat','from_long','radius','msg','booking_info_lists'));	
	    	

	    }

	
	}



	public function device_info(){
		$site_name = Request::get('sn');
		$lat = Request::get('lat');
		$long = Request::get('long');
		$radius = Request::get('radius');
		$ip= Request::get('ip');
		$tocted=Request::get('tocted');
		$client=Request::get('client');
		$site_lat=Request::get('site_lat');
		$site_long=Request::get('site_long');

		$interface_where_clause = "";

		$get_vendor_query = "SELECT vendor FROM looking_glass.everest_node_dump WHERE host_name = '$site_name'";
		$vendor_array = \DB::select(\DB::raw($get_vendor_query));

		//return $vendor_array[0]->vendor;


		if($vendor_array[0]->vendor == "Huawei"){
			$interface_where_clause = "(erd.resource_name like 'GigabitEthernet%' OR erd.resource_name like 'Port%')";
		}
		elseif($vendor_array[0]->vendor == "Juniper"){	
			$interface_where_clause = "(erd.resource_name like 'ge-%' OR erd.resource_name like 'xe-%' OR erd.resource_name like 'et-%' OR erd.resource_name like 'Port%')";
		}
		else{
			$interface_where_clause = "((erd.resource_name like 'GigabitEthernet%') OR (erd.resource_name like 'Port%'))";
		}

		//$interfaces_condition = "(resource_name like 'ge-%' OR resource_name like 'xe-%' OR resource_name like 'et-%' OR resource_name like 'Port%' OR resource_name like 'GigabitEthernet%')";



		// ----------------------------######################### Get  all physical devices for device table ######################### -----------------------------------------------

		// $all_physical_device_info_query = 	"SELECT ot.site_name as 'node',ot.snmpifname as 'ifname',ot.`snmpifspeed`,ot.port_category,ot.port_type,ot.snmpifadminstatus as 'admin_status',ot.snmpifoperstatus as 'oper_status',ot.snmpifalias as 'if_description',ed.average as 'avg',ed.minimum as 'min',ed.maximum as 'max',oat.logmsg 
		// 									FROM looking_glass.`opennms` ot 
		// 									LEFT JOIN looking_glass.`opennms_alarm` oat ON ot.site_name = oat.nodesysname AND ot.snmpifname = oat.ifname 
		// 									LEFT JOIN looking_glass.everest_data ed ON ot.site_name = ed.host_name AND ot.snmpifname = ed.resource_name 
		// 									WHERE ot.site_name = '$site_name' ORDER BY INET_ATON(ot.snmpifname)";


		$all_physical_device_info_query = 	"SELECT erd.host_name as 'node',erd.resource_name as 'ifname',erd.bandwidth_polled as 'snmpifspeed',erd.resource_alias as 'if_alias',etd.average as 'avg',etd.minimum as 'min',etd.maximum as 'max', ead.description as 'logmsg',nd.model,
											(CASE
												when(erd.resource_name like '%.%' or erd.resource_name like '%mpls')
												THEN 'Logical'
												ELSE 'Physical'
											END
											) as port_category,
											(select
											(case  
											when(
												(
												(nd.model like '%acx1000%' and (erd.resource_name = 'ge-0/2/0' or erd.resource_name = 'ge-0/2/1' or erd.resource_name = 'ge-0/2/2' or erd.resource_name = 'ge-0/2/3')) 
												
												or (nd.model like '%acx1100%' and (erd.resource_name = 'ge-0/1/0' or erd.resource_name = 'ge-0/1/1' or erd.resource_name = 'ge-0/1/2' or erd.resource_name = 'ge-0/1/3'))
												
												or (nd.model like '%acx2200%' and (erd.resource_name = 'ge-0/1/0' or erd.resource_name = 'ge-0/1/1' or erd.resource_name = 'ge-0/1/2' or erd.resource_name = 'ge-0/1/3' or erd.resource_name = 'ge-0/2/0' or erd.resource_name = 'ge-0/2/1' or erd.resource_name = 'xe-0/3/0' or erd.resource_name = 'xe-0/3/1'))
												
												or (nd.model like '%acx2100%' and (erd.resource_name = 'ge-1/1/0' or erd.resource_name = 'ge-1/1/1' or erd.resource_name = 'ge-1/1/2' or erd.resource_name = 'ge-1/1/3' or erd.resource_name = 'ge-1/2/0' or erd.resource_name = 'ge-1/2/1' or erd.resource_name = 'xe-1/3/0' or erd.resource_name = 'xe-1/3/1'))
											
											
												or (nd.model like '%ATN910B ..%' and (erd.resource_name = 'GigabitEthernet0/2/16' 
																							or erd.resource_name = 'GigabitEthernet0/2/17' 
																							or erd.resource_name = 'GigabitEthernet0/2/18' 
																							or erd.resource_name = 'GigabitEthernet0/2/19' 
																							or erd.resource_name = 'GigabitEthernet0/2/20' 
																							or erd.resource_name = 'GigabitEthernet0/2/21' 
																							or erd.resource_name = 'GigabitEthernet0/2/22' 
																							or erd.resource_name = 'GigabitEthernet0/2/23'
																							or erd.resource_name = 'GigabitEthernet0/2/32' 
																							or erd.resource_name = 'GigabitEthernet0/2/33'))
											
												or (nd.model like '%ATN 910B-D%' and (erd.resource_name = 'GigabitEthernet0/2/0' 
																							or erd.resource_name = 'GigabitEthernet0/2/1' 
																							or erd.resource_name = 'GigabitEthernet0/2/2' 
																							or erd.resource_name = 'GigabitEthernet0/2/3' 
																							or erd.resource_name = 'GigabitEthernet0/2/4' 
																							or erd.resource_name = 'GigabitEthernet0/2/5' 
																							or erd.resource_name = 'GigabitEthernet0/2/6' 
																							or erd.resource_name = 'GigabitEthernet0/2/7'
																							or erd.resource_name = 'GigabitEthernet0/2/8' 
																							or erd.resource_name = 'GigabitEthernet0/2/9'
																							or erd.resource_name = 'GigabitEthernet0/2/10' 
																							or erd.resource_name = 'GigabitEthernet0/2/11' 
																							or erd.resource_name = 'GigabitEthernet0/2/12' 
																							or erd.resource_name = 'GigabitEthernet0/2/13' 
																							or erd.resource_name = 'GigabitEthernet0/2/14' 
																							or erd.resource_name = 'GigabitEthernet0/2/15' 
																							or erd.resource_name = 'GigabitEthernet0/2/16'
																							or erd.resource_name = 'GigabitEthernet0/2/17' 
																							or erd.resource_name = 'GigabitEthernet0/2/18'
																							or erd.resource_name = 'GigabitEthernet0/2/19' 
																							or erd.resource_name = 'GigabitEthernet0/2/20' 
																							or erd.resource_name = 'GigabitEthernet0/2/21' 
																							or erd.resource_name = 'GigabitEthernet0/2/22' 
																							or erd.resource_name = 'GigabitEthernet0/2/23' 
																							or erd.resource_name = 'GigabitEthernet0/2/24' 
																							or erd.resource_name = 'GigabitEthernet0/2/25'
																							or erd.resource_name = 'GigabitEthernet0/2/26' 
																							or erd.resource_name = 'GigabitEthernet0/2/27'
																							))
											
												or (nd.model like '%ATN 910B-F%' and (erd.resource_name = 'GigabitEthernet0/2/0' 
																							or erd.resource_name = 'GigabitEthernet0/2/1' 
																							or erd.resource_name = 'GigabitEthernet0/2/2' 
																							or erd.resource_name = 'GigabitEthernet0/2/3' 
																							or erd.resource_name = 'GigabitEthernet0/2/4' 
																							or erd.resource_name = 'GigabitEthernet0/2/5' 
																							or erd.resource_name = 'GigabitEthernet0/2/6' 
																							or erd.resource_name = 'GigabitEthernet0/2/7'
																							or erd.resource_name = 'GigabitEthernet0/2/8' 
																							or erd.resource_name = 'GigabitEthernet0/2/9'
																							or erd.resource_name = 'GigabitEthernet0/2/10' 
																							or erd.resource_name = 'GigabitEthernet0/2/11' 
																							or erd.resource_name = 'GigabitEthernet0/2/12' 
																							or erd.resource_name = 'GigabitEthernet0/2/13' 
																							or erd.resource_name = 'GigabitEthernet0/2/14' 
																							or erd.resource_name = 'GigabitEthernet0/2/15'                          or erd.resource_name = 'GigabitEthernet0/2/24' 
																							or erd.resource_name = 'GigabitEthernet0/2/25'
																							or erd.resource_name = 'GigabitEthernet0/2/26' 
																							or erd.resource_name = 'GigabitEthernet0/2/27'
																							))
											
												or (nd.model like '%ATN 910C-B%' and (erd.resource_name = 'GigabitEthernet0/2/0' 
																							or erd.resource_name = 'GigabitEthernet0/2/1' 
																							or erd.resource_name = 'GigabitEthernet0/2/2' 
																							or erd.resource_name = 'GigabitEthernet0/2/3' 
																							or erd.resource_name = 'GigabitEthernet0/2/4' 
																							or erd.resource_name = 'GigabitEthernet0/2/5' 
																							or erd.resource_name = 'GigabitEthernet0/2/6' 
																							or erd.resource_name = 'GigabitEthernet0/2/7'
																							or erd.resource_name = 'GigabitEthernet0/2/8' 
																							or erd.resource_name = 'GigabitEthernet0/2/9'
																							or erd.resource_name = 'GigabitEthernet0/2/10' 
																							or erd.resource_name = 'GigabitEthernet0/2/11' 
																							or erd.resource_name = 'GigabitEthernet0/2/12' 
																							or erd.resource_name = 'GigabitEthernet0/2/13' 
																							or erd.resource_name = 'GigabitEthernet0/2/14' 
																							or erd.resource_name = 'GigabitEthernet0/2/15'                          
																							or erd.resource_name = 'GigabitEthernet0/2/16' 
																							or erd.resource_name = 'GigabitEthernet0/2/17'
																							or erd.resource_name = 'GigabitEthernet0/2/18' 
																							or erd.resource_name = 'GigabitEthernet0/2/19'
																							))
												
												or (erd.resource_name like '%Port%')
												
												or (nd.model like '%mx%')
												)
											and (erd.resource_name not like '%.%')
												) 
											THEN
												'Optical'
											when(erd.resource_name like '%.%')
											then 
												''	
											ELSE
												'Electrical' 
											END
											)
											as port_type 
											from everest_node_dump where everest_node_dump.host_name = erd.host_name) as port_type,
											eaod.admin_status,
											eaod.oper_status
											FROM looking_glass.everest_resource_dump erd 
											LEFT JOIN looking_glass.everest_alarm_dump ead ON erd.host_name = ead.host_name AND erd.resource_name = ead.resource_name AND erd.ip_addr = ead.ip_addr
											LEFT JOIN looking_glass.everest_throughput_dump etd ON erd.host_name = etd.host_name AND erd.resource_name = etd.resource_name
											LEFT JOIN looking_glass.everest_node_dump nd ON erd.host_name = nd.host_name
											LEFT JOIN looking_glass.everest_admin_oper_status_dump eaod ON erd.host_name = eaod.host_name AND erd.resource_name = eaod.resource_name 
											WHERE erd.host_name = '$site_name' AND   
											$interface_where_clause
											ORDER BY erd.bandwidth_polled";

		//return $all_physical_device_info_query;

		$all_physical_device_infos = \DB::select(\DB::raw($all_physical_device_info_query));
		
		


		//----------------------------########################### Getting Ring Usage and Ring built capacity data ################### -------------------------------------------------
		//#>Get Ring Usage:
        //               >Get ring info by matching third octet of device ip
        //               >Get the first and last node of the ring by getting Min and Max of 4th octet
        //               >Get interfaces list from first and last node of the ring
        //               >From interface list get the interfaces which has matching third octet. The result will have four interfaces.
        //               >Get Usage data (AVG,Min,Max) from interfaces. Get the max Usage from  two sites from this usage data.
		//
		//
		// #>Get Ring Capacity: 
        //             >Get the lowest interface speed from the ring. It will be the Ring built capacity
		

		// Getting third octet with both 10.1 and 172.16 
		$iparray = explode('.',$ip);
		$third_octet = $iparray[0].'.'.$iparray[1].'.'.$iparray[2];

		$third_octet_172=str_replace('10.1.','172.16.', $third_octet);
		$third_octet_10=str_replace('172.16.','10.1.', $third_octet);

		$third_octet_172 = $third_octet_172.".";
		$third_octet_10 = $third_octet_10.".";
		$third_octet = $third_octet."."; 

		// Getting the ring node info
		// $ring_nodes_query = "SELECT di.`ip_address`,di.`host_name` 
		// 					FROM looking_glass.`device_info` di 
		// 					where (di.`ip_address` like '$third_octet_172%') OR (di.`ip_address` like '$third_octet_10%')
		// 					ORDER BY INET_ATON(di.`ip_address`) ASC";


		$ring_nodes_query = "SELECT nd.ip_addr as 'ip_address',nd.`host_name` 
							FROM looking_glass.everest_node_dump nd 
							where (nd.ip_addr like '$third_octet_172%') OR (nd.ip_addr like '$third_octet_10%') 
							ORDER BY INET_ATON(nd.ip_addr) ASC";

		//return $ring_nodes_query;

		//echo $ring_nodes_query;
		$ring_nodes = \DB::select(\DB::raw($ring_nodes_query));
		//print_r($ring_nodes);



		$condition = "";

		// This loop sets the condition string for getting all the if speed from the ring
		// foreach ($ring_nodes as $ring_node) {
		// 	$condition .= "(`site_name` ='".$ring_node->host_name."' AND (`interfaceip` like '$third_octet_172%' OR `interfaceip` like '$third_octet_10%')) OR ";		
		// }
		foreach ($ring_nodes as $ring_node) {
			$condition .= "(`host_name` ='".$ring_node->host_name."' AND (`interface_ip` like '$third_octet_172%' OR `interface_ip` like '$third_octet_10%')) OR ";		
		}

		$condition = rtrim($condition,'OR ');

		// $all_ring_if_speed_query = "SELECT `snmpifspeed` FROM looking_glass.`nms_nodes` WHERE $condition ";

		$all_ring_if_speed_query = "SELECT bandwidth_polled 
		FROM looking_glass.everest_resource_dump erd 
		WHERE $condition 
		AND (erd.resource_name like 'ge-%' OR erd.resource_name like 'xe-%' OR erd.resource_name like 'et-%' OR erd.resource_name like 'Port%' OR erd.resource_name like 'GigabitEthernet%')
		AND erd.poll_status != 'Disabled'";

		//return $all_ring_if_speed_query;

		$if_speeds = \DB::select(\DB::raw($all_ring_if_speed_query));
		
		$ring_if_speeds = array();
		
		// foreach ($if_speeds as $if_speed) {
		// 	# code...
		// 	array_push($ring_if_speeds,$if_speed->snmpifspeed);
		// }

		foreach ($if_speeds as $if_speed) {
			# code...
			$if_speed = $if_speed->bandwidth_polled;
			$calculated_if_speed = 0;

			if($if_speed != "-" ){
				$if_speed_split = explode(" ",$if_speed);
				$speed = $if_speed_split[0];
				$unit = $if_speed_split[1];
				if($unit == "Mbps"){
					$calculated_if_speed = $speed * 1000000; 
				}
				if($unit == "Gbps"){
					$calculated_if_speed = $speed * 1000000000;
				}
				array_push($ring_if_speeds,$calculated_if_speed);
			}

			
		}
		
		if(count($ring_if_speeds) == 0){
			array_push($ring_if_speeds,0);
		}

		$ring_built_capacity = min($ring_if_speeds)/1000000;

		$first_node = reset($ring_nodes);
		$last_node = end($ring_nodes);

		// $first_node_interfaces_query = 
		// "SELECT nn.`site_name`,nn.`nodeip`,nn.`snmpifname`,nn.`interfaceip`,nn.`snmpifspeed`,ed.host_name,ed.resource_name,ed.resource_alias,ed.average,ed.minimum,ed.maximum 
		// FROM looking_glass.`opennms` nn 
		// LEFT JOIN looking_glass.`everest_data` ed ON nn.site_name = ed.host_name AND SUBSTRING_INDEX(nn.snmpifname,'.',1) = ed.resource_name 
		// WHERE `site_name` = '$first_node->host_name' AND (`interfaceip` like '$third_octet_172%' OR `interfaceip` like '$third_octet_10%')";

		$first_node_interfaces_query = 
		"SELECT erd.host_name as 'site_name',erd.ip_addr as 'nodeip',erd.resource_name as 'snmpifname',erd.interface_ip as 'interfaceip',erd.bandwidth_polled as 'snmpifspeed',etd.host_name,etd.resource_name,etd.resource_alias,etd.average,etd.minimum,etd.maximum 
		FROM looking_glass.everest_resource_dump erd 
		LEFT JOIN looking_glass.`everest_throughput_dump` etd ON erd.host_name = etd.host_name AND SUBSTRING_INDEX(erd.resource_name, '.', 1)  = etd.resource_name 
		WHERE erd.host_name = '$first_node->host_name' AND (erd.interface_ip like '$third_octet_172%' OR erd.interface_ip like '$third_octet_10%') AND 
		(erd.resource_name like 'ge-%' OR erd.resource_name like 'xe-%' OR erd.resource_name like 'et-%' OR erd.resource_name like 'Port%' OR erd.resource_name like 'GigabitEthernet%')
		AND erd.poll_status != 'Disabled'";


		//return $first_node_interfaces_query;


		//echo $first_node_interfaces_query; 
		
		$first_node_interfaces = \DB::select(\DB::raw($first_node_interfaces_query));
		
		// $last_node_interfaces_query = 
		// "SELECT nn.`site_name`,nn.`nodeip`,nn.`snmpifname`,nn.`interfaceip`,nn.`snmpifspeed`,ed.host_name,ed.resource_name,ed.resource_alias,ed.average,ed.minimum,ed.maximum 
		// FROM looking_glass.`opennms` nn 
		// LEFT JOIN looking_glass.`everest_data` ed ON nn.site_name = ed.host_name AND SUBSTRING_INDEX(nn.snmpifname,'.',1) = ed.resource_name 
		// WHERE `site_name` = '$last_node->host_name' AND (`interfaceip` like '$third_octet_172%' OR `interfaceip` like '$third_octet_10%')";

		$last_node_interfaces_query = 
		"SELECT erd.host_name as 'site_name',erd.ip_addr as 'nodeip',erd.resource_name as 'snmpifname',erd.interface_ip as 'interfaceip',erd.bandwidth_polled as 'snmpifspeed',etd.host_name,etd.resource_name,etd.resource_alias,etd.average,etd.minimum,etd.maximum 
		FROM looking_glass.everest_resource_dump erd 
		LEFT JOIN looking_glass.`everest_throughput_dump` etd ON erd.host_name = etd.host_name AND SUBSTRING_INDEX(erd.resource_name, '.', 1) = etd.resource_name 
		WHERE erd.host_name = '$last_node->host_name' AND (erd.interface_ip like '$third_octet_172%' OR erd.interface_ip like '$third_octet_10%') AND 
		(erd.resource_name like 'ge-%' OR erd.resource_name like 'xe-%' OR erd.resource_name like 'et-%' OR erd.resource_name like 'Port%' OR erd.resource_name like 'GigabitEthernet%')
		AND erd.poll_status != 'Disabled'";

	
		//echo $last_node_interfaces_query;
		$last_node_interfaces = \DB::select(\DB::raw($last_node_interfaces_query));

		$avg_first = array();
		$min_first = array();
		$max_first = array();

		$avg_second = array();
		$min_second = array();
		$max_second = array();



		foreach ($first_node_interfaces as $first_node_interface) {
			array_push($avg_first,$first_node_interface->average);
			array_push($min_first,$first_node_interface->minimum);
			array_push($max_first,$first_node_interface->maximum);
			# code...
		}

		foreach ($last_node_interfaces as $last_node_interface) {
			array_push($avg_second,$last_node_interface->average);
			array_push($min_second,$last_node_interface->minimum);
			array_push($max_second,$last_node_interface->maximum);
			# code...
		}
		if(count($avg_first) == 0){
			array_push($avg_first,0);
		}
		if(count($min_first) == 0){
			array_push($min_first,0);
		}
		if(count($max_first) == 0){
			array_push($max_first,0);
		}

		if(count($avg_second) == 0){
			array_push($avg_second,0);
		}
		if(count($min_second) == 0){
			array_push($min_second,0);
		}
		if(count($max_second) == 0){
			array_push($max_second,0);
		}

		$avg_f_val = max($avg_first);
		$min_f_val = max($min_first);
		$max_f_val = max($max_first);


		$avg_s_val = max($avg_second);
		$min_s_val = max($min_second);
		$max_s_val = max($max_second);

		//echo $avg_f_val." ".$min_f_val." ".$max_f_val." ".$avg_s_val." ".$min_s_val." ".$max_s_val;

		$avg_val = ($avg_f_val + $avg_s_val)/2;
		$min_val = ($min_f_val + $min_s_val)/2;
		$max_val = ($max_f_val+$max_s_val);

		// echo "Average:";
		// echo $avg_val;
		// echo "<p>";

		// echo "Min:";
		// echo $min_val;
		// echo "<p>";

		// echo "Max:";
		// echo $max_val;

		$interface_wise_bookings_count = "SELECT `interface`,COUNT(`id`) as 'number_of_booking' FROM looking_glass.`booking_table` where site_name = '$site_name' GROUP BY `interface`";
		$interface_wise_bookings = \DB::select(\DB::raw($interface_wise_bookings_count));

		//////////////////////////////////////////////////////////////////////////////////////
		



		////////////////////Electrical Optical Free Occupied Port Calculation/////////////////		
		foreach($all_physical_device_infos as $all_physical_device_info){
			if($all_physical_device_info->port_category == "Physical"){
				$temp_admin = "";
				$temp_oper = "";
				
				// determine admin status
				if($all_physical_device_info->admin_status==1){
					$temp_admin = "up";
				}
				else if($all_physical_device_info->admin_status=="" && $all_physical_device_info->port_category == "Physical"){	
					$temp_admin = "disabled";	
				}
				else if($all_physical_device_info->admin_status==""){
						
				}
				else{
					$temp_admin = "down";
				}
				
				// determine oper status
				if($all_physical_device_info->oper_status==1){
					$temp_oper = "up";
				}
				elseif($all_physical_device_info->oper_status=="" && $all_physical_device_info->port_category == "Physical"){	
					$temp_oper = "disabled";	
				}
				elseif($all_physical_device_info->oper_status==""){
					
				}
				else{
					$temp_oper = "down";
				}

				$all_physical_device_info->admin_status_translation = $temp_admin;
				$all_physical_device_info->oper_status_translation = $temp_oper;
				
			}else{
				$all_physical_device_info->admin_status_translation = "";
				$all_physical_device_info->oper_status_translation = "";
			}

		}

		$optical_free = 0;
		$optical_occupied = 0;
		
		$electrical_free = 0;
		$electrical_occupied = 0;

		foreach($all_physical_device_infos as $all_physical_device_info){
				if($all_physical_device_info->port_category == 'Physical'){
					if($all_physical_device_info->port_type == 'Optical'){			
						if((($all_physical_device_info->admin_status_translation == "down") || ($all_physical_device_info->admin_status_translation == "disabled")) && (($all_physical_device_info->oper_status_translation=="down") || ($all_physical_device_info->oper_status_translation=="disabled"))){
							$optical_free = $optical_free+1;
						}
						else if( ($all_physical_device_info->admin_status_translation == "up") && ($all_physical_device_info->oper_status_translation=="up")){
							$optical_occupied = $optical_occupied + 1;
						}
						else if( ($all_physical_device_info->admin_status_translation == "up") && ($all_physical_device_info->oper_status_translation=="down") && ( ($all_physical_device_info->logmsg) || ($all_physical_device_info->avg))){
							$optical_occupied = $optical_occupied + 1;
						}
						else{
							echo "blackhole_optical";
						}

					}
					else if($all_physical_device_info->port_type == 'Electrical'){
						if(($all_physical_device_info->admin_status_translation == "down" || $all_physical_device_info->admin_status_translation == "disabled") && ($all_physical_device_info->oper_status_translation=="down" || $all_physical_device_info->oper_status_translation=="disabled")){
							$electrical_free += 1;
						}
						else if($all_physical_device_info->admin_status_translation == "up" && $all_physical_device_info->oper_status_translation=="up"){
							$electrical_occupied += 1;
						}
						else if($all_physical_device_info->admin_status_translation == "up" && $all_physical_device_info->oper_status_translation=="down" && ($all_physical_device_info->logmsg || $all_physical_device_info->avg)){
							$electrical_occupied += 1;
						}
						else{
							echo "blackhole_electrical";
						}

					}
					else{
						echo "port type:";
						echo $all_physical_device_info->port_type;
						echo "blackhole_total";
						echo "<br>";
					}
			}
		}

		////////////////////////////////////
		return view('Main.physical_info_table',compact('all_physical_device_infos','lat','long','radius','avg_val','min_val','max_val','ring_built_capacity','tocted','interface_wise_bookings','client','site_lat','site_long','electrical_free','electrical_occupied','optical_free','optical_occupied'));
	}

	public function bb_info(){
		$third_octet = Request::get('tocted');
		//dd($third_octet);
		$lat = Request::get('lat');
		$long = Request::get('long');
		$radius = Request::get('radius');


		// $bb_info_query = "SELECT erd.host_name,erd.resource_name,ed.average,ed.minimum,ed.maximum,COUNT(erd.host_name) as 'count' 
		// 				  FROM looking_glass.everest_resource_dump erd
		// 				  JOIN looking_glass.everest_data ed ON erd.host_name = ed.host_name AND erd.resource_name = ed.resource_name
		// 				  WHERE erd.ip_addr like '".$third_octet."%' AND erd.interface_ip like '".$third_octet."%'
		// 				  GROUP BY host_name";

		$bb_info_query = 	"SELECT nd.ip_addr,nd.lat,nd.lon,nd.address,nd.location,nd.upazilla,nd.district,nd.host_name,COUNT(rd.id) as 'count' 
							FROM looking_glass.`everest_resource_dump` rd
							LEFT JOIN looking_glass.everest_node_dump nd ON rd.host_name = nd.host_name
							WHERE rd.ip_addr like '".$third_octet."%' AND rd.interface_ip like '".$third_octet."%'
							AND (rd.resource_name like 'ge-%' OR rd.resource_name like 'xe-%' OR rd.resource_name like 'et-%' OR rd.resource_name like 'Port%' OR rd.resource_name like 'GigabitEthernet%')
							AND rd.poll_status != 'Disabled'
							GROUP BY host_name
							HAVING count > 2";



		

		return $bb_info_query;

		$bb_infos = \DB::select(\DB::raw($bb_info_query));

		// foreach($bb_infos as $bb){
		// 	$bb->port_category = "to be determined";
		// 	$bb->port_type = "to be determined";
		// 	$bb->admin_status = "to be determined";
		// 	$bb->oper_status = "to be determined";
		// 	$bb->if_descr_alis = "to be determined";



		// }

		//dd($bb_infos);
		//return "Success";


		
		// $bb_info_query = "SELECT `id`,`third_octet`,`bb_node`,`interface`,`connected_ring_count`,`neighbour_node`,`bb_node_id`,`port_category`,`port_type`,`admin_status`,`oper_status`,`if_description_alias`,`capacity_mbps`,`avg_mbps`,`min_mbps`,`max_mbps` FROM looking_glass.`neighbourship_table` WHERE `third_octet` = '$third_octet'";

		// $bb_info_query = "SELECT nt.`id`,nt.`third_octet`,nt.`bb_node`,nt.`interface`,nt.`connected_ring_count`,nt.`bb_node_id`,nt.`port_category`,nt.`port_type`,nt.`admin_status`,nt.`oper_status`,nt.`if_description_alias`, 
		// 						ot.snmpifspeed as `capacity_mbps`, 
		// 						ed.average as 'avg_mbps',ed.minimum as 'min_mbps',ed.maximum as 'max_mbps' 
		
		// FROM looking_glass.`neighbourship_table` nt 
		// LEFT JOIN looking_glass.`opennms` ot ON nt.bb_node = ot.site_name AND nt.interface = ot.snmpifname 
		// LEFT JOIN looking_glass.everest_data ed ON nt.bb_node = ed.host_name AND nt.interface = ed.resource_name 
		// WHERE nt.`third_octet` = '$third_octet'";

		//$bb_infos = \DB::select(\DB::raw($bb_info_query));

		return view('Main.bb_info_tableV2',compact('bb_infos','lat','long','radius'));
	}

	public function ring_info(){
		$third_octet = Request::get('tocted');

		$lat = Request::get('lat');
		$long = Request::get('long');
		$radius = Request::get('radius');
		$ring_info_query = "SELECT nd.ip_addr,nd.host_name,nd.lat as 'latitude',nd.lon as 'longitude',nd.division,nd.upazilla,nd.district,nd.location,nd.address,td.po_vol,td.client 
							FROM looking_glass.everest_node_dump nd 
							LEFT JOIN looking_glass.`telco_data` td ON nd.host_name = td.`node_name` where nd.ip_addr like '$third_octet.%'
							ORDER BY INET_ATON(nd.ip_addr)";

		//dd($ring_info_query);

		$ring_infos = \DB::select(\DB::raw($ring_info_query));

	
		return view('Main.ring_info_table',compact('ring_infos','lat','long','radius'));

	}


	public function planning_work(){
		// flood gate
		// $_SESSION["last_id_checked"] = 0;

		// return "initialized";
		
		$last_checked_id = $_SESSION["last_id_checked"];

		if($last_checked_id >= 436){
			return "All done";
		} 
		$get_latlong_list_query = "SELECT * FROM looking_glass.planning_latlong_list
								 WHERE id > $last_checked_id
								 LIMIT 50";
		$lat_longs = \DB::select(\DB::raw($get_latlong_list_query));


		
		$id = 1;
		$total_site_lists = array();
		foreach ($lat_longs as $item) {
			$from_lat = $item->lat;
			$from_long = $item->lon;

			$select_site_list_query = "SELECT * FROM looking_glass.planning_site_db";
			$site_list = \DB::select(\DB::raw($select_site_list_query));
			foreach ($site_list as $site) {
				$to_lat = $site->lat;
		    	$to_long = $site->lon;

		    	//dd($from_long);


		    	$distance = $this->get_distance($from_lat, $from_long,$to_lat,$to_long);

		    	//echo "1st calculation done";
		    	$total_site_lists[$site->site_id] = $distance;
			}
			//echo "<br> all calculation done";
			asort($total_site_lists);
			
			foreach ($total_site_lists as $key => $value) {
				# code...
				$got_site_id = $key; 
				$got_distance = $value;  
				break;
			}

			//echo "<br> got site_id and distence";
			$update_query = "UPDATE looking_glass.planning_latlong_list 
							SET distence = '".$got_distance."',root_site='".$got_site_id."'
							WHERE id=$item->id";
			$site_list = \DB::update(\DB::raw($update_query));

			//echo "<br> update done";			
			$_SESSION["last_id_checked"] = $item->id;

			echo $id;
			echo "<br>";
			$id = $id +1;
		}



		//print_r($lat_longs);

		return "success";
	}

	public function update_alarm_table(){

		

		
		return "Success";
	}

	

 //-------------------------------- Utility Functions -----------------------------------------
     public function get_distance($latitudeFrom, $longitudeFrom,$latitudeTo,$longitudeTo){
	    
	    $theta = $longitudeFrom - $longitudeTo;
	    $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
	    $dist = acos($dist);
	    $dist = rad2deg($dist);
	    $miles = $dist * 60 * 1.1515;
	    return ($miles * 1.609344);
	    
	}
}
