<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class videoModelosDB {

    //video modelo por id
    public function videoModeloPorIdDB($id){
        $ds = new DataServices();
        $param[0] = $id;
        
        $result = $ds->Execute("videoModeloPorIdDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    //Obtener coleccion de videos
    public function ObtVideosModeloDB($activo, $gModeloId){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();

        if($activo!=""){
            $query[] = " a.activo=$activo ";
        }
        if($gModeloId!=""){
            $query[] = " a.gModeloId=$gModeloId ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);          
          $param[0] = $wordWhere.$setWhere;
        }        
        
        $result = $ds->Execute("ObtVideosModeloDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function InsertVideoModeloDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("InsertVideoModeloDB", $param, true);
        $ds->CloseConnection();
        return $result;
    }

    public function ActVideoModeloDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("ActVideoModeloDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function EliminarVideoModeloDB($param){
        $ds = new DataServices();

        $result = $ds->Execute("EliminarVideoModeloDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    
     public function ActCampoVideoModeloDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("ActCampoVideoModeloDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
        
        
}