<?php $usuario = $_SESSION['user_name'] ?? 'Invitado' ?>

<sl-drawer placement="top" class="drawer-placement-top">
    <?php require __DIR__ . '/sidebar.php'; ?>
</sl-drawer>

<sl-button variant="text" size="">
    <sl-icon name="list" style="font-size: 2rem; color: var(--color-primary-1);" label="Menú">
    </sl-icon>
</sl-button>

<sl-button id="btn-open-create-form" class="btn btn-open-modal primary-button" size="medium" variant="primary" pill>
    <sl-icon name="plus-lg"></sl-icon>
    Registro
</sl-button>
<sl-dialog class="create-new-registro" id="create-dialog" label="Añadir registro">
    <div id="create-dialog-content"></div>
</sl-dialog>
<sl-dropdown class="only-desktop">
    <sl-button slot="trigger" variant="text" class="topbar-username" pill>
        <sl-icon src="app/assets/img/perfil.svg" slot="prefix" style="font-size: 3rem;"></sl-icon>
    </sl-button>
    <sl-menu>
        <sl-menu-item disabled>
            <?php
                $usuarioStr = (string)$usuario;
                $usuarioCap = mb_strtoupper(mb_substr($usuarioStr, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($usuarioStr, 1, null, 'UTF-8');
                echo htmlspecialchars($usuarioCap, ENT_QUOTES, 'UTF-8');
                ?>
        </sl-menu-item>
        <sl-menu-item onclick="location.href='index.php?c=usuarios&a=logout'">Cerrar Sesión</sl-menu-item>
    </sl-menu>
</sl-dropdown>

<script>
const drawer = document.querySelector('.drawer-placement-top');
const openButton = drawer.nextElementSibling;
const closeButton = drawer.querySelector('sl-button[variant="primary"]');

openButton.addEventListener('click', () => drawer.show());
closeButton.addEventListener('click', () => drawer.hide());
</script>