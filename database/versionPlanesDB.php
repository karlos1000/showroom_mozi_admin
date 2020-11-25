<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class versionPlanesDB {

    //version plan por id
    public function versionPlanPorIdDB($id){
        $ds = new DataServices();
        $param[0] = $id;        
        
        $result = $ds->Execute("versionPlanPorIdDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    //Obtener coleccion de versiones planes
    public function ObtVersionesPlanesDB($idVersionGral, $activo, $preconfigurado){
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
        //Para obtener los planes preconfigurados
        if($preconfigurado != 0)
        {
            $query[] = " a.preconfigurado=1 ";
        }else{
            $query[] = " a.preconfigurado=0 ";
        }   

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }
        
        $result = $ds->Execute("ObtVersionesPlanesDB", $param);
        $ds->CloseConnection();
        return $result;
    }
    public function ActCampoVersionPlanDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("ActCampoVersionPlanDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    public function InsertVersionPlanDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("InsertVersionPlanDB", $param, true);
        $ds->CloseConnection();
        return $result;
    }
    public function ActVersionPlanDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("ActVersionPlanDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    public function versionPlanDataSet($ds, $gralVersId, $conceptoPlanB, $activoPlanB, $preconfigurado)
    {
        $dsO = new DataServices();
        $param[0] = "";        
        $query = array();
        if($gralVersId != "")
        {
            $query[] = " a.gralVersId=$gralVersId ";
        }
         if($conceptoPlanB !== "")
        {
            $query[] = " a.planId=$conceptoPlanB ";
        }
        $activoPlanB = ($activoPlanB==-1)?"":$activoPlanB;
        if($activoPlanB !== "")
        {
            $query[] = " a.activo=$activoPlanB ";
        }
        //Para obtener los planes preconfigurados
        if($preconfigurado != 0)
        {
            $query[] = " a.preconfigurado=1 ";
        }    

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }
        // echo "<pre>"; print_r($param); echo "</pre>";
        $ds->SelectCommand = $dsO->ExecuteDS("ObtVersionesPlanesDB", $param);
        // $param[0] = $gralVersId;
        // $ds->InsertCommand = $dsO->ExecuteDS("insertVersionPrecioGrid", $param);        
        // $ds->UpdateCommand = $dsO->ExecuteDS("updateVersionPrecioGrid", $param);
        $param = null;
        $ds->DeleteCommand = $dsO->ExecuteDS("deleteVersionesPlanesGrid", $param);
        $dsO->CloseConnection();
        return $ds;
    }
    
    //obtener ids de los planes por el id de la version
    public function idsPlanesVersPorIdsVersionDB($gralVersId){
        $ds = new DataServices();
        $param[0] = $gralVersId;        
        
        $result = $ds->Execute("idsPlanesVersPorIdsVersionDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function EliminarVersionPlanDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("EliminarVersionPlanDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    } 

    //version plan por id
    public function versionPlanPorIdPadreDB($idPadre){
        $ds = new DataServices();
        $param[0] = $idPadre;
        
        $result = $ds->Execute("versionPlanPorIdPadreDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    //Eliminar versiones por el id padre
    public function EliminarVersionPlanPorIdPadreDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("EliminarVersionPlanPorIdPadreDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    } 

    //Obtener datos de los planes por su id 
    public function ObtVariosPlanesPorIdDB($id){
        $ds = new DataServices();
        $param[0] = $id;
        
        $result = $ds->Execute("ObtVariosPlanesPorIdDB", $param);
        $ds->CloseConnection();
        return $result;
    }
}