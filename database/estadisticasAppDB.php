<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class estadisticasAppDB {

    public function insertarEstadisticaAppDB($param){
        $ds = new DataServices();
        $result = $ds->Execute("insertarEstadisticaAppDB", $param, true);
        return $result;
    }

    // Obtener agentes de ventas
    public function obtEstAgentesDB(){
        $ds = new DataServices();
        $param = NULL;

        $result = $ds->Execute("obtEstAgentesDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    // Obtener modelos
    public function obtEstModelosDB(){
        $ds = new DataServices();
        $param = NULL;

        $result = $ds->Execute("obtEstModelosDB", $param);
        $ds->CloseConnection();
        return $result;
    }


    /*public function actualizarProspectoDB($param){
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
    */
    
    
    // public function EstadisticasDataSet($ds, $vendedorB, $nombreProspecB, $agenciaId, $fechaDel, $fechaAl)
    public function EstadisticasDataSet($ds, $fechaDel, $fechaAl, $fil_opcion, $fil_agente, $fil_modelo)
    {
        // echo "fil_opcion: ".$fil_opcion."<br/>";
        // echo "fil_agente: ".$fil_agente."<br/>";
        // echo "fil_modelo: ".$fil_modelo."<br/>";
        // 1 = Prospecto registrado
        // 2 = Inicio sesión
        // 3 = Mesa de trabajo
        // 4 = Calculadora
        // 5 = Cotización guardada
        // 6 = Envío de características

        $dsO = new DataServices();
        $query = array();
        $param[0] = "";
        
        // $query[] = " ( DATE_FORMAT(STR_TO_DATE(A.fechaLog, '%d/%m/%Y'), '%Y-%m-%d') >= '$fechaDel' AND  DATE_FORMAT(STR_TO_DATE(A.fechaLog, '%d/%m/%Y'), '%Y-%m-%d') <= '$fechaAl' ) ";
        $query[] = " ( DATE_FORMAT(STR_TO_DATE(a.fechaLog, '%Y-%m-%d'), '%Y-%m-%d') >= '$fechaDel' AND  DATE_FORMAT(STR_TO_DATE(a.fechaLog, '%Y-%m-%d'), '%Y-%m-%d') <= '$fechaAl' ) ";
        
        // Para los admin, obtener solo aquellos que pertenecen a su agencia
        if($_SESSION['idRol']!=1 && $_SESSION['idAgencia']!=""){
            $query[] = " b.agenciaId=".$_SESSION['idAgencia'];
        }
        
        // Imp. 12/02/20
        if($fil_opcion>0){
            switch ($fil_opcion){
                case 1: $query[] = " a.accion='Prospecto registrado' "; break;
                case 2: $query[] = " a.accion='Inicio sesion' "; break;
                case 3: $query[] = " a.accion='Mesa de trabajo' "; break;
                case 4: $query[] = " a.accion='Calculadora' "; break;
                case 5: $query[] = " a.accion='Cotizacion guardada' "; break;
                case 6: $query[] = " a.accion='Envio de caracteristicas' "; break;
            }        
        }

        // Imp. 12/02/20
        if($fil_agente>0){
            $query[] = " a.usuarioId=$fil_agente ";
        }

        // Imp. 12/02/20
        if($fil_modelo>0){
            $query[] = " a.idModelo=$fil_modelo ";
        }        
        

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }
        // echo "<pre>"; print_r($param); echo "</pre>";
        
        $ds->SelectCommand = $dsO->ExecuteDS("obtEstadisticasAppGrid", $param);        
        $dsO->CloseConnection();

        return $ds;
    }

    // Obtener las estadisticas 
    public function obtEstadisticasRptDB($fechaDel, $fechaAl, $fil_opcion, $fil_agente, $fil_modelo){
        $ds = new DataServices();
        $query = array();
        $param[0] = "";
                
        $query[] = " ( DATE_FORMAT(STR_TO_DATE(a.fechaLog, '%Y-%m-%d'), '%Y-%m-%d') >= '$fechaDel' AND  DATE_FORMAT(STR_TO_DATE(a.fechaLog, '%Y-%m-%d'), '%Y-%m-%d') <= '$fechaAl' ) ";
        
        // Para los admin, obtener solo aquellos que pertenecen a su agencia
        // if($_SESSION['idRol']!=1 && $_SESSION['idAgencia']!=""){
        //     $query[] = " b.agenciaId=".$_SESSION['idAgencia'];
        // }
        
        // Imp. 13/02/20
        if($fil_opcion>0){
            switch ($fil_opcion){
                case 1: $query[] = " a.accion='Prospecto registrado' "; break;
                case 2: $query[] = " a.accion='Inicio sesion' "; break;
                case 3: $query[] = " a.accion='Mesa de trabajo' "; break;
                case 4: $query[] = " a.accion='Calculadora' "; break;
                case 5: $query[] = " a.accion='Cotizacion guardada' "; break;
                case 6: $query[] = " a.accion='Envio de caracteristicas' "; break;
            }        
        }

        // Imp. 13/02/20
        if($fil_agente>0){
            $query[] = " a.usuarioId=$fil_agente ";
        }

        // Imp. 13/02/20
        if($fil_modelo>0){
            $query[] = " a.idModelo=$fil_modelo ";
        }        
        

        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }
        
        // echo "<pre>"; print_r($param); echo "</pre>";

        $result = $ds->Execute("obtEstadisticasAppGrid", $param);
        $ds->CloseConnection();
        return $result;
    }

/*
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

        $query[] = " ( DATE_FORMAT(STR_TO_DATE(A.fechaAlta, '%d/%m/%Y'), '%Y-%m-%d') >= '$fechaDel' AND  DATE_FORMAT(STR_TO_DATE(A.fechaAlta, '%d/%m/%Y'), '%Y-%m-%d') <= '$fechaAl' ) ";

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
*/
}