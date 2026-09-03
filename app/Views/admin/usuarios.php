<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Usuarios</h2>
            <p class="text-muted mb-0">Administracion de accesos y estados del sistema.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Crear usuario</h5>
            <form action="<?= base_url('admin/usuarios') ?>" method="post">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Correo</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Rol</label>
                        <select name="rol_id" class="form-select" required>
                            <option value="">Selecciona...</option>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?= esc($rol['id']) ?>"><?= esc($rol['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" minlength="10" required aria-describedby="passwordHelp">
                        <small id="passwordHelp" class="form-text text-muted">Debe tener al menos 10 caracteres.</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" name="confirm_password" class="form-control" minlength="10" required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Guardar usuario</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= esc($usuario['nombre']) ?></td>
                                <td><?= esc($usuario['email']) ?></td>
                                <td><?= esc($usuario['rol_nombre']) ?></td>
                                <td>
                                    <?php if ((int) $usuario['activo'] === 1): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <form action="<?= base_url('admin/usuarios/toggle') ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="usuario_id" value="<?= esc($usuario['id']) ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            <?= (int) $usuario['activo'] === 1 ? 'Desactivar' : 'Activar' ?>
                                        </button>
                                    </form>

                                    <?php if ((int) session()->get('usuario_id') !== (int) $usuario['id']): ?>
                                        <form action="<?= base_url('admin/usuarios/eliminar') ?>" method="post" class="d-inline ms-2" onsubmit="return confirm('Seguro que quieres eliminar este usuario?');">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="usuario_id" value="<?= esc($usuario['id']) ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
