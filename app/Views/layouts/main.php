<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title><?= esc($titulo ?? 'Oficina del Agua') ?></title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
  <link rel="stylesheet" href="<?= base_url('assets/css/mdb.min.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>" />
</head>
<body>
  <header>
    <!-- Sidebar -->
    <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white">
      <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
          <a href="<?= url_to('DashboardController::index') ?>" class="list-group-item list-group-item-action py-2">
            <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Dashboard</span>
          </a>
          <a href="<?= base_url('clientes') ?>" class="list-group-item list-group-item-action py-2">
            <i class="fas fa-users fa-fw me-3"></i><span>Clientes</span>
          </a>
          <a href="<?= base_url('contadores') ?>" class="list-group-item list-group-item-action py-2">
            <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Contadores</span>
          </a>
          <a href="<?= base_url('tarifas') ?>" class="list-group-item list-group-item-action py-2">
            <i class="fas fa-tags fa-fw me-3"></i><span>Tarifas</span>
          </a>
          <a href="<?= base_url('lecturas') ?>" class="list-group-item list-group-item-action py-2">
            <i class="fas fa-tint fa-fw me-3"></i><span>Lecturas</span>
          </a>
          <a href="<?= base_url('pagos') ?>" class="list-group-item list-group-item-action py-2">
            <i class="fas fa-money-bill fa-fw me-3"></i><span>Pagos</span>
          </a>
        </div>
      </div>
    </nav>
    <!-- Navbar -->
    <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-mdb-collapse-init data-mdb-target="#sidebarMenu"
          aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-bars"></i>
        </button>
        <a class="navbar-brand" href="<?= url_to('DashboardController::index') ?>">Oficina del Agua</a>
        <ul class="navbar-nav ms-auto d-flex flex-row">
          <li class="nav-item me-3">
            <span class="nav-link"><?= esc(session()->get('usuario_nombre')) ?> (<?= esc(session()->get('usuario_rol')) ?>)</span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt"></i> Salir</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <main style="margin-top: 58px;">
    <?php if (session()->getFlashdata('error')) : ?>
      <div class="alert alert-danger m-3"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('message')) : ?>
      <div class="alert alert-success m-3"><?= esc(session()->getFlashdata('message')) ?></div>
    <?php endif; ?>

    <?= $this->renderSection('contenido') ?>
  </main>

  <script type="text/javascript" src="<?= base_url('assets/js/mdb.umd.min.js') ?>"></script>
</body>
</html>