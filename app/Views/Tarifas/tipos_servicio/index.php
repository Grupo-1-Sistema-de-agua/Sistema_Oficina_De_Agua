<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <div class="mb-2">
    <a href="<?= base_url('tarifas') ?>" class="link-primary">
      <i class="fas fa-arrow-left me-1"></i> Volver a Tarifas
    </a>
  </div>
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h2 class="h4 mb-0">Tipos de Servicio</h2>
    <a href="<?= base_url('tipos-servicio/crear') ?>" class="btn btn-primary">
      <i class="fas fa-plus me-1"></i>Nuevo Tipo de Servicio
    </a>
  </div>

  <div class="pt-3">
    <div class="card shadow-sm mt-3">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Codigo</th>
                <th>Nombre</th>
                <th>Volumen incluido (litros)</th>
                <th>Contratable</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($tipos)) : ?>
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">Todavia no hay tipos de servicio registrados.</td>
                </tr>
              <?php else : ?>
                <?php foreach ($tipos as $tipo) : ?>
                  <tr>
                    <td><code><?= esc_nativo($tipo['codigo']) ?></code></td>
                    <td class="fw-semibold"><?= esc_nativo($tipo['nombre']) ?></td>
                    <td><?= $tipo['volumen_incluido_litros'] !== null ? number_format((float) $tipo['volumen_incluido_litros'], 0) . ' L' : '—' ?></td>
                    <td>
                      <?php if ((int) $tipo['es_servicio'] === 1) : ?>
                        <span class="badge bg-success">Si</span>
                      <?php else : ?>
                        <span class="badge bg-secondary">No (excedente)</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-end">
                      <div class="d-inline-flex gap-2">
                        <a href="<?= base_url('tipos-servicio/' . $tipo['id'] . '/editar') ?>" class="btn btn-sm btn-outline-secondary">
                          <i class="fas fa-edit me-1"></i>Editar
                        </a>
                        <form action="<?= base_url('tipos-servicio/' . $tipo['id'] . '/eliminar') ?>" method="post" class="d-inline"
                              onsubmit="return confirm('¿Eliminar este tipo de servicio? Esta accion no se puede deshacer.');">
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
</div>
<?= $this->endSection() ?>