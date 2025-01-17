<!DOCTYPE html>
<html lang="en">
<head>
	<title>Backbone Data</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="form_css/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="form_css/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="form_css/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="form_css/fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="form_css/vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="form_css/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="form_css/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="form_css/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="form_css/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	{{-- <link rel="stylesheet" type="text/css" href="form_css/vendor/noui/nouislider.min.css"> --}}
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="form_css/css/util.css">
	<link rel="stylesheet" type="text/css" href="form_css/css/main.css">
<!--===============================================================================================-->


<style>
	input {
		border: 1px solid black;
		width: 200px;
	}

	select { width: 200px }

	.padding_10{
		padding: 10px;
	}
</style>
</head>
<body>

<input type="hidden" id="source" value="{{$bb_data[0]->source}}">
<input type="hidden" id="resource" value="{{$bb_data[0]->resource}}">
<input type="hidden" id="destination" value="{{$bb_data[0]->destination}}">
<input type="hidden" id="ip_address" value="{{$bb_data[0]->ip_address}}">
<input type="hidden" id="destionation_resource" value="{{$bb_data[0]->destination_resource}}">
<input type="hidden" id="division" value="{{$bb_data[0]->division}}">
<input type="hidden" id="district" value="{{$bb_data[0]->district}}">
<input type="hidden" id="link_name" value="{{$bb_data[0]->link_name}}">
<input type="hidden" id="built_capacity" value="{{$bb_data[0]->built_capacity}}">
<input type="hidden" id="path_status" value="{{$bb_data[0]->path_status}}">
<input type="hidden" id="hopwise_link_name" value="{{$bb_data[0]->hopwise_link_name}}">
<input type="hidden" id="districtwise_link_name" value="{{$bb_data[0]->districtwise_link_name}}">

<input type="hidden" id="resource_type" value="">


	<div class="container-contact100">
		<div class="wrap-contact100">
			<form class="contact100-form validate-form" action="{{ url('update_bb_data') }}" method="post">
				<input type="hidden" name="row_id" value="{{$bb_data[0]->row_id}}">

				<span class="contact100-form-title">
					Update Backbone Data
				</span>

				
							<div class="col-md-12">
									<span class="col-md-4 col-md-offset-2 float-left">Source:</span>
									<span class="col-md-4 col-md-offset-2 float-right">
										<select name="source" id="source_form" onchange="onChange_Source()">
											<option>Please choose</option>
											@foreach($host_names as $row)
												<option >{{$row->host_name}}</option>	
											@endforeach
										</select>
									</span>
							</div>
				
				
				
				<div class="col-md-12">
					<span class="col-md-4 col-md-offset-2 float-left">Resource:</span>
					<span class="col-md-4 col-md-offset-2 float-right">
						<input type="text" name="resource" id="resource_select_form" value="{{$bb_data[0]->resource}}" onclick="change_resource_name();">
					</span>
				</div>

				<div class="col-md-12">
					<span class="col-md-4 col-md-offset-2 float-left">IP Address:</span>
					<span class="col-md-4 col-md-offset-2 float-right">
						<input type="text" name="ip_address" id="ip_address_form">
					</span>
				</div>


					<div class="col-md-12">
						<span class="col-md-4 col-md-offset-2 float-left">Destination:</span>
						<span class="col-md-4 col-md-offset-2 float-right">
							<select name="destination" id="destination_form" onchange="onChange_Destination()">
								<option>Please choose</option>
								@foreach($host_names as $row)
									<option >{{$row->host_name}}</option>	
								@endforeach
							</select>
						</span>
					</div>
					
					<div class="col-md-12">
						<span class="col-md-4 col-md-offset-2 float-left">Destination Resource:</span>
						<span class="col-md-4 col-md-offset-2 float-right">
							<input type="text" name="destination_resource" id="destination_resource_select_form" value="{{$bb_data[0]->destination_resource}}" onclick="change_destination_resource_name();">
						</span>
					</div>

					<div class="col-md-12">
							<span class="col-md-4 col-md-offset-2 float-left">Division:</span>
							<span class="col-md-4 col-md-offset-2 float-right">
								<input type="text" name="division" id="division_form">
							</span>
					</div>

					<div class="col-md-12">
							<span class="col-md-4 col-md-offset-2 float-left">District:</span>
							<span class="col-md-4 col-md-offset-2 float-right">
								<input type="text" name="district" id="district_form">
							</span>
					</div>
				
					<div class="col-md-12">
							<span class="col-md-4 col-md-offset-2 float-left">Link Name:</span>
							<span class="col-md-4 col-md-offset-2 float-right">
								<input type="text" name="link_name" id="link_name_form">
							</span>
					</div>

					<div class="col-md-12">
							<span class="col-md-4 col-md-offset-2 float-left">Built Capacity:</span>
							<span class="col-md-4 col-md-offset-2 float-right">
								<select name="built_capacity" id="built_capacity_form">
									<option>Please choose</option>
									@foreach($bandwidth_data as $row)
										<option >{{$row->bandwidth_polled}}</option>	
									@endforeach
								</select>
							</span>
					</div>

					<div class="col-md-12">
							<span class="col-md-4 col-md-offset-2 float-left">Path Status:</span>
							<span class="col-md-4 col-md-offset-2 float-right">
								<input type="text" name="path_status" id="path_status_form">
							</span>
					</div>

					<div class="col-md-12">
							<span class="col-md-4 col-md-offset-2 float-left">Hopwise Linkname:</span>
							<span class="col-md-4 col-md-offset-2 float-right">
								<input type="text" name="hopwise_linkname" id="hopwise_linkname_form" onclick="hopwise_link_name();">
							</span>
					</div>

					<div class="col-md-12">
							<span class="col-md-4 col-md-offset-2 float-left">Districtwise Linkname:</span>
							<span class="col-md-4 col-md-offset-2 float-right">
								<input type="text" name="districtwise_linkname" id="districtwise_linkname_form" onclick="districtwise_link_name();">
							</span>
					</div>


				<div class="container-contact100-form-btn">
					<button class="contact100-form-btn">
						<span>
							Submit
							<i class="fa fa-long-arrow-right m-l-7" aria-hidden="true"></i>
						</span>
					</button>
				</div>
			</form>
		</div>
	</div>



<!--===============================================================================================-->
	<script src="form_css/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="form_css/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="form_css/vendor/bootstrap/js/popper.js"></script>
	<script src="form_css/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="form_css/vendor/select2/select2.min.js"></script>
	<script>
        $( document ).ready(function() {
            console.log( "ready!" );

            var source = document.getElementById("source").value;
            var url = "http://172.16.136.35/LookingGlass/public/get_node_resources_list?host_name=";
            var full_url = url.concat(source);
            ///////////////////////////////// source selected ///////////////////////////////
            $("#source_form").val(source);
    
            /////////////////////////////// Generate resource options /////////////////////////
            $.ajax({
                    url: full_url,
                    method: "GET",
                    dataType: "text",
                    success: function(data){
                        //console.log(data);
                        document.getElementById("resource_select_form").innerHTML = data;
                        // $('#check').prop('checked', false); // Checks it
                        // $("#element_list_table").html(data);
                        // $('#result_div').prop("hidden", false);
                    },
                    error: function (request, status, error) {
                        // $('#check').prop('checked', false);
                        alert(request.responseText);
                    }
            });
            
            ////////////////////////////// Set source resource ///////////////////////////////////
            var resource = document.getElementById("resource").value;
            //alert(resource);
            $("#resource_select_form").val(resource);
            ////////////////////////////// Set ip address ///////////////////////////////////
            var ip_address = document.getElementById("ip_address").value;
            //alert(resource);
            $("#ip_address_form").val(ip_address);

            ////////////////////////////// Set division ///////////////////////////////////
            var division = document.getElementById("division").value;
            $("#division_form").val(division);

            ////////////////////////////// Set district ///////////////////////////////////
            var district = document.getElementById("district").value;
            $("#district_form").val(division);

            ////////////////////////////// Set Link name ///////////////////////////////////
            var link_name = document.getElementById("link_name").value;
            $("#link_name_form").val(link_name);

            ///////////////////////////// Destination Selected ///////////////////////////////////
            var destination = document.getElementById("destination").value;
            $("#destination_form").val(destination);
            var url = "http://172.16.136.35/LookingGlass/public/get_node_resources_list?host_name=";
            var full_url = url.concat(destination);              
            /////////////////////////////// Generate destination resource options /////////////////////////
            $.ajax({
                    url: full_url,
                    method: "GET",
                    dataType: "text",
                    success: function(data){
                        //console.log(data);
                        document.getElementById("destination_resource_select_form").innerHTML = data;
                        // $('#check').prop('checked', false); // Checks it
                        // $("#element_list_table").html(data);
                        // $('#result_div').prop("hidden", false);
                    },
                    error: function (request, status, error) {
                        // $('#check').prop('checked', false);
                        alert(request.responseText);
                    }
            });

            ////////////////////////////// Set Built capacity ///////////////////////////////////
            var built_capacity = document.getElementById("built_capacity").value;
            $("#built_capacity_form").val(built_capacity);

            ////////////////////////////// Set Path Status ///////////////////////////////////
            var path_status = document.getElementById("path_status").value;
            $("#path_status_form").val(built_capacity);

            ////////////////////////////// Set Hopwise Linkname ///////////////////////////////////
            var hopwise_link_name = document.getElementById("hopwise_link_name").value;
            $("#hopwise_linkname_form").val(hopwise_link_name);

            ////////////////////////////// Set Districtwise Linkname ///////////////////////////////////
            var districtwise_link_name = document.getElementById("districtwise_link_name").value;
            $("#districtwise_linkname_form").val(hopwise_link_name);

        });

		function onChange_Source(){
				refresh_link_name();
				var source = document.getElementById("source").value;
				var url = "http://172.16.136.35/LookingGlass/public/get_node_resources_list?host_name=";
				var full_url = url.concat(source);
			
			
				$.ajax({
						url: full_url,
						method: "GET",
						dataType: "text",
						success: function(data){
							//console.log(data);
							document.getElementById("resource_select").innerHTML = data;
							// $('#check').prop('checked', false); // Checks it
							// $("#element_list_table").html(data);
							// $('#result_div').prop("hidden", false);
						},
						error: function (request, status, error) {
							// $('#check').prop('checked', false);
							alert(request.responseText);
						}
				});

				var url = "http://172.16.136.35/LookingGlass/public/get_district_division?host_name=";
				var full_url = url.concat(source);

				$.ajax({
						url: full_url,
						method: "GET",
						dataType: "text",
						success: function(data){
							console.log(data);

							var json_obj = JSON.parse(data);
							var district = json_obj.district;
							var division = json_obj.division;
							var ip_addr = json_obj.ip_addr;

							document.getElementById("district").value = district;
							document.getElementById("division").value = division;
							document.getElementById("ip_address").value = ip_addr;

							console.log(district);
							//document.getElementById("resource_select").innerHTML = data;
							// $('#check').prop('checked', false); // Checks it
							// $("#element_list_table").html(data);
							// $('#result_div').prop("hidden", false);
						},
						error: function (request, status, error) {
							// $('#check').prop('checked', false);
							alert(request.responseText);
						}
				});


			//console.log(source);
			//alert(source);
		}

		function onChange_Destination(){
			refresh_link_name();
			console.log("test");
			var destination = document.getElementById("destination").value;
			var url = "http://172.16.136.35/LookingGlass/public/get_node_resources_list?host_name=";
			var full_url = url.concat(destination);
			
			
			$.ajax({
						url: full_url,
						method: "GET",
						dataType: "text",
						success: function(data){
							//console.log(data);
							document.getElementById("destination_resource_select").innerHTML = data;
							// $('#check').prop('checked', false); // Checks it
							// $("#element_list_table").html(data);
							// $('#result_div').prop("hidden", false);
						},
						error: function (request, status, error) {
							// $('#check').prop('checked', false);
							alert(request.responseText);
						}
			});
		}
		
		function refresh_link_name(){
			var source = document.getElementById("source").value;
			var destination = document.getElementById("destination").value;
			
			if(source!="Please choose"){
				if(destination!="Please choose"){
					console.log(source);
					console.log(destination);
					document.getElementById("link_name").value = source+"|"+destination;
				}
			}
			
		}

        function hopwise_link_name(){
            console.log("test");
            window.open('hopwise_link');
        }

		function districtwise_link_name(){
            console.log("test");
            window.open('districtwise_link');
        }

		function add_hopwise_linkname(link_name){
			document.getElementById("hopwise_linkname_form").value = link_name;
		}

		function add_districtwise_linkname(link_name){
			document.getElementById("districtwise_linkname_form").value = link_name;
		}

		function change_resource_name(){
			console.log("success");
			document.getElementById("resource_type").value = "source";
			var host_name = document.getElementById("source_form").value;

			var resource = document.getElementById("resource_select_form").value;
			var resource_string = resource.split('/').join('slash');
			var url = "change_resource?resource=" + resource_string+"&host_name="+host_name;
			window.open(url);
			//alert(resource);
		}
		
		function change_destination_resource_name(){
			
			document.getElementById("resource_type").value = "destination";
			var host_name = document.getElementById("destination_form").value;

			var resource = document.getElementById("destination_resource_select_form").value;
			var resource_string = resource.split('/').join('slash');
			var url = "change_resource?resource=" + resource_string+"&host_name="+host_name;
			window.open(url);
			//alert(resource);
		}

		function update_resource_name(resource){
			var resource_type = document.getElementById("resource_type").value;
			if(resource_type == "source"){
				document.getElementById("resource_select_form").value = resource;
			}else{
				document.getElementById("destination_resource_select_form").value = resource;
			}
		}

	</script>
<!--===============================================================================================-->
	<script src="form_css/vendor/daterangepicker/moment.min.js"></script>
	<script src="form_css/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="form_css/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	{{-- <script src="form_css/vendor/noui/nouislider.min.js"></script> --}}
	<script>
	    // var filterBar = document.getElementById('filter-bar');

	    // noUiSlider.create(filterBar, {
	    //     start: [ 1500, 3900 ],
	    //     connect: true,
	    //     range: {
	    //         'min': 1500,
	    //         'max': 7500
	    //     }
	    // });

	    // var skipValues = [
	    // document.getElementById('value-lower'),
	    // document.getElementById('value-upper')
	    // ];

	    // filterBar.noUiSlider.on('update', function( values, handle ) {
	    //     skipValues[handle].innerHTML = Math.round(values[handle]);
	    //     $('.contact100-form-range-value input[name="from-value"]').val($('#value-lower').html());
	    //     $('.contact100-form-range-value input[name="to-value"]').val($('#value-upper').html());
	    // });
	</script>
<!--===============================================================================================-->
	<script src="form_css/js/main.js"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
{{-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-23581568-13');
</script> --}}

</body>
</html>
