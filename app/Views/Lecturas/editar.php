<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h2 class="h5 mb-0">Editar lectura</h2>
                </div>
                <div class="card-body">
                    <?php /*
                      NUEVA VISTA (formulario de edicion de la lectura vigente).
                      Muestra los datos originales de la lectura (fecha, lectura
                      anterior, numero de recibo) y permite corregir la lectura
                      actual. El envio va hacia 'lecturas/actualizar' y el
                      controlador recalcula consumo y montos.
                    */ ?>
                    <dl class="row mb-3">
                        <dt class="col-sm-4 text-muted">Contador</dt>
                        <dd class="col-sm-8"><a href="<?= base_url('contadores/ver/' . $contador['id']) ?>"><?= esc($contador['codigo_fisico']) ?></a> — <?= esc($contador['cliente_nombre']) ?></dd>
                        <dt class="col-sm-4 text-muted">Sector</dt>
                        <dd class="col-sm-8"><?= esc($contador['sector_nombre']) ?></dd>
                        <dt class="col-sm-4 text-muted">Tipo de servicio</dt>
                        <dd class="col-sm-8"><?= esc($contador['tipo_nombre']) ?></dd>
                        <dt class="col-sm-4 text-muted">Volumen incluido</dt>
                        <dd class="col-sm-8"><?= esc(number_format((int) $contador['volumen_incluido_litros'])) ?> litros</dd>
                        <dt class="col-sm-4 text-muted">Tarifa base vigente</dt>
                        <dd class="col-sm-8"><?= $tarifaBase ? 'Q' . esc(number_format((float) $tarifaBase['precio'], 2)) . ' / litro' : '<span class="text-danger">Sin tarifa vigente</span>' ?></dd>
                    </dl>

                    <?php if ($cliente): ?>
                        <div class="card mb-4 bg-light border-0">
                            <div class="card-body py-3">
                                <h6 class="mb-2">Informacion del usuario</h6>
                                <dl class="row mb-0">
                                    <dt class="col-sm-4 text-muted">Nombre</dt>
                                    <dd class="col-sm-8"><?= esc($cliente['nombre']) ?></dd>
                                    <dt class="col-sm-4 text-muted">DPI</dt>
                                    <dd class="col-sm-8"><?= esc($cliente['dpi'] ?? '') ?></dd>
                                    <dt class="col-sm-4 text-muted">Telefono</dt>
                                    <dd class="col-sm-8"><?= esc($cliente['telefono'] ?? '') ?></dd>
                                    <dt class="col-sm-4 text-muted">Direccion</dt>
                                    <dd class="col-sm-8"><?= esc($cliente['direccion_principal']) ?></dd>
                                </dl>
                            </div>
                        </div>
                    <?php endif; ?>

                    <dl class="row mb-4">
                        <dt class="col-sm-4 text-muted">Fecha de la lectura</dt>
                        <dd class="col-sm-8"><?= esc($lectura['fecha']) ?></dd>
                        <dt class="col-sm-4 text-muted">Lectura anterior</dt>
                        <dd class="col-sm-8 fw-semibold"><?= esc($lectura['lectura_anterior']) ?></dd>
                        <dt class="col-sm-4 text-muted">Recibo</dt>
                        <dd class="col-sm-8"><?= esc($lectura['numero_recibo']) ?></dd>
                    </dl>

                    <form method="post" action="<?= base_url('lecturas/actualizar') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="lectura_id" value="<?= esc($lectura['id']) ?>">

                        <div class="mb-3">
                            <label for="lectura_actual" class="form-label">Lectura actual</label>
                            <input type="number" name="lectura_actual" id="lectura_actual" class="form-control"
                                   value="<?= esc($lectura['lectura_actual']) ?>"
                                   min="<?= esc($lectura['lectura_anterior']) ?>" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                            <a href="<?= base_url('lecturas') ?>" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>