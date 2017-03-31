    var mapLat = 0.0;
    var mapLon = 0.0;

    console.log(lastlocations.length);

    var locationcounter = 0;
    for (i = 0; i < lastlocations.length; i++) 
    { 
	if (lastlocations[i][3] != '0' && lastlocations[i][4] != '0')
	{
        	mapLat += parseFloat(lastlocations[i][3]);
		mapLon += parseFloat(lastlocations[i][4]);
		locationcounter++;
	}	
    }
	console.log(mapLat);
	console.log(mapLon);
    
	mapLat = mapLat / locationcounter;
    	mapLon = mapLon / locationcounter;

	console.log(mapLat);
	console.log(mapLon);



    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 16,
      //center: new google.maps.LatLng( 52.16114, 5.02915),
      center: new google.maps.LatLng( mapLat, mapLon),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
	
		
    var infowindow = new google.maps.InfoWindow();
    var marker, i;
    var flightPlanCoordinates = [];
    var bounds = new google.maps.LatLngBounds();

    for (i = 0; i < lastlocations.length; i++) 
    {  
	if (lastlocations[i][3] != '0' && lastlocations[i][4] != '0')
	{
	
		marker = new google.maps.Marker({
                        position: new google.maps.LatLng(lastlocations[i][3], lastlocations[i][4]),
                        icon: icons[(lastlocations[i][0])].icon,
                        map: map,
                });
		bounds.extend(marker.position);
      		flightPlanCoordinates.push(marker.position);

      		google.maps.event.addListener(marker, 'click', (function(marker, i) {
        		return function() {
          		var date = new Date(lastlocations[i][1]);
          		infowindow.setContent('<div><strong>' + 
				nummer[(lastlocations[i][0])].name + ' ' + date.toString() + '<br/> ' +
				'RSSI : ' + lastlocations[i][2]+ 
				' COUNT : ' + lastlocations[i][5]+ 
				' VOLTAGE : ' + lastlocations[i][6]+ 
				'</strong></div>');
          	infowindow.open(map, marker);
        	}
      		})(marker, i));
	}
    }

    if (showFlightPath)
    {
    	var flightPath = new google.maps.Polyline({
          	path: flightPlanCoordinates,
          	geodesic: true,
          	strokeColor: '#FF0000',
          	strokeOpacity: 1.0,
          	strokeWeight: 2
    	});
    	flightPath.setMap(map);
    }
    map.setCenter(bounds.getCenter()); //or use custom center
    map.fitBounds(bounds);
