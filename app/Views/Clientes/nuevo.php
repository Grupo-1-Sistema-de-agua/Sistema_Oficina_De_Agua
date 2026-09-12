<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header bg-white">
          <h2 class="h5 mb-0">Nuevo Cliente</h2>
        </div>
        <div class="card-body">
          <form action="<?= base_url('clientes/store') ?>" method="post">
            <?= csrf_field_nativo() ?>

            <div class="mb-3">
              <label class="form-label" for="nombre">Nombre</label>
              <input type="text" class="form-control" id="nombre" name="nombre"
                     value="<?= esc_nativo(old('nombre', '')) ?>" required maxlength="150">
            </div>

            <div class="mb-3">
              <label class="form-label" for="dpi">DPI</label>
              <input type="text" class="form-control" id="dpi" name="dpi"
                     value="<?= esc_nativo(old('dpi', '')) ?>" required minlength="13" maxlength="13"
                     inputmode="numeric" pattern="\d{13}">
              <small class="form-text text-muted">13 digitos, sin espacios ni guiones.</small>
            </div>

            <div class="mb-3">
              <label class="form-label" for="telefono">Telefono</label>
              <input type="text" class="form-control" id="telefono" name="telefono"
                     value="<?= esc_nativo(old('telefono', '')) ?>" maxlength="20">
            </div>

            <div class="mb-4">
              <label class="form-label" for="direccion_principal">Direccion Principal</label>
              <input type="text" class="form-control" id="direccion_principal" name="direccion_principal"
                     value="<?= esc_nativo(old('direccion_principal', '')) ?>" required maxlength="255">
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Guardar</button>
              <a href="<?= base_url('clientes') ?>" class="btn btn-outline-secondary" data-mdb-ripple-init>Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>