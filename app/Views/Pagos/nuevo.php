<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
  <div class="row justify-content-center">
    <div class="col-lg-7">
      <div class="card shadow-sm">
        <div class="card-header bg-white">
          <h2 class="h5 mb-0">Registrar Pago</h2>
        </div>
        <div class="card-body">

          <dl class="row mb-4">
            <dt class="col-sm-4">Cliente</dt>
            <dd class="col-sm-8"><?= esc_nativo($lectura['cliente_nombre']) ?></dd>

            <dt class="col-sm-4">Contador</dt>
            <dd class="col-sm-8"><?= esc_nativo($lectura['codigo_fisico']) ?></dd>

            <dt class="col-sm-4">Recibo</dt>
            <dd class="col-sm-8"><code><?= esc_nativo($lectura['numero_recibo']) ?></code></dd>

            <dt class="col-sm-4">Fecha de lectura</dt>
            <dd class="col-sm-8"><?= esc_nativo(date('d/m/Y', strtotime($lectura['fecha']))) ?></dd>

            <dt class="col-sm-4">Consumo</dt>
            <dd class="col-sm-8"><?= esc_nativo($lectura['consumo_litros']) ?> litros</dd>

            <dt class="col-sm-4">Monto a pagar</dt>
            <dd class="col-sm-8">
              <span class="fs-4 fw-bold text-primary">
                Q<?= number_format((float) $lectura['monto_base'] + (float) $lectura['monto_exceso'], 2) ?>
              </span>
              <div class="form-text">Este monto viene calculado de la lectura y no se puede modificar.</div>
            </dd>
          </dl>

          <form action="<?= base_url('pagos/store') ?>" method="post">
            <?= csrf_field_nativo() ?>
            <input type="hidden" name="lectura_id" value="<?= esc_nativo($lectura['id']) ?>">
            <input type="hidden" name="pago_token" value="<?= esc_nativo($token) ?>">

            <div class="mb-4">
              <label class="form-label" for="metodo_id">Metodo de pago</label>
              <select class="form-select" id="metodo_id" name="metodo_id" required>
                <option value="" disabled selected>Selecciona un metodo</option>
                <?php foreach ($metodos as $metodo) : ?>
                  <option value="<?= esc_nativo((string) $metodo['id']) ?>">
                    <?= esc_nativo($metodo['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Confirmar pago</button>
              <a href="<?= base_url('pagos') ?>" class="btn btn-outline-secondary" data-mdb-ripple-init>Cancelar</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>