<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catEstadosDB {

    //Obtener coleccion de historial por rango de fecha
    public function ObtTodosEstadosDB(){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();        

//        if($fDel!=""){
//          $query[] = " ( fechaCreacion >= '$fDel 00:00:00' AND  fechaCreacion <= '$fAl 23:59:59' ) ";
//        }
//        
//        if($idCliente != "")
//        {
//            $query[] = " idCliente=$idCliente ";
//        }
//        
        

//        En caso de llevar filtro
//        if(count($query) > 0){
//          $wordWhere = " WHERE ";
//          $setWhere = implode(" AND ", $query);
//          // echo $setWhere;
//
//          $param[0] = $wordWhere.$setWhere;          
//        }
//        $param[1] = " ORDER BY fechaCreacion DESC ";
//        
        $result = $ds->Execute("ObtTodosEstadosDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function EstadoByID($idEstado)
    {
        $ds = new DataServices();
        $param[0]= $idEstado;
        $result = $ds->Execute("EstadoByID", $param);
        $ds->CloseConnection();
        return $result;
    }
    public function insertEstadoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insertEstadoDB", $param, true);

        return $result;
    }
  
    public function updateEstadoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("updateEstadoDB", $param, true);
        return $result;
    }
    
    
    
  
    
     public function EstadosSet($ds)
    {
        $dsO = new DataServices();
        $param[0] = "";
        $query = array();        

//        if($fDel!=""){
//          $query[] = " ( fechaCreacion >= '$fDel 00:00:00' AND  fechaCreacion <= '$fAl 23:59:59' ) ";
//        }
//        if($activo != ""){
//          $query[] = " activo=$activo ";
//        }
//        if($publico != ""){
//          $query[] = " publico=$publico ";
//        }
//        
//        //En caso de llevar filtro
//        if(count($query) > 0){
//          $wordWhere = " WHERE ";
//          $setWhere = implode(" AND ", $query);
//          // echo $setWhere;
//
//          $param[0] = $wordWhere.$setWhere;          
//        }
//        $param[1] = " ORDER BY fechaCreacion DESC ";

        $ds->SelectCommand = $dsO->ExecuteDS("getEstadosForGrid", $param);
        //$ds->UpdateCommand = $dsO->ExecuteDS("updateEstadoGrid", $param);
        //$ds->DeleteCommand = $dsO->ExecuteDS("deleteEstadoGrid", $param);
        //$ds->InsertCommand = $dsO->ExecuteDS("insertEstadoGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }


}
