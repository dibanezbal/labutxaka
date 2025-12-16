<h1>Movimientos</h1>
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

    <sl-dialog id="delete-dialog" label="Eliminar Movimiento" style="--width: 640px;">
        <div id="delete-dialog-content"></div>
        <sl-button slot="footer" variant="neutral" id="delete-cancel">Cancelar</sl-button>
        <sl-button slot="footer" variant="primary" id="delete-accept">Aceptar</sl-button>
    </sl-dialog>
</div>

<div class="movimientos-container">

    <label class="card-list__select-all">
        <input type="checkbox" id="select-all"> Seleccionar todo
    </label>

    <card-list data-url="?c=movimientos&a=listaMovimientos"></card-list>

</div>

<script type="module" src="app/assets/js/components/card-list.js"></script>
<script type="module" src="app/assets/js/components/modal.js"></script>