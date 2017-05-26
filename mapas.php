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

<?php

require ("conexion.php");

/* Método para llamar a trafico*/

/*Ubicaion de los sensores (solamente 1 2 3 4 son reales .. los demas se
 * calculan en base a los anteriores)
 *
 * ----------------<------10---------<-2---------------------------
 * -----------------------1->---------7--->------------------------
 *         | |                  |  5                     |  |
 *         | |                  |  |                     |  |
 *         | |                  |  |                     |  |
 *         | |                  6  |                     |  |
 *          ------------<---9-----------<--4----------------
 *          ----------------3-->-----------8---->-----------
 * */

$sql3 = "SELECT * from trafico  order by traNum DESC";
$result3 = $conn ->query($sql3);
$rowTraf = $result3->fetch_array(MYSQLI_NUM); // Devuelve la ID de la fila como número, con MYSQLI_ASSOC la devuelve con el nombre

$T_Medio = 10;
$T_Bajo = 5;
$N_Ruta = 0;
/*
$uno= $rowTraf[3];
$dos = $rowTraf[4];
$tres = $rowTraf[5];
$cuatro= $rowTraf[6];
*/
$uno= 4;
$dos = 6;
$tres = 12;
$cuatro= 5;

$cinco = (0.4 * $tres) + (0.3 * $cuatro);
$seis = (0.4 * $uno) + (0.2 * $dos);
$siete = (0.6 * $uno) + (0.5 * $cinco);
$ocho = (0.6 * $tres) + (0.5 * $seis);
$nueve = (0.6 * $cuatro) + (0.65* $seis);
$diez = (0.8 * $dos) + (0.5 * $cinco);

//Ruta 1
if( ( $uno < $T_Medio && $siete <= $seis)                               ||  ( $dos < $T_Medio &&  $diez <= $seis)){
    $N_Ruta = 1;
}elseif ( ($uno < $T_Medio &&  $seis <= $siete)                         ||  ( ($dos > $T_Medio || $ocho < $T_Bajo)  &&   $cinco < $nueve && $cinco < $T_Bajo) ){
    $N_Ruta = 2;
}elseif( (($uno > $T_Medio) || ( $tres < $T_Bajo  && $cinco <= $ocho))    ||   ( $dos < $T_Bajo && $seis <= $diez) ){
    $N_Ruta = 3;
}elseif( ( ($uno > $T_Medio) || ($tres < $T_Bajo && $ocho <= $cinco)) ||   (  ($dos > $T_Medio || $ocho < $T_Bajo)  &&  $nueve <= $cinco &&  $nueve < $T_Bajo ) ){
    $N_Ruta = 4;
}else{
    $N_Ruta = 1;
    echo '<script>alert("[Error]  Asignacion de ruta #200")</script> ';
}
?>
<script type="text/javascript"> var ruta = "<?php echo $N_Ruta; ?>";   </script>
<?php

/*
function nivelTrafico ($dato, $N_B, $N_M){
    $resultado = 0;
    if ($dato>=0 || $dato<=$N_B) {
        $resultado = 5;
    }elseif ($dato>=$N_B || $dato<=$N_M){
        $resultado = 10;
    }elseif($dato>=$N_M){
        $resultado = 15;
    }else{
        $resultado = 99;
    }
    return $resultado;
}

$traf_Carr9HNorte = nivelTrafico ($estadoTrafico_Carr9HNorte,   $T_Bajo, $T_Medio);
$traf_Carr9HSur =   nivelTrafico ($estadoTrafico_Carr9HSur,     $T_Bajo, $T_Medio);
$traf_Carr6HNorte = nivelTrafico ($estadoTrafico_Carr6HNorte,   $T_Bajo, $T_Medio);
$traf_Carr6HSur =   nivelTrafico ($estadoTrafico_Carr6HSur,     $T_Bajo, $T_Medio);
*/







?>

<h2>Trazar ruta</h2>
<div id="map_trazarRuta" style="width: 65%; height: 500px; float: left;" ></div>
<div id="right-panel">
    <div>
        <b>Start:</b>
        <select id="start">
            <option value="bella vista, popayan" selected="selected">Norte</option>
            <option value="texaco catay, popayan">Sur</option>

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
            <option value="bella vista, popayan">Norte</option>
            <option value="texaco catay, popayan" selected="selected">Sur</option>
        </select>
        <br>
        <input type="submit" id="submit">
    </div>
    <div id="directions-panel"></div>
</div>

<script type="text/javascript">
    //Primero se determina el sentido en el que va - N->S  S->N
    var waypts = [];
    //Variable que selecciona la ruta
    //var ruta = 1;

    var map = null;
    var directionsDisplay = null;
    var directionsService = null;

    var rutaOrigen = null;
    var rutaDestino = null;
    var direccion = null;



//
// Esto es una idea de la ubicacion de los waypoints
// 4 Y 5 sus paquetes estan repetidos
/*
 * -------------1--------------------------2------------------------
 *         |                    |                           |
 *         |                    5                           |
 *         |                    |                           |
 *         |                    |                           |
 *          --------3----------------------4----------------
 * */

function agregarWaypoint(latitud, longitud){

        waypts.push({
            location: {lat: latitud, lng: longitud},
            stopover: false
        });

}//Fin funcion agregar waypoint

//Estas opciones selleccionan los waypoints
//hacia Sur
    var opc1_HS_lat = 2.45709;
    var opc2_HS_lat = 2.466449;
    var opc3_HS_lat = 2.454367;
    var opc4_HS_lat = 2.466908;
    var opc5_HS_lat = 2.458184;

    var opc1_HS_long = -76.595341;
    var opc2_HS_long = -76.587024;
    var opc3_HS_long = -76.593148;
    var opc4_HS_long = -76.579897;
    var opc5_HS_long = -76.592532;
    //hacia Norte
    var opc1_HN_lat = 2.456984;
    var opc2_HN_lat = 2.466339;
    var opc3_HN_lat = 2.454287;
    var opc4_HN_lat = 2.466908;
    var opc5_HN_lat = 2.458184;

    var opc1_HN_long = -76.595276;
    var opc2_HN_long = -76.586936;
    var opc3_HN_long = -76.593075;
    var opc4_HN_long = -76.579897;
    var opc5_HN_long = -76.592532;

    /*
    rutaOrigen = document.getElementById('start').value;
    rutaDestino = document.getElementById('end').value;
    direccion = "";
    if(rutaDestino == "bella vista, popayan" && rutaOrigen == "texaco catay, popayan"){
        direccion = "norte";
    }
    if(rutaOrigen== "bella vista, popayan" && rutaDestino == "texaco catay, popayan"){
        direccion = "sur";
    }
    if(rutaOrigen== "bella vista, popayan" && rutaDestino == "bella vista, popayan"){
        direccion = "no valido";
        waypts = [];
        ruta = 5;
        alert("Combinacion de direcciones no valida");
    }
    if(rutaOrigen== "texaco catay, popayan" && rutaDestino == "texaco catay, popayan"){
        direccion = "no valido";
        waypts = [];
        ruta = 5;
        alert("Combinacion de direcciones no valida");
    }
    */



    //askñalsdkañlsdkañsldkñlska

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

        /*waypts.push({
            location: {lat: 2.451745, lng: -76.598477},
            stopover: false
        });

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

        rutaOrigen = document.getElementById('start').value;
        rutaDestino = document.getElementById('end').value;
        direccion = "";
        ruta = 3;
        if(rutaDestino == "bella vista, popayan" && rutaOrigen == "texaco catay, popayan"){
            direccion = "norte";
        }else if(rutaOrigen== "bella vista, popayan" && rutaDestino == "texaco catay, popayan"){
            direccion = "sur";
        }else if(rutaOrigen== "bella vista, popayan" && rutaDestino == "bella vista, popayan"){
            direccion = "no valido";
            waypts = [];
            ruta = 5;
            alert("Combinacion de direcciones no valida #303");
        }else if(rutaOrigen== "texaco catay, popayan" && rutaDestino == "texaco catay, popayan"){
            direccion = "no valido";
            waypts = [];
            ruta = 5;
            alert("Combinacion de direcciones no valida #304");
        }else{
            direccion = "no valido";
            waypts = [];
            ruta = 5;
            alert("[Error Inesperado] #304");
        }

        if(direccion == "norte"){
            if(ruta == 1){
                waypts = [];
                agregarWaypoint(opc1_HN_lat, opc1_HN_long);
                agregarWaypoint(opc2_HN_lat, opc2_HN_long);
            }else if(ruta == 2){
                waypts = [];
                agregarWaypoint(opc1_HN_lat, opc1_HN_long);
                agregarWaypoint(opc5_HN_lat, opc5_HN_long);
                agregarWaypoint(opc4_HN_lat, opc4_HN_long);
            }else if(ruta == 3){
                waypts = [];
                agregarWaypoint(opc3_HN_lat, opc3_HN_long);
                agregarWaypoint(opc5_HN_lat, opc5_HN_long);
                agregarWaypoint(opc2_HN_lat, opc2_HN_long);
            }else if(ruta == 4){
                waypts = [];
                agregarWaypoint(opc3_HN_lat, opc3_HN_long);
                agregarWaypoint(opc4_HN_lat, opc4_HN_long);
            }else{
                waypts = [];
                alert("[Error] trazar ruta hacia el norte #403");
            }
        }else if(direccion == "sur"){
            if(ruta == 1){
                waypts = [];
                agregarWaypoint(opc2_HS_lat, opc2_HS_long);
                agregarWaypoint(opc1_HS_lat, opc1_HS_long);
            }else if(ruta == 2){
                waypts = [];
                agregarWaypoint(opc4_HS_lat, opc4_HS_long);
                agregarWaypoint(opc5_HS_lat, opc5_HS_long);
                agregarWaypoint(opc1_HS_lat, opc1_HS_long);
            }else if(ruta == 3){
                waypts = [];
                agregarWaypoint(opc2_HS_lat, opc2_HS_long);
                agregarWaypoint(opc5_HS_lat, opc5_HS_long);
                agregarWaypoint(opc3_HS_lat, opc3_HS_long);
            }else if(ruta == 4){
                waypts = [];
                agregarWaypoint(opc4_HS_lat, opc4_HS_long);
                agregarWaypoint(opc3_HS_lat, opc3_HS_long);
            }else{
                waypts = [];
                alert("[Error] trazar ruta hacia el sur #404");
            }
        }else{
            waypts = [];
            alert("[Error] trazar rutas-Se desconoce la dirreccion #405");
        }

        directionsService.route({

            origin: rutaOrigen,
            //origin: "bella vista, popayan",
            destination: rutaDestino,
            //destination: "texaco catay, popayan",
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