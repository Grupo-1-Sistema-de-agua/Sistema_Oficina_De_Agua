<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">

  <div class="rounded-4 p-4 mb-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f2942 0%, #123a52 100%);">
    <svg style="position: absolute; top: -30px; right: -10px; width: 130px; height: 130px; opacity: 0.10;" viewBox="0 0 100 100"><path d="M50 8 C50 8 22 42 22 62 C22 79 34 92 50 92 C66 92 78 79 78 62 C78 42 50 8 50 8 Z" fill="#ffffff"></path></svg>
    <div class="small fw-semibold" style="color: #cfe9ef;">Panel del sistema</div>
    <div class="h5 text-white mb-0">Bienvenido, <?= esc_nativo($nombre) ?></div>
  </div>

  <div class="rounded-4 p-4 mb-4 text-white position-relative overflow-hidden d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #0f2942 0%, #123a52 100%);">
    <svg style="position: absolute; bottom: -20px; right: 10px; width: 90px; height: 90px; opacity: 0.14;" viewBox="0 0 100 100"><path d="M50 8 C50 8 22 42 22 62 C22 79 34 92 50 92 C66 92 78 79 78 62 C78 42 50 8 50 8 Z" fill="#ffffff"></path></svg>
    <div>
      <div class="small fw-semibold" style="color: #a9c9d6;">Contadores pendientes este mes</div>
      <div class="fs-1 fw-semibold mt-1"><?= count($pendientesLector) ?></div>
    </div>
    <i class="fas fa-tint" style="font-size: 40px; color: #7fd8e3;"></i>
  </div>

  <div class="rounded-4 p-4" style="background: #fdfcf9; border: 1px solid #e2e2de;">
    <div class="fw-semibold mb-3" style="color: #123a52;">Proximos a leer</div>
    <?php if (empty($pendientesLector)) : ?>
      <p class="text-muted small mb-0">No tienes contadores pendientes este mes.</p>
    <?php else : ?>
      <?php foreach (array_slice($pendientesLector, 0, 6) as $i => $c) : ?>
        <div class="d-flex justify-content-between align-items-center py-2 <?= $i > 0 ? 'border-top' : '' ?>">
          <div>
            <div class="fw-semibold" style="color: #1a1a18; font-size: 13px;"><?= esc_nativo($c['codigo_fisico']) ?></div>
            <div class="small text-muted"><?= esc_nativo($c['cliente_nombre']) ?> &middot; <?= esc_nativo($c['sector_nombre']) ?></div>
          </div>
          <a href="<?= base_url('lecturas/nueva/' . $c['id']) ?>" class="btn btn-sm" style="background: #123a52; color: #fff;">Registrar</a>
        </div>
      <?php endforeach; ?>
      <?php if (count($pendientesLector) > 6) : ?>
        <div class="text-center mt-2">
          <a href="<?= base_url('lecturas') ?>" class="small">Ver los <?= count($pendientesLector) - 6 ?> restantes</a>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>

</div>
<?= $this->endSection() ?>