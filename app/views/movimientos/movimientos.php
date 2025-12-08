<?php
// Variables esperadas: $movimientos, $cuentas, $categorias
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Movimientos</title>
</head>

<body>
    <h1>Movimientos</h1>
    <button><a href="?c=movimientos&a=create">Crear Movimiento</a></button>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Tipo Registro</th>
                <th>Tipo Movimiento</th>
                <th>Cuenta</th>
                <th>Categoría</th>
                <th>Cantidad</th>
                <th>Comentario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimientos as $m): ?>
            <tr>
                <td><?= htmlspecialchars(isset($m['fecha_registro']) ? date('Y-m-d', strtotime($m['fecha_registro'])) : '') ?>
                </td>
                <td><?= htmlspecialchars($m['tipo_registro'] ?? '') ?></td>
                <td><?= htmlspecialchars($m['tipo_movimiento'] ?? '') ?></td>
                <td><?= htmlspecialchars($cuentas[$m['cuenta_id']] ?? '') ?></td>
                <td><?= htmlspecialchars($categorias[$m['categoria_id']] ?? '') ?></td>
                <td><?= htmlspecialchars($m['cantidad'] ?? '') ?></td>
                <td><?= htmlspecialchars($m['comentario'] ?? '') ?></td>
                <td>
                    <a href="?c=movimientos&a=edit&id=<?= $m['id']; ?>">Editar</a>
                    |
                    <a href="?c=movimientos&a=delete&id=<?= $m['id']; ?>">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>