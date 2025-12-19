<h1>Resumen</h1>

<?php

$saldoPorCuenta = [];
$ultimo_mes_registro = date('m');

foreach ($cuentas as $cuenta) {
  $saldoPorCuenta[$cuenta['id']] = ($cuenta['saldo_inicial'] ?? 0);
}

foreach ($movimientos as $m) {
  $cid = (int)($m['cuenta_id'] ?? 0);
  if (!isset($saldoPorCuenta[$cid])) continue;

  $tipo = strtolower($m['tipo_movimiento'] ?? '');
  $cantidad = (float)($m['cantidad'] ?? 0);
  $último_movimiento_mes = date('m', strtotime($m['fecha_registro'] ?? ''));
  
  $mes_filtro = date('m', strtotime($m['fecha_registro'] ?? ''));

  if ($tipo === 'ingreso') {
    $saldoPorCuenta[$cid] += $cantidad;
  } elseif ($tipo === 'gasto') {
    $saldoPorCuenta[$cid] -= $cantidad;
  }
}
?>


<ul>
    <?php foreach ($cuentas as $cuenta): ?>
    <?php
      $icon_cuenta = 'credit-card-2-back';
      if (($cuenta['nombre'] ?? '') === 'Efectivo') {
        $icon_cuenta = 'coin';
      } elseif (($cuenta['nombre'] ?? '') === 'Ahorro') {
        $icon_cuenta = 'piggy-bank';
      }
      $saldoActual = $saldoPorCuenta[$cuenta['id']] ?? 0.0;
      // Formato europeo 1.234,56
      $saldoFmt = number_format($saldoActual, 2, ',', '.');
    ?>
    <li value="<?= $cuenta['id']; ?>">
        <sl-icon name="<?= $icon_cuenta ?>"></sl-icon>
        <?= htmlspecialchars($cuenta['nombre']); ?>
        <span> - Saldo actual: <?= $saldoFmt; ?> €</span>
    </li>
    <?php endforeach; ?>
</ul>

<div class="card__resumen total_cuentas">

    <p><strong>Saldo total:</strong>
        <?php
      $saldoTotal = array_sum($saldoPorCuenta);
      $saldoTotalFmt = number_format($saldoTotal, 2, ',', '.');
      echo $saldoTotalFmt . ' €';
      ?>
    </p>
</div>

<div>

    <p><strong>Suma de ingresos:</strong>
        <?php
      $sumaIngresos = 0.0;
      foreach ($movimientos as $m) {
        $tipo = strtolower($m['tipo_movimiento'] ?? '');
        $cantidad = ($m['cantidad'] ?? 0);
        if ($tipo === 'ingreso') {
          $sumaIngresos += $cantidad;
        }
      }
      $sumaIngresosFmt = number_format($sumaIngresos, 2, ',', '.');
      echo $sumaIngresosFmt . ' €';
      ?>
    </p>
</div>

<div>
    <p><strong>Suma de gastos:</strong>
        <?php
      $sumaGastos = 0.0;
      foreach ($movimientos as $m) {
        $tipo = strtolower($m['tipo_movimiento'] ?? '');
        $cantidad = ($m['cantidad'] ?? 0);
        if ($tipo === 'gasto') {
          $sumaGastos += $cantidad;
        }
      }
      $sumaGastosFmt = number_format($sumaGastos, 2, ',', '.');
      echo $sumaGastosFmt . ' €';
      ?>
</div>


<?php require __DIR__ . '/movimientos/listaResumenMovimientos.php'; ?>