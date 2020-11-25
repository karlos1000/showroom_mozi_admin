<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/versionZonasDB.php';
include_once  $dirname.'/brules/catGaleriasObj.php';
include_once  $dirname.'/brules/versionColoresObj.php';
include_once  $dirname.'/brules/versionZonasActivasObj.php';

class versionZonasObj {
    private $_zonaVersId = 0;
    private $_coloresVersId = 0;    
    private $_galeriaId = 0;    
    private $_activo = '';
    private $_fechaCreacion = '0000-00-00 00:00:00';    

    // extras
    private $_classActivo = '';
    private $_classInactivo = '';
    private $_conceptoGaleria = '';
    private $_idsZonaVers = 0;
    

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }
    
    //version zona por id
    public function versionZonaPorId($id)
    {
        $ds = new versionZonasDB();

        $result = $ds->versionZonaPorIdDB($id);
        $this->setDatos($result);
    }

    public function versionZonaPorColorGaleria($coloresVersId, $galeriaId)
    {
        $ds = new versionZonasDB();

        $result = $ds->versionZonaPorColorGaleriaDB($coloresVersId, $galeriaId);
        $this->setDatos($result);
    }

    //Obtener coleccion de versiones zonas
    public function ObtVersionesZonas($opcObj=false, $coloresVersId = ""){
        $array = array();
        $ds = new versionZonasDB();
        $result = $ds->ObtVersionesZonasDB($coloresVersId);

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "zonaVersId");
        }

        return $array;
    }

    public function ActCampoVersionZona($campo, $valor, $zonaVersId){
        $array = array();
        $ds = new versionZonasDB();
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $zonaVersId;

        return $ds->ActCampoVersionZonaDB($param);
    }

     //Insertar un version zona
    public function InsertVersionZona(){
        $array = array();
        $ds = new versionZonasDB();

        $this->_zonaVersId = $ds->InsertVersionZonaDB($this->getParams(false));
    }

    //Actualizar una version zona
    public function ActVersionZona(){
        $array = array();
        $ds = new versionZonasDB();

        return $ds->ActVersionZonaDB($this->getParams(true));
    }


    //Parametros
    private function getParams($act)
    {
        $dTZoneObj = obtDateTimeZone();                
        $this->_fechaCreacion = $dTZoneObj->fechaHora;

        $param[0] = $this->_coloresVersId;
        $param[1] = $this->_galeriaId;
        $param[2] = $this->_fechaCreacion; 

        //continua si es actualizacion
        if($act){
            $param[2] = $this->_zonaVersId;
        }

        return $param;
    }

    public function EliminarVersionZona($zonaVersId){
        $array = array();
        $ds = new versionZonasDB();
        $param[0] = $zonaVersId;

        return $ds->EliminarVersionZonaDB($param);
    }


    //Grid version zonas
    public function GetVersionZonaGrid($coloresVersId = ""){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        // $ds = new MySQLiDataSource($dbConn);
        $uDB = new versionZonasDB();


        $zonas = $this->ObtVersionesZonas(false, $coloresVersId);
        $arrResult = array();
        $versionColoresObj = new versionColoresObj();
        $versionZonasActivasObj = new versionZonasActivasObj();
        foreach ($zonas as $zona) {
            $versionColoresObj->versionColorPorId($zona->coloresVersId);
            $zonasActivas = $versionZonasActivasObj->ObtVersionesZonasActivas(false, $zona->zonaVersId);            

            foreach ($zonasActivas as $zonaActiva) {
                $imagenes = (array)json_decode($zonaActiva->imagenes);
                $arrResult[] = array(
                "activaZonaId"=>$zonaActiva->activaZonaId,
                "zonaVersId"=>$zona->zonaVersId,
                "coloresVersId"=>$zona->coloresVersId,
                "galeriaId"=>$zona->galeriaId,
                "activo"=>$zona->activo,
                "fechaCreacion"=>$zona->fechaCreacion,
                "contImagenes"=>count($imagenes),
                "color"=>$versionColoresObj->color,
                "classActivo"=>$zona->classActivo,
                "classInactivo"=>$zona->classInactivo,
                "archivoMostrar"=>($zona->galeriaId==20)?'galeria360':'galeriamultiple',
                );
            }

            
        }

        $ds = new ArrayDataSource($arrResult);

        // $ds = $uDB->versionZonaDataSet($ds, $coloresVersId);
        $grid = new KoolGrid("versionZonaGrid");

        $this->defineGridForm($grid, $ds);
        $this->defineColumnForm($grid, "activaZonaId", "Id Zona Activa", false, true);
        $this->defineColumnForm($grid, "zonaVersId", "ID", false, true);
        $this->defineColumnForm($grid, "color", "Color", true, false, 1);
        $this->defineColumnForm($grid, "galeriaId", "Galeria", true, false, 1);   
        $this->defineColumnForm($grid, "contImagenes", "Imagenes", true, false, 1);           
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
        elseif ($name_field == 'galeriaId') {
            $galeriasObj = new catGaleriasObj();
            $galerias = $galeriasObj->ObtTodosCatGalerias();

            $column = new GridDropDownColumn();
            // $column->AddItem('-- Seleccionar --',NULL);
            foreach($galerias as $galeria)
            {
                $column->AddItem($galeria->nombre, $galeria->galeriaId);
            }
            $column->AllowFiltering = true;
            $column->FilterOptions  = array("No_Filter","Equal","Contain");//Only show 3 chosen options.
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
        //<a class="kgrLinkEdit" href="galeriaindividual.php?zonaVersId={zonaVersId}" target="_blank">Editar</a>
        $column = new GridCustomColumn();
        $column->ItemTemplate = '            
                <a class="kgrLinkEdit" href="{archivoMostrar}.php?activazonaid={activaZonaId}" target="_blank">Editar</a> 
                <a class="kgrLinkDelete" onclick="eliminarZonaVersion({zonaVersId})" href="javascript:void 0" title="Eliminar"></a>
                |
                <a class=" {classInactivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({zonaVersId},'."'versionzona'".',0)" href="javascript:void(0);" title="desactivar."><img src="../images/icon_delete.png" class="iconoDesactivar" ></a>

                 <a class="{classActivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({zonaVersId},'."'versionzona'".',1)" href="javascript:void(0);" title="activar."><img src="../images/activar.jpg" class="iconoDesactivar" ></a>
            ';
        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $column->Width = "120px";
        $grid->MasterTable->AddColumn($column);
    }


    //obtener ids de las zonas por el id de los colores
    public function idsZonasVersPorIdsColor($coloresVersId)
    {
        $ds = new versionZonasDB();
        $result = $ds->idsZonasVersPorIdsColorDB($coloresVersId);
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
                $array[$myRows->zonaVersId] = $myRows;
            }
            mysqli_free_result($result); // Free result set        
        }
        return $array;
    }

}