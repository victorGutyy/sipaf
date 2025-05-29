<?php
require_once '../config/db.php';
require_once '../includes/header.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $documento = trim($_POST['documento']);
    $correo = trim($_POST['correo']);
    $rol = $_POST['rol'];

    $stmt = $conn->prepare("INSERT INTO solicitudes (nombre, documento, correo, rol_solicitado) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $documento, $correo, $rol);

    if ($stmt->execute()) {
        $mensaje = "✅ Solicitud enviada correctamente. Espera aprobación.";
    } else {
        $mensaje = "❌ Error al enviar la solicitud.";
    }
}
?>

<div class="crud-container">
    <h2>Solicitar acceso</h2>

    <?php if ($mensaje): ?>
        <p style="color: <?= str_starts_with($mensaje, '✅') ? 'green' : 'red'; ?>"><?= $mensaje ?></p>
    <?php endif; ?>

    <form method="POST" class="formulario-funcionario">
        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Documento:</label>
        <input type="text" name="documento" required>

        <label>Correo electrónico:</label>
        <input type="text" name="correo" required>

        <label>Rol solicitado:</label>
        <select name="rol" required>
            <option value="">Selecciona un rol</option>
            <option value="Funcionario">Funcionario</option>
            <option value="Guarda">Guarda</option>
        </select>

        <button type="submit">Enviar solicitud</button>
    </form>

   <a class="btn btn-back" href="../index.php">⬅ Volver al inicio</a>


</div>

<?php require_once '../includes/footer.php'; ?>
