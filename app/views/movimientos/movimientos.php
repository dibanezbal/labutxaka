<script type="module" src="app/assets/js/components/card-list.js"></script>
<script type="module" src="app/assets/js/components/modal.js"></script>

<div class="movimientos-container">

    <sl-button id="btn-open-edit-form" class="btn btn-open-modal" data-id="" variant="primary">Editar
    </sl-button>

    <sl-button id="btn-open-delete-modal" class="btn btn-open-modal" data-id="" variant="primary">Eliminar
    </sl-button>

    <sl-dialog id="edit-dialog" label="Editar Movimiento" style="--width: 640px;">
        <div id="edit-dialog-content"></div>
        <sl-button slot="footer" variant="neutral" id="edit-cancel">Cerrar</sl-button>
    </sl-dialog>

    <sl-dialog id="delete-dialog" label="Eliminar Movimiento" style="--width: 640px;">
        <div id="delete-dialog-content"></div>
        <sl-button slot="footer" variant="neutral" id="delete-cancel">Cancelar</sl-button>
        <sl-button slot="footer" variant="primary" id="delete-accept">Aceptar</sl-button>
    </sl-dialog>

    <card-list movimientos='<?= json_encode($movimientos) ?>' cuentas='<?= json_encode($cuentas) ?>'
        categorias='<?= json_encode($categorias) ?>'>
    </card-list>
</div>