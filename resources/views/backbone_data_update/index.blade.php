<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Backbone Data</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <style>
    #example1 {
      table-layout: fixed;
      width: 100% !important;
    }
    #example1 td,
    #example1 th{
      width: auto !important;
      white-space: normal;
      text-overflow: ellipsis;
      overflow: hidden;
      word-wrap: break-word;
    }
  </style>
</head>
<body style="background-color: #989090;overflow: auto;white-space: nowrap;">

 <!-- Main content -->
 <section class="content">
      <div class="row">
        <div class="col-xs-12">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Backbone Data</h3>
              
            </div>
            <div class="box-header">
              <span><a class="btn btn-primary" href="insert_bb_data_view" role="button">+</a></span>
              {{-- <span><a class="btn btn-primary" href="export_bb_data" role="button"><i class="fa fa-download"></i></a></span> --}}
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sl</th>
                  <th>Source</th>
                  <th>Resource</th>
                  <th>Ip Address</th>
                  <th>Destination</th>
                  <th>Destination Resource</th>
                  <th>Division</th>
                  <th>District</th>
                  <th>Link Name</th>
                  <th>Built Capacity</th>  
                  <th>Path Status</th>
                  <th>Hopwise Link Name</th>
                  <th>Districtwise Link Name</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>

                  @foreach($data as $row)
                  <tr>
                    <td>{{$row->row_id}}</td>
                    <td>{{$row->source}}</td>
                    <td>{{$row->resource}}</td>
                    <td>{{$row->ip_address}}</td>
                    <td>{{$row->destination}}</td>
                    <td>{{$row->destination_resource}}</td>
                    <td>{{$row->division}}</td>
                    <td>{{$row->district}}</td>
                    <td>{{$row->link_name}}</td>
                    <td>{{$row->built_capacity}}</td>
                    <td>{{$row->path_status}}</td>
                    <td>{{$row->hopwise_link_name}}</td>
                    <td>{{$row->districtwise_link_name}}</td>
                    <td>
                        
          
                        <a class="btn btn-primary"  href="bb_update_view?id={{$row->row_id}}" role="button" style="margin:3px; width: 100px">Update</a>
                        <form class="delete" action="bb_destroy?id={{$row->row_id}}" method="post" style='display:inline;'>
                            {{csrf_field()}}
                            <input  type="hidden" name="id" value="{{$row->row_id}}" style='display:inline;'>
                            <button class="btn btn-danger" type="submit" style="margin:3px; width: 100px" style='display:inline;'>Delete</button> 
                        </form>
                    </td>                    
                  </tr>
                @endforeach
                
                
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->




<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>

<script>

$(".delete").on("submit", function(){
return confirm("Do you want to delete this item?");
});
</script>

</body>
</html>
