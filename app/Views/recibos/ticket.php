<?php
$mesesEs = [1=>'Ene',2=>'Feb',3=>'Mar',4=>'Abr',5=>'May',6=>'Jun',7=>'Jul',8=>'Ago',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dic'];
function periodoEs(string $fecha, array $meses): string
{
    $ts = strtotime($fecha);
    return $meses[(int) date('n', $ts)] . ' ' . date('Y', $ts);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recibo — <?= esc_nativo($contador['cliente_nombre']) ?></title>
<style>
  * { box-sizing: border-box; }
  body {
    font-family: Arial, Helvetica, sans-serif;
    color: #1a1a1a;
    background: #f2f2f2;
    margin: 0;
    padding: 24px 0;
  }
  .hoja {
    max-width: 640px;
    margin: 0 auto;
    background: #ffffff;
    padding: 40px 44px;
    border: 1px solid #ddd;
  }
  .encabezado {
    text-align: center;
    border-bottom: 2px solid #1a1a1a;
    padding-bottom: 16px;
    margin-bottom: 24px;
  }
  .encabezado .marca {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 22px;
    font-weight: bold;
    letter-spacing: 0.3px;
  }
  .encabezado .subtitulo {
    font-size: 13px;
    color: #555;
    margin-top: 4px;
  }
  .datos {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4px 24px;
    font-size: 13px;
    margin-bottom: 24px;
  }
  .datos .etiqueta { color: #666; }
  .datos .valor { font-weight: bold; }
  table.lecturas {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
    margin-bottom: 20px;
  }
  table.lecturas thead tr { border-bottom: 1px solid #1a1a1a; }
  table.lecturas th {
    text-align: left;
    padding: 6px 4px;
    font-weight: bold;
  }
  table.lecturas th.num, table.lecturas td.num { text-align: right; }
  table.lecturas tbody tr { border-bottom: 1px solid #e0e0e0; }
  table.lecturas td {
    padding: 7px 4px;
    font-family: 'Courier New', Courier, monospace;
  }
  .total {
    display: flex;
    justify-content: flex-end;
    border-top: 2px solid #1a1a1a;
    padding-top: 10px;
    margin-bottom: 28px;
  }
  .total .caja { text-align: right; }
  .total .etiqueta { font-size: 12px; color: #666; }
  .total .monto {
    font-family: 'Courier New', Courier, monospace;
    font-size: 26px;
    font-weight: bold;
  }
  .pie {
    text-align: center;
    font-size: 11px;
    color: #888;
    border-top: 1px solid #e0e0e0;
    padding-top: 10px;
  }
  .acciones {
    max-width: 640px;
    margin: 16px auto 0;
    text-align: center;
  }
  .acciones button {
    font-size: 14px;
    padding: 8px 20px;
    cursor: pointer;
  }
  @media print {
    body { background: #fff; padding: 0; }
    .hoja { border: none; max-width: none; padding: 0; }
    .acciones { display: none; }
  }
</style>
</head>
<body>

  <div class="hoja">
    <div class="encabezado">
      <div class="marca">Oficina del Agua</div>
      <div class="subtitulo">Recibo de consumo pendiente de pago</div>
    </div>

    <div class="datos">
      <div><div class="etiqueta">Cliente</div><div class="valor"><?= esc_nativo($contador['cliente_nombre']) ?></div></div>
      <div><div class="etiqueta">Contador</div><div class="valor"><?= esc_nativo($contador['codigo_fisico']) ?></div></div>
      <div><div class="etiqueta">Direccion</div><div class="valor"><?= esc_nativo($contador['direccion_principal']) ?></div></div>
      <div><div class="etiqueta">Sector / servicio</div><div class="valor"><?= esc_nativo($contador['sector_nombre']) ?> &middot; <?= esc_nativo($contador['tipo_nombre']) ?></div></div>
      <div><div class="etiqueta">Fecha de emision</div><div class="valor"><?= esc_nativo(date('d/m/Y H:i', strtotime($fechaEmision))) ?></div></div>
    </div>

    <table class="lecturas">
      <thead>
        <tr>
          <th>Recibo</th>
          <th>Periodo</th>
          <th class="num">Anterior</th>
          <th class="num">Actual</th>
          <th class="num">Consumo</th>
          <th class="num">Monto</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($lecturas as $l) : ?>
          <tr>
            <td><?= esc_nativo($l['numero_recibo']) ?></td>
            <td style="font-family: Arial, sans-serif;"><?= esc_nativo(periodoEs($l['fecha'], $mesesEs)) ?></td>
            <td class="num"><?= number_format((float) $l['lectura_anterior'], 0) ?></td>
            <td class="num"><?= number_format((float) $l['lectura_actual'], 0) ?></td>
            <td class="num"><?= number_format((float) $l['consumo_litros'], 0) ?> L</td>
            <td class="num">Q<?= number_format((float) $l['monto_base'] + (float) $l['monto_exceso'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="total">
      <div class="caja">
        <div class="etiqueta">Total pendiente (<?= count($lecturas) ?> recibo<?= count($lecturas) === 1 ? '' : 's' ?>)</div>
        <div class="monto">Q<?= number_format($totalPendiente, 2) ?></div>
      </div>
    </div>

    <div class="pie">
      Documento generado por el sistema &middot; no requiere firma
    </div>
  </div>

  <div class="acciones">
    <button onclick="window.print()">Imprimir</button>
  </div>

</body>
</html>