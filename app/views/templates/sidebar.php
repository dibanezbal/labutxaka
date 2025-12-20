<?php
$currentController = strtolower($_GET['c'] ?? 'resumen');
$currentAction = strtolower($_GET['a'] ?? 'index');

$activePage = fn(string $controller, string $action = 'index') => 
    $currentController === strtolower($controller) && 
    $currentAction === strtolower($action) ? 'sidebar-active' : '';
?>

<div class="sidebar">
    <a class="sidebar-title logo-desktop" href="index.php?c=movimientos&a=resumen"><img
            src="app/assets/img/logo_amarillo_vertical.svg" alt=""></a>
    <a class="sidebar-title logo-mobile" href="index.php?c=movimientos&a=resumen"><img
            src="app/assets/img/logo_amarillo_horizontal.svg" alt=""></a>
    <nav class="sidebar-nav">
        <a class="sidebar-link <?= $activePage('movimientos', 'resumen') ?>" href="index.php?c=movimientos&a=resumen">
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
    <div class="sidebar-footer">
        <p class="sidebar-footer-user">
            <sl-icon name="person-circle"></sl-icon>
            <?php
                $usuarioStr = (string)$usuario;
                $usuarioCap = mb_strtoupper(mb_substr($usuarioStr, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($usuarioStr, 1, null, 'UTF-8');
                echo htmlspecialchars($usuarioCap, ENT_QUOTES, 'UTF-8');
                ?>
        </p>
        <a class="sidebar-footer-link" href="index.php?c=usuarios&a=logout">
            <sl-icon name="box-arrow-right"></sl-icon>Cerrar Sesión
        </a>
    </div>
</div>