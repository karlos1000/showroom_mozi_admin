<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catGaleriasDB.php';

class catGaleriasObj {
    private $_galeriaId = 0;    
    private $_nombre = '';
    private $_activo = '';    
    private $_fechaCreacion = '0000-00-00 00:00:00';    
    // Extras
    private $_classInactivo = '';

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion del catalogo de galerias
    public function ObtTodosCatGalerias(){
        $array = array();
        $ds = new catGaleriasDB();

        $result = $ds->ObtTodosCatGaleriasDB();
        $array = $this->arrDatos($result, "galeriaId");

        return $array;
    }


     public function ObtGaleriaPorId($galeriaId){        
        $ds = new catGaleriasDB();

        $result = $ds->ObtGaleriaPorIdDB($galeriaId);
        $this->setDatos($result);
    }

    //Agregar galeria
    public function agregarGaleria(){        
        $ds = new catGaleriasDB();
        $this->_galeriaId = $ds->agregarGaleriaDB($this->_nombre, $this->_activo);
        return $this->_galeriaId;
    }


    //Grid roles
    public function GetCatGaleriasGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new catGaleriasDB();
        $ds = $uDB->catGaleriasDataSet($ds);
        $grid = new KoolGrid("catGaleriasGrid");

        $this->defineGridForm($grid, $ds);
        $this->defineColumnForm($grid, "galeriaId", "ID", false, true);
        $this->defineColumnForm($grid, "nombre", "Nombre", true, false, 1);
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
            $column->addvalidator($this->GetValidatorCatGalerias ($validator));

        $column->Visible = $visible;
        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $grid->MasterTable->AddColumn($column);
    }
    private function GetValidatorCatGalerias($type){
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
        /*
        $column = new grideditdeletecolumn();
        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $grid->MasterTable->AddColumn($column);
        */
        $column = new GridCustomColumn();
        $column->ItemTemplate = '
              <a class="kgrLinkEdit {classInactivo} " onclick="grid_edit(this)" href="javascript:void 0" title="Editar"></a>'.
              '<a class="kgrLinkDelete {classInactivo} " onclick="grid_delete(this)" href="javascript:void 0" title="Eliminar"></a>'
              ;
        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $column->Width = "120px";
        $grid->MasterTable->AddColumn($column);
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

}