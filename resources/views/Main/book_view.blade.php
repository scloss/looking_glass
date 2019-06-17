<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Latest compiled and minified CSS -->

<!-- <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen"> -->
<link href="bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>



<style>



body {
  margin: 0 auto;
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
    /*display: block;*/
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}


</style>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> -->
  
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" ></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script>



</head>
<body class="container-fluid">
<div class="col-md-6 col-md-offset-4">
<div class="row">

<div class="col-md-12">


<div class="col-md-8 form-group">
<br>
<br>

<!-- <p>Register a booking</p> -->
<div class="col-md-12"><center><a href="/LookingGlass/public?lat={{$lat}}&long={{$long}}&radius={{$radius}}"><span class="glyphicon glyphicon-home"></a></center></div>
<div class="col-md-12"><center><a href="{{url('LookForSite')}}?lat={{$lat}}&long={{$long}}&radius={{$radius}}"><span class="glyphicon glyphicon-menu-left">Back</a></center></div>
<!-- <div class="col-md-12"><center><a href="{{url('LookForSite')}}?lat={{$lat}}&long={{$long}}&radius={{$radius}}">Back</a></center></div> -->
<br>

<div class="well tab">
  Book Now
  
</div>





<div class="tabcontent">
  
    <div>
  <form action="{{url('make_booking')}}" method="get" onsubmit="return validate();">
    <input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">

    <input class="form-control" type="hidden" name="search_lat" value="{{ $lat }}">
    <input class="form-control" type="hidden" name="search_long" value="{{ $long }}">
    <input class="form-control" type="hidden" name="search_radius" value="{{ $radius }}">    

    <div class="form-group">     
      <label for="existing_device_client">Existing Client Device</label>
      <input required="true" type="text" class="form-control col-md-4" id="existing_device_client" name="existing_device_client" placeholder="" value="{{$device_client}}" readonly >
    </div>

    <div class="form-group">
      <label for="site_name">Site Name:</label>
      <input required="true" type="text" class="form-control col-md-4" id="site_name" name="site_name" placeholder="" value="{{$site_name}}" readonly >
    </div>

   

    <div class="form-group">
      <label for="interface">Interface</label>
      <input required="true" type="text" required="true" id="interface" name="interface" class="form-control col-md-4" readonly placeholder="" value="{{$if_name}}">

      <!-- <select required="true" id="interface" name="interface" class="form-control col-md-4" readonly>
          <option selected disabled="true"></option>
        @foreach($interface_lists as $interface_list)
          <option value="{{$interface_list->snmp_if_name}}"
              @if($if_name==$interface_list->snmp_if_name)
                selected="true"
              @endif

            >{{$interface_list->snmp_if_name}}</option>
        @endforeach
      </select> -->
    </div>

    <div class="form-group">


      <label for="capacity">Capacity</label>
      <input required="true" type="text" class="form-control col-md-4" id="capacity" name="capacity" placeholder="" value= "{{$capacity}}" readonly>

    </div>

    <div class="form-group">


      <label for="latitude">Latitude</label>
      <input required="true" type="text" class="form-control col-md-4" id="latitude" name="latitude" placeholder="" value="{{$site_lat}}" readonly>

    </div>

    <div class="form-group">


      <label for="longitude">Longitude</label>
      <input required="true" type="text" class="form-control col-md-4" id="longitude" name="longitude" placeholder="" value="{{$site_long}}" readonly>

    </div>


    <div class="form-group">


      <label for="city">City</label>
      <input required="true" type="text" class="form-control col-md-4" id="city" name="city" placeholder="" value="{{$location_info[0]->city}}" readonly>

    </div>

    <div class="form-group">


      <label for="division">Division</label>
      <input required="true" type="text" class="form-control col-md-4" id="division" name="division" placeholder="" value="{{$location_info[0]->division}}" readonly>

    </div>

    <div class="form-group">


      <label for="district">District</label>
      <input required="true" type="text" class="form-control col-md-4" id="district" name="district" placeholder="" value="{{$location_info[0]->district}}" readonly>

    </div>

    <div class="form-group">


      <label for="location">Location</label>
      <input required="true" type="text" class="form-control col-md-4" id="location" name="location" placeholder="" value="{{$location_info[0]->location}}" readonly>

    </div>

    <div class="form-group">


      <label for="address">Address</label>
      <input required="true" type="text" class="form-control col-md-4" id="address" name="address" placeholder="" value="{{$location_info[0]->address}}" readonly>

    </div>



    <br>
    <br>

    <div class="form-group" style="background-color: #C7D3D3; padding:20px;border: 2px solid #73AD21;border-radius: 25px;">
    <center><strong>Client Feasibility Info</strong></center>

    <div class="form-group">


      <label for="client_feasibility">Client Name</label>
      <input required="true" type="text" class="form-control col-md-4" id="client_feasibility" name="client_feasibility" placeholder="">

    </div>

    <div class="form-group">
      <label for="capacity_feasibility">Capacity(Mbps)</label>
      <input required="true" type="number" class="form-control col-md-4" id="capacity_feasibility" name="capacity_feasibility" placeholder="">
    </div>

    <div class="form-group">
      <label for="expiry_date">Booking Expiry Date</label>
      <!-- <input required="true" type="date" class="form-control col-md-4" id="expiry_date" name="expiry_date" placeholder=""> -->
      <div class="form-group">
          <div class="input-group date expiry_date" data-date="" data-date-format="yyyy-mm-dd hh:ii:ss" data-link-field="expiry_date" style="padding: 0;">
              <input class="form-control" size="16" type="text" value="">
              <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
              <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
          </div>
        <input type="hidden" id="expiry_date" name="expiry_date" value="" required = "true"/><br/>
      </div>

    </div>

    </div>

    <div class="form-group">
      
      <label for="comment">Comment</label>
      <textarea required="true" class="form-control col-md-4" id="comment" name="comment" rows="4" cols="50"></textarea>
      
    </div>

    <div class="form-group">
      
      <label for="comment">Booked By</label>
      <input required="true" type="text" class="form-control col-md-4" id="booked_by" name="booked_by" placeholder="" value="{{$user_id}}" readonly="true">
      
    </div>

    <br>
    <br>
    <br>

    <div class=" form-group">
      <center>
      <button type="submit" class="btn btn-success">Save</button>
      </center>
    </div>

    <br>
    <br>
    <br>


    
  </form>



  </div>
</div>

<br>
<br>


</div>



</div>

</div>

</div>

<!-- interface list modal -->

<!-- Modal -->
<!-- <div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">
    
      
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <div class="col-md-12">
            <span class="col-md-4">Interface</span>
            <span class="col-md-4">Booking</span>
            <span class="col-md-4">Action</span>
          </div>
          <br>
          @foreach($interface_lists as $interface_list)
            <div>
              <span class="col-md-4">{{$interface_list->snmp_if_name}}</span>
              <span class="col-md-4">{{$interface_list->number_of_booking}}</span>
              <span value="{{$interface_list->snmp_if_name}}" class="col-md-4"><button onclick="setInterface()">Add</button></span>
            </div>
            <p>
          @endforeach
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
</div> -->

<script>
function setInterface() {

    alert('success');
}
</script>


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

    function validate(){

      var expiry_date = document.getElementById('expiry_date');
      if(expiry_date.value==''){
        alert("Please select Valid Date & Time");
        return false;
      }
    }
</script>



     
</body>
</html> 
