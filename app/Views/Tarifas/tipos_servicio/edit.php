<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header bg-white">
          <h2 class="h5 mb-0">Editar Tipo de Servicio</h2>
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

          <form action="<?= base_url('tipos-servicio/' . $tipo['id']) ?>" method="post">
            <?= csrf_field_nativo() ?>

            <div class="mb-3">
              <label class="form-label" for="nombre">Nombre</label>
              <input type="text" class="form-control" id="nombre" name="nombre"
                     value="<?= esc_nativo($tipo['nombre']) ?>" maxlength="50" required>
            </div>

            <div class="mb-4">
              <label class="form-label" for="volumen_incluido_litros">Volumen incluido (litros)</label>
              <input type="number" class="form-control" id="volumen_incluido_litros" name="volumen_incluido_litros"
                     value="<?= esc_nativo((string) ($tipo['volumen_incluido_litros'] ?? '')) ?>">
            </div>

            <div class="mb-4">
              <label class="form-label d-block">Es un servicio contratable directamente</label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="es_servicio" id="es_servicio_si" value="1"
                       <?= (int) $tipo['es_servicio'] === 1 ? 'checked' : '' ?>>
                <label class="form-check-label" for="es_servicio_si">Si</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="es_servicio" id="es_servicio_no" value="0"
                       <?= (int) $tipo['es_servicio'] === 0 ? 'checked' : '' ?>>
                <label class="form-check-label" for="es_servicio_no">No (excedente)</label>
              </div>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Guardar cambios</button>
              <a href="<?= base_url('tipos-servicio') ?>" class="btn btn-outline-secondary" data-mdb-ripple-init>Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>