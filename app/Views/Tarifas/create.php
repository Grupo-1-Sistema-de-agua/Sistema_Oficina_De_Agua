<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <h2 class="mb-4">Nueva Tarifa</h2>

  <?php if (! empty($errors)) : ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errors as $error) : ?>
          <li><?= esc($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="<?= base_url('tarifas') ?>" method="post" class="col-lg-6">
    <?= csrf_field() ?>

    <div class="form-outline mb-4" data-mdb-input-init>
      <select class="select" id="tipo_servicio_id" name="tipo_servicio_id" required>
        <option value="" disabled selected>Selecciona un tipo de servicio</option>
        <?php foreach ($tipos as $tipo) : ?>
          <option value="<?= esc((string) $tipo['id']) ?>">
            <?= esc($tipo['nombre']) ?> (<?= esc($tipo['codigo']) ?>)
          </option>
        <?php endforeach; ?>
      </select>
      <label class="form-label select-label" for="tipo_servicio_id">Tipo de servicio</label>
    </div>

    <div class="form-outline mb-4" data-mdb-input-init>
      <input type="number" step="0.01" min="0.01" class="form-control" id="precio" name="precio"
             value="<?= esc($old['precio'] ?? '') ?>" required>
      <label class="form-label" for="precio">Precio (Q)</label>
    </div>

    <div class="form-outline datepicker mb-2" data-mdb-inline="false" data-mdb-input-init>
      <input type="text" class="form-control" id="vigente_desde" name="vigente_desde"
             value="<?= esc($old['vigente_desde'] ?? '') ?>" autocomplete="off" required>
      <label class="form-label" for="vigente_desde">Fecha de vigencia</label>
    </div>
    <div class="form-text mb-4">
      Selecciona una fecha en el calendario. Si eliges el dia de hoy, la tarifa
      entra en vigencia de inmediato. Si eliges una fecha futura, queda programada
      para ese dia.
    </div>

    <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Guardar</button>
    <a href="<?= base_url('tarifas') ?>" class="btn btn-outline-secondary" data-mdb-ripple-init>Cancelar</a>
  </form>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var campo = document.querySelector('#vigente_desde');
    var datepicker = new mdb.Datepicker(campo.closest('.datepicker'), {
      format: 'dd/mm/yyyy',
      todayButton: true,
      clearButton: true,
      todayHighlight: true,
    });
  });
</script>
<?= $this->endSection() ?>