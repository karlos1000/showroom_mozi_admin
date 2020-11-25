<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catActualizacionesDB.php';

class catActualizacionesObj {
    private $_actualizacionId = 0;    
    private $_tabla = '';    
    private $_fechaActualizacion = '0000-00-00 00:00:00';    

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion de actualizaciones
    public function ObtActualizaciones($opcObj=false){
        $array = array();
        $ds = new catActualizacionesDB();
        $result = $ds->ObtActualizacionesDB();

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "actualizacionId");
        }

        return $array;
    }

    //Verificar si la tabla tiene algun cambio
    public function ObtDatosActualizacionPorTabla($tabla){
        $array = array();
        $ds = new catActualizacionesDB();

        $result = $ds->ObtDatosActualizacionPorTablaDB($tabla);
        $this->setDatos($result);
    }


    public function updActualizacion($tabla){
        $array = array();
        $ds = new catActualizacionesDB();
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
        $param[0] = $dateTime;
        $param[1] = $tabla;
        return $ds->updActualizacionDB($param);
    }
    //Metodo especificos
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

    //Obtener coleccion de datos por fetch_object
    private function arrDatosObj($result){
        $array = array();
        $classObj = get_class($this);
        if ($result)
        {               
            while ($myRows = mysqli_fetch_object($result)){            
                $array[$myRows->actualizacionId] = $myRows;
            }
            mysqli_free_result($result); // Free result set        
        }
        return $array;
    }

}