<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h2 class="h4 mb-0">Clientes</h2>
    <a href="<?= base_url('clientes/nuevo') ?>" class="btn btn-primary">
      <i class="fas fa-plus me-1"></i> Nuevo Cliente
    </a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-responsive-cards">
          <thead class="table-light">
            <tr>
              <th>Nombre</th>
              <th>DPI</th>
              <th>Telefono</th>
              <th>Direccion</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($clientes)) : ?>
              <tr>
                <td colspan="5" class="text-center text-muted py-4">No hay clientes registrados en el sistema.</td>
              </tr>
            <?php else : ?>
              <?php foreach ($clientes as $cliente) : ?>
                <tr>
                  <td class="fw-semibold" data-label="Nombre"><?= esc_nativo($cliente['nombre']) ?></td>
                  <td data-label="DPI"><?= esc_nativo($cliente['dpi'] ?? '') ?></td>
                  <td data-label="Telefono"><?= esc_nativo($cliente['telefono'] ?? '') ?></td>
                  <td data-label="Direccion"><?= esc_nativo($cliente['direccion_principal']) ?></td>
                  <td class="text-end celda-acciones" data-label="Acciones">
                    <div class="d-inline-flex gap-2 flex-wrap justify-content-end">
                      <a href="<?= base_url('clientes/editar/' . $cliente['id']) ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-edit me-1"></i>Editar
                      </a>
                      <form action="<?= base_url('clientes/delete/' . $cliente['id']) ?>" method="post" class="d-inline"
                            onsubmit="return confirm('¿Eliminar este cliente? Esta accion no se puede deshacer.');">
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