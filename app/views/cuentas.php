<ul>
    <?php foreach ($data['cuentas'] as $id => $nombre): ?>
    <?php $icon_cuenta = ''; 

    if ($nombre === 'Efectivo') {
        $icon_cuenta = 'coin';
    } else if ($nombre === 'Ahorro'){
        $icon_cuenta = 'piggy-bank';
    } else {
        $icon_cuenta = 'credit-card-2-back';
    }
    
    ?>
    <li value="<?= $id; ?>">
        <sl-icon name="<?= $icon_cuenta ?>"></sl-icon>
        <?= $nombre; ?>
    </li>
    <?php endforeach; ?>
</ul>