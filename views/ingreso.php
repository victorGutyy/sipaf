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

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placa = strtoupper(trim($_POST['placa']));
    $tipo_vehiculo = $_POST['tipo_vehiculo'];
    $observaciones = trim($_POST['observaciones']);
    $parqueadero = $_POST['parqueadero'];
    $sede = $_POST['sede'];
    $id_funcionario = $_POST['id_funcionario'];
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    $tipo_registro = "Ingreso";
    $registrado_por = $_SESSION['id_usuario'];

    // Buscar si el vehículo existe y obtener su ID
    $stmtVehiculo = $conn->prepare("SELECT id_vehiculo FROM vehiculo WHERE placa = ?");
    $stmtVehiculo->bind_param("s", $placa);
    $stmtVehiculo->execute();
    $resVehiculo = $stmtVehiculo->get_result();

    if ($resVehiculo->num_rows > 0) {
        $vehiculo = $resVehiculo->fetch_assoc();
        $idVehiculo = $vehiculo['id_vehiculo'];
    } else {
        // Insertar vehículo si no existe
        $insertVehiculo = $conn->prepare("INSERT INTO vehiculo (placa, tipo) VALUES (?, ?)");
        $insertVehiculo->bind_param("ss", $placa, $tipo_vehiculo);
        if ($insertVehiculo->execute()) {
            $idVehiculo = $insertVehiculo->insert_id;
        } else {
            $mensaje = "❌ Error al registrar el vehículo.";
            $idVehiculo = null;
        }
    }

    // Verificar si se permite el ingreso
    $permitido = false;
    if ($idVehiculo !== null) {
        $check = $conn->prepare("
            SELECT tipo_registro 
            FROM ingresosalida 
            WHERE id_vehiculo = ? 
            ORDER BY id_registro DESC 
            LIMIT 1
        ");
        $check->bind_param("i", $idVehiculo);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $ultimo = $result->fetch_assoc();
            if ($ultimo['tipo_registro'] === 'Salida') {
                $permitido = true;
            } else {
                $mensaje = "❌ El vehículo ya está registrado como ingresado. Debe registrarse la salida antes.";
            }
        } else {
            $permitido = true;
        }
    }

    // Procesar imagen
    $foto_nombre = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_nombre = uniqid("veh_") . "_" . basename($_FILES['foto']['name']);
        move_uploaded_file($foto_tmp, "../uploads/fotos/" . $foto_nombre);
    }

    // Registrar ingreso si se permite
    if ($permitido && $idVehiculo !== null) {
        $stmt = $conn->prepare("INSERT INTO ingresosalida 
            (id_vehiculo, id_usuario, tipo_registro, fecha, hora, parqueadero, observaciones, foto, sede, registrado_por, id_funcionario)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("iissssssssi", $idVehiculo, $registrado_por, $tipo_registro, $fecha, $hora, $parqueadero, $observaciones, $foto_nombre, $sede, $registrado_por, $id_funcionario);

        if ($stmt->execute()) {
            $mensaje = "✅ Ingreso registrado correctamente.";
        } else {
            $mensaje = "❌ Error al registrar el ingreso: " . $stmt->error;
        }
    }
}
?>

<!-- HTML -->

<div class="dashboard-container">
    <h2>Registrar ingreso de vehículo</h2>

    <?php if ($mensaje): ?>
        <p style="color: <?= str_starts_with($mensaje, '✅') ? 'green' : 'red'; ?>"><?= $mensaje ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Placa del vehículo:</label>
        <input type="text" name="placa" required><br>

        <label>Tipo de vehículo:</label>
        <select name="tipo_vehiculo" required>
            <option value="">Seleccionar</option>
            <option value="Carro">Carro</option>
            <option value="Moto">Moto</option>
            <option value="Camioneta">Camioneta</option>
            <option value="Otro">Otro</option>
        </select><br>

        <label>Funcionario que entrega el vehículo:</label>
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
        <input type="text" name="sede" value="Seccional Quindío" required><br>

        <label>Foto del vehículo:</label>
        <input type="file" name="foto" accept="image/*"><br><br>

        <button type="submit">Registrar ingreso</button>
    </form>

    <a class="logout-btn" href="dashboard.php">⬅ Volver al panel</a>
</div>

<?php require_once '../includes/footer.php'; ?>
