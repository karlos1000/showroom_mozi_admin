<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/estadisticasAppDB.php';
// include_once  $dirname.'/brules/usuariosObj.php';

class estadisticasAppObj {
    private $_estadisticaId = 0;
    private $_usuarioId = 0;
    private $_nombre = '';
    private $_accion = '';    
    private $_idModelo = '';
    private $_modelo = '';
    private $_idVersion = '';
    private $_version = '';
    private $_emailProspecto = '';
    private $_prospecto = '';
    private $_fechaLog = '0000-00-00 00:00:00';
    private $_fechaCreacion = '0000-00-00 00:00:00';
    
    //Extras
    // private $_agencia = '';

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    public function salvarEstadisticaApp()
    {
        $ds = new estadisticasAppDB();        
        $this->_estadisticaId = $ds->insertarEstadisticaAppDB($this->getParams());
    }
       
    // Obtener agentes de ventas
    public function obtEstAgentes(){
        $array = array();
        $ds = new estadisticasAppDB();
        $result = $ds->obtEstAgentesDB();

        $array = (array)$this->arrDatosObjSinId($result);
        
        return $array;
    }

    // Obtener modelos
    public function obtEstModelos(){
        $array = array();
        $ds = new estadisticasAppDB();
        $result = $ds->obtEstModelosDB();

        $array = (array)$this->arrDatosObjSinId($result);
        return $array;
    }

    //Grid estadisticas    
    public function obtEstadisticasRpt($fechaDel="", $fechaAl="", $fil_opcion=0, $fil_agente=0, $fil_modelo=0){
        $array = array();
        $ds = new estadisticasAppDB();
        $result = $ds->obtEstadisticasRptDB($fechaDel, $fechaAl, $fil_opcion, $fil_agente, $fil_modelo);
        
        $array = (array)$this->arrDatosObj($result);     
        
        return $array;
    }

    /*//obtener datos por el id
    public function datosProspectoPorId($id)
    {
        $ds = new estadisticasAppDB();
        $result = $ds->datosProspectoPorIdDB($id);
        $this->setDatos($result);
    }

    //Obtener todos los datos por el id del usuario (vendedor)
    public function obtTodosProspectosPorUsuario($opcObj=false, $idUsuario=0)
    {
        $array = array();
        $ds = new estadisticasAppDB();
        $result = $ds->obtTodosProspectosPorUsuarioDB($idUsuario);        

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "estadisticaId");           
        }
        
        return $array;
    } */

    private function getParams($opc=false)
    {
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
        $this->_fechaCreacion = $dateTime;
        
        $param[0] = $this->_usuarioId;
        $param[1] = $this->_nombre;        
        $param[2] = $this->_accion;
        $param[3] = $this->_idModelo;
        $param[4] = $this->_modelo;
        $param[5] = $this->_idVersion;
        $param[6] = $this->_version;
        $param[7] = $this->_emailProspecto;
        $param[8] = $this->_prospecto;        
        $param[9] = $this->_fechaLog;
        
        if($opc==true){
            $param[10] = $this->_estadisticaId;
        }else{
            $param[10] = $this->_fechaCreacion;
        }    

        return $param;
    }

    //Grid estadisticas
    // public function GetEstadisticasGrid($vendedorB = "", $nombreProspecB = "", $agenciaId=0, $fechaDel="", $fechaAl="")
    public function GetEstadisticasGrid($fechaDel="", $fechaAl="", $fil_opcion=0, $fil_agente=0, $fil_modelo=0)
    {
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);        
        $uDB = new estadisticasAppDB();
        $ds = $uDB->EstadisticasDataSet($ds, $fechaDel, $fechaAl, $fil_opcion, $fil_agente, $fil_modelo);
        $grid = new KoolGrid("EstadisticasGrid");

        $this->defineGridForm($grid, $ds);
        $this->defineColumnForm($grid, "estadisticaId", "ID", false, true);
        $this->defineColumnForm($grid, "nombre", "Vendedor", true, false, 0);
        $this->defineColumnForm($grid, "accion", "Evento", true, false, 0);
        $this->defineColumnForm($grid, "idModelo", "Id Modelo", false, false, 0);
        $this->defineColumnForm($grid, "modelo", "Modelo", true, false, 0);
        $this->defineColumnForm($grid, "idVersion", "Id versi&oacute;n", false, false, 0);
        $this->defineColumnForm($grid, "version", "Versi&oacute;n", true, false, 0);
        $this->defineColumnForm($grid, "emailProspecto", "Correo Prospecto", true, false, 0);
        $this->defineColumnForm($grid, "prospecto", "Prospecto", true, false, 0);
        $this->defineColumnForm($grid, "fechaLog2", "Fecha", true, false, 0);        
        // $this->defineColumnForm($grid, "acciones", "Accci&oacute;n", true, false, 0);

        //pocess grid
        $grid->Process();

        return $grid;
    }

    //Private Functions
    private function defineGridForm($grid, $ds)
    {
        //create and define grid
        $grid->scriptFolder = "../brules/KoolControls/KoolGrid";
        $grid->styleFolder="office2010blue";
        $grid->Width = "860px";

        $grid->RowAlternative = true;
        $grid->AjaxEnabled = true;
        $grid->AjaxLoadingImage =  "../brules/KoolControls/KoolAjax/loading/5.gif";
        $grid->Localization->Load("../brules/KoolControls/KoolGrid/localization/es.xml");

        $grid->AllowInserting = true;
        $grid->AllowEditing = true;
        $grid->AllowDeleting = true;
        $grid->AllowSorting = true;
        $grid->ColumnWrap = true;
        $grid->AllowScrolling = false;
        //$grid->MasterTable->Height = "540px";
        //$grid->MasterTable->ColumnWidth = "130px";
        $grid->AllowResizing = true;

        $grid->MasterTable->DataSource = $ds;
        $grid->MasterTable->AutoGenerateColumns = false;
        $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
        $grid->MasterTable->Pager->ShowPageSize = true;
        $grid->MasterTable->Pager->PageSizeOptions = "10,25,50,100,150";
        //Show Function Panel
        $grid->MasterTable->ShowFunctionPanel = false;
        //Insert Settings
        $grid->MasterTable->InsertSettings->Mode = "Form";
        $grid->MasterTable->EditSettings->Mode = "Form";
        $grid->MasterTable->InsertSettings->ColumnNumber = 1;
        // $grid->ClientSettings->ClientEvents["OnRowConfirmEdit"] = "Handle_OnRowConfirmEdit";
    }
    //define the grid columns
    private function defineColumnForm($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0)
    {   
        /*
        if($name_field == 'usuarioId') {
            $column = new GridDropDownColumn();
            $usrObj = new usuariosObj();
            $usrArr = $usrObj->obtTodosUsuarios();            
            foreach($usrArr as $usrTmp)
            {                
                $column->AddItem($usrTmp->nombre,$usrTmp->idUsuario);
            }        
        }
        elseif($name_field == 'acciones') {
            $column = new GridCustomColumn();
            $column->ItemTemplate = '
                <a class="" href="prospectodetalle.php?idP={estadisticaId}">Ver detalle</a>                
                ';            
        }
        else{
            $column = new gridboundcolumn();
        }*/

        $column = new gridboundcolumn(); 
            
        $column->Visible = $visible;
        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $grid->MasterTable->AddColumn($column);
    }

    /*
    //Obtener los prospectos a exportar 
    public function obtProspectosParaExportar($opcObj=false, $agenciaId="", $idUsuario="", $prospecto="", $fechaDel="", $fechaAl=""){
        $array = array();
        $ds = new estadisticasAppDB();
        $result = $ds->obtProspectosParaExportarDB($agenciaId, $idUsuario, $prospecto, $fechaDel, $fechaAl);

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "estadisticaId");           
        }
        
        return $array;
    }

    //Eliminar prospecto por id
    public function EliminarProspectoPorId($id){
        $ds = new estadisticasAppDB();
        $param[0] = $id;

        return $ds->EliminarProspectoPorIdDB($param);
    }*/

    
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
                $array[$myRows->estadisticaId] = $myRows;
            }
            mysqli_free_result($result); // Free result set        
        }
        return $array;
    }

    //Obtener coleccion de datos por fetch_object SIN UN IDENTIFICADOR
    private function arrDatosObjSinId($result){
        $array = array();
        $classObj = get_class($this);
        if ($result)
        {               
            while ($myRows = mysqli_fetch_object($result)){            
                $array[] = $myRows;
            }
            mysqli_free_result($result); // Free result set        
        }
        return $array;
    }

}