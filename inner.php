<?php 
require 'functions/connection.php';
require 'functions/functions.php';

	// Para poder unir resultados de una tabla, de dos, tres o de las tablas que necesitemos
	$sql = "SELECT p.titulo, p.contenido, p.imagen, p.fecha, u.usuario FROM publicaciones p INNER JOIN users u ON p.usuario_id = u.id";
	$stmt = $mysql->prepare($sql);

	// Ejecutamos la consulta
	$stmt->execute();

	// Listamos los resultados
	$stmt->store_result();

	// Si es mayor que 0, significa que hay registros
	if ($stmt->num_rows > 0) {
		$stmt->bind_result($titulo, $contenido, $imagen, $fecha, $usuario);

		while ($stmt->fetch()) {
			echo "<h4>Datos de la publicacion</h4>";
			echo $titulo . "<br/>";
			echo $contenido . "<br/>";
			echo $imagen . "<br/>";
			echo $fecha . "<br/>";
			echo "<h4>Datos del usuario: ". $usuario ."</h4>";
		}
	}


?>