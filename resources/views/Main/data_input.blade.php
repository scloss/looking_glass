@include('Nav.header')
<body>
	<form action="{{url('DataInput')}}" method="post">		
		<div class="col-md-12">
			<center>
		  	<div class="col-md-6">
		  		<br><br>
			    <label for="name"><b>Name</b></label>
			    <input type="text" placeholder="Enter Name" name="name" required>
			    <input type="hidden" name="_token" value="{{ csrf_token() }}">
			      
			    <button type="submit">Save</button>
			    
		    </div>
			</center>
		</div>

	</form>
</body>
@include('Nav.footer')