<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catEstadosDB.php';
include_once  $dirname.'/brules/PHPExcel/PHPExcel/IOFactory.php';

class catEstadosObj {
    private $_idEstado = 0;
    private $_estado = '';
    private $_clave = '';
    private $_pais = 'MX';
//    private $_fechaCreacion = '0000-00-00 00:00:00';
//    private $_fechaCreacion2 = '0000-00-00 00:00:00';
    
    //get y set
    public function __get($name) {             
        return $this->{"_".$name};
    }
    public function __set($name, $value) {        
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion de historial por rango de fecha
    public function ObtTodosEstados(){
        $array = array();
        $ds = new catEstadosDB();
        
        $result = $ds->ObtTodosEstadosDB();
        $array = $this->arrDatos($result, "idEstado");     

        return $array;            
    }
    
    public function ObtEstadoByID($id)
    {
        $DS = new catEstadosDB();
        $result = $DS->EstadoByID($id);     
        $this->setDatos($result);
    }
    
     //Salvar cuentas por cobrar
    public function GuardarEstado()
    {       
        $objDB = new catEstadosDB();
        $this->_idEstado = $objDB->insertEstadoDB($this->getParams());
    }
    
    
    public function ActualizarCampo($campo, $valor, $id)
    {   
	$param[0] = $campo;
	$param[1] = $valor;
	$param[2] = $id;
        $objDB = new catEstadosDB();
        $resAct = $objDB->updateEstadoDB($param);
        return $resAct;
    }

    private function getParams()
    {
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
        $this->_fechaCreacion = $dateTime;
        
        $param[0] = $this->_estado;        
        $param[1] = $this->_clave;
        $param[2] = $this->_pais;
        
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
     public function ObtEstadoGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $siDB = new catProcesosDB();
        
       
        $ds = $siDB->ProcesosSet();
        $grid = new KoolGrid("procesosGrid");

        $this->defineGridComunicado($grid, $ds);
        $this->defineColumnComunicado($grid, "idProceso", "idProceso", false, true);
        $this->defineColumnComunicado($grid, "nombreProceso", "Nombre", true, false, 1);
        
        $this->defineColumnEditComunicado($grid);

        //pocess grid
        $grid->Process();

        return $grid;
    }

    //Private Functions
    private function defineGridComunicado($grid, $ds)
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
    private function defineColumnComunicado($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0)
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
            $column->addvalidator($this->GetValidatorComunicado ($validator));

        $column->Visible = $visible;
        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $grid->MasterTable->AddColumn($column);
    }

    private function GetValidatorComunicado($type){
        switch ($type) {
            case 1: //required
                $validator = new RequiredFieldValidator();
                $validator->ErrorMessage = "Campo requerido";
                return $validator;
                break;
        }
    }

    private function defineColumnEditComunicado($grid)
    {
        //<a class="kgrLinkEdit" onclick="grid_edit(this)" href="javascript:void 0"></a>
        //<a class="kgrLinkDelete" onclick="grid_delete(this)" href="javascript:void 0" ></a>
        $column = new GridCustomColumn();
        $column->ItemTemplate = '<a class="kgrLinkEdit" href="comunicado.php?id={idComunicado}"></a>';
        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $column->Width = "50px";
        $grid->MasterTable->AddColumn($column);
    }
    


}
