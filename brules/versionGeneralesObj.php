<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/versionGeneralesDB.php';
include_once  $dirname.'/brules/gamaModelosObj.php';

class versionGeneralesObj {
    private $_gralVersId = 0;    
    private $_gModeloId = 0;
    private $_version = '';
    private $_precioInicial = 0;
    private $_caracteristicas = '';
    private $_imagen = '';
    private $_activo = '';
    private $_fechaCreacion = '0000-00-00 00:00:00';        
    private $_sAutomotriz = 0;
    private $_sVida = 0;
    private $_sDesempleo = 0;
    private $_sGarantiaExt = 0; //Imp. 25/11/19
    private $_urlPdf = '';
    private $_codigoVersion = '';
    private $_orden = ''; //Imp. 26/11/19

    // extras
    private $_modeloGM = '';
    private $_anioGM = '';

    // extras
    private $_classActivo = '';
    private $_classInactivo = '';
    private $_idsGralVers = 0;

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }
    
    //version general por id
    public function versionGeneralPorId($id)
    {
        $ds = new versionGeneralesDB();

        $result = $ds->versionGeneralPorIdDB($id);
        $this->setDatos($result);
    }

    //Obtener coleccion de versiones 
    public function ObtVersionesGenerales($opcObj=false, $activo=-1){
        $array = array();
        $ds = new versionGeneralesDB();
        $result = $ds->ObtVersionesGeneralesDB($activo);

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "gralVersId");
        }

        return $array;
    }
    public function ActCampoVersionGral($campo, $valor, $idVersionGral){
        $array = array();
        $ds = new versionGeneralesDB();
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $idVersionGral;
        return $ds->ActCampoVersionGralDB($param);
    }
     //Insertar un modelo
    public function InsertVersionGral(){
        $array = array();
        $ds = new versionGeneralesDB();
        $this->_gralVersId = $ds->InsertVersionGralDB($this->getParams(false));
    }
    //Actualizar una version general
    public function ActVersionGral(){
        $array = array();
        $ds = new versionGeneralesDB();
        return $ds->ActVersionGralDB($this->getParams(true));
    }
    
    //Parametros
    private function getParams($act)
    {
        $dTZoneObj = obtDateTimeZone();                
        $this->_fechaCreacion = $dTZoneObj->fechaHora;
        $param[0] = $this->_gModeloId;
        $param[1] = $this->_version;
        $param[2] = $this->_precioInicial;
        $param[3] = $this->_caracteristicas;   
        $param[4] = $this->_imagen;
        $param[5] = $this->_urlPdf;
        $param[6] = $this->_sAutomotriz;
        $param[7] = $this->_sVida;
        $param[8] = $this->_sDesempleo;
        $param[9] = $this->_codigoVersion;
        $param[10] = $this->_sGarantiaExt;
        $param[11] = $this->_fechaCreacion; 
        //continua si es actualizacion
        if($act){
            $param[11] = $this->_gralVersId;
        }
        return $param;
    }

    //Grid version generales
    public function GetVersionGeneralGrid($modeloVersionB = "", $nombreVersionB = "", $activoVersionB = ""){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new versionGeneralesDB();
        $ds = $uDB->versionGeneralDataSet($ds, $modeloVersionB, $nombreVersionB, $activoVersionB);
        $grid = new KoolGrid("versionGeneralGrid");

        $this->defineGridForm($grid, $ds);
        $this->defineColumnForm($grid, "gralVersId", "ID", true, true);
        $this->defineColumnForm($grid, "gModeloId", "Modelo", true, false, 1);
        $this->defineColumnForm($grid, "version", "Versi&oacute;n", true, false, 1);            
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
        elseif ($name_field == 'gModeloId') {
            $modelosObj = new gamaModelosObj();
            $modelos = $modelosObj->ObtTodosGamaModelos();
            $column = new GridDropDownColumn();
            $column->AddItem('-- Seleccionar --',"");
            foreach($modelos as $modelo)
            {
                $column->AddItem($modelo->modelo."-".$modelo->anio,$modelo->gModeloId);
            }
            // $column->AllowFiltering = true;
            // $column->FilterOptions  = array("No_Filter","Equal","Contain");//Only show 3 chosen options.
            // $column->FilterOptions  = array("Equal");//Only show 3 chosen options.
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
        
        if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){
            $link .= ' <a class="kgrLinkEdit" href="version.php?verId={gralVersId}">Editar</a>';
            $link .= ' &nbsp;<a class="kgrLinkDelete" onclick="eliminar_reg({gralVersId}, \'version_modelo\')" href="javascript:void 0" title="Eliminar"></a>';        
            $link .= ' &nbsp;<a class=" {classInactivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({gralVersId},'."'versiongral'".',0)" href="javascript:void(0);" title="desactivar."><img src="../images/icon_delete.png" class="iconoDesactivar" ></a>
                       <a class="{classActivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({gralVersId},'."'versiongral'".',1)" href="javascript:void(0);" title="activar."><img src="../images/activar.jpg" class="iconoDesactivar" ></a>';
        }
        if($_SESSION['idRol']==4){
            $link .= ' <a class="kgrLinkEdit" href="versioneditor.php?verId={gralVersId}">Editar</a>';
        }

        $column->ItemTemplate = $link;        
        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $column->Width = "120px";
        $grid->MasterTable->AddColumn($column);
    }

    //obtener ids de las versiones por el id del modelo
    public function idsVersGeneralPorGModeloId($gModeloId)
    {
        $ds = new versionGeneralesDB();
        $result = $ds->idsVersGeneralPorGModeloIdDB($gModeloId);
        $this->setDatos($result);         
    }

    public function EliminarVersionGeneral($id){        
        $ds = new versionGeneralesDB();
        $param[0] = $id;

        return $ds->EliminarVersionGeneralDB($param);
    }

    //>>Salvar orden de las versiones
    public function OrdenarVersiones($datos){
        $ds = new versionGeneralesDB();
        
        return $ds->OrdenarVersionesDB($datos);
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
                $array[$myRows->gralVersId] = $myRows;
            }
            mysqli_free_result($result); // Free result set        
        }
        return $array;
    }

}