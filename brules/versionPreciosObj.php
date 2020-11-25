<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/versionPreciosDB.php';
include_once  $dirname.'/brules/catConceptoPreciosObj.php';
include_once  $dirname.'/brules/catTransmisionesObj.php';

class versionPreciosObj {
    private $_precioVersId = 0;
    private $_gralVersId = 0;    
    private $_concPrecioId = 0;
    private $_precio = 0;
    private $_activo = '';
    private $_transmisionId = 0;
    private $_fechaCreacion = '0000-00-00 00:00:00'; 
    //extras
    private $_classActivo = '';
    private $_classInactivo = '';
    private $_conceptoPrecio = '';
    private $_idsPrecioVers = 0;
    private $_transmision = 0;
    

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }
    
    //version precio por id
    public function versionPrecioPorId($id)
    {
        $ds = new versionPreciosDB();

        $result = $ds->versionPrecioPorIdDB($id);
        $this->setDatos($result);
    }

    //Obtener coleccion de versiones precios
    public function ObtVersionesPrecios($opcObj=false, $idVersionGral = "", $activo = ""){
        $array = array();
        $ds = new versionPreciosDB();
        $result = $ds->ObtVersionesPreciosDB($idVersionGral, $activo);

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "precioVersId");
        }

        return $array;
    }
    public function ActCampoVersionPrecio($campo, $valor, $idVersionPrecio){
        $array = array();
        $ds = new versionPreciosDB();
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $idVersionPrecio;
        return $ds->ActCampoVersionPrecioDB($param);
    }
    //Grid version precios
    public function GetVersionPrecioGrid($gralVersId = "", $conceptoPrecioB = "", $activoPrecioB = ""){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new versionPreciosDB();
        $ds = $uDB->versionPrecioDataSet($ds, $gralVersId, $conceptoPrecioB, $activoPrecioB);
        $grid = new KoolGrid("versionPrecioGrid");
        $this->defineGridForm($grid, $ds);
        $this->defineColumnForm($grid, "precioVersId", "ID", false, true);
        $this->defineColumnForm($grid, "concPrecioId", "Fuente", true, false, 1);        
        $this->defineColumnForm($grid, "activo", "Activo", true, false, 0);            
        $this->defineColumnForm($grid, "transmisionId", "Concepto", true, false, 1);        
        $this->defineColumnForm($grid, "precio", "Precio", true, false, 1, "FLOAT");
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
        //Insert Settings
        $grid->MasterTable->InsertSettings->Mode = "Form";
        $grid->MasterTable->EditSettings->Mode = "Form";
        $grid->MasterTable->InsertSettings->ColumnNumber = 1;
        $grid->ClientSettings->ClientEvents["OnRowConfirmEdit"] = "Handle_OnRowConfirmEdit";
        $grid->ClientSettings->ClientEvents["OnConfirmInsert"] = "Handle_OnConfirmInsert";
        $grid->ClientSettings->ClientEvents["OnRowDelete"] = "Handle_OnRowDelete";
    }
    //define the grid columns
    private function defineColumnForm($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0, $field_type="")
    {   
        if($name_field == 'activo') {
            $column = new GridBooleanColumn();
            $column->UseCheckBox = true;            
        }
        elseif ($name_field == 'concPrecioId') {
            $conceptosObj = new catConceptoPreciosObj();
            $conceptos = $conceptosObj->ObtTodosCatConceptoPrecios();
            $column = new GridDropDownColumn();
            // $column->AddItem('-- Seleccionar --',NULL);
            foreach($conceptos as $concepto)
            {
                $column->AddItem($concepto->concepto,$concepto->concPrecioId);
            }
            $column->AllowFiltering = true;
            $column->FilterOptions  = array("No_Filter","Equal","Contain");//Only show 3 chosen options.
        }
        elseif($name_field == 'transmisionId'){
            $tran = new catTransmisionesObj();
            $objTran = $tran->ObtTodosTransmisiones(false);
            $column = new GridDropDownColumn();
            $column->AddItem('-- Seleccionar --',NULL);
            foreach($objTran as $elemTran)
            {
                $column->AddItem($elemTran->transmision,$elemTran->transmisionId);
            }
            // $column->AllowFiltering = true;
            // $column->FilterOptions  = array("No_Filter","Equal","Contain");//Only show 3 chosen options.
        }
        else{
            $column = new gridboundcolumn();
        } 
        if($validator > 0)
            $column->addvalidator($this->GetValidator ($validator));

        if($field_type != "")
            $column->addvalidator($this->GetValidatorFieldTypeOffice($field_type));

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
    //valido el tipo del campo
    private function GetValidatorFieldTypeOffice($field_type){
        switch ($field_type) {
            case "FLOAT":
                $validatorTmp = new RegularExpressionValidator();
                $validatorTmp->ValidationExpression = "/^([.0-9])+$/";
                $validatorTmp->ErrorMessage = "Campo tipo flotante";
                return $validatorTmp;
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
                <a class="kgrLinkEdit" onclick="grid_edit(this)" href="javascript:void 0">Editar</a> 
                <a class="kgrLinkDelete" onclick="grid_delete(this)" href="javascript:void(0)" title="Eliminar"></a>
                |
                <a class=" {classInactivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({precioVersId},'."'versionprecio'".',0)" href="javascript:void(0);" title="desactivar."><img src="../images/icon_delete.png" class="iconoDesactivar" ></a>
                 <a class="{classActivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({precioVersId},'."'versionprecio'".',1)" href="javascript:void(0);" title="activar."><img src="../images/activar.jpg" class="iconoDesactivar" ></a>
            ';
        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $column->Width = "120px";
        $grid->MasterTable->AddColumn($column);
    }


    //obtener ids de los precios por el id de la version
    public function idsPreciosVersPorIdsVersion($gralVersId)
    {
        $ds = new versionPreciosDB();
        $result = $ds->idsPreciosVersPorIdsVersionDB($gralVersId);
        $this->setDatos($result);         
    }
    
    public function EliminarVersionPrecio($id){        
        $ds = new versionPreciosDB();
        $param[0] = $id;

        return $ds->EliminarVersionPrecioDB($param);
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
                $array[$myRows->precioVersId] = $myRows;
            }
            mysqli_free_result($result); // Free result set        
        }
        return $array;
    }

}