<?php
require_once 'class/factura.php';

$token = $_POST['token'];
$action = $_POST['action'];

if(!empty($token) && $token == 'D1ktghOZ5MHRD1tff1N3'){
    
    if($action == 'borrarArchivo'){
        $id = $_POST['id'];
        if(!empty($id)){

            $factura = new Factura();

            if($result = $factura->deleteFactura($id) === true){
                $respuesta = [
                    "status" => "success",
                    "data" => $id,
                ];
            } else{
                $respuesta = [
                    "status" => "error",
                    "data" => $result
                ];
            }
    
            print json_encode($respuesta);
        }
    }

    if($action == 'loadHTMLFacturas'){

        $factura = new Factura();

        $sql = "SELECT * FROM facturas order by `fecha_creacion` DESC";

        $resql = $factura->db->fetch_all($sql);

        if($resql){
            $facturas = array();
            foreach($resql as $singleFactura){
                
                $facturas[] = [
                    "id" => $singleFactura['id'],
                    "cliente" => $singleFactura['cliente'],
                    "clienteID" => $singleFactura['clienteID'],
                    "tipoCliente" => $singleFactura['tipoCliente'],
                    "tipoID" => $singleFactura['tipoID'],
                    "fecha" => $singleFactura['fecha'],
                    "numeracion" => $singleFactura['numeracion'],
                    "total" => $singleFactura['total'],
                ];
            }
    
            $html = '';
    
            $html .= '<table class="table table-striped table-default">
                <tr class="table-primary">
                    <th>Nº de Factura</th>
                    <th>Cliente</th>
                    <th>Fecha de Factura</th>
                    <th>Importe Total</th>
                    <th class="text-center">Acciones</th>
                </tr>';
                
                foreach($facturas as $singleFactura){
                    $html .= '
                    <tr>
                        <td class="align-middle">'.$singleFactura['numeracion'].'<br><small>'.ucfirst($singleFactura['tipoCliente']).'</small></td>
                        <td class="align-middle">'.$singleFactura['cliente'].'<br><small>'.$singleFactura['tipoID'].' - '.$singleFactura['clienteID'].'</small></td>
                        <td class="align-middle">'.date('d/m/Y',strtotime($singleFactura['fecha'])).'</td>
                        <td class="align-middle">'.str_replace('.', ',', $singleFactura['total']).' €</td>
                        <td class="text-center">
                            <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-eye" style="color: white;"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                <a class="dropdown-item" target="_blank" href="'."/facturas/".$singleFactura["id"].'.pdf">Ver</a>
                                </li>
                                <li><a class="dropdown-item descargar" href="javascript:void(0);" data-nombre="'.str_replace(' ', '', $singleFactura['cliente']).'_'.$singleFactura['fecha'].'.pdf" data-id="'.$singleFactura["id"].'">Descargar</a></li>
                            </ul>
                            </div>
                            <button type="button" class="btn btn-danger eliminar" data-id="'.$singleFactura["id"].'"><i class="fa-solid fa-trash" style="color: white;"></i></button>
                        </td>
                    </tr>';
                }
    
                $html .= '</table>';
    
                $respuesta = [
                    "status" => "success",
                    "data" => $html
                ];
    
                print json_encode($respuesta);
        } else{
            $respuesta = [
                "status" => "error",
                "data" => "No hay facturas aún"
            ];

            print json_encode($respuesta);
        }

    }

    if($action == 'getLastNumeracion'){
        $tipoCliente = $_POST['tipoCliente'];
        if(!empty($tipoCliente)){

            $factura = new Factura();

            $numeracion = $factura->getLastNumeracion($tipoCliente);
            
            $respuesta = [
                "status" => "success",
                "data" => json_encode($numeracion),
            ];

        } else{
            $respuesta = [
                "status" => "error",
                "data" => "Tipo de cliente no indicado."
            ];
        }

        print json_encode($respuesta);
    }

} else{

    $respuesta = [
        "status" => "error",
        "data" => "Token incorrecto"
    ];

    print json_encode($respuesta);

}

?>