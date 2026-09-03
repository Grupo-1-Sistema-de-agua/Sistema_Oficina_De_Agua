<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h2 class="mb-0">Tarifas</h2>
    <div class="d-flex flex-wrap gap-2">
      <a href="<?= base_url('tipos-servicio') ?>" class="btn btn-outline-primary" data-mdb-ripple-init>
        <i class="fas fa-list me-1"></i> Tipos de Servicio
      </a>
      <a href="<?= base_url('tarifas/crear') ?>" class="btn btn-primary" data-mdb-ripple-init>
        <i class="fas fa-plus me-1"></i> Nueva Tarifa
      </a>
    </div>
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
      ['clave' => 'vigentes',    'titulo' => 'Vigentes',    'datos' => $vigentes,    'badge' => 'bg-success',           'texto' => 'Vigente',    'color' => '#198754'],
      ['clave' => 'programadas', 'titulo' => 'Programadas', 'datos' => $programadas, 'badge' => 'bg-info text-dark',    'texto' => 'Programada', 'color' => '#0dcaf0'],
      ['clave' => 'historicas',  'titulo' => 'Historicas',  'datos' => $historicas,  'badge' => 'bg-warning text-dark', 'texto' => 'Historica',  'color' => '#d97706'],
      ['clave' => 'anuladas',    'titulo' => 'Anuladas',    'datos' => $anuladas,    'badge' => 'bg-danger',            'texto' => 'Anulada',    'color' => '#dc3545'],
  ];
  ?>

  <?php if (empty($vigentes) && empty($programadas) && empty($historicas) && empty($anuladas)) : ?>
    <p class="text-muted text-center py-4">Todavia no hay tarifas registradas.</p>
  <?php endif; ?>

  <?php foreach ($bloques as $bloque) : ?>
    <?php if (empty($bloque['datos'])) continue; ?>

    <?php $muestraVencimiento = in_array($bloque['clave'], ['historicas', 'anuladas'], true); ?>

    <div class="d-flex align-items-center px-3 py-2 mb-2 rounded-2"
         style="background-color: <?= $bloque['color'] ?>26; border-left: 5px solid <?= $bloque['color'] ?>;">
      <span class="fw-bold" style="color: <?= $bloque['color'] ?>;"><?= esc($bloque['titulo']) ?></span>
      <span class="text-muted ms-2">(<?= count($bloque['datos']) ?>)</span>
    </div>

    <div class="table-responsive mb-4">
      <table class="table table-striped align-middle mb-0">
        <colgroup>
          <col style="width: <?= $muestraVencimiento ? '30' : '35' ?>%;">
          <col style="width: <?= $muestraVencimiento ? '15' : '20' ?>%;">
          <col style="width: <?= $muestraVencimiento ? '18' : '22' ?>%;">
          <?php if ($muestraVencimiento) : ?>
            <col style="width: 18%;">
          <?php endif; ?>
          <col style="width: 13%;">
          <?php if ($bloque['clave'] !== 'anuladas' && $bloque['clave'] !== 'historicas') : ?>
            <col style="width: 12%;">
          <?php endif; ?>
        </colgroup>
        <thead>
          <tr>
            <th>Tipo de servicio</th>
            <th>Precio</th>
            <th>Vigente desde</th>
            <?php if ($muestraVencimiento) : ?>
              <th>Vigente hasta</th>
            <?php endif; ?>
            <th>Estado</th>
            <?php if ($bloque['clave'] !== 'anuladas' && $bloque['clave'] !== 'historicas') : ?>
              <th></th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($bloque['datos'] as $tarifa) : ?>
            <tr>
              <td><?= esc($tarifa['tipo_nombre']) ?> <span class="text-muted">(<?= esc($tarifa['tipo_codigo']) ?>)</span></td>
              <td>Q<?= number_format((float) $tarifa['precio'], 2) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($tarifa['vigente_desde'])) ?></td>
              <?php if ($muestraVencimiento) : ?>
                <td><?= $tarifa['vigente_hasta'] ? date('d/m/Y H:i', strtotime($tarifa['vigente_hasta'])) : '—' ?></td>
              <?php endif; ?>
              <td><span class="badge <?= $bloque['badge'] ?>"><?= esc($bloque['texto']) ?></span></td>
              <?php if ($bloque['clave'] !== 'anuladas' && $bloque['clave'] !== 'historicas') : ?>
                <td class="text-end">
                  <form action="<?= base_url('tarifas/' . $tarifa['id'] . '/anular') ?>" method="post"
                        onsubmit="return confirm('¿Anular esta tarifa? Esta accion no se puede deshacer.');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-outline-danger">Anular</button>
                  </form>
                </td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endforeach; ?>
</div>
<?= $this->endSection() ?>