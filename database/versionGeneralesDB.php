<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/common/DataServices.php';

class versionGeneralesDB {

    //version general por id
    public function versionGeneralPorIdDB($id){
        $ds = new DataServices();
        $param[0] = $id;        
        
        $result = $ds->Execute("versionGeneralPorIdDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    //Obtener coleccion de versiones 
    public function ObtVersionesGeneralesDB($activo){
        $ds = new DataServices();        
        $param[0] = "";
        $query = array();

        if($activo != -1){
            $query[] = " activo=".$activo;
        }
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);          
          $param[0] = $wordWhere.$setWhere;
        }
        
        $result = $ds->Execute("ObtVersionesGeneralesDB", $param);
        $ds->CloseConnection();
        return $result;
    }
    public function ActCampoVersionGralDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("ActCampoVersionGralDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }
    public function InsertVersionGralDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("InsertVersionGralDB", $param, true);
        $ds->CloseConnection();
        return $result;
    }
    //actualizar version general
    public function ActVersionGralDB($param){
        $ds = new DataServices();        
        $result = $ds->Execute("ActVersionGralDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    }

    //Version General grid
    public function versionGeneralDataSet($ds, $modeloVersionB, $nombreVersionB, $activoVersionB)
    {
        $dsO = new DataServices();
        $param[0] = "";
        $query = array();

        if($nombreVersionB != "")
        {
            $query[] = " version LIKE '%".$nombreVersionB."%' ";
        }
        if($modeloVersionB != "")
        {
            $query[] = " gModeloId=".$modeloVersionB." ";
        }
        $activoVersionB = ($activoVersionB==-1)?"":$activoVersionB;
        if($activoVersionB !== "")
        {
            $query[] = " activo=$activoVersionB ";
        }
        // echo"<pre>";print_r($query);echo"</pre>";
        //En caso de llevar filtro
        if(count($query) > 0){
          $wordWhere = " WHERE ";
          $setWhere = implode(" AND ", $query);
          // echo $setWhere;
          $param[0] = $wordWhere.$setWhere;
        }
        // echo $param[0]."<br>";
        $ds->SelectCommand = $dsO->ExecuteDS("ObtTodosVersionesGeneralesDB", $param);        
        $dsO->CloseConnection();

        return $ds;
    }

    //obtener ids de las versiones por el id del modelo
    public function idsVersGeneralPorGModeloIdDB($gModeloId){
        $ds = new DataServices();
        $param[0] = $gModeloId;            
        
        $result = $ds->Execute("idsVersGeneralPorGModeloIdDB", $param);
        $ds->CloseConnection();
        return $result;
    }

    public function EliminarVersionGeneralDB($param){
        $ds = new DataServices();        

        $result = $ds->Execute("EliminarVersionGeneralDB", $param, false, true);
        $ds->CloseConnection();
        return $result;
    } 
    
    //Ordenar las versiones    
    public function OrdenarVersionesDB($data){
        $ds = new DataServices();
        $position = 1;      
        $result = array();
        foreach($data as $item){
            $id = (int)trim(str_replace("id_ver_", "", $item));
            // array_push($result, $item.'-'.$position);            
            
            $param[0]=$position;
            $param[1]=$id;
            // $ds->Execute("OrdenarVersionesDB", $param);
            $resOrd = $ds->Execute("OrdenarVersionesDB", $param, false, true);
            
            if($resOrd>0){
                $result[] = 1;
            }
            
            $position++;            
        }
        $ds->CloseConnection();        
                        
        return $result;
    }
}