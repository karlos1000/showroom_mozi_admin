<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class inventarioPermisosDB {

    //Obtener coleccion
    public function ObtInventarioPermisosDB($activo){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();

        if($activo != -1){
            $query[] = " activo=$activo ";
        }
        
        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);          
          $param[0] = $wordWhere.$setWhere;
        }
        
        $result = $ds->Execute("ObtInventarioPermisosDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    //grid
    public function inventarioPermisosDataSet($ds){
        $dsO = new DataServices();
        $param[0] = "";

        $ds->SelectCommand = $dsO->ExecuteDS("ObtInventarioPermisosDB", $param);        
        $param = NULL;
        $ds->UpdateCommand = $dsO->ExecuteDS("ActInventarioPermisosGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }
    
}