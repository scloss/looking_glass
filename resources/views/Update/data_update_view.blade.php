@include('Nav.header')
<body>
	<table id="customers">
	  <tr>
	    <th>ID</th>
	    <th>Name</th>
	    <th>Created Time</th>
	    <th>Deleted Time</th>
	  </tr>
	  @foreach($info_lists as $info_list)
	  	<tr>
		    <td>{{$info_list->table_row_id}}</td>
		    <td>{{$info_list->name}}</td>
		    <td>{{$info_list->row_created_time}}</td>
		    <td>{{$info_list->row_update_time}}</td>
		 </tr>
	  @endforeach
	  
	  
	</table>
</body>
@include('Nav.footer')