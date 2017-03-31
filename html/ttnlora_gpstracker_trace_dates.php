
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

function getData($parent, $arr)
{
	//echo "parent = $parent\n";
	global $dev_id,$app_id,$lat,$lng,$time,$rssi,$count,$code;
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

$dates = [];

echo "var availableDates = [\n";
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
		
		if (!in_array($date, $dates, true))
		{
			$dates[]= $date;

			//echo "date = $date\n";
			$value = "'$date'";
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
