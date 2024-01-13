<?php 
if (count($errors) > 0) {
 echo "<div class='error text-center mx-5 bg-danger text-white p-3'>";
 foreach ($errors as $error) {
  echo "<i class='fas fa-exclamation-circle'></i> " . $error;
 }
 echo "</div>";
}
?>