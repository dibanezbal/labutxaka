<form id="new-movimiento" name="new-movimiento" method="POST" action="index.php?c=movimientos&a=save"
    autocomplete="off">
    <sl-radio-group class="radio-tabs" label="" name="tipo_movimiento" value="Gasto" required>
        <sl-radio-button value="Gasto">Gasto</sl-radio-button>
        <sl-radio-button value="Ingreso">Ingreso</sl-radio-button>
        <sl-radio-button value="Transferencia" disabled>Transferencia</sl-radio-button>
    </sl-radio-group>

    <div class="form__fields">
        <sl-input type="date" label="Fecha" name="fecha_registro" required></sl-input>

        <sl-radio-group class="radio__tipo" label="Tipo" name="tipo_registro" value="Fijo" required>
            <sl-radio value="Fijo">Fijo</sl-radio>
            <sl-radio value="Variable">Variable</sl-radio>
        </sl-radio-group>

        <sl-select name="cuenta_id" id="cuenta" placeholder="Elige una cuenta" label="Cuenta" required>
            <?php $icon_cuenta = ''; ?>
            <?php foreach ($data['cuentas'] as $id => $nombre): ?>
            <?php
              if ($nombre === 'Efectivo') {
                $icon_cuenta = 'coin';
              } elseif ($nombre === 'Ahorro') {
                $icon_cuenta = 'piggy-bank';
              } else {
                $icon_cuenta = 'credit-card-2-back';
              }
            ?>
            <sl-option value="<?= $id; ?>" data-icon="<?= $icon_cuenta ?>">
                <sl-icon name="<?= $icon_cuenta ?>"></sl-icon>
                <?= htmlspecialchars($nombre) ?>
            </sl-option>
            <?php endforeach; ?>
        </sl-select>

        <sl-select name="categoria_id" id="categoria" placeholder="Elige una categoría" label="Categoría" required>
            <?php foreach ($data['categorias'] as $id => $nombre): ?>
            <sl-option value="<?= $id; ?>"><?= $nombre; ?></sl-option>
            <?php endforeach; ?>
        </sl-select>
    </div>

    <div class="form__fields form__fields--cantidad">
        <sl-input type="number" name="cantidad" label="Cantidad" placeholder="Escribe una cantidad" inputmode="decimal"
            min="0.01" step="0.01" required pattern="^\d+(?:[.,]\d{1,2})?$">
        </sl-input>

        <sl-select name="moneda" disabled placeholder="EUR" label="Moneda">
            <sl-option value="EUR" selected>EUR</sl-option>
        </sl-select>
    </div>

    <sl-input type="text" name="comentario" label="Comentario" maxlength="120"></sl-input>

    <div class="form__buttons">
        <sl-button class="form__button--cancel" variant="secondary" onclick="window.location.href='index.php'"
            size="medium" pill>Cancelar</sl-button>
        <sl-button class="form__button--submit" type="submit" variant="primary" size="medium" pill>Guardar</sl-button>
    </div>
</form>