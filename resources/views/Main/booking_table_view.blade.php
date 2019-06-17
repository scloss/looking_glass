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
		white-space: nowrap;
	}
	td{
		white-space: nowrap;
	}

	tr.selected td {
    background-color: #D0DFDF;
    color: #fff;    
	}
	.search-header{
		 background-color: #DADFDF;
	}

	.date-field{
		min-width: 80px;
	}


</style>


<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<body>

	<?php
		if(!isset($select_query_for_site_table)){
			$select_query_for_site_table = "SELECT * FROM looking_glass.booking_table";
		}
	?>

	<br>
	<br>
	
	<center><a href="show_all_booking_table">Show All</a></center>
	<center><a href="/LookingGlass/public"><span class="glyphicon glyphicon-home"></a></center>


	<form action="{{url('export_booking_log')}}" method="post" style="border: none">
	<input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="text" hidden = true id="qry" name="qry" value ="{{$select_query_for_site_table}}"/>
	<center><input class="btn btn-primary" type="submit" value="Export"></center>
	</form>
	



	<br>


	
	<div >
	<table>
		<tr>
			<th><center>ID</center></th>
			<th><center>Mother Site Name</center></th>			
			<th><center>Existing Device Client</center></th>
			<th><center>Interface</center></th>
			<th><center>Capacity</center></th>
			<th><center>Latitude</center></th>
			<th><center>Longitude</center></th>

			<th><center>City</center></th>
			<th><center>Division</center></th>
			<th><center>District</center></th>
			<th><center>Location</center></th>
			<th><center>Address</center></th>

			<th><center>Client Feasibility</center></th>
			<th><center>Capacity Feasibility</center></th>
			<th><center>Comment</center></th>
			<th><center>Booking Date</center></th>
			<th><center>Expiry Date</center></th>
			<th><center>Booked By</center></th>
			<th><center>Action</center></th>
		</tr>

			<tr class="table-row">

				<form action="{{url('search_booking_log')}}" method="get">
					<input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">
					<th class= "search-header"><!-- <center><input class="form-control" type="text" name="id"></center> --></th>
					<th class= "search-header"><center><input class="form-control" type="text" name="site_name"></center></th>
					<th class= "search-header"><center><input class="form-control" type="text" name="device_client"></center></th>
					<th class= "search-header"><center><input class="form-control" type="text" name="interface"></center></th>
					<th class= "search-header"><center><input class="form-control" type="text" name="capacity"></center></th>
					<th class= "search-header"><center><input class="form-control" type="text" name="latitude"></center></th>
					<th class= "search-header"><center><input class="form-control" type="text" name="longitude"></center></th>

					<th class= "search-header"><center><input class="form-control" type="text" name="city"></center></th>
					<th class= "search-header"><center><input class="form-control" type="text" name="division"></center></th>
					<th class= "search-header"><center><input class="form-control" type="text" name="dDistrict"></center></th>
					<th class= "search-header"><center><input class="form-control" type="text" name="location"></center></th>
					<th class= "search-header"><center><input class="form-control" type="text" name="address"></center></th>


					<th class= "search-header"><center><input class="form-control" type="text" name="client_feasibility"></center></th>
					<th class= "search-header"><center><input class="form-control" type="text" name="capacity_feasibility"></center></th>
					<th class= "search-header"><center><input class="form-control" type="text" name="comment"></center></th>
					<th class= "search-header"><center><input class="form-control" type="date" name="booking_date"></center></th>
					<th class= "search-header date-field"><center><input class="form-control" type="date" name="expiry_date"></center></th>
					<th class= "search-header"><center><input class="form-control" type="text" name="booked_by"></center></th>
					<th class= "search-header"><center><input class="form-control" type="submit" value="Search"></center></th>
				</form>
			</tr>
		

			@foreach($site_lists as $site_list)
			<tr class="table-row">
				<td><center>{{$site_list->id}}</center></td>
				<td><center>{{$site_list->site_name}}</center></td>
				<td><center>{{$site_list->existing_device_client}}</center></td>
				<td><center>{{$site_list->interface}}</center></td>
				<td><center>{{$site_list->capacity}}</center></td>
				<td><center>{{$site_list->latitude}}</center></td>
				<td><center>{{$site_list->longitude}}</center></td>

				<td><center>{{$site_list->city}}</center></td>
				<td><center>{{$site_list->division}}</center></td>
				<td><center>{{$site_list->district}}</center></td>
				<td><center>{{$site_list->location}}</center></td>
				<td><center>{{$site_list->address}}</center></td>

				<td><center>{{$site_list->feasibility_client}}</center></td>
				<td><center>{{$site_list->feasibility_mbps}}</center></td>
				<td><center>{{$site_list->comment}}</center></td>
				<td><center>{{$site_list->created_at}}</center></td>
				<td><center>{{$site_list->expiry_date}}</center></td>
				<td><center>{{$site_list->booked_by}}</center></td>
				<td></td>
			</tr>
				

			@endforeach
		

		<br>
		

	</table>
	</div>
</body>

<script type="text/javascript" src="jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>

<script type="text/javascript">
    $('.expiry_date').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 0
    });
</script>



@include('Nav.footer')