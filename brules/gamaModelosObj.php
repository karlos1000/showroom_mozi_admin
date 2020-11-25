<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/gamaModelosDB.php';
// include '../brules/utilsObj.php';

class gamaModelosObj {
    private $_gModeloId = 0;    
    private $_modelo = '';
    private $_anio = 0;
    private $_imagen = '';
    private $_activo = '';
    private $_fechaCreacion = '0000-00-00 00:00:00';
    private $_eslogan = '';
    private $_camion = 0;
    private $_nuevo = 0;
    private $_mostrarAnio = 0;
    // extras
    private $_classActivo = '';
    private $_classInactivo = '';    
    private $_orden = '';

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //obtener datos del modelo
    public function ObtDatosGModeloPorId($id){
        $ds = new gamaModelosDB();

        $result = $ds->ObtDatosGModeloPorIdDB($id);
        $this->setDatos($result);
    }

    //Obtener coleccion de gama de modelos
    public function ObtTodosGamaModelos($opcObj=false, $activo = ""){
        $array = array();
        $ds = new gamaModelosDB();
        $result = $ds->ObtTodosGamaModelosDB($activo);

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "gModeloId");
        }
        return $array;
    }

    //Insertar un modelo
    public function InsertGamaModelo(){
        $array = array();
        $ds = new gamaModelosDB();

        $this->_gModeloId = $ds->InsertGamaModeloDB($this->getParams(false));
    }
    //Actualizar un modelo
    public function ActGamaModelo(){
        $array = array();
        $ds = new gamaModelosDB();

        return $ds->ActGamaModeloDB($this->getParams(true));
    }

    public function ActCampoGamaModelo($campo, $valor, $idModelo){
        $array = array();
        $ds = new gamaModelosDB();
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $idModelo;
        return $ds->ActCampoGamaModeloDB($param);
    }
    //Parametros
    private function getParams($act)
    {
        $dTZoneObj = obtDateTimeZone();                
        $this->_fechaCreacion = $dTZoneObj->fechaHora;

        $param[0] = $this->_modelo;
        $param[1] = $this->_anio;
        $param[2] = $this->_imagen;
        $param[3] = $this->_activo;                
        $param[4] = $this->_eslogan;              
        $param[5] = $this->_camion;
        $param[6] = $this->_nuevo;
        $param[7] = $this->_mostrarAnio;
        $param[8] = $this->_fechaCreacion; 
        

        //continua si es actualizacion
        if($act){
            $param[8] = $this->_gModeloId;
        }

        return $param;
    }

    //Activar y desactivar
    public function ActivaDesactivaGModelo($valor){
        $ds = new gamaModelosDB();
        return $ds->ActivaDesactivaGModeloDB($valor);        
    }


    //Grid gama de modelos
    public function GetGamaModelosGrid($nombreModeloB = "", $anioModeloB = "", $activoModeloB = ""){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new gamaModelosDB();
        $ds = $uDB->gamaModelosDataSet($ds, $nombreModeloB, $anioModeloB, $activoModeloB);
        $grid = new KoolGrid("gamaModelosGrid");

        $this->defineGridForm($grid, $ds);
        $this->defineColumnForm($grid, "gModeloId", "ID", false, true);
        $this->defineColumnForm($grid, "modelo", "Modelo", true, false, 0);
        $this->defineColumnForm($grid, "anio", "A&ntilde;o", true, false, 0);
        $this->defineColumnForm($grid, "activo", "Activo", true, false, 0);
        $this->defineColumnEditForm($grid);

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
        $grid->Width = "760px";

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
        $grid->MasterTable->ShowFunctionPanel = true;        
        $grid->MasterTable->FunctionPanel->ShowInsertButton = false;
        //Insert Settings
        $grid->MasterTable->InsertSettings->Mode = "Form";
        $grid->MasterTable->EditSettings->Mode = "Form";
        $grid->MasterTable->InsertSettings->ColumnNumber = 1;
        // $grid->ClientSettings->ClientEvents["OnRowConfirmEdit"] = "Handle_OnRowConfirmEdit";
    }
    //define the grid columns
    private function defineColumnForm($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0)
    {   
        if($name_field == 'activo') {
            $column = new GridBooleanColumn();
            $column->UseCheckBox = true;            
        }else{
            $column = new gridboundcolumn();
        } 
    
        if($validator > 0)
            $column->addvalidator($this->GetValidator ($validator));

        $column->Visible = $visible;
        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $grid->MasterTable->AddColumn($column);
    }
    private function GetValidator($type){
        switch ($type) {
            case 1: //required
                $validator = new RequiredFieldValidator();
                $validator->ErrorMessage = "Campo requerido";
                return $validator;
                break;
        }
    }
    private function defineColumnEditForm($grid)
    {
        // <a class="kgrLinkEdit" onclick="grid_editar({gModeloId},"gamamodelos")" href="javascript:void 0" title="Editar"></a>
        $column = new GridCustomColumn();
        $column->ItemTemplate = ' 
                <a class="kgrLinkEdit" href="regGamaModelo.php?regId={gModeloId}">Editar</a> |
                <a class="kgrLinkDelete" onclick="eliminar_reg({gModeloId}, \'gama_modelo\')" href="javascript:void 0" title="Eliminar"></a> |
                <a class=" {classInactivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({gModeloId},'."'gamamodelo'".',0)" href="javascript:void(0);" title="desactivar."><img src="../images/icon_delete.png" class="iconoDesactivar" ></a>
                 <a class="{classActivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({gModeloId},'."'gamamodelo'".',1)" href="javascript:void(0);" title="activar."><img src="../images/activar.jpg" class="iconoDesactivar" ></a>
            ';
        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $column->Width = "120px";
        $grid->MasterTable->AddColumn($column);
    }

    public function EliminarGamaModelo($id){        
        $ds = new gamaModelosDB();
        $param[0] = $id;

        return $ds->EliminarGamaModeloDB($param);
    }


    //>>Salvar orden de los modelos
    public function OrdenarModelos($datos){
        $ds = new gamaModelosDB();
        
        return $ds->OrdenarModelosDB($datos);
    }

    //>>Actualizar el numero de orden
    public function ActNumOrdenModelo($nOrden){
        $ds = new gamaModelosDB();
        $param[0] = $nOrden;
        $param[1] = $nOrden;
        return $ds->ActNumOrdenModeloDB($param);
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
                $array[$myRows->gModeloId] = $myRows;
            }          
            mysqli_free_result($result); // Free result set        
        }
        return $array;
    }

}