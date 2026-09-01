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
    <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-body">
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
    <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light fixed-top">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-mdb-collapse-init data-mdb-target="#sidebarMenu"
          aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-bars"></i>
        </button>

        <a class="navbar-brand d-flex align-items-center" href="<?= url_to('DashboardController::index') ?>">
          <i class="fas fa-droplet me-2"></i>
          <strong>Oficina del Agua</strong>
        </a>

        <ul class="navbar-nav ms-auto d-flex flex-row align-items-center">
          <li class="nav-item">
            <button id="themeToggle" type="button" class="nav-link nav-icon-btn border-0 bg-transparent p-0" title="Cambiar tema">
              <i class="fas fa-moon"></i>
            </button>
          </li>

          <li class="nav-item mx-3 d-none d-sm-block">
            <span class="navbar-divider"></span>
          </li>

          <li class="nav-item d-flex align-items-center">
            <div class="user-avatar me-2">
              <?= esc(strtoupper(substr(session()->get('usuario_nombre') ?? '?', 0, 1))) ?>
            </div>
            <div class="d-none d-md-flex flex-column lh-1">
              <span class="user-name"><?= esc(session()->get('usuario_nombre')) ?></span>
              <small class="user-role"><?= esc(session()->get('usuario_rol')) ?></small>
            </div>
          </li>

          <li class="nav-item ms-3">
            <a class="nav-link nav-icon-btn" href="<?= base_url('logout') ?>" title="Cerrar sesion">
              <i class="fas fa-sign-out-alt"></i>
            </a>
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
  <script>
    (function () {
      var boton = document.getElementById('themeToggle');
      var icono = boton.querySelector('i');
      var oscuro = localStorage.getItem('tema') === 'oscuro';

      function aplicar(esOscuro) {
        if (esOscuro) {
          document.documentElement.setAttribute('data-mdb-theme', 'dark');
        } else {
          document.documentElement.removeAttribute('data-mdb-theme');
        }
        icono.className = esOscuro ? 'fas fa-sun' : 'fas fa-moon';
      }

      aplicar(oscuro);

      boton.addEventListener('click', function () {
        oscuro = !oscuro;
        localStorage.setItem('tema', oscuro ? 'oscuro' : 'claro');
        aplicar(oscuro);
      });
    })();
  </script>
</body>
</html>