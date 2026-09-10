<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <h2 class="mb-4">Editar Tipo de Servicio</h2>

  <?php if (! empty($errors)) : ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errors as $error) : ?>
          <li><?= esc($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="<?= base_url('tipos-servicio/' . $tipo['id']) ?>" method="post" class="col-lg-6">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="PUT">

    <div class="form-outline mb-4" data-mdb-input-init>
      <input type="text" class="form-control" id="nombre" name="nombre"
             value="<?= esc($tipo['nombre']) ?>" maxlength="50" required>
      <label class="form-label" for="nombre">Nombre</label>
    </div>

    <div class="form-outline mb-4" data-mdb-input-init>
      <input type="number" class="form-control" id="volumen_incluido_litros" name="volumen_incluido_litros"
             value="<?= esc((string) ($tipo['volumen_incluido_litros'] ?? '')) ?>">
      <label class="form-label" for="volumen_incluido_litros">Volumen incluido (litros)</label>
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

    <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Guardar cambios</button>
    <a href="<?= base_url('tipos-servicio') ?>" class="btn btn-outline-secondary" data-mdb-ripple-init>Cancelar</a>
  </form>
</div>
<?= $this->endSection() ?>