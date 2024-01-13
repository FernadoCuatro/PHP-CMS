<?php
session_start();
require 'functions/connection.php';
require 'functions/functions.php';

// Si no existe la sesion, significa que no esta logueado
if (!isset($_SESSION['user'])) {
 header("Location: login.php");
}

// arreglo de errores
$errors = array();

if (isset($_POST['enviar'])) {
 $id            = NULL;
 $id_usuario    = $_SESSION['id'];

 $titulo        = $_POST['titulo'];
 $contenido     = $_POST['contenido'];
 // Informacion para la carpeta donde se subio la imagen
 $fecha_carpeta = date("Y-m-d");

 // Tipos de imagenes
 $tipos = array('image/png', 'image/jpeg');

 if (isset($_FILES['ilustracion']['name'])) {
  if (in_array($_FILES['ilustracion']['type'], $tipos)) {
   // El tipo de dato es correcto
   $tamanio = 1024 * 1024 * 10;
   // Son 10 MegaBytes

   // Validamos que el tamaño sea menor al permitido en las imagenes
   if ($_FILES['ilustracion']['size'] < $tamanio) {
    // Creamos una carpeta
    $carpeta = "publicaciones/";

    // Si no existe la carpeta, la creamos
    if (!file_exists($carpeta)) {
     // Creamos la carpeta
     mkdir($carpeta);
    }

    // publicaciones/9
    // Concatenamos a la carpeta que ya tenenemos
    $carpeta .= $id_usuario."/";
    if (!file_exists($carpeta)) {
     // Creamos la carpeta pero ahora con el id
     mkdir($carpeta);
    }

    // Ahora concatenamos la fecha
    // Que es lo que trae la fecha_carpeta 
    $carpeta .= $fecha_carpeta."/";
    if (!file_exists($carpeta)) {
     // Creamos la carpeta pero ahora con el id y tambien la fecha
     mkdir($carpeta);
    }

    // Guardamos el tipo
    $tipo = $_FILES['ilustracion']['type'];
    $fecha = date("Ymd-His");

    // Renormbramos el archivo
    // Cambiando el nombre con la extencion del archivo
    if (strcmp($tipo, "image/jpeg") == 0) {
     $archivo = $carpeta . $fecha . ".jpg";
    } else {
     $archivo = $carpeta . $fecha . ".png";
    }

    // Nombre temporal
    $tmp_name = $_FILES['ilustracion']['tmp_name'];
    // Si el archivo no existe, lo sobimos
    if (move_uploaded_file($tmp_name, $archivo)) {
     // Si nos regresa true, es porque se subio el archivo
     // Y seguimos insertando los datos a la base de datos

     $sql = "INSERT INTO publicaciones(id,titulo,contenido,imagen,usuario_id) VALUES (?,?,?,?,?)";
     $stmt = $mysql->prepare($sql);
     $stmt->bind_param("isssi", $id, $titulo, $contenido, $archivo, $id_usuario);

     if ($stmt->execute()) {
      // Si es true, se ejecuto todo correctamente
      $resultado = "Publicacion agregada correctamente";
     }else {
      $errors[] = "Error al registrar los datos.";
     }
    }else{
     $errors[] = "No se pudo procesar la imagen, intente mas tarde.";
    }
   }else{
    $errors[] = "El archivo es demasiado pesado para ser procesado.";
   }
  }else{
   $errors[] = "El tipo de dato no es compatible.";
  }
 }else{
  $errors[] = "Agregue una imagen a su contenido.";
 }


}


?>

<?php include 'templates/header.php'; ?>

<div class="container">
 <div class="row mt-5">
  <div class="col-8 m-auto bg-white rounded shadow p-0">
   <h4 class="text-center mb-4 text-secondary mt-5"></h4>
   <div class="px-5 pb-5">
    <h4 class="text-right" style="font-size: 1.0em"><?php echo $_SESSION['user']; ?></h4>

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
    
    <h3 class="text-center">Agregar una nueva publicación</h3>

    <form  method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
     <div class="form-group">
      Título de la publicación:
      <input type="text" name="titulo" placeholder="Título de la publicación" class="form-control">
     </div>

     <div class="form-group">
      Contenido de la publicación:
      <textarea name="contenido" class="form-control" cols="30" rows="3"></textarea>
     </div>

     <div class="form-group">
      Imagen de la publicación:
      <input type="file" name="ilustracion" class="form-control">
     </div>

     <div class="form-group text-right">
      <button type="submit" class="btn btn-primary" name="enviar">Subir publicación</button>
     </div>

    </form>

   </div>
   <div class="col-4 mx-5 mb-5">
    <a href="index.php"><button class="btn btn-outline-secondary form-control">Volver atrás</button></a>
   </div>
  </div>
 </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>