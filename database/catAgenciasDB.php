<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catAgenciasDB {
    
    //Obtener coleccion de agencias
    public function ObtTodasAgenciasDB($activo){
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
        $param[1] = " ORDER BY nombre ASC ";
        
        $result = $ds->Execute("ObtTodasAgenciasDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function agenciaDataSet($ds, $activo)
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

        $ds->SelectCommand = $dsO->ExecuteDS("ObtTodasAgenciasDB", $param);
        $param = null;
        $ds->UpdateCommand = $dsO->ExecuteDS("actAgenciaGrid", $param);
        $ds->InsertCommand = $dsO->ExecuteDS("insAgenciaGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("eliminarAgenciaGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }


    //Obtener agencia por id
    public function AgenciaPorIdDB($id){
        $ds = new DataServices();
        $param[0]= $id;
        $result = $ds->Execute("AgenciaPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }
        
    // Implementado el 04/02/20
    //Insertar 
    public function InsertAgenciaDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("InsertAgenciaDB", $param, true);
        $ds->CloseConnection();
        return $result;
    }

    //actualizar 
    public function ActAgenciaDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("ActAgenciaDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }    

}