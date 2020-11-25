<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/catProspectosDB.php';
include_once  $dirname.'/brules/usuariosObj.php';

class catProspectosObj {
    private $_prospectoId = 0;    
    private $_usuarioId = 0;
    private $_nombre = '';
    private $_direccion = '';
    private $_telefono = '';
    private $_email = '';
    private $_fechaAlta = '';
    private $_datosJson = '';
    private $_fechaCreacion = '0000-00-00 00:00:00';   

    //Extras
    private $_agencia = '';

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    public function salvarProspecto()
    {
        $ds = new catProspectosDB();        
        $this->_prospectoId = $ds->insertarProspectoDB($this->getParams());
    }

    public function actProspecto()
    {
        $ds = new catProspectosDB();        
        $respAct = $ds->actualizarProspectoDB($this->getParams(true));
        return $respAct;
    }

    //obtener datos por el email
    public function datosProspectoPorEmail($email)
    {
        $ds = new catProspectosDB();
        $result = $ds->datosProspectoPorEmailDB($email);
        $this->setDatos($result);
    }

    //obtener datos por el id
    public function datosProspectoPorId($id)
    {
        $ds = new catProspectosDB();
        $result = $ds->datosProspectoPorIdDB($id);
        $this->setDatos($result);
    }

    //Obtener todos los prospectos por el id del usuario (vendedor)
    public function obtTodosProspectosPorUsuario($opcObj=false, $idUsuario=0)
    {
        $array = array();
        $ds = new catProspectosDB();
        $result = $ds->obtTodosProspectosPorUsuarioDB($idUsuario);        

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "prospectoId");           
        }
        
        return $array;
    } 


    private function getParams($opc=false)
    {
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
        $this->_fechaCreacion = $dateTime;

        $param[0] = $this->_usuarioId;
        $param[1] = $this->_nombre;
        $param[2] = $this->_direccion;
        $param[3] = $this->_telefono;                
        $param[4] = $this->_email;
        $param[5] = $this->_datosJson;
        $param[6] = $this->_fechaAlta;

        if($opc==true){
            $param[7] = $this->_prospectoId;
        }else{
            $param[7] = $this->_fechaCreacion;
        }    

        return $param;
    }

    //Grid prospectos
    public function GetCatProspectoGrid($vendedorB = "", $nombreProspecB = "", $agenciaId=0, $fechaDel="", $fechaAl="")
    {
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);        
        $uDB = new catProspectosDB();
        $ds = $uDB->catProspectoDataSet($ds, $vendedorB, $nombreProspecB, $agenciaId, $fechaDel, $fechaAl);
        $grid = new KoolGrid("catProspectoGrid");

        $this->defineGridForm($grid, $ds);
        $this->defineColumnForm($grid, "prospectoId", "ID", false, true);
        $this->defineColumnForm($grid, "fechaAlta", "Fecha Alta", true, false, 0);
        $this->defineColumnForm($grid, "agencia", "Agencia", true, false, 0);
        $this->defineColumnForm($grid, "usuarioId", "Vendedor", true, false, 0);
        $this->defineColumnForm($grid, "nombre", "Prospecto", true, false, 0);
        // $this->defineColumnForm($grid, "direccion", "Direcci&oacute;n", true, false, 0);
        $this->defineColumnForm($grid, "telefono", "Tel&eacute;fono", true, false, 0);
        $this->defineColumnForm($grid, "email", "Email", true, false, 0);        
        $this->defineColumnForm($grid, "acciones", "Accci&oacute;n", true, false, 0);
        // $this->defineColumnEditForm($grid);

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
        $grid->Width = "860px";

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
        if($name_field == 'usuarioId') {
            $column = new GridDropDownColumn();
            $usrObj = new usuariosObj();
            $usrArr = $usrObj->obtTodosUsuarios();            
            foreach($usrArr as $usrTmp)
            {                
                $column->AddItem($usrTmp->nombre,$usrTmp->idUsuario);
            }        
        }
        elseif($name_field == 'acciones') {
            $column = new GridCustomColumn();
            $column->ItemTemplate = '
                <a class="" href="prospectodetalle.php?idP={prospectoId}">Ver detalle</a>                
                ';
            // $column->ItemTemplate = '
            //     <a class="" href="prospectodetalle.php?idP={prospectoId}">Ver detalle</a> | 
            //     <a class="" href="javascrip:void(0)" onclick="eliminar_reg({prospectoId}, \'prospecto\')">Eliminar</a>
            //     ';    
        }
        else{
            $column = new gridboundcolumn();
        } 
            
        $column->Visible = $visible;
        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $grid->MasterTable->AddColumn($column);
    }


    //Obtener los prospectos a exportar 
    public function obtProspectosParaExportar($opcObj=false, $agenciaId="", $idUsuario="", $prospecto="", $fechaDel="", $fechaAl=""){
        $array = array();
        $ds = new catProspectosDB();
        $result = $ds->obtProspectosParaExportarDB($agenciaId, $idUsuario, $prospecto, $fechaDel, $fechaAl);

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "prospectoId");           
        }
        
        return $array;
    }

    //Eliminar prospecto por id
    public function EliminarProspectoPorId($id){
        $ds = new catProspectosDB();
        $param[0] = $id;

        return $ds->EliminarProspectoPorIdDB($param);
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
                $array[$myRows->prospectoId] = $myRows;
            }
            mysqli_free_result($result); // Free result set        
        }
        return $array;
    }

}