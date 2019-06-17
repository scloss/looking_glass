<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Choose Resource</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</head>

<body>

    {{-- Navigation bar --}}
    <nav>
        <div class="nav-wrapper grey darken-4">
            <a href="#!" class="brand-logo">  Resources</a>
            <ul class="right hide-on-med-and-down">
                <li><a href="#">search</a></li>
                <li><a href="#">view_module</a></li>
                <li><a href="#">refresh</a></li>
                <li><a href="#">more_vert</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        {{-- Input Field --}}
        <div class="col card hoverable s10 pull-s1 m6 pull-m3 l4 pull-l4">
            
                <div class="card-content">
                    <span class="card-title">Choose Resource Name</span>
                    <div class="row">
                        <div class="input-field col s12">
                        <input id="resource_name" type="text" class="validate" value="{{$resource_name}}">
                        </div>
                    </div>
                </div>
                <div class="card-action right-align">
                    <button class="btn waves-effect waves-light" onclick="submit();">Submit
                    </button>
                </div>
            

        </div>
        {{-- Table --}}
        <table class="responsive-table highlight">
            <thead>
                <tr>
                    <th>Resource Name</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @foreach($resource_data as $row)
                    <tr>
                        <td>{{$row->resource_name}}</td>
                        <td>
                            <button class="btn waves-effect waves-light" type="submit" name="action" onclick="choose_resource('{{$row->resource_name}}')">Add</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>

<script>
    function choose_resource(resource_name){
        //alert(resource_name);

        document.getElementById("resource_name").value = resource_name;
    }

    function submit(){
        var resource = document.getElementById("resource_name").value;
        //alert(resource);
        window.opener.update_resource_name(resource);
        window.close();
        return false;
    }
</script>