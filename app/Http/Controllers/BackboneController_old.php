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


class BackboneController extends Controller
{
   


    public function findActiveBB(){
		$query = "SELECT concat(SUBSTRING_INDEX(a.`hopwise_link_name`,'|',1),'|',
SUBSTRING(a.hopwise_link_name,LENGTH(a.hopwise_link_name) - LOCATE('|',REVERSE(a.hopwise_link_name)) + 2)) as link_name, SUBSTRING_INDEX(a.`hopwise_link_name`,'|',1) AS source,
SUBSTRING(a.hopwise_link_name,LENGTH(a.hopwise_link_name) - LOCATE('|',REVERSE(a.hopwise_link_name)) + 2) as destination,SUBSTRING_INDEX(a.districtwise_link_name,'-',1) source_district,SUBSTRING_INDEX(a.districtwise_link_name,'-',-1) destination_district,a.`districtwise_link_name`,a.`hopwise_link_name`,a.`path_status`,max(CAST(SUBSTRING_INDEX(c.ninty_fifth_percentile,' ',1) AS DECIMAL(10,3))) utilization, a.built_capacity, b.protection_exist,ROUND ((LENGTH(a.hopwise_link_name)-LENGTH( REPLACE ( a.hopwise_link_name, '|', '') ) ) / LENGTH('|') )-1 AS hop_count
FROM looking_glass.bb_tbl as a
left JOIN
    (SELECT `districtwise_link_name`,
		CASE
    		WHEN LOCATE('Protection',GROUP_CONCAT(path_status)) != 0 THEN 'yes'
    		ELSE 'no'
		END as protection_exist 
	FROM looking_glass.bb_tbl
	group by `districtwise_link_name`
	having protection_exist='yes'
    ) as b
on LOCATE(a.districtwise_link_name,b.districtwise_link_name) != 0
left join looking_glass.everest_throughput_dump c
on a.source = c.host_name and a.resource = c.resource_name
where a.`path_status` = 'Active' 
group by `districtwise_link_name`
order by row_id";


		$array_bb_active = \DB::select(\DB::raw($query));
		return $array_bb_active;
		
	}

	public function findActiveBBAll(){
		$query = "SELECT a.*,SUBSTRING_INDEX(a.districtwise_link_name,'-',1) source_district,SUBSTRING_INDEX(a.districtwise_link_name,'-',-1) destination_district,CAST(SUBSTRING_INDEX(b.ninty_fifth_percentile,' ',1) AS DECIMAL(10,3)) as utilization from looking_glass.bb_tbl as a
					left join looking_glass.everest_throughput_dump as b
					on a.source = b.host_name and a.resource = b.resource_name
					 where path_status='Active'";


		$array_bb_active_all = \DB::select(\DB::raw($query));
		return $array_bb_active_all;
		
	}


	public function findActiveBBWithUtilization(){


	$array_bb_active_utlization = array();
	$whereClause = "0";
	$hopwise_link_name = "";
	$districtwise_link_name = "";
	$array_bb_active = $this->findActiveBB();
	//return $array_bb_active;
	foreach ($array_bb_active as $active_bb) {

		$hopwise_link_name = $active_bb->hopwise_link_name;
		$districtwise_link_name = $active_bb->districtwise_link_name;

		if ($active_bb->hop_count>0) {
			$whereClause .= " or ((a.source = SUBSTRING_INDEX(a.`hopwise_link_name`,'|',1) or a.destination = SUBSTRING(a.hopwise_link_name,LENGTH(a.hopwise_link_name) - LOCATE('|',REVERSE(a.hopwise_link_name)) + 2)) and a.path_status = 'Active' and a.`hopwise_link_name` = '$hopwise_link_name' and a.`districtwise_link_name` = '$districtwise_link_name') ";

		}
	}
    $selectQuery = "SELECT a.`districtwise_link_name`, SUM( CAST( SUBSTRING_INDEX(b.ninty_fifth_percentile, ' ', 1) AS DECIMAL(10, 3) ) ) sum_utilization FROM looking_glass.bb_tbl a left join looking_glass.everest_throughput_dump b on a.source = b.host_name AND a.resource = b.resource_name WHERE ".$whereClause;
    //echo 'rp'.$selectQuery.'rp';die();
    $array_bb_active_utlization = \DB::select(\DB::raw($selectQuery));
	//return $array_bb_active_utlization;
	foreach ($array_bb_active_utlization as $active_bb_utilization) {
		foreach ($array_bb_active as $key => $active_bb) {
			if ($active_bb_utilization->districtwise_link_name == $active_bb->districtwise_link_name) 
			{
				$array_bb_active[$key]->utilization = $active_bb_utilization->sum_utilization;
				//echo 'melloo'.$key;
			}
		}
	}
	return $array_bb_active;
		
	} 


	public function findProtectionPerActiveBB()
	{
		$selectQuery = "SELECT a.*,SUBSTRING_INDEX(a.districtwise_link_name,'-',1) source_district,SUBSTRING_INDEX(a.districtwise_link_name,'-',-1) destination_district,CAST(SUBSTRING_INDEX(c.ninty_fifth_percentile,' ',1) AS DECIMAL(10,3)) utilization,b.link_name active_bb FROM looking_glass.bb_tbl as a
			left join looking_glass.everest_throughput_dump as c 
			on a.source = c.host_name and a.resource = c.resource_name
			left JOIN
			(SELECT concat(SUBSTRING_INDEX(`hopwise_link_name`,'|',1),'|',
			SUBSTRING(hopwise_link_name,LENGTH(hopwise_link_name) - LOCATE('|',REVERSE(hopwise_link_name)) + 2)) as link_name,source,SUBSTRING_INDEX(districtwise_link_name,'-',1) source_district,destination,SUBSTRING_INDEX(districtwise_link_name,'-',-1) destination_district,`districtwise_link_name`,`path_status` FROM looking_glass.bb_tbl where `path_status` = 'Active' group by `districtwise_link_name`) as b
			on a.districtwise_link_name = b.districtwise_link_name
			WHERE a.`path_status` !='Active' and SUBSTRING_INDEX(a.districtwise_link_name,'-',1) != SUBSTRING_INDEX(a.districtwise_link_name,'-',-1)";
  //echo 'hello'.$selectQuery;


		  $array_bb_protection = \DB::select(\DB::raw($selectQuery));
		  return $array_bb_protection;
		
	}


	public function findProtectionPerActiveBBWithCommonPath()
	{
		$selectQuery = "select looking_glass.bb_tbl.*,SUBSTRING_INDEX(looking_glass.bb_tbl.districtwise_link_name,'-',1) source_district,SUBSTRING_INDEX(looking_glass.bb_tbl.districtwise_link_name,'-',-1) destination_district,CAST(SUBSTRING_INDEX(looking_glass.everest_throughput_dump.ninty_fifth_percentile,' ',1) AS DECIMAL(10,3)) utilization from looking_glass.bb_tbl 
		  left join looking_glass.everest_throughput_dump
		  on source = host_name and resource = resource_name
		  where districtwise_link_name like '%|%'";
		  //echo $selectQuery;
		  $array_common_protection = \DB::select(\DB::raw($selectQuery));

		  $array_bb_protection = $this->findProtectionPerActiveBB();
		  $array_bb_active = $this->findActiveBBWithUtilization();

		  foreach ($array_common_protection as $bb_protection_common) 
		  {
			  foreach ($array_bb_active as $bb_active) 
			  {
					if (strpos($bb_protection_common->districtwise_link_name,$bb_active->districtwise_link_name) !== FALSE) 
					{
						$bb_protection_common->active_bb=$bb_active->link_name;
						array_push($array_bb_protection, $bb_protection_common);
					}
		      }
	      }
		  return $array_bb_protection;	  	
	} 


	public function findActiveBBWithProtectionUtilization()
	{

		$array_bb_protection=$this->findProtectionPerActiveBBWithCommonPath();
		$array_bb_active=$this->findActiveBBWithUtilization();
		//print_r($array_bb_protection); die();
		//print_r($array_bb_active); die();
		$array_bb_protection_capacity = array();

		foreach ($array_bb_active as $key => $bb_active) 
		{
			if ($bb_active->protection_exist == 'yes') 
			{
				$bb_protection_capacity_sum = 0;
				$bb_protection_built_capacity_minimum = 100000000000;
				
				$temp_link_name_s = '';
				$temp_link_name_d = '';
				foreach ($array_bb_protection as $bb_protection) 
				{
					

					if ($bb_protection->active_bb==$bb_active->link_name && ($bb_active->source==$bb_protection->source || $bb_active->source==$bb_protection->destination || $bb_active->destination==$bb_protection->source || $bb_active->destination==$bb_protection->destination) && $bb_protection->source!=$temp_link_name_d && $bb_protection->destination!=$temp_link_name_s && $bb_protection->source!=$temp_link_name_s && $bb_protection->destination!=$temp_link_name_d) 
					{
							
							$bb_protection_capacity_sum = $bb_protection_capacity_sum + $bb_protection->utilization;
							if ($bb_protection_built_capacity_minimum>$bb_protection->built_capacity) 
							{
								$bb_protection_built_capacity_minimum=$bb_protection->built_capacity;
							}
							$temp_link_name_s = $bb_protection->source;
							$temp_link_name_d = $bb_protection->destination;
							/*if($bb_protection->active_bb=='SYUSHC01JR01|SNSDR02-AGG')
							{
						echo $bb_active->link_name.'cap: '.$bb_protection_capacity_sum.'---';
						echo 'built: '.$bb_protection_built_capacity_minimum.'---'.$bb_protection->source.$bb_protection->resource.'---'.$temp_link_name_s.'----'.$temp_link_name_d.'<br>';
							}*/
					}
				}
				array_push($array_bb_protection_capacity,array('active_bb'=>$bb_active->link_name,'bb_active_districtwise'=>$bb_active->districtwise_link_name,'protection_bw_sum'=>$bb_protection_capacity_sum,'protection_built_capacity_min'=>$bb_protection_built_capacity_minimum));

				$array_bb_active[$key]->protection_utilization = $bb_protection_capacity_sum;
				$array_bb_active[$key]->protection_built_capacity = $bb_protection_built_capacity_minimum;
				//echo $bb_active->districtwise_link_name.'*****'.$bb_protection_capacity_sum.'###'.$bb_protection_built_capacity_minimum;	
			}
		}
		//die();
		return $array_bb_active;
	} 

	public function findActiveBBWithSellableFreeCapacity()
	{
		$array_bb_protection=$this->findProtectionPerActiveBBWithCommonPath();
		$array_bb_active=$this->findActiveBBWithProtectionUtilization();

		//print_r($array_bb_active);die();

		foreach ($array_bb_active as $key => $bb_active) 
		{
			if($bb_active->protection_exist=='yes')
			{
				if (0.8*$bb_active->built_capacity-($bb_active->utilization+$bb_active->protection_utilization)<0 || 0.8*$bb_active->protection_built_capacity-($bb_active->utilization+$bb_active->protection_utilization)<0) 
				{
					$array_bb_active[$key]->sellable = 'No';
				}
				else{
					$array_bb_active[$key]->sellable = 'Yes';

					$array_bb_active[$key]->free_capacity = 0.8*(min($bb_active->built_capacity,$bb_active->protection_built_capacity)) - ($bb_active->utilization + $bb_active->protection_utilization);
					/*if ($bb_active->built_capacity-$bb_active->utilization<$bb_active->protection_built_capacity-$bb_active->protection_utilization) 
					{
						$array_bb_active[$key]->free_capacity = $bb_active->built_capacity-$bb_active->utilization;
					}
					else{
						$array_bb_active[$key]->free_capacity = $bb_active->protection_built_capacity-$bb_active->protection_utilization;	
					}*/
					
				}
			}
			else
			{
				if (0.8*($bb_active->built_capacity)-$bb_active->utilization<0) 
				{
					$array_bb_active[$key]->sellable = 'No';
				}
				else
				{
					$array_bb_active[$key]->sellable = 'Yes';
					$array_bb_active[$key]->free_capacity = 0.8*($bb_active->built_capacity)-$bb_active->utilization;
				}
			}
		}
		return $array_bb_active;
		
	}

	public function findActiveBBWithSellableFreeCapacityView()
	{
		$array_bb_active=$this->findActiveBBWithSellableFreeCapacity();
		$this->createJson();		
		return view('Backbone.Backbone_view',compact('array_bb_active','array_bb_active'));
		
	}

/////////////////topology graphing/////////////////


	/*public function findNodes()
	{
		$array_bb_active=$this->findActiveBBWithSellableFreeCapacity();
		$array_bb_protection=$this->findProtectionPerActiveBBWithCommonPath();
		$nodes = array();
		foreach ($array_bb_active as $bb_active) {
			array_push($nodes, $bb_active->source);
			array_push($nodes, $bb_active->destination);
		}

		foreach ($array_bb_protection as $bb_protection) {
			array_push($nodes, $bb_protection->source);
			array_push($nodes, $bb_protection->destination);
		}
		$nodes = array_unique($nodes);
		$finalNodes = array();
		foreach ($nodes as $key => $node) {
			array_push($finalNodes, array("id"=>"$node","name"=>array("$node"),"labels"=>array("Router"),"properties"=>array("userId"=>"eisman")));
		}
		return $finalNodes;
	}*/



	public function findNodes()
	{
		$array_bb_active=$this->findActiveBBAll();
		$array_bb_protection=$this->findProtectionPerActiveBBWithCommonPath();
		
		//return $array_bb_protection;

		$query = 	"SELECT DISTINCT bbt1.source as node ,nd.lat,nd.lon,nd.district,nd.upazilla FROM looking_glass.bb_tbl bbt1
					LEFT JOIN looking_glass.everest_node_dump nd ON nd.host_name = bbt1.source 
					union 
				  	SELECT DISTINCT bbt2.destination,nd.lat,nd.lon,nd.district,nd.upazilla as node FROM looking_glass.bb_tbl bbt2
				  	LEFT JOIN looking_glass.everest_node_dump nd ON nd.host_name = bbt2.destination";


		$nodes = \DB::select(\DB::raw($query));
	
		$finalNodes = array();

		foreach ($nodes as $node) {
			$partOfBB = '';
			$linkName='';
			foreach ($array_bb_active as $bb_active) {
				if (($node->node==$bb_active->source || $node->node==$bb_active->destination) && strpos($linkName, str_replace(' ','',$bb_active->districtwise_link_name))===FALSE) {

					$partOfBB = $partOfBB."|".$bb_active->districtwise_link_name;
					$partOfBB = str_replace(' ', '', $partOfBB);

					$linkName=$linkName.','.$bb_active->source_district.'-'.$bb_active->destination_district.','.$bb_active->destination_district.'-'.$bb_active->source_district;
					$linkName=str_replace(' ', '', $linkName);

				}
			}

			foreach ($array_bb_protection as $bb_protection) {
				if (($node->node==$bb_protection->source || $node->node==$bb_protection->destination) && strpos($linkName, str_replace(' ','',$bb_protection->districtwise_link_name))===FALSE) {
					
					$partOfBB = $partOfBB."|".$bb_protection->districtwise_link_name;
					$partOfBB = str_replace(' ', '', $partOfBB);

					$linkName=$linkName.','.$bb_protection->source_district.'-'.$bb_protection->destination_district.','.$bb_protection->destination_district.'-'.$bb_protection->source_district;
					$linkName=str_replace(' ', '', $linkName);

				}
			}
			array_push($finalNodes, array("id"=>"$node->node","name"=>array("$node->node"),"labels"=>array("Router"),"properties"=>array("userId"=>"eisman","partOfBB"=>"$partOfBB","lat"=>"$node->lat","lon"=>"$node->lon","district"=>"$node->district","upazilla"=>"$node->upazilla")));
		}

		
		return $finalNodes;
	}

	public function findRelationships()
	{
		$array_bb_active=$this->findActiveBBAll();
		$array_bb_protection=$this->findProtectionPerActiveBBWithCommonPath();
		$nodes = $this->findNodes();
		//print_r($array_bb_protection);
		$relationships = array();
		$id = 1;
		$linkName='';
				
		foreach ($array_bb_active as $bb_active) {
			foreach ($nodes as $node) {
				$nodeName = $node['name'][0];
				if ($nodeName==$bb_active->source && strpos($linkName, $bb_active->link_name)===FALSE) {
					$utilization=number_format((float)($bb_active->utilization/1000), 2, '.', '')."G / ".($bb_active->built_capacity/1000)."G";
					array_push($relationships,array("id"=>"$id","type"=>$utilization,"startNode"=>"$nodeName","endNode"=>"$bb_active->destination","properties"=>array("partOfBB"=>"$bb_active->districtwise_link_name","pathStatus"=>"$bb_active->path_status","built_capacity"=>"$bb_active->built_capacity","utilization"=>number_format((float)($bb_active->utilization), 2, '.', ''))));
					$id++;
					$linkName=$linkName.','.$bb_active->source.'|'.$bb_active->destination.','.$bb_active->destination.'|'.$bb_active->source;
				}
				elseif ($nodeName==$bb_active->destination && strpos($linkName, $bb_active->link_name)===FALSE) {
					$utilization=number_format((float)($bb_active->utilization/1000), 2, '.', '')."G / ".($bb_active->built_capacity/1000)."G";
					array_push($relationships,array("id"=>"$id","type"=>$utilization,"startNode"=>"$nodeName","endNode"=>"$bb_active->source","properties"=>array("partOfBB"=>"$bb_active->districtwise_link_name","pathStatus"=>"$bb_active->path_status","built_capacity"=>"$bb_active->built_capacity","utilization"=>number_format((float)($bb_active->utilization), 2, '.', ''))));
					$id++;
					$linkName=$linkName.','.$bb_active->source.'|'.$bb_active->destination.','.$bb_active->destination.'|'.$bb_active->source;
				}
			}
			
		}
		foreach ($array_bb_protection as $bb_protection) {
			foreach ($nodes as $node) {
				$nodeName = $node['name'][0];
				if ($nodeName==$bb_protection->source && strpos($linkName, $bb_protection->link_name)===FALSE) {
					$utilization=number_format((float)($bb_protection->utilization/1000), 2, '.', '')."G / ".($bb_protection->built_capacity/1000)."G";
					array_push($relationships,array("id"=>"$id","type"=>$utilization,"startNode"=>"$nodeName","endNode"=>"$bb_protection->destination","properties"=>array("partOfBB"=>"$bb_protection->districtwise_link_name","pathStatus"=>"$bb_protection->path_status","built_capacity"=>"$bb_protection->built_capacity","utilization"=>"$bb_protection->utilization")));
					$id++;
					$linkName=$linkName.','.$bb_protection->source.'|'.$bb_protection->destination.','.$bb_protection->destination.'|'.$bb_protection->source;
				}
				elseif ($nodeName==$bb_protection->destination && strpos($linkName, $bb_protection->link_name)===FALSE) {
					$utilization=number_format((float)($bb_protection->utilization/1000), 2, '.', '')."G / ".($bb_protection->built_capacity/1000)."G";
					array_push($relationships,array("id"=>"$id","type"=>$utilization,"startNode"=>"$nodeName","endNode"=>"$bb_protection->source","properties"=>array("partOfBB"=>"$bb_protection->districtwise_link_name","pathStatus"=>"$bb_protection->path_status","built_capacity"=>"$bb_protection->built_capacity","utilization"=>"$bb_protection->utilization")));
					$id++;
					$linkName=$linkName.','.$bb_protection->source.'|'.$bb_protection->destination.','.$bb_protection->destination.'|'.$bb_protection->source;
				}
			}
			
		}

		return $relationships;
		
	}


	public function createJson()
	{
		$nodes = $this->findNodes();
		$relationships = $this->findRelationships();
		$obj = array(	
					"results"=> array(
									array(
										"columns"=>array("user","entity"),
										"data"=>array(
													array(
														"graph"=>array(
															"nodes"=>
															$nodes,
															"relationships"=>
															$relationships
															
														)
													)
												)
					)),	
					"errors"=>array()
		);			
		$myJSON = json_encode($obj);
		/*$fp = fopen('neo4jData.json', 'w');
		fwrite($fp, $myJSON);
		fclose($fp);*/
		$myFile = "json/neo4jData.json";
		try
  		{
  			if(file_put_contents($myFile, $myJSON)) 
  			{
	        //echo 'Data successfully saved';
	    	}
	    	else 
	        echo "error";

	    }
	    catch (Exception $e) {
	            echo 'Caught exception: ',  $e->getMessage(), "\n";
	    }
		//echo $myJSON;
	}
}
