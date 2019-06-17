<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class BB_Data_Update_Controller extends Controller
{
    //
    public function index(Request $request){
        $query = "SELECT * FROM looking_glass.bb_tbl";
        $data = \DB::select(\DB::raw($query));
    	return view('backbone_data_update.index',compact('data'));
        //return "success";
    }

    public function insert_bb_data_view(){
        $host_name_query = "SELECT DISTINCT host_name FROM looking_glass.everest_node_dump ORDER BY host_name";
        $host_names = \DB::select(\DB::raw($host_name_query));

        $bandwidth_query = "SELECT DISTINCT bandwidth_polled FROM looking_glass.`everest_resource_dump` ORDER BY bandwidth_polled";
        $bandwidth_data = \DB::select(\DB::raw($bandwidth_query));

        return view('backbone_data_update.insert',compact('host_names','bandwidth_data'));
    }

    public function get_node_resources_list(Request $request){
        $host_name = $request->host_name;

        $query = "SELECT DISTINCT resource_name FROM `everest_resource_dump` WHERE host_name = '$host_name'";
        $resource_data = \DB::select(\DB::raw($query));

        $html = "<option>Please choose</option>";
        foreach($resource_data as $row){
            $html .= "<option value='$row->resource_name'>$row->resource_name</option>";
        }

        return $html;
    }

    public function get_district_division(Request $request){
        $host_name = $request->host_name;

        $query = "SELECT division,district,ip_addr FROM looking_glass.`everest_node_dump` WHERE host_name = '$host_name'";
        $data = \DB::select(\DB::raw($query));

        $json_array = array();

        foreach($data as $row){
            $json_array["district"] = $row->district;
            $json_array["division"] = $row->division;
            $json_array["ip_addr"] = $row->ip_addr;
        }

        $json = json_encode($json_array);
        return $json;
    }

    public function hopwise_link(){
        $host_name_query = "SELECT DISTINCT host_name FROM looking_glass.everest_node_dump ORDER BY host_name";
        $host_names = \DB::select(\DB::raw($host_name_query));

        return view('backbone_data_update.hopwise_link',compact('host_names'));
    }

    public function districtwise_link(){
        $district_query = "SELECT DISTINCT district_name FROM looking_glass.district_table ORDER BY district_name";
        $district_names = \DB::select(\DB::raw($district_query));
        return view('backbone_data_update.districtwise_link',compact('district_names'));
    }

    public function insert_bb_data(Request $request){
        $source = $request->source;
        $resource = $request->resource;
        $ip_address = $request->ip_address;
        $destination = $request->destination;
        $destination_resource = $request->destination_resource;
        $division = $request->division;
        $district = $request->district;
        $link_name = $request->link_name;
        $built_capacity = $request->built_capacity;
        $path_status = $request->path_status;
        $hopwise_linkname = $request->hopwise_linkname;
        $districtwise_linkname = $request->districtwise_linkname;

        $insert = DB::table('looking_glass.bb_tbl')->insertGetId(
            [   
                'source' => $source,
                'resource' => $resource, 
                'ip_address' => $ip_address,
                'destination' => $destination,
                'destination_resource' => $destination_resource,
                'division' => $division,
                'district' => $district,
                'link_name' => $link_name,
                'built_capacity' => $built_capacity,
                'path_status' => $path_status,
                'hopwise_link_name' => $hopwise_linkname,
                'districtwise_link_name' => $districtwise_linkname
                  
            ]
        );

        return redirect('backbone_data');
    }

    public function bb_destroy(Request $request){
        $id = $request->id;

        $get_bb_query = "DELETE FROM looking_glass.bb_tbl WHERE row_id = $id";
        $bb_data = \DB::delete(\DB::raw($get_bb_query));
        return redirect('backbone_data');
    }

    public function bb_update_view(Request $request){
        $row_id = $request->id;

        $host_name_query = "SELECT DISTINCT host_name FROM looking_glass.everest_node_dump ORDER BY host_name";
        $host_names = \DB::select(\DB::raw($host_name_query));

        $bandwidth_query = "SELECT DISTINCT bandwidth_polled FROM looking_glass.`everest_resource_dump` ORDER BY bandwidth_polled";
        $bandwidth_data = \DB::select(\DB::raw($bandwidth_query));

        $get_bb_query = "SELECT * FROM looking_glass.bb_tbl WHERE row_id = $row_id";
        $bb_data = \DB::select(\DB::raw($get_bb_query));



        return view('backbone_data_update.update',compact('host_names','bandwidth_data','bb_data'));
    }

    public function change_host_name(Request $request){
        $host_name = $request->host_name;

        return $host_name;
    }

    public function change_resource(Request $request){
        $resource = $request->resource;
        $resource_name = str_replace("slash","/",$resource);
        $host_name = $request->host_name;

        $resource_query = "SELECT * FROM looking_glass.everest_resource_dump WHERE host_name = '$host_name'";
        $resource_data = \DB::select(\DB::raw($resource_query));

        //dd($resource_data);
        return view('backbone_data_update.change_resource_name',compact('resource_data','resource_name'));
    }

    public function update_bb_data(Request $request){
        $row_id = $request->row_id;
        $source = $request->source;
        $resource = $request->resource;
        $ip_address = $request->ip_address;
        $destination = $request->destination;
        $destination_resource = $request->destination_resource;
        $division = $request->division;
        $district = $request->district;
        $link_name = $request->link_name;
        $built_capacity = $request->built_capacity;
        $path_status = $request->path_status;
        $hopwise_linkname = $request->hopwise_linkname;
        $districtwise_linkname = $request->districtwise_linkname;

        $query = "UPDATE looking_glass.bb_tbl 
                    SET source = '$source',
                        resource = '$resource',
                        ip_address = '$ip_address',
                        destination = '$destination',
                        destination_resource = '$destination_resource',
                        division = '$division',
                        district = '$district',
                        link_name = '$link_name',
                        built_capacity = '$built_capacity',
                        path_status = '$path_status',
                        hopwise_link_name = '$hopwise_linkname',
                        districtwise_link_name = '$districtwise_linkname'
                    WHERE row_id = '$row_id'";
        
        $bb_data = \DB::update(\DB::raw($query));


        return redirect('backbone_data');

    }
}
