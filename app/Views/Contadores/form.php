<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h2 class="h5 mb-0"><?= esc($titulo) ?></h2>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= ! empty($contador) ? base_url('contadores/actualizar/' . $contador['id']) : base_url('contadores') ?>">
                        <?= csrf_field_nativo() ?>

                        <div class="mb-3">
                            <label for="cliente_id" class="form-label">Cliente</label>
                            <select name="cliente_id" id="cliente_id" class="form-select">
                                <option value="">-- Seleccionar cliente --</option>
                                <?php foreach ($clientes as $c): ?>
                                    <option value="<?= esc($c['id']) ?>"
                                        <?= (string) old('cliente_id', $contador['cliente_id'] ?? '') === (string) $c['id'] ? 'selected' : '' ?>>
                                        <?= esc($c['nombre']) ?><?= ! empty($c['dpi']) ? ' (' . esc($c['dpi']) . ')' : '' ?> | <?= esc($c['direccion_principal']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['cliente_id'])): ?>
                                <small class="text-danger"><?= esc($errors['cliente_id']) ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="tipo_servicio_id" class="form-label">Tipo de servicio</label>
                            <select name="tipo_servicio_id" id="tipo_servicio_id" class="form-select">
                                <option value="">-- Seleccionar tipo --</option>
                                <?php foreach ($tipos as $t): ?>
                                    <option value="<?= esc($t['id']) ?>"
                                        <?= (string) old('tipo_servicio_id', $contador['tipo_servicio_id'] ?? '') === (string) $t['id'] ? 'selected' : '' ?>>
                                        <?= esc($t['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['tipo_servicio_id'])): ?>
                                <small class="text-danger"><?= esc($errors['tipo_servicio_id']) ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="sector_id" class="form-label">Sector</label>
                            <select name="sector_id" id="sector_id" class="form-select">
                                <option value="">-- Seleccionar sector --</option>
                                <?php foreach ($sectores as $s): ?>
                                    <option value="<?= esc($s['id']) ?>"
                                        <?= (string) old('sector_id', $contador['sector_id'] ?? '') === (string) $s['id'] ? 'selected' : '' ?>>
                                        <?= esc($s['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['sector_id'])): ?>
                                <small class="text-danger"><?= esc($errors['sector_id']) ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="codigo_fisico" class="form-label">Codigo fisico</label>
                            <input type="text" name="codigo_fisico" id="codigo_fisico"
                                   value="<?= esc(old('codigo_fisico', $contador['codigo_fisico'] ?? '')) ?>"
                                   class="form-control" maxlength="30">
                            <?php if (isset($errors['codigo_fisico'])): ?>
                                <small class="text-danger"><?= esc($errors['codigo_fisico']) ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_asignacion" class="form-label">Fecha de asignacion</label>
                            <input type="date" name="fecha_asignacion" id="fecha_asignacion"
                                   value="<?= esc(old('fecha_asignacion', $contador['fecha_asignacion'] ?? date('Y-m-d'))) ?>"
                                   class="form-control">
                            <?php if (isset($errors['fecha_asignacion'])): ?>
                                <small class="text-danger"><?= esc($errors['fecha_asignacion']) ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="direccion_servicio" class="form-label">Direccion del servicio</label>
                            <textarea name="direccion_servicio" id="direccion_servicio"
                                      class="form-control" rows="3"><?= esc(old('direccion_servicio', $contador['direccion_servicio'] ?? '')) ?></textarea>
                            <?php if (isset($errors['direccion_servicio'])): ?>
                                <small class="text-danger"><?= esc($errors['direccion_servicio']) ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="activo" id="activo" value="1"
                                <?= (int) old('activo', $contador['activo'] ?? 1) === 1 ? 'checked' : '' ?>>
                            <label class="form-check-label" for="activo">Contador activo</label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="<?= base_url('contadores') ?>" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>