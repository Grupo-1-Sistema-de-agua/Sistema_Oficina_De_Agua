<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">

  <div class="rounded-4 p-4 mb-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f2942 0%, #123a52 100%);">
    <svg style="position: absolute; top: -30px; right: -10px; width: 130px; height: 130px; opacity: 0.10;" viewBox="0 0 100 100"><path d="M50 8 C50 8 22 42 22 62 C22 79 34 92 50 92 C66 92 78 79 78 62 C78 42 50 8 50 8 Z" fill="#ffffff"></path></svg>
    <div class="small fw-semibold" style="color: #cfe9ef;">Panel del sistema</div>
    <div class="h5 text-white mb-0">Bienvenido, <?= esc_nativo($nombre) ?></div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="rounded-4 p-3 h-100" style="background: #f4f2ec;">
        <i class="fas fa-users" style="font-size: 18px; color: #123a52;"></i>
        <div class="small fw-semibold mt-2" style="color: #6b6b68;">Clientes</div>
        <div class="fs-3 fw-semibold" style="color: #123a52;"><?= esc_nativo($totalClientes) ?></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="rounded-4 p-3 h-100" style="background: #f4f2ec;">
        <i class="fas fa-tachometer-alt" style="font-size: 18px; color: #123a52;"></i>
        <div class="small fw-semibold mt-2" style="color: #6b6b68;">Contadores activos</div>
        <div class="fs-3 fw-semibold" style="color: #123a52;"><?= esc_nativo($contadoresActivos) ?></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="rounded-4 p-3 h-100" style="background: #f4f2ec;">
        <i class="fas fa-tint" style="font-size: 18px; color: #123a52;"></i>
        <div class="small fw-semibold mt-2" style="color: #6b6b68;">Lecturas este mes</div>
        <div class="fs-3 fw-semibold" style="color: #123a52;"><?= esc_nativo($lecturasDelMes) ?></div>
      </div>
    </div>
  </div>

  <div class="rounded-4 p-4" style="background: #fdfcf9; border: 1px solid #e2e2de;">
    <div class="fw-semibold mb-3" style="color: #123a52;">Estado de cuenta de clientes</div>
    <form method="get" action="<?= base_url('dashboard') ?>" class="mb-3">
      <div class="d-flex gap-2">
        <input type="text" name="q_cuenta" class="form-control form-control-sm" style="max-width: 300px;"
               placeholder="Buscar por nombre..." value="<?= esc_nativo($qCuenta ?? '') ?>">
        <button type="submit" class="btn btn-sm btn-outline-secondary">Buscar</button>
        <?php if (! empty($qCuenta)) : ?>
          <a href="<?= base_url('dashboard') ?>" class="btn btn-sm btn-link">Limpiar</a>
        <?php endif; ?>
      </div>
    </form>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Cliente</th>
            <th class="text-end">Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($estadosCuenta)) : ?>
            <tr><td colspan="2" class="text-center text-muted py-4">No hay clientes que coincidan.</td></tr>
          <?php else : ?>
            <?php foreach ($estadosCuenta as $c) : ?>
              <?php $pendientes = (int) $c['lecturas_pendientes']; ?>
              <tr>
                <td><?= esc_nativo($c['nombre']) ?></td>
                <td class="text-end">
                  <?php if ($pendientes > 0) : ?>
                    <span class="badge" style="background: #fdecc8; color: #7a4f08;">Pendiente (<?= $pendientes ?>)</span>
                  <?php else : ?>
                    <span class="badge" style="background: #e0f0e6; color: #1d7a4c;">Al dia</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
<?= $this->endSection() ?>