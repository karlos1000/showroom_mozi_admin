<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catConfiguracionesDB.php';
include_once  $dirname.'/brules/PHPExcel/PHPExcel/IOFactory.php';

class catConfiguracionesObj {
    private $_idConfiguracion = 0;
    private $_nombre = '';
    private $_valor = '';  
    private $_activo = 0;
    private $valor2 = '';

   
    //get y set
    public function __get($name) {             
        return $this->{"_".$name};
    }
    public function __set($name, $value) {        
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion de historial por rango de fecha
    public function ObtTodConfiguraciones($fDel="", $fAl=""){
        $array = array();
        $ds = new catCongiguracionesDB();

        $fDel = ($fDel!="") ?$this->convertDate($fDel) :"";
        $fAl = ($fAl!="") ?$this->convertDate($fAl) :"";

        $result = $ds->ObtTodasConfiguracionesDB($fDel, $fAl);
        $array = $this->arrDatos($result, "idConfiguracion");     

        return $array;            
    }
    
    public function ObtConfiguracionByID($id)
    {
        $DS = new catCongiguracionesDB;

        $result = $DS->ConfiguracionByID($id);
        $this->setDatos($result);
    }

    // Obtener configuraciones por ids //Imp. 08/01/2020
    public function ObtConfiguracionesPorIds($opcObj=false, $activo=-1, $id=""){
        $array = array();
        $ds = new catCongiguracionesDB;

        $result = $ds->ConfiguracionesPorIdsDB($activo, $id);

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "idConfiguracion");     
        }    

        return $array;
    }

    
    //Salvar cuentas por cobrar
    public function GuardarConfiguracion()
    {   
        $objDB = new comunicadosDB();
        $this->_idComunicado = $objDB->insertComunicadoDB($this->getParams());
    }
    
    public function ActualizarConfiguracion()
    {   
        $objDB = new catCongiguracionesDB();
        return $objDB->updateConfiguracionDB($this->getParams());
    }

    private function getParams()
    {
        $param[0] = $this->nombre;
        $param[1] = $this->_valor;
        $param[2] = $this->_idConfiguracion;
        
        
        return $param;
    }

    
     //Grid configuraciones
    public function ObtConfiguracionesGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new catCongiguracionesDB();
        $ds = $uDB->catConfiguracioneDataSet($ds);
        $grid = new KoolGrid("catConfiguracionesGrid");

        $this->defineGridForm($grid, $ds);
        $this->defineColumnForm($grid, "idConfiguracion", "ID", false, true);
        $this->defineColumnForm($grid, "nombre", "Nombre", true, false, 1);
        $this->defineColumnForm($grid, "valor2", "Valor", true, true, 0, "", "350px");
        $this->defineColumnForm($grid, "valor", "Valor", false, false, 1);
        $this->defineColumnForm($grid, "activo", "Activo", true, false, 0);
        $this->defineColumnEditForm($grid);

        //pocess grid
        // $grid->ClientSettings->ClientEvents["OnBeforeRowStartEdit"] = "Handle_OnBeforeRowStartEdit";
        $grid->Process();

        return $grid;
    }

    //Private Functions
    private function defineGridForm($grid, $ds){
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
        // $grid->ClientSettings->ClientEvents["OnRowConfirmEdit"] = "Handle_OnRowConfirmEdit";
    }
    //define the grid columns
    private function defineColumnForm($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0, $field_type="", $width = ""){   
        if($name_field == 'activo') {
            $column = new GridBooleanColumn();
            $column->UseCheckBox = true;
        }elseif($name_field == 'valor') {
            $column = new GridTextAreaColumn();
            // $column->AllowHtmlRender = true;
            $column->BoxHeight = "100px";
        }elseif($name_field == 'activo') {
            $column = new GridBooleanColumn();
            $column->UseCheckBox = true;            
        }        
        else{
            $column = new gridboundcolumn();
        }     

        //Valida requerido
        if($validator > 0){
            $column->addvalidator($this->GetValidatorCatConfig($validator));
        }

        //Tipo de validacion
        if($field_type != ""){
            $column->addvalidator($this->GetValidatorFieldType($field_type));
        }

        $column->Visible = $visible;
        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $column->Width = $width;
        $grid->MasterTable->AddColumn($column);
    }

    private function GetValidatorCatConfig($type){
        switch ($type) {
            case 1: //required
                $validator = new RequiredFieldValidator();
                $validator->ErrorMessage = "Campo requerido";
                return $validator;
                break;
        }
    }

    //valido el tipo del campo
    private function GetValidatorFieldType($field_type){
        switch ($field_type) {
            case "INT":
                $validatorTmp = new RegularExpressionValidator();
                $validatorTmp->ValidationExpression = "/^([0-9])+$/"; 
                $validatorTmp->ErrorMessage = "Campo tipo entero";
                return $validatorTmp;
            break;
            case "FLOAT":
                $validatorTmp = new RegularExpressionValidator();
                $validatorTmp->ValidationExpression = "/^([.0-9])+$/";
                $validatorTmp->ErrorMessage = "Campo tipo flotante";
                return $validatorTmp;
            break;
            case "EMAIL":
                $validatorTmp = new RegularExpressionValidator();
                $validatorTmp->ValidationExpression = "/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/";
                $validatorTmp->ErrorMessage = "Campo tipo email";
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
        $link = '';

        if($_SESSION['idRol']==1){            
            $link .= '<a class="kgrLinkEdit" onclick="grid_edit(this)" href="javascript:void 0" title="Editar"></a>';
            // $link .= '&nbsp;<a class="kgrLinkDelete" onclick="grid_delete(this)" href="javascript:void 0" title="Eliminar"></a>';            
        }
        if($_SESSION['idRol']==2){            
            $link .= '<a class="kgrLinkEdit" onclick="grid_edit(this)" href="javascript:void 0" title="Editar"></a>';            
        }
        $column->ItemTemplate = $link;

        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $column->Width = "100px";
        $grid->MasterTable->AddColumn($column);
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

    //Obtener coleccion de datos por fetch_object
    private function arrDatosObj($result){
        $array = array();
        $classObj = get_class($this);
        if ($result)
        {               
            while ($myRows = mysqli_fetch_object($result)){            
                $array[$myRows->idConfiguracion] = $myRows;
            }
            mysqli_free_result($result); // Free result set        
        }
        return $array;
    }


    //Convertir fechas dd/mm/yy - yy-mm-dd
    private function convertDate($date){
        list($dd, $mm, $yy) = explode("/", $date);
        $date = $yy.'-'.$mm.'-'.$dd;

        return $date;
    }

    


}