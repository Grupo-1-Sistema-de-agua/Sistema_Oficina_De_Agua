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

  <?php
  $bloques = [
      ['titulo' => 'Vigentes',    'datos' => $vigentes,    'badge' => 'bg-success',        'texto' => 'Vigente',    'color' => '#198754'],
      ['titulo' => 'Programadas', 'datos' => $programadas, 'badge' => 'bg-info text-dark', 'texto' => 'Programada', 'color' => '#0dcaf0'],
      ['titulo' => 'Historicas',  'datos' => $historicas,  'badge' => 'bg-warning text-dark', 'texto' => 'Historica',  'color' => '#d97706'],
  ];
  ?>

  <?php if (empty($vigentes) && empty($programadas) && empty($historicas)) : ?>
    <p class="text-muted text-center py-4">Todavia no hay tarifas registradas.</p>
  <?php endif; ?>

  <?php foreach ($bloques as $bloque) : ?>
    <?php if (empty($bloque['datos'])) continue; ?>

    <div class="d-flex align-items-center px-3 py-2 mb-2 rounded-2"
         style="background-color: <?= $bloque['color'] ?>26; border-left: 5px solid <?= $bloque['color'] ?>;">
      <span class="fw-bold" style="color: <?= $bloque['color'] ?>;"><?= esc($bloque['titulo']) ?></span>
      <span class="text-muted ms-2">(<?= count($bloque['datos']) ?>)</span>
    </div>

    <div class="table-responsive mb-4">
      <table class="table table-striped align-middle mb-0">
        <colgroup>
          <col style="width: 40%;">
          <col style="width: 20%;">
          <col style="width: 25%;">
          <col style="width: 15%;">
        </colgroup>
        <thead>
          <tr>
            <th>Tipo de servicio</th>
            <th>Precio</th>
            <th>Vigente desde</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($bloque['datos'] as $tarifa) : ?>
            <tr>
              <td><?= esc($tarifa['tipo_nombre']) ?> <span class="text-muted">(<?= esc($tarifa['tipo_codigo']) ?>)</span></td>
              <td>Q<?= number_format((float) $tarifa['precio'], 2) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($tarifa['vigente_desde'])) ?></td>
              <td><span class="badge <?= $bloque['badge'] ?>"><?= esc($bloque['texto']) ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endforeach; ?>
</div>
<?= $this->endSection() ?>