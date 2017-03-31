
<?php

// include the global vars
include './ttnlora_gpstracker_vars.php';

header('Content-type: application/json');
$code = "";
$dev_id = ""; 
$app_id = ""; 
$lat = ""; 
$time = ""; 
$lng = ""; 
$rssi = ""; 
$count = ""; 
$voltage = ""; 


$filter_id = $_GET["id"];
$filter_date = $_GET["date"];

echo "// filter_id=$filter_id\n";
echo "// filter_date=$filter_date\n";


echo "var selected_date = '$filter_date';\n";
echo "var selected_id = '$filter_id';\n";

function getData($parent, $arr)
{
	//echo "parent = $parent\n";
	global $dev_id,$app_id,$lat,$lng,$time,$rssi,$count,$code,$voltage;
	foreach($arr as $key => $value)
	{
		if (is_numeric($value))
		{
			//echo "0.key = $key\n";
			{
				switch($key) {
					case "lat" : $lat = $value; break;
					case "lng" : $lng = $value; break;
					case "rssi" : $rssi = $value; break;
					case "counter" : $count = $value; break;
					case "code" : $code = $value; break;
					case "voltage" : $voltage = $value; break;
				}
			}
		}
		else if (is_array($value))
		{
			getData($key, $value);
		}
		else if (is_object($value))
		{
			getData($key, $value);
		}
		else 
		{
			if ($parent == "metadata" && !is_numeric($parent))
			{
				//echo "1.key = $key\n";
				switch($key) {
					case "time" : $time = $value; break;
				}
			}
			else
			{
				//echo "3.key = $key\n";
				switch($key) {
					case "dev_id" : $dev_id = $value; break;
					case "app_id" : $app_id = $value; break;
				}
			}
		} 
	}	
}

//$filename = '/home/lex/Zendamateur_www/TTN/PH2LB_gpstracker_ttnlora.log';
$filestream = fopen($filename, "r") or die("Unable to open file!");
/*
 var locations = [
      ['Lelievlet 014', 'lvgeel', 52.16118, 5.02909, 4],
      ['Lelievlet 283', 'lvbruin', 52.16116, 5.02911, 3],
      ['Lelievlet 384', 'lvoranje', 52.16120, 5.02913, 2],
      ['Lelievlet 1235', 'lvgroen', 52.16114, 5.02915, 1],
      ['Sleper', 'slblauw', 52.16112, 5.02917, 5]
    ];
*/

echo "var lastlocations = [\n";
$first = true;
while(($line = fgets($filestream)) != false)
{
	# reset value
	$dev_id = ""; 
	$app_id = ""; 
	$time = ""; 
	$lat = ""; 
	$lng = ""; 
	$rssi = ""; 
	$count = ""; 
	$voltage = ""; 

	$my_arr = json_decode($line);
 	getData("", $my_arr);

	if (!empty($code) && !empty($time) && !empty($rssi) && !empty($lat) && !empty($lng) && !empty($count))
	{
		//time = 2017-03-28T06:23:45.439548795Z
		$utcpart = explode(".", $time);
		$time = "$utcpart[0].000Z";
    		//time = 2010-12-07T23:12:34.4556Z,
		//echo "time = $time\n";
		$dt = DateTime::createFromFormat("Y-m-d\TH:i:s.u\Z", $time);
		$date = $dt->format('Y-m-d');
		//echo "date = $date\n";
                if (($filter_id == $code || $filter_id == '') &&
                	($filter_date == $date || $filter_date == '') &&
			($lat <= 90.0 && $lat >= -90.0 && $lng <= 90.0 && $lng >= -90.0))
		{
			$value = "['$code','$time','$rssi','$lat','$lng','$count','$voltage']";
    			if (!$first)
    			{
    				echo ",\n";
		
    			}
    			else
    			{
				$first = false;
    			}
    			echo "    $value";
		}
	}
}
fclose($filestream);

echo "\n];\n";

?>
