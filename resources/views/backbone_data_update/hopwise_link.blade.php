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
	<link rel="stylesheet" type="text/css" href="form_css/vendor/noui/nouislider.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="form_css/css/util.css">
	<link rel="stylesheet" type="text/css" href="form_css/css/main.css">
<!--===============================================================================================-->
<style>
	
	
		select { width: 200px }
	
		.padding_10{
			padding: 10px;
		}
	</style>
</head>
<body>


	<div class="container-contact100">
		<div class="wrap-contact100">
			<form class="contact100-form validate-form">
				<span class="contact100-form-title">
					Hopwise Link Name
				</span>

				<ol id="result">

				</ol>
				

                
                <div class="col-md-12">
					<span class="col-md-4 col-md-offset-2 float-left">Select Hop:</span>
					<span >
					<select id="hop" >
						<option>Please choose</option>
							@foreach($host_names as $row)
								<option >{{$row->host_name}}</option>	
							@endforeach
					</select>
					</span>
					<a class="col-md-4 col-md-offset-2 float-right btn btn-info" onclick="add_item();">ADD</a>
				</div>
				<br/>
				<br/>
				<br/>
				<div class="wrap-input100 validate-input bg1" data-validate="Please Your Name">
						<span class="label-input100">HOPWISE LINKNAME</span>
						<input class="input100" type="text" name="hop_text" id="hop_text">
				</div>

				<div class="container-contact100-form-btn">
					<a class="contact100-form-btn" onclick="submit();">
						<span>
							Submit
							<i class="fa fa-long-arrow-right m-l-7" aria-hidden="true"></i>
						</span>
					</a>
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
	
<!--===============================================================================================-->
	<script src="form_css/vendor/daterangepicker/moment.min.js"></script>
	<script src="form_css/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="form_css/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="form_css/vendor/noui/nouislider.min.js"></script>
	<script>
	    // 
		
		function add_item(){
			var text_value = document.getElementById("hop").value;
			var current = document.getElementById("hop_text").value;

			var result = "";
			if(current != ""){
				result = current + "|" + text_value;
			}
			else{
				result = text_value;
			}
			
			document.getElementById("hop_text").value = result;
			// // var node = document.createElement("LI");
			// // var textnode = document.createTextNode(text_value);
			// // node.appendChild(textnode);
			// // document.getElementById("result").appendChild(node);
			// console.log("test");
		}


		function submit(){

			var current = document.getElementById("hop_text").value;
			window.opener.add_hopwise_linkname(current);
   	 		window.close();

			return false;
		}
	</script>
<!--===============================================================================================-->
	{{-- <script src="js/main.js"></script> --}}

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-23581568-13');
</script>

</body>
</html>
