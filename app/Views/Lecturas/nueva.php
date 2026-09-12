<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h2 class="h5 mb-0">Registrar lectura</h2>
                </div>
                <div class="card-body">
                    <?php /*
                      CAMBIOS:
                      - Se agrego la seccion "Informacion del usuario" (nombre,
                        DPI, telefono, direccion) y la tarifa base vigente para
                        el caso de uso "Mostrar informacion del usuario".
                      - Se quito el campo de FECHA del formulario: ahora la fecha
                        es la actual del sistema y solo se muestra como texto.
                    */ ?>
                    <dl class="row mb-3">
                        <dt class="col-sm-4 text-muted">Contador</dt>
                        <dd class="col-sm-8"><a href="<?= base_url('contadores/ver/' . $contador['id']) ?>"><?= esc_nativo($contador['codigo_fisico']) ?></a> — <?= esc_nativo($contador['cliente_nombre']) ?></dd>
                        <dt class="col-sm-4 text-muted">Sector</dt>
                        <dd class="col-sm-8"><?= esc_nativo($contador['sector_nombre']) ?></dd>
                        <dt class="col-sm-4 text-muted">Tipo de servicio</dt>
                        <dd class="col-sm-8"><?= esc_nativo($contador['tipo_nombre']) ?></dd>
                        <dt class="col-sm-4 text-muted">Volumen incluido</dt>
                        <dd class="col-sm-8"><?= esc_nativo(number_format((int) $contador['volumen_incluido_litros'])) ?> litros</dd>
                        <dt class="col-sm-4 text-muted">Tarifa base vigente</dt>
                        <dd class="col-sm-8"><?= $tarifaBase ? 'Q' . esc_nativo(number_format((float) $tarifaBase['precio'], 2)) . ' / litro' : '<span class="text-danger">Sin tarifa vigente</span>' ?></dd>
                    </dl>

                    <?php if ($cliente): ?>
                        <div class="card mb-4 bg-light border-0">
                            <div class="card-body py-3">
                                <h6 class="mb-2">Informacion del usuario</h6>
                                <dl class="row mb-0">
                                    <dt class="col-sm-4 text-muted">Nombre</dt>
                                    <dd class="col-sm-8"><?= esc_nativo($cliente['nombre']) ?></dd>
                                    <dt class="col-sm-4 text-muted">DPI</dt>
                                    <dd class="col-sm-8"><?= esc_nativo($cliente['dpi'] ?? '') ?></dd>
                                    <dt class="col-sm-4 text-muted">Telefono</dt>
                                    <dd class="col-sm-8"><?= esc_nativo($cliente['telefono'] ?? '') ?></dd>
                                    <dt class="col-sm-4 text-muted">Direccion</dt>
                                    <dd class="col-sm-8"><?= esc_nativo($cliente['direccion_principal']) ?></dd>
                                </dl>
                            </div>
                        </div>
                    <?php endif; ?>

                    <dl class="row mb-4">
                        <dt class="col-sm-4 text-muted">Lectura anterior</dt>
                        <dd class="col-sm-8 fw-semibold">
                            <?= esc_nativo($lectura_anterior) ?>
                            <?php if ($ultima_fecha): ?> <small class="text-muted">(<?= esc_nativo($ultima_fecha) ?>)</small><?php endif; ?>
                        </dd>
                    </dl>

                    <form method="post" action="<?= base_url('lecturas/guardar') ?>">
                        <?= csrf_field_nativo() ?>
                        <input type="hidden" name="contador_id" value="<?= esc_nativo($contador['id']) ?>">

                        <div class="mb-3">
                            <label for="lectura_actual" class="form-label">Lectura actual</label>
                            <input type="number" name="lectura_actual" id="lectura_actual" class="form-control"
                                   min="<?= esc_nativo($lectura_anterior) ?>" required>
                            <div class="form-text">
                                Se registrara con la fecha automatica: <strong><?= esc_nativo($fecha) ?></strong>
                                (una lectura por contador por mes de calendario).
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Guardar lectura</button>
                            <a href="<?= base_url('lecturas') ?>" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>