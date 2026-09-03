<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Tarifas</h2>
    <a href="<?= base_url('tarifas/crear') ?>" class="btn btn-primary" data-mdb-ripple-init>
      <i class="fas fa-plus me-1"></i> Nueva Tarifa
    </a>
  </div>

  <form action="<?= base_url('tarifas') ?>" method="get" class="row g-2 align-items-end mb-4">
    <div class="col-auto">
      <label class="form-label" for="tipo_servicio_id">Filtrar por tipo de servicio</label>
      <select class="form-select" id="tipo_servicio_id" name="tipo_servicio_id" onchange="this.form.submit()">
        <option value="">Todos los tipos</option>
        <?php foreach ($tipos as $tipo) : ?>
          <option value="<?= esc((string) $tipo['id']) ?>"
            <?= (string) $tipoSeleccionado === (string) $tipo['id'] ? 'selected' : '' ?>>
            <?= esc($tipo['nombre']) ?> (<?= esc($tipo['codigo']) ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php if (! empty($tipoSeleccionado)) : ?>
      <div class="col-auto">
        <a href="<?= base_url('tarifas') ?>" class="btn btn-outline-secondary">Quitar filtro</a>
      </div>
    <?php endif; ?>
  </form>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Tipo de servicio</th>
          <th>Precio</th>
          <th>Vigente desde</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($tarifas)) : ?>
          <tr>
            <td colspan="4" class="text-center text-muted py-4">Todavia no hay tarifas registradas.</td>
          </tr>
        <?php else : ?>
          <?php foreach ($tarifas as $tarifa) : ?>
            <tr>
              <td><?= esc($tarifa['tipo_nombre']) ?> <span class="text-muted">(<?= esc($tarifa['tipo_codigo']) ?>)</span></td>
              <td>Q<?= number_format((float) $tarifa['precio'], 2) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($tarifa['vigente_desde'])) ?></td>
              <td>
                <?php if ($tarifa['estado'] === 'vigente') : ?>
                  <span class="badge bg-success">Vigente</span>
                <?php elseif ($tarifa['estado'] === 'programada') : ?>
                  <span class="badge bg-info text-dark">Programada</span>
                <?php else : ?>
                  <span class="badge bg-secondary">Historica</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>