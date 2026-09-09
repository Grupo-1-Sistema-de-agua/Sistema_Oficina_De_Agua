<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo No. <?= esc($recibo['numero_recibo']) ?></title>
    <style>
        /* Optimizado para impresora térmica de 80mm (ancho efectivo de impresión 72mm) */
        @page {
            margin: 0;
            size: 80mm auto;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.25;
            width: 72mm;
            margin: 0 auto;
            padding: 10px 0;
            color: #000;
            background-color: #fff;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th, td {
            padding: 3px 0;
            vertical-align: top;
        }
        .total-container {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 6px 0;
            margin-top: 8px;
            font-size: 13px;
            font-weight: bold;
        }
        .btn-imprimir {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            margin-bottom: 15px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                width: 100%;
                padding: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="text-center no-print">
        <button class="btn-imprimir" onclick="window.print()">Imprimir Recibo</button>
    </div>

    <div class="text-center">
        <span class="fw-bold" style="font-size: 15px;">OFICINA DEL AGUA</span><br>
        SISTEMA DE GESTIÓN DE AGUA (VERSIÓN BETA)<br>
        DEPARTAMENTO DE COBROS
    </div>

    <div class="divider"></div>

    <div>
        <strong>Recibo No:</strong> <?= esc($recibo['numero_recibo']) ?><br>
        <strong>Fecha Emisión:</strong> <?= date('d/m/Y', strtotime($recibo['fecha_emision'])) ?><br>
        <strong>N° Contador:</strong> <?= esc($recibo['numero_contador'] ?? 'SIN CONTADOR') ?>
    </div>

    <div class="divider"></div>

    <div>
        <strong>Contribuyente:</strong><br>
        <?= esc($recibo['nombre_cliente']) ?><br>
        <strong>Dirección:</strong><br>
        <?= esc($recibo['direccion']) ?>
    </div>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Canon / Consumo de Agua Potable</td>
                <td class="text-right">Q. <?= number_format($recibo['monto_total'], 2) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="total-container text-right">
        TOTAL: Q. <?= number_format($recibo['monto_total'], 2) ?>
    </div>

    <div class="divider"></div>

    <div class="text-center" style="font-size: 10px; margin-top: 8px;">
        Generado por: SISTEMA_OFICINA_AGUA<br>[cite: 1]
        Firma Electrónica: 7B8F9A2C1D<br>[cite: 1]
        ¡Gracias por su pago puntual!
    </div>

</body>
</html>