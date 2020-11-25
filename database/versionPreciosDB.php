<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class versionPreciosDB {

    //version precio por id
    public function versionPrecioPorIdDB(){
        $ds = new DataServices();
        $param = NULL;        
        
        $result = $ds->Execute("versionPrecioPorIdDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function ActCampoVersionPrecioDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("ActCampoVersionPrecioDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    //Obtener coleccion de versiones precios
    public function ObtVersionesPreciosDB($idVersionGral, $activo){
        $ds = new DataServices();
        $param[0] = "";        
        $query = array();
        if($activo != "")
        {
            $query[] = " a.activo=$activo ";
        }
        if($idVersionGral != "")
        {
            $query[] = " a.gralVersId=$idVersionGral ";
        }
        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }
        
        $param[1] = " ORDER BY a.concPrecioId ";
        $result = $ds->Execute("ObtVersionesPreciosDB", $param);
        $ds->CloseConnection();
        return $result;
    }
    public function versionPrecioDataSet($ds, $gralVersId, $conceptoPrecioB, $activoPrecioB)
    {
        $dsO = new DataServices();
        $param[0] = "";        
        $query = array();
        if($gralVersId != "")
        {
            $query[] = " a.gralVersId=$gralVersId ";
        }
        if($conceptoPrecioB !== "")
        {
            $query[] = " a.concPrecioId=$conceptoPrecioB ";
        }
        $activoPrecioB = ($activoPrecioB==-1)?"":$activoPrecioB;
        if($activoPrecioB !== "")
        {
            $query[] = " a.activo=$activoPrecioB ";
        }
        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }
        $param[1] = " ORDER BY c.transmision ASC ";
        // print_r($param);

        $ds->SelectCommand = $dsO->ExecuteDS("ObtVersionesPreciosDB", $param);
        $param = null; $param[0] = $gralVersId;        
        $ds->InsertCommand = $dsO->ExecuteDS("insertVersionPrecioGrid", $param);        
        $ds->UpdateCommand = $dsO->ExecuteDS("updateVersionPrecioGrid", $param);        
        $param = null;
        $ds->DeleteCommand = $dsO->ExecuteDS("deleteVersionPrecioGrid", $param);
        $dsO->CloseConnection();
        return $ds;
    }

    //obtener ids de los precios por el id de la version
    public function idsPreciosVersPorIdsVersionDB($gralVersId){
        $ds = new DataServices();
        $param[0] = $gralVersId;        
        
        $result = $ds->Execute("idsPreciosVersPorIdsVersionDB", $param);
        $ds->CloseConnection();
        return $result;
    }  

    public function EliminarVersionPrecioDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("EliminarVersionPrecioDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }  
    
}