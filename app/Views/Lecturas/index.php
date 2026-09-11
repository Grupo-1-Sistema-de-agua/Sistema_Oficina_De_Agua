<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
    <h2 class="mb-3">Lecturas</h2>

    <!--
      CAMBIOS:
      - Se agrego la caja de busqueda por numero de contador o cliente ($q),
        ademas del filtro existente por sector (zona/barrio).
      - Se agrego la columna "Estado mes actual" que indica si el contador ya
        registro lectura este mes (badge verde + boton "Editar lectura") o si
        esta pendiente (badge amarillo + boton "Registrar lectura").
    -->
    <form method="get" action="<?= base_url('lecturas') ?>" class="row g-2 mb-3 align-items-end">
        <div class="col-md-4">
            <label for="q" class="form-label small mb-1">Buscar por numero de contador o cliente</label>
            <input type="text" name="q" id="q" class="form-control"
                   placeholder="Numero, nombre..." value="<?= esc_nativo($q ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label for="sector" class="form-label small mb-1">Zona / Sector</label>
            <select name="sector" class="form-select">
                <option value="">-- Todos los sectores --</option>
                <?php foreach ($sectores as $s): ?>
                    <option value="<?= esc_nativo($s['id']) ?>" <?= (string) ($sectorSeleccionado ?? '') === (string) $s['id'] ? 'selected' : '' ?>>
                        <?= esc_nativo($s['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search me-1"></i>Filtrar
            </button>
        </div>
        <?php if (! empty($q) || ! empty($sectorSeleccionado)): ?>
            <div class="col-12">
                <a href="<?= base_url('lecturas') ?>" class="small">Limpiar filtros</a>
            </div>
        <?php endif; ?>
    </form>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Codigo</th>
                            <th>Cliente</th>
                            <th>Sector</th>
                            <th>Tipo servicio</th>
                            <th>Estado mes actual</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pendientes)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No hay contadores pendientes.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pendientes as $c): ?>
                                <?php $tieneLecturaMes = isset($lecturasMes[$c['id']]); ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc_nativo($c['codigo_fisico']) ?></td>
                                    <td><?= esc_nativo($c['cliente_nombre']) ?></td>
                                    <td><?= esc_nativo($c['sector_nombre']) ?></td>
                                    <td><?= esc_nativo($c['tipo_nombre']) ?></td>
                                    <td>
                                        <?php if ($tieneLecturaMes): ?>
                                            <span class="badge bg-success">Lectura registrada este mes</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pendiente de lectura</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php if ($tieneLecturaMes): ?>
                                            <a href="<?= base_url('lecturas/editar/' . $lecturasMes[$c['id']]) ?>" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-pen me-1"></i>Editar lectura
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('lecturas/nueva/' . $c['id']) ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-tint me-1"></i>Registrar lectura
                                            </a>
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
</div>
<?= $this->endSection() ?>
