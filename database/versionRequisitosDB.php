<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class versionRequisitosDB {

    //version requisito por id
    public function versionRequisitoPorIdDB($id){
        $ds = new DataServices();
        $param[0] = $id;        
        
        $result = $ds->Execute("versionRequisitoPorIdDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    //Obtener coleccion de versiones requisitos
    public function ObtVersionesRequisitosDB($idVersionGral, $activo, $preconfigurado){
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
        //Para obtener los planes preconfigurados
        if($preconfigurado != 0)
        {
            $query[] = " preconfigurado=1 ";
        }else{
            $query[] = " preconfigurado=0 ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }
        
        $result = $ds->Execute("ObtVersionesRequisitosDB", $param);
        $ds->CloseConnection();
        return $result;
    }
    public function ActCampoVersionReqDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("ActCampoVersionReqDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    public function InsertVersionReqDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("InsertVersionReqDB", $param, true);
        $ds->CloseConnection();
        return $result;
    }
    public function ActVersionReqDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("ActVersionReqDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    public function versionRequisitoDataSet($ds, $gralVersId, $preconfigurado)
    {
        $dsO = new DataServices();
        $param[0] = "";        
        $query = array();
        if($gralVersId != "")
        {
            $query[] = " gralVersId=$gralVersId ";
        }
        //Para obtener los planes preconfigurados
        if($preconfigurado != 0)
        {
            $query[] = " preconfigurado=1 ";
        } 

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }
        $ds->SelectCommand = $dsO->ExecuteDS("ObtVersionesRequisitosDB", $param);
        // $param[0] = $gralVersId;
        // $ds->InsertCommand = $dsO->ExecuteDS("insertVersionPrecioGrid", $param);        
        // $ds->UpdateCommand = $dsO->ExecuteDS("updateVersionPrecioGrid", $param);
        $param = null;
        $ds->DeleteCommand = $dsO->ExecuteDS("deleteVersionesRequisitosGrid", $param);
        $dsO->CloseConnection();
        return $ds;
    }

    //obtener ids de los requisitos por el id de la version
    public function idsReqVersPorIdsVersionDB($gralVersId){
        $ds = new DataServices();
        $param[0] = $gralVersId;        
        
        $result = $ds->Execute("idsReqVersPorIdsVersionDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function EliminarVersionRequisitoDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("EliminarVersionRequisitoDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    

    //Obtener datos de los requisitos por su id 
    public function ObtVariosRequisitosPorIdDB($id){
        $ds = new DataServices();
        $param[0] = $id;
        
        $result = $ds->Execute("versionRequisitoPorIdDB", $param);
        $ds->CloseConnection();
        return $result;
    }

}