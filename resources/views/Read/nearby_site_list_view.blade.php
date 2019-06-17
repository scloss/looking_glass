@include('Nav.header')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
$(document).ready(

	function () {
    $("#client").change(function () {
        var val = $(this).val();
        if (val == "ROBI") {
        	//alert('Robi');
            $("#site_id").html(
            	<?php
            	$options = "";
            	foreach($robi_site_ids as $robi_site_id){
            		$options.="<option value='$robi_site_id->node_name'>$robi_site_id->node_name</option>";

            	}
            	echo "\"$options\"";
            	?>           	
            	);
        } 


        else if (val == "GP") {
           //alert('GP');
           $("#site_id").html(

           		<?php
            	$options = "";
            	foreach($gp_site_ids as $gp_site_id){
            		$options.="<option value='$gp_site_id->node_name'>$gp_site_id->node_name</option>";

            	}
            	echo "\"$options\"";
            	?>
            	);
        } 

        else if (val == "BANGLALINK") {
        	//alert('BANGLALINK');
           $("#site_id").html(
           		<?php
            	$options = "";
            	foreach($bl_site_ids as $bl_site_id){
            		$options.="<option value='$bl_site_id->node_name)'>$bl_site_id->node_name</option>";

            	}
            	echo "\"$options\"";
            	?>
            	);
        } 

    });
});


</script>

<body>
	<form action="{{url('search_site')}}" method="post">
		
		<div class="col-md-12">
			<center>
		  	<div class="col-md-6">
		  		<br><br>
		  		<center><h3>Search Sites</h3></center>
		  		


			    <label for="client"><b>Client</b></label>

			    <select id="client" name="client">
			    	<option value= "" selected disabled>SELECT</option>

			    	@foreach($client_lists as $client_list)
			    		@if($client_list->client != "")
			    			<option value= "{{$client_list->client}}" >{{$client_list->client}}</option>
			    		@endif	
			    	@endforeach
				  
				</select>
				<br>
				<br>

				<label for="site_id"><b>Site Name</b></label>
				<select id="site_id" name="site_id">
					<option value= "" selected disabled>Please select Client First</option>

				</select>
			    
				<meta name="csrf-token" content="{{ csrf_token() }}">
			    
			    <input type="hidden" name="_token" value="{{ csrf_token() }}">
			      
			    <button type="submit" >Search Sites</button>
			    
		    </div>
		</center>
		</div>

	</form>
</body>
@include('Nav.footer')