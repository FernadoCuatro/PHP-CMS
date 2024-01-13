<?php 
session_start();

// Mandamos a llamar los requerimientos necesarios
require 'functions/connection.php';
require 'functions/functions.php';
require 'functions/sesion.php';

// arreglo de errores
$errors = array();

if (isset($_POST['enviar'])) {
 // Cuando se utilizan sentencias preparadas ya no es necesario
 // utilizar estas sentecias para saniar el string que recibimos
 $usuario    = $mysql->real_escape_string($_POST['usuario']);
 $contrasena = $mysql->real_escape_string($_POST['contrasena']);

 if (!loginVacio($usuario, $contrasena)) {

  $errors[] = login($usuario, $contrasena);

 }else {
  $errors[] = "No debe dejar campos vacios.";
 }

}

?>

<?php include 'templates/header.php'; ?>
<div class="container">
 <div class="row mt-5">
  <div class="col-8 m-auto bg-white rounded shadow p-0">
   <h4 class="text-center mb-4 text-secondary mt-5">INICIAR SESIÓN</h4>

   <div class="col-12 bg-light py-3 mb-5 text-center">
    <p class="text-secondary m-0 p-0">Inicia sesión para obtener acceso.</p>
   </div>

   <?php include 'functions/errors.php'; ?>

   <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="m-5">
    <label for="usuario" class="text-secondary">Usuario:</label>
    <div class="input-group mb-5">
     <div class="input-group-prepend">
      <i class="input-group-text bg-primary text-white fas fa-user"></i>
     </div>
     <!-- Input para el usuario -->
     <input type="text" placeholder="Nombre de usuario" name="usuario" id="usuario" autcomplete="off" class="form-control">
    </div>

    <div class="form-row">
     <div class="col-12 mb-3">
      <label for="contrasena" class="text-secondary">Contraseña:</label>
      <div class="input-group">
       <div class="input-group-prepend">
        <i class="input-group-text bg-primary text-white fas fa-key"></i>
       </div>
       <!-- Input para la contraseña -->
       <input type="password" placeholder="Contraseña" name="contrasena" id="contrasena" class="form-control">
      </div>
     </div>
    </div>

    <div class="row mt-4">
     <div class="col-4 offset-8">
      <!-- Input del botón para enviar el formulario -->
      <input type="submit" class="form-control btn btn-primary" name="enviar" value="Iniciar sesión">
     </div>
    </div>
   </form>

   <div class="col-4 m-5">
    <a href="registro.php"><button class="btn btn-outline-secondary form-control">Registrarte</button></a>
    <p class="text-secondary text-center">¿No tienes cuenta?</p>
   </div>

  </div>
 </div>
</div>
<?php include 'templates/footer.php'; ?>