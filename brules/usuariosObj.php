<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/database/usuariosBD.php';
include_once  $dirname.'/brules/rolesObj.php';
include_once  $dirname.'/brules/catAgenciasObj.php';

class usuariosObj {
    private $_idUsuario = 0;
    private $_idRol = 0;
    private $_nombre = '';
    private $_email = '';
    private $_password = '';
    private $_activo = 0;        
    private $_fechaCreacion = '0000-00-00 00:00:00';    
    //extras
    private $_usuarioActivo = '';
    private $_nombreImg = '';
    // private $_editcol = '';
    private $_agenciaId = 0;
    private $_enlaceCot = "";
    private $_enlaceBank = "";
    private $_urlsActivas = "";
    

    //get y set
    public function __get($name) {
        return $this->{"_".$name};
    }
    public function __set($name, $value) {
        $this->{"_".$name} = $value;
    }

    //logueo de usuario
    public function LoginUser($email, $password){
        $usrDS = new usuariosBD();

        $result = $usrDS->LoginUser($email, $password);
        $this->setDatos($result);
    }
    //usuario por id
    public function UserByID($id)
    {
        $usrDS = new usuariosBD();

        $result = $usrDS->UserByID($id);
        $this->setDatos($result);
    }
    
    //Usuario por email (para recupera la contrasenia)
    public function UserByEmail($email)
    {
        $usrDS = new usuariosBD();
        $result = $usrDS->UserByEmailDB($email);
        $this->setDatos($result);
    }

    //Usuario por no. contrato e email
    public function UserByContractEmail($contrat, $email)
    {
        $usrDS = new usuariosBD();
        $result = $usrDS->UserByContractEmailDB($contrat, $email);
        $this->setDatos($result);
    }
    
    //region
    //Grid usuarios
     public function GetUsersGrid(){
        $DataServices = new DataServices();
        $dbConn = $DataServices->getConnection();
        $ds = new MySQLiDataSource($dbConn);
        $uDB = new usuariosBD();
        $ds = $uDB->UsersDataSet($ds);
        $grid = new KoolGrid("usuariosGrid");

        $this->defineGridUser($grid, $ds);
        $this->defineColumnUser($grid, "idUsuario", "ID", false, true);
        $this->defineColumnUser($grid, "idRol", "Rol", true, false, 1,"100px");
        $this->defineColumnUser($grid, "agenciaId", "Agencia", true, false, 0,"100px");
        $this->defineColumnUser($grid, "nombre", "Nombre", true, false, 1,"100px");
        $this->defineColumnUser($grid, "email", "Correo Electronico", true, false, 1,"150px");
        $this->defineColumnUser($grid, "password", "Contraseña", true, false, 1,"150px");                                    

        //$this->defineColumnUser($grid, "usuarioActivo", "Activo", true, true, 1,"60px");
        // $this->defineColumnUser($grid, "editcol", "", true, true, 1,"40px");
        $this->defineColumnUser($grid, "activo", "Activo", false, true, 1,"80px");
        $this->defineColumnEditUser($grid);

        //pocess grid
        $grid->Process();

        return $grid;
    }

    //Private Functions
    private function defineGridUser($grid, $ds)
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
        $grid->AllowScrolling = true;
      	//$grid->MasterTable->Height = "540px";
        $grid->MasterTable->ColumnWidth = "90px";
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
        $grid->ClientSettings->ClientEvents["OnRowConfirmEdit"] = "Handle_OnRowConfirmEdit";
        $grid->ClientSettings->ClientEvents["OnConfirmInsert"] = "Handle_OnConfirmInsert";
    }
    //define the grid columns
    private function defineColumnUser($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0, $width = "90px")
    {
        if($name_field == 'idRol') {
            $column = new GridDropDownColumn();
            $rolObj = new rolesObj();
            $rolArr = $rolObj->GetAllRoles();
            $column->AddItem('-- Seleccionar --',NULL);
            foreach($rolArr as $rolTmp)
            {                
                $column->AddItem($rolTmp->rol,$rolTmp->idRol);             
            }
        } 
        elseif($name_field == 'agenciaId'){
           $column = new GridDropDownColumn(); 
           $catAgenciaObj = new catAgenciasObj();
           $colAgencias = $catAgenciaObj->ObtTodasAgencias(false, 1);
           $column->AddItem('-- Seleccionar --',"NULL");
           foreach($colAgencias as $agtTmp)
           {                
               $column->AddItem($agtTmp->nombre,$agtTmp->agenciaId);
           }
        }        

        // else if($name_field == 'editcol'){
        //     $column = new GridDropDownColumn();
        //     $column->AddItem('<a class="btnEditarColaborador" onclick="muestraEditarColaborador({idUsuario},\'{nombre}\')"  href="#fancyEditarColaborador" title="Editar Colaborador"><img src="../images/icon_cargo.png" class="iconoDesactivar" ></a>', 1);
        //     $column->AddItem('', 0);
        // }
        else{
            $column = new gridboundcolumn();
        }

        if($validator > 0)
            $column->addvalidator($this->GetValidatorUser ($validator));

        $column->Visible = $visible;
        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $column->Width = $width;
        $grid->MasterTable->AddColumn($column);
    }
    //validar campo
    private function GetValidatorUser($type){
        switch ($type) {
            case 1: //required
                $validator = new RequiredFieldValidator();
                $validator->ErrorMessage = "Campo requerido";
                return $validator;
                break;
        }
    }
    //define la columna de acciones
    private function defineColumnEditUser($grid){
        $column = new GridCustomColumn();
        $column->ItemTemplate = '            
            <a class="kgrLinkEdit" onclick="grid_edit(this)" href="javascript:void 0" title="Editar"></a>'
            . '<a class="kgrLinkDelete" onclick="grid_delete(this)" href="javascript:void 0" title="Eliminar"></a>';
            // . '<a class="btnDesactivarUsuario" onclick="muestraDesactivarUsuario({idUsuario},\'{nombre}\',{activo})"  href="#fancyDesactivarUsuario" title="Activar/Desactivar usuario"><img src="../images/{nombreImg}" class="iconoDesactivar" ></a>';
        $column->Align = "center";
        $column->HeaderText = "Acciones";
        $column->Width = "120px";
        $grid->MasterTable->AddColumn($column);
    }
    
    public function GuardarUsuario()
    {
        $objDB = new usuariosBD();
        $this->_idUsuario = $objDB->insertUsuarioDB($this->getParams());
    }
    private function getParams()
    {
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual
        $this->_fechaCreacion = $dateTime;

        $param[0] = $this->_idRol;
        $param[1] = $this->_nombre;
        $param[2] = $this->_email;
        $param[3] = $this->_password;                
        $param[4] = $this->_fechaCreacion;
        $param[5] = $this->_codEmpleado;

        return $param;
    }
    public function ActualizarUsuario($campo, $valor, $id)
    {
        $param[0] = $campo;
        $param[1] = $valor;
        $param[2] = $id;

        $objDB = new usuariosBD();
        $resAct = $objDB->updateUsuarioDB($param);
        return $resAct;
    }
    public function obtUsuariosByIdRol($idRol)
    {
        $array = array();
        $ds = new usuariosBD();
        $result = $ds->obtUsuariosByIdRolDB($idRol);
        $array = $this->arrDatos($result, "idUsuario");

        return $array;
    }
    //Obtener todos los usuarios
    public function obtTodosUsuarios($opcObj=false)
    {
        $array = array();
        $ds = new usuariosBD();
        $result = $ds->obtTodosUsuariosDB();        

        if($opcObj==true){
           $array = (array)$this->arrDatosObj($result);
        }else{
           $array = $this->arrDatos($result, "idUsuario");           
        }
        
        return $array;
    } 


    //Metodos especificos 
    //Obtiene el arreglo de datos
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

    //Obtener coleccion de datos por fetch_object
    private function arrDatosObj($result){
        $array = array();
        $classObj = get_class($this);
        if ($result)
        {               
            while ($myRows = mysqli_fetch_object($result)){            
                $array[$myRows->idUsuario] = $myRows;
            }
            mysqli_free_result($result); // Free result set        
        }
        return $array;
    }

}
