<?PHP
function readCSV($csvFile){
    $file_handle = fopen($csvFile, 'r');
    while (!feof($file_handle) ) {
        $line_of_text[] = fgetcsv($file_handle, 1024);
    }
    fclose($file_handle);
    return $line_of_text;
}
 
 
// Set path to CSV file
$csvFile = 'test.csv';
 
$csv = readCSV($csvFile);

$insert_query = "INSERT INTO looking_glass.`everest_data` (`host_name`, `resource_name`, `resource_alias`, `average`, `minimum`, `maximum`, `ninty_fifth_percentile`, `created_time`) VALUES";

$serial = 0;
for($i = 4; $i < count($csv); $i++ ){
    //print_r($csv[$i]);
    //echo $serial;
    $line = array();
    $line = $csv[$i]; 
    // print_r($line);
    

    $host_name = $line[0];
    $resource_name = $line[1];
    $resource_alias = $line[2];
    $average = $line[5];
    $minimum = $line[6];
    $maximum = $line[7];
    $ninty_fifth_percentile = $line[7];
    $created_time = "CURRENT_TIMESTAMP";

    // echo "('$host_name', '$resource_name', '$resource_alias', '$average', '$minimum', '$maximum', '$ninty_fifth_percentile', $created_time),";
    // echo "<br>";
    $insert_query .= "('$host_name', '$resource_name', '$resource_alias', '$average', '$minimum', '$maximum', '$ninty_fifth_percentile', $created_time) ,";
    $serial += 1;
}



$insert_query = rtrim($insert_query,",");
//echo $insert_query;

$truncate_query = "TRUNCATE TABLE looking_glass.`everest_data`";


$mysql_con = mysqli_connect('localhost', 'root', '', 'looking_glass');
	if (!$mysql_con) {
	      die("Connection failed: " . mysqli_connect_error());
	}

	if (mysqli_query($mysql_con, $truncate_query)) {
		echo "Truncated everest_data table <br>";
	      
	}



	if (mysqli_query($mysql_con, $insert_query)) {
		$serial += 1;
		echo "Inserted in everest_data <br>";
	      
	} 
	else {
	    echo "Insert failed" ;
	    
	}

mysqli_close($mysql_con);


// echo '<pre>';
// print_r($csv);
// echo '</pre>';
?>