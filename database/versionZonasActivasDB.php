<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class versionZonasActivasDB {

    //version zona activa por id
    public function versionZonaActivaPorIdDB($id){
        $ds = new DataServices();
        $param[0] = $id;        
        
        $result = $ds->Execute("versionZonaActivaPorIdDB", $param);
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

    public function InsertVersionZonaActivaDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("InsertVersionZonaActivaDB", $param, true);
        $ds->CloseConnection();
        return $result;
    }

    public function ActVersionZonaActivaDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("ActVersionZonaActivaDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function EliminarVersionZonaActivaDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("EliminarVersionZonaActivaDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    //Obtener coleccion de versiones zonas activas
    public function ObtVersionesZonasActivasDB($zonaVersId){
        $ds = new DataServices();
        $param[0] = "";

         $query = array();

        if($zonaVersId != "")
        {
            $query[] = " zonaVersId=$zonaVersId ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }
        
        $result = $ds->Execute("ObtVersionesZonasActivasDB", $param);
        $ds->CloseConnection();
        return $result;
    }
    
    //obtener ids de zonas activas por el id de zonas
    public function idsZonasActivasVersPorIdsZonaDB($zonaVersId){
        $ds = new DataServices();
        $param[0] = $zonaVersId;        
        
        $result = $ds->Execute("idsZonasActivasVersPorIdsZonaDB", $param);
        $ds->CloseConnection();
        return $result;
    }
}