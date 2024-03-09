<?php

require_once 'dompdf/autoload.inc.php';
require_once 'class/factura.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('chroot', $_SERVER['HTTP_HOST']);
$options->set('isRemoteEnabled', TRUE);

$factura = new Factura();
$factura->assignData();
$data = $factura->getDatos();

$html =
'
<style>
    body{
        background: url("https://facturas.cavellan.es/background.png");
        background-size: cover;
    }
    *{margin:0;padding:0}
    #margin-top{
        width: 100%;
        height: 200px;
    }
    #cliente{
        border: solid 1px;
        margin-left: 60px;
        width: 300px;
        text-align: center;
        float: left;
        padding: 10px;
        margin-top: 10px;
    }
    .factura{
        color: blue;
        font-size: 25px;
        font-weight: bold;
        width: 100%;
        text-align:center;
        font-family: sans-serif;
    }
    .numero_factura{
        border: solid 1px;
        float: left;
        margin-left: 50px;
        margin-top: 29px;
        padding: 5px;
        text-align:center;
    }
    .fecha_factura{
        border: solid 1px;
        float: right;
        margin-top: 29px;
        margin-right: 50px;
        padding: 5px;
        text-align:center;
    }
    table.container{
        border: solid 1px black;
        width: 89%;
        margin-left: 50px;
        margin-top: 100px;
        height: 550px;
        padding: 10px;
        position: relative;
    }
    .concepto{
        width: 70%;
        padding: 5px;
        border-bottom: dotted 1px gray;
    }
    .importe{
        width: 20%;
        text-align: right;
        border-left: 1px solid gray;
        padding: 5px;
        border-bottom: dotted 1px gray;
        padding-right: 30px;
    }
    .tconcepto, .timporte{
        padding: 10px;
        border-bottom: solid 1px;
    }
    table.total{
        border: solid 1px black;
        float: right;
        margin-right: 38px;
        border-collapse: collapse;
    }
    .n_cuenta{
        width: 100%;
        text-align: center;
        font-size: 10px;
        position: absolute;
        bottom: 10px;
    }
    .total-izq{
        width: 200px;
        padding: 10px;
    }
    .total-der{
        width: 10%;
        padding: 10px;
    }
    table.total td{
        border-bottom: solid 1px;
    }
    .foot{
        color: #000475;
        font-family: sans-serif;
        text-align: center;
        width: 1100px;
    }
    .info{
        position: absolute;
        bottom: 43px;
        font-size: 13px;
        left: 20px;
    }
    small{
        font-size: 5px;
        position: absolute;
        bottom: 10px;
        left: 75px;
        width: 980px !important;
        
    }
    .lateral{
        color: #000475;
        -webkit-transform: rotate(-90deg); 
        -moz-transform: rotate(-90deg);
        position: absolute;
        width: 25px;
        height: auto;
        white-space: nowrap;
        font-size: 10px;
    }
</style>
<body>
    <div id="margin-top"></div>
    <div id="cliente">
        <p class="bb">'. $data["nombre"] .'</p>
        <p class="bb">'. $data["direccion"] .'</p>
        <p class="bb">'.$data['urbanizacion'].'</p>
        <p class="bb">'.$data['codigo_postal'].' - '.$data['localidad'].'</p>
        <p>'.$data['tipoID'].' - '.$data['dni'].'</p>
    </div>
    <h1 class="factura">FACTURA</h1>
    <div class="numero_factura">
        <span style="border-bottom: solid 1px;"><strong>FACTURA Nº</strong></span><br>
        <span>'.$data['numeroFactura'].'</span>
    </div>
    <div class="fecha_factura">
        <span style="border-bottom: solid 1px;"><strong>FECHA FACTURA</strong></span><br>
        <span>'.$data['fecha'].'</span>
    </div>
    <table class="container">
        <thead>
            <tr>
                <th class="tconcepto"><strong>Concepto</strong></th>
                <th class="timporte"><strong>Importe (EUROS)</strong></th>
            </tr>
        </thead>
        <tbody>
';

foreach($data['conceptos'] as $key => $concepto){
    $html .= '
    <tr>
        <td class="concepto">'.$concepto.'</td>
        <td class="importe">'.number_format($data['importes'][$key], 2, ',', '.').' €</td>
    </tr>
    ';
}

$base = 0;

foreach($data['importes'] as $importe){
    $base += $importe;
}

$base = number_format($base, 2, ',', '.');
$iva = ($data['iva']/100) * $base;
$iva = number_format($iva, 2, ',', '.');
$total = $base+$iva;
$total = number_format($total, 2, ',', '.');

$html .= '<tbody>
        <div class="n_cuenta">SABADELL NºCUENTA: ES60 0049 1500 0512 3456 7892</div>
        </table>
        <table class="total">
            <tr class="tr-total">
                <td></td>
                <td class="total-izq"><strong>BASE IMPONIBLE</strong></td>
                <td class="total-der">'.$base.' €<td>
            </tr>
            <tr class="tr-total">
                <td></td>
                <td class="total-izq"><strong>I.V.A. '.$data['iva'].'%</strong></td>
                <td class="total-der">'.$iva.' €<td>
            </tr>
            <tr>
                <td></td>
                <td class="total-izq"><strong>TOTAL FACTURA</strong></td>
                <td class="total-der">'.$total.' €<td>
            </tr>
        </table>
        <p class="foot info">C/ Calle de ejemplo de la empresa, 3 - Telf. y Fax 965 11 22 33 - empresa@dominio.com - 03526 MADRID</p>
        <small class="foot">A los efectos previstos en la Ley Orgánica 15/1999, de 13 de diciembre, sobre Protección de Datos de Carácter Personal se le informa que los datos personales proporcionados se incorporarán (o actualzarán) a los ficheros de EMPRESA S.L. con dirección Calle de ejemplo de la empresa, 3 de Madrid. La finalidad del tratamiento de los datos será la de gestionar la facturación de la entidad. Los datos personales solicitados en este documento son decarácter obligatorio, por la que su no cumplimentación supone la imposibilidad de su inclusión en los ficheros antes descritos y de cumplir con la finalidad definida en el párrafo anterior. Ud. tiene derecho al acceso, rectificación, cancelacióny oposición en los términos previstos en la Ley que podrá ejercitar mediante escrito dirigido al responsable de los mismos en la dirección anteriormente indicada.
        </small>
        <p class="lateral">Registro Mercantil de Madrid, Tomo 2.394, Libro 8, de la Sección 2ª, Hoja B-24267, Inscripción 1ª CIF B-25791259 - EMPRESA S.L.</p>
        </body>';

$pdf = new Dompdf($options);
$pdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$pdf->setPaper('A4');

// Render the HTML as PDF
$pdf->render();

// Output del PDF para almacenarlo
$facturaRender = $pdf->output();

if($data["almacenar"]){

    $factura->assignCalcData($base, $iva, $total);
    $id = $factura->insertFactura();

    file_put_contents("facturas/".$id.".pdf", $facturaRender);

    header('Location: list.php');
    die();

} else{
    // Output the generated PDF to Browser
    $pdf->stream('factura',[ "Attachment" => false]);
}
