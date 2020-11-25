<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/inventarioPermisosDB.php';

class inventarioPermisosObj {
    private $_inventarioPermisoId = 0;    
    private $_nombre = '';
    private $_alias = '';
    private $_activo = '';    
    private $_fechaCreacion = '0000-00-00 00:00:00';        
    

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //Obtener coleccion
     //Obtener coleccion de modelos
    public function ObtInventarioPermisos($opcObj=false, $activo=-1){
        $array = array();
        $ds = new inventarioPermisosDB();
        $result = $ds->ObtInventarioPermisosDB($activo);

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "inventarioPermisoId");
        }

        return $array;
    }

    //Grid roles
    public function ObtInventarioPermisosGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new inventarioPermisosDB();
        $ds = $uDB->inventarioPermisosDataSet($ds);
        $grid = new KoolGrid("inventarioPermisosGrid");

        $this->defineGridForm($grid, $ds);
        $this->defineColumnForm($grid, "inventarioPermisoId", "ID", false, true);
        $this->defineColumnForm($grid, "nombre", "Nombre", true, true, 0);
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
        $column = new GridCustomColumn();
        $link = '';
        $link .= ' <a class="kgrLinkEdit " onclick="grid_edit(this)" href="javascript:void 0" title="Editar"></a>';
        // $link .= ' <a class="kgrLinkDelete {classInactivo} " onclick="grid_delete(this)" href="javascript:void 0" title="Eliminar"></a>';
        $column->ItemTemplate = $link;
         
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

    //Obtener coleccion de datos por fetch_object
    private function arrDatosObj($result){
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