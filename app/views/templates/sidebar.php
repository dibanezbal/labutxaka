<?php
$currentController = strtolower($_GET['c'] ?? 'resumen');
$currentAction = strtolower($_GET['a'] ?? 'index');

$activePage = fn(string $controller, string $action = 'index') => 
    $currentController === strtolower($controller) && 
    $currentAction === strtolower($action) ? 'sidebar-active' : '';
?>

<div class="sidebar">
    <a class="sidebar-title" href="index.php"><img src="app/assets/img/logo_amarillo_vertical.svg" alt=""></a>
    <nav class="sidebar-nav">
        <a class="sidebar-link <?= $activePage('resumen', 'index') ?>" href="index.php">
            <sl-icon name="list-check"></sl-icon>
            Resumen
        </a>
        <a class="sidebar-link <?= $activePage('movimientos', 'index') ?>" href="?c=movimientos&a=index">
            <sl-icon name="cash-coin"></sl-icon>
            Movimientos
        </a>
        <a class="sidebar-link <?= $activePage('cuentas', 'index') ?>" href="?c=cuentas&a=index">
            <sl-icon name="wallet2"></sl-icon> Cuentas
        </a>
        <a class="sidebar-link <?= $activePage('categorias', 'index') ?>" href="?c=categorias&a=index">
            <sl-icon name="list-stars"></sl-icon> Categorías
        </a>
    </nav>
</div>