<?php 
date_default_timezone_set('America/El_Salvador');

function esVacia($usuario, $contrasena, $repContrasena)
{
	// empty => Revisamos que algo esta vacio
	// trim => Revisamos que no tenga espacios en blanco
	if (!empty(trim($usuario)) && !empty(trim($contrasena)) && !empty(trim($repContrasena))) {
		return false;
	} else {
		return true;
	}
}

function validaLargo($usuario) 
{
	if (strlen(trim($usuario)) > 3 && strlen(trim($usuario)) < 20) {
		// Si la cadena tiene mas de 3 y menos de 20 caracteres, esta todo correcto
		return true;
	} else {
		return false;
	}
}

function usuarioExiste($usuario)
{
	global $mysql;

	$usuario = trim($usuario);

	$sql  = "SELECT id FROM users WHERE usuario = ?";
	$stmt = $mysql->prepare($sql);
	$stmt->bind_param("s", $usuario);

	$stmt->execute();
	$stmt->store_result();

	// Contamos el numero de filas que encuentra la consulta
	$numeroFilas = $stmt->num_rows();
	$stmt->close();

	if ($numeroFilas > 0) {
		// El usuario existe
		return true;
	}else{
		// El usuario no existe
		return false;
	}

}

function contrasenasIguales($contrasena, $repContrasena)
{
	// strcmp => Compara dos cadenas
	if (strcmp($contrasena, $repContrasena) == 0) {
		// Nos devuelve 0 si son iguales 
		return true;
	}else{
		// Las contraseñas no son iguales
		return false;
	}
}

function hashContrasena($contrasena)
{
	$hash = password_hash($contrasena, PASSWORD_DEFAULT);
	return $hash;
}

function registra($usuario, $hash)
{
	global $mysql;

	$usuario = trim($usuario);
	$fecha = date("Y-m-d H:i:s");

	$sql = "INSERT INTO users(usuario,contrasena,fecha_registro) VALUES (?,?,?)";
	$stmt = $mysql->prepare($sql);
	$stmt->bind_param("sss", $usuario, $hash, $fecha);

	if ($stmt->execute()) {
		// La consulta se ejecuto con exito
		$stmt->close();
		return true;
	} else {
		$stmt->close();
		return false;
	}
}


function loginVacio($usuario, $contrasena)
{
	if (!empty(trim($usuario)) && !empty(trim($usuario))) {
		return false;
	}else{
		// Si el login viene vacio retornamos verdadero
		return true;
	}
}

function login($usuario, $contrasena)
{
	global $mysql;

	$sql = "SELECT id, contrasena FROM users WHERE usuario = ?";
	$stmt = $mysql->prepare($sql);
	$stmt->bind_param("s", $usuario);

	// Ejecuta la sentencia
	$stmt->execute();
	// Lista los resultados
	$stmt->store_result();

	// Revisamos si el usuario existe
	$num_filas = $stmt->num_rows;

	if ($num_filas >0) {
		// Significa que el usuario existe
		$stmt->bind_result($id, $clave);
		// Como solo es un registro, lo usamos con el fetch();
		$stmt->fetch();

		// Validar la contraseña
		$contrahash = password_verify($contrasena, $clave);

		if ($contrahash) {
			// La contraseña es valida

			// Creamos las sessiones
			$_SESSION['id'] = $id;
			$_SESSION['user'] = $usuario;

			// Actualizara la ultima conexion del usuario
			$ultima_conexion = ultimaConexion($id);

			// Rediriguimos a la pagina de inicio
			header("Location: index.php");
		} else {
		return "La contraseña no es correcta, revise.";
	}

	}else{
		return "El usuario no existe.";
	}

}

function ultimaConexion($id)
{
	global $mysql;

	$stmt = $mysql->prepare("UPDATE users SET ultima_conexion =  NOW() WHERE id = ?");
	$stmt->bind_param("i", $id);

	if ($stmt->execute()) {
		// Comprobamos que se ejecute la sentencia

		// Comprobamos que las filas afectadas sean mayores a 0
		if ($stmt->affected_rows > 0) {
			$stmt->close();
			return true;
		}else{
			$stmt->close();
			return false;
		}
		
	}else{
		$stmt->close();
		return false;
	} 

}


?>