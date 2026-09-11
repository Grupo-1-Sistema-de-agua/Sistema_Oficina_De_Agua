<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title><?= esc_nativo($titulo ?? 'Oficina del Agua') ?></title>
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
          <?php
            $rolActual = $_SESSION['rol'] ?? null;
            $segmentoActual = strtok(trim(uri_string(), '/'), '/') ?: 'dashboard';
          ?>

          <a href="<?= url_to('DashboardController::index') ?>" class="list-group-item list-group-item-action py-2 <?= $segmentoActual === 'dashboard' ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Dashboard</span>
          </a>

          <?php if ($rolActual === 'administrador'): ?>
            <div class="px-2 pt-3 pb-1 small text-muted">Catalogos</div>
            <a href="<?= base_url('tarifas') ?>" class="list-group-item list-group-item-action py-2 <?= $segmentoActual === 'tarifas' ? 'active' : '' ?>">
              <i class="fas fa-tags fa-fw me-3"></i><span>Tarifas</span>
            </a>
            <a href="<?= base_url('sectores') ?>" class="list-group-item list-group-item-action py-2 <?= $segmentoActual === 'sectores' ? 'active' : '' ?>">
              <i class="fas fa-map-marker-alt fa-fw me-3"></i><span>Sectores</span>
            </a>
          <?php endif; ?>

          <?php if (in_array($rolActual, ['secretaria', 'administrador'], true)): ?>
            <div class="px-2 pt-3 pb-1 small text-muted">Clientes y servicio</div>
            <a href="<?= base_url('clientes') ?>" class="list-group-item list-group-item-action py-2 <?= $segmentoActual === 'clientes' ? 'active' : '' ?>">
              <i class="fas fa-users fa-fw me-3"></i><span>Clientes</span>
            </a>
            <a href="<?= base_url('contadores') ?>" class="list-group-item list-group-item-action py-2 <?= $segmentoActual === 'contadores' ? 'active' : '' ?>">
              <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Contadores</span>
            </a>
          <?php endif; ?>

          <?php if (in_array($rolActual, ['lector', 'secretaria', 'administrador'], true)): ?>
            <div class="px-2 pt-3 pb-1 small text-muted">Operacion</div>
          <?php endif; ?>
          <?php if (in_array($rolActual, ['lector', 'administrador'], true)): ?>
            <a href="<?= base_url('lecturas') ?>" class="list-group-item list-group-item-action py-2 <?= $segmentoActual === 'lecturas' ? 'active' : '' ?>">
              <i class="fas fa-tint fa-fw me-3"></i><span>Lecturas</span>
            </a>
          <?php endif; ?>
          <?php if (in_array($rolActual, ['secretaria', 'administrador'], true)): ?>
            <a href="<?= base_url('pagos') ?>" class="list-group-item list-group-item-action py-2 <?= $segmentoActual === 'pagos' ? 'active' : '' ?>">
              <i class="fas fa-money-bill fa-fw me-3"></i><span>Pagos</span>
            </a>
            <a href="<?= base_url('recibos') ?>" class="list-group-item list-group-item-action py-2 <?= $segmentoActual === 'recibos' ? 'active' : '' ?>">
              <i class="fas fa-file-invoice-dollar fa-fw me-3"></i><span>Recibos</span>
            </a>
          <?php endif; ?>

          <?php if ($rolActual === 'administrador'): ?>
            <div class="px-2 pt-3 pb-1 small text-muted">Administracion</div>
            <a href="<?= base_url('admin/usuarios') ?>" class="list-group-item list-group-item-action py-2 <?= $segmentoActual === 'admin' ? 'active' : '' ?>">
              <i class="fas fa-user-shield fa-fw me-3"></i><span>Usuarios</span>
            </a>
          <?php endif; ?>
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
              <?= esc_nativo(strtoupper(substr($_SESSION['nombre'] ?? '?', 0, 1))) ?>
            </div>
            <div class="d-none d-md-flex flex-column lh-1">
              <span class="user-name"><?= esc_nativo($_SESSION['nombre'] ?? '') ?></span>
              <small class="user-role"><?= esc_nativo($_SESSION['rol'] ?? '') ?></small>
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

  <main id="main-content">
    <?php $errorFlash = flash_get('error'); $messageFlash = flash_get('message'); ?>
    <?php if ($errorFlash || $messageFlash) : ?>
      <div class="pt-4 px-3">
        <?php if ($errorFlash) : ?>
          <div class="alert alert-danger"><?= esc_nativo($errorFlash) ?></div>
        <?php endif; ?>
        <?php if ($messageFlash) : ?>
          <div class="alert alert-success"><?= esc_nativo($messageFlash) ?></div>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <?= $this->renderSection('contenido') ?>
  </main>

  <script type="text/javascript" src="<?= base_url('assets/js/mdb.umd.min.js') ?>"></script>
  <script>
    (function () {
      var nav = document.getElementById('main-navbar');
      var main = document.getElementById('main-content');

      function ajustarMargenSuperior() {
        var altura = nav.offsetHeight;
        main.style.marginTop = altura + 'px';
        document.documentElement.style.setProperty('--navbar-height', altura + 'px');
      }

      ajustarMargenSuperior();
      window.addEventListener('resize', ajustarMargenSuperior);
    })();
  </script>
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