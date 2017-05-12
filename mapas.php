<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <title>KML Layers</title>
    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 100%;
        }
        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<div id="map"></div>
<script>

    function initMap() {

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 11,
            center: {lat: 2.466441, lng: -76.585801}
        });

        var ctaLayer = new google.maps.KmlLayer({
            url: 'https://raw.githubusercontent.com/AndresGJ144/mobilitycity/master/Rutacompleta.kml',
            map: map
        });

        var icono = new GIcon();
        icono.image= 'img/map-marker.png';
        icono.iconSize = new GSize(12, 20);
        icono.iconAnchor = new GPoint(2, 2);

        var punto = new GLatLng(2.466441,-76.585801);
        map.addOverlay(new GMarker(punto,icono));
    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAHOZFL6ZqfT9wcpPuq9lEvqBJFUw7XWrA&callback=initMap">
</script>
</body>
</html>