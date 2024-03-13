<?php

require("db.php");

class Factura{

    public $db;
    protected $nombreCliente;
    protected $dniCliente;
    protected $tipoID;
    protected $direccion;
    protected $urbanizacionCliente;
    protected $codigo_postal;
    protected $localidad;
    protected $numeroFactura;
    protected $fechaFactura;
    protected $ivaFactura;
    protected $almacenar;
    protected $tipoCliente;
    protected $base;
    protected $importeIva;
    protected $total;
    protected $filas = array();

    public function __construct()
    {
        $this->db = new db(
            _HOSTNAME_,
            _DB_USER_,
            _DB_PASS_,
            _DATABASE_,
            _DB_PORT_
        );
    }

    public function assignData(){
        $this->nombreCliente = $_POST['nombreCliente'];
        $this->dniCliente = $_POST['dniCliente'];
        $this->direccion = $_POST['direccion'];
        $this->tipoCliente = $_POST['tipoCliente'];
        $this->urbanizacionCliente = $_POST['urbanizacionCliente'];
        $this->codigo_postal = $_POST['codigo_postal'];
        $this->localidad = $_POST['localidad'];
        $this->numeroFactura = $_POST['numeroFactura'];
        $this->fechaFactura = $_POST['fechaFactura'];
        $this->ivaFactura = $_POST['ivaFactura'];
        $this->filas[0] = $_POST['conceptoFactura'];
        $this->filas[1] = $_POST['importeFactura'];
        $this->almacenar = isset($_POST['almacenar']) ? true : false;

        if($_POST['tipoID'] == 1){
            $this->tipoID = "DNI";
        } else if($_POST['tipoID'] == 2){
            $this->tipoID = "CIF";
        } else{
            $this->tipoID = null;
        }
    }

    public function assignCalcData($base, $iva, $total){
        $this->base = $base;
        $this->importeIva = $iva;
        $this->total = $total;
    }

    public function getDatos(){
        $data = array(
            "nombre" => $this->nombreCliente,
            "dni" => $this->dniCliente,
            "tipoID" => $this->tipoID,
            "direccion" => $this->direccion,
            "urbanizacion" => $this->urbanizacionCliente,
            "codigo_postal" => $this->codigo_postal,
            "localidad" => $this->localidad,
            "numeroFactura" => $this->numeroFactura,
            "fecha" => $this->fechaFactura,
            "iva" => $this->ivaFactura,
            "conceptos" => $this->filas[0],
            "importes" => $this->filas[1],
            "tipoCliente" => $this->tipoCliente,
            "almacenar" => $this->almacenar,
        );
        
        return $data;
    }

    private function prepareInsert(){
        $fields = array(
            "cliente" => $this->nombreCliente,
            "clienteID" => $this->dniCliente,
            "tipoID" => $this->tipoID,
            "direccion" => $this->direccion,
            "urbanizacion" => $this->urbanizacionCliente,
            "codigo_postal" => $this->codigo_postal,
            "localidad" => $this->localidad,
            "numeracion" => $this->numeroFactura,
            "fecha" => $this->fechaFactura,
            "iva" => $this->ivaFactura,
            "conceptos" => json_encode($this->filas[0]),
            "importes" => json_encode($this->filas[1]),
            "tipoCliente" => $this->tipoCliente,
            "base" => $this->base,
            "importeIva" => $this->importeIva,
            "total" => $this->total
        );

        return $fields;
    }

    private function construirInsert($fields){
        $insertSQL = "
        INSERT INTO `facturas` (
        ";
        
        $count = 0;
        foreach($fields as $index=>$field){
            if($count==0){
                $insertSQL .= "`".$index."`";
            } else{
                $insertSQL .= ",`".$index."`";
            }
            $count++;
        }

        $insertSQL .= ") VALUES (";

        $count = 0;
        foreach($fields as $field){
            if($count==0){
                $insertSQL .= "'".$field."'";
            } else{
                $insertSQL .= ",'".$field."'";
            }
            $count++;
        }

        $insertSQL .= ")";

        return $insertSQL;
    }

    public function insertFactura(){
        $fields = $this->prepareInsert();
        $sql = $this->construirInsert($fields);

        if($this->db->query($sql) === true){
            return $this->db->insert_id;
        } else{
            print $this->db->get_error();
            die();
        }
    }

    public function getLastNumeracion($tipoCliente){
        $sql = "SELECT numeracion FROM facturas WHERE `tipoCliente` = '".$tipoCliente."' order by `numeracion` DESC LIMIT 1";

        $resql = $this->db->fetch_assoc($sql);

        if($resql){
            return $resql["numeracion"];
        } else{
            return 0;
        }
    }

    public function deleteFactura($id){
        if($this->deletePDF($id)){
            if($this->deleteDBFactura($id)){
                return true;
            } else{
                return "El indice en la base de datos no existe.";
            }
        } else{
            return "El archivo no existe.";
        }
    }

    private function deleteDBFactura($id){
        $sql = "DELETE FROM facturas WHERE `id` = '".$id."'";

        $this->db->query($sql);

        if($this->db->affected_rows > 0){
            return true;
        } else{
            return false;
        }
    }

    private function deletePDF($id){

        $path = dirname(__FILE__).'/../facturas/'.$id.'.pdf';

        if(file_exists($path)){

            unlink($path);
            return true;

        } else{
            return false;
        }
    }

}