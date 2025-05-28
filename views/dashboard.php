<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/header.php';

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];
?>

<div class="dashboard-container">
    <h2>Bienvenido, <?php echo ucfirst($nombre); ?> 👋</h2>
    <p><strong>Rol:</strong> <?php echo $rol; ?></p>
    
    <ul class="dashboard-menu">
        <?php if ($rol === 'Administrador'): ?>
            <li><a href="#">📋 Gestión de usuarios</a></li>
            <li><a href="#">📝 Ver solicitudes de acceso</a></li>
            <li><a href="#">📊 Reportes generales</a></li>

        <?php elseif ($rol === 'Guarda'): ?>
            <li><a href="#">🚗 Registrar ingreso de vehículo</a></li>
            <li><a href="#">🚙 Registrar salida de vehículo</a></li>
            <li><a href="#">📋 Ver historial</a></li>

        <?php elseif ($rol === 'Funcionario'): ?>
            <li><a href="#">📊 Ver estado de parqueaderos</a></li>
            <li><a href="#">📄 Mis reportes</a></li>

        <?php else: ?>
            <li>⚠️ Rol no reconocido</li>
        <?php endif; ?>
    </ul>

    <a class="logout-btn" href="../logout.php">Cerrar sesión</a>
</div>

<?php require_once '../includes/footer.php'; ?>
