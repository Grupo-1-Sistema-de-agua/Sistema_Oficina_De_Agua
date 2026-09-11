<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h2 class="mb-0">Contadores</h2>
        <a href="<?= base_url('contadores/nuevo') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Nuevo contador
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="get" action="<?= base_url('contadores') ?>" class="row g-2 align-items-end">
                <div class="col-md-6 col-lg-5">
                    <label for="q" class="form-label small mb-1">Buscar</label>
                    <input type="text" name="q" id="q" class="form-control"
                           placeholder="Codigo, direccion, cliente o DPI"
                           value="<?= esc_nativo($q ?? '') ?>">
                </div>
                <div class="col-md-4 col-lg-3">
                    <label for="sector" class="form-label small mb-1">Sector</label>
                    <select name="sector" id="sector" class="form-select">
                        <option value="">-- Todos --</option>
                        <?php foreach ($sectores as $s): ?>
                            <option value="<?= esc_nativo($s['id']) ?>"
                                <?= (int) ($sectorSeleccionado ?? 0) === (int) $s['id'] ? 'selected' : '' ?>>
                                <?= esc_nativo($s['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-search me-1"></i>Filtrar
                    </button>
                </div>
                <?php if (! empty($q) || ! empty($sectorSeleccionado)): ?>
                    <div class="col-12">
                        <a href="<?= base_url('contadores') ?>" class="small">Limpiar filtros</a>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mt-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Codigo</th>
                            <th>Cliente</th>
                            <th>DPI</th>
                            <th>Direccion</th>
                            <th>Sector</th>
                            <th>Tipo de servicio</th>
                            <th>Fecha asignacion</th>
                            <th>Pagos</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($contadores)): ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    No hay contadores que coincidan con la busqueda.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($contadores as $cont): ?>
                                <?php $pend = $pendientes[$cont['id']] ?? 0; ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc_nativo($cont['codigo_fisico']) ?></td>
                                    <td><?= esc_nativo($cont['cliente_nombre']) ?></td>
                                    <td><?= esc_nativo($cont['cliente_dpi'] ?? '') ?></td>
                                    <td><?= esc_nativo($cont['direccion_servicio']) ?></td>
                                    <td><?= esc_nativo($cont['sector_nombre']) ?></td>
                                    <td><?= esc_nativo($cont['tipo_nombre']) ?></td>
                                    <td><?= esc_nativo($cont['fecha_asignacion'] ?? '') ?></td>
                                    <td>
                                        <?php if ($pend > 0): ?>
                                            <span class="badge bg-warning text-dark"><?= $pend ?> pendiente<?= $pend > 1 ? 's' : '' ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Al dia</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($cont['activo']): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                        <a href="<?= base_url('contadores/ver/' . $cont['id']) ?>"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Ver
                                        </a>
                                        <a href="<?= base_url('contadores/editar/' . $cont['id']) ?>"
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit me-1"></i>Editar
                                        </a>
                                        <form method="post" action="<?= base_url('contadores/eliminar/' . $cont['id']) ?>"
                                              class="d-inline"
                                              onsubmit="return confirm('<?= $cont['activo'] ? 'Desactivar' : 'Activar' ?> este contador?');">
                                            <?= csrf_field_nativo() ?>
                                            <button type="submit" class="btn btn-sm <?= $cont['activo'] ? 'btn-outline-danger' : 'btn-outline-success' ?>">
                                                <i class="fas <?= $cont['activo'] ? 'fa-times' : 'fa-check' ?> me-1"></i>
                                                <?= $cont['activo'] ? 'Desactivar' : 'Activar' ?>
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
<?= $this->endSection() ?>