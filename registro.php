<?php 
session_start();

// Mandamos a llamar los requerimientos necesarios
require 'functions/connection.php';
require 'functions/functions.php';
require 'functions/sesion.php';

// arreglo de errores
$errors = array();

if (isset($_POST['enviar'])) {
 // Si se envio el formulario
 $usuario       = $_POST['usuario'];
 $contrasena    = $_POST['contrasena'];
 $repContrasena = $_POST['repContrasena'];

 // Validamos que los campos no vengan vacios
 if (!esVacia($usuario, $contrasena, $repContrasena)) {
  // Si no esta vacia, seguimos | esVacia nos devuelve true

  // Verificamos que no sean solo numeros
  if (!is_numeric($usuario)) {
   // Se verifica que el usuario no es un numero

   // Validar que sea una cantidad de caracteres
   // Si la cadena tiene mas de 3 y menos de 20 caracteres, esta todo correcto
   if (validaLargo($usuario)) {

    // Validar que el usuario no exista en el base de datos
    if (!usuarioExiste($usuario)) {
     // usuario no existe

     if (contrasenasIguales($contrasena, $repContrasena)) {
      // Las contraseñas son iguales

      // Ciframos la contraseña
      $hash = hashContrasena($contrasena);

      if (registra($usuario, $hash)) {
       // El usuario se registro con exito
       $resultado = "El usuario se registro correctamente.";

      } else {
       $errors[] = "No se proceso la peticion, intente mas tarde.";
      }

     } else {
      $errors[] = "Las contraseñas no coiciden.";
     }

    } else {
     $errors[] = "El usuario ya existe, intente nuevamente.";
    }

   }else{
    $errors[] = "El usuario puede tener entre 3 y 20 caracteres.";
   }

  }else{
   $errors[] = "Tu usuario no debe tener solo numeros.";
  }

 } else {
  $errors[] = "No debe dejar ningun campo vacio.";
 }

}

?>

<?php include 'templates/header.php'; ?>

 <div class="container">
  <div class="row mt-5" style="margin-top: 30px !important;">
   <div class="col-8 m-auto bg-white rounded shadow p-0">
    <h4 class="text-center mb-4 text-secondary mt-5">REGÍSTRATE EN NUESTRA PÁGINA WEB</h4>
    <div class="col-12 bg-light py-3 mb-5 text-center">
     <p class="text-secondary m-0 p-0">Regístrate en nuestra web para obtener excelentes beneficios.</p>
    </div>

    <?php 
     if (isset($resultado)) {
    ?>
     <div class="bg-success text-white p-3 mx-5 text-center">
      <?php echo $resultado; ?>
     </div>
    <?php 
     }

    include 'functions/errors.php';
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="m-5">
     <label for="usuario" class="text-secondary">Usuario:</label>
     <div class="input-group mb-5">
      <div class="input-group-prepend">
       <i class="input-group-text bg-primary text-white fas fa-user"></i>
      </div>
      <!-- Input para el usuario -->
      <input type="text" placeholder="Nombre de usuario" autocomplete="off" name="usuario" id="usuario" class="form-control">
     </div>

     <div class="form-row">
      <div class="col-6 mb-3">
       <label for="contrasena" class="text-secondary">Contraseña:</label>
       <div class="input-group">
        <div class="input-group-prepend">
         <i class="input-group-text bg-primary text-white fas fa-key"></i>
        </div>
        <!-- Input para la contraseña -->
        <input type="password" placeholder="Contraseña" name="contrasena" id="contrasena" class="form-control">
       </div>
      </div>

      <div class="col-6 mb-3">
       <label for="repContrasena" class="text-secondary">Repite la contraseña:</label>
       <div class="input-group">
        <div class="input-group-prepend">
         <i class="input-group-text bg-primary text-white fas fa-key"></i>
        </div>
        <!-- Input para la repetición de la contraseña -->
        <input type="password" placeholder="Repite tu contraseña" name="repContrasena" id="repContrasena" class="form-control">
       </div>
      </div>

     </div>

     <div class="row mt-4">
      <div class="col-4 offset-8">
       <!-- Input del botón para enviar el formulario -->
       <input type="submit" class="form-control btn btn-primary" name="enviar" value="Registrarme">
      </div>
     </div>

    </form>
    <div class="col-4 m-5">
     <a href="login.php"><button class="btn btn-outline-secondary form-control">Iniciar sesión</button></a>
     <p class="text-secondary text-center">¿Ya tienes cuenta?</p>
    </div>
   </div>
  </div>
 </div>

<?php include 'templates/footer.php'; ?>