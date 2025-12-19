<?php $usuario = $_SESSION['user_name'] ?? 'Invitado' ?>

<div class="topbar">
    <div class="topbar-user">
        <sl-button id="btn-open-create-form" class="btn btn-open-modal primary-button" size="medium" variant="primary"
            pill>
            <sl-icon name="plus-lg"></sl-icon>
            Registro
        </sl-button>
        <sl-dialog class="create-new-registro" id="create-dialog" label="Añadir registro">
            <div id="create-dialog-content"></div>
        </sl-dialog>
        <sl-dropdown>
            <sl-button slot="trigger" caret>
                <?= htmlspecialchars($usuario) ?>
            </sl-button>
            <sl-menu>
                <sl-divider></sl-divider>
                <sl-menu-item onclick="location.href='index.php?c=usuarios&a=logout'">Cerrar Sesión</sl-menu-item>
            </sl-menu>
        </sl-dropdown>
    </div>
</div>