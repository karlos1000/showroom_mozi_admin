<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catAyudasDB {

    //Obtener coleccion del catalogo de ayudas
    public function ObtTodosCatAyudasDB(){
        $ds = new DataServices();
        $param = NULL;        
        
        $result = $ds->Execute("ObtTodosCatAyudasDB", $param);
        $ds->CloseConnection();
        return $result;
    }
    
}