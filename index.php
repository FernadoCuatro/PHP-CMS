<?php
session_start();
require 'functions/connection.php';
require 'functions/functions.php';

// Si no existe la sesion, significa que no esta logueado
if (!isset($_SESSION['user'])) {
 header("Location: login.php");
}

// Trayendo los datos para las publicaciones
 $sql = "SELECT p.titulo, p.contenido, p.imagen, p.fecha, u.usuario FROM publicaciones p INNER JOIN users u ON p.usuario_id = u.id";
 $stmt = $mysql->prepare($sql);
 $stmt->execute();
 $stmt->store_result();
?>

<?php include 'templates/header.php'; ?>

<div class="container">
 <div class="row mt-5">
  <div class="col-8 m-auto bg-white rounded shadow p-0">
   <h4 class="text-center mb-4 text-secondary mt-5">INDEX</h4>

   <div class="col-12 bg-light py-3 mb-5 text-center">
    <a href="agregar.php"><button class="btn btn-success m-auto">Agregar publicación</button></a>
   </div>

   <div class="px-5 pb-5">
    <h4 class="mb-5">Estás logueado como: <?php echo $_SESSION['user']; ?></h4>

    <?php 
    if ($stmt->num_rows > 0) {
     $stmt->bind_result($titulo, $contenido, $imagen, $fecha, $usuario);

     while ($stmt->fetch()) {
    ?>
     <div class="publicacion border-bottom">
      <img src="<?php echo $imagen ?>" class="rounded img-fluid" alt="<?php echo $imagen ?>">
      <h4 class="my-2"><?php echo $titulo ?></h4>
      <p class="text-muted"><?php echo $fecha ?></p>
      <p><?php echo $contenido ?></p>
      <p class="text-right text-muted">Publicado por: <?php echo $usuario ?></p>
     </div>
    <?php 
     }
    }
    ?>

   </div>

   <div class="col-4 m-5">
    <a href="logout.php"><button class="btn btn-outline-secondary form-control">Cerrar sesión</button></a>
    <p class="text-secondary text-center">¿Quieres cerrar sesión?</p>
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