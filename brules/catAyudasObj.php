<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catAyudasDB.php';

class catAyudasObj {
    private $_idAyuda = 0;    
    private $_alias = '';
    private $_titulo = '';
    private $_descripcion = '';
    private $_fechaCreacion = '0000-00-00 00:00:00';    

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion del catalogo de ayudas
    public function ObtTodosCatAyudas(){
        $array = array();
        $ds = new catAyudasDB();

        $result = $ds->ObtTodosCatAyudasDB();
        $array = $this->arrDatos($result, "idAyuda");

        return $array;
    }

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

}