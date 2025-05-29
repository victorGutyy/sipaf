<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../index.php");
    exit;
}

$mensaje = "";

// Crear o actualizar vehículo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = strtoupper(trim($_POST['placa']));
    $tipo = $_POST['tipo'];
    $color = $_POST['color'];
    $observaciones = trim($_POST['observaciones']);

    if (!empty($_POST['id_vehiculo'])) {
        $id = $_POST['id_vehiculo'];
        $sql = $conn->prepare("UPDATE vehiculo SET placa=?, tipo=?, color=?, observaciones=? WHERE id_vehiculo=?");
        $sql->bind_param("ssssi", $placa, $tipo, $color, $observaciones, $id);
        $sql->execute();
        $mensaje = "Vehículo actualizado.";
    } else {
        $sql = $conn->prepare("INSERT INTO vehiculo (placa, tipo, color, observaciones) VALUES (?, ?, ?, ?)");
        $sql->bind_param("ssss", $placa, $tipo, $color, $observaciones);
        $sql->execute();
        $mensaje = "Vehículo registrado.";
    }
}

// Eliminar vehículo
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = $conn->prepare("DELETE FROM vehiculo WHERE id_vehiculo = ?");
    $sql->bind_param("i", $id);
    $sql->execute();
    $mensaje = "Vehículo eliminado.";
}

// Editar
$vehiculo = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = $conn->prepare("SELECT * FROM vehiculo WHERE id_vehiculo = ?");
    $sql->bind_param("i", $id);
    $sql->execute();
    $res = $sql->get_result();
    $vehiculo = $res->num_rows > 0 ? $res->fetch_assoc() : null;
}

// Listar
$lista = $conn->query("SELECT * FROM vehiculo ORDER BY placa ASC");
?>

<div class="crud-container">
    <h2>Gestión de Vehículos</h2>

    <?php if ($mensaje): ?>
        <p style="color: green;"><?= $mensaje ?></p>
    <?php endif; ?>

    <form method="POST" class="formulario-funcionario">
        <?php if ($vehiculo): ?>
            <input type="hidden" name="id_vehiculo" value="<?= $vehiculo['id_vehiculo'] ?>">
        <?php endif; ?>

        <label>Placa:</label>
        <input type="text" name="placa" required value="<?= $vehiculo['placa'] ?? '' ?>">

        <label>Tipo:</label>
        <input type="text" name="tipo" required value="<?= $vehiculo['tipo'] ?? '' ?>">

        <label>Color:</label>
        <input type="text" name="color" value="<?= $vehiculo['color'] ?? '' ?>">

        <label>Observaciones:</label>
        <input type="text" name="observaciones" value="<?= $vehiculo['observaciones'] ?? '' ?>">

        <button type="submit"><?= $vehiculo ? 'Actualizar' : 'Registrar' ?> vehículo</button>
    </form>

    <table class="tabla-funcionarios">
        <thead>
            <tr>
                <th>ID</th>
                <th>Placa</th>
                <th>Tipo</th>
                <th>Color</th>
                <th>Observaciones</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $lista->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_vehiculo'] ?></td>
                    <td><?= $row['placa'] ?></td>
                    <td><?= $row['tipo'] ?></td>
                    <td><?= $row['color'] ?></td>
                    <td><?= $row['observaciones'] ?></td>
                    <td>
                        <a class="btn btn-edit" href="?edit=<?= $row['id_vehiculo'] ?>">✏️ Editar</a>
                        <a class="btn btn-delete" href="?delete=<?= $row['id_vehiculo'] ?>" onclick="return confirm('¿Eliminar este vehículo?')">🗑️ Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a class="btn btn-back" href="dashboard.php">⬅ Volver al panel</a>
</div>

<?php require_once '../includes/footer.php'; ?>
