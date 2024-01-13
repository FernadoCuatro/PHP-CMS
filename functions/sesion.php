<?php 
// Comprobando exista la sesion user
if (isset($_SESSION['user'])) {
	header("Location: index.php");
}

?>