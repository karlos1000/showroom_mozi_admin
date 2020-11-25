<?php

/*
 *  © 2017 Framelova. All rights reserved. Privacy Policy
 *  Creado: 21/02/2017
 *  Por: JCarlos Ramírez García - JCRG
 *  Descripción: registroDispositivo Object
 */

$dirname = dirname(__DIR__);
include_once  $dirname.'/database/registroDispositivosDB.php';

class registroDispositivo{
    
    //Private Fields
    private $_idReg = 0;    
    private $_usuarioId = 0;    
    private $_idRegDispositivo = "";    
    private $_activo = 0;    
    private $_plataforma = 0;
    private $_fechaCreacion = 0;
    
    //get y set
    public function __get($name) {             
        return $this->{"_".$name};
    }
    public function __set($name, $value) {        
        $this->{"_".$name} = $value;
    }

        
    //Invoca al metodo de registrar dispositivo
    public function Salvar(){
        $this->regDispositivo();
    }
    
    //registrar un nuevo dispositivo
    private function regDispositivo(){
        $obj = new registroDispositivosDB();
        $this->_idReg = $obj->insertarRegDispositivoDB($this->_usuarioId, $this->_idRegDispositivo, $this->_plataforma); 
    }        
    
    //Comprobar que ya existe el registro del dispositivo
    public function ObtRegDispositivoPorIdReg(){        
        $objDS = new registroDispositivosDB();
        $result = $objDS->ObtRegDispositivoPorIdRegDB($this->_idRegDispositivo, $this->_usuarioId);
        $this->setDatos($result);
    }

    //Obtener todos los registros dependiendo del id del usuario
    public function obtTodosRegDispositivoPorIdUsr(){
        $array = array();
        $objDS = new registroDispositivosDB();
        $result = $objDS->obtTodosRegDispositivoPorIdUsrDB($this->_usuarioId);                
        $array = $this->arrDatos($result, "idReg");
        
        return $array;
    }

    //Actualizar el registro por el id del usuario 
    public function ActRegActivo(){
        $obj = new registroDispositivosDB();
        $obj->ActRegActivoDB($this->_idRegDispositivo, $this->_plataforma, $this->_idReg);
    }

    
    // public function ObtRegDispositivoPorIdUsr($idUsuario){
    //     $resArr = array();
    //     $obj = new registroDispositivosDB();
    //     $result = $obj->ObtRegDispositivoPorIdUsrDB($idUsuario);
        
    //     if ($result)
    //     {
    //         while($myRows = mysql_fetch_array($result)) 
    //         {
    //             $objTmp = new registroDispositivo();
    //             $objTmp->setIdReg($myRows['id_reg']);                                
    //             $objTmp->setUsuarioId($myRows['usuario_id']);                                
    //             $objTmp->setIdRegDispositivo($myRows['id_reg_dispositivo']);                                
    //             $objTmp->setActivo($myRows['activo']);
    //             $objTmp->setPlataforma($myRows['plataforma']);
    //             $objTmp->setRecibirNotificacion($myRows['recibir_notificacion']);
                                
    //             $resArr[$objTmp->getIdReg()] = $objTmp;
    //         }
    //     }
        
    //     return $resArr;
    // }
    
    
    //obtener todos los registros de los dispositivos por idrol 
    // public function ObtRegDispositivoPorIdRol($idRol){
    //     $resArr = array();
    //     $obj = new registroDispositivosDB();
    //     $result = $obj->ObtRegDispositivoPorIdRolDB($idRol);
        
    //     if ($result)
    //     {
    //         while($myRows = mysql_fetch_array($result)) 
    //         {
    //             $objTmp = new registroDispositivo();
    //             $objTmp->setIdReg($myRows['id_reg']);                                
    //             $objTmp->setUsuarioId($myRows['usuario_id']);                                
    //             $objTmp->setIdRegDispositivo($myRows['id_reg_dispositivo']);                                
    //             $objTmp->setActivo($myRows['activo']);
    //             $objTmp->setPlataforma($myRows['plataforma']);
    //             $objTmp->setRecibirNotificacion($myRows['recibir_notificacion']);
                                
    //             $resArr[$objTmp->getIdReg()] = $objTmp;
    //         }
    //     }
        
    //     return $resArr;
    // }
    
    //actualizar registro de dispositivo por id del usuario
    // function ActRegDispositivoPorIdUser(){
    //     $obj = new registroDispositivosDB();
    //     $result = $obj->ActRegDispositivoPorIdUserDB($this->_usuario_id, $this->_idReg_dispositivo, $this->_plataforma);
    //     return $result;
    // }
    

    //setear datos en las variables privadas    
    private function setDatos($result){ 
        if ($result)
        {
            $myRows = mysqli_fetch_array($result);
            if($myRows == false) return;            
            foreach ($myRows as $key => $rowData) {
              if(is_string($key)) {
                  $this->{"_".$key} = $rowData;
              }
            }
        }           
    }
    //obtener coleccion de datos
    private function arrDatos($result, $nombreID){ 
        $array = array();
        $classObj = get_class($this);
        if ($result)
        {
            while($myRows = mysqli_fetch_array($result))
            {                               
                $objTmp = new $classObj();
                foreach ($myRows as $key => $rowData) {                 
                   if(is_string($key)) {
                     $objTmp->{"_".$key} = $rowData;
                     $array[$objTmp->{$nombreID}] = $objTmp;
                   }                   
                }
            }
        }
        return $array;
    }

  
    //Metodo que envia notificaciones push al dispositivo "Para android"
    public function gcmSend($regid, $message){        
        $apiKey = 'AIzaSyB7YmGaXgeQ6q_AjnvNEZ0L1fwf6QWnOb4';  //api key aguilardemo https://console.developers.google.com/apis/credentials?project=aguilardemo-172317
        $gcmUrl = 'https://android.googleapis.com/gcm/send';        

        // Send message:
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $gcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Authorization: key=' . $apiKey,
                'Content-Type: application/json'
            )
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(
                array(
                    'registration_ids' => array($regid),
                    'data' => array(
                        'message' => $message
                    )
                ),
                JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
            )
        );

        $result = curl_exec($ch);
        //if ($result === false) {
        //    throw new \Exception(curl_error($ch));
        //}
        curl_close($ch);

        return $result;
    }
    
    //Metodo que envia notificaciones push a dispositivos "ios"
    public function apnsSend($regid, $message){
        $dirname = dirname(__DIR__);
        $archivoPem = $dirname.'/common/aps_devdist_aguilarpushCK.pem';

        // El password del fichero .pem
        $passphrase = 'cerpub123';

        $ctx = stream_context_create();
        //Especificamos la ruta al certificado .pem que hemos creado
        stream_context_set_option($ctx, 'ssl', 'local_cert', $archivoPem);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
         
        // Abrimos conexión con APNS
        //ssl://gateway.sandbox.push.apple.com:2195
        $fp = stream_socket_client(
            'ssl://gateway.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
         
        if (!$fp) {
            exit("Error de conexión: $err $errstr" . PHP_EOL);
        }
         
        // echo 'Conectado al APNS' . PHP_EOL;
         
        // Creamos el payload
        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'bingbong.aiff',
            'badge' => 0
            );
         
        // Lo codificamos a json
        $payload = json_encode($body);
         
        // Construimos el mensaje binario
        $msg = chr(0) . pack('n', 32) . pack('H*', $regid) . pack('n', strlen($payload)) . $payload;
         
        // Lo enviamos
        $result = fwrite($fp, $msg, strlen($msg));
         
        // if (!$result) {
        //     echo 'Mensaje no enviado' . PHP_EOL;
        // } else { 
        //     echo 'Mensaje enviado correctamente' . PHP_EOL;
        // }
         
        // cerramos la conexión
        fclose($fp);

        return $msg;


/*        
       // Set parameters:
        $apnsHost = 'gateway.sandbox.push.apple.com';
        $apnsPort = 2195;
        $apnsCert = $dirname.'/common/aps_devdist_aguilarpushCK.pem';
        // Setup stream:
        $streamContext = stream_context_create();
        @stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
        @stream_context_set_option($streamContext, 'ssl', 'passphrase', $passphrase);
        // Open connection:
        $apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
        // Get the device token (fetch from a database for example):
        $deviceToken = $regid;
        // Create the payload:        
        // If message is too long, truncate it to stay within the max payload of 256 bytes.
        if (strlen($message) > 125) {
            $message = substr($message, 0, 125) . '...';
        }
        $payload['aps'] = array('alert' => $message, 'badge' => 1, 'sound' => 'default');
        $payload = json_encode($payload);
        // Send the message:
        $apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $deviceToken)) . chr(0) . chr(strlen($payload)). $payload;
        fwrite($apns, $apnsMessage);
        // Close connection:
        @socket_close($apns);
        fclose($apns);  
        echo $apnsMessage;
*/        
    }
    
}
?>