<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class inventariosDB {
    
    //Obtener coleccion 
    public function ObtInventarioDB($rfc, $dealer, $vin, $modelo, $anio, $version, $color, $clv_vehicular, $condicion, $disponibilidad, $agencia){
        $ds = new DataServices();
        $param[0] = "";        
        $query = array();

        if($rfc != ""){
            $query[] = " rfc='$rfc' ";
        }
        if($dealer != ""){
            $query[] = " dealer='$dealer' ";
        }
        if($vin != ""){
            $query[] = " vin='$vin' ";
        }
        if($modelo != ""){
            $query[] = ' modelo LIKE "%'.$modelo.'%" ';
        }
        if($anio != ""){
            $query[] = " anio='$anio' ";
        }
        if($version != ""){
            $query[] = ' version LIKE "%'.$version.'%" ';
        }
        if($color != ""){
            $query[] = ' color LIKE "%'.$color.'%" ';
        }
        if($clv_vehicular != ""){
            $query[] = " clv_vehicular='$clv_vehicular' ";
        }
        if($condicion != ""){
            $query[] = " condicion_uso='$condicion' ";
        }
        if($disponibilidad != ""){
            $query[] = " disponibilidad='$disponibilidad' ";
        }
        if($agencia != ""){
            $query[] = " agencia='$agencia' ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }
        // $param[1] = " ORDER BY nombre ASC ";
        // print_r($param);
        
        $result = $ds->Execute("ObtInventarioDB", $param);
        $ds->CloseConnection();
        return $result;
    }
    
    // Implementado el 26/02/20
    //Limpiar tabla 
    public function LimpiarInventarioDB(){
        $ds = new DataServices();
        $param = NULL;

        $result = $ds->Execute("LimpiarInventarioDB", $param, true);
        $ds->CloseConnection();

        return $result;
    }

    // Implementado el 26/02/20
    //Insertar 
    public function InsertarInventarioDB($param){
        $ds = new DataServices();

        $result = $ds->Execute("InsertarInventarioDB", $param, true);
        $ds->CloseConnection();
        return $result;
    }  

    //Obtener agencias 
    public function ObtAgenciasDB(){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();
    
        $query[] = " agencia!='' ";

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere ." GROUP BY agencia ASC ";
        }        
        // print_r($param);
        
        $result = $ds->Execute("ObtInventarioDB", $param);
        $ds->CloseConnection();
        return $result;
    }  


    /*//Obtener agencia por id
    public function AgenciaPorIdDB($id){
        $ds = new DataServices();
        $param[0]= $id;
        $result = $ds->Execute("AgenciaPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }
        
    
    //actualizar 
    public function ActAgenciaDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("ActAgenciaDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }  */  

}