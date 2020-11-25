<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class galeriaModelosDB {

    //galeria modelo por id
    public function galeriaModeloPorIdDB($id){
        $ds = new DataServices();
        $param[0] = $id;
        
        $result = $ds->Execute("galeriaModeloPorIdDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    //Obtener coleccion de galerias
    public function ObtGaleriasModeloDB($activo, $gModeloId){
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
        
        $result = $ds->Execute("ObtGaleriasModeloDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function InsertGaleriaModeloDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("InsertGaleriaModeloDB", $param, true);
        $ds->CloseConnection();
        return $result;
    }

    public function ActGaleriaModeloDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("ActGaleriaModeloDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function EliminarGaleriaModeloDB($param){
        $ds = new DataServices();

        $result = $ds->Execute("EliminarGaleriaModeloDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    
     public function ActCampoGaleriaModeloDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("ActCampoGaleriaModeloDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    





    public function versionZonaActivaPorZonaDB($zonaVersId){
        $ds = new DataServices();
        $param[0] = $zonaVersId;        
        
        $result = $ds->Execute("versionZonaActivaPorZonaDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function ActCampoVersionZonaADB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("ActCampoVersionZonaADB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    

    public function ActVersionZonaActivaDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("ActVersionZonaActivaDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    

        
}