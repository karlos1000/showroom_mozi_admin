<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/versionZonasActivasDB.php';

class versionZonasActivasObj {
    
    private $_activaZonaId = 0;   
    private $_zonaVersId = 0; 
    // private $_galeriaId = 0;    
    private $_activo = '';
    private $_fechaCreacion = '0000-00-00 00:00:00';    
    private $_imagenes = '';
    private $_coordenada = '';
    //Extras
    private $_idsZonaActivaVers = 0; 

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }
    
    //version zona activa por id
    public function versionZonaActivaPorId($id)
    {
        $ds = new versionZonasActivasDB();

        $result = $ds->versionZonaActivaPorIdDB($id);
        $this->setDatos($result);
    }

    public function versionZonaActivaPorZona($zonaVersId)
    {
        $ds = new versionZonasActivasDB();

        $result = $ds->versionZonaActivaPorZonaDB($zonaVersId);
        $this->setDatos($result);
    }

    //Obtener coleccion de versiones zonas activas
    public function ObtVersionesZonasActivas($opcObj=false, $zonaVersId = ""){
        $array = array();
        $ds = new versionZonasActivasDB();

        $result = $ds->ObtVersionesZonasActivasDB($zonaVersId);

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "activaZonaId");
        }

        return $array;
    }

    public function ActCampoVersionZonaA($campo, $valor, $activaZonaId){
        $array = array();
        $ds = new versionZonasActivasDB();
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $activaZonaId;

        return $ds->ActCampoVersionZonaADB($param);
    }

    //Insertar un version zona activa
    public function InsertVersionZonaActiva(){
        $array = array();
        $ds = new versionZonasActivasDB();

        $this->_activaZonaId = $ds->InsertVersionZonaActivaDB($this->getParams(false));
    }

    //Actualizar una version zona activa
    public function ActVersionZonaActiva(){
        $array = array();
        $ds = new versionZonasActivasDB();

        return $ds->ActVersionZonaActivaDB($this->getParams(true));
    }

    public function EliminarVersionZonaActiva($zonaVersId){
        $array = array();
        $ds = new versionZonasActivasDB();
        $param[0] = $zonaVersId;

        return $ds->EliminarVersionZonaActivaDB($param);
    }


    //Parametros
    private function getParams($act)
    {
        $dTZoneObj = obtDateTimeZone();                
        $this->_fechaCreacion = $dTZoneObj->fechaHora;

        $param[0] = $this->_zonaVersId;
        $param[1] = $this->_coordenada;
        $param[2] = $this->_fechaCreacion; 

        //continua si es actualizacion
        if($act){
            $param[2] = $this->_activaZonaId;
        }

        return $param;
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
                $array[$myRows->activaZonaId] = $myRows;
            }
            mysqli_free_result($result); // Free result set        
        }
        return $array;
    }


    public function actualizaJsonImagenes($activaZonaId, $imagen, $jsonOriginal, $titulo = "", $descripcion = "")
    {
        
        if($jsonOriginal != '')
        {
            $arrJson = json_decode($jsonOriginal);
            $arrImgFinal = array();

            $idImagen = 1;
            foreach ($arrJson as $json) {
                $idImagen = $json->idImagen;
            }
            $idImagen++;
                     

            $arrImagen = (object)array("idImagen"=>$idImagen,"titulo"=>$titulo, "descripcion"=>$descripcion, "imagen"=>$imagen, "precio"=>"");

            array_push($arrJson, $arrImagen);


            // echo "<br>json editar:<br>".json_encode($arrImgOriginal)."<br>";

            return $this->ActCampoVersionZonaA("imagenes",json_encode($arrJson),$activaZonaId);
        }
        else
        {
            $arrImagen[] = array("idImagen"=>1,"titulo"=>$titulo, "descripcion"=>$descripcion, "imagen"=>$imagen, "precio"=>"");

            // echo "<br>json nuevo:<br>".json_encode($arrImagen)."<br>";

            return $this->ActCampoVersionZonaA("imagenes",json_encode($arrImagen),$activaZonaId);
        }
        


    }

    public function eliminarImgJson($activaZonaId, $idImagen, $jsonOriginal)
    {
        $arrJson = json_decode($jsonOriginal);
        $arrResult = array();
        $borrado = false;

        foreach ($arrJson as $arr) {            
            if($arr->idImagen == $idImagen)
            {
                // if(file_exists($arr->imagen)){
                //     $borrado = true;
                //     @unlink($arr->imagen);
                // }            
                $borrado = true;
            }else{
                $arrResult[] = $arr;
            }
        }

        if($borrado==true){
            return $this->ActCampoVersionZonaA("imagenes",json_encode($arrResult),$activaZonaId);
        }else{
            return 0;
        }
    }



    //Grid version zonas
    public function GetVersionZonaActivaGrid($zonaVersId = "", $gal360=0){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        // $ds = new MySQLiDataSource($dbConn);
        $uDB = new versionZonasActivasDB();

        // $versionZonasActivasObj = new versionZonasActivasObj();
        // $versionZonasActivasObj->versionZonaActivaPorZona($zonaVersId);        
        $result = $uDB->versionZonaActivaPorIdDB($zonaVersId);
        $this->setDatos($result);

        // $arrJson = (array)json_decode($versionZonasActivasObj->imagenes);
        $arrJson = (array)json_decode($this->_imagenes);
        $arrResult = array();
        foreach ($arrJson as $imagen) {
            // echo "<pre>"; print_r($imagen); echo "</pre>";
            $arrResult[] = array(
                "zonaVersId"=>$zonaVersId,
                "idImagen"=>$imagen->idImagen,
                "titulo"=>$imagen->titulo,
                "descripcion"=>$imagen->descripcion,
                "imagen"=>$imagen->imagen,
                "precio"=>(isset($imagen->precio))? ($imagen->precio=="")?"":number_format($imagen->precio,2) :"",                
                "activaZonaId"=>$this->activaZonaId,
                // "activaZonaId"=>$versionZonasActivasObj->activaZonaId,
                );
        }


        $ds = new ArrayDataSource($arrResult);

        // $ds = $uDB->versionZonaDataSet($ds, $coloresVersId);
        $grid = new KoolGrid("versionZonaActivaGrid");

        $this->defineGridForm($grid, $ds);
        $this->defineColumnForm($grid, "idImagen", "ID", false, true);
        if($gal360==0) {
        $this->defineColumnForm($grid, "titulo", "Titulo", true, false, 1);
        $this->defineColumnForm($grid, "descripcion", "Descripcion", true, false, 1);   
          $this->defineColumnForm($grid, "precio", "Precio $", true, false, 1);
        }
        $this->defineColumnForm($grid, "imagen", "Imagen", true, false, 1);           
        // $this->defineColumnForm($grid, "precio", "Precio $", true, false, 1);
        // $this->defineColumnForm($grid, "activo", "Activo", true, false, 0);
        $this->defineColumnEditForm($grid, $gal360);

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
    private function defineColumnEditForm($grid, $gal360)
    {
        // $column = new grideditdeletecolumn();
        // $column->Align = "center";
        // $column->HeaderText = "Acciones";
        // $grid->MasterTable->AddColumn($column);

        $column = new GridCustomColumn();
        $acciones = '';
        if($gal360==0){
        $acciones .= '
                <a class="kgrLinkEdit btnEditarImgGaleria" onclick="mostrarFancyEditarImgGaleria({zonaVersId}, {idImagen}, \'\', \'\', \'{imagen}\', \'{precio}\')" href="#fancyImgGaleria" title="Editar">Editar</a>
                ';
        }
        $acciones .= '
                <input type="hidden" id="descripcion_img_{idImagen}" value="{descripcion}">
                <input type="hidden" id="titulo_img_{idImagen}" value="{titulo}">
                <a class="kgrLinkDelete" onclick="eliminarImgGaleria({activaZonaId},{idImagen})" href="javascript:void 0" title="Eliminar"></a>
                <label class="contCheckbox">&nbsp;<input type="checkbox" idCheck="{idImagen}" value="0" class="selDelMul"><span class="checkmark"></span></label>

                <!--<a class=" {classInactivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({zonaVersId},'."'versionzona'".',0)" href="javascript:void(0);" title="desactivar."><img src="../images/icon_delete.png" class="iconoDesactivar" ></a>

                 <a class="{classActivo} btnDesactivarUsuario cursorPointer" onclick="grid_activardesactivar({zonaVersId},'."'versionzona'".',1)" href="javascript:void(0);" title="activar."><img src="../images/activar.jpg" class="iconoDesactivar" ></a>-->
            ';
        $column->ItemTemplate = $acciones;
        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $column->Width = "120px";
        $grid->MasterTable->AddColumn($column);
    }


    //obtener ids de zonas activas por el id de zonas
    public function idsZonasActivasVersPorIdsZona($zonaVersId)
    {
        $ds = new versionZonasActivasDB();
        $result = $ds->idsZonasActivasVersPorIdsZonaDB($zonaVersId);
        $this->setDatos($result);         
    }

}