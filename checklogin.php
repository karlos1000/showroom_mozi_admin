<?php
ob_start();

include_once 'brules/usuariosObj.php';
$userObj = new usuariosObj();

$usrEmail = stripslashes($_POST['usr_email']);
$usrPass = stripslashes($_POST['usr_pass']);

//verificar si existe usuario
// $userObj->LogInUser(trim($usrEmail), trim($usrPass));
$userObj->LogInUser(trim($usrEmail), str_replace("'", "", trim($usrPass)));

if($userObj->idUsuario>0)
{
    if($userObj->activo>0)
    {
        session_start();
        $_SESSION['idUsuario'] = $userObj->idUsuario;
        $_SESSION['idRol'] = $userObj->idRol;
        $_SESSION['myusername'] = $userObj->nombre;
        $_SESSION["status"] = "ok";
        $_SESSION["idAgencia"] = $userObj->agenciaId;
        
        if($userObj->idRol == 1 || $userObj->idRol == 2 || $userObj->idRol == 4){
            header("location:admin/index.php");
        }        
        else{
            header("location:index.php?login=error");
        }
    }
    else{
        header("location:index.php?login=inactivo");
    }
}
else
{
    header("location:index.php?login=error");
}

ob_flush();
?>
