<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Ingresar - Oficina del Agua</title>
  <link rel="icon" type="image/svg+xml" href="<?= base_url('assets/img/favicon-agua.svg') ?>">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
  <link rel="stylesheet" href="<?= base_url('assets/css/mdb.min.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>" />
  <style>
    #intro {
      background-image: url(https://images.unsplash.com/photo-1545897398-2aba891843b6?q=80&w=2067&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D);
      height: 100vh;
    }
    @media (min-width: 992px) {
      #intro { margin-top: -58.59px; }
    }
    .navbar .nav-link { color: #fff !important; }
  </style>
</head>
<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark d-none d-lg-block" style="z-index: 2000;">
      <div class="container-fluid">
        <a class="navbar-brand nav-link d-flex align-items-center" href="<?= base_url('login') ?>">
          <i class="fas fa-droplet me-2"></i>
          <strong>Oficina del Agua</strong>
        </a>
      </div>
    </nav>

    <div id="intro" class="bg-image shadow-2-strong">
      <div class="mask d-flex align-items-center h-100" style="background-color: rgba(0, 24, 69, 0.8);">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-md-8">
              <form class="bg-white rounded shadow-5-strong p-5" action="<?= base_url('login') ?>" method="post">
                <div class="text-center mb-4">
                  <i class="fas fa-droplet fa-2x" style="color: #123a52;"></i>
                  <h4 class="mt-2 mb-0" style="color: #123a52;">Oficina del Agua</h4>
                  <p class="text-muted small mb-0">Ingresa con tu cuenta para continuar</p>
                </div>
                <?= csrf_field_nativo() ?>

                <?php $errorFlash = flash_get('error'); ?>
                <?php if ($errorFlash) : ?>
                  <div class="alert alert-danger"><?= esc_nativo($errorFlash) ?></div>
                <?php endif; ?>
                <?php $messageFlash = flash_get('message'); ?>
                <?php if ($messageFlash) : ?>
                  <div class="alert alert-success"><?= esc_nativo($messageFlash) ?></div>
                <?php endif; ?>

                <div class="form-outline mb-4" data-mdb-input-init>
                  <input type="email" name="email" id="form1Example1" class="form-control" required />
                  <label class="form-label" for="form1Example1">Correo</label>
                </div>

                <div class="form-outline mb-4" data-mdb-input-init>
                  <input type="password" name="password" id="form1Example2" class="form-control" required />
                  <label class="form-label" for="form1Example2">Contrasena</label>
                </div>

                <button type="submit" class="btn btn-primary btn-block w-100" data-mdb-ripple-init>Ingresar</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <script type="text/javascript" src="<?= base_url('assets/js/mdb.umd.min.js') ?>"></script>
</body>
</html>