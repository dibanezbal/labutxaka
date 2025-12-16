<ul>
    <?php foreach ($data['categorias'] as $id => $nombre): ?>
    <li value="<?= $id; ?>"><?= $nombre; ?></li>
    <?php endforeach; ?>
</ul>