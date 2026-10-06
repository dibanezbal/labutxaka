<h1>Resumen</h1>

<?php

// Categorías a excluir en gráficos/listados de gastos por categoría (p. ej. transferencias internas)
// Puedes añadir más IDs aquí.
$categoriasExcluidasIds = [25, 26];

// Categorías a excluir SOLO en el bloque "Gastos por categoría".
// Útil si aquí quieres ocultar más cosas sin afectar a otros cálculos.
$categoriasExcluidasGastosPorCategoriaIds = [25, 26];

// Categorías a excluir SOLO en el bloque "Balance".
// Si quieres excluir por igual en ingresos y gastos, pon los mismos IDs en ambos.
$categoriasExcluidasBalanceIngresosIds = [25, 26];
$categoriasExcluidasBalanceGastosIds = [25, 26];

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

<section class="card-list__cuentas-wrapper">
    <ul class="card-list__cuentas">
        <?php foreach ($cuentas as $cuenta): ?>
        <?php
      $icon_cuenta = 'credit-card-2-back';
      if (($cuenta['nombre'] ?? '') === 'Efectivo') {
        $icon_cuenta = 'coin';
      } elseif (($cuenta['nombre'] ?? '') !== 'Efectivo' && stripos($cuenta['nombre'] ?? '', 'ahorro') !== false) {
        $icon_cuenta = 'piggy-bank';
      }
      $saldoActual = $saldoPorCuenta[$cuenta['id']] ?? 0.0;
      // Formatear saldo
      $saldoFmt = number_format($saldoActual, 2, ',', '.');
    ?>
        <a href="index.php?c=cuentas&a=index" ; ?>
            <li id="cuenta-<?= $cuenta['id']; ?>" class="small-card__cuentas" value=" <?= $cuenta['id']; ?>">

                <span class="icon-wrapper">
                    <sl-icon name="<?= $icon_cuenta ?>"></sl-icon>
                </span>
                <div class="small-card__content">
                    <?= htmlspecialchars($cuenta['nombre']); ?>
                    <span class="small-card__saldo"><?= $saldoFmt; ?> €</span>
                </div>
            </li>
            <?php endforeach; ?>
        </a>
    </ul>
</section>

<section class="dashboard-movimientos__wrapper">

    <div id="saldo-total" class="small-card small-card--white">
        <h3 class="small-card__title">Saldo total </h3>

        <div class="small-card__content">
            <span class="icon-wrapper">
                <sl-icon name="<?= $icon_cuenta ?>"></sl-icon>
            </span>

            <?php
            $saldoTotal = array_sum($saldoPorCuenta);
            $saldoTotalFmt = number_format($saldoTotal, 2, ',', '.');
            ?>

            <p class="saldo-total"><?= $saldoTotalFmt; ?> €</p>
        </div>
    </div>


    <div id="balance" class="small-card small-card--white">
        <h3 class="small-card__title">Balance </h3>

        <div class="small-card__content">
            <span class="icon-wrapper">
                <sl-icon src="/app/assets/img/scale.svg"></sl-icon>
            </span>

            <div class="small-card__balance-section">
                <div class="small-card__balance-section-info">
                    <p>Ingresos</p>

                    <?php
              $sumaIngresos = 0.0;
              $mesActualBalance = date('Y-m');
              foreach ($movimientos as $m) 
                {
                  $tipo = strtolower($m['tipo_movimiento'] ?? '');
                  $fecha = $m['fecha_registro'] ?? null;
                  if (!$fecha || date('Y-m', strtotime($fecha)) !== $mesActualBalance) {
                    continue;
                  }

                  if (in_array((int)($m['categoria_id'] ?? 0), $categoriasExcluidasBalanceIngresosIds, true)) {
                    continue;
                  }

                  $cantidad = (float)($m['cantidad'] ?? 0);
                  if ($tipo === 'ingreso') {
                    $sumaIngresos += $cantidad;
                  }
                }
                $sumaIngresosFmt = number_format($sumaIngresos, 2, ',', '.');
                
                ?>
                    <span class="font-ingreso"><?= $sumaIngresosFmt; ?> €</span>
                </div>

                <div class="small-card__balance-section-info">
                    <p>Gastos</p>
                    <?php
            $sumaGastos = 0.0;
            $mesActualBalance = date('Y-m');
            foreach ($movimientos as $m) {
              $tipo = strtolower($m['tipo_movimiento'] ?? '');
              $fecha = $m['fecha_registro'] ?? null;
              if (!$fecha || date('Y-m', strtotime($fecha)) !== $mesActualBalance) {
                continue;
              }

              if (in_array((int)($m['categoria_id'] ?? 0), $categoriasExcluidasBalanceGastosIds, true)) {
                continue;
              }

              $cantidad = (float)($m['cantidad'] ?? 0);
              if ($tipo === 'gasto') {
                $sumaGastos += $cantidad;
              }
            }
            $sumaGastosFmt = number_format($sumaGastos, 2, ',', '.');
            ?>
                    <span class="font-gasto">- <?= $sumaGastosFmt; ?> €</span>
                </div>
            </div>
        </div>
    </div>

    <div id="gasto-mas-mes" class="small-card small-card--white">
        <h3 class="small-card__title">Este mes he gastado más en:</h3>

        <div class="small-card__content">
            <span class="icon-wrapper">
                <sl-icon name="currency-euro"></sl-icon>
            </span>

            <?php
            $mesActual = date('Y-m'); // año-mes actual

            // Sumar gastos por categoría SOLO del mes actual
            $gastosMesPorCategoria = []; // categoria => total

            foreach (($movimientos ?? []) as $m) {
              $tipo = strtolower($m['tipo_movimiento'] ?? '');
              if ($tipo !== 'gasto') {
                continue;
              }

              if (in_array((int)($m['categoria_id'] ?? 0), $categoriasExcluidasIds, true)) {
                continue;
              }

              $fecha = $m['fecha_registro'] ?? null;
              if (!$fecha) {
                continue;
              }

              if (date('Y-m', strtotime($fecha)) !== $mesActual) {
                continue;
              }

              $categoria = (string)($m['categoria_nombre'] ?? 'Sin categoría');
              $cantidad  = (float)($m['cantidad'] ?? 0);

              $gastosMesPorCategoria[$categoria] = ($gastosMesPorCategoria[$categoria] ?? 0) + $cantidad;
            }

            // Elige la categoría con mayor total
            $categoriaMaxima = 'Ninguna';
            $gastoMaximoTotal = 0.0;

            if (!empty($gastosMesPorCategoria)) {
              arsort($gastosMesPorCategoria); 
              $categoriaMaxima = array_key_first($gastosMesPorCategoria);
              $gastoMaximoTotal = (float)$gastosMesPorCategoria[$categoriaMaxima];
            }

            $gastoMaximoTotalFmt = number_format($gastoMaximoTotal, 2, ',', '.');
          ?>

            <div>
                <p class="font-ingreso"><?= htmlspecialchars($categoriaMaxima) ?></p>
                <p class="font-gasto font-weight-bold">
                    - <?= $gastoMaximoTotalFmt; ?> €
                </p>
            </div>
        </div>
    </div>

    <div id="ultimos-movimientos">
        <?php require __DIR__ . '/movimientos/listaResumenMovimientos.php'; ?>
    </div>

    <div id="gastos-por-categoria">
        <a href="index.php?c=movimientos&a=index">
            <div class="small-card small-card--white categoria-list">
                <h3 class="categoria-title">Gastos por categoría</h3>
                <ul>
                    <?php 
            $gastosPorCategoria = [];
            $mesActualCategorias = date('Y-m');

            foreach ($movimientos as $m) {
              $tipo = strtolower($m['tipo_movimiento'] ?? '');
              if ($tipo !== 'gasto') continue;

              $fecha = $m['fecha_registro'] ?? null;
              if (!$fecha || date('Y-m', strtotime($fecha)) !== $mesActualCategorias) {
                continue;
              }

              if (in_array((int)($m['categoria_id'] ?? 0), $categoriasExcluidasGastosPorCategoriaIds, true)) {
                continue;
              }

              $categoria = (string)($m['categoria_nombre'] ?? 'Sin categoría');
              $cantidad  = (float)($m['cantidad'] ?? 0);

              if (!isset($gastosPorCategoria[$categoria])) $gastosPorCategoria[$categoria] = 0.0;
              $gastosPorCategoria[$categoria] += $cantidad;
            }

            // Ordenar de mayor a menor gasto
            arsort($gastosPorCategoria);

            foreach ($gastosPorCategoria as $categoria => $total):
            $totalFmt = number_format($total, 2, ',', '.');
            ?>
                    <li class="categoria-item">
                        <span class="categoria-nombre"><?= htmlspecialchars($categoria); ?></span>
                        <span class="categoria-valor">- <?= $totalFmt; ?> €</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </a>
    </div>
</section>