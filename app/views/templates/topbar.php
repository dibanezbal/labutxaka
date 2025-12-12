<div class="topbar">
    <div class="topbar-user">
        <sl-button id="btn-open-create-form" class="btn btn-open-modal" variant="primary">Nuevo movimiento</sl-button>
        <sl-dialog id="create-dialog" label="Crear Nuevo Movimiento">
            <div id="create-dialog-content"></div>
            <sl-button slot="footer" variant="primary" id="create-cancel">Cerrar</sl-button>
        </sl-dialog>
        <sl-avatar label="User avatar">
        </sl-avatar>
        <sl-dropdown>
            <sl-button slot="trigger" circle>
                <sl-icon name="person-circle"></sl-icon>
            </sl-button>
            <sl-menu>
                <sl-menu-item value="cut">Cerrar sesión</sl-menu-item>
            </sl-menu>
        </sl-dropdown>
    </div>
</div>