<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catConceptoPlanesDB {

    //Obtener coleccion del catalogo concepto planes
    public function ObtTodosCatConceptoPlanesDB(){
        $ds = new DataServices();
        $param = NULL;        
        
        $result = $ds->Execute("ObtTodosCatConceptoPlanesDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    //cat concepto planes grid
    public function catConceptoPlanesDataSet($ds)
    {
        $dsO = new DataServices();
        $param = null;

        $ds->SelectCommand = $dsO->ExecuteDS("ObtTodosCatConceptoPlanesDB", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("actCatConceptoPlanGrid", $param);
        $ds->InsertCommand = $dsO->ExecuteDS("insertCatConceptoPlanGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("eliminarCatConceptoPlanGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }
    
}