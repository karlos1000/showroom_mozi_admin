<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/galeriaModelosDB.php';
include_once  $dirname.'/brules/catGaleriasObj.php';

class galeriaModelosObj {
    private $_galeriaModeloId = 0;
    private $_gModeloId = 0;    
    private $_galeriaId = 0;
    private $_titulo = '';
    private $_descripcion = '';
    private $_imagen = '';
    private $_precio = 0;
    private $_activo = 0;
    private $_fechaCreacion = '0000-00-00 00:00:00';
    // extras
    private $_classActivo = '';
    private $_classInactivo = '';
    private $_galeria = '';

    
    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    
    //galeria modelo por id
    public function galeriaModeloPorId($id)
    {
        $ds = new galeriaModelosDB();

        $result = $ds->galeriaModeloPorIdDB($id);
        $this->setDatos($result);
    }

    //Obtener coleccion de galerias
    public function ObtGaleriasModelo($opcObj=false, $activo="", $gModeloId=""){
        $array = array();
        $ds = new galeriaModelosDB();

        $result = $ds->ObtGaleriasModeloDB($activo, $gModeloId);

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "galeriaModeloId");
        }

        return $array;
    }

    //Insertar una galeria
    public function InsertGaleriaModelo(){
        $array = array();
        $ds = new galeriaModelosDB();

        $this->_galeriaModeloId = $ds->InsertGaleriaModeloDB($this->getParams(false));
    }

    public function ActGaleriaModelo(){
        $array = array();
        $ds = new galeriaModelosDB();

        return $ds->ActGaleriaModeloDB($this->getParams(true));
    }

    public function EliminarGaleriaModelo($galeriaModeloId){
        $array = array();
        $ds = new galeriaModelosDB();
        $param[0] = $galeriaModeloId;

        return $ds->EliminarGaleriaModeloDB($param);
    }

    public function ActCampoGaleriaModelo($campo, $valor, $id){
        $array = array();
        $ds = new galeriaModelosDB();
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;
        return $ds->ActCampoGaleriaModeloDB($param);
    }


    //Parametros
    private function getParams($act)
    {
        $dTZoneObj = obtDateTimeZone();                
        $this->_fechaCreacion = $dTZoneObj->fechaHora;

        $param[0] = $this->_gModeloId;
        $param[1] = $this->_galeriaId;
        $param[2] = $this->_titulo;
        $param[3] = $this->_descripcion;
        $param[4] = $this->_imagen;
        $param[5] = $this->_precio;
        $param[6] = $this->_activo;        
        $param[7] = $this->_fechaCreacion; 

        //continua si es actualizacion
        if($act){
            $param[7] = $this->_galeriaModeloId;
        }

        return $param;
    }
    

    //Grid por modelos
    public function GetGaleriasModeloGrid($gModeloId=""){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $uDB = new galeriaModelosDB();
        $ds = new galeriaModelosObj();
        //Obtener datos                    
        $result = $ds->ObtGaleriasModelo(true, "", $gModeloId);    
    
        $arrResult = array();
        foreach ($result as $elem) {
            //obtener el nombre de la galeria

            $arrResult[] = array(
                "galeriaModeloId"=>$elem->galeriaModeloId,
                "gModeloId"=>$elem->gModeloId,
                "galeriaId"=>$elem->galeriaId,
                "titulo"=>$elem->titulo,
                "descripcion"=>$elem->descripcion,
                "imagen"=>$elem->imagen,                
                "precio"=>(isset($elem->precio))? ($elem->precio=="" || $elem->precio==0)?"":number_format($elem->precio,2) :"",                
                "activo"=>$elem->activo,
                "fechaCreacion"=>$elem->fechaCreacion,
                "classActivo"=>$elem->classActivo,
                "classInactivo"=>$elem->classInactivo,
                );
        }

        // $arrResult = $result;
        $ds = new ArrayDataSource($arrResult);    
        $grid = new KoolGrid("galeriasModeloGrid");

        $this->defineGridForm($grid, $ds);
        $this->defineColumnForm($grid, "galeriaModeloId", "ID", false, true);
        $this->defineColumnForm($grid, "galeriaId", "Galer&iacute;a", true, false, 1);
        $this->defineColumnForm($grid, "titulo", "Titulo", true, false, 1);
        // $this->defineColumnForm($grid, "descripcion", "Descripcion", true, false, 1);   
        $this->defineColumnForm($grid, "imagen", "Imagen", true, false, 1);           
        $this->defineColumnForm($grid, "precio", "Precio $", true, false, 1);
        // $this->defineColumnForm($grid, "activo", "Activo", true, false, 0);
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
            foreach($galerias as $galeria)
            {
                $column->AddItem($galeria->nombre, $galeria->galeriaId);
            }
            // $column->AllowFiltering = true;
            // $column->FilterOptions  = array("No_Filter","Equal","Contain");//Only show 3 chosen options.
        }
        elseif ($name_field == 'imagen') {
            $column = new GridCustomColumn();
            $column->ItemTemplate = '<img src="{imagen}" class="imgGrid">';
        }
        elseif ($name_field == 'descripcion') {
            $column = new GridCustomColumn();
            $column->ItemTemplate = '{descripcion}';
        }
        else{
            $column = new gridboundcolumn();
        } 
    

        // if($validator > 0)
        //     $column->addvalidator($this->GetValidator ($validator));

        $column->Visible = $visible;
        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $grid->MasterTable->AddColumn($column);
    }
    
    private function defineColumnEditForm($grid)
    {        
        $column = new GridCustomColumn();
        $column->ItemTemplate = '            
                <a class="kgrLinkEdit btnEditarImgGaleria" onclick="mostrarFancyEditarImgGaleriaGamaModelo({galeriaModeloId}, {gModeloId}, {galeriaId}, \'{titulo}\', \'{imagen}\', \'{precio}\')" href="#fancyImgGaleria" title="Editar">Editar</a>
                <input type="hidden" id="descripcion_img_{galeriaModeloId}" value="{descripcion}">
                <a class="kgrLinkDelete" onclick="eliminarImgGaleriaModelo({galeriaModeloId})" href="javascript:void 0" title="Eliminar"></a>
                <a class=" {classInactivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({galeriaModeloId},'."'galeriadesdemodelo'".',0)" href="javascript:void(0);" title="desactivar."><img src="../images/icon_delete.png" class="iconoDesactivar" ></a>
                <a class=" {classActivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({galeriaModeloId},'."'galeriadesdemodelo'".',1)" href="javascript:void(0);" title="activar."><img src="../images/activar.jpg" class="iconoDesactivar" ></a>
                <label class="contCheckbox">&nbsp;<input type="checkbox" idCheck="{galeriaModeloId}" value="0" class="selDelMul"><span class="checkmark"></span></label>
            ';
            // <input type="checkbox" idCheck="{galeriaModeloId}" value="0" class="selDelMul">
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
                $array[$myRows->galeriaModeloId] = $myRows;
            }
            mysqli_free_result($result); // Free result set        
        }
        return $array;
    }

}