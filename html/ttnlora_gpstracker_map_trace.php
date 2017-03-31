
<html> 
<head> 
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 
  <title>TTN GPS Tracker</title> 
  <link rel="stylesheet" type="text/css" href="ttnlora_gpstracker_map.css">
  <?php
	// include the global vars
	include './ttnlora_gpstracker_vars.php';

  	echo '<script src="https://maps.googleapis.com/maps/api/js?key=';
	echo $googleMapApiKey;
	echo '" type="text/javascript"></script>';
  ?>

  <script src="./ttnlora_gpstracker_trace_dates.php"></script>
  <script src="./ttnlora_gpstracker_trace_codes.php"></script>
  <script src="./ttnlora_gpstracker_vars.js" type="text/javascript"></script>

  <?php
  	echo '<script src="./ttnlora_gpstracker_trace.php';
	echo '?';
	$date = $_GET['date'];	
  	echo 'date=';
       	echo $date;
	echo '&';
	$id = $_GET['id'];	
  	echo 'id=';
       	echo $id;
  	echo '" type="text/javascript"></script>';
  ?>

 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>

    var showFlightPath = true;

	function zeroPad(num, places) {
  		var zero = places - num.toString().length + 1;
  		return Array(+(zero > 0 && zero)).join("0") + num;
	}

	function available(date) {
  		dmy = date.getFullYear() + "-" + zeroPad((date.getMonth()+1),2) + "-" + zeroPad(date.getDate(),2);
  		if ($.inArray(dmy, availableDates) != -1) {
    			return [true, "","Available"];
  		} else {
    			return [false,"","unAvailable"];
  		}
	}

	function setSelectedValue(selectObj, valueToSet) {
    		for (var i = 0; i < selectObj.options.length; i++) {
        		if (selectObj.options[i].text== valueToSet) {
            			selectObj.options[i].selected = true;
            			return;
        		}
    		}
	}

  $( function() {

	var $datepicker = $('#datepicker');
	$datepicker.datepicker({
      		dateFormat: "yy-mm-dd",
                beforeShowDay: available,
      		onSelect: function(dateText) {
        	$(this).change();
      		}
    	});
	// set the selected date
	$datepicker.datepicker("setDate", selected_date);

	var $deviceidpicker = $('#deviceidpicker');
        $.each(availableCodes, function(val, text)
        {
                var itemval = text;
                var itemtext = nummer[itemval].name;
                $deviceidpicker.append( $('<option></option>').val(itemval).html(itemtext) )
        });

	$deviceidpicker.val(selected_id);

	var $refreshbutton = $('#refreshbutton');
        $refreshbutton.click(function() {
                var e = document.getElementById('deviceidpicker');
                var deviceid = e.options[e.selectedIndex].value;
                var date = $('#datepicker').datepicker({ dateFormat: 'dd-mm-yy' }).val();
                window.location.href = "ttnlora_gpstracker_map_trace.php?date=" + date + "&id=" + deviceid;
        });
  } );
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
  <tr>
    <th align="left" scope="col">&nbsp;</th>
    <td align="center" colspan="3" align="center">Kies een datum : <input type="text" id="datepicker"> <label for="devicelabel">Selecteer een boot</label>
    <select name="deviceid" id="deviceidpicker"></select>
    <button name="refresh" id="refreshbutton">Refresh</button></td>
    <th align="right" scope="col">&nbsp;</th>
  </tr>
</table>
<hr />
  <div id="map" style="width: 100%; height: 800px;"></div>
  <script src="./ttnlora_gpstracker_map_code.js" type="text/javascript"></script>
  </body>
</html>
