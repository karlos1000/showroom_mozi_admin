<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class promocionesDB {

    //version promocion por id
    public function versionPromocionPorIdDB($id){
        $ds = new DataServices();
        $param[0] = $id;        
        
        $result = $ds->Execute("versionPromocionPorIdDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    //Obtener coleccion de versiones promociones
    public function ObtVersionesPromocionesDB($idVersionGral, $activo, $preconfigurado){
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
        
        $result = $ds->Execute("ObtVersionesPromocionesDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function ActCampoVersionPromocionDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("ActCampoVersionPromocionDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function InsertVersionPromocionDB($param){
        $ds = new DataServices();         
        $result = $ds->Execute("InsertVersionPromocionDB", $param, true);
        $ds->CloseConnection();
        return $result;
    }

    public function ActVersionPromocionDB($param){
        $ds = new DataServices();              
        $result = $ds->Execute("ActVersionPromocionDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function versionPromocionDataSet($ds, $gralVersId, $preconfigurado)
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
        $ds->SelectCommand = $dsO->ExecuteDS("ObtVersionesPromocionesDB", $param);        
        $param = null;
        // $ds->DeleteCommand = $dsO->ExecuteDS("deleteVersionesRequisitosGrid", $param);
        $dsO->CloseConnection();
        return $ds;
    }

    //obtener ids de las promociones por el id de la version
    public function idsPromosVersPorIdsVersionDB($gralVersId){
        $ds = new DataServices();
        $param[0] = $gralVersId;        
        
        $result = $ds->Execute("idsPromosVersPorIdsVersionDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function EliminarVersionPromocionDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("EliminarVersionPromocionDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    //version promo por id
    public function versionPromoPorIdPadreDB($idPadre){
        $ds = new DataServices();
        $param[0] = $idPadre;
        
        $result = $ds->Execute("versionPromoPorIdPadreDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    //Eliminar versiones por el id padre
    public function EliminarVersionPromocionPorIdPadreDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("EliminarVersionPromocionPorIdPadreDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    } 
    
}