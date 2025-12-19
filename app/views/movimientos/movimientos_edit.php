<?php
$fecha_movimiento = date('Y-m-d', strtotime($movimiento['fecha_registro']));
?>


<form id="edit-movimiento" action="?c=movimientos&a=update" method="POST">
    <input type="hidden" name="id" value="<?= $movimiento['id']; ?>">

    <?php 
    $tipo_movimiento_checked = '';
    $tipo_registro_checked = '';

    if ($movimiento['tipo_movimiento'] === 'Gasto') {
        $tipo_movimiento_checked = 'Gasto';
    } elseif ($movimiento['tipo_movimiento'] === 'Ingreso') {
        $tipo_movimiento_checked = 'Ingreso';
    }

    if ($movimiento['tipo_registro'] === 'Fijo') {
        $tipo_registro_checked = 'Fijo';
    } elseif ($movimiento['tipo_registro'] === 'Variable') {
        $tipo_registro_checked = 'Variable';
    }
    ?>

    <sl-radio-group class="radio-tabs" label="" name="tipo_movimiento" value="<?= $tipo_movimiento_checked; ?>"
        required>
        <sl-radio-button value="Gasto">Gasto
        </sl-radio-button>
        <sl-radio-button value="Ingreso">Ingreso
        </sl-radio-button>
        <sl-radio-button value="Transferencia" disabled>Transferencia</sl-radio-button>
    </sl-radio-group>

    <div class="form__fields">
        <sl-input type="date" label="Fecha" name="fecha_registro" value="<?= $fecha_movimiento; ?>" required></sl-input>

        <sl-radio-group class="radio__tipo" label="Tipo" name="tipo_registro" value="<?= $tipo_registro_checked; ?>"
            required>
            <sl-radio value="Fijo">Fijo</sl-radio>
            <sl-radio value="Variable">Variable</sl-radio>
        </sl-radio-group>


        <sl-select name="cuenta_id" id="cuenta" placeholder="Elige una cuenta" label="Cuenta"
            value="<?= $movimiento['cuenta_id']; ?>" required>
            <?php $icon_cuenta = ''; ?>
            <?php foreach ($cuentas as $cuenta): ?>
            <?php
              if ($cuenta['nombre'] === 'Efectivo') {
                $icon_cuenta = 'coin';
              } elseif ($cuenta['nombre'] === 'Ahorro') {
                $icon_cuenta = 'piggy-bank';
              } else {
                $icon_cuenta = 'credit-card-2-back';
              }
            ?>
            <sl-option value="<?= $cuenta['id']; ?>" data-icon="<?= $icon_cuenta ?>">
                <sl-icon name="<?= $icon_cuenta ?>"></sl-icon>
                <?= htmlspecialchars($cuenta['nombre']) ?>
            </sl-option>
            <?php endforeach; ?>
        </sl-select>

        <sl-select name="categoria_id" id="categoria" placeholder="Elige una categoría" label="Categoría"
            value="<?= $movimiento['categoria_id']; ?>" required>
            <?php foreach ($categorias as $categoria): ?>
            <sl-option value="<?= $categoria['id']; ?>"><?= $categoria['nombre']; ?></sl-option>
            <?php endforeach; ?>
        </sl-select>
    </div>

    <div class="form__fields form__fields--cantidad">
        <sl-input type="number" name="cantidad" label="Cantidad" placeholder="Escribe una cantidad" inputmode="decimal"
            min="0.01" step="0.01" required pattern="^\d+(?:[.,]\d{1,2})?$" value="<?= $movimiento['cantidad']; ?>">
        </sl-input>

        <sl-select name="moneda" disabled placeholder="EUR" label="Moneda">
            <sl-option value="EUR" selected>EUR</sl-option>
        </sl-select>
    </div>

    <sl-input type="text" name="comentario" label="Comentario" maxlength="120"
        value="<?= htmlspecialchars($movimiento['comentario']); ?>"></sl-input>

    <div class="form__buttons">
        <sl-button class="form__button--cancel" variant="secondary"
            onClick="document.getElementById('edit-dialog').hide()" size="medium" pill>
            Cancelar
        </sl-button>
        <sl-button class="form__button--submit" type="submit" variant="primary" size="medium" pill>Guardar</sl-button>
    </div>
</form>