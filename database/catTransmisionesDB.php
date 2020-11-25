<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catTransmisionesDB {

    //obtener datos del modelo
    public function ObtDatosTransmisionPorIdDB($id){
        $ds = new DataServices();
        $param[0] = $id;
        
        $result = $ds->Execute("ObtDatosTransmisionPorIdDB", $param);
        $ds->CloseConnection();
        return $result;
    }    

    //Obtener coleccion de transmisiones
    public function ObtTodosTransmisionesDB($activo){
        $ds = new DataServices();
        $param[0] = " ORDER BY transmision ASC ";
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
          // $param[0] = $wordWhere.$setWhere;
          $param[0] = $wordWhere.$setWhere ." ORDER BY transmision ASC ";
        }
        // $param[1] = " ORDER BY transmision ASC ";
        // echo "<pre>"; print_r($param); echo "</pre>";
        
        $result = $ds->Execute("ObtTodosTransmisionesDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function transmisionesDataSet($ds, $activo)
    {
        $dsO = new DataServices();
        $param[0] = " ORDER BY transmision ASC ";
        $query = array();

        if($activo != "")
        {
            $query[] = " activo=$activo ";
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);          
          // $param[0] = $wordWhere.$setWhere;
          $param[0] = $wordWhere.$setWhere ." ORDER BY transmision ASC ";
        }
        // $param[1] = " ORDER BY transmision ASC ";

        $ds->SelectCommand = $dsO->ExecuteDS("ObtTodosTransmisionesDB", $param); 
        $param = null;
        $ds->UpdateCommand = $dsO->ExecuteDS("actTransmisionGrid", $param);
        $ds->InsertCommand = $dsO->ExecuteDS("insTransmisionGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("eliminarTransmisionGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }

    /*
    
    //Insertar un modelo
    public function InsertGamaModeloDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("InsertGamaModeloDB", $param, true);
        $ds->CloseConnection();
        return $result;
    }

    //actualizar modelo
    public function ActGamaModeloDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("ActGamaModeloDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function ActCampoGamaModeloDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("ActCampoGamaModeloDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    //Activar y desactivar
    public function ActivaDesactivaGModeloDB($valor){
        $ds = new DataServices();
        $param[0] = $valor;

        $result = $ds->Execute("ActivaDesactivaGModeloDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    public function EliminarGamaModeloDB($param){
        $ds = new DataServices();

        $result = $ds->Execute("EliminarGamaModeloDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    } 
    */
}