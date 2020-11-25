<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/versionColoresDB.php';

class versionColoresObj {
    private $_coloresVersId = 0;
    private $_gralVersId = 0;    
    private $_color = '';
    private $_imagenAuto = '';
    private $_imagenColor = 0;    
    private $_activo = '';
    private $_porDefecto = 0;
    private $_fechaCreacion = '0000-00-00 00:00:00';    
    
    // extras
    private $_classActivo = '';
    private $_classInactivo = '';
    private $_classPorDefecto = '';
    private $_idsColoresVers = 0;


    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }
    
    //version color por id
    public function versionColorPorId($id)
    {
        $ds = new versionColoresDB();

        $result = $ds->versionColorPorIdDB($id);
        $this->setDatos($result);
    }

    //Obtener coleccion de colores
    public function ObtVersionesColores($opcObj=false, $idVersionGral = "", $activo = ""){
        $array = array();
        $ds = new versionColoresDB();
        $result = $ds->ObtVersionesColoresDB($idVersionGral, $activo);

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "coloresVersId");
        }

        return $array;
    }
    public function ActCampoVersionColor($campo, $valor, $idVersionColor){
        $array = array();
        $ds = new versionColoresDB();
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $idVersionColor;
        return $ds->ActCampoVersionColorDB($param);
    }
    //Insertar un version color
    public function InsertVersionColor(){
        $array = array();
        $ds = new versionColoresDB();
        $this->_coloresVersId = $ds->InsertVersionColorDB($this->getParams(false));
    }
    //Actualizar una version requisito
    public function ActVersionColor(){
        $array = array();
        $ds = new versionColoresDB();
        return $ds->ActVersionColorDB($this->getParams(true));
    }
    public function eliminarVersionColor($coloresVersId){
        $array = array();
        $ds = new versionColoresDB();
        $param[0] = $coloresVersId;
        return $ds->eliminarVersionColorDB($param);
    }
    //Desactivar por el id de la version
    public function desactivarPorGralVers($campo, $gralVersId){
        $array = array();
        $ds = new versionColoresDB();
        $param[0] = $campo;        
        $param[1] = 0;     
        $param[2] = $gralVersId;
        return $ds->desactivarPorGralVersDB($param);
    }


    //Parametros
    private function getParams($act)
    {
        $dTZoneObj = obtDateTimeZone();                
        $this->_fechaCreacion = $dTZoneObj->fechaHora;
        $param[0] = $this->_gralVersId;
        $param[1] = $this->_color;
        $param[2] = $this->_imagenAuto;
        $param[3] = $this->_imagenColor;
        $param[4] = $this->_fechaCreacion; 
        //continua si es actualizacion
        if($act){
            $param[4] = $this->_coloresVersId;
        }
        return $param;
    }
    //Grid version colores
    public function GetVersionColorGrid($gralVersId = ""){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new versionColoresDB();
        $ds = $uDB->versionColorDataSet($ds, $gralVersId);
        $grid = new KoolGrid("versionColorGrid");
        $this->defineGridForm($grid, $ds);
        $this->defineColumnForm($grid, "coloresVersId", "ID", false, true);
        $this->defineColumnForm($grid, "color", "Color", true, false, 1);
        $this->defineColumnForm($grid, "imagenAuto", "Imagen Auto", true, false, 1);
        $this->defineColumnForm($grid, "imagenColor", "Imagen Color", true, false, 1);
        // $this->defineColumnForm($grid, "color", "Color", true, false, 1);            
        $this->defineColumnForm($grid, "activo", "Activo", true, false, 0);
        $this->defineColumnForm($grid, "porDefecto", "Por Defecto", true, false, 0);
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
        }
        elseif ($name_field == 'colorId') {
            $conceptosObj = new catConceptoColoresObj();
            $conceptos = $conceptosObj->ObtTodosCatConceptoColores();
            $column = new GridDropDownColumn();
            // $column->AddItem('-- Seleccionar --',NULL);
            foreach($conceptos as $concepto)
            {
                $column->AddItem($concepto->color,$concepto->colorId);
            }
            $column->AllowFiltering = true;
            $column->FilterOptions  = array("No_Filter","Equal","Contain");//Only show 3 chosen options.
        }
        elseif ($name_field == 'imagenAuto') {
            $column = new GridCustomColumn();
            $column->ItemTemplate = '<img src="{imagenAuto}" class="imgGrid">';
        }
        elseif ($name_field == 'imagenColor') {
            $column = new GridCustomColumn();
            $column->ItemTemplate = '<img src="{imagenColor}" class="imgGrid">';
        }
        elseif ($name_field == 'porDefecto') {
            $column = new GridCustomColumn();
            $column->Align = "center";
            $column->ItemTemplate = '            
                <input type="checkbox" onclick="selPorDefecto({coloresVersId})" {classPorDefecto}>
            ';   
        }        
        else{
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
            case 2:
                $validator = new RegularExpressionValidator();
                $validator->ValidationExpression = "/^([0-9])+$/"; // Only accept integer.
                $validator->ErrorMessage = "Escriba un valor numerico";
                return $validator;
                break;
        }
    }
    private function defineColumnEditForm($grid)
    {
        // $column = new grideditdeletecolumn();
        // $column->Align = "center";
        // $column->HeaderText = "Acciones";
        // $grid->MasterTable->AddColumn($column);
        $column = new GridCustomColumn();
        $column->ItemTemplate = '            
                <a class="kgrLinkEdit btnEditarVersionColor" onclick="mostrarFancyAgregarColor({coloresVersId}, \'{color}\', \'{imagenAuto}\', \'{imagenColor}\')" href="#fancyVersionColor" title="Editar">Editar</a>
                <a class="kgrLinkDelete" onclick="eliminarColorVersion({coloresVersId})" href="javascript:void 0" title="Eliminar"></a>
                 |
                <a class=" {classInactivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({coloresVersId},'."'versioncolor'".',0)" href="javascript:void(0);" title="Desactivar"><img src="../images/icon_delete.png" class="iconoDesactivar" ></a>
                 <a class="{classActivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({coloresVersId},'."'versioncolor'".',1)" href="javascript:void(0);" title="Activar"><img src="../images/activar.jpg" class="iconoDesactivar" ></a>
            ';
        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $column->Width = "120px";
        $grid->MasterTable->AddColumn($column);
    }
    

    //obtener ids de los colores por el id de la version
    public function idsColoresVersPorIdsVersion($gralVersId)
    {
        $ds = new versionColoresDB();
        $result = $ds->idsColoresVersPorIdsVersionDB($gralVersId);
        $this->setDatos($result);         
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
                $array[$myRows->coloresVersId] = $myRows;
            }
            mysqli_free_result($result); // Free result set        
        }
        return $array;
    }

}