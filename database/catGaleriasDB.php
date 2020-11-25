<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catGaleriasDB {

    //Obtener coleccion del catalogo de galerias
    public function ObtTodosCatGaleriasDB(){
        $ds = new DataServices();
        $param = NULL;        
        
        $result = $ds->Execute("ObtTodosCatGaleriasDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function ObtGaleriaPorIdDB($id){
        $ds = new DataServices();
        $param[0] = $id;        
        
        $result = $ds->Execute("ObtGaleriaPorIdDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    //cat galerias grid
    public function catGaleriasDataSet($ds)
    {
        $dsO = new DataServices();
        $param = null;

        $ds->SelectCommand = $dsO->ExecuteDS("ObtTodosCatGaleriasDB", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("ActCatGaleriaGrid", $param);
        $ds->InsertCommand = $dsO->ExecuteDS("insertCatGaleriaGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("eliminarCatGaleriaGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }

    //Agregar galeria
    public function agregarGaleriaDB($nombre, $activo){
        $ds = new DataServices();
        $param[0] = $nombre;
        $param[1] = $activo;
        
        $result = $ds->Execute("agregarGaleriaDB", $param, true);
        $ds->CloseConnection();
        return $result;
    }    
    
}