<?php
session_start();
session_unset(); // Opcional: limpia todas las variables de sesión
session_destroy(); // Destruye la sesión
header("Location: index.php"); // Redirige al login
exit;
?>
