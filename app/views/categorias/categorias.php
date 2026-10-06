<h1>Categorías</h1>
<ul>
    <?php foreach ($categorias as $categoria): ?>
    <li value="<?= $categoria['id']; ?>"><?= htmlspecialchars($categoria['nombre']); ?></li>
    <?php endforeach; ?>
</ul>

<?php if (empty($categorias)): ?>
<p>No hay categorías disponibles.</p>
<?php endif; ?>