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
	<center>Show Backbone Info: <?php echo count($bb_infos); ?> </center>	
	
	<center><a href="/LookingGlass/public?lat={{$lat}}&long={{$long}}&radius={{$radius}}"><span class="glyphicon glyphicon-home"></a></center>
	<center><a href="{{url('LookForSite')}}?lat={{$lat}}&long={{$long}}&radius={{$radius}}"><span class="glyphicon glyphicon-chevron-left"></a></center>

	<div class="container-fluid">
	<div class="row">
	<table>
		<tr>
			<th><center>Sl</center></th>
			<th><center>Third Octet</center></th>
			
			<th><center>BB Node</center></th>
			<th><center>Interface</center></th>
			<th><center>Connected Ring Count</center></th>
			<th><center>Port Category</center></th>
			<th><center>Port Type</center></th>
			<th><center>Admin Status</center></th>
			<th><center>Oper Status</center></th>
			<th><center>If Description Alias</center></th>
			<th><center>Capacity(Mbps)</center></th>
			<th><center>AVG(Mbps)</center></th>
			<th><center>Min(Mbps)</center></th>
			<th><center>Max(Mbps)</center></th>
			

		</tr>

			<?php
			$sl = 0;
			?>

			@foreach($bb_infos as $bb_info)
				<tr class="table-row">
				<td><center>{{$sl+1}}</td></center>
				
				<td><center>{{$bb_info->third_octet}}</td></center>
				<td><center>{{$bb_info->bb_node}}</td></center>
				<td><center>{{$bb_info->interface}}</td></center>
				<td><center>{{$bb_info->connected_ring_count}}</td></center>
				<td><center>{{$bb_info->port_category}}</td></center>
				<td><center>{{$bb_info->port_type}}</td></center>
				<td><center>{{$bb_info->admin_status}}</td></center>
				<td><center>{{$bb_info->oper_status}}</td></center>
				<td><center>{{$bb_info->if_description_alias}}</td></center>
				<td><center>{{$bb_info->capacity_mbps/1000000}}</td></center>
				<td><center>{{$bb_info->avg_mbps}}</td></center>
				<td><center>{{$bb_info->min_mbps}}</td></center>
				<td><center>{{$bb_info->max_mbps}}</td></center>
				
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