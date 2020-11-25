<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class gamaModelosDB {

    //obtener datos del modelo
    public function ObtDatosGModeloPorIdDB($id){
        $ds = new DataServices();
        $param[0] = $id;
        
        $result = $ds->Execute("ObtDatosGModeloPorIdDB", $param);
        $ds->CloseConnection();
        return $result;
    }    

    //Obtener coleccion de gama de modelos
    public function ObtTodosGamaModelosDB($activo){
        $ds = new DataServices();
        $param[0] = " ORDER BY orden ASC ";        
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
          $param[0] = $wordWhere.$setWhere ." ORDER BY orden ASC ";
        }
        
        $result = $ds->Execute("ObtTodosGamaModelosDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    //Gama de modelos grid
    public function gamaModelosDataSet($ds, $nombreModeloB, $anioModeloB, $activoModeloB)
    {
        $dsO = new DataServices();
        $param[0] = " ORDER BY orden ASC  ";
        $query = array();
        if($nombreModeloB != "")
        {
            $query[] = " modelo LIKE '%".$nombreModeloB."%' ";
        }
        if($anioModeloB != "")
        {
            $query[] = " anio LIKE '%".$anioModeloB."%' ";
        }
        $activoModeloB = ($activoModeloB==-1)?"":$activoModeloB;
        if($activoModeloB !== "")
        {
            $query[] = " activo=$activoModeloB ";
        }        

        // echo"<pre";print_r($query);echo"</pre>";
        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere ." ORDER BY orden ASC ";
        }        

        $ds->SelectCommand = $dsO->ExecuteDS("ObtTodosGamaModelosDB", $param);        
        $dsO->CloseConnection();

        return $ds;
    }
    
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


    //Ordenar los modelos    
    public function OrdenarModelosDB($data){
        $ds = new DataServices();
        $position = 1;      
        $result = array();
        foreach($data as $item){
            $id = (int)trim(str_replace("id_mod_", "", $item));
            // array_push($result, $item.'-'.$position);            
            
            $param[0]=$position;
            $param[1]=$id;
            // $ds->Execute("OrdenarModelosDB", $param);
            $resOrd = $ds->Execute("OrdenarModelosDB", $param, false, true);
            
            if($resOrd>0){
                $result[] = 1;
            }
            
            $position++;            
        }
        $ds->CloseConnection();        
                        
        return $result;
    }

    //Actualizar el numero de orden cuando se crea el registro
    public function ActNumOrdenModeloDB($param){
        $ds = new DataServices();

        $result = $ds->Execute("OrdenarModelosDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    
}