<?php
$rootPath = str_contains($_SERVER['PHP_SELF'], '/views/') ? '../' : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPAF - Sistema Integral de Parqueaderos</title>
    <link rel="stylesheet" href="<?= $rootPath ?>public/css/estilos.css">
</head>
<body>
    <header>
        <img src="<?= $rootPath ?>public/img/logo_sipaf.jpg" alt="Logo SIPAF"
             style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; display: block; margin: 0 auto;">

        <h1>SIPAF</h1>
        <h2>Fiscalía General de la Nación – Seccional Quindío</h2>

        <div id="reloj-fecha" style="font-size: 14px; margin-top: 10px;"></div>

        <script>
            function actualizarReloj() {
                const ahora = new Date();
                const opciones = {
                    timeZone: 'America/Bogota',
                    year: 'numeric', month: 'long', day: 'numeric',
                    hour: '2-digit', minute: '2-digit', second: '2-digit',
                    hour12: false
                };
                document.getElementById('reloj-fecha').textContent = ahora.toLocaleString('es-CO', opciones);
            }
            setInterval(actualizarReloj, 1000);
            actualizarReloj();
        </script>
    </header>

    <main class="main-content"> <!-- Comienza contenido principal -->
