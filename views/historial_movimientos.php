<?php
session_start();
if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['rol'], ['Administrador', 'Guarda'])) {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/header.php';
require_once '../config/db.php';

$resultado = $conn->query("
    SELECT 
        i.tipo_registro, i.fecha, i.hora, i.parqueadero,
        v.placa, v.tipo,
        f.nombre AS conductor
    FROM ingresosalida i
    INNER JOIN vehiculo v ON i.id_vehiculo = v.placa
    INNER JOIN funcionario f ON i.id_usuario = f.id_funcionario
    ORDER BY i.fecha DESC, i.hora DESC
");
?>

<div class="dashboard-container">
    <h2>Historial de Movimientos Vehiculares</h2>

    <table class="tabla-funcionarios">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Tipo</th>
                <th>Placa</th>
                <th>Tipo Vehículo</th>
                <th>Conductor</th>
                <th>Parqueadero</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['fecha'] ?></td>
                    <td><?= $row['hora'] ?></td>
                    <td><?= $row['tipo_registro'] ?></td>
                    <td><?= $row['placa'] ?></td>
                    <td><?= $row['tipo'] ?></td>
                    <td><?= $row['conductor'] ?></td>
                    <td><?= $row['parqueadero'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a class="btn btn-back" href="dashboard.php">⬅ Volver al panel</a>
</div>

<?php require_once '../includes/footer.php'; ?>
