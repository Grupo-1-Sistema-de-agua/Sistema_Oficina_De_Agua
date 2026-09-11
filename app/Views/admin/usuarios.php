<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
      <h2 class="mb-1">Usuarios</h2>
    </div>
    <a href="<?= base_url('admin/usuarios/nuevo') ?>" class="btn btn-primary" data-mdb-ripple-init>
      <i class="fas fa-plus me-1"></i> Nuevo Usuario
    </a>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Nombre</th>
              <th>Correo</th>
              <th>Rol</th>
              <th>Estado</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($usuarios as $usuario): ?>
              <tr>
                <td><?= esc_nativo($usuario['nombre']) ?></td>
                <td><?= esc_nativo($usuario['email']) ?></td>
                <td><?= esc_nativo($usuario['rol_nombre']) ?></td>
                <td>
                  <?php if ((int) $usuario['activo'] === 1): ?>
                    <span class="badge bg-success">Activo</span>
                  <?php else: ?>
                    <span class="badge bg-secondary">Inactivo</span>
                  <?php endif; ?>
                </td>
                <td class="text-end">
                  <a href="<?= base_url('admin/usuarios/' . $usuario['id'] . '/password') ?>" class="btn btn-sm btn-outline-secondary">
                    Cambiar contraseña
                  </a>

                  <form action="<?= base_url('admin/usuarios/toggle') ?>" method="post" class="d-inline">
                    <?= csrf_field_nativo() ?>
                    <input type="hidden" name="usuario_id" value="<?= esc_nativo($usuario['id']) ?>">
                    <?php if ((int) $usuario['activo'] === 1): ?>
                      <button type="submit" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-xmark me-1"></i> Desactivar
                      </button>
                    <?php else: ?>
                      <button type="submit" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-check me-1"></i> Activar
                      </button>
                    <?php endif; ?>
                  </form>

                  <?php $esUsuarioActual = (int) ($_SESSION['id_usuario'] ?? 0) === (int) $usuario['id']; ?>
                  <form action="<?= base_url('admin/usuarios/eliminar') ?>" method="post" class="d-inline ms-2" onsubmit="return confirm('Seguro que quieres eliminar este usuario?');">
                    <?= csrf_field_nativo() ?>
                    <input type="hidden" name="usuario_id" value="<?= esc_nativo($usuario['id']) ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger"
                      <?= $esUsuarioActual ? 'disabled title="No puedes eliminar tu propia cuenta"' : '' ?>>
                      Eliminar
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>