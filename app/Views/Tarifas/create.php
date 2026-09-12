<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header bg-white">
          <h2 class="h5 mb-0">Nueva Tarifa</h2>
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

          <form action="<?= base_url('tarifas') ?>" method="post">
            <?= csrf_field_nativo() ?>

            <div class="mb-3">
              <label class="form-label" for="tipo_servicio_id">Tipo de servicio</label>
              <select class="form-select" id="tipo_servicio_id" name="tipo_servicio_id" required>
                <option value="" disabled selected>Selecciona un tipo de servicio</option>
                <?php foreach ($tipos as $tipo) : ?>
                  <option value="<?= esc_nativo((string) $tipo['id']) ?>">
                    <?= esc_nativo($tipo['nombre']) ?> (<?= esc_nativo($tipo['codigo']) ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label" for="precio">Precio (Q)</label>
              <input type="number" step="0.01" min="0.01" class="form-control" id="precio" name="precio"
                     value="<?= esc_nativo($old['precio'] ?? '') ?>" required>
            </div>

            <div class="mb-2">
              <label class="form-label" for="vigente_desde">Fecha de vigencia</label>
              <div class="input-group">
                <input type="date" class="form-control" id="vigente_desde" name="vigente_desde"
                       value="<?= esc_nativo($old['vigente_desde'] ?? '') ?>" required>
                <button type="button" class="btn btn-outline-secondary" id="btnHoy">Hoy</button>
              </div>
            </div>
            <div class="form-text mb-4">
              Selecciona una fecha en el calendario. Si eliges el dia de hoy, la tarifa
              entra en vigencia de inmediato. Si eliges una fecha futura, queda programada
              para ese dia.
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Guardar</button>
              <a href="<?= base_url('tarifas') ?>" class="btn btn-outline-secondary" data-mdb-ripple-init>Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('btnHoy').addEventListener('click', function () {
    var hoy = new Date();
    var iso = hoy.getFullYear() + '-'
      + String(hoy.getMonth() + 1).padStart(2, '0') + '-'
      + String(hoy.getDate()).padStart(2, '0');
    document.getElementById('vigente_desde').value = iso;
  });
</script>
<?= $this->endSection() ?>