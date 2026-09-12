<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h2 class="h4 mb-0"><?= esc_nativo($contador['codigo_fisico']) ?></h2>
        <a href="<?= base_url('contadores') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Volver
        </a>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Ficha del contador</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><strong>Codigo:</strong> <?= esc_nativo($contador['codigo_fisico']) ?></li>
                        <li class="mb-2"><strong>Direccion de servicio:</strong><br><?= esc_nativo($contador['direccion_servicio']) ?></li>
                        <li class="mb-2"><strong>Tipo de servicio:</strong> <?= esc_nativo($tipo['nombre'] ?? '') ?></li>
                        <li class="mb-2"><strong>Sector:</strong> <?= esc_nativo($sector['nombre'] ?? '') ?></li>
                        <li class="mb-2"><strong>Fecha de asignacion:</strong> <?= esc_nativo($contador['fecha_asignacion'] ?? '') ?></li>
                        <li class="mb-2"><strong>Fecha de desactivacion:</strong> <?= esc_nativo($contador['fecha_desactivacion'] ?? '') ?></li>
                        <li class="mb-0">
                            <strong>Estado:</strong>
                            <?php if ($contador['activo']): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactivo</span>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Cliente</h5>
                </div>
                <div class="card-body">
                    <?php if ($cliente): ?>
                        <ul class="list-unstyled mb-3">
                            <li class="mb-2"><strong>Nombre:</strong> <?= esc_nativo($cliente['nombre']) ?></li>
                            <li class="mb-2"><strong>DPI:</strong> <?= esc_nativo($cliente['dpi'] ?? '') ?></li>
                            <li class="mb-2"><strong>Telefono:</strong> <?= esc_nativo($cliente['telefono'] ?? '') ?></li>
                            <li class="mb-0"><strong>Direccion principal:</strong><br><?= esc_nativo($cliente['direccion_principal']) ?></li>
                        </ul>
                        <?php if (count($otros) > 1): ?>
                            <h6 class="small text-muted">Otros contadores de este cliente</h6>
                            <ul class="list-unstyled mb-0">
                                <?php foreach ($otros as $o): ?>
                                    <?php if ((int) $o['id'] !== (int) $contador['id']): ?>
                                        <li class="mb-1">
                                            <a href="<?= base_url('contadores/ver/' . $o['id']) ?>">
                                                <?= esc_nativo($o['codigo_fisico']) ?> — <?= esc_nativo($o['direccion_servicio']) ?>
                                            </a>
                                            <?php if ($o['activo']): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Estado de pagos</h5>
                </div>
                <div class="card-body">
                    <?php $pend = $pendientes[$contador['id']] ?? 0; ?>
                    <p class="mb-0">
                        <?php if ($pend > 0): ?>
                            <span class="badge bg-warning text-dark fs-6"><?= $pend ?> lectura<?= $pend > 1 ? 's' : '' ?> pendiente<?= $pend > 1 ? 's' : '' ?> de pago</span>
                        <?php else: ?>
                            <span class="badge bg-success fs-6">Al dia</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-3">
        <div class="card-header bg-white">
            <h5 class="mb-0">Historial de lecturas</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Lectura anterior</th>
                            <th>Lectura actual</th>
                            <th>Consumo (litros)</th>
                            <th>Recibo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($historial)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Este contador no tiene lecturas registradas todavia.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($historial as $h): ?>
                                <tr>
                                    <td><?= esc_nativo($h['fecha']) ?></td>
                                    <td><?= esc_nativo($h['lectura_anterior']) ?></td>
                                    <td><?= esc_nativo($h['lectura_actual']) ?></td>
                                    <td><?= esc_nativo($h['consumo_litros']) ?></td>
                                    <td><?= esc_nativo($h['numero_recibo']) ?></td>
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