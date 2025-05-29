<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php'; // Encabezado y estilos

// Solo admins
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location:../index.php");
    exit;
}

$mensaje = "";

// CREAR / ACTUALIZAR
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $documento = trim($_POST['documento']);
    $cargo = trim($_POST['cargo']);
    $dependencia = trim($_POST['dependencia']);

    if (!empty($_POST['id_funcionario'])) {
        $id = $_POST['id_funcionario'];
        $sql = $conn->prepare("UPDATE funcionario SET nombre=?, documento=?, cargo=?, dependencia=? WHERE id_funcionario=?");
        $sql->bind_param("ssssi", $nombre, $documento, $cargo, $dependencia, $id);
        $sql->execute();
        $mensaje = "Funcionario actualizado.";
    } else {
        $sql = $conn->prepare("INSERT INTO funcionario (nombre, documento, cargo, dependencia) VALUES (?, ?, ?, ?)");
        $sql->bind_param("ssss", $nombre, $documento, $cargo, $dependencia);
        $sql->execute();
        $mensaje = "Funcionario creado.";
    }
}

// ELIMINAR
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = $conn->prepare("DELETE FROM funcionario WHERE id_funcionario = ?");
    $sql->bind_param("i", $id);
    $sql->execute();
    $mensaje = "Funcionario eliminado.";
}

// EDITAR
$funcionario = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = $conn->prepare("SELECT * FROM funcionario WHERE id_funcionario = ?");
    $sql->bind_param("i", $id);
    $sql->execute();
    $resultado = $sql->get_result();
    $funcionario = $resultado->fetch_assoc();
}

// LISTAR
$lista = $conn->query("SELECT * FROM funcionario ORDER BY nombre ASC");
?>

<div class="crud-container">
    <h2>Gestión de Funcionarios</h2>

    <?php if ($mensaje): ?>
        <p style="color: green;"><?= $mensaje ?></p>
    <?php endif; ?>

    <form method="POST" class="formulario-funcionario">
        <?php if ($funcionario): ?>
            <input type="hidden" name="id_funcionario" value="<?= $funcionario['id_funcionario'] ?>">
        <?php endif; ?>

        <label>Nombre:</label>
        <input type="text" name="nombre" required value="<?= $funcionario['nombre'] ?? '' ?>">

        <label>Documento:</label>
        <input type="text" name="documento" required value="<?= $funcionario['documento'] ?? '' ?>">

        <label>Cargo:</label>
        <input type="text" name="cargo" required value="<?= $funcionario['cargo'] ?? '' ?>">

        <label>Dependencia:</label>
        <input type="text" name="dependencia" required value="<?= $funcionario['dependencia'] ?? '' ?>">

        <button type="submit"><?= $funcionario ? 'Actualizar' : 'Crear' ?> funcionario</button>
    </form>

    <table class="tabla-funcionarios">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Documento</th>
                <th>Cargo</th>
                <th>Dependencia</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $lista->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_funcionario'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['documento'] ?></td>
                    <td><?= $row['cargo'] ?></td>
                    <td><?= $row['dependencia'] ?></td>
                    <td>
                        <a class="btn btn-edit" href="?edit=<?= $row['id_funcionario'] ?>">✏️ Editar</a>
                        <a class="btn btn-delete" href="?delete=<?= $row['id_funcionario'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este funcionario?')">🗑️ Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a class="btn btn-back" href="dashboard.php">⬅ Volver al panel</a>
</div>

<?php require_once '../includes/footer.php'; ?>
