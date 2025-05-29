<?php
session_start();
require_once 'config/db.php';
require_once 'includes/header.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST["documento"]);
    $clave = trim($_POST["contraseña"]);

    $sql = "SELECT * FROM Usuario WHERE documento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $usuario_data = $resultado->fetch_assoc();
        if ($clave === $usuario_data["contraseña"]) {
            $_SESSION["id_usuario"] = $usuario_data["id_usuario"];
            $_SESSION["rol"] = $usuario_data["rol"];
            $_SESSION["nombre"] = $usuario_data["nombre"];
            header("Location: views/dashboard.php");
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>

<h2>Iniciar sesión</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="post">
    <label for="documento">Documento:</label>
    <input type="text" name="documento" required>

    <label for="contraseña">Contraseña:</label>
    <input type="password" name="contraseña" required>

    <button type="submit">Ingresar</button>
</form>

<div class="login-links">
    <a class="btn-link" href="recuperar.php">¿Olvidaste tu contraseña?</a>
    <a class="btn-link" href="views/solicitar_acceso.php">Solicitar acceso</a>
</div>


<?php require_once 'includes/footer.php'; ?>
