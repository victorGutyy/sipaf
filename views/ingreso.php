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

    $verifica = $conn->prepare("SELECT placa FROM vehiculo WHERE placa = ?");
    $verifica->bind_param("s", $placa);
    $verifica->execute();
    $res = $verifica->get_result();

    if ($res->num_rows == 0) {
        $insert_vehiculo = $conn->prepare("INSERT INTO vehiculo (placa, tipo) VALUES (?, ?)");
        $insert_vehiculo->bind_param("ss", $placa, $tipo_vehiculo);
        $insert_vehiculo->execute();
    }

    $foto_nombre = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_nombre = uniqid("veh_") . "_" . basename($_FILES['foto']['name']);
        move_uploaded_file($foto_tmp, "../uploads/fotos/" . $foto_nombre);
    }

    $sql = "INSERT INTO ingresosalida 
            (id_vehiculo, id_usuario, tipo_registro, fecha, hora, parqueadero, observaciones, foto, sede, registrado_por, id_funcionario)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissssssssi", $placa, $registrado_por, $tipo_registro, $fecha, $hora, $parqueadero, $observaciones, $foto_nombre, $sede, $registrado_por, $id_funcionario);

    if ($stmt->execute()) {
        $mensaje = "✅ Ingreso registrado correctamente.";
    } else {
        $mensaje = "❌ Error al registrar el ingreso: " . $stmt->error;
    }
}
?>

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
