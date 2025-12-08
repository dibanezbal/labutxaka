<?php
include('connection.php');
$con = connection();

$id = $_GET['id'];

$sql = "SELECT * FROM movimientos WHERE id = '$id'";
$query = mysqli_query($con, $sql);
$row = mysqli_fetch_array($query);

$cuentas_res = mysqli_query($con, "SELECT id, nombre FROM cuentas");
$cuentas = [];
if ($cuentas_res) {
    while ($c = mysqli_fetch_assoc($cuentas_res)) {
        $cuentas[$c['id']] = $c['nombre'];
    }
}

$categorias_res = mysqli_query($con, "SELECT id, nombre FROM categorias");
$categorias = [];
if ($categorias_res) {
    while ($cat = mysqli_fetch_assoc($categorias_res)) {
        $categorias[$cat['id']] = $cat['nombre'];
    }
}

$fecha_movimiento = date('Y-m-d', strtotime($row['fecha_registro']));


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuarios</title>
</head>

<body>
    <div>
        <h1>Editar movimiento</h1>
        <form action="edit_movimiento.php" method="POST">
            <input type="hidden" name="id" value="<?= $row['id']; ?>">
            <div>
                <input type="radio" name="tipo_movimiento" value="Gasto"
                    <?= $row['tipo_movimiento'] == 'Gasto' ? 'checked' : ''; ?>>Gasto
                <input type="radio" name="tipo_movimiento" value="Ingreso"
                    <?= $row['tipo_movimiento'] == 'Ingreso' ? 'checked' : ''; ?>>Ingreso
            </div>
            <th><?= $row['id']; ?></th>
            <input type="date" name="fecha_registro" placeholder="Fecha" value="<?= $fecha_movimiento; ?>">
            <input type="radio" name="tipo_registro" value="Fijo">Fijo
            <input type="radio" name="tipo_registro" value="Variable" checked>Variable
            <select name="cuenta" id="cuenta">
                <?php foreach ($cuentas as $id => $nombre): ?>
                <option value="<?= $id; ?>" <?= $row['cuenta_id'] == $id ? 'selected' : ''; ?>><?= $nombre; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="categoria" id="categoria">
                <?php foreach ($categorias as $id => $nombre): ?>
                <option value="<?= $id; ?>" <?= $row['categoria_id'] == $id ? 'selected' : ''; ?>><?= $nombre; ?>
                </option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="cantidad" value="<?= $row['cantidad']; ?>" placeholder="Cantidad">
            <select name="moneda">
                <option value="EUR">EUR</option>
            </select>
            <input type="hidden" name="moneda" value="EUR">
            <input type="text" name="comentario" value="<?= $row['comentario']; ?>" placeholder="Comentario">
            <input type="submit" value="Actualizar movimiento">
        </form>
    </div>

</body>

</html>