<link rel="stylesheet" href="app/assets/css/styles.css">
<!-- Shoelace -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.10.0/cdn/themes/light.css" />
<script type="module">
window.__shoelace_base_path = 'https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.10.0/cdn';
</script>
<script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.10.0/cdn/shoelace.js"></script>

<section class="auth-home">
    <img src="app/assets/img/logo_amarillo_vertical.svg" alt="">

    <sl-tab-group placement="bottom">
        <sl-tab slot="nav" panel="login">Entrar</sl-tab>
        <sl-tab slot="nav" panel="signup">Crear cuenta</sl-tab>

        <sl-tab-panel name="login">
            <form method="POST" action="index.php?c=usuarios&a=login" autocomplete="off">
                <sl-input name="usuario" type="text" label="Usuario" size="small" required></sl-input>
                <sl-input name="password" type="password" label="Contraseña" size="small" required></sl-input>
                <sl-button type="submit" variant="primary" pill>Iniciar sesión</sl-button>
            </form>
        </sl-tab-panel>

        <sl-tab-panel name="signup">
            <form method="POST" action="index.php?c=usuarios&a=signup" autocomplete="off">
                <sl-input name="usuario" type="text" label="Nombre" size="small" required></sl-input>
                <sl-input name="email" type="email" label="Email" size="small" required></sl-input>
                <sl-input name="password" type="password" label="Contraseña (mín. 6)" size="small" required></sl-input>
                <sl-input name="confirm" type="password" label="Confirmar contraseña" size="small" required></sl-input>
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