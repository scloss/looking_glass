@include('Nav.header')
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
		width: 100%;
		white-space: nowrap;

	}
	td{
		width: 100%;
		white-space: nowrap;

	}

	tr.selected td {
    background-color: #D0DFDF;
    color: #fff;    
	}


</style>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">


<body>
	<br>
	<center>Found Devices: <?php echo count($total_site_lists); ?> </center>
	<center>Search Params:(Lat:{{$from_lat}},Long:{{$from_long}},Search Radius:{{$radius}})</center>
	<center><a href="/LookingGlass/public?lat={{$from_lat}}&long={{$from_long}}&radius={{$radius}}"><span class="glyphicon glyphicon-home"></a></center>
	
	<center>{{$msg}}</center>

	<table>
		<tr>
			<th><center>Sl</center></th>
			<th><center>Site Name</center></th>
			<th><center>Tags</center></th>
			<th><center>Distance</center></th>
			<th><center>Device IP</center></th>
			<th><center>Ring No</center></th>
			<!-- <th><center>Third Octet</center></th> -->
			<th><center>Corresponding BB</center></th>
			<th><center>Corresponding Ring</center></th>
			<th><center>Division</center></th>
			<th><center>District</center></th>
			<th><center>Location</center></th>
			<th><center>Address</center></th>
			<th><center>Lat</center></th>
			<th><center>Long</center></th>
			<th><center>Device Model</center></th>
			<th><center>Telco PO Vol Mbps</center></th>
			<th><center>Telco Client</center></th>
			<th><center>Access Issue</center></th>
			<th><center>Booking Info</center></th>
			<!-- <th><center>Booking Action</center></th> -->

		</tr>

			<?php
			$sl = 0;
			?>

			@foreach($total_site_lists as $key => $value)
				<?php

				$octet = explode('.',$site_infos[$key]->device_ip);

				$tocted = $octet[0].'.'.$octet[1].'.'.$octet[2];

				$second_degree = $octet[0].'.'.$octet[1];

				$ring = $octet[2];  

				?>


				<tr class="table-row"
				@if($site_infos[$key]->access_issue != "")

					style = "background-color: #FFE8F1;"

				@endif


				>


				<td><center>{{$sl+1}}</center></td>
				<td


				><center><a href="device_info?sn={{$key}}&lat={{$from_lat}}&long={{$from_long}}&radius={{$radius}}&ip={{$site_infos[$key]->device_ip}}&tocted={{$tocted}}&client={{$site_infos[$key]->device_info_client}}&site_lat={{$site_infos[$key]->latitude}}&site_long={{$site_infos[$key]->longitude}}"><strong>{{$key}}</strong></a></center></td>
				<td><center><strong>{{$site_infos[$key]->tag}}</strong></center></td>
				<td><center>{{round($value,2)}} km</center></td>
				<td><center>{{$site_infos[$key]->device_ip}}</center></td>
				
				<td>
					@if($second_degree != '172.30')
						<center>{{$ring}}</center>
					@else
						<center>NA</center>
					@endif
				</td>
				<!-- <td><center>{{$tocted}}</center></td> -->
				<td><center><a href="bbshow?tocted={{$tocted}}&lat={{$from_lat}}&long={{$from_long}}&radius={{$radius}}" class="btn btn-success">Show</a></center></td>
				<td><center><a href="ringshow?tocted={{$tocted}}&lat={{$from_lat}}&long={{$from_long}}&radius={{$radius}}" class="btn btn-primary">Show</a></center></td>
				<td><center>{{$site_infos[$key]->division}}</center></td>
				<td><center>{{$site_infos[$key]->district}}</center></td>
				<td><center>{{$site_infos[$key]->location}}</center></td>
				<td><center>{{$site_infos[$key]->address}}</center></td>
				<td><center>{{number_format((float)$site_infos[$key]->latitude, 6, '.', '')}}</center></td>
				<td><center>{{number_format((float)$site_infos[$key]->longitude,6, '.', '')}}</center></td>
				<td><center>{{$site_infos[$key]->device_model}}</center></td>
				<td><center>{{$site_infos[$key]->po_vol}}</center></td>
				<td><center>{{$site_infos[$key]->client}}</center></td>
				<td><center>{{$site_infos[$key]->access_issue}}</center></td>
				<td><center>
					@foreach($booking_info_lists as $booking_info_list)
						@if($booking_info_list->site_name == $key)
							<a href="show_booking_table?lat={{$from_lat}}&long={{$from_long}}&radius={{$radius}}&site_name={{$key}}" class="btn btn-warning">Show Booking Info</a>
						@endif
					@endforeach

				</center></td>
				<!-- <td><center><a href="book_view?lat={{$from_lat}}&long={{$from_long}}&radius={{$radius}}&client={{$site_infos[$key]->device_info_client}}&site_name={{$key}}&site_lat={{$site_infos[$key]->latitude}}&site_long={{$site_infos[$key]->longitude}}" class="btn btn-warning">Book Now</a></center></td> -->

				<?php
					$sl = $sl +1;
					?>

			@endforeach
		

		<br>
		

	</table>
</body>
@include('Nav.footer')