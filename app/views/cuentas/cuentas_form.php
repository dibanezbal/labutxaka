<section class="cuentas-form">
    <h1><?= $cuenta ? 'Editar cuenta' : 'Nueva cuenta' ?></h1>

    <form method="POST" action="index.php?c=cuentas&a=save" autocomplete="off">
        <input type="hidden" name="id" value="<?= (int)($cuenta['id'] ?? 0) ?>">

        <sl-input name="nombre" label="Nombre" required
            value="<?= htmlspecialchars((string)($cuenta['nombre'] ?? '')) ?>"></sl-input>

        <sl-input name="saldo_inicial" type="number" step="0.01" label="Saldo inicial" required
            value="<?= htmlspecialchars((string)($cuenta['saldo_inicial'] ?? '0')) ?>"></sl-input>

        <sl-select name="tipo_cuenta" label="Tipo de cuenta"
            value="<?= htmlspecialchars((string)($cuenta['tipo_cuenta'] ?? 'corriente')) ?>">
            <sl-option value="corriente">Corriente</sl-option>
            <sl-option value="ahorro">Ahorro</sl-option>
            <sl-option value="efectivo">Efectivo</sl-option>
            <sl-option value="tarjeta">Tarjeta</sl-option>
        </sl-select>

        <sl-textarea name="descripcion" label="Descripción">
            <?= htmlspecialchars((string)($cuenta['descripcion'] ?? '')) ?></sl-textarea>

        <div class="form-actions">
            <sl-button type="submit" variant="primary" pill><?= $cuenta ? 'Guardar cambios' : 'Crear cuenta' ?>
            </sl-button>
            <sl-button type="button" variant="neutral" pill onclick="history.back()">Cancelar</sl-button>

            <?php if ($cuenta): ?>
            <sl-button type="button" variant="danger" pill id="btn-delete">Eliminar</sl-button>
            <?php endif; ?>
        </div>
    </form>

    <?php if ($cuenta): ?>
    <sl-dialog id="delete-dialog" label="Eliminar cuenta">
        <p>¿Deseas eliminar la cuenta “<?= htmlspecialchars((string)$cuenta['nombre']) ?>”?</p>
        <div slot="footer">
            <form method="POST" action="index.php?c=cuentas&a=delete">
                <input type="hidden" name="id" value="<?= (int)$cuenta['id'] ?>">
                <sl-button type="button" variant="neutral" onclick="document.getElementById('delete-dialog').hide()">
                    Cancelar</sl-button>
                <sl-button type="submit" variant="danger">Eliminar</sl-button>
            </form>
        </div>
    </sl-dialog>
    <script type="module">
    document.getElementById('btn-delete')?.addEventListener('click', () => {
        document.getElementById('delete-dialog')?.show();
    });
    </script>
    <?php endif; ?>
</section>