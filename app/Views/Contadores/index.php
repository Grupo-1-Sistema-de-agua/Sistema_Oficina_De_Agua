<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Contadores</h2>
        <a href="<?= base_url('contadores/nuevo') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Nuevo contador
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Codigo</th>
                            <th>Cliente</th>
                            <th>Tipo de servicio</th>
                            <th>Sector</th>
                            <th>Direccion</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($contadores)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No hay contadores registrados todavia.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($contadores as $cont): ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($cont['codigo_fisico']) ?></td>
                                    <td><?= esc($cont['cliente_nombre']) ?></td>
                                    <td><?= esc($cont['tipo_nombre']) ?></td>
                                    <td><?= esc($cont['sector_nombre']) ?></td>
                                    <td><?= esc($cont['direccion_servicio']) ?></td>
                                    <td>
                                        <?php if ($cont['activo']): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= base_url('contadores/editar/' . $cont['id']) ?>"
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit me-1"></i>Editar
                                        </a>
                                        <form method="post" action="<?= base_url('contadores/eliminar/' . $cont['id']) ?>"
                                              class="d-inline"
                                              onsubmit="return confirm('<?= $cont['activo'] ? 'Desactivar' : 'Activar' ?> este contador?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm <?= $cont['activo'] ? 'btn-outline-danger' : 'btn-outline-success' ?>">
                                                <i class="fas <?= $cont['activo'] ? 'fa-times' : 'fa-check' ?> me-1"></i>
                                                <?= $cont['activo'] ? 'Desactivar' : 'Activar' ?>
                                            </button>
                                        </form>
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