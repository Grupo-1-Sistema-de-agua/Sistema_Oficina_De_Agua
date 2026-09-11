<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h2 class="h4 mb-0">Sectores</h2>
    <a href="<?= base_url('sectores/crear') ?>" class="btn btn-primary">
      <i class="fas fa-plus me-1"></i>Nuevo Sector
    </a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Nombre</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($sectores)) : ?>
              <tr>
                <td colspan="2" class="text-center text-muted py-4">Todavia no hay sectores registrados.</td>
              </tr>
            <?php else : ?>
              <?php foreach ($sectores as $sector) : ?>
                <tr>
                  <td class="fw-semibold"><?= esc_nativo($sector['nombre']) ?></td>
                  <td class="text-end">
                    <div class="d-inline-flex gap-2">
                      <a href="<?= base_url('sectores/' . $sector['id'] . '/editar') ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-edit me-1"></i>Editar
                      </a>
                      <form action="<?= base_url('sectores/' . $sector['id'] . '/eliminar') ?>" method="post" class="d-inline"
                            onsubmit="return confirm('¿Eliminar este sector? Esta accion no se puede deshacer.');">
                        <?= csrf_field_nativo() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                          <i class="fas fa-trash me-1"></i>Eliminar
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>