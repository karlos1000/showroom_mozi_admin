<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class versionZonasDB {

    //version zona por id
    public function versionZonaPorIdDB($id){
        $ds = new DataServices();
        $param[0] = $id;        
        
        $result = $ds->Execute("versionZonaPorIdDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function versionZonaPorColorGaleriaDB($coloresVersId, $galeriaId){
        $ds = new DataServices();
        $param[0] = $coloresVersId;        
        $param[1] = $galeriaId;    
        
        $result = $ds->Execute("versionZonaPorColorGaleriaDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    //Obtener coleccion de versiones zonas
    public function ObtVersionesZonasDB($coloresVersId){
        $ds = new DataServices();
        $param[0] = "";

         $query = array();

        if($coloresVersId != "")
        {
            $query[] = " a.coloresVersId IN ($coloresVersId) ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }
        
        $result = $ds->Execute("ObtVersionesZonasDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function ActCampoVersionZonaDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("ActCampoVersionZonaDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }


    public function InsertVersionZonaDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("InsertVersionZonaDB", $param, true);
        $ds->CloseConnection();
        return $result;
    }

    public function ActVersionZonaDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("ActVersionZonaDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function EliminarVersionZonaDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("EliminarVersionZonaDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function versionZonaDataSet($ds, $coloresVersId)
    {
        $dsO = new DataServices();
        $param[0] = "";        
        $query = array();

        if($coloresVersId != "")
        {
            $query[] = " A.coloresVersId=$coloresVersId ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }

        $ds->SelectCommand = $dsO->ExecuteDS("ObtVersionesZonasGridDB", $param);
        // $param[0] = $gralVersId;
        // $ds->InsertCommand = $dsO->ExecuteDS("insertVersionPrecioGrid", $param);        
        // $ds->UpdateCommand = $dsO->ExecuteDS("updateVersionPrecioGrid", $param);
        // $ds->DeleteCommand = $dsO->ExecuteDS("deleteEspacioGrid", $param);
        
        $dsO->CloseConnection();

        return $ds;
    }
    
    //obtener ids de las zonas por el id de los colores
    public function idsZonasVersPorIdsColorDB($coloresVersId){
        $ds = new DataServices();
        $param[0] = $coloresVersId;        
        
        $result = $ds->Execute("idsZonasVersPorIdsColorDB", $param);
        $ds->CloseConnection();
        return $result;
    }
}