@include('Nav.header')
<body>
	<br>
	<center>Near by sites of : {{$site_id}}</center>
	<center>** Showing sites within 6 kilometers **</center>
	<center>Number of sites: {{$count}}</center>
	<center><a href="{{url('ShowNearBySiteList')}}">Go Back</a></center>
	<table>
		<tr>
			<th><center>Sl</center></th>
			<th><center>Site Name</center></th>
			<th><center>Distance</center></th> 
		</tr>

		<br>
			<?php
			$sl = 0;
			?>
		

			@foreach($total_site_lists as $key => $value)
				@if($key != $site_id)
					<tr>
					<td><center>{{$sl+1}}</center></td>
					<td><center>{{$key}}</center></td>
					<td><center>{{$value}} km</center></td>
					</tr>
					<?php
					$sl = $sl +1;
					?>
				@endif
			@endforeach
			
		

	</table>
</body>
@include('Nav.footer')