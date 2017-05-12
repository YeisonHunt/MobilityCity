<?php

// Este programa es ejecutado por el modulo electronico, para 
// escribir los datos arrojados por los sensores en la base de datos
// si se hace un cambio por Hardware.

require ("conexion.php");

/********* Capturar datos enviados desde el arduino**********/
$ardTemperatura  = intval($_GET['cliTemperatura']);
$ardHumedad  = intval($_GET['cliHumedad']);
$ardLluvia  = intval($_GET['cliLluvia']);
$ardCarr9HNorte  = intval($_GET['traCarr9HNorte']);
$ardCarr9HSur  = intval($_GET['traCarr9HSur']);
$ardCarr6HNorte  = intval($_GET['traCarr6HNorte']);
$ardCarr6HSur  = intval($_GET['traCarr6HSur']);
$ardZona = "centro";


/********* Lógica determinar tipo de tráfico **********/
/********* Lógica determinar tipo de tráfico **********/
/********* Lógica determinar tipo de tráfico **********/
/********* Lógica determinar tipo de tráfico **********/


/* Tabla clima */
$sqlClima = "INSERT INTO clima (cliHumedad, cliLluvia, cliTemperatura, cliZona, cliFecha, cliHora) VALUES ($ardHumedad, $ardLluvia ,$ardTemperatura, $ardZona, CURDATE(),CURTIME() )";

if ($conn->query($sqlClima) === TRUE) {
    echo "Daños añadidos exitosamente a tabla clima.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

/* Tabla trafico */
$sqlTrafico = "INSERT INTO trafico (traFecha, traHora, traCarr9HNorte, traCarr9HSur, traCarr6HNorte, traCarr6HSur) VALUES (CURDATE(),CURTIME(), $ardCarr9HNorte,$ardCarr9HSur, $ardCarr6HNorte, $ardCarr6HSur)";

if ($conn->query($sqlTrafico) === TRUE) {
    echo "Daños añadidos exitosamente a tabla trafico.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close();
?>

