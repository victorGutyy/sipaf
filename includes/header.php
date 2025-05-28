<?php
$rootPath = str_contains($_SERVER['PHP_SELF'], '/views/') ? '../' : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SIPAF - Sistema Integral de Parqueaderos</title>
    <link rel="stylesheet" href="<?= $rootPath ?>public/css/estilos.css">
</head>
<body>
    <div class="container">
        <header>

            <img src="<?= $rootPath ?>public/img/logo_sipaf.jpg" alt="Logo SIPAF"
     style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; display: block; margin: 0 auto;">


            <h1>SIPAF</h1>
            <h2>Fiscalía General de la Nación – Seccional Quindío</h2>
        </header>
        <main>

