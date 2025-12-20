<?php
$c = $_GET['c'] ?? 'usuarios';
$a = $_GET['a'] ?? 'index';
$pageId = $c . '-' . $a;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($titulo ?? 'Labutxaka'); ?></title>

    <!-- Shoelace -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.10.0/cdn/themes/light.css" />
    <script type="module">
    window.__shoelace_base_path = 'https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.10.0/cdn';
    </script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.10.0/cdn/shoelace.js"></script>

    <!-- Componentes propios -->
    <script type="module" src="app/assets/js/components/card-list.js"></script>
    <script type="module" src="app/assets/js/components/modal.js"></script>

    <!-- Estilos propios -->
    <link rel="stylesheet" href="app/assets/css/styles.css" />
</head>

<body id="<?= htmlspecialchars($pageId); ?>">
    <div class="layout">

        <aside class="layout-sidebar">
            <?php require __DIR__ . '/sidebar.php'; ?>
        </aside>

        <header class="layout-topbar">
            <?php require __DIR__ . '/topbar.php'; ?>
        </header>

        <main class="layout-content">
            <div class="alerts">
                <?php require __DIR__ . '/alerts.php'; ?>
            </div>