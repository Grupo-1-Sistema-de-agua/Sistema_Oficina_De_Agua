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
  * {
    box-sizing: border-box;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
  body {
    font-family: Arial, Helvetica, sans-serif;
    color: #1a1a18;
    background: #eef1f0;
    margin: 0;
    padding: 24px 0;
  }
  .hoja {
    max-width: 620px;
    margin: 0 auto;
    background: #fdfcf9;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid #e2e2de;
  }
  .encabezado {
    background: linear-gradient(135deg, #0f2942 0%, #123a52 100%);
    padding: 26px 32px 24px;
    position: relative;
    overflow: hidden;
  }
  .encabezado .gota-marca {
    position: absolute;
    top: -20px;
    right: -10px;
    width: 140px;
    height: 140px;
    opacity: 0.12;
  }
  .encabezado .ola {
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 30px;
  }
  .marca-fila {
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
  }
  .marca-fila .titulo {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 25px;
    font-weight: bold;
    color: #ffffff;
    line-height: 1.1;
  }
  .marca-fila .subtitulo {
    font-size: 13px;
    color: #cfe9ef;
    margin-top: 2px;
    font-weight: bold;
  }
  .cuerpo { padding: 28px 32px 8px; }
  .datos-fila {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
    gap: 16px;
  }
  .datos {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4px 28px;
    font-size: 13px;
  }
  .datos .etiqueta { color: #6b6b68; font-weight: bold; }
  .datos .valor { color: #1a1a18; font-weight: bold; font-size: 14px; }
  .badge-estado {
    background: #fdecc8;
    color: #7a4f08;
    font-size: 11.5px;
    font-weight: bold;
    padding: 5px 12px;
    border-radius: 20px;
    white-space: nowrap;
  }
  table.lecturas {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
    margin-bottom: 4px;
  }
  table.lecturas thead tr { border-bottom: 2px solid #123a52; }
  table.lecturas th {
    text-align: left;
    padding: 7px 4px;
    font-weight: bold;
    color: #123a52;
  }
  table.lecturas th.num, table.lecturas td.num { text-align: right; }
  table.lecturas tbody tr { border-bottom: 0.5px solid #e5e5e1; }
  table.lecturas td {
    padding: 8px 4px;
    font-family: 'Courier New', Courier, monospace;
    color: #1a1a18;
  }
  table.lecturas td.periodo { font-family: Arial, sans-serif; }
  .talon {
    position: relative;
    margin: 6px 0;
  }
  .talon .linea {
    border-top: 2px dashed #b7c4c9;
    margin: 0 32px;
  }
  .talon .corte {
    position: absolute;
    top: -10px;
    width: 20px;
    height: 20px;
    background: #eef1f0;
    border-radius: 50%;
  }
  .talon .corte.izq { left: -10px; }
  .talon .corte.der { right: -10px; }
  .total-fila {
    padding: 18px 32px 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .total-fila .etiqueta { font-size: 12.5px; color: #4a4a47; font-weight: bold; }
  .total-fila .sub { font-size: 11.5px; color: #7a7a76; }
  .total-fila .monto {
    font-family: 'Courier New', Courier, monospace;
    font-size: 32px;
    font-weight: bold;
    color: #7a4f08;
  }
  .pie {
    text-align: center;
    font-size: 11.5px;
    color: #7a7a76;
    padding: 18px 10px 22px;
  }
  .acciones {
    max-width: 620px;
    margin: 16px auto 0;
    text-align: center;
  }
  .acciones button {
    font-size: 14px;
    padding: 8px 20px;
    cursor: pointer;
    border-radius: 8px;
    border: 1px solid #123a52;
    background: #123a52;
    color: #fff;
  }
  @media print {
    body { background: #fff; padding: 0; }
    .hoja { border: none; max-width: none; border-radius: 0; }
    .acciones { display: none; }
  }
</style>
</head>
<body>

  <div class="hoja">
    <div class="encabezado">
      <svg class="gota-marca" viewBox="0 0 100 100" aria-hidden="true">
        <path d="M50 8 C50 8 22 42 22 62 C22 79 34 92 50 92 C66 92 78 79 78 62 C78 42 50 8 50 8 Z" fill="#ffffff"></path>
      </svg>
      <svg class="ola" viewBox="0 0 600 30" preserveAspectRatio="none">
        <path d="M0,16 C100,30 200,0 300,16 C400,30 500,0 600,16 L600,30 L0,30 Z" fill="#fdfcf9"></path>
      </svg>
      <div class="marca-fila">
        <svg width="34" height="34" viewBox="0 0 100 100" aria-hidden="true">
          <path d="M50 6 C50 6 18 46 18 68 C18 87 32 98 50 98 C68 98 82 87 82 68 C82 46 50 6 50 6 Z" fill="#7fd8e3"></path>
          <ellipse cx="40" cy="66" rx="7" ry="11" fill="#ffffff" opacity="0.55"></ellipse>
        </svg>
        <div>
          <div class="titulo">Oficina del Agua</div>
          <div class="subtitulo">Recibo de consumo pendiente de pago</div>
        </div>
      </div>
    </div>

    <div class="cuerpo">
      <div class="datos-fila">
        <div class="datos">
          <div><div class="etiqueta">Cliente</div><div class="valor"><?= esc_nativo($contador['cliente_nombre']) ?></div></div>
          <div><div class="etiqueta">Contador</div><div class="valor"><?= esc_nativo($contador['codigo_fisico']) ?></div></div>
          <div><div class="etiqueta">Direccion</div><div class="valor"><?= esc_nativo($contador['direccion_principal']) ?></div></div>
          <div><div class="etiqueta">Sector / servicio</div><div class="valor"><?= esc_nativo($contador['sector_nombre']) ?> &middot; <?= esc_nativo($contador['tipo_nombre']) ?></div></div>
        </div>
        <span class="badge-estado">Pendiente</span>
      </div>

      <table class="lecturas">
        <thead>
          <tr>
            <th>Recibo</th>
            <th>Periodo</th>
            <th class="num">Consumo</th>
            <th class="num">Monto</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($lecturas as $l) : ?>
            <tr>
              <td><?= esc_nativo($l['numero_recibo']) ?></td>
              <td class="periodo"><?= esc_nativo(periodoEs($l['fecha'], $mesesEs)) ?></td>
              <td class="num"><?= number_format((float) $l['consumo_litros'], 0) ?> L</td>
              <td class="num"><?= number_format((float) $l['monto_base'] + (float) $l['monto_exceso'], 2) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="talon">
      <div class="linea"></div>
      <div class="corte izq"></div>
      <div class="corte der"></div>
    </div>

    <div class="total-fila">
      <div>
        <div class="etiqueta">Total pendiente</div>
        <div class="sub"><?= count($lecturas) ?> recibo<?= count($lecturas) === 1 ? '' : 's' ?></div>
      </div>
      <div class="monto">Q<?= number_format($totalPendiente, 2) ?></div>
    </div>

    <div class="pie">
      Fecha de emision: <?= esc_nativo(date('d/m/Y H:i', strtotime($fechaEmision))) ?> &middot; documento generado por el sistema, no requiere firma
    </div>
  </div>

  <div class="acciones">
    <button onclick="window.print()">Imprimir</button>
  </div>

</body>
</html>