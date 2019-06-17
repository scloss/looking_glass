<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Looking Glass</title>

<style>
body {
	font-family: Arial;
	}

/* Style the tab */
.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}
th,td{
	border: 2px solid black;
}

.input-label{
	width: 150px;
}
.input-box{
	width: 150px;
}

</style>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" ></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" ></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script>
</head>
<body class="container-fluid">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Looking Glass</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="http://172.16.136.35/access_issue/public/issue_list">Acess Issue</a>
	  </li>
	  <li class="nav-item">
        <a class="nav-link" href="http://172.16.136.35/access_issue/public/telco_data_list">Telco PO Volume</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="http://172.16.136.35/booking_table/public/index">Booking Table</a>
	  </li>
	  <li class="nav-item">
        <a class="nav-link" href="http://172.16.136.35/LookingGlass/public/bb_map">BB MAP</a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" href="#">Pricing</a>
      </li> -->
      <!-- <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li> -->
    </ul>
  </div>
</nav>
<div class="row">


	<marquee height=100>
   		<small>Last Updated Times: Alarm Data ({{$alarm[0]->created_time}}), Node Data: ({{$node[0]->created_time}}), Resource Data({{$resource[0]->created_time}}), Throughput Table({{$throughput[0]->created_time}}), Telco PO data: ({{$telco[0]->created_time}}), Access Issue Table({{$access[0]->created_time}}) </small>
	</marquee>

<div class="col-md-12">

<center>
<div class="col-md-6 form-group">
<br>
<br>

<p>Please choose input format first</p>

<center><a href="{{url('search_booking_log')}}">Search Booking Log</a></center>

<br>
<br>

<div class="well tab">
  <button class="tablinks" onclick="openTab(event, 'Lat/Long')">Lat/Long</button>
  <button class="tablinks" onclick="openTab(event, 'Deg/Min/Sec')">Deg/Min/Sec</button>
  <button id="search-button" hidden="true" class="tablinks" onclick="openTab(event, 'Search')">Search</button>
</div>


<script>






@if($lat)

	$(document).ready(function() {
		openTab(event, 'Lat/Long');
	});

@else 
	$lat = "";
	$long = "";
	$radius = "";

	$(document).ready(function() {
		openTab(event, 'Lat/Long');
	});

@endif
</script>

<div id="Lat/Long" class="tabcontent">
	<center><h3>Lat/Long</h3></center>
	<div>
		<label class="input-label" class="col-md-4" for="latitude"><b>Latitude</b></label>
		<input class="input-box"   class="col-md-4" type="number" placeholder="Enter latitude" name="latitude" id="latitude" value="{{$lat}}" >
	</div>
	<div>
		<label class="input-label" class="col-md-4" for="longitude"><b>Longitude</b></label>
		<input class="input-box"   class="col-md-4" type="number" placeholder="Enter longitude" name="longitude" id="longitude" value="{{$long}}">
	</div>

	<div>
		<label class="input-label" class="col-md-4" for="lsearch-radius"><b>Search Radius</b></label>
		<input class="input-box"   class="col-md-4" type="number" placeholder="Enter search radius in km" name="lsearch-radius" id="lsearch-radius" value="{{$radius}}">
	</div>

	<div>
		<center><button class="btn btn-warning" onclick="generateFromLatLong()">Generate Lat/Long</button></center>
	</div>
</div>

<div id="Deg/Min/Sec" class="tabcontent">
  <h3>Deg/Min/Sec</h3>

  	<div>
		<label class="input-label" for="dsearch-radius"><b>Search Radius</b></label>
		<input class="input-box" type="number" placeholder="Radius in km" name="dsearch-radius" id="dsearch-radius">
	</div>
	<div >

		<div>
			<h4>Latitude</h4>

			<div>
				<label class="input-label" for="lat_degree"><b>Degree</b></label>
				<input class="input-box"    type="number" placeholder="Enter degree" name="lat_degree" id="lat_degree">
			</div>

			<div>
				<label class="input-label" for="minute"><b>Minute</b></label>
				<input class="input-box" type="number" placeholder="Enter minute" name="lat_minute" id="lat_minute">
			</div>


			<div>
				<label class="input-label" for="second"><b>Second</b></label>
				<input class="input-box" type="number" placeholder="Enter second" name="lat_second" id="lat_second">
			</div>
		</div>

	</div>


	<div>
		<div>
		<h4>Longitude</h4>
		
		<div>
		<label class="input-label" for="long_degree"><b>Degree</b></label>
		<input class="input-box" type="number" placeholder="Enter degree" name="long_degree" id="long_degree">
		</div>

		<div>
		<label class="input-label" for="minute"><b>Minute</b></label>
		<input class="input-box" type="number" placeholder="Enter minute" name="long_minute" id="long_minute">
		</div>

		<div>
		<label class="input-label" for="second"><b>Second</b></label>
		<input class="input-box" type="number" placeholder="Enter second" name="long_second" id="long_second">
		</div>
		</div>


	</div>

	





  	

	

	

	<div>
		<center><button class="btn btn-warning" onclick="generateFromDegreeMinuteSecond()">Generate Lat/Long</button></center>
	</div>

</div>



<div id="Search" class="tabcontent">
  <h3>Search</h3>
  	<div>
	<form action="{{url('LookForSite')}}" method="get">
		<input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">
		<input hidden="true" type="text" placeholder="lat" name="lat" id="lat" readonly="true" required="true">
		<input hidden="true" type="text" placeholder="long" name="long" id="long" readonly="true" required="true">

		<div>
			<label class="col-md-3">Latitude</label>
			<label class="col-md-3" id="lat-label"></label>
		</div>

		<div>
			<label class="col-md-3">Longitude</label>
			<label class="col-md-3" id="long-label"></label>
		</div>

		<div>
			<label class="col-md-3">Search Radius</label>
			<label class="col-md-3" id="radius-label">km</label>
		</div>


		<div>
		<input hidden="true" type="number" placeholder="Please give search radius" name="radius" id="radius" required="true">
		</div>
		<div>
		<button type="submit" class="btn btn-success">Search</button>
		</div>
	</form>
	</div>
</div>

<br>
<br>


</div>

</center>

</div>

</div>
<script>
function generateFromLatLong(){
	var lat = document.getElementById('latitude').value;
	var long = document.getElementById('longitude').value;
	var radius = document.getElementById('lsearch-radius').value;

	document.getElementById('lat').value = lat;
	document.getElementById('long').value = long;
	document.getElementById('radius').value = radius;

	document.getElementById('lat-label').innerHTML = lat;
	document.getElementById('long-label').innerHTML = long;
	document.getElementById('radius-label').innerHTML = radius+' km';

	openTab(event, 'Search');

	
}


function generateFromDegreeMinuteSecond(){
	var radius = document.getElementById('dsearch-radius').value;

	var lat_deg = document.getElementById('lat_degree').value;
	var long_deg = document.getElementById('long_degree').value;
	

	var lat_min = document.getElementById('lat_minute').value;
	var lat_sec = document.getElementById('lat_second').value;

	var long_min = document.getElementById('long_minute').value;
	var long_sec = document.getElementById('long_second').value;

	var lat = parseFloat(lat_deg) + ((parseFloat(lat_min)/60) + (parseFloat(lat_sec)/3600));
	var long = parseFloat(long_deg) + ((parseFloat(long_min)/60) + (parseFloat(long_sec)/3600)); 

	lat = parseFloat(lat).toFixed(6);
	long = parseFloat(long).toFixed(6);

	document.getElementById('lat').value = lat;
	document.getElementById('long').value = long;
	document.getElementById('radius').value = radius;

	document.getElementById('lat-label').innerHTML = lat;
	document.getElementById('long-label').innerHTML = long;
	document.getElementById('radius-label').innerHTML = radius+' km';

	openTab(event, 'Search');
}

function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}





</script>
     
</body>
</html> 
