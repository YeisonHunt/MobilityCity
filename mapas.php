<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Waypoints in directions</title>
    <style>
        #right-panel {
            font-family: 'Roboto','sans-serif';
            line-height: 30px;
            padding-left: 10px;
        }

        #right-panel select, #right-panel input {
            font-size: 120%;
        }

        #right-panel select {
            width: 100%;
        }

        #right-panel i {
            font-size: 120%;
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        /*
        #map {
            height: 100%;
            float: left;
            width: 65%;
        }
        */
        #right-panel {
            margin: 0%;
            border-width: 2px;
            width: 30%;
            height: 500px;
            float: right;
            text-align: left;
            padding-top: 0;
            overflow: auto;
        }
        #directions-panel {
            margin-top: 10px;
            background-color: #FFEE77;
            padding: 10px;
        }
    </style>
</head>
<body>
<h2>Trazar ruta</h2>

<div id="map_trazarRuta" style="width: 65%; height: 500px; float: left;" ></div>
<div id="right-panel">
    <div>
        <b>Start:</b>
        <select id="start">
            <option value="Cra. 9 #54a Norte-182, Popayán, Cauca, Colombia">Norte</option>
            <option value="Timbio">Timbio</option>

        </select>
        <br>
        <!--
        <b>Waypoints:</b> <br>
        <i>(Ctrl+Click or Cmd+Click for multiple selection)</i> <br>
        <select multiple id="waypoints">
            <option value="cementerio central, popayan">cementerio central, popayan</option>
            <option value="estadio ciro lopez, popayan">estadio ciro lopez, popayan</option>
        </select>
        <br>
        -->

        <b>Tipo de Viaje</b> 
        <select id="travelMode" class="routeOptions" >
            <option value="DRIVING" selected="selected">En Auto</option>
                      	<option value="BICYCLING">En Bicicleta</option>
                      	<option value="WALKING">Caminando</option>
                  	</select><br/>
        <b>End:</b>
        <select id="end">
            <option value="Cra. 9 #54a Norte-182, Popayán, Cauca, Colombia">Norte</option>
            <option value="bello horizonte, Popayan">bello horizonte, Popayan</option>
        </select>
        <br>
        <input type="submit" id="submit">
    </div>
    <div id="directions-panel"></div>
</div>
<script>
    var map = null;
    var directionsDisplay = null;
    var directionsService = null;

    function initMap_trazarRuta() {
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
        var map = new google.maps.Map(document.getElementById('map_trazarRuta'), {
            zoom: 13,
            center: {lat: 2.463697, lng: -76.58462}
        });

        directionsDisplay.setMap(map);
        document.getElementById('submit').addEventListener('click', function() {
            calculateAndDisplayRoute(directionsService, directionsDisplay);
        });

    }

    function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        var waypts = [];
        waypts.push({
            location: "cementerio central, popayan",
            stopover: false
        });
        /*
        var checkboxArray = document.getElementById('waypoints');
        for (var i = 0; i < checkboxArray.length; i++) {
            if (checkboxArray.options[i].selected) {
                waypts.push({
                    location: checkboxArray[i].value,
                    stopover: false
                });
            }
        }
        */

        directionsService.route({
            origin: document.getElementById('start').value,
            destination: document.getElementById('end').value,
            waypoints: waypts,
            optimizeWaypoints: false,
            travelMode: document.getElementById('travelMode').value
        }, function(response, status) {
            if (status === 'OK') {
                directionsDisplay.setDirections(response);
                var route = response.routes[0];
                var summaryPanel = document.getElementById('directions-panel');
                summaryPanel.innerHTML = '';
                // For each route, display summary information.
                for (var i = 0; i < route.legs.length; i++) {
                    var routeSegment = i + 1;
                    summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment + '</b><br>';
                    summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
                    summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
                    summaryPanel.innerHTML += route.legs[i].duration.text + '<br>';
                    summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
                }
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
    }

    $('.routeOptions').live('change', function(){ calculateAndDisplayRoute(directionsService, directionsDisplay); });

    $(document).ready(function() {
        initMap_trazarRuta();

    });
</script>
<script  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBp8tOsgWyAmX1ZnO-CPhpnqwuaZgx0uzc&callback=initMap_trazarRuta"></script>
</body>
</html>