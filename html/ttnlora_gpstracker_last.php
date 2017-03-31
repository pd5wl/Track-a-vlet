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

$vlet_array = array();

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
		$value = "['$code','$time','$rssi','$lat','$lng']";
		$vlet_array[$code] = $value;
	}
}
fclose($filestream);
echo "var lastlocations = [\n";
$first = true;
foreach ($vlet_array as $key => $value) 
{
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

echo "\n];\n";

?>
