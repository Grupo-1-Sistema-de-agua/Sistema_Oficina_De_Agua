<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>

<div class="container-fluid mt-4">
    <?php if(isset($clima)): ?>
    <div class="card shadow-2-strong text-center mb-4" style="max-width: 18rem;">
        <div class="card-body">
            <h5 class="card-title text-primary"><i class="fas fa-cloud-sun"></i> Clima Actual</h5>
            <h6 class="card-subtitle mb-2 text-muted">Ciudad de Guatemala</h6>

            <?php if($clima['temperatura'] !== '--'): ?>
                <!-- Usé la url dinámica para que cargue la imagen real según el clima -->
                <img src="https://openweathermap.org/img/wn/<?= esc($clima['icono']) ?>@4x.png" alt="Icono del clima">
            <?php endif; ?>

            <h2 class="display-4 my-1 text-dark"><?= esc($clima['temperatura']) ?>°C</h2>
            <p class="card-text text-capitalize text-muted mb-0"><?= esc($clima['descripcion']) ?></p>
            <small class="text-muted">Humedad: <?= esc($clima['humedad']) ?>%</small>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>