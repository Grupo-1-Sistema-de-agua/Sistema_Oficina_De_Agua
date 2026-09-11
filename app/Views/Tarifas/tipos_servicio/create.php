<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <h2 class="mb-4">Nuevo Tipo de Servicio</h2>

  <?php if (! empty($errors)) : ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errors as $error) : ?>
          <li><?= esc_nativo($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="<?= base_url('tipos-servicio') ?>" method="post" class="col-lg-6">
    <?= csrf_field_nativo() ?>

    <div class="form-outline mb-4" data-mdb-input-init>
      <input type="text" class="form-control" id="nombre" name="nombre"
             value="<?= esc_nativo($old['nombre'] ?? '') ?>" maxlength="50" required>
      <label class="form-label" for="nombre">Nombre (ej. 1/4 de paja)</label>
    </div>

    <div class="form-outline mb-4" data-mdb-input-init>
      <input type="number" class="form-control" id="volumen_incluido_litros" name="volumen_incluido_litros"
             value="<?= esc_nativo($old['volumen_incluido_litros'] ?? '') ?>">
      <label class="form-label" for="volumen_incluido_litros">Volumen incluido (litros)</label>
    </div>
    <div class="form-text mb-4">
      Para un servicio normal, es el volumen incluido antes de cobrar excedente.
      Para el tipo "exceso", indica cada cuantos litros se aplica el cargo
      (ej. si pones 1000, el precio se cobra por cada 1000 litros de excedente).
    </div>

    <div class="mb-4">
      <label class="form-label d-block">Es un servicio contratable directamente</label>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="es_servicio" id="es_servicio_si" value="1" checked>
        <label class="form-check-label" for="es_servicio_si">Si (ej. 1/4 de paja, 1/2 paja)</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="es_servicio" id="es_servicio_no" value="0">
        <label class="form-check-label" for="es_servicio_no">No (ej. tipo "exceso")</label>
      </div>
    </div>

    <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Guardar</button>
    <a href="<?= base_url('tipos-servicio') ?>" class="btn btn-outline-secondary" data-mdb-ripple-init>Cancelar</a>
  </form>
</div>
<?= $this->endSection() ?>