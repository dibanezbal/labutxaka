<h1>Movimientos</h1>

<?php
    $f_mes = array_key_exists('mes', $_GET) ? (string)($_GET['mes'] ?? '') : date('Y-m');
  $f_cuenta = (string)($_GET['cuenta_id'] ?? '');
  $f_categoria = (string)($_GET['categoria_id'] ?? '');
  $f_tipo_mov = (string)($_GET['tipo_movimiento'] ?? '');
  $f_tipo_reg = (string)($_GET['tipo_registro'] ?? '');
  $f_q = (string)($_GET['q'] ?? '');

  $qs = [];
  if ($f_mes !== '') $qs['mes'] = $f_mes;
  if ($f_cuenta !== '') $qs['cuenta_id'] = $f_cuenta;
  if ($f_categoria !== '') $qs['categoria_id'] = $f_categoria;
  if ($f_tipo_mov !== '') $qs['tipo_movimiento'] = $f_tipo_mov;
  if ($f_tipo_reg !== '') $qs['tipo_registro'] = $f_tipo_reg;
  if ($f_q !== '') $qs['q'] = $f_q;

  $listUrl = '?c=movimientos&a=listaMovimientos' . (!empty($qs) ? '&' . http_build_query($qs) : '');
?>

<section class="movimientos-filters" aria-label="Filtros de movimientos">
    <div class="movimientos-filters__row">
        <div class="movimientos-filters__field">
            <label for="filter-mes">Mes</label>
            <input id="filter-mes" type="month" value="<?= htmlspecialchars($f_mes) ?>" />
        </div>

        <div class="movimientos-filters__field">
            <label for="filter-cuenta">Cuenta</label>
            <select id="filter-cuenta">
                <option value="">Todas</option>
                <?php foreach (($cuentas ?? []) as $c): ?>
                <option value="<?= (int)($c['id'] ?? 0) ?>"
                    <?= ((string)($c['id'] ?? '') === $f_cuenta) ? 'selected' : '' ?>>
                    <?= htmlspecialchars((string)($c['nombre'] ?? '')) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="movimientos-filters__field">
            <label for="filter-categoria">Categoría</label>
            <select id="filter-categoria">
                <option value="">Todas</option>
                <?php foreach (($categorias ?? []) as $cat): ?>
                <option value="<?= (int)($cat['id'] ?? 0) ?>"
                    <?= ((string)($cat['id'] ?? '') === $f_categoria) ? 'selected' : '' ?>>
                    <?= htmlspecialchars((string)($cat['nombre'] ?? '')) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="movimientos-filters__field">
            <label for="filter-tipo-mov">Tipo</label>
            <select id="filter-tipo-mov">
                <option value="">Todos</option>
                <?php foreach (['Ingreso','Gasto','Transferencia'] as $opt): ?>
                <option value="<?= htmlspecialchars($opt) ?>" <?= ($opt === $f_tipo_mov) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($opt) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="movimientos-filters__field">
            <label for="filter-tipo-reg">Registro</label>
            <select id="filter-tipo-reg">
                <option value="">Todos</option>
                <?php foreach (['Fijo','Variable'] as $opt): ?>
                <option value="<?= htmlspecialchars($opt) ?>" <?= ($opt === $f_tipo_reg) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($opt) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="movimientos-filters__field movimientos-filters__field--grow">
            <label for="filter-q">Buscar</label>
            <input id="filter-q" type="search" placeholder="Comentario, cuenta o categoría"
                value="<?= htmlspecialchars($f_q) ?>" />
        </div>

        <div class="movimientos-filters__actions">
            <sl-button id="filter-reset" variant="default" size="small" pill>Limpiar</sl-button>
        </div>
    </div>
</section>

<div class="movimientos-controls">
    <sl-button id="btn-open-edit-form" class="btn btn-edit btn-open-modal btn-disabled" data-id="" variant="primary"
        size="small" pill>Editar
    </sl-button>

    <sl-button id="btn-open-delete-modal" class="btn btn-delete btn-open-modal btn-disabled" data-id=""
        variant="primary" size="small" pill>
        Eliminar
    </sl-button>

    <sl-dialog id="edit-dialog" label="Editar Movimiento">
        <div id="edit-dialog-content"></div>
    </sl-dialog>

    <sl-dialog id="delete-dialog" label="Eliminar Movimiento">
        <div id="delete-dialog-content"></div>
        <sl-button class="form__button--cancel" slot="footer" id="delete-cancel" pill>Cancelar</sl-button>
        <sl-button class="form__button--submit" slot="footer" id="delete-accept" pill>Aceptar</sl-button>
    </sl-dialog>

</div>

<div class="movimientos-container">
    <div class="card-list__headers">
        <ul class="card-list__labels">
            <label class="card-list__select-all"><span class="only-mobile">Seleccionar todos</span>
                <input type="checkbox" id="select-all">
            </label>
            <li>Fecha</li>
            <li>Categoría</li>
            <li>Comentario</li>
            <li>Cuenta</li>
            <li>Tipo</li>
            <li>Cantidad</li>
        </ul>
    </div>

    <?php if (empty($movimientos)) : ?>
    <p class="no-data-message">No hay movimientos registrados.</p>
    <?php endif; ?>

    <card-list class="card-list__lista-movimientos" data-url="<?= htmlspecialchars($listUrl) ?>"></card-list>
</div>