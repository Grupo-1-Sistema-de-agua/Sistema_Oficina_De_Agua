<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <h2>Bienvenido, <?= esc($nombre) ?></h2>
  <p class="text-muted">Rol: <?= esc($rol) ?></p>
  <p>Este es el punto de entrada del sistema. Cada modulo agrega aqui (o en su propia vista) el resumen que necesite.</p>
</div>
<?= $this->endSection() ?>