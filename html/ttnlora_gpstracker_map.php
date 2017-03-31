<html> 
<head> 
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 
  <title>TTN GPS Tracker</title> 
  <link rel="stylesheet" type="text/css" href="ttnlora_gpstracker_map.css">
  <?php
	// include the global vars
	include './ttnlora_gpstracker_vars.php';
  	//<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDmN7mtrcr4b6c6PtGUUmxknZvk5cUnmWI" type="text/javascript"></script>
  	echo '<script src="https://maps.googleapis.com/maps/api/js?key=';
	echo $googleMapApiKey;
	echo '" type="text/javascript"></script>';
  ?>
  <script src="./ttnlora_gpstracker_last.php" type="text/javascript"></script>
  <script>
    var showFlightPath = false;
  </script>

</head> 
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <th width="10%" rowspan="2" align="left" scope="col"><img src="images/JCLogo.jpg" height="50%" /></th>
    <th colspan="3" align="center" ><h1>Track a Vlet</h1></th>
    <th width="10%" rowspan="2" align="right" scope="col"><img src="images/ttnlogo.jpg" height="50%" /></th>
  </tr>
  <tr>
    <td width="26.6%" align="right"><a href="https://ttn.pd5wl.nl">Home</a></td>
    <td width="26.6%" align="center"><a href="./ttnlora_gpstracker_map.php">Laatst bekende positie</a></td>
    <td width="26.6%" align="left"><a href="./ttnlora_gpstracker_map_trace.php">Track and Trace</a></td>
  </tr>
</table>
<hr />
  <div id="map" style="width: 100%; height: 700px;"></div>
  <script src="./ttnlora_gpstracker_vars.js" type="text/javascript"></script>
  <script src="./ttnlora_gpstracker_map_code.js" type="text/javascript"></script>
  </body>
</html>
