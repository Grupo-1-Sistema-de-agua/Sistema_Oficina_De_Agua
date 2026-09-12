<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">

  <div class="rounded-4 p-4 mb-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f2942 0%, #123a52 100%);">
    <svg style="position: absolute; top: -30px; right: -10px; width: 130px; height: 130px; opacity: 0.10;" viewBox="0 0 100 100"><path d="M50 8 C50 8 22 42 22 62 C22 79 34 92 50 92 C66 92 78 79 78 62 C78 42 50 8 50 8 Z" fill="#ffffff"></path></svg>
    <div class="small fw-semibold" style="color: #cfe9ef;">Panel del sistema</div>
    <div class="h5 text-white mb-0">Bienvenido, <?= esc_nativo($nombre) ?></div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-5">
      <div class="rounded-4 p-3 h-100 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f2942 0%, #123a52 100%);">
        <svg style="position: absolute; bottom: -14px; right: -10px; width: 70px; height: 70px; opacity: 0.14;" viewBox="0 0 100 100"><path d="M50 8 C50 8 22 42 22 62 C22 79 34 92 50 92 C66 92 78 79 78 62 C78 42 50 8 50 8 Z" fill="#ffffff"></path></svg>
        <div class="small fw-semibold" style="color: #a9c9d6;">Monto pendiente de cobro</div>
        <div class="fs-3 fw-semibold mt-1">Q<?= number_format($montoPendiente, 2) ?></div>
        <div class="small mt-1" style="color: #7fd8e3;"><?= esc_nativo($lecturasPendientes) ?> lecturas sin pagar</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="rounded-4 p-3 h-100" style="background: #f4f2ec;">
        <i class="fas fa-users" style="font-size: 18px; color: #123a52;"></i>
        <div class="small fw-semibold mt-2" style="color: #6b6b68;">Clientes</div>
        <div class="fs-3 fw-semibold" style="color: #123a52;"><?= esc_nativo($totalClientes) ?></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="rounded-4 p-3 h-100" style="background: #f4f2ec;">
        <i class="fas fa-tachometer-alt" style="font-size: 18px; color: #123a52;"></i>
        <div class="small fw-semibold mt-2" style="color: #6b6b68;">Contadores activos</div>
        <div class="fs-3 fw-semibold" style="color: #123a52;"><?= esc_nativo($contadoresActivos) ?></div>
      </div>
    </div>
  </div>

  <div class="rounded-4 p-4 mb-4" style="background: #fdfcf9; border: 1px solid #e2e2de;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="fw-semibold" style="color: #123a52;">Ingresos cobrados por mes</div>
      <div class="small" style="color: #6b6b68;">Ultimos 6 meses</div>
    </div>
    <div style="height: 220px;"><canvas id="chartIngresos"></canvas></div>
  </div>

  <div class="rounded-4 p-4 mb-4" style="background: #fdfcf9; border: 1px solid #e2e2de;">
    <div class="fw-semibold mb-3" style="color: #123a52;">Ultimos pagos registrados</div>
    <?php if (empty($ultimosPagos)) : ?>
      <p class="text-muted small mb-0">Todavia no hay pagos registrados.</p>
    <?php else : ?>
      <?php foreach ($ultimosPagos as $i => $p) : ?>
        <div class="d-flex justify-content-between align-items-center py-2 <?= $i > 0 ? 'border-top' : '' ?>">
          <span><?= esc_nativo($p['cliente_nombre']) ?></span>
          <span class="small text-muted"><?= esc_nativo(date('d/m', strtotime($p['fecha_pago']))) ?></span>
          <span class="fw-semibold" style="color: #1d7a4c;">Q<?= number_format((float) $p['monto'], 2) ?></span>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
  const datosIngresos = <?= json_encode($ingresosPorMes) ?>;
  const ctx = document.getElementById('chartIngresos');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: datosIngresos.map(d => d.mes),
      datasets: [{
        data: datosIngresos.map(d => parseFloat(d.total)),
        backgroundColor: '#123a52',
        borderRadius: 5,
        maxBarThickness: 40
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, grid: { color: '#eee' } },
        x: { grid: { display: false } }
      }
    }
  });
</script>
<?= $this->endSection() ?>