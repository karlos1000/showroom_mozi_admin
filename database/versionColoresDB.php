<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class versionColoresDB {

    //version color por id
    public function versionColorPorIdDB($id){
        $ds = new DataServices();
        $param[0] = $id;        
        
        $result = $ds->Execute("versionColorPorIdDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    //Obtener coleccion de colores
    public function ObtVersionesColoresDB($idVersionGral, $activo){
        $ds = new DataServices();
        $param[0] = "";        
        $query = array();
        if($activo != "")
        {
            $query[] = " activo=$activo ";
        }
        if($idVersionGral != "")
        {
            $query[] = " gralVersId=$idVersionGral ";
        }
        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }
        $param[1] = " ORDER BY color ASC "; //Imp. 02/01/2020
        // echo "<pre>"; print_r($param); echo "</pre>";
        
        $result = $ds->Execute("ObtVersionesColoresDB", $param);
        $ds->CloseConnection();
        return $result;
    }
    public function ActCampoVersionColorDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("ActCampoVersionColorDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    public function InsertVersionColorDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("InsertVersionColorDB", $param, true);
        $ds->CloseConnection();
        return $result;
    }
    public function ActVersionColorDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("ActVersionColorDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    public function eliminarVersionColorDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("eliminarVersionColorDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    public function versionColorDataSet($ds, $gralVersId)
    {
        $dsO = new DataServices();
        $param[0] = "";        
        $query = array();
        if($gralVersId != "")
        {
            $query[] = " gralVersId=$gralVersId ";
        }
        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
          $param[1] = " ORDER BY color ASC "; //Imp. 02/01/2020
        }
        $ds->SelectCommand = $dsO->ExecuteDS("ObtVersionesColoresDB", $param);
        // $param[0] = $gralVersId;
        // $ds->InsertCommand = $dsO->ExecuteDS("insertVersionPrecioGrid", $param);        
        // $ds->UpdateCommand = $dsO->ExecuteDS("updateVersionPrecioGrid", $param);
        // $ds->DeleteCommand = $dsO->ExecuteDS("deleteEspacioGrid", $param);
        $dsO->CloseConnection();
        return $ds;
    }

    //Desactivar todo la columna (solo aplica para los campos tipo char(1))
    public function desactivarPorGralVersDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("desactivarPorGralVersDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    

    //obtener ids de los colores por el id de la version
    public function idsColoresVersPorIdsVersionDB($gralVersId){
        $ds = new DataServices();
        $param[0] = $gralVersId;        
        
        $result = $ds->Execute("idsColoresVersPorIdsVersionDB", $param);
        $ds->CloseConnection();
        return $result;
    }
}