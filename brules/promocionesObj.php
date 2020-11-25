<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/promocionesDB.php';

class promocionesObj {
    private $_promocionId = 0;
    private $_gralVersId = 0;    
    private $_concepto = '';
    private $_caracteristicas = '';
    private $_activo = '';
    private $_preconfigurado = 0;
    private $_fechaCreacion = '0000-00-00 00:00:00';    
    // extras
    private $_classActivo = '';
    private $_classInactivo = '';
    // private $_idsPromosVers = 0;
    private $_promocionIdPadre = 0;
    

    //get y set
    public function __get($name) {        
        return $this->{"_".$name};
    }
    public function __set($name, $value) {                
        $this->{"_".$name} = $value;
    }
    
    //version promocion por id
    public function versionPromocionPorId($id)
    {
        $ds = new promocionesDB();

        $result = $ds->versionPromocionPorIdDB($id);
        $this->setDatos($result);
    }

    //Obtener coleccion de versiones promociones
    public function ObtVersionesPromociones($opcObj=false, $idVersionGral = "", $activo = "", $preconfigurado=0){
        $array = array();
        $ds = new promocionesDB();
        $result = $ds->ObtVersionesPromocionesDB($idVersionGral, $activo, $preconfigurado);

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "promocionId");
        }

        return $array;
    }
    public function ActCampoVersionPromocion($campo, $valor, $promocionId){
        $array = array();
        $ds = new promocionesDB();
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $promocionId;
        return $ds->ActCampoVersionPromocionDB($param);
    }
    //Insertar un version promocion
    public function InsertVersionPromocion(){
        $array = array();
        $ds = new promocionesDB();
        $this->_promocionId = $ds->InsertVersionPromocionDB($this->getParams(false));
    }
    //Actualizar una version promocion
    public function ActVersionPromocion(){
        $array = array();
        $ds = new promocionesDB();
        return $ds->ActVersionPromocionDB($this->getParams(true));
    }
    //Parametros
    private function getParams($act)
    {
        $dTZoneObj = obtDateTimeZone();                
        $this->_fechaCreacion = $dTZoneObj->fechaHora;
        $param[0] = $this->_gralVersId;
        $param[1] = $this->_concepto;
        $param[2] = $this->_caracteristicas;
        $param[3] = $this->_preconfigurado; 
        $param[4] = $this->_promocionIdPadre;
        $param[5] = $this->_fechaCreacion; 
        //continua si es actualizacion
        if($act){
            $param[5] = $this->_promocionId;
        }
        return $param;
    }
    //Grid version promociones
    public function GetVersionPromocionGrid($gralVersId = "", $preconfigurado=0){        
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);

        $uDB = new promocionesDB();
        $ds = $uDB->versionPromocionDataSet($ds, $gralVersId, $preconfigurado);
        $grid = new KoolGrid("versionPromocionGrid");
        $this->defineGridForm($grid, $ds);
        $this->defineColumnForm($grid, "promocionId", "ID", false, true);
        $this->defineColumnForm($grid, "concepto", "Concepto", true, false, 1);        
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
        }
        // elseif ($name_field == 'requisitoId') {
        //     $conceptosObj = new catConceptoRequisitosObj();
        //     $conceptos = $conceptosObj->ObtTodosCatConceptoRequisitos();
        //     $column = new GridDropDownColumn();
        //     // $column->AddItem('-- Seleccionar --',NULL);
        //     foreach($conceptos as $concepto)
        //     {
        //         $column->AddItem($concepto->requisito,$concepto->requisitoId);
        //     }
        //     $column->AllowFiltering = true;
        //     $column->FilterOptions  = array("No_Filter","Equal","Contain");//Only show 3 chosen options.
        // }
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
        $column = new GridCustomColumn();        
        // <a class="kgrLinkDelete" onclick="grid_delete(this)" href="javascript:void(0)" title="Eliminar"></a>
        $column->ItemTemplate = '            
                <a class="kgrLinkEdit btnEditarVersionReq" onclick="mostrarFancyAgregarPromocion({promocionId}, \'{concepto}\')" href="#fancyVersionPromocion">Editar</a> 
                <a class="kgrLinkDelete" onclick="eliminar_reg({promocionId}, \'promociones\')" href="javascript:void(0)" title="Eliminar"></a>
                |
                <a class=" {classInactivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({promocionId},'."'versionpromocion'".',0)" href="javascript:void(0);" title="desactivar."><img src="../images/icon_delete.png" class="iconoDesactivar" ></a>
                 <a class="{classActivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({promocionId},'."'versionpromocion'".',1)" href="javascript:void(0);" title="activar."><img src="../images/activar.jpg" class="iconoDesactivar" ></a>
            ';
        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $column->Width = "120px";
        $grid->MasterTable->AddColumn($column);
    }
    

    //obtener ids de las promociones por el id de la version
    public function idsPromosVersPorIdsVersion($gralVersId)
    {
        $ds = new promocionesDB();
        $result = $ds->idsPromosVersPorIdsVersionDB($gralVersId);
        $this->setDatos($result);         
    }

    public function EliminarVersionPromocion($id){        
        $ds = new promocionesDB();
        $param[0] = $id;

        return $ds->EliminarVersionPromocionDB($param);
    }

    //obtener ids version promo por id padre
    public function versionPromoPorIdPadre($idPadre){
        $array = array();
        $ds = new promocionesDB();

        $result = $ds->versionPromoPorIdPadreDB($idPadre);        
        $array = (array)$this->arrDatosObj($result);
        
        return $array;
    }

    //Eliminar versiones por el id padre
    public function EliminarVersionPromocionPorIdPadre($idPadre){        
        $ds = new promocionesDB();
        $param[0] = $idPadre;

        return $ds->EliminarVersionPromocionPorIdPadreDB($param);
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
                $array[$myRows->promocionId] = $myRows;
            }
            mysqli_free_result($result); // Free result set        
        }
        return $array;
    }

}