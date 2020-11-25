<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class usuariosBD {
    //method declaration
    public function LoginUser($email, $password)
    {
        $ds = new DataServices();
        $param[0]= $email;
        $param[1]= $password;

        $result = $ds->Execute("LoginUser", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function UserByID($idUser)
    {
        $ds = new DataServices();
        $param[0]= $idUser;
        $result = $ds->Execute("UserByID", $param);
        $ds->CloseConnection();

        return $result;
    }    
    public function UsersDataSet($ds)
    {
        $dsO = new DataServices();
        $param = null;

        $ds->SelectCommand = $dsO->ExecuteDS("getUsersForGrid", $param);
        $ds->UpdateCommand = $dsO->ExecuteDS("updateUserGrid", $param);
        $ds->DeleteCommand = $dsO->ExecuteDS("deleteUserGrid", $param);
        $ds->InsertCommand = $dsO->ExecuteDS("insertUserGrid", $param);
        $dsO->CloseConnection();

        return $ds;
    }
    //Otener todos los usuarios
    public function obtTodosUsuariosDB()
    {
        $ds = new DataServices();
        $param = null;

        $result = $ds->Execute("obtTodosUsuariosDB", $param);
        $ds->CloseConnection();
        return $result;
    }
    public function UserByEmailDB($email)
    {
        $ds = new DataServices();
        $param[0]= $email;
        $result = $ds->Execute("UserByEmailDB", $param);
        $ds->CloseConnection();
        return $result;
    }



    // public function insertUsuarioDB($param){
    //     $ds = new DataServices();
    //     $result = $ds->Execute("insertUsuarioDB", $param, true);
    //     return $result;
    // }
    // public function updateUsuarioDB($param){
    //     $ds = new DataServices();
    //     $result = $ds->Execute("updateUsuarioDB", $param, false, true);
    //     return $result;
    // }
    // //Recuperar contrasenia por su email
    

    // //Recuperar datos por numero de contrato y email
    // public function UserByContractEmailDB($contrat, $email)
    // {
    //     $ds = new DataServices();
    //     $param[0]= $contrat;
    //     $param[1]= $email;

    //     $result = $ds->Execute("UserByContractEmailDB", $param);
    //     $ds->CloseConnection();
    //     return $result;
    // }
    //Obtener usuarios por rol
    public function obtUsuariosByIdRolDB($idRol){
        $ds = new DataServices();
        $param[0] = "";
        $query = array();

        if($idRol != ""){
            $query[] = " A.idRol=$idRol ";
        }

        // Obtener id de la agencia
        if(isset($_SESSION["idAgencia"]) && $_SESSION["idAgencia"]!=""){
            //Solo administrador y editores
            if($_SESSION["idRol"]==2 || $_SESSION["idRol"]==4){
                $query[] = " A.agenciaId=".$_SESSION["idAgencia"]." ";
            }
        }

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere. " ORDER BY A.nombre ASC ";
        }
        // $param[1] = " ORDER BY A.nombre ASC ";
        $result = $ds->Execute("obtUsuariosByIdRolDB", $param);
        $ds->CloseConnection();
        return $result;
    } 
    

    // //Obtener ids de usuarios X numero de contrato
    // public function ObtIdsUsuarioXNoContratoDB($noContratos)
    // {
    //   $ds = new DataServices();
    //   $param[0]= $noContratos;

    //   $result = $ds->Execute("ObtIdsUsuarioXNoContratoDB", $param);
    //   $ds->CloseConnection();
    //   return $result;
    // }
    
}