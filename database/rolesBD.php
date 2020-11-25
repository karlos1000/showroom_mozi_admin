<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class rolesBD {

    //Obtener coleccion de roles
    public function GetallRoles(){
        $ds = new DataServices();

        $param= NULL;
        $result = $ds->Execute("GetAllRoles", $param);
        $ds->CloseConnection();
        return $result;
    }

    //obtener el rol por su id
    public function obtenerRolByIdDB($id){
        $ds = new DataServices();
        $param[0] = $id;
        $result = $ds->Execute("obtenerRolByIdDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    //roles grid
    public function rolesDataSet($ds)
    {
        $dsO = new DataServices();
        $param = null;

        $ds->SelectCommand = $dsO->ExecuteDS("GetAllRoles", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("updateRolGrid", $param);
        $ds->InsertCommand = $dsO->ExecuteDS("insertRolGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("deleteRolesGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }

}
