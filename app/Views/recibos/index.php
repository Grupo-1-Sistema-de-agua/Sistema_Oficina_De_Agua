<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <div class="mb-3">
    <h2 class="h4 mb-0">Recibos</h2>
  </div>

  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <form method="get" action="<?= base_url('recibos') ?>" class="row g-2 align-items-end">
        <div class="col-md-5">
          <label class="form-label small mb-1" for="q">Buscar por cliente o codigo de contador</label>
          <input type="text" class="form-control" id="q" name="q"
                 placeholder="Nombre, codigo..." value="<?= esc_nativo($q ?? '') ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label small mb-1" for="estado">Mostrar</label>
          <select class="form-select" id="estado" name="estado">
            <option value="pendientes" <?= ($estado ?? 'pendientes') === 'pendientes' ? 'selected' : '' ?>>
              Solo con pendientes
            </option>
            <option value="todas" <?= ($estado ?? '') === 'todas' ? 'selected' : '' ?>>
              Todos (incluye al dia)
            </option>
          </select>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-outline-secondary w-100">
            <i class="fas fa-search me-1"></i>Filtrar
          </button>
        </div>
        <?php if (! empty($q) || ($estado ?? 'pendientes') !== 'pendientes') : ?>
          <div class="col-12">
            <a href="<?= base_url('recibos') ?>" class="small">Limpiar filtros</a>
          </div>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Cliente</th>
              <th>Contador</th>
              <th>Lecturas pendientes</th>
              <th>Monto pendiente</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($contadores)) : ?>
              <tr>
                <td colspan="5" class="text-center text-muted py-4">
                  No hay contadores que coincidan con este filtro.
                </td>
              </tr>
            <?php else : ?>
              <?php foreach ($contadores as $c) : ?>
                <?php $pendientes = (int) $c['lecturas_pendientes']; ?>
                <tr>
                  <td class="fw-semibold"><?= esc_nativo($c['cliente_nombre']) ?></td>
                  <td><code><?= esc_nativo($c['codigo_fisico']) ?></code></td>
                  <td>
                    <?php if ($pendientes > 0) : ?>
                      <span class="badge bg-warning text-dark"><?= $pendientes ?> pendiente<?= $pendientes === 1 ? '' : 's' ?></span>
                    <?php else : ?>
                      <span class="badge bg-success">Al dia</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($pendientes > 0) : ?>
                      Q<?= number_format((float) $c['monto_pendiente'], 2) ?>
                    <?php else : ?>
                      <span class="text-muted">—</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-end">
                    <a href="<?= base_url('recibos/imprimir/' . $c['contador_id']) ?>" class="btn btn-sm btn-primary" target="_blank">
                      <i class="fas fa-print me-1"></i>Imprimir recibo
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