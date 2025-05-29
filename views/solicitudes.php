<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../index.php");
    exit;
}

// Aprobar o rechazar
if (isset($_GET['aprobar'])) {
    $id = $_GET['aprobar'];
    $conn->query("UPDATE solicitudes SET estado = 'Aprobado' WHERE id_solicitud = $id");
} elseif (isset($_GET['rechazar'])) {
    $id = $_GET['rechazar'];
    $conn->query("UPDATE solicitudes SET estado = 'Rechazado' WHERE id_solicitud = $id");
}

// Cargar solicitudes
$resultado = $conn->query("SELECT * FROM solicitudes ORDER BY fecha DESC");
?>

<div class="crud-container">
    <h2>Solicitudes de acceso</h2>

    <table class="tabla-funcionarios">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Documento</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id_solicitud'] ?></td>
                <td><?= $row['nombre'] ?></td>
                <td><?= $row['documento'] ?></td>
                <td><?= $row['correo'] ?></td>
                <td><?= $row['rol_solicitado'] ?></td>
                <td><?= $row['estado'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($row['fecha'])) ?></td>
                <td>
                    <?php if ($row['estado'] === 'Pendiente'): ?>
                        <a class="btn btn-edit" href="?aprobar=<?= $row['id_solicitud'] ?>">✅ Aprobar</a>
                        <a class="btn btn-delete" href="?rechazar=<?= $row['id_solicitud'] ?>" onclick="return confirm('¿Rechazar esta solicitud?')">❌ Rechazar</a>
                    <?php else: ?>
                        <span>-</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <a class="btn btn-back" href="dashboard.php">⬅ Volver al panel</a>
</div>

<?php require_once '../includes/footer.php'; ?>
