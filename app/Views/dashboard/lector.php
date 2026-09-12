<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">

  <div class="rounded-4 p-4 mb-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f2942 0%, #123a52 100%);">
    <svg style="position: absolute; top: -30px; right: -10px; width: 130px; height: 130px; opacity: 0.10;" viewBox="0 0 100 100"><path d="M50 8 C50 8 22 42 22 62 C22 79 34 92 50 92 C66 92 78 79 78 62 C78 42 50 8 50 8 Z" fill="#ffffff"></path></svg>
    <div class="small fw-semibold" style="color: #cfe9ef;">Panel del sistema</div>
    <div class="h5 text-white mb-0">Bienvenido, <?= esc_nativo($nombre) ?></div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="rounded-4 p-3 h-100" style="background: #f4f2ec;">
        <i class="fas fa-users" style="font-size: 18px; color: #123a52;"></i>
        <div class="small fw-semibold mt-2" style="color: #6b6b68;">Clientes</div>
        <div class="fs-3 fw-semibold" style="color: #123a52;"><?= esc_nativo($totalClientes) ?></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="rounded-4 p-3 h-100" style="background: #f4f2ec;">
        <i class="fas fa-tachometer-alt" style="font-size: 18px; color: #123a52;"></i>
        <div class="small fw-semibold mt-2" style="color: #6b6b68;">Contadores activos</div>
        <div class="fs-3 fw-semibold" style="color: #123a52;"><?= esc_nativo($contadoresActivos) ?></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="rounded-4 p-3 h-100" style="background: #f4f2ec;">
        <i class="fas fa-tint" style="font-size: 18px; color: #123a52;"></i>
        <div class="small fw-semibold mt-2" style="color: #6b6b68;">Lecturas este mes</div>
        <div class="fs-3 fw-semibold" style="color: #123a52;"><?= esc_nativo($lecturasDelMes) ?></div>
      </div>
    </div>
  </div>

</div>
<?= $this->endSection() ?>