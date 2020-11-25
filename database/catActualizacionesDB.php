<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catActualizacionesDB {

    //Obtener coleccion de actualizaciones
    public function ObtActualizacionesDB(){
        $ds = new DataServices();
        $param = NULL;
        
        $result = $ds->Execute("ObtActualizacionesDB", $param);
        $ds->CloseConnection();
        return $result;
    }   

    //Obtener coleccion de actualizaciones
    public function ObtDatosActualizacionPorTablaDB($tabla){
        $ds = new DataServices();
        $param[0] = $tabla;
            
        $result = $ds->Execute("ObtDatosActualizacionPorTablaDB", $param);
        $ds->CloseConnection();
        return $result;
    }
    public function updActualizacionDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("updActualizacionDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
}