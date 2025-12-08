<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $data["titulo"]; ?></title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <script src="assets/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
        <h2><?php echo $data["titulo"]; ?></h2>

        <form id="nuevo" name="nuevo" method="POST" action="index.php?c=movimientos&a=save" autocomplete="off">
            <div>
                <input type="radio" name="tipo_movimiento" value="Gasto" checked>Gasto
                <input type="radio" name="tipo_movimiento" value="Ingreso">Ingreso
            </div>
            <input type="date" name="fecha_registro" placeholder="Fecha">
            <input type="radio" name="tipo_registro" value="Fijo">Fijo
            <input type="radio" name="tipo_registro" value="Variable" checked>Variable
            <select name="cuenta_id" id="cuenta">
                <?php foreach ($data['cuentas'] as $id => $nombre): ?>
                <option value="<?= $id; ?>"><?= $nombre; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="categoria_id" id="categoria">
                <?php foreach ($data['categorias'] as $id => $nombre): ?>
                <option value="<?= $id; ?>"><?= $nombre; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="cantidad" placeholder="Cantidad">
            <select name="moneda">
                <option value="EUR">EUR</option>
            </select>
            <input type="hidden" name="moneda" value="EUR">
            <input type="text" name="comentario" placeholder="Comentario">
            <input type="button" value="Cancelar" onclick="window.location.href='index.php'">
            <input type="submit" value="Guardar">

        </form>
    </div>
</body>

</html>