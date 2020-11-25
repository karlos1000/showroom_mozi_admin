<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catCongiguracionesDB {

    //Obtener coleccion de historial por rango de fecha
    public function ObtTodasConfiguracionesDB($fDel, $fAl){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();        

        if($fDel!=""){
          $query[] = " ( fechaCreacion >= '$fDel 00:00:00' AND  fechaCreacion <= '$fAl 23:59:59' ) ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;

          $param[0] = $wordWhere.$setWhere;          
        }
        $param[1] = " ORDER BY fechaCreacion DESC ";
        
        $result = $ds->Execute("ObtTodasConfiguracionesDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    
    public function ConfiguracionByID($idComunicado)
    {
        $ds = new DataServices();
        $param[0]= $idComunicado;
        $result = $ds->Execute("ConfiguracionByID", $param);
        $ds->CloseConnection();

        return $result;
    }

    // Obtener configuraciones por ids //Imp. 08/01/2020
    public function ConfiguracionesPorIdsDB($activo, $ids){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();

        if($activo>-1){
            $query[] = " activo=$activo ";
        }
        if($ids!=""){
            $query[] = " idConfiguracion IN (".$ids.") ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);          
          $param[0] = $wordWhere.$setWhere;
        }        
        
        $result = $ds->Execute("ConfiguracionesPorIdsDB", $param);
        $ds->CloseConnection();
        return $result;
    }
    
    /*public function insertComunicadoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insertComunicadoDB", $param, true);

        return $result;
    }*/
    
    
    public function updateConfiguracionDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("updateConfiguracionDB", $param, false, true);

        return $result;
    }
     
    public function catConfiguracioneDataSet($ds)
    {
        $dsO = new DataServices();
        /*$param[0] = "";
        $query = array();        

        if($fDel!=""){
          $query[] = " ( fechaCreacion >= '$fDel 00:00:00' AND  fechaCreacion <= '$fAl 23:59:59' ) ";
        }
        if($activo != ""){
            $query[] = " activo=$activo ";
        }
        if($publico != ""){
            $query[] = " publico=$publico ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
            $wordWhere = " WHERE ";
            $setWhere = implode(" AND ", $query);
            // echo $setWhere;
            $param[0] = $wordWhere.$setWhere;          
        }
        $param[1] = " ORDER BY fechaCreacion DESC ";*/

        $param[0] = "";
        $param[1] = "";
        $ds->SelectCommand = $dsO->ExecuteDS("ObtTodasConfiguracionesDB", $param);
        $param = NULL;
        $ds->UpdateCommand = $dsO->ExecuteDS("ActConfiguracionGrid", $param);
        //$ds->DeleteCommand = $dsO->ExecuteDS("deleteSesInvGrid", $param);
        //$ds->InsertCommand = $dsO->ExecuteDS("insertSesInvGrid", $param);
        $dsO->CloseConnection();

      return $ds;
    }


}
