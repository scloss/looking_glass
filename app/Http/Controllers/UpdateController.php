<?php

namespace App\Http\Controllers;
use File;
use DB;
use Input;
use DateTime;
use Zipper;
//use Request;
use Illuminate\Http\Request;
use App\InfoTable;
session_start();
//use Excel;
class UpdateController extends Controller
{
   public function data_update_view(){
       
       $info_lists = InfoTable::all();
       return view('Update.data_update_view',compact('info_lists'));
   }

   public function update_db_view(){
       return view('Update_DB.update_db');
   }
  
   public function update_db_post(Request $request){
       date_default_timezone_set('Asia/Dhaka');
       $time = date('Y-m-d H:i:s');
       //return $time;


       $table_name = $request->table_name;
       echo $table_name;
       echo "<br>";
       if($table_name == "Resource Dump"){
           if($request->hasFile('file')){
               $path = $request->file->getRealPath();
               $file = fopen($path,"r");
               $query = "INSERT INTO `everest_resource_dump` (`id`, `host_name`, `ip_addr`, `resource_name`, `resource_descr`, `resource_alias`, `interface_ip`, `bandwidth_polled`, `bandwidth_configured`, `link_name`, `link_capacity_mbps`, `poll_status`, `created_time`) VALUES";
               $id = 0;
               while(!feof($file)){
                   $row = fgetcsv($file);
                   // echo $row[0];
                   // echo "<br>";
                   $host_name = $row[0];
                   $ip_address = $row[1];
                   $resource_name = $row[2];
                   $resource_description = $row[3];
                   $resource_alis = $row[4];
                   $interface_ip_address = $row[5];
                   $bandwidth_polled = $row[6];
                   $bandwidth_configured = $row[7];
                   $link_name = $row[8];
                   $link_capacity = $row[9];
                   $tags = $row[10];
                   $poll_status = $row[11];
  
                   if($id != 0){
                       $query .= "('$id','$host_name','$ip_address','$resource_name','$resource_description','$resource_alis','$interface_ip_address','$bandwidth_polled','$bandwidth_configured','$link_name','$link_capacity','$poll_status','$time'),";
                   }
                   $id = $id+1;
               }
               fclose($file);
               $query = rtrim($query,",");
               \DB::insert(\DB::raw($query));
  
               return "Success fully Inserted";
               //echo $query;
  
           }else{
               return "file not found";
           }
       }
       else if($table_name == "Alarm Dump"){
           if($request->hasFile('file')){
               $path = $request->file->getRealPath();
               $file = fopen($path,"r");
               $query = "INSERT INTO `everest_alarm_dump` (`id`, `alarm_time`, `ip_addr`, `host_name`, `resource_name`, `description`, `created_time`) VALUES";
               $id = 0;
               while(!feof($file)){
                   $row = fgetcsv($file);
                   $time = $row[0];
                   $severity = $row[1];
                   $division = $row[2];
                   $district = $row[3];
                   $upzilla = $row[4];
                   $host_name = $row[5];
                   $ip_addr = $row[6];
                   $resource = $row[7];
                   $alias = $row[8];
                   $type = $row[9];
                   $threshold = $row[10];
                   $alarm_value = $row[11];
                   $message = $row[12];
                   $bandwidth = $row[13];
                   $premium = $row[14];
                   $ack_by = $row[15];
                   $ack_comment = $row[16];
                   $repeat_count = $row[17];
                   $first_event_time = $row[18];
                  
                   if($id != 0){
                       $query .= "('$id', '$first_event_time', '$ip_addr', '$host_name', '$resource', '$message', '$time'),";
                   }
                   $id = $id+1;
               }
               fclose($file);
               $query = rtrim($query,",");
               \DB::insert(\DB::raw($query));
  
               return "Success fully Inserted";

       }

      
   }
   else if($table_name == "Throughput Dump"){
           //echo "if reached";
           if($request->hasFile('file')){
               $path = $request->file->getRealPath();
               $file = fopen($path,"r");
               $query = "INSERT INTO `everest_throughput_dump` (`serial`, `host_name`, `resource_name`, `resource_alias`, `average`, `minimum`, `maximum`, `ninty_fifth_percentile`, `created_time`) VALUES";
               $id = 0;
               while(!feof($file)){
                   $row = fgetcsv($file);

                   $host_name = $row[0];
                   $resource = $row[1];
                   $description = $row[2];
                   $model = $row[3];
                   $ip_addr = $row[4];
                   $alias = $row[5];
                   $division = $row[6];
                   $bandwidth = $row[7];
                   $wan_ip = $row[8];
                   $district = $row[9];
                   $resource = $row[10];
                   $avg = $row[11];
                   $min = $row[12];
                   $max = $row[13];
                   $ninty_five_percentile = $row[14];
                  
                   if($id != 0){
                       $query .= "('$id', '$host_name', '$resource', '$alias', '$avg', '$min', '$max', '$ninty_five_percentile','$time'),";
                   }
                   $id = $id+1;
               }
               fclose($file);
               $query = rtrim($query,",");
            //    echo "<br/>";
            //    echo $query;
            //    echo "<br/>";
               \DB::insert(\DB::raw($query));
               return "Success fully Inserted";
       }  
   }
   else if($table_name == "Admin Oper Dump"){
       if($request->hasFile('admin')){
           $path = $request->admin->getRealPath();
           $file = fopen($path,"r");
           $query = "INSERT INTO `everest_admin_dump` (`id`, `host_name`, `resource_name`, `admin`) VALUES";
           $id = 0;
           while(!feof($file)){
               $row = fgetcsv($file);

               $host_name = $row[0];
               $resource = $row[1];
               $ip_addr = $row[2];
               $alias = $row[3];
               $resource = $row[4];
               $avg = $row[5];
               $min = $row[6];
               $max = $row[7];
               $ninty_five_percentile = $row[8];
              
               if($id != 0){
                   $query .= "('$id', '$host_name', '$resource', '$max'),";
               }
               $id = $id+1;
           }
           fclose($file);
           $query = rtrim($query,",");
           \DB::insert(\DB::raw($query));
           echo "Success fully Inserted admin";
           echo "<br/>";
       }

       if($request->hasFile('oper')){
           $path = $request->oper->getRealPath();
           $file = fopen($path,"r");
           $query = "INSERT INTO `everest_oper_dump` (`id`, `host_name`, `resource_name`, `oper`) VALUES";
           $id = 0;
           while(!feof($file)){
               $row = fgetcsv($file);

               $host_name = $row[0];
               $resource = $row[1];
               $ip_addr = $row[2];
               $alias = $row[3];
               $resource = $row[4];
               $avg = $row[5];
               $min = $row[6];
               $max = $row[7];
               $ninty_five_percentile = $row[8];
              
               if($id != 0){
                   $query .= "('$id', '$host_name', '$resource', '$max'),";
               }
               $id = $id+1;
           }
           fclose($file);
           $query = rtrim($query,",");
           \DB::insert(\DB::raw($query));
           echo "Success fully Inserted oper";
           echo "<br/>";
       }

       $join_query =   "SELECT ead.host_name,ead.resource_name,ead.admin,eod.oper 
                       FROM looking_glass.everest_admin_dump ead
                       JOIN looking_glass.everest_oper_dump eod ON ead.host_name = eod.host_name AND ead.resource_name = eod.resource_name";

       $everest_admin_oper_report = \DB::select(\DB::raw($join_query));
       $insert_query = "INSERT INTO `everest_admin_oper_status_dump` (`id`, `host_name`, `resource_name`, `admin_status`, `oper_status`, `created_time`) VALUES";
       $id = 1;

       foreach($everest_admin_oper_report as $report){
           $insert_query .= "('$id','$report->host_name','$report->resource_name','$report->admin','$report->oper','$time'),";
           $id += 1;
       }
       $insert_query = rtrim($insert_query,",");
       \DB::insert(\DB::raw($insert_query));

       // echo $insert_query;
       // echo "<br/>";


       return "Successfully updated admin oper table";

   }
   else if($table_name == "Node Dump"){
       if($request->hasFile('disabled_node')){
           $path = $request->disabled_node->getRealPath();
           $file = fopen($path,"r");
           $query = "INSERT INTO `everest_disabled_node` (`id`, `host_name`, `ip_addr`) VALUES";
           $id = 0;
           while(!feof($file)){
               $row = fgetcsv($file);
              
               $ip_addr = $row[0];
               $host_name = $row[1];

               if($id != 0){
                   $query .= "('$id', '$host_name', '$ip_addr'),";
               }
               $id = $id+1;
           }
           fclose($file);
           $query = rtrim($query,",");
           \DB::insert(\DB::raw($query));
           echo "Success fully Inserted disabled nodes";
           echo "<br/>";
       }
       if($request->hasFile('node_dump')){
           $path = $request->node_dump->getRealPath();
           $file = fopen($path,"r");
           $query = "INSERT INTO `everest_node_dump_dummy` (`id`, `descr`, `sysdescr`, `lat`, `lon`, `series`, `model`, `device_type`, `owner`, `country`, `location`, `domain`, `vendor`, `ip_addr`, `category`, `address`, `alias`, `division`, `upazilla`, `host_name`, `client`, `district`, `created_time`) VALUES";
           $id = 0;
           while(!feof($file)){
               $row = fgetcsv($file);

            //    $sno=$row[0];
            //    $description=$row[1];
            //    $device_group=$row[2];
            //    $nvram_size=$row[3];
            //    $flash_size=$row[4];
            //    $mother_board_serial_number=$row[5];
            //    $mother_board_revision_number=$row[6];
            //    $configuration_register=$row[7];
            //    $power_supply_part_no=$row[8];
            //    $power_supply_revision_no=$row[9];
            //    $image_file_name=$row[10];
            //    $end_of_life=$row[11];
            //    $end_of_support=$row[12];
            //    $sysobjectid=$row[13];
            //    $syscontact=$row[14];
            //    $sysdescr=$row[15];
            //    $latitude=$row[16];
            //    $longitude=$row[17];
            //    $x=$row[18];
            //    $y=$row[19];
            //    $business_hour_profile=$row[20];
            //    $series=$row[21];
            //    $model=$row[22];
            //    $device_type=$row[23];
            //    $owner=$row[24];
            //    $country=$row[25];
            //    $location=$row[26];
            //    $domain=$row[27];
            //    $product_type=$row[28];
            //    $service_type=$row[29];
            //    $priority=$row[30];
            //    $serial_number=$row[31];
            //    $operating_system=$row[32];
            //    $os_version=$row[33];
            //    $mac_address=$row[34];
            //    $memory_size=$row[35];
            //    $processor=$row[36];
            //    $disk_size=$row[37];
            //    $email_id=$row[38];
            //    $contact_number=$row[39];
            //    $contact_person=$row[40];
            //    $vendor=$row[41];
            //    $ip_address=$row[42];
            //    $category=$row[43];
            //    $address=$row[44];
            //    $alias=$row[45];
            //    $syslocation=$row[46];
            //    $division=$row[47];
            //    $upazilla=$row[48];
            //    $host_name=$row[49];
            //    $technology=$row[50];
            //    $resource_product_type=$row[51];
            //    $node_tag=$row[52];
            //    $service_tag=$row[53];
            //    $asset_id=$row[54];
            //    $bandwidth=$row[55];
            //    $client=$row[56];
            //    $wan_ip=$row[57];
            //    $fiber_distance=$row[58];
            //    $resource_tag=$row[59];
            //    $district=$row[60];
            //    $resource_category=$row[61];
            //    $resource_service_type=$row[62];
            //    $resource_type=$row[63];
            //    $resource=$row[64];
            //    $poller=$row[65];
            //    $comment_tags=$row[66];
            //    $subcenter=$row[67];
            //    $contact_person=$row[68];
            //    $email_id=$row[69];
            //    $contact_number=$row[70];
            //    $service_tag=$row[71];
            //    $tags=$row[72];

            $sno=$row[0];
            $description=$row[1];
            $alias=$row[2];
            $device_group=$row[3];
            $nvram_size=$row[4];
            $flash_size=$row[5];
            $mother_board_serial_number=$row[6];
            $mother_board_revision_number=$row[7];
            $configuration_register=$row[8];
            $power_supply_part_no=$row[9];
            $power_supply_revision_no=$row[10];
            $image_file_name=$row[11];
            $end_of_life=$row[12];
            $end_of_support=$row[13];
            $sysobjectid=$row[14];
            $syscontact=$row[15];
            $sysdescr=$row[16];
            $latitude=$row[17];
            $longitude=$row[18];
            $x=$row[19];
            $y=$row[20];
            $business_hour_profile=$row[21];
            $series=$row[22];
            $model=$row[23];
            $device_type=$row[24];
            $owner=$row[25];
            $country=$row[26];
            $location=$row[27];
            $domain=$row[28];
            $product_type=$row[29];
            $service_type=$row[30];
            $priority=$row[31];
            $serial_number=$row[32];
            $operating_system=$row[33];
            $os_version=$row[34];
            $mac_address=$row[35];
            $memory_size=$row[36];
            $processor=$row[37];
            $disk_size=$row[38];
            $email_id=$row[39];
            $contact_number=$row[40];
            $contact_person=$row[41];
            $vendor=$row[42];
            $ip_address=$row[43];
            $category=$row[44];
            $address=$row[45];
            $syslocation=$row[46];
            $division=$row[47];
            $upazilla=$row[48];
            $host_name=$row[49];
            $technology=$row[50];
            $resource_product_type=$row[51];
            $node_tag=$row[52];
            $service_tag=$row[53];
            $asset_id=$row[54];
            $bandwidth=$row[55];
            $client=$row[56];
            $wan_ip=$row[57];
            $fiber_distance=$row[58];
            $resource_tag=$row[59];
            $district=$row[60];
            $resource_category=$row[61];
            $resource_service_type=$row[62];
            $resource_type=$row[63];
            $resource=$row[64];
            $poller=$row[65];
            $comment_tags=$row[66];
            $subcenter=$row[67];
            $contact_person=$row[68];
            $email_id=$row[69];
            $contact_number=$row[70];
            $service_tag=$row[71];
            $tags=$row[72];

              
               if($id != 0){
                   $query .= "('$id', '$description', '$sysdescr', '$latitude', '$longitude', '$series', '$model', '$device_type', '$owner', '$country', '$location', '$domain', '$vendor', '$ip_address', '$category', '$address', '$alias', '$division', '$upazilla', '$host_name', '$client', '$district', '$time'),";
               }
               $id = $id+1;
           }
           fclose($file);
           $query = rtrim($query,",");
           \DB::insert(\DB::raw($query));
           echo "Success fully Inserted node_dump";
           echo "<br/>";
       }

       $select_disabled_node = "SELECT * FROM looking_glass.everest_disabled_node";
       $disabled_node_list = \DB::select(\DB::raw($select_disabled_node));
       $delete_query = "DELETE FROM looking_glass.everest_node_dump_dummy WHERE (host_name,ip_addr) NOT IN (";
       foreach($disabled_node_list as $node){
           if($node->host_name != "" && $node->ip_addr != ""){
               $delete_query .= "('$node->host_name','$node->ip_addr'),";
           }
       }
       $delete_query = rtrim($delete_query,",");
       $delete_query .= ")";
       \DB::delete(\DB::raw($delete_query));
       echo "Successfully Deleted the disabled nodes";
       echo "<br/>";

       $insert_into_select = "INSERT INTO looking_glass.everest_node_dump
                               SELECT *
                               FROM everest_node_dump_dummy
                               WHERE ip_addr like  '10.%'";
       $insert_query = \DB::insert(\DB::raw($insert_into_select));
       echo "Successfully inserted nttn nodes";
       echo "<br/>";

       $insert_into_select = "INSERT INTO looking_glass.everest_node_dump
                               SELECT *
                               FROM everest_node_dump_dummy
                               WHERE ip_addr like  '172.30.%'";
       $insert_query = \DB::insert(\DB::raw($insert_into_select));
       echo "Successfully inserted nttn switches";
       echo "<br/>";
   }
   else {
       return "Invalid table";
   }
}

   function readCSV($csvFile){
       $file_handle = fopen($csvFile, 'r');
       while (!feof($file_handle) ) {
           $line_of_text[] = fgetcsv($file_handle, 1024);
       }
       fclose($file_handle);
       return $line_of_text;
   }
}


