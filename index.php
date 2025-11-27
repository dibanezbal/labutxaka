<?php
include('./app/connection.php');

$con = connection();

$sql = "SELECT * FROM movimientos";
$query = mysqli_query($con, $sql);

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div>
        <form action="./app/insert_movimiento.php" method="POST">
            <h1>Crear usuario</h1>
            <div>
                <input type="radio" name="tipo_movimiento" value="Gasto" checked>Gasto
                <input type="radio" name="tipo_movimiento" value="Ingreso">Ingreso
            </div>
            <input type="date" name="fecha_registro" placeholder="Fecha">
            <input type="radio" name="tipo_registro" value="Fijo">Fijo
            <input type="radio" name="tipo_registro" value="Variable" checked>Variable
            <select name="cuenta" id="cuenta">
                <?php foreach ($cuentas as $id => $nombre): ?>
                <option value="<?= $id; ?>"><?= $nombre; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="categoria" id="categoria">
                <?php foreach ($categorias as $id => $nombre): ?>
                <option value="<?= $id; ?>"><?= $nombre; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="cantidad" placeholder="Cantidad">
            <select name="moneda">
                <option value="EUR">EUR</option>
            </select>
            <input type="hidden" name="moneda" value="EUR">
            <input type="text" name="comentario" placeholder="Comentario">
            <input type="button" value="Cancelar">
            <input type="submit" value="Guardar">
        </form>
    </div>

    <div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Tipo Registro</th>
                    <th>Tipo Movimiento</th>
                    <th>Cuenta</th>
                    <th>Categoría</th>
                    <th>Cantidad</th>
                    <th>Comentario</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_array($query)): ?>
                <tr>

                    <th><?= $row['id']; ?></th>
                    <th><?= $row['fecha_registro']; ?></th>
                    <th><?= $row['tipo_registro']; ?></th>
                    <th><?= $row['tipo_movimiento']; ?></th>
                    <th><?= $cuentas[$row['cuenta_id']]; ?></th>
                    <th><?= $categorias[$row['categoria_id']]; ?></th>
                    <th><?= $row['cantidad']; ?></th>
                    <th><?= $row['comentario']; ?></th>

                    <th><a href="./app/update.php?id=<?= $row['id']; ?>">Editar</a></th>
                    <th><a href="./app/delete_movimiento.php?id=<?= $row['id']; ?>">Eliminar</a></th>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>