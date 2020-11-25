<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catAgenciasDB.php';

class catAgenciasObj {
    private $_agenciaId = 0;    
    private $_nombre = '';
    private $_enlaceCot = '';
    private $_activo = '';
    private $_enlaceBank = '';
    private $_fechaCreacion = '0000-00-00 00:00:00'; 
    private $_urlsActivas = 0;
    private $_gtVwsf = "";
    private $_correoGtvwsf = "";
    private $_gtVentas = "";
    private $_correoGtVentas = "";
    private $_gtGral = "";
    private $_correoGtGral = "";
    private $_logo = "";

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

   
    //Obtener coleccion de agencias
    public function ObtTodasAgencias($opcObj=false, $activo = ""){
        $array = array();
        $ds = new catAgenciasDB();
        $result = $ds->ObtTodasAgenciasDB($activo);

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "agenciaId");
        }
        return $array;
    }
    //agencia por id
    public function AgenciaPorId($id){
        $ds = new catAgenciasDB();
        $result = $ds->AgenciaPorIdDB($id);
        $this->setDatos($result);
    }

    
    //Grid de agencias 
    public function GetAgenciaGrid($activo = ""){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new catAgenciasDB();
        $ds = $uDB->agenciaDataSet($ds, $activo);
        $grid = new KoolGrid("agenciaGrid");

        $this->defineGridForm($grid, $ds);
        $this->defineColumnForm($grid, "agenciaId", "ID", false, true);
        $this->defineColumnForm($grid, "nombre", "Nombre", true, false, 1);
        $this->defineColumnForm($grid, "enlaceCot", "Enlace VWFS", true, false, 1);        
        $this->defineColumnForm($grid, "enlaceBank", "Enlace VWFS Bank", true, false, 1);        
        // $this->defineColumnForm($grid, "activo", "Activo", true, false, 0);
        $this->defineColumnForm($grid, "urlsActivas", "Url Activas", true, false, 0);
        $this->defineColumnForm($grid, "gtVwsf", "Gte. VWSF", true, false, 1);
        $this->defineColumnForm($grid, "correoGtvwsf", "Correo VWSF", true, false, 1);
        $this->defineColumnForm($grid, "gtVentas", "Gte. Ventas", true, false, 1);
        $this->defineColumnForm($grid, "correoGtVentas", "Correo Ventas", true, false, 1);
        $this->defineColumnForm($grid, "gtGral", "Gte. General", true, false, 1);
        $this->defineColumnForm($grid, "correoGtGral", "Correo General", true, false, 1);
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
        $grid->AllowScrolling = true;
        // $grid->MasterTable->Height = "350px";
        $grid->MasterTable->ColumnWidth = "130px";
        $grid->AllowResizing = true;

        $grid->MasterTable->DataSource = $ds;
        $grid->MasterTable->AutoGenerateColumns = false;
        $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
        $grid->MasterTable->Pager->ShowPageSize = true;
        $grid->MasterTable->Pager->PageSizeOptions = "10,25,50,100,150";
        //Show Function Panel
        $grid->MasterTable->ShowFunctionPanel = true;
        $grid->MasterTable->FunctionPanel->ShowInsertButton = false;
        $grid->MasterTable->FunctionPanel->ShowRefreshButton = true;

        //Insert Settings
        $grid->MasterTable->InsertSettings->Mode = "Form";
        $grid->MasterTable->EditSettings->Mode = "Form";
        $grid->MasterTable->InsertSettings->ColumnNumber = 1;        
        $grid->ClientSettings->ClientEvents["OnRowConfirmEdit"] = "Handle_OnRowConfirmEdit";
        $grid->ClientSettings->ClientEvents["OnConfirmInsert"] = "Handle_OnConfirmInsert";
    }
    //define the grid columns
    private function defineColumnForm($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0, $field_type="")
    {               
        if($name_field == 'activo') {
            $column = new GridBooleanColumn();
            $column->UseCheckBox = true;            
        }
        elseif($name_field == 'urlsActivas') {
            $column = new GridBooleanColumn();
            $column->UseCheckBox = true;      
            $column->Align = "center";
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
        /*$column = new grideditdeletecolumn();
        $column->ShowDeleteButton = false;
        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $grid->MasterTable->AddColumn($column);    */        

        $column = new GridCustomColumn();
        $link = '';
        $link .= ' <a class="kgrLinkEdit" href="regAgencia.php?regId={agenciaId}">Editar</a>';
        
        $column->ItemTemplate = $link;        
        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $column->Width = "120px";
        $grid->MasterTable->AddColumn($column);
    }
    
    // Implementado el 04/02/20
    //Insertar
    public function InsertAgencia(){
        $array = array();
        $ds = new catAgenciasDB();

        $this->_agenciaId = $ds->InsertAgenciaDB($this->getParams(false));
    }

    //Actualizar
    public function ActAgencia(){
        $array = array();
        $ds = new catAgenciasDB();

        return $ds->ActAgenciaDB($this->getParams(true));
    }

    //Parametros
    private function getParams($act){
        $dTZoneObj = obtDateTimeZone();                
        $this->_fechaCreacion = $dTZoneObj->fechaHora;

        $param[0] = $this->_nombre;
        $param[1] = $this->_enlaceCot;
        $param[2] = $this->_activo;
        $param[3] = $this->_enlaceBank;                
        $param[4] = $this->_urlsActivas;
        $param[5] = $this->_gtVwsf;
        $param[6] = $this->_correoGtvwsf;
        $param[7] = $this->_gtVentas;
        $param[8] = $this->_correoGtVentas;
        $param[9] = $this->_gtGral;
        $param[10] = $this->_correoGtGral;
        $param[11] = $this->_logo;
        $param[12] = $this->_fechaCreacion;         

        //continua si es actualizacion
        if($act){
            $param[12] = $this->_agenciaId;
        }

        return $param;
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
                $array[$myRows->agenciaId] = $myRows;
            }          
            mysqli_free_result($result); // Free result set        
        }
        return $array;
    }

}