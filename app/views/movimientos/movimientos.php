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

    <sl-dialog id="delete-dialog" label="Eliminar Movimiento">
        <div id="delete-dialog-content"></div>
        <sl-button class="form__button--cancel" slot="footer" id="delete-cancel" pill>Cancelar</sl-button>
        <sl-button class="form__button--submit" slot="footer" id="delete-accept" pill>Aceptar</sl-button>
    </sl-dialog>
</div>

<div class="movimientos-container">

    <div class="card-list__headers">

        <label class="card-list__select-all">
            <input type="checkbox" id="select-all">
        </label>

        <ul class="card-list__labels">
            <li>Fecha</li>
            <li>Categoría</li>
            <li>Comentario</li>
            <li>Cuenta</li>
            <li>Tipo</li>
            <li>Cantidad</li>
        </ul>
    </div>

    <card-list class="card-list__lista-movimientos" data-url="?c=movimientos&a=listaMovimientos"></card-list>

</div>