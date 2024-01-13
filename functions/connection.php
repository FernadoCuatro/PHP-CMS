<?php 

	$mysql = new mysqli("localhost", "root", "", "proyecto17_db");
	$mysql->set_charset("utf8");
	
	if ($mysql->connect_error) {
		echo "Algo salio mal";
	}

?>