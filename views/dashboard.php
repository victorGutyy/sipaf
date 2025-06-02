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
    <div class="dashboard-header">
        <h2>Bienvenido, <?php echo ucfirst($nombre); ?> 👋</h2>
        <p><strong>Rol:</strong> <?php echo $rol; ?></p>
    </div>

    <div class="dashboard-menu">
        <ul>
            <?php if ($rol === 'Administrador'): ?>
                <li><a class="btn-dashboard" href="gestionar.php"> Gestión de funcionarios</a></li>
                <li><a class="btn-dashboard" href="vehiculos.php"> Gestión de vehículos</a></li>
                <li><a class="btn-dashboard" href="solicitudes.php"> Ver solicitudes de acceso</a></li>
                <li><a class="btn-dashboard" href="reportes.php"> Reportes generales</a></li>

            <?php elseif ($rol === 'Guarda'): ?>
                <li><a class="btn-dashboard" href="ingreso.php"> Registrar ingreso de vehículo</a></li>
                <li><a class="btn-dashboard" href="salida.php"> Registrar salida de vehículo</a></li>
    
            <?php elseif ($rol === 'Funcionario'): ?>
                <li><a class="btn-dashboard" href="#">📊 Ver estado de parqueaderos</a></li>
                <li><a class="btn-dashboard" href="#">📄 Mis reportes</a></li>

            <?php else: ?>
                <li><span class="warning">⚠️ Rol no reconocido</span></li>
            <?php endif; ?>

            <?php if ($rol === 'Administrador' || $rol === 'Guarda'): ?>
                <li><a class="btn-dashboard" href="historial_movimientos.php"> Historial vehicular</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <form method="post" action="../logout.php" style="text-align:center;">
    <button type="submit" class="btn-logout">Cerrar sesión</button>
</form>


</div>

<?php require_once '../includes/footer.php'; ?>
