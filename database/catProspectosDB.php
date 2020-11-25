<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class catProspectosDB {

    public function insertarProspectoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insertarProspectoDB", $param, true);
        return $result;
    }

    public function actualizarProspectoDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("actualizarProspectoDB", $param, false, true);
        return $result;
    }

    public function datosProspectoPorEmailDB($email){
        $ds = new DataServices();
        $param[0]= $email;
        $result = $ds->Execute("datosProspectoPorEmailDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    public function datosProspectoPorIdDB($id){
        $ds = new DataServices();
        $param[0]= $id;
        $result = $ds->Execute("datosProspectoPorIdDB", $param);
        $ds->CloseConnection();

        return $result;
    }

    
    

    public function catProspectoDataSet($ds, $vendedorB, $nombreProspecB, $agenciaId, $fechaDel, $fechaAl)
    {
        $dsO = new DataServices();
        $query = array();
        $param[0] = "";
        
        $query[] = " ( DATE_FORMAT(STR_TO_DATE(A.fechaAlta, '%d/%m/%Y'), '%Y-%m-%d') >= '$fechaDel' AND  DATE_FORMAT(STR_TO_DATE(A.fechaAlta, '%d/%m/%Y'), '%Y-%m-%d') <= '$fechaAl' ) ";

        if($nombreProspecB != "")
        {
            $query[] = " A.nombre LIKE '%".$nombreProspecB."%' ";
        }
        if($vendedorB != "")
        {
            $query[] = " A.usuarioId=".$vendedorB." ";
        }
        if($agenciaId>0)
        {
            $query[] = " B.agenciaId=".$agenciaId." ";
        }  

        // Obtener id de la agencia
        if(isset($_SESSION["idAgencia"]) && $_SESSION["idAgencia"]!=""){
            //Solo administrador y editores
            if($_SESSION["idRol"]==2 || $_SESSION["idRol"]==4){
                $query[] = " B.agenciaId=".$_SESSION["idAgencia"]." ";
            }
        }        

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }
        // echo $param[0]."<br>";
        $ds->SelectCommand = $dsO->ExecuteDS("getProspectosGrid", $param);        
        $dsO->CloseConnection();

        return $ds;
    }

    //Obtener todos los prospectos por el id del usuario (vendedor)
    public function obtTodosProspectosPorUsuarioDB($idUsuario)
    {
        $ds = new DataServices();
        if($idUsuario>0){
            $param[0] = " WHERE usuarioId=".$idUsuario;
        }else{
            $param[0] = " ";
        }        

        $result = $ds->Execute("obtTodosProspectosPorUsuarioDB", $param);
        $ds->CloseConnection();
        return $result;
    }


    //Obtener los prospectos a exportar 
    public function obtProspectosParaExportarDB($agenciaId, $idUsuario, $prospecto, $fechaDel, $fechaAl){
        $ds = new DataServices();
        $query = array();
        $param[0] = "";

        if($fechaDel!=""){
            $query[] = " ( DATE_FORMAT(STR_TO_DATE(A.fechaAlta, '%d/%m/%Y'), '%Y-%m-%d') >= '$fechaDel' AND  DATE_FORMAT(STR_TO_DATE(A.fechaAlta, '%d/%m/%Y'), '%Y-%m-%d') <= '$fechaAl' ) ";
        }        

        if($agenciaId>0){
            $query[] = " B.agenciaId=".$agenciaId." ";
        }
        if($idUsuario != ""){
            $query[] = " A.usuarioId=".$idUsuario." ";
        }
        if($prospecto != ""){
            $query[] = " A.nombre LIKE '%".$prospecto."%' ";
        }        

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);          
          $param[0] = $wordWhere.$setWhere;
        }
        // echo "<pre>".$param[0]."</pre>";

        $result = $ds->Execute("getProspectosGrid", $param);
        $ds->CloseConnection();
        return $result;    
    }
    
    //Eliminar versiones por el id padre
    public function EliminarProspectoPorIdDB($param){
        $ds = new DataServices();

        $result = $ds->Execute("EliminarProspectoPorIdDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    } 

}