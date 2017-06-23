<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');

$actualDate = date('Y-m-d H:i:s');

$load = sys_getloadavg();

echo "Load average over the last 1 minute: " . $load[0] . "<br /> ";
echo "Load average over the last 5 minutes: " . $load[1] . "<br /> ";
echo "Load average over the last 15 minutes: " . $load[2] . "<br /> ";

echo "Logging...<br /> ";


//Si se dispara $load[0]
//Mando registro por email
$umbral = 2;//Limite en el que disparo las alertas
if($load[2] > $umbral){// si la medicion sobrepasa el umbral disparo mails de alerta
$para      = 'pedro@elmistihostels.com';
$titulo    = 'Pico Load Averages';
$link = 'http://elmistihostels.com/cronjob/load_averages_history_graph.php?initDate=' . urlencode(date('Y-m-d H:i:s', time() - 3600));
$mensaje   = "Load average over the last 1 minute: " . $load[0] . " 
			  Load average over the last 5 minutes: " . $load[1] . "
			  Load average over the last 15 minutes: " . $load[2] ."
			  Grafico: $link";

$cabeceras = 'From: no-reply@elmistihostels.com' . "\r\n" .
    'Reply-To: pedro@elmistihostels.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($para, $titulo, $mensaje, $cabeceras);
}

//Guardo los loads averages en la DB
include("db_load_averages.php");
// sql 
$sql = "INSERT INTO `elmisti_load_averages`.`load_averages` (`date`, `1`, `5`, `15`) 
		VALUES ('$actualDate', ". $load[0] ." , " . $load[1] . ", " . $load[2] . ")";



if ($conn->query($sql) === TRUE) {
    echo "Data saved ok! Db connection closed.<br /> ";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();

?>

<a href="load_averages_history_graph.php">Grafica</a>