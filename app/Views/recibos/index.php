<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <div class="mb-3">
    <h2 class="h4 mb-0">Recibos</h2>
  </div>

  <div class="card shadow-sm mb-4">
    <div class="card-body p-0">
      <div class="px-3 pt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-3">Pendientes de pago</h5>
        <span class="badge bg-warning text-dark mb-3">
          <?= count($contadoresPendientes) ?> contador<?= count($contadoresPendientes) === 1 ? '' : 'es' ?>
        </span>
      </div>
      <div class="px-3 pb-3">
        <form method="get" action="<?= base_url('recibos') ?>" class="d-flex gap-2 flex-wrap">
          <input type="text" name="q_pendientes" class="form-control form-control-sm" style="max-width: 300px;"
                 placeholder="Buscar por cliente o recibo..." value="<?= esc_nativo($qPendientes ?? '') ?>">
          <?php if (! empty($qPagadas)) : ?>
            <input type="hidden" name="q_pagadas" value="<?= esc_nativo($qPagadas) ?>">
          <?php endif; ?>
          <button type="submit" class="btn btn-sm btn-outline-secondary">Buscar</button>
          <?php if (! empty($qPendientes)) : ?>
            <a href="<?= base_url('recibos' . (! empty($qPagadas) ? '?q_pagadas=' . urlencode($qPagadas) : '')) ?>" class="btn btn-sm btn-link">Limpiar</a>
          <?php endif; ?>
        </form>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-responsive-cards">
          <thead class="table-light">
            <tr>
              <th>Cliente</th>
              <th>Contador</th>
              <th>Recibos pendientes</th>
              <th>Monto total</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($contadoresPendientes)) : ?>
              <tr><td colspan="5" class="text-center text-muted py-4">No hay contadores con pendientes.</td></tr>
            <?php else : ?>
              <?php foreach ($contadoresPendientes as $c) : ?>
                <?php
                  $numeros = explode(',', $c['recibos_pendientes']);
                  $visibles = array_slice($numeros, 0, 3);
                  $restantes = count($numeros) - count($visibles);
                ?>
                <tr>
                  <td class="fw-semibold" data-label="Cliente"><?= esc_nativo($c['cliente_nombre']) ?></td>
                  <td data-label="Contador"><code><?= esc_nativo($c['codigo_fisico']) ?></code></td>
                  <td data-label="Recibos pendientes">
                    <?php foreach ($visibles as $numero) : ?>
                      <span class="badge bg-light text-dark border me-1 mb-1"><?= esc_nativo($numero) ?></span>
                    <?php endforeach; ?>
                    <?php if ($restantes > 0) : ?>
                      <span class="badge bg-secondary">+<?= $restantes ?></span>
                    <?php endif; ?>
                  </td>
                  <td data-label="Monto total">Q<?= number_format((float) $c['monto_pendiente'], 2) ?></td>
                  <td class="text-end celda-acciones" data-label="Acciones">
                    <a href="<?= base_url('recibos/imprimir/' . $c['contador_id']) ?>" class="btn btn-sm btn-primary" target="_blank">
                      <i class="fas fa-print me-1"></i>Imprimir
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="px-3 pt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-3">Pagadas</h5>
        <span class="badge bg-success mb-3">
          <?= count($lecturasPagadas) ?> lectura<?= count($lecturasPagadas) === 1 ? '' : 's' ?>
        </span>
      </div>
      <div class="px-3 pb-3">
        <form method="get" action="<?= base_url('recibos') ?>" class="d-flex gap-2 flex-wrap">
          <input type="text" name="q_pagadas" class="form-control form-control-sm" style="max-width: 300px;"
                 placeholder="Buscar por cliente o recibo..." value="<?= esc_nativo($qPagadas ?? '') ?>">
          <?php if (! empty($qPendientes)) : ?>
            <input type="hidden" name="q_pendientes" value="<?= esc_nativo($qPendientes) ?>">
          <?php endif; ?>
          <button type="submit" class="btn btn-sm btn-outline-secondary">Buscar</button>
          <?php if (! empty($qPagadas)) : ?>
            <a href="<?= base_url('recibos' . (! empty($qPendientes) ? '?q_pendientes=' . urlencode($qPendientes) : '')) ?>" class="btn btn-sm btn-link">Limpiar</a>
          <?php endif; ?>
        </form>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-responsive-cards">
          <thead class="table-light">
            <tr>
              <th>Recibo</th>
              <th>Cliente</th>
              <th>Contador</th>
              <th>Monto</th>
              <th>Pagado el</th>
              <th>Metodo</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($lecturasPagadas)) : ?>
              <tr><td colspan="7" class="text-center text-muted py-4">No hay lecturas pagadas todavia.</td></tr>
            <?php else : ?>
              <?php foreach ($lecturasPagadas as $l) : ?>
                <tr>
                  <td data-label="Recibo"><code><?= esc_nativo($l['numero_recibo']) ?></code></td>
                  <td data-label="Cliente"><?= esc_nativo($l['cliente_nombre']) ?></td>
                  <td data-label="Contador"><code><?= esc_nativo($l['codigo_fisico']) ?></code></td>
                  <td data-label="Monto">Q<?= number_format((float) $l['monto_base'] + (float) $l['monto_exceso'], 2) ?></td>
                  <td data-label="Pagado el"><?= esc_nativo(date('d/m/Y', strtotime($l['fecha_pago']))) ?></td>
                  <td data-label="Metodo"><?= esc_nativo($l['metodo_nombre']) ?></td>
                  <td class="text-end celda-acciones" data-label="Acciones">
                    <a href="<?= base_url('recibos/pagada/' . $l['id']) ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
                      <i class="fas fa-receipt me-1"></i>Ver recibo
                    </a>
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