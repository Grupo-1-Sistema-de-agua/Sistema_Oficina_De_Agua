<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <div class="mb-3">
    <h2 class="h4 mb-0">Pagos</h2>
  </div>

  <div class="card shadow-sm mb-4">
    <div class="card-body p-0">
      <div class="px-3 pt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-3">Lecturas pendientes de pago</h5>
        <span class="badge bg-warning text-dark mb-3">
          <?= count($lecturasPendientes) ?> pendiente<?= count($lecturasPendientes) === 1 ? '' : 's' ?>
        </span>
      </div>
      <div class="px-3 pb-3">
        <form method="get" action="<?= base_url('pagos') ?>" class="d-flex gap-2">
          <input type="text" name="q_pendientes" class="form-control form-control-sm" style="max-width: 300px;"
                 placeholder="Buscar por cliente o recibo..." value="<?= esc_nativo($qPendientes ?? '') ?>">
          <?php if (! empty($qPagos)) : ?>
            <input type="hidden" name="q_pagos" value="<?= esc_nativo($qPagos) ?>">
          <?php endif; ?>
          <button type="submit" class="btn btn-sm btn-outline-secondary">Buscar</button>
          <?php if (! empty($qPendientes)) : ?>
            <a href="<?= base_url('pagos' . (! empty($qPagos) ? '?q_pagos=' . urlencode($qPagos) : '')) ?>" class="btn btn-sm btn-link">Limpiar</a>
          <?php endif; ?>
        </form>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Cliente</th>
              <th>Direccion</th>
              <th>Recibo</th>
              <th>Fecha de lectura</th>
              <th>Consumo</th>
              <th>Monto</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (! $lecturasPendientes) : ?>
              <tr><td colspan="7" class="text-center text-muted py-4">No hay lecturas pendientes de pago.</td></tr>
            <?php endif; ?>
            <?php foreach ($lecturasPendientes as $lectura) : ?>
              <tr>
                <td>
                  <strong><?= esc_nativo($lectura['cliente_nombre']) ?></strong>
                  <?php if ($lectura['telefono']) : ?>
                    <br><small class="text-muted"><?= esc_nativo($lectura['telefono']) ?></small>
                  <?php endif; ?>
                </td>
                <td><?= esc_nativo($lectura['direccion_principal']) ?></td>
                <td><?= esc_nativo($lectura['numero_recibo']) ?></td>
                <td><?= esc_nativo(date('d/m/Y', strtotime($lectura['fecha']))) ?></td>
                <td><?= esc_nativo($lectura['consumo_litros']) ?> litros</td>
                <td>Q<?= number_format((float) $lectura['monto_base'] + (float) $lectura['monto_exceso'], 2) ?></td>
                <td class="text-end">
                  <a href="<?= base_url('pagos/nuevo/' . $lectura['id']) ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-money-bill me-1"></i>Pagar
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="px-3 pt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-3">Pagos registrados</h5>
        <span class="badge bg-primary mb-3"><?= count($pagos) ?> pago<?= count($pagos) === 1 ? '' : 's' ?></span>
      </div>
      <div class="px-3 pb-3">
        <form method="get" action="<?= base_url('pagos') ?>" class="d-flex gap-2">
          <input type="text" name="q_pagos" class="form-control form-control-sm" style="max-width: 300px;"
                 placeholder="Buscar por cliente o recibo..." value="<?= esc_nativo($qPagos ?? '') ?>">
          <?php if (! empty($qPendientes)) : ?>
            <input type="hidden" name="q_pendientes" value="<?= esc_nativo($qPendientes) ?>">
          <?php endif; ?>
          <button type="submit" class="btn btn-sm btn-outline-secondary">Buscar</button>
          <?php if (! empty($qPagos)) : ?>
            <a href="<?= base_url('pagos' . (! empty($qPendientes) ? '?q_pendientes=' . urlencode($qPendientes) : '')) ?>" class="btn btn-sm btn-link">Limpiar</a>
          <?php endif; ?>
        </form>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Cliente</th>
              <th>Recibo</th>
              <th>Monto</th>
              <th>Fecha</th>
              <th>Metodo</th>
              <th>Registrado por</th>
              <th>Estado</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (! $pagos) : ?>
              <tr><td colspan="8" class="text-center text-muted py-4">No hay pagos registrados.</td></tr>
            <?php endif; ?>
            <?php foreach ($pagos as $pago) : ?>
              <tr class="<?= (int) $pago['anulado'] === 1 ? 'text-muted' : '' ?>">
                <td><?= esc_nativo($pago['cliente_nombre']) ?></td>
                <td><?= esc_nativo($pago['numero_recibo']) ?></td>
                <td>Q<?= esc_nativo(number_format((float) $pago['monto'], 2)) ?></td>
                <td><?= esc_nativo(date('d/m/Y H:i', strtotime($pago['fecha_pago']))) ?></td>
                <td><?= esc_nativo($pago['metodo_nombre']) ?></td>
                <td><?= esc_nativo($pago['usuario_nombre']) ?></td>
                <td>
                  <?php if ((int) $pago['anulado'] === 1) : ?>
                    <span class="badge bg-secondary">Anulado</span>
                  <?php else : ?>
                    <span class="badge bg-success">Activo</span>
                  <?php endif; ?>
                </td>
                <td class="text-end">
                  <?php if ((int) $pago['anulado'] !== 1) : ?>
                    <form action="<?= base_url('pagos/' . $pago['id'] . '/anular') ?>" method="post" class="d-inline"
                          onsubmit="return confirm('¿Anular este pago? La lectura volvera a quedar pendiente.');">
                      <?= csrf_field_nativo() ?>
                      <button type="submit" class="btn btn-sm btn-outline-danger">Anular</button>
                    </form>
                  <?php endif; ?>
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