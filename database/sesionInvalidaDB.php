<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class sesionInvalidaDB {

    //Obtener coleccion de historial por rango de fecha
    public function ObtTodosSesionInvalidaDB($fDel, $fAl){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();        

        if($fDel!=""){
          $query[] = " ( FechaEjecucion >= '$fDel' AND  FechaEjecucion <= '$fAl' ) ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;

          $param[0] = $wordWhere.$setWhere;          
        }        
        
        $result = $ds->Execute("ObtHistorialPorFechaDB", $param);
        $ds->CloseConnection();
        return $result;
    }


    public function insertSesionInvalidaDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insertSesionInvalidaDB", $param, true);



        
        return $result;
    }
    
     public function SesionesInvalidasSet($ds)
    {
        $dsO = new DataServices();
        $param = null;

        $ds->SelectCommand = $dsO->ExecuteDS("getSesInvForGrid", $param);
        //$ds->UpdateCommand = $dsO->ExecuteDS("updateSesInvGrid", $param);
        //$ds->DeleteCommand = $dsO->ExecuteDS("deleteSesInvGrid", $param);
        //$ds->InsertCommand = $dsO->ExecuteDS("insertSesInvGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }


}
