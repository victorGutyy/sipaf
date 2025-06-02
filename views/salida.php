<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Guarda') {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/header.php';
require_once '../config/db.php';

$mensaje = "";

// Obtener lista de funcionarios
$funcionarios = [];
$query = $conn->query("SELECT id_funcionario, nombre FROM funcionario ORDER BY nombre ASC");
while ($row = $query->fetch_assoc()) {
    $funcionarios[] = $row;
}

// Procesar salida
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placa = strtoupper(trim($_POST['placa']));
    $observaciones = trim($_POST['observaciones']);
    $parqueadero = $_POST['parqueadero'];
    $sede = $_POST['sede'];
    $id_funcionario = $_POST['id_funcionario'];
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    $tipo_registro = "Salida";
    $registrado_por = $_SESSION['id_usuario'];

    // Verificar el último registro del vehículo
    $verifica = $conn->prepare("
        SELECT tipo_registro 
        FROM ingresosalida 
        WHERE id_vehiculo = ? 
        ORDER BY id_registro DESC 
        LIMIT 1
    ");
    $verifica->bind_param("s", $placa);
    $verifica->execute();
    $resultado = $verifica->get_result();

    if ($resultado->num_rows > 0) {
        $ultimo = $resultado->fetch_assoc();

        if ($ultimo['tipo_registro'] === 'Ingreso') {
            // Insertar salida
            $stmt = $conn->prepare("INSERT INTO ingresosalida 
                (id_vehiculo, id_usuario, tipo_registro, fecha, hora, parqueadero, observaciones, sede, registrado_por, id_funcionario) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param("sisssssssi", $placa, $registrado_por, $tipo_registro, $fecha, $hora, $parqueadero, $observaciones, $sede, $registrado_por, $id_funcionario);

            if ($stmt->execute()) {
                $mensaje = "✅ Salida registrada correctamente.";
            } else {
                $mensaje = "❌ Error al registrar la salida: " . $stmt->error;
            }

        } else {
            $mensaje = "⚠️ El vehículo ya se encuentra fuera. No es posible registrar otra salida.";
        }

    } else {
        $mensaje = "⚠️ No hay registro de ingreso previo para esta placa.";
    }
}
?>

<div class="dashboard-container">
    <h2>Registrar salida de vehículo</h2>

    <?php if ($mensaje): ?>
        <p style="color: <?= str_starts_with($mensaje, '✅') ? 'green' : 'red'; ?>"><?= $mensaje ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Placa del vehículo:</label>
        <input type="text" name="placa" required><br>

        <label>Funcionario que retira el vehículo:</label>
        <select name="id_funcionario" required>
            <option value="">Seleccionar funcionario</option>
            <?php foreach ($funcionarios as $f): ?>
                <option value="<?= $f['id_funcionario'] ?>"><?= $f['nombre'] ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Observaciones:</label>
        <textarea name="observaciones" rows="3"></textarea><br>

        <label>Parqueadero:</label>
        <select name="parqueadero" required>
            <option value="Santa Ana">Santa Ana</option>
            <option value="Palacio de Justicia">Palacio de Justicia</option>
            <option value="CAF">CAF</option>
            <option value="CTI 15">CTI 15</option>
            <option value="Giralda">Giralda</option>
        </select><br>

        <label>Sede:</label>
        <input type="text" name="sede" value="Seccional Quindío" required><br><br>

        <button type="submit">Registrar salida</button>
    </form>

    <a class="logout-btn" href="dashboard.php">⬅ Volver al panel</a>
</div>

<?php require_once '../includes/footer.php'; ?>
