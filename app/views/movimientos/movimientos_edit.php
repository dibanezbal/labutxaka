<?php
// Variables esperadas: $movimiento
$fecha_movimiento = date('Y-m-d', strtotime($movimiento['fecha_registro']));


?>
<form id="edit-movimiento-form" action="?c=movimientos&a=update" method="POST">
    <input type="hidden" name="id" value="<?= $movimiento['id']; ?>">
    <div>
        <input type="radio" name="tipo_movimiento" value="Gasto"
            <?= $movimiento['tipo_movimiento'] === 'Gasto' ? 'checked' : ''; ?>>Gasto
        <input type="radio" name="tipo_movimiento" value="Ingreso"
            <?= $movimiento['tipo_movimiento'] === 'Ingreso' ? 'checked' : ''; ?>>Ingreso
    </div>
    <input type="date" name="fecha_registro" placeholder="Fecha" value="<?= $fecha_movimiento; ?>">
    <input type="radio" name="tipo_registro" value="Fijo"
        <?= $movimiento['tipo_registro'] === 'Fijo' ? 'checked' : ''; ?>>Fijo
    <input type="radio" name="tipo_registro" value="Variable"
        <?= $movimiento['tipo_registro'] === 'Variable' ? 'checked' : ''; ?>>Variable
    <select name="cuenta_id" id="cuenta">
        <?php foreach ($cuentas as $id => $nombre): ?>
        <option value="<?= $id; ?>" <?= $movimiento['cuenta_id'] == $id ? 'selected' : ''; ?>><?= $nombre; ?>
        </option>
        <?php endforeach; ?>
    </select>
    <select name="categoria_id" id="categoria">
        <?php foreach ($categorias as $id => $nombre): ?>
        <option value="<?= $id; ?>" <?= $movimiento['categoria_id'] == $id ? 'selected' : ''; ?>><?= $nombre; ?>
        </option>
        <?php endforeach; ?>
    </select>
    <input type="number" name="cantidad" value="<?= $movimiento['cantidad']; ?>" placeholder="Cantidad">
    <select name="moneda">
        <option value="EUR">EUR</option>
    </select>
    <input type="hidden" name="moneda" value="EUR">
    <input type="text" name="comentario" value="<?= $movimiento['comentario']; ?>" placeholder="Comentario">

    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>