<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catTasasDB {
    
    //Obtener coleccion de tasas
    public function ObtTodasTasasDB($activo){
        $ds = new DataServices();
        $param[0] = "";        
        $query = array();
        if($activo != "")
        {
            $query[] = " activo=$activo ";            
        }
        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }
        
        $result = $ds->Execute("ObtTodasTasasDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function tasasDataSet($ds, $activo)
    {
        $dsO = new DataServices();
        $param[0] = "";
        $query = array();

        if($activo != "")
        {
            $query[] = " activo=$activo ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);          
          $param[0] = $wordWhere.$setWhere;
        }

        $ds->SelectCommand = $dsO->ExecuteDS("ObtTodasTasasDB", $param);
        $param = null;
        $ds->UpdateCommand = $dsO->ExecuteDS("actTasaGrid", $param);
        $ds->InsertCommand = $dsO->ExecuteDS("insTasaGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("eliminarTasaGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }

}