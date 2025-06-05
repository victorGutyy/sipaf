<?php
session_start();
if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['rol'], ['Administrador', 'Guarda'])) {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/header.php';
require_once '../config/db.php';

$where = [];

// Si no hay fecha en el GET, se usa la fecha de hoy
$fecha_actual = date('Y-m-d');
$fecha = isset($_GET['fecha']) ? $conn->real_escape_string($_GET['fecha']) : $fecha_actual;
$where[] = "i.fecha = '$fecha'";

// Si hay parqueadero seleccionado
if (!empty($_GET['parqueadero'])) {
    $parqueadero = $conn->real_escape_string($_GET['parqueadero']);
    $where[] = "i.parqueadero = '$parqueadero'";
}

// Construir cláusula WHERE
$where_sql = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

// Consulta
$sql = "
    SELECT 
        i.tipo_registro, i.fecha, i.hora, i.parqueadero,
        v.placa, v.tipo,
        f.nombre AS conductor,
        u.nombre AS registrado_por
    FROM ingresosalida i
    LEFT JOIN vehiculo v ON i.id_vehiculo = v.id_vehiculo
    LEFT JOIN funcionario f ON i.id_funcionario = f.id_funcionario
    LEFT JOIN usuario u ON i.registrado_por = u.id_usuario
    $where_sql
    ORDER BY i.id_registro DESC
";


$resultado = $conn->query($sql);
?>

<div class="dashboard-container">
    <h2>Historial de Movimientos Vehiculares</h2>

    <form method="GET" style="text-align: center; margin-bottom: 20px;">
        <label>Fecha:</label>
        <input type="date" name="fecha" value="<?= htmlspecialchars($fecha) ?>">

        <label>Parqueadero:</label>
        <select name="parqueadero">
            <option value="">Todos</option>
            <?php
            $parqueaderos = ["Santa Ana", "Palacio de Justicia", "CAF", "CTI 15", "Giralda"];
            foreach ($parqueaderos as $p) {
                $selected = (isset($_GET['parqueadero']) && $_GET['parqueadero'] === $p) ? 'selected' : '';
                echo "<option value=\"$p\" $selected>$p</option>";
            }
            ?>
        </select>

        <button type="submit" class="btn">🔍 Buscar</button>
    </form>

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
                <th>Registrado por</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <?php while ($row = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['fecha']) ?></td>
                        <td><?= htmlspecialchars($row['hora']) ?></td>
                        <td><?= htmlspecialchars($row['tipo_registro']) ?></td>
                        <td><?= htmlspecialchars($row['placa']) ?></td>
                        <td><?= htmlspecialchars($row['tipo']) ?></td>
                        <td><?= htmlspecialchars($row['conductor']) ?></td>
                        <td><?= htmlspecialchars($row['parqueadero']) ?></td>
                        <td><?= htmlspecialchars($row['registrado_por']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8">No hay movimientos registrados para los filtros aplicados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a class="btn btn-back" href="dashboard.php">⬅ Volver al panel</a>
</div>

<?php require_once '../includes/footer.php'; ?>
