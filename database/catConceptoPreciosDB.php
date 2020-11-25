<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catConceptoPreciosDB {

    //Obtener coleccion del catalogo concepto precios
    public function ObtTodosCatConceptoPreciosDB(){
        $ds = new DataServices();
        $param = NULL;        
        
        $result = $ds->Execute("ObtTodosCatConceptoPreciosDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    //cat concepto precios grid
    public function catConceptoPreciosDataSet($ds)
    {
        $dsO = new DataServices();
        $param = null;

        $ds->SelectCommand = $dsO->ExecuteDS("ObtTodosCatConceptoPreciosDB", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("ActCatConceptoPrecioGrid", $param);
        $ds->InsertCommand = $dsO->ExecuteDS("insertCatConceptoPrecioGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("eliminarCatConceptoPrecioGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }
    
}