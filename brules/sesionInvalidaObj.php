<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/sesionInvalidaDB.php';
include_once  $dirname.'/brules/PHPExcel/PHPExcel/IOFactory.php';
ini_set('xdebug.max_nesting_level', 200);

class sesionInvalidaObj {
    private $_idSesionInvalida = 0;
    private $_email = '';
    private $_password = '';
    private $_fechaCreacion = '0000-00-00 00:00:00';
    private $_numContrato = '';
    
   //get y set
    public function __get($name) {             
        return $this->{"_".$name};
    }
    public function __set($name, $value) {        
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion de historial por rango de fecha
    public function ObtTodosSesionInvalida($fDel="", $fAl=""){
        $array = array();
        $ds = new sesionInvalidaDB();

        $fDel = ($fDel!="") ?$this->convertDate($fDel) :"";
        $fAl = ($fAl!="") ?$this->convertDate($fAl) :"";

        $result = $ds->ObtTodosSesionInvalidaDB($fDel, $fAl);
        $array = $this->arrDatos($result, "idSesionInvalida");     

        return $array;            
    }

    public function GuardarSesionInvalida()
    {       
        $objDB = new sesionInvalidaDB();
        $this->_idSesionInvalida = $objDB->insertSesionInvalidaDB($this->getParams());
    }
    
    private function getParams()
    {
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
        $this->_fechaCreacion = $dateTime;
        $param[0] = $this->_email;
        $param[1] = $this->_password;
        $param[2] = $this->_numContrato;
        $param[3] = $this->_fechaCreacion;
        return $param;
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


    //Convertir fechas dd/mm/yy - yy-mm-dd
    private function convertDate($date){
        list($dd, $mm, $yy) = explode("/", $date);
        $date = $yy.'-'.$mm.'-'.$dd;

        return $date;
    }

    //Grid usuarios
     public function ObtSesionesInvalidasGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $siDB = new sesionInvalidaDB();
        $ds = $siDB->SesionesInvalidasSet($ds);
        $grid = new KoolGrid("sesionInvalidaGrid");

        $this->defineGridSesionInvalida($grid, $ds);
        $this->defineColumnSesionInv($grid, "idSesionInvalida", "idSesionInvalida", false, true);
        $this->defineColumnSesionInv($grid, "fechaCreacion2", "Fecha", true, false, 1);
        $this->defineColumnSesionInv($grid, "numContrato", "Numero contrato", true, false, 1);
        $this->defineColumnSesionInv($grid, "email", "Correo Electronico", true, false, 1);
        $this->defineColumnSesionInv($grid, "password", "Contrase&ntilde;a", true, false, 1);
//        $this->defineColumnEditSesInv($grid);

        //pocess grid
        $grid->Process();

        return $grid;
    }

    //Private Functions
    private function defineGridSesionInvalida($grid, $ds)
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
        $grid->MasterTable->ShowFunctionPanel = false;
        //Insert Settings
	      $grid->MasterTable->InsertSettings->Mode = "Form";
        $grid->MasterTable->EditSettings->Mode = "Form";
	      $grid->MasterTable->InsertSettings->ColumnNumber = 1;
        // $grid->ClientSettings->ClientEvents["OnRowConfirmEdit"] = "Handle_OnRowConfirmEdit";
    }

    //define the grid columns
    private function defineColumnSesionInv($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0)
    {
        if($name_field == 'idRol') {
            $column = new GridDropDownColumn();
            $rolObj = new rolesObj();
            $rolArr = $rolObj->GetAllRoles();
            $column->AddItem('-- Seleccionar --',NULL);
            foreach($rolArr as $rolTmp)
            {
                $column->AddItem($rolTmp->rol,$rolTmp->idRol);
            }
        }
        else{
            $column = new gridboundcolumn();
        }

        if($validator > 0)
            $column->addvalidator($this->GetValidatorSesionInv ($validator));

        $column->Visible = $visible;
        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $grid->MasterTable->AddColumn($column);
    }

    private function GetValidatorSesionInv($type){
        switch ($type) {
            case 1: //required
                $validator = new RequiredFieldValidator();
                $validator->ErrorMessage = "Campo requerido";
                return $validator;
                break;
        }
    }

    private function defineColumnEditSesInv($grid)
    {
        $column = new grideditdeletecolumn();
        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $grid->MasterTable->AddColumn($column);
    }
    


}
