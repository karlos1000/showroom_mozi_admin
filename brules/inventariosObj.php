<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/inventariosDB.php';

class inventariosObj {
    private $_inventarioId = 0;
    private $_rfc = '';
    private $_dealer = '';
    private $_condicion_uso = '';
    private $_vin = '';
    private $_combustible = '';
    private $_version = '';
    private $_anio = '';
    private $_color = '';
    private $_modelo = '';
    private $_transmision = '';
    private $_hp = '';
    private $_cilindros = '';
    private $_color_interior = '';
    private $_tipo_auto = '';
    private $_clv_vehicular = '';
    private $_disponibilidad = '';
    private $_razon_social = '';
    private $_fecha_compra = '';
    private $_fc_serie = '';
    private $_fc_folio = '';
    private $_fecha_vencimiento = '';
    private $_fecha_recibo = '';
    private $_valor_factura = '';
    private $_local_origen = '';
    private $_fecha_entrega = '';
    private $_fecha_venta = '';
    private $_fv_serie = '';
    private $_fv_folio = '';
    private $_sucursal = '';
    private $_agencia = '';

    
    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

   
    //Obtener coleccion
    public function ObtInventario($opcObj=false, $rfc="", $dealer="", $vin="", $modelo="", $anio="", $version="", $color="", $clv_vehicular="", $condicion="", $disponibilidad="", $agencia=""){
        $array = array();
        $ds = new inventariosDB();
        $result = $ds->ObtInventarioDB($rfc, $dealer, $vin, $modelo, $anio, $version, $color, $clv_vehicular, $condicion, $disponibilidad, $agencia);

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "inventarioId");
        }
        return $array;
    }

    //Limpiar tabla
    public function LimpiarInventario(){
        $array = array();
        $ds = new inventariosDB();

        return $ds->LimpiarInventarioDB();
    }

    //Insertar
    public function InsertarInventario(){
        $array = array();
        $ds = new inventariosDB();

        $this->_inventarioId = $ds->InsertarInventarioDB($this->getParams(false));
    }

    // Obtener agencias 
    public function ObtAgencias($opcObj=false){
        $array = array();
        $ds = new inventariosDB();
        $result = $ds->ObtAgenciasDB();

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "inventarioId");
        }
        return $array;
    }


    //Parametros
    private function getParams($act){
        $dTZoneObj = obtDateTimeZone();                
        // $this->_fechaCreacion = $dTZoneObj->fechaHora;
        
        $param[0]  = $this->_rfc;
        $param[1]  = $this->_dealer;
        $param[2]  = $this->_condicion_uso;
        $param[3]  = $this->_vin;
        $param[4]  = $this->_combustible;
        $param[5]  = $this->_version;
        $param[6]  = $this->_anio;
        $param[7]  = $this->_color;
        $param[8]  = $this->_modelo;
        $param[9]  = $this->_transmision;
        $param[10] = $this->_hp;
        $param[11] = $this->_cilindros;
        $param[12] = $this->_color_interior;
        $param[13] = $this->_tipo_auto;
        $param[14] = $this->_clv_vehicular;
        $param[15] = $this->_disponibilidad;
        $param[16] = $this->_razon_social;
        $param[17] = $this->_fecha_compra;
        $param[18] = $this->_fc_serie;
        $param[19] = $this->_fc_folio;
        $param[20] = $this->_fecha_vencimiento;
        $param[21] = $this->_fecha_recibo;
        $param[22] = $this->_valor_factura;
        $param[23] = $this->_local_origen;
        $param[24] = $this->_fecha_entrega;
        $param[25] = $this->_fecha_venta;
        $param[26] = $this->_fv_serie;
        $param[27] = $this->_fv_folio;
        $param[28] = $this->_sucursal;
        $param[29] = $this->_agencia;
        
        return $param;
    }
    

    //Metodo para importar el archivo
    public function ImportarInventario($FILES){
        $dateByZone = obtDateTimeZone();
        $dateTime = $dateByZone->fechaHora; //fecha Actual        
        $arrDisponible = array("disponible", "nuevos", "afasa");
        $arrSalvados = array();
        $arrNoSalvados = array();

        // echo "<pre>";    
        // print_r($FILES);
        // echo "</pre>";

        $filename = $_FILES['nameFile']['name'];
        $extFile = @end(explode(".", $filename));
        $fileTempPath = $_FILES['nameFile']['tmp_name'];
        $dirname = dirname(__DIR__);
        $uploadPath = $dirname."/uploadxls/".$filename;        
                    
        //verifica la extension del archivo
        if($extFile=='xls' || $extFile=='xlsx'){
            
            if(move_uploaded_file($fileTempPath, $uploadPath)){
                $objPHPExcel = PHPExcel_IOFactory::load($uploadPath); //leer archivo
                $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                $totalCeldas = count($sheetData); 
                $totalCol = count($sheetData[1]);                                
                
                $CollA=''; $CollB=''; $CollC=''; $CollD=''; $CollE=''; $CollF=''; $CollJ=''; $CollH=''; $CollI=''; $CollJ='';                   
                $CollK=''; $CollL=''; $CollM=''; $CollN=''; $CollO=''; $CollP=''; $CollQ='';
                set_time_limit(0); //evitar el error max_execution_time
                
                //valida que los datos sean correctos
                for ($i = 1; $i <=1; $i++) {
                    $CollA = (isset($sheetData[$i]["A"]) && $sheetData[$i]["A"]!="") ? $sheetData[$i]["A"] : '';  //RFC
                    $CollB = (isset($sheetData[$i]["B"]) && $sheetData[$i]["B"]!="") ? $sheetData[$i]["B"] : '';  //Sucursal
                    $CollC = (isset($sheetData[$i]["C"]) && $sheetData[$i]["C"]!="") ? $sheetData[$i]["C"] : '';  //Razón Social
                    $CollD = (isset($sheetData[$i]["D"]) && $sheetData[$i]["D"]!="") ? $sheetData[$i]["D"] : '';  //Tipo de Condición
                    $CollE = (isset($sheetData[$i]["E"]) && $sheetData[$i]["E"]!="") ? $sheetData[$i]["E"] : '';  //VIN
                    $CollF = (isset($sheetData[$i]["F"]) && $sheetData[$i]["F"]!="") ? $sheetData[$i]["F"] : '';  //Combustible
                    $CollG = (isset($sheetData[$i]["G"]) && $sheetData[$i]["G"]!="") ? $sheetData[$i]["G"] : '';  //Versión
                    $CollH = (isset($sheetData[$i]["H"]) && $sheetData[$i]["H"]!="") ? $sheetData[$i]["H"] : '';  //Año
                    $CollI = (isset($sheetData[$i]["I"]) && $sheetData[$i]["I"]!="") ? $sheetData[$i]["I"] : '';  //Color
                    $CollJ = (isset($sheetData[$i]["J"]) && $sheetData[$i]["J"]!="") ? $sheetData[$i]["J"] : '';  //Modelo
                    $CollK = (isset($sheetData[$i]["K"]) && $sheetData[$i]["K"]!="") ? $sheetData[$i]["K"] : '';  //Transmisión
                    $CollL = (isset($sheetData[$i]["L"]) && $sheetData[$i]["L"]!="") ? $sheetData[$i]["L"] : '';  //Potencia
                    $CollM = (isset($sheetData[$i]["M"]) && $sheetData[$i]["M"]!="") ? $sheetData[$i]["M"] : '';  //Cilindros
                    $CollN = (isset($sheetData[$i]["N"]) && $sheetData[$i]["N"]!="") ? $sheetData[$i]["N"] : '';  //Color Interior
                    $CollO = (isset($sheetData[$i]["O"]) && $sheetData[$i]["O"]!="") ? $sheetData[$i]["O"] : '';  //Estatus
                    $CollP = (isset($sheetData[$i]["P"]) && $sheetData[$i]["P"]!="") ? $sheetData[$i]["P"] : '';  //Tipo de Vehículo
                    $CollQ = (isset($sheetData[$i]["Q"]) && $sheetData[$i]["Q"]!="") ? $sheetData[$i]["Q"] : '';  //Clave Vehicular    
                    $CollR = (isset($sheetData[$i]["R"]) && $sheetData[$i]["R"]!="") ? $sheetData[$i]["R"] : '';  //Nombre de la agencia
                                       
                    if($CollA !='' && $CollB !='' && $CollC !='' && $CollD !='' && $CollE !='' && $CollF !='' && $CollG !='' && $CollH !='' && 
                       $CollI !='' && $CollJ !='' && $CollK !='' && $CollL !='' && $CollM !='' && $CollN !='' && $CollO !='' && $CollP !='' && 
                       $CollQ !='' && $CollR !=''){
                        // return 'Archivo correcto.';
                    }else{
                        return 'Por favor verifique la estructura del archivo, visualice el ejemplo en la nota de arriba.';
                    }                                        
                }

                //Limpiar tabla 
                $inventarioObj = new inventariosObj();
                $limpResp = $inventarioObj->LimpiarInventario();
                // if($limpResp>0){
                //     echo "Se limpio<br/>";
                // }else{
                //     echo "No se limpio<br/>";
                // }
                // exit();        

                // Recorrer fila tras fila
                for ($i = 2; $i <=$totalCeldas; $i++) {
                    $CollA = (isset($sheetData[$i]["A"]) && $sheetData[$i]["A"]!="") ? $sheetData[$i]["A"] : '';  //RFC
                    $CollB = (isset($sheetData[$i]["B"]) && $sheetData[$i]["B"]!="") ? $sheetData[$i]["B"] : '';  //Sucursal
                    $CollC = (isset($sheetData[$i]["C"]) && $sheetData[$i]["C"]!="") ? $sheetData[$i]["C"] : '';  //Razón Social
                    $CollD = (isset($sheetData[$i]["D"]) && $sheetData[$i]["D"]!="") ? $sheetData[$i]["D"] : '';  //Tipo de Condición
                    $CollE = (isset($sheetData[$i]["E"]) && $sheetData[$i]["E"]!="") ? $sheetData[$i]["E"] : '';  //VIN
                    $CollF = (isset($sheetData[$i]["F"]) && $sheetData[$i]["F"]!="") ? $sheetData[$i]["F"] : '';  //Combustible
                    $CollG = (isset($sheetData[$i]["G"]) && $sheetData[$i]["G"]!="") ? $sheetData[$i]["G"] : '';  //Versión
                    $CollH = (isset($sheetData[$i]["H"]) && $sheetData[$i]["H"]!="") ? $sheetData[$i]["H"] : '';  //Año
                    $CollI = (isset($sheetData[$i]["I"]) && $sheetData[$i]["I"]!="") ? $sheetData[$i]["I"] : '';  //Color
                    $CollJ = (isset($sheetData[$i]["J"]) && $sheetData[$i]["J"]!="") ? $sheetData[$i]["J"] : '';  //Modelo
                    $CollK = (isset($sheetData[$i]["K"]) && $sheetData[$i]["K"]!="") ? $sheetData[$i]["K"] : '';  //Transmisión
                    $CollL = (isset($sheetData[$i]["L"]) && $sheetData[$i]["L"]!="") ? $sheetData[$i]["L"] : '';  //Potencia
                    $CollM = (isset($sheetData[$i]["M"]) && $sheetData[$i]["M"]!="") ? $sheetData[$i]["M"] : '';  //Cilindros
                    $CollN = (isset($sheetData[$i]["N"]) && $sheetData[$i]["N"]!="") ? $sheetData[$i]["N"] : '';  //Color Interior
                    $CollO = (isset($sheetData[$i]["O"]) && $sheetData[$i]["O"]!="") ? $sheetData[$i]["O"] : '';  //Estatus
                    $CollR = (isset($sheetData[$i]["R"]) && $sheetData[$i]["R"]!="") ? $sheetData[$i]["R"] : '';  //Nombre de la agencia

                    // Verificacion si esta disponible o no 
                    $CollO = strtolower($CollO);
                    if($CollO!=""){
                        if(in_array($CollO, $arrDisponible)){
                            $CollO = 1;
                        }else{
                            $CollO = 0;
                        }
                    }else{
                        $CollO = 0;
                    }

                    $CollP = (isset($sheetData[$i]["P"]) && $sheetData[$i]["P"]!="") ? $sheetData[$i]["P"] : '';  //Tipo de Vehículo
                    $CollQ = (isset($sheetData[$i]["Q"]) && $sheetData[$i]["Q"]!="") ? $sheetData[$i]["Q"] : '';  //Clave Vehicular

                    // Crear instancia y setear datos                    
                    $inventarioObj = new inventariosObj();
                    $inventarioObj->rfc               = $CollA;
                    $inventarioObj->dealer            = "";
                    $inventarioObj->condicion_uso     = $CollD;
                    $inventarioObj->vin               = $CollE;
                    $inventarioObj->combustible       = $CollF;
                    $inventarioObj->version           = $CollG;
                    $inventarioObj->anio              = $CollH;
                    $inventarioObj->color             = $CollI;
                    $inventarioObj->modelo            = $CollJ;
                    $inventarioObj->transmision       = $CollK;
                    $inventarioObj->hp                = $CollL;
                    $inventarioObj->cilindros         = $CollM;
                    $inventarioObj->color_interior    = $CollN;
                    $inventarioObj->tipo_auto         = $CollP;
                    $inventarioObj->clv_vehicular     = $CollQ;
                    $inventarioObj->disponibilidad    = $CollO;
                    $inventarioObj->razon_social      = $CollC;
                    $inventarioObj->fecha_compra      = "";
                    $inventarioObj->fc_serie          = "";
                    $inventarioObj->fc_folio          = "";
                    $inventarioObj->fecha_vencimiento = "";
                    $inventarioObj->fecha_recibo      = "";
                    $inventarioObj->valor_factura     = "";
                    $inventarioObj->local_origen      = "";
                    $inventarioObj->fecha_entrega     = "";
                    $inventarioObj->fecha_venta       = "";
                    $inventarioObj->fv_serie          = "";
                    $inventarioObj->fv_folio          = "";
                    $inventarioObj->sucursal          = $CollB;
                    $inventarioObj->agencia           = $CollR;
                    $inventarioObj->InsertarInventario();

                    if($inventarioObj->inventarioId>0){
                        $arrSalvados[] = $CollA;
                    }else{
                        $arrNoSalvados[] = $CollA;
                    }                
                }

                $resultFinal = array("arrSalvados"=>$arrSalvados, "arrNoSalvados"=>$arrNoSalvados);                
                return $resultFinal; 
            }else{
                return "El archivo no subio correctamente, por favor de verificar.";
            }
        }else{
            return "La extensi&oacute;n del archivo no es correcta.";
        }
    }


    // //agencia por id
    // public function AgenciaPorId($id){
    //     $ds = new inventariosDB();
    //     $result = $ds->AgenciaPorIdDB($id);
    //     $this->setDatos($result);
    // }

    
    /*// Implementado el 04/02/20    
    //Actualizar
    public function ActAgencia(){
        $array = array();
        $ds = new inventariosDB();

        return $ds->ActAgenciaDB($this->getParams(true));
    }
    */  


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
                // $array[$myRows->inventarioId] = $myRows;
                $array[] = $myRows;
            }          
            mysqli_free_result($result); // Free result set        
        }
        return $array;
    }

}