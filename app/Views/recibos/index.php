<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Recibos</h2>
        <button type="button" class="btn btn-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modalCrearRecibo">
            <i class="fas fa-file-invoice-dollar me-2"></i> Generar Recibo
        </button>
    </div>

    <!-- Tabla Principal -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
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
                            
                            <td>
                                <!-- Botón Imprimir -->
                                <a href="<?= base_url('recibos/imprimir/' . $recibo['id']) ?>" 
                                target="_blank" 
                                class="btn btn-primary btn-sm btn-floating me-1" 
                                title="Imprimir Recibo">
                                <i class="fas fa-print"></i>
                                </a>

                                <!-- Botón Anular -->
                                <a href="<?= base_url('recibos/anular/' . $recibo['id']) ?>" 
                                class="btn btn-danger btn-sm btn-floating" 
                                onclick="return confirm('¿Estás seguro de anular este recibo?');" 
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

            <input type="hidden" id="nombre_cliente" name="nombre_cliente" required />

            <div class="form-outline mb-4" data-mdb-input-init>
                <input type="text" id="direccion" name="direccion" class="form-control bg-light" required maxlength="255" readonly />
                <label class="form-label" for="direccion">Dirección</label>
            </div>
              
            <!-- N° de Contador -->
            <div class="mb-3">
                <label for="selector_contador" class="form-label fw-bold text-primary">
                    <i class="fas fa-tachometer-alt"></i> N° de Contador (Físico)
                </label>
                <select name="numero_contador" id="selector_contador" class="form-select" required disabled>
                    <option value="">-- Seleccione un contador --</option>
                    <?php foreach ($contadores as $contador) : ?>
                        <?php 
                            $cId = $contador['id'];
                            $consumo = isset($mapaLecturas[$cId]) ? $mapaLecturas[$cId]['consumo'] : 0;
                            $monto = isset($mapaLecturas[$cId]) ? $mapaLecturas[$cId]['monto'] : 0;
                        ?>
                        <option value="<?= esc($contador['codigo_fisico']) ?>" 
                                data-cliente-id="<?= esc($contador['cliente_id']) ?>"
                                data-consumo="<?= esc($consumo) ?>"
                                data-monto="<?= esc($monto) ?>">
                            <?= esc($contador['codigo_fisico']) ?> - Dir: <?= esc($contador['direccion_servicio']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Consumo Automático -->
            <div class="mb-3">
                <label class="form-label fw-bold text-info">
                    <i class="fas fa-tint"></i> Consumo Registrado
                </label>
                <input type="text" id="display_consumo" class="form-control bg-light" readonly placeholder="Seleccione un contador para ver consumo..." />
                <!-- Hidden input que realmente se enviará al controlador -->
                <input type="hidden" name="consumo_litros" id="input_consumo" required />
            </div>

            <!-- Monto Automático -->
            <div class="mb-3">
                <label class="form-label fw-bold text-success">
                    <i class="fas fa-money-bill-wave"></i> Monto a Pagar
                </label>
                <input type="text" id="display_monto" class="form-control bg-light text-success fw-bold" readonly placeholder="Seleccione un contador para ver el monto..." />
                <!-- Hidden input que realmente se enviará al controlador -->
                <input type="hidden" name="monto_total" id="input_monto" required />
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" id="btn_emitir" data-mdb-ripple-init>Emitir Recibo</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const selectorCliente = document.getElementById('selector_cliente');
    const selectorContador = document.getElementById('selector_contador');
    
    // Displays e Inputs ocultos
    const displayConsumo = document.getElementById('display_consumo');
    const inputConsumo = document.getElementById('input_consumo');
    const displayMonto = document.getElementById('display_monto');
    const inputMonto = document.getElementById('input_monto');
    const btnEmitir = document.getElementById('btn_emitir');

    // Función para limpiar campos de pago
    function limpiarCamposPago() {
        displayConsumo.value = "";
        inputConsumo.value = "";
        displayMonto.value = "";
        inputMonto.value = "";
        btnEmitir.disabled = false;
    }

    // 1. Evento al cambiar de Cliente
    selectorCliente.addEventListener('change', function() {
        const clienteId = this.value;
        const opcionSeleccionada = this.options[this.selectedIndex];
        
        document.getElementById('nombre_cliente').value = opcionSeleccionada.getAttribute('data-nombre') || '';
        
        const inputDireccion = document.getElementById('direccion');
        inputDireccion.value = opcionSeleccionada.getAttribute('data-direccion') || '';
        if(typeof mdb !== 'undefined' && mdb.Input) {
            new mdb.Input(inputDireccion.parentNode).init();
        }
        
        // Habilitar contador y limpiar 
        selectorContador.removeAttribute('disabled');
        selectorContador.value = "";
        limpiarCamposPago();
        
        // Filtrar contadores
        const opcionesContador = selectorContador.querySelectorAll('option');
        opcionesContador.forEach(option => {
            if (option.value === "") {
                option.style.display = "block";
                return;
            }
            if (option.getAttribute('data-cliente-id') === clienteId) {
                option.style.display = "block";
            } else {
                option.style.display = "none";
            }
        });
    });

    // 2. Evento al cambiar de Contador
    selectorContador.addEventListener('change', function() {
        if(this.value === "") {
            limpiarCamposPago();
            return;
        }

        const opcionSeleccionada = this.options[this.selectedIndex];
        const consumo = parseFloat(opcionSeleccionada.getAttribute('data-consumo'));
        const monto = parseFloat(opcionSeleccionada.getAttribute('data-monto'));

        if (consumo > 0 || monto > 0) {
            displayConsumo.value = consumo + " Litros";
            inputConsumo.value = consumo;
            
            displayMonto.value = "Q. " + monto.toFixed(2);
            inputMonto.value = monto;
            
            btnEmitir.disabled = false;
        } else {
            // Si el contador no tiene lecturas pendientes
            displayConsumo.value = "Sin lectura pendiente";
            inputConsumo.value = "0";
            
            displayMonto.value = "Q. 0.00";
            inputMonto.value = "0";
            
            // Opcional: Bloquear botón si no hay nada que cobrar
            btnEmitir.disabled = true;
        }
    });
});
</script>
<?= $this->endSection() ?>