<link rel="stylesheet" href="/app/assets/css/styles.css">
<link rel="shortcut icon" href="/app/assets/img/favicon.ico" type="image/x-icon">

<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Shoelace -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.10.0/cdn/themes/light.css" />
<script type="module">
window.__shoelace_base_path = 'https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.10.0/cdn';
</script>
<script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.10.0/cdn/shoelace.js"></script>

<section class="auth-home">
    <img src="/app/assets/img/logo_amarillo_vertical.svg" alt="">

    <sl-tab-group placement="bottom">
        <sl-tab slot="nav" panel="login">Entrar</sl-tab>
        <sl-tab slot="nav" panel="signup">Crear cuenta</sl-tab>

        <sl-tab-panel name="login">
            <form method="POST" action="index.php?c=usuarios&a=login" autocomplete="off" data-form="login">
                <sl-input id="login-usuario" type="text" label="Usuario" size="small" required></sl-input>
                <input type="hidden" name="usuario" id="login-usuario-hidden">

                <sl-input id="login-password" type="password" label="Contraseña" size="small" password-toggle required></sl-input>
                <input type="hidden" name="password" id="login-password-hidden">

                <sl-button id="login-submit" type="submit" variant="primary" pill>Iniciar sesión</sl-button>
            </form>
        </sl-tab-panel>

        <sl-tab-panel name="signup">
            <form method="POST" action="index.php?c=usuarios&a=signup" autocomplete="off" data-form="signup">
                <sl-input id="signup-usuario" type="text" label="Nombre" size="small" required></sl-input>
                <input type="hidden" name="usuario" id="signup-usuario-hidden">

                <sl-input id="signup-email" type="email" label="Email" size="small" required></sl-input>
                <input type="hidden" name="email" id="signup-email-hidden">

                <sl-input id="signup-password" type="password" label="Contraseña (mín. 6)" size="small" password-toggle required></sl-input>
                <input type="hidden" name="password" id="signup-password-hidden">

                <sl-input id="signup-confirm" type="password" label="Confirmar contraseña" size="small" required></sl-input>
                <input type="hidden" name="confirm" id="signup-confirm-hidden">

                <sl-button type="submit" variant="primary" pill>Crear cuenta</sl-button>
            </form>
        </sl-tab-panel>
    </sl-tab-group>

    <?php if (!empty($_GET['error'])): ?>
    <sl-alert open variant="danger">
        <sl-icon slot="icon" name="exclamation-triangle"></sl-icon>
        <strong>Error</strong>
                <span>Revisa los datos e inténtalo de nuevo.</span>
    </sl-alert>
    <?php endif; ?>
</section>

<script>
    const sync = (formSelector, pairs) => {
        const form = document.querySelector(formSelector);
        if (!form) return;
        form.addEventListener('submit', () => {
            for (const [slId, hiddenId] of pairs) {
                const sl = document.getElementById(slId);
                const hidden = document.getElementById(hiddenId);
                if (sl && hidden) hidden.value = (sl.value ?? '');
            }
        });
    };

    // iOS/Safari: no dependemos de form-associated custom elements
    sync('form[data-form="login"]', [
        ['login-usuario', 'login-usuario-hidden'],
        ['login-password', 'login-password-hidden'],
    ]);

    sync('form[data-form="signup"]', [
        ['signup-usuario', 'signup-usuario-hidden'],
        ['signup-email', 'signup-email-hidden'],
        ['signup-password', 'signup-password-hidden'],
        ['signup-confirm', 'signup-confirm-hidden'],
    ]);
</script>