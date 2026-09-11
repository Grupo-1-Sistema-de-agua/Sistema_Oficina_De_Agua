<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header bg-white">
          <h2 class="h5 mb-0">Nuevo Usuario</h2>
        </div>
        <div class="card-body">
          <form method="post" action="<?= base_url('admin/usuarios') ?>">
            <?= csrf_field_nativo() ?>

            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" name="nombre" id="nombre" class="form-control"
                     value="<?= esc_nativo(old('nombre', '')) ?>" required>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Correo</label>
              <input type="email" name="email" id="email" class="form-control"
                     value="<?= esc_nativo(old('email', '')) ?>" required>
            </div>

            <div class="mb-3">
              <label for="rol_id" class="form-label">Rol</label>
              <select name="rol_id" id="rol_id" class="form-select" required>
                <option value="">Selecciona...</option>
                <?php foreach ($roles as $rol): ?>
                  <option value="<?= esc_nativo($rol['id']) ?>"
                    <?= (string) old('rol_id', '') === (string) $rol['id'] ? 'selected' : '' ?>>
                    <?= esc_nativo($rol['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <input type="password" name="password" id="password" class="form-control"
                     minlength="10" required aria-describedby="passwordHelp">
              <small id="passwordHelp" class="form-text text-muted">Debe tener al menos 10 caracteres.</small>
            </div>

            <div class="mb-4">
              <label for="confirm_password" class="form-label">Confirmar contraseña</label>
              <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                     minlength="10" required>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">Guardar</button>
              <a href="<?= base_url('admin/usuarios') ?>" class="btn btn-outline-secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>