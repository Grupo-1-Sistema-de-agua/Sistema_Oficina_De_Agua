<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Clientes</h2>
        <!-- Botón para abrir modal de creación -->
        <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modalCrearCliente">
            <i class="fas fa-plus me-2"></i> Nuevo Cliente
        </button>
    </div>

    <!-- Tabla principal -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($clientes) && is_array($clientes)): ?>
                            <?php foreach($clientes as $cliente): ?>
                                <tr>
                                    <td><?= esc($cliente['id']) ?></td>
                                    <td><?= esc($cliente['nombre']) ?></td>
                                    <td><?= esc($cliente['telefono']) ?></td>
                                    <td><?= esc($cliente['direccion_principal']) ?></td>
                                    <td>
                                        <!-- Botón Editar (Abre modal específico por ID) -->
                                        <button type="button" class="btn btn-warning btn-sm btn-floating" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modalEditarCliente<?= $cliente['id'] ?>">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <!-- Botón Eliminar -->
                                        <a href="<?= base_url('clientes/delete/' . $cliente['id']) ?>" class="btn btn-danger btn-sm btn-floating" onclick="return confirm('¿Confirmas la eliminación de este cliente?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal Editar Cliente -->
                                <div class="modal fade" id="modalEditarCliente<?= $cliente['id'] ?>" tabindex="-1" aria-labelledby="modalEditarClienteLabel<?= $cliente['id'] ?>" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="modalEditarClienteLabel<?= $cliente['id'] ?>">Editar Cliente</h5>
                                        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <form action="<?= base_url('clientes/update/' . $cliente['id']) ?>" method="POST">
                                          <div class="modal-body">
                                              <!-- Inputs con clases MDB -->
                                              <div class="form-outline mb-4" data-mdb-input-init>
                                                  <input type="text" id="nombreEdit<?= $cliente['id'] ?>" name="nombre" class="form-control" value="<?= esc($cliente['nombre']) ?>" required maxlength="150" />
                                                  <label class="form-label" for="nombreEdit<?= $cliente['id'] ?>">Nombre</label>
                                              </div>
                                              <div class="form-outline mb-4" data-mdb-input-init>
                                                  <input type="text" id="telefonoEdit<?= $cliente['id'] ?>" name="telefono" class="form-control" value="<?= esc($cliente['telefono']) ?>" maxlength="20" />
                                                  <label class="form-label" for="telefonoEdit<?= $cliente['id'] ?>">Teléfono</label>
                                              </div>
                                              <div class="form-outline mb-4" data-mdb-input-init>
                                                  <input type="text" id="direccionEdit<?= $cliente['id'] ?>" name="direccion_principal" class="form-control" value="<?= esc($cliente['direccion_principal']) ?>" required maxlength="255" />
                                                  <label class="form-label" for="direccionEdit<?= $cliente['id'] ?>">Dirección Principal</label>
                                              </div>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Guardar Cambios</button>
                                          </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay clientes registrados en el sistema.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Cliente -->
<div class="modal fade" id="modalCrearCliente" tabindex="-1" aria-labelledby="modalCrearClienteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCrearClienteLabel">Registrar Nuevo Cliente</h5>
        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('clientes/store') ?>" method="POST">
          <div class="modal-body">
              <div class="form-outline mb-4" data-mdb-input-init>
                  <input type="text" id="nombre" name="nombre" class="form-control" required maxlength="150" />
                  <label class="form-label" for="nombre">Nombre</label>
              </div>
              <div class="form-outline mb-4" data-mdb-input-init>
                  <input type="text" id="telefono" name="telefono" class="form-control" maxlength="20" />
                  <label class="form-label" for="telefono">Teléfono</label>
              </div>
              <div class="form-outline mb-4" data-mdb-input-init>
                  <input type="text" id="direccion_principal" name="direccion_principal" class="form-control" required maxlength="255" />
                  <label class="form-label" for="direccion_principal">Dirección Principal</label>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Guardar</button>
          </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>