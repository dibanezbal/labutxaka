<section class="card-list__lista-movimientos card-list__lista-movimientos--resumen">
    <div class="card-list__header--resumen">
        <h3>Últimos movimientos</h3>
        <a href="index.php?c=movimientos&a=index">Ver más movimientos ></a>
    </div>

    <!-- Un único modal para editar (fuera del foreach) -->
    <sl-dialog id="edit-dialog" label="Editar movimiento">
        <div id="edit-dialog-content"></div>
    </sl-dialog>

    <?php if (empty($movimientos)) : ?>
    <p class="no-data-message">No hay movimientos registrados.</p>
    <?php endif; ?>

    <?php $i = 0;
  foreach (($movimientos ?? []) as $m) :
    if ($i++ === 10) break;

    $fecha = !empty($m['fecha_registro']) ? date('d/m/Y', strtotime($m['fecha_registro'])) : '';
    $cantidadStyle  = ($m['tipo_movimiento'] ?? '') === 'Gasto' ? 'font-gasto' : 'font-ingreso';
    $cantidadSymbol = ($m['tipo_movimiento'] ?? '') === 'Gasto' ? '-' : '+';
    $cantidad = $cantidadSymbol . ' ' . number_format((float)($m['cantidad'] ?? 0), 2, ',', '.');

    $icon_cuenta = '';
    if (($m['cuenta_nombre'] ?? '') === 'Efectivo')      $icon_cuenta = 'coin';
    else if (($m['cuenta_nombre'] ?? '') === 'Ahorro')   $icon_cuenta = 'piggy-bank';
    else                                                 $icon_cuenta = 'credit-card-2-back';
    ?>
    <div class="mov-card mov-card--resumen mov-card--clickable" data-id="<?= (int)$m['id'] ?>" role="button"
        tabindex="0" aria-label="Editar movimiento">
        <div class="mov-card__row mov-card__row--mobile">
            <span class="mov-card__date"><?= htmlspecialchars((string)$fecha) ?></span>
            <span class="mov-card__category"><?= htmlspecialchars((string)($m['categoria_nombre'] ?? '—')) ?></span>

            <?php if (!empty($m['comentario'])): ?>
            <span class="mov-card__comment"><?= htmlspecialchars((string)$m['comentario']) ?></span>
            <?php else: ?>
            <span class="mov-card__comment">—</span>
            <?php endif; ?>

            <span class="mov-card__account mov-card__account--desktop">
                <sl-icon class="mov-card__icon" name="<?= htmlspecialchars($icon_cuenta) ?>"></sl-icon>
                <?= htmlspecialchars((string)($m['cuenta_nombre'] ?? '—')) ?>
            </span>
            <span class="mov-card__amount mov-card__amount--desktop <?= htmlspecialchars($cantidadStyle) ?>">
                <?= htmlspecialchars((string)$cantidad) ?> €
            </span>
        </div>

        <div class="mov-card__row mov-card__row--mobile--bottom">
            <span class="mov-card__account mov-card__account--mobile">
                <sl-icon class="mov-card__icon" name="<?= htmlspecialchars($icon_cuenta) ?>"></sl-icon>
                <?= htmlspecialchars((string)($m['cuenta_nombre'] ?? '—')) ?>
            </span>

            <span class="mov-card__amount mov-card__amount--mobile <?= htmlspecialchars($cantidadStyle) ?>">
                <?= htmlspecialchars((string)$cantidad) ?> €
            </span>
        </div>
    </div>
    <?php endforeach; ?>
</section>