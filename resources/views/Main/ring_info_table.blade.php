<!DOCTYPE html>
<html>
<head>
	<title>Looking Glass</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> -->
	<link rel="stylesheet" type="text/css" href="{{asset('css/auth_css.css')}}">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" ></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" ></script>


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>
		$( document ).ready(function() {
			console.log( "ready!" );
			$(".table-row td").on("click", function() {
				var tr = $(this).parent();
				if(tr.hasClass("selected")) {
					tr.removeClass("selected");
				} else {
					tr.addClass("selected");
				}

			});
		});
	</script>

	<style type="text/css">

		th{
			white-space: nowrap;
		}
		td{
			white-space: nowrap;
			height: 30px;
		}

		tr.selected td {
	    background-color: #D0DFDF;
	    color: #fff;    
		}

		.info-btn{
			width: 100px;
		}

		.bw-stat-table{
			margin-left:auto;
			margin-right:auto;
			width:50%;
		}


		body{
			margin-left:auto;
			margin-right:auto;
			width:100%;

		}
		.container-fluid {
		    padding-right: 5px;
		    padding-left: 5px;
		    margin-right: auto;
		    margin-left: auto;
		}
		
	</style>

	
	
</head>
<body>
	<br>
	<center>Show Ring Info: {{count($ring_infos)}}</center>

	<center><a href="/LookingGlass/public?lat={{$lat}}&long={{$long}}&radius={{$radius}}"><span class="glyphicon glyphicon-home"></a></center>
	<center><a href="{{url('LookForSite')}}?lat={{$lat}}&long={{$long}}&radius={{$radius}}"><span class="glyphicon glyphicon-chevron-left"></a></center>	
	
	<div class="container-fluid">
	<div class="row">
	<table>
		<tr>
			<th><center>Sl</center></th>
			<th><center>Host Name</center></th>
			<th><center>Ring No</center></th>
			<th><center>IP Address</center></th>
			<th><center>Latitude</center></th>
			<th><center>Longitude</center></th>
			<th><center>Division</center></th>
			<th><center>Upazilla</center></th>
			<th><center>District</center></th>
			<th><center>Location</center></th>
			<th><center>Address</center></th>
			<th><center>PO Vol</center></th>
			<th><center>Client</center></th>
		</tr>

			<?php
			$sl = 0;
			?>

			@foreach($ring_infos as $ring_info)
				<tr class="table-row">
				<td><center>{{$sl+1}}</td></center>
				<td><center>{{$ring_info->host_name}}</td></center>
				<?php

				$ip_array = explode('.',$ring_info->ip_addr); 
				
				$tocted = $ip_array[0].'.'.$ip_array[1].'.'.$ip_array[2];

				$ring_no = $ip_array[2];  

				?>
				<td><center>{{$ring_no}}</td></center>
				<td><center>{{$ring_info->ip_addr}}</td></center>
				<td><center>{{$ring_info->latitude}}</td></center>
				<td><center>{{$ring_info->longitude}}</td></center>
				<td><center>{{$ring_info->division}}</td></center>
				<td><center>{{$ring_info->upazilla}}</td></center>
				<td><center>{{$ring_info->district}}</td></center>
				<td><center>{{$ring_info->location}}</td></center>
				<td><center>{{$ring_info->address}}</td></center>
				<td><center>{{$ring_info->po_vol}}</td></center>
				<td><center>{{$ring_info->client}}</td></center>
				</tr>

				<?php
					$sl = $sl +1;
					?>

			@endforeach
		

		<br>
		

	</table>
	</div>
	</div>
</body>
@include('Nav.footer')