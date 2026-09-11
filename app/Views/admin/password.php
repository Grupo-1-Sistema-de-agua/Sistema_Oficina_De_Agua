<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h2 class="mb-3">Cambio de contrasena</h2>
                    <p class="text-muted">Establece una nueva contraseña para cualquier usuario del sistema.</p>

                    <form action="<?= base_url('admin/password') ?>" method="post">
                        <?= csrf_field_nativo() ?>
                        <div class="mb-3">
                            <label class="form-label">Usuario</label>
                            <select name="usuario_id" class="form-select" required>
                                <option value="">Selecciona un usuario</option>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <option value="<?= esc_nativo($usuario['id']) ?>"><?= esc_nativo($usuario['nombre']) ?> (<?= esc_nativo($usuario['email']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nueva contraseña</label>
                            <input type="password" name="password" class="form-control" minlength="10" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmar contraseña</label>
                            <input type="password" name="confirm_password" class="form-control" minlength="10" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar contraseña</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
