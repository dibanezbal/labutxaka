<div class="card-list__grid">
    <?php foreach ($movimientos as $m): ?>
    <?php
    $fecha = !empty($m['fecha_registro']) ? date('d/m/Y', strtotime($m['fecha_registro'])) : '';
    $cantidadStyle = $m['tipo_movimiento'] === 'Gasto' ? 'font-gasto' : 'font-ingreso';
    $cantidadSymbol = $m['tipo_movimiento'] === 'Gasto' ? '-' : '+';
    $cantidad = $cantidadSymbol . ' ' . number_format((float)$m['cantidad'], 2, ',', '.');
    $icon_cuenta = '';

    if ($cuentas[$m['cuenta_id']] === 'Efectivo') {
        $icon_cuenta = 'coin';
    } else if ($cuentas[$m['cuenta_id']] === 'Ahorro'){
        $icon_cuenta = 'piggy-bank';
    } else {
        $icon_cuenta = 'credit-card-2-back';
    }
    


    ?>
    <div class="mov-card mov-card--clickable" data-id="<?= (int)$m['id'] ?>" role="button">
        <div class="mov-card__body">
            <div class="mov-card__row">
                <input type="checkbox" class="select-movimiento mov-card__checkbox" data-id="<?= (int)$m['id'] ?>">
                <span class="mov-card__date"><?= htmlspecialchars($fecha) ?></span>
                <span class="mov-card__category"><?= htmlspecialchars($categorias[$m['categoria_id']] ?? '—') ?></span>
                <?php if (!empty($m['comentario'])): ?>
                <span class="mov-card__comment"><?= htmlspecialchars($m['comentario']) ?></span>
                <?php else: ?>
                <span class="mov-card__comment">—</span>
                <?php endif; ?>
                <span class="mov-card__account">
                    <sl-icon name="<?= $icon_cuenta ?>"></sl-icon>
                    <?= htmlspecialchars($cuentas[$m['cuenta_id']] ?? '—') ?>
                </span>
                <span class="mov-card__type"><?= htmlspecialchars($m['tipo_registro'] ?? '') ?></span>
                <span class="mov-card__amount <?= $cantidadStyle ?>"><?= htmlspecialchars($cantidad) ?> €<span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>