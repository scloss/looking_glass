@include('Nav.header')
<body>
	<form action="{{url('authenticate')}}" method="post">
		
		<div class="col-md-12">
			<center>
		  	<div class="col-md-6">
		  		<br><br>
			    <label for="uname"><b>Username</b></label>
			    <input type="text" placeholder="Enter Username" name="user_name" required>

			    <label for="psw"><b>Password</b></label>
			    <input type="password" placeholder="Enter Password" name="user_password" required>
			    <input type="hidden" name="_token" value="{{ csrf_token() }}">
			      
			    <button type="submit">Login</button>
			    <label>
			      <span class="psw">Forgot <a href="#">password?</a></span>
			    </label>
		    </div>
		</center>
		</div>

	</form>
</body>
@include('Nav.footer')