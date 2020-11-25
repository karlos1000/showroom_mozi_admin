<?php

/*
 *  © 2017 Framelova. All rights reserved. Privacy Policy
 *  Creado: 21/02/2017
 *  Por: JCarlos Ramírez García - JCRG
 *  Descripción: registro dispositivo database object
 */
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class registroDispositivosDB 
{        
        
    public function insertarRegDispositivoDB($usuarioId, $idRegDispositivo, $plataforma){
        $ds = new DataServices();
        
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha y hora

        $param[0]= $usuarioId;
        $param[1]= $idRegDispositivo;
        $param[2]= 1;
        $param[3]= $plataforma;
        $param[4]= $dateTime;

        $result = $ds->Execute("insertarRegDispositivoDB", $param, true);	
        $ds->CloseConnection();

        return $result;
    }
    
    //Comprobar que ya existe el registro del dispositivo
    public function ObtRegDispositivoPorIdRegDB($idReg, $idUsuario){
        $ds = new DataServices();
        $param[0]= $idReg;
        $param[1]= $idUsuario;
        $result = $ds->Execute("ObtRegDispositivoPorIdRegDB", $param); 
        return $result;
    }


    public function obtTodosRegDispositivoPorIdUsrDB($usuarioId){
        $ds = new DataServices();
        $param[0]= $usuarioId;        
        $result = $ds->Execute("obtTodosRegDispositivoPorIdUsrDB", $param); 
        $ds->CloseConnection();

        return $result;
    }

    //Actualizar el registro por el id del usuario 
    public function ActRegActivoDB($regDispositivo, $plataforma, $idReg){
        $ds = new DataServices();
        $param[0]= $regDispositivo;
        $param[1]= '1';
        $param[2]= $plataforma;
        $param[3]= $idReg;
        $result = $ds->Execute("ActRegActivoDB", $param);   

        return $result;
    }   


    /*
    public function ObtRegDispositivoPorIdUsrDB($idUsuario){
        $ds = new DataServices();
        
        $param[0]= $idUsuario;
        $result = $ds->Execute("ObtRegDispositivoPorIdUsrDB", $param);	

        return $result;
    }
    
    
    
     
    
    //obtener todos los registros de los dispositivos por idrol 
    public function ObtRegDispositivoPorIdRolDB($idRol){
        $ds = new DataServices();
        
        $param[0]= $idRol;
        $result = $ds->Execute("ObtRegDispositivoPorIdRolDB", $param);	

        return $result;
    }
    
    //actualizar registro de dispositivo por id del usuario
    public function ActRegDispositivoPorIdUserDB($usuarioId, $idRegDispositivo, $plataforma){
        $ds = new DataServices();
        $result = 0;
        
        $param[0] = $idRegDispositivo;
        $param[1] = $plataforma;
        $param[2] = $usuarioId;
        
        $ds->Execute("ActRegDispositivoPorIdUserDB", $param);	

        if(mysql_affected_rows()>0){
            $result = 1;
        }
        
        return $result;
    }
*/    
    
}
?>