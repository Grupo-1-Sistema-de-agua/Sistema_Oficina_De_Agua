<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header bg-white">
          <h2 class="h5 mb-0">Cambiar contraseña</h2>
        </div>
        <div class="card-body">
          <p class="text-muted">
            Usuario: <strong><?= esc_nativo($usuario['nombre']) ?></strong>
            (<?= esc_nativo($usuario['email']) ?>)
          </p>

          <form method="post" action="<?= base_url('admin/usuarios/' . $usuario['id'] . '/password') ?>">
            <?= csrf_field_nativo() ?>

            <div class="mb-3">
              <label for="password" class="form-label">Nueva contraseña</label>
              <input type="password" name="password" id="password" class="form-control"
                     minlength="10" required>
            </div>

            <div class="mb-4">
              <label for="confirm_password" class="form-label">Confirmar contraseña</label>
              <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                     minlength="10" required>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">Guardar contraseña</button>
              <a href="<?= base_url('admin/usuarios') ?>" class="btn btn-outline-secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>