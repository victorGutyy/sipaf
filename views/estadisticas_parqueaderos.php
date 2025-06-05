<?php
session_start();
if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['rol'], ['Administrador', 'Guarda'])) {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/header.php';
require_once '../config/db.php';

// Filtro de parqueadero
$parqueadero = isset($_GET['parqueadero']) ? $conn->real_escape_string($_GET['parqueadero']) : '';
$where = $parqueadero ? "WHERE i.parqueadero = '$parqueadero'" : '';

// Consulta para obtener el ultimo movimiento de cada vehiculo
$sql = "
    SELECT i.*, v.tipo, f.nombre AS conductor, u.nombre AS registrado_por
    FROM ingresosalida i
    INNER JOIN (
        SELECT id_vehiculo, MAX(id_registro) AS max_id
        FROM ingresosalida
        GROUP BY id_vehiculo
    ) ult ON i.id_vehiculo = ult.id_vehiculo AND i.id_registro = ult.max_id
    LEFT JOIN vehiculo v ON i.id_vehiculo = v.id_vehiculo
    LEFT JOIN funcionario f ON i.id_funcionario = f.id_funcionario
    LEFT JOIN usuario u ON i.registrado_por = u.id_usuario
    $where
    ORDER BY i.hora DESC
";

$resultado = $conn->query($sql);

$vehiculos_dentro = [];
$vehiculos_fuera = [];
while ($row = $resultado->fetch_assoc()) {
    if ($row['tipo_registro'] === 'Ingreso') {
        $vehiculos_dentro[] = $row;
    } else {
        $vehiculos_fuera[] = $row;
    }
}
?>

<div class="dashboard-container">
    <h2>Estadísticas de Parqueaderos</h2>

    <form method="GET" style="text-align: center; margin-bottom: 20px;">
        <label>Parqueadero:</label>
        <select name="parqueadero">
            <option value="">Todos</option>
            <?php
            $parqueaderos = ["Santa Ana", "Palacio de Justicia", "CAF", "CTI 15", "Giralda"];
            foreach ($parqueaderos as $p) {
                $selected = ($parqueadero === $p) ? 'selected' : '';
                echo "<option value=\"$p\" $selected>$p</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn">🔍 Consultar</button>
    </form>

    <h3>🟢 Vehículos actualmente dentro</h3>
    <table class="tabla-funcionarios">
        <thead>
            <tr>
                <th>Placa</th>
                <th>Tipo</th>
                <th>Conductor</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Parqueadero</th>
                <th>Registrado por</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($vehiculos_dentro) > 0): ?>
                <?php foreach ($vehiculos_dentro as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id_vehiculo']) ?></td>
                        <td><?= htmlspecialchars($row['tipo']) ?></td>
                        <td><?= htmlspecialchars($row['conductor']) ?></td>
                        <td><?= htmlspecialchars($row['fecha']) ?></td>
                        <td><?= htmlspecialchars($row['hora']) ?></td>
                        <td><?= htmlspecialchars($row['parqueadero']) ?></td>
                        <td><?= htmlspecialchars($row['registrado_por']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No hay vehículos actualmente dentro.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h3 style="margin-top: 30px;">🔴 Vehículos que ya salieron</h3>
    <table class="tabla-funcionarios">
        <thead>
            <tr>
                <th>Placa</th>
                <th>Tipo</th>
                <th>Conductor</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Parqueadero</th>
                <th>Registrado por</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($vehiculos_fuera) > 0): ?>
                <?php foreach ($vehiculos_fuera as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id_vehiculo']) ?></td>
                        <td><?= htmlspecialchars($row['tipo']) ?></td>
                        <td><?= htmlspecialchars($row['conductor']) ?></td>
                        <td><?= htmlspecialchars($row['fecha']) ?></td>
                        <td><?= htmlspecialchars($row['hora']) ?></td>
                        <td><?= htmlspecialchars($row['parqueadero']) ?></td>
                        <td><?= htmlspecialchars($row['registrado_por']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">Todos los vehículos han salido.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a class="btn btn-back" href="historial_movimientos.php">⬅ Volver al historial</a>
    

</div>

<?php require_once '../includes/footer.php'; ?>
