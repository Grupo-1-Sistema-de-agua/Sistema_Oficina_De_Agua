<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Recibos</h2>
        <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modalCrearRecibo">
            <i class="fas fa-file-invoice-dollar me-2"></i> Generar Recibo
        </button>
    </div>

    <!-- Alertas del Sistema -->
    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('mensaje') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('errores')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errores') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Tabla Principal -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <!-- app/Views/recibos/index.php -->
                <table class="table align-middle mb-0 bg-white">
                    <thead class="bg-light">
                        <tr>
                        <th>N° Recibo</th>
                        <th>Cliente</th>
                        <th>Dirección</th>
                        <th>N° Contador</th>
                        <th>Fecha Emisión</th>
                        <th>Monto Total</th>
                        <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recibos) && is_array($recibos)) : ?>
                        <?php foreach ($recibos as $recibo) : ?>
                            <tr>
                            <td><strong><?= esc($recibo['numero_recibo']) ?></strong></td>
                            <td><?= esc($recibo['nombre_cliente']) ?></td>
                            <td><?= esc($recibo['direccion']) ?></td>
                            <td><?= esc($recibo['numero_contador'] ?? 'N/A') ?></td>
                            <td><?= date('d/m/Y', strtotime($recibo['fecha_emision'])) ?></td>
                            <td>Q. <?= number_format($recibo['monto_total'], 2) ?></td>
                            
                            <!-- AQUÍ SE COLOCAN LOS BOTONES -->
                            <td>
                                <!-- 1. Botón de Imprimir (Abre el ticket térmico) -->
                                <a href="<?= base_url('recibos/imprimir/' . $recibo['id']) ?>" 
                                target="_blank" 
                                class="btn btn-primary btn-sm btn-floating me-1" 
                                title="Imprimir Recibo">
                                <i class="fas fa-print"></i>
                                </a>

                                <!-- 2. Botón de Anular (Baja lógica, no eliminación definitiva) -->
                                <a href="<?= base_url('recibos/anular/' . $recibo['id']) ?>" 
                                class="btn btn-danger btn-sm btn-floating" 
                                onclick="return confirm('¿Estás seguro de anular este recibo? No se eliminará del historial contable.');" 
                                title="Anular Recibo">
                                <i class="fas fa-ban"></i>
                                </a>
                            </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php else : ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">No hay recibos generados en el sistema.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Recibo -->
<div class="modal fade" id="modalCrearRecibo" tabindex="-1" aria-labelledby="modalCrearReciboLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCrearReciboLabel">Generar Nuevo Recibo</h5>
        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form action="<?= base_url('recibos/store') ?>" method="POST">
          <div class="modal-body">
              
              <div class="row mb-4">
                  <div class="col-md-6">
                      <div class="form-outline" data-mdb-input-init>
                          <input type="text" id="numero_recibo" name="numero_recibo" class="form-control active" readonly value="<?= esc($siguiente_recibo ?? 'REC-001') ?>" />
                          <label class="form-label" for="numero_recibo">Número de Recibo (Automático)</label>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-outline" data-mdb-input-init>
                          <input type="date" id="fecha_emision" name="fecha_emision" class="form-control active" required value="<?= date('Y-m-d') ?>" />
                          <label class="form-label" for="fecha_emision">Fecha de Emisión</label>
                      </div>
                  </div>
              </div>

              <!-- Selector de Cliente unificado -->
              <div class="mb-4">
                  <label class="form-label select-label">Cliente</label>
                  <select class="form-select" name="id_cliente" id="selector_cliente" required>
                      <option value="" selected disabled>Seleccione un cliente...</option>
                      <?php if(!empty($clientes)): ?>
                          <?php foreach($clientes as $cliente): ?>
                              <option value="<?= esc($cliente['id']) ?>" 
                                      data-nombre="<?= esc($cliente['nombre']) ?>" 
                                      data-direccion="<?= esc($cliente['direccion_principal']) ?>">
                                  <?= esc($cliente['nombre']) ?>
                              </option>
                          <?php endforeach; ?>
                      <?php endif; ?>
                  </select>
              </div>

              <!-- Input oculto para el snapshot del nombre -->
              <input type="hidden" id="nombre_cliente" name="nombre_cliente" required />

              <!-- Dirección -->
              <div class="form-outline mb-4" data-mdb-input-init>
                  <input type="text" id="direccion" name="direccion" class="form-control" required maxlength="255" readonly />
                  <label class="form-label" for="direccion">Dirección</label>
              </div>
              
              <div class="row mb-4">
                  <div class="col-md-6">
                      <label class="form-label select-label">N° de Contador</label>
                      <!-- Nace deshabilitado hasta que se elija un cliente -->
                      <select class="form-select" name="numero_contador" id="selector_contador" required disabled>
                          <option value="" selected disabled>Elija un contador...</option>
                          <?php if(!empty($contadores)): ?>
                              <?php foreach($contadores as $contador): ?>
                                  <option value="<?= esc($contador['codigo_fisico']) ?>" data-cliente-id="<?= esc($contador['cliente_id']) ?>">
                                      <?= esc($contador['codigo_fisico']) ?> (<?= esc($contador['direccion_servicio']) ?>)
                                  </option>
                              <?php endforeach; ?>
                          <?php endif; ?>
                      </select>
                  </div>
                  <div class="col-md-6">
                      <div class="form-outline" data-mdb-input-init>
                          <input type="number" step="0.01" id="monto_total" name="monto_total" class="form-control" required />
                          <label class="form-label" for="monto_total">Monto Total (Q.)</label>
                      </div>
                  </div>
              </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Emitir Recibo</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- Script para habilitar el selector múltiple de contadores y autocompletar datos -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const selectorCliente = document.getElementById('selector_cliente');
    const selectorContador = document.getElementById('selector_contador');
    
    selectorCliente.addEventListener('change', function() {
        const clienteId = this.value;
        const opcionSeleccionada = this.options[this.selectedIndex];
        
        const nombre = opcionSeleccionada.getAttribute('data-nombre');
        const direccion = opcionSeleccionada.getAttribute('data-direccion');
        
        document.getElementById('nombre_cliente').value = nombre ? nombre : '';
        const inputDireccion = document.getElementById('direccion');
        inputDireccion.value = direccion ? direccion : '';
        
        if(typeof mdb !== 'undefined' && mdb.Input) {
            new mdb.Input(inputDireccion.parentNode).init();
        }
        
        // Habilitar y limpiar el selector de contadores
        selectorContador.removeAttribute('disabled');
        selectorContador.value = "";
        
        // Mostrar únicamente los contadores pertenecientes al cliente seleccionado
        const opcionesContador = selectorContador.querySelectorAll('option');
        
        opcionesContador.forEach(option => {
            if (option.value === "") {
                option.style.display = "block";
                return;
            }
            
            const contadorClienteId = option.getAttribute('data-cliente-id');
            if (contadorClienteId === clienteId) {
                option.style.display = "block";
            } else {
                option.style.display = "none";
            }
        });
    });
});
</script>
<?= $this->endSection() ?>