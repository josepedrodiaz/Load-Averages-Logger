<?php

	define('DB_DRIVER', 'mysql');
	define('DB_SERVER', 'localhost');
	define('DB_USER', 'elmisti_update08');
	define('DB_PASS', 'Misti2000');
	define('DB_BANCO', 'elmisti_update082015');

	
	$conn = mysql_connect(DB_SERVER, DB_USER, DB_PASS);

	if (!$conn) {
	    die("Connection failed: " . $conn->connect_error);
	} 

  echo "Conectando a DB <br />";

	mysql_select_db(DB_BANCO);

  echo "Limpiando wp_options <br />";

    $sql = "DELETE FROM wp_options WHERE option_name LIKE '_transient_%' OR option_name LIKE 'displayed_galleries%' OR option_name LIKE 'displayed_gallery_rendering%' LIMIT 15000";

    mysql_query($sql);

    $affected_rows = mysql_affected_rows();



  
    echo $sql;
   
   

    echo "<br>";
    echo "Registros deletados: ".$affected_rows;

?>