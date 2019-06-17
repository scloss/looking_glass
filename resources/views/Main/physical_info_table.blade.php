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
			text-align: center;
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
		    padding-right: 0px;
		    padding-left: 0px;
		    margin-right: auto;
		    margin-left: auto;
		}
		
	</style>

	
	
</head>






<body>
	<br>
	<center>Show Device Info: {{count($all_physical_device_infos)}}</center>
	<center><a href="/LookingGlass/public?lat={{$lat}}&long={{$long}}&radius={{$radius}}"><span class="glyphicon glyphicon-home"></a></center>
	<center><a href="{{url('LookForSite')}}?lat={{$lat}}&long={{$long}}&radius={{$radius}}"><span class="glyphicon glyphicon-chevron-left"></a></center>


	<div class="bw-stat-table">
		<table>
			<tr>
				<th style="width:25%"><center>Ring Built Capacity(Mbps)</center></th>
				<th style="width:75%" colspan="3"><center>Ring Usage (Approx in Mbps)</center></th>
				<th style="width:75%" colspan="3"><center></center></th>				
			</tr>
			<tr>
				<td style="width:25%"></td>
				<td style="width:25%">Avg</td>
				<td style="width:25%">min</td>
				<td style="width:25%">Max</td>
				<td><a href="bbshow?tocted={{$tocted}}&lat={{$lat}}&long={{$long}}&radius={{$radius}}" class="btn btn-success info-btn" target="_blank">B-B  Info</a></td>				
			</tr>
			<tr>
				<td style="width:25%">{{$ring_built_capacity}}</td>
				<td style="width:25%">{{$avg_val}}</td>
				<td style="width:25%">{{$min_val}}</td>
				<td style="width:25%">{{$max_val}}</td>
				<td><a href="ringshow?tocted={{$tocted}}&lat={{$lat}}&long={{$long}}&radius={{$radius}}" class="btn btn-primary info-btn" target="_blank">Ring Info</a></td>
			</tr>
		</table>
	</div>
	<br/>
	
	<div class="bw-stat-table">
		<table>
			<tr>
				<td style="width:25%"></td>
				<td style="width:25%">Free</td>
				<td style="width:25%">Occupied</td>				
			</tr>
			<tr>
				<td style="width:25%">Electrical</td>
				<td style="width:25%">{{$electrical_free}}</td>
				<td style="width:25%">{{$electrical_occupied}}</td>
			</tr>
			<tr>
				<td style="width:25%">Optical</td>
				<td style="width:25%">{{$optical_free}}</td>
				<td style="width:25%">{{$optical_occupied}}</td>
			</tr>
		</table>
	</div>

	
	
	<!-- <div class ="container-fluid">
		<div class="row">
		<div class="col-md-12"> -->
	<table>
		<tr>
			
			<th class="first-col"><center>Sl</center></th>
			<th><center>Node</center></th>
			<th><center>ifname</center></th>
			<th><center>Port Category</center></th>
			<th><center>Port Type</center></th>
			<th><center>Admin Status</center></th>
			<th><center>Oper-Status</center></th>
			<th><center>If Description Alias</center></th>
			<th><center>Capacity</center></th>
			<th><center>Link Down Alarms</center></th>
			<th><center>AVG(Mbps)</center></th>
			<th><center>Min(Mbps)</center></th>
			<th><center>Max(Mbps)</center></th>
			<th><center>Actions</center></th>			
		</tr>

			<?php
			$sl = 0;
			?>

			@foreach($all_physical_device_infos as $all_physical_device_info)
				<tr class="table-row">
				<td class="first-col"><center>{{$sl+1}}</center></td>
				<td><center>{{$all_physical_device_info->node}}</center></td>
				<td><center>{{$all_physical_device_info->ifname}}</center></td>
				<td><center>{{$all_physical_device_info->port_category}}</center></td>
				<td><center>{{$all_physical_device_info->port_type}}</center></td>
				<td>
				@if($all_physical_device_info->admin_status_translation == "up")
					<center style="color:green">{{$all_physical_device_info->admin_status_translation}}</center>
				@elseif($all_physical_device_info->admin_status_translation == "down")
					<center style="color:red">{{$all_physical_device_info->admin_status_translation}}</center>
				@elseif($all_physical_device_info->admin_status_translation == "disabled")
					<center style="color:blue">{{$all_physical_device_info->admin_status_translation}}</center>
				@endif
				</td>
				<td>
				@if($all_physical_device_info->oper_status_translation == "up")
					<center style="color:green">{{$all_physical_device_info->oper_status_translation}}</center>
				@elseif($all_physical_device_info->oper_status_translation == "down")
					<center style="color:red">{{$all_physical_device_info->oper_status_translation}}</center>
				@elseif($all_physical_device_info->oper_status_translation == "disabled")
					<center style="color:blue">{{$all_physical_device_info->oper_status_translation}}</center>
				@endif
				</td>
				<td><center>{{$all_physical_device_info->if_alias}}</center></td>
				<td><center>{{$all_physical_device_info->snmpifspeed}}</center></td>
				<td><center>
				@if($all_physical_device_info->logmsg)
					<span style="color:red"><b>Alarm Exists</b></span>
				@else
					<span style="color:green">No Alarm</span>
				@endif


				</center></td>
				<td><center>{{$all_physical_device_info->avg}}</center></td>
				<td><center>{{$all_physical_device_info->min}}</center></td>
				<td><center>{{$all_physical_device_info->max}}</center></td>
				<td>
					<center>
						@if($all_physical_device_info->port_category == 'Physical')



						@foreach($interface_wise_bookings as $interface_wise_booking)

							@if($all_physical_device_info->ifname == $interface_wise_booking->interface)
								<!-- booking info -->
								
								<a href="show_booking_table?lat={{$lat}}&long={{$long}}&radius={{$radius}}&site_name={{$all_physical_device_info->node}}&if_name={{$all_physical_device_info->ifname}}" class="btn btn-warning info-btn" target="_blank">Booking Info</a>
								@break		
							@elseif($all_physical_device_info->ifname != $interface_wise_booking->interface)
								<!-- book now -->
								
								<a href="book_view?lat={{$lat}}&long={{$long}}&radius={{$radius}}&client={{$client}}&site_name={{$all_physical_device_info->node}}&site_lat={{$site_lat}}&site_long={{$site_long}}&if_name={{$all_physical_device_info->ifname}}&capacity={{$all_physical_device_info->snmpifspeed}}" class="btn btn-primary info-btn" target="_blank">Book Now</a>
								@break
							@else

							@endif
						@endforeach

						<?php
						if(count($interface_wise_bookings)==0){
						?>

							<a href="book_view?lat={{$lat}}&long={{$long}}&radius={{$radius}}&client={{$client}}&site_name={{$all_physical_device_info->node}}&site_lat={{$site_lat}}&site_long={{$site_long}}&if_name={{$all_physical_device_info->ifname}}&capacity={{$all_physical_device_info->snmpifspeed}}" class="btn btn-primary info-btn" target="_blank">Book Now</a>

						<?php
						}
						?>
						@endif


						
						
					</center>
				</td>

				<?php
					$sl = $sl +1;
					?>

			@endforeach
		

		<br>
		

	</table>
		<!-- </div>
	</div>
	</div> -->
</body>
@include('Nav.footer')