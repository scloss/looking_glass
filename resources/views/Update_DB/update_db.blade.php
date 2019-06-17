<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update DB</title>
</head>
<body>

<form action="update_db_post" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div>
        <select name="table_name">
            <option value="select">Select</option>
            <option value="Resource Dump">Resource Dump</option>
            <option value="Alarm Dump">Alarm Dump</option>
            <option value="Throughput Dump">Throughput Dump</option>
            <option value="Admin Oper Dump">Admin Oper Dump</option>
            <option value="Node Dump">Node Dump</option>
        </select>
    </div>
    <br/>
    <div>
        <label for="file">Alarm/Throughput/Resource</label>
        <input type="file" name="file" id="file">
    </div>
    <br/>
    <hr/>
    <div>
        <label for="admin">Admin Status</label>
        <input type="file" name="admin" id="admin">
    </div>
    <div>
        <label for="oper">Oper Status</label>
        <input type="file" name="oper" id="oper">
    </div>
    <hr/>
    <div>
        <label for="node_dump">Node Dump</label>
        <input type="file" name="node_dump" id="node_dump">
    </div>
    <div>
        <label for="disabled_node">Disabled Node</label>
        <input type="file" name="disabled_node" id="disabled_node">
    </div>

     <br/>
     <br/>

    <div>
        <input type="submit" value="Upload" name="submit">
    </div>
</form>
    
</body>
</html>