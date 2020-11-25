<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/videoModelosDB.php';

class videoModelosObj {
    private $_videoModeloId = 0;
    private $_gModeloId = 0;        
    private $_titulo = '';
    private $_descripcion = '';
    private $_url = '';    
    private $_activo = 0;
    private $_fechaCreacion = '0000-00-00 00:00:00';
    // extras
    private $_classActivo = '';
    private $_classInactivo = ''; 

    
    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    
    //video modelo por id
    public function videoModeloPorId($id)
    {
        $ds = new videoModelosDB();

        $result = $ds->videoModeloPorIdDB($id);
        $this->setDatos($result);
    }

    //Obtener coleccion de modelos
    public function ObtVideosModelo($opcObj=false, $activo="", $gModeloId=""){
        $array = array();
        $ds = new videoModelosDB();

        $result = $ds->ObtVideosModeloDB($activo, $gModeloId);

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "videoModeloId");
        }

        return $array;
    }

    //Insertar un video
    public function InsertVideoModelo(){
        $array = array();
        $ds = new videoModelosDB();

        $this->_videoModeloId = $ds->InsertVideoModeloDB($this->getParams(false));
    }

    public function ActVideoModelo(){
        $array = array();
        $ds = new videoModelosDB();

        return $ds->ActVideoModeloDB($this->getParams(true));
    }

    public function EliminarVideoModelo($galeriaModeloId){
        $array = array();
        $ds = new videoModelosDB();
        $param[0] = $galeriaModeloId;

        return $ds->EliminarVideoModeloDB($param);
    }

    public function ActCampoVideoModelo($campo, $valor, $id){
        $array = array();
        $ds = new videoModelosDB();
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;
        return $ds->ActCampoVideoModeloDB($param);
    }

    //Parametros
    private function getParams($act)
    {
        $dTZoneObj = obtDateTimeZone();                
        $this->_fechaCreacion = $dTZoneObj->fechaHora;

        $param[0] = $this->_gModeloId;        
        $param[1] = $this->_titulo;
        $param[2] = $this->_descripcion;
        $param[3] = $this->_url;        
        $param[4] = $this->_activo;        
        $param[5] = $this->_fechaCreacion; 

        //continua si es actualizacion
        if($act){
            $param[5] = $this->_videoModeloId;
        }

        return $param;
    }
    

    //Grid por modelos videos
    public function GetVideosModeloGrid($gModeloId=""){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $uDB = new videoModelosDB();
        $ds = new videoModelosObj();
        //Obtener datos                    
        $result = $ds->ObtVideosModelo(true, "", $gModeloId);    
    
        $arrResult = array();
        foreach ($result as $elem) {
            //obtener el nombre de la galeria

            $arrResult[] = array(
                "videoModeloId"=>$elem->videoModeloId,
                "gModeloId"=>$elem->gModeloId,                
                "titulo"=>$elem->titulo,
                "descripcion"=>$elem->descripcion,
                "url"=>$elem->url,
                "activo"=>$elem->activo,
                "fechaCreacion"=>$elem->fechaCreacion,
                "classActivo"=>$elem->classActivo,
                "classInactivo"=>$elem->classInactivo,
                );
        }

        // $arrResult = $result;
        $ds = new ArrayDataSource($arrResult);    
        $grid = new KoolGrid("videosModeloGrid");

        $this->defineGridForm($grid, $ds);
        $this->defineColumnForm($grid, "videoModeloId", "ID", false, true);
        // $this->defineColumnForm($grid, "titulo", "Titulo", true, false, 1);
        // $this->defineColumnForm($grid, "descripcion", "Descripcion", true, false, 1);   
        $this->defineColumnForm($grid, "url", "Video", true, false, 1);
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
        elseif ($name_field == 'url') {
            $column = new GridCustomColumn();
            // controls
            $tagVideo = '<video width="150" height="70">
                          <source src="{url}" type="video/mp4">
                          Your browser does not support the video tag.
                        </video>';

            $column->ItemTemplate = $tagVideo; //'<img src="{imagen}" class="imgGrid">';
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
        $link = '';
        // $link .= '<a class="kgrLinkEdit btnEditarImgGaleria" onclick="mostrarFancyEditarImgGaleriaGamaModelo({videoModeloId}, {gModeloId}, '', \'{titulo}\', \'{imagen}\', 0)" href="#fancyImgGaleria" title="Editar">Editar</a>';
        // $link .= '<input type="hidden" id="descripcion_img_{videoModeloId}" value="{descripcion}">';
        $link .= '<a class="kgrLinkDelete" onclick="eliminarVideoModelo({videoModeloId})" href="javascript:void 0" title="Eliminar"></a>';
        // $link .= '<a class=" {classInactivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({videoModeloId},'."'galeriadesdemodelo'".',0)" href="javascript:void(0);" title="desactivar."><img src="../images/icon_delete.png" class="iconoDesactivar" ></a>';
        // $link .= '<a class=" {classActivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({videoModeloId},'."'galeriadesdemodelo'".',1)" href="javascript:void(0);" title="activar."><img src="../images/activar.jpg" class="iconoDesactivar" ></a>';
        // $link .= '<label class="contCheckbox">&nbsp;<input type="checkbox" idCheck="{videoModeloId}" value="0" class="selDelMul"><span class="checkmark"></span></label>';

        $column->ItemTemplate = $link;

            // <input type="checkbox" idCheck="{videoModeloId}" value="0" class="selDelMul">
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
                $array[$myRows->videoModeloId] = $myRows;
            }
            mysqli_free_result($result); // Free result set        
        }
        return $array;
    }

}