<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header bg-white">
          <h2 class="h5 mb-0">Nuevo Sector</h2>
        </div>
        <div class="card-body">
          <?php if (! empty($errors)) : ?>
            <div class="alert alert-danger">
              <ul class="mb-0">
                <?php foreach ($errors as $error) : ?>
                  <li><?= esc_nativo($error) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form action="<?= base_url('sectores') ?>" method="post">
            <?= csrf_field_nativo() ?>

            <div class="mb-4">
              <label class="form-label" for="nombre">Nombre</label>
              <input type="text" class="form-control" id="nombre" name="nombre"
                     value="<?= esc_nativo($old['nombre'] ?? '') ?>" maxlength="50" required>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Guardar</button>
              <a href="<?= base_url('sectores') ?>" class="btn btn-outline-secondary" data-mdb-ripple-init>Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>