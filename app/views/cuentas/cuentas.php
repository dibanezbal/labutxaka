<h1>Cuentas</h1>
<ul>
    <?php foreach ($cuentas as $cuenta):
    $icon_cuenta = ''; 

    if ($cuenta['nombre'] === 'Efectivo') {
        $icon_cuenta = 'coin';
    } else if ($cuenta['nombre'] === 'Ahorro'){
        $icon_cuenta = 'piggy-bank';
    } else {
        $icon_cuenta = 'credit-card-2-back';
    }
    
    ?>
    <li value="<?= $cuenta['id'] ; ?>">
        <sl-icon name="<?= $icon_cuenta ?>"></sl-icon>
        <?= htmlspecialchars($cuenta['nombre']); ?>
    </li>
    <?php endforeach; ?>
</ul>

<?php if (empty($cuentas)): ?>
<p>No hay cuentas disponibles.</p>
<?php endif; ?>