<div class="card-list__lista-movimientos">
    <?php foreach ($movimientos as $m):

    $fecha = !empty($m['fecha_registro']) ? date('d/m/Y', strtotime($m['fecha_registro'])) : '';
    $cantidadStyle = $m['tipo_movimiento'] === 'Gasto' ? 'font-gasto' : 'font-ingreso';
    $cantidadSymbol = $m['tipo_movimiento'] === 'Gasto' ? '-' : '+';
    $cantidad = $cantidadSymbol . ' ' . number_format((float)$m['cantidad'], 2, ',', '.');
   

    ?>
    <div class="mov-card mov-card--clickable" data-id="<?= (int)$m['id'] ?>" role="button" tabindex="0">
        <div class="mov-card__body">
            <div class="mov-card__row">
                <input type="checkbox" class="select-movimiento mov-card__checkbox only-desktop"
                    data-id="<?= (int)$m['id'] ?>">
                <span class="mov-card__date"><?= htmlspecialchars($fecha) ?></span>
                <?php
                $categoria = $categorias[$m['categoria_id']] ?? '—';
                $cuenta    = $cuentas[$m['cuenta_id']] ?? '—';
                $icon_cuenta = '';

                if ($m['cuenta_nombre'] === 'Efectivo') {
                    $icon_cuenta = 'coin';
                } else if ($m['cuenta_nombre'] === 'Ahorro'){
                    $icon_cuenta = 'piggy-bank';
                } else {
                    $icon_cuenta = 'credit-card-2-back';
                }
                ?>
                <span class="mov-card__category"><?= htmlspecialchars(($m['categoria_nombre'] ?? '—')) ?></span>
                <?php if (!empty($m['comentario'])): ?>
                <span class="mov-card__comment only-desktop"><?= htmlspecialchars(($m['comentario'] ?? '—')) ?></span>
                <?php else: ?>
                <span class="mov-card__comment only-desktop">—</span>
                <?php endif; ?>
                <span class="mov-card__account">
                    <sl-icon name="<?= $icon_cuenta ?>"></sl-icon>
                    <?= htmlspecialchars(($m['cuenta_nombre'] ?? '—')) ?>
                </span>
                <span class="mov-card__type"><?= htmlspecialchars($m['tipo_registro'] ?? '') ?></span>
                <span class="mov-card__amount only-desktop <?= $cantidadStyle ?>"><?= htmlspecialchars($cantidad) ?>
                    €<span>
            </div>
            <div class="mov-card__row only-mobile">
                <?php if (!empty($m['comentario'])): ?>
                <span class="mov-card__comment"><?= htmlspecialchars(($m['comentario'] ?? '—')) ?></span>
                <?php else: ?>
                <span class="mov-card__comment">—</span>
                <?php endif; ?>
                <span class="mov-card__amount <?= $cantidadStyle ?>"><?= htmlspecialchars($cantidad) ?> €<span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>