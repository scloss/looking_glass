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


	<div class="container-contact100">
		<div class="wrap-contact100">
			<form class="contact100-form validate-form" action="{{ url('insert_bb_data') }}" method="post">
				<span class="contact100-form-title">
					Backbone Data
				</span>

				
							<div class="col-md-12">
									<span class="col-md-4 col-md-offset-2 float-left">Source:</span>
									<span class="col-md-4 col-md-offset-2 float-right">
										<select name="source" id="source" onchange="onChange_Source()">
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
						<select name="resource" id="resource_select">
							<option>Please choose</option>
						</select>
					</span>
				</div>

				<div class="col-md-12">
					<span class="col-md-4 col-md-offset-2 float-left">IP Address:</span>
					<span class="col-md-4 col-md-offset-2 float-right">
						<input type="text" name="ip_address" id="ip_address">
					</span>
				</div>


					<div class="col-md-12">
						<span class="col-md-4 col-md-offset-2 float-left">Destination:</span>
						<span class="col-md-4 col-md-offset-2 float-right">
							<select name="destination" id="destination" onchange="onChange_Destination()">
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
							<select name="destination_resource" id="destination_resource_select">
								<option>Please choose</option>
							</select>
						</span>
					</div>

					<div class="col-md-12">
							<span class="col-md-4 col-md-offset-2 float-left">Division:</span>
							<span class="col-md-4 col-md-offset-2 float-right">
								<input type="text" name="division" id="division">
							</span>
					</div>

					<div class="col-md-12">
							<span class="col-md-4 col-md-offset-2 float-left">District:</span>
							<span class="col-md-4 col-md-offset-2 float-right">
								<input type="text" name="district" id="district">
							</span>
					</div>
				
					<div class="col-md-12">
							<span class="col-md-4 col-md-offset-2 float-left">Link Name:</span>
							<span class="col-md-4 col-md-offset-2 float-right">
								<input type="text" name="link_name" id="link_name">
							</span>
					</div>

					<div class="col-md-12">
							<span class="col-md-4 col-md-offset-2 float-left">Built Capacity:</span>
							<span class="col-md-4 col-md-offset-2 float-right">
								<select name="built_capacity" id="built_capacity">
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
								<input type="text" name="path_status" id="path_status">
							</span>
					</div>

					<div class="col-md-12">
							<span class="col-md-4 col-md-offset-2 float-left">Hopwise Linkname:</span>
							<span class="col-md-4 col-md-offset-2 float-right">
								<input type="text" name="hopwise_linkname" id="hopwise_linkname" onclick="hopwise_link_name();">
							</span>
					</div>

					<div class="col-md-12">
							<span class="col-md-4 col-md-offset-2 float-left">Districtwise Linkname:</span>
							<span class="col-md-4 col-md-offset-2 float-right">
								<input type="text" name="districtwise_linkname" id="districtwise_linkname" onclick="districtwise_link_name();">
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
			document.getElementById("hopwise_linkname").value = link_name;
		}

		function add_districtwise_linkname(link_name){
			document.getElementById("districtwise_linkname").value = link_name;
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
