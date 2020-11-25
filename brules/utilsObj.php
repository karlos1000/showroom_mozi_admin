<?php
$dirname = dirname(__DIR__);


//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//>>>METODOS GENERALES<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

//llamar a librerias koolphp
function libreriasKool()
{
  include_once '../brules/KoolControls/KoolGrid/koolgrid.php';
  require_once '../brules/KoolControls/KoolCalendar/koolcalendar.php';
  include_once '../brules/KoolControls/KoolGrid/ext/datasources/MySQLiDataSource.php';
}

//Metodo para obtener anios atras o adelante a la fecha actual
function aniosPrevPos($anios, $anioActual, $ctr){
    $result = "";    
    //Obtiene anios atras
    if($ctr=="prev") {
      $result = $anioActual-$anios;
    }
    //Obtiene anios delante
    if($ctr=="pos") {
      $result = $anioActual+$anios;
    }

    return $result;
}

//Obtener la fecha 
function obtDateTimeZone(){
    $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
    $fecha = $dateByZone->format('Y-m-d'); //fecha
    $hora = $dateByZone->format('H:i:s'); //Hora
    $fechaHora = $dateByZone->format('Y-m-d H:i:s'); //fecha y hora
    $anio = $dateByZone->format('Y'); //fecha
    //Formatos d/m/Y
    $fechaF2 = $dateByZone->format('d/m/Y'); //fecha formato d/m/Y
    $fechaHoraF2 = $dateByZone->format('d/m/Y H:i:s'); //fecha y hora d/m/Y H:i:s

    return (object)array("fecha"=>$fecha, "hora"=>$hora, "fechaHora"=>$fechaHora, "fechaF2"=>$fechaF2, "fechaHoraF2"=>$fechaHoraF2, "anio"=>$anio);
}    

//Subir una imagen al server
function subirImagen($files, $params, $opc = 0){
    $nombreInput = "imagen";
    if($opc == 1)
    {
        $nombreInput = "imagenc";
    }
    $folderDestino = $params->folderDestino; 
    // $archivoDestino = $folderDestino . basename($files[$nombreInput]["name"]);    
    $archivoExt = strtolower(pathinfo(basename($files[$nombreInput]["name"]),PATHINFO_EXTENSION));
    
    //Cambiar nombre a la imagen 
    $nuevaImg = generarPassword(10, TRUE, TRUE, FALSE, FALSE).".".$archivoExt;
    $archivoDestino = $folderDestino.$nuevaImg;

    //comprueba la extension del archivo
    if(!in_array($archivoExt, $params->arrExt)){
        return (object)array("result"=>false, "respImg"=>"Comprueba la extensión del archivo.");
    }

    //Subir archivo
    if(move_uploaded_file($_FILES[$nombreInput]["tmp_name"], $archivoDestino)) {
        return (object)array("result"=>true, "respImg"=>"Archivo subido correctamente.", "nImg"=>$nuevaImg);
       // return (object)array("result"=>true, "respImg"=>"Archivo subido correctamente.", "nImg"=>basename($files[$nombreInput]["name"]));
    }else{
        return (object)array("result"=>false, "respImg"=>"Lo sentimos, hubo un error al cargar su archivo.");        
    }    
}

//Subir un archivo al servidor
function subirArchivo($files, $params){
    $nombreInput = $params->nameFile;
    $folderDestino = $params->folderDestino;     
    $archivoExt = strtolower(pathinfo(basename($files[$nombreInput]["name"]),PATHINFO_EXTENSION));
    
    //Cambiar nombre del archivo
    $nombreArchivo = generarPassword(10, TRUE, TRUE, FALSE, FALSE).".".$archivoExt;
    $archivoDestino = $folderDestino.$nombreArchivo;

    //comprueba la extension del archivo
    if(!in_array($archivoExt, $params->arrExt)){
        return (object)array("result"=>false, "respArchivo"=>"Comprueba la extensión del archivo.");
    }

    //Subir archivo
    if(move_uploaded_file($_FILES[$nombreInput]["tmp_name"], $archivoDestino)) {
        return (object)array("result"=>true, "respArchivo"=>"Archivo subido correctamente.", "nArchivo"=>$nombreArchivo);
    }else{
        return (object)array("result"=>false, "respArchivo"=>"Lo sentimos, hubo un error al cargar su archivo.");        
    }
}


//Convertir imagen en base 64
function imagenBase64($rutaImg){   
    if(file_exists($rutaImg))
    {
    $type = pathinfo($rutaImg, PATHINFO_EXTENSION);
    $data = file_get_contents($rutaImg);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    return $base64;
    }
    else
    {
        return "";
    }
}


//Metodo para obtener dias atras o dias posteriores a la fecha actual
function diasPrevPos($dias, $dataCurrent, $ctr, $tipo = 1){
    $fecha = "";
    $formato = "d/m/Y";
    if($tipo == 2)
    {
        $formato = "Y-m-d h:i:s";
    }
    //Obtiene dias atras
    if($ctr=="prev") {
      $fecha = strtotime ( "-$dias day" , strtotime ( $dataCurrent ) ) ;
      $fecha = date ($formato, $fecha);
    }
    //Obtiene dias adelante
    if($ctr=="pos") {
      $fecha = strtotime ( "+$dias day" , strtotime ( $dataCurrent ) ) ;
      $fecha = date ($formato, $fecha);
    }

    return $fecha;
}

//convertir fechas de formato (dd/mm/YYYY) a (YYYY-mm-dd)
function conversionFechas($fecha){
    list($dd, $mm, $yyyy) = explode("/", $fecha);
    $fecha = $yyyy.'-'.$mm.'-'.$dd;
    return $fecha;
}

//Genera una cadena aleatoria
function generarPassword($longitud = 6, $opc_letras = TRUE, $opc_numeros = TRUE,   $opc_letrasMayus = FALSE, $opc_especiales = FALSE ){    
    $password = "";
    $letras ="abcdefghijklmnopqrstuvwxyz";
    $numeros = "1234567890";
    $letrasMayus = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $especiales ="|@#~$%()=^*+[]{}-_";
    $listado = "";
    if ($opc_letras == TRUE) {
        $listado .= $letras; }
    if ($opc_numeros == TRUE) {
        $listado .= $numeros; }
    if($opc_letrasMayus == TRUE) {
        $listado .= $letrasMayus; }
    if($opc_especiales == TRUE) {
        $listado .= $especiales; }
    str_shuffle($listado);
    for( $i=1; $i<=$longitud; $i++) {
        $password .= @$listado[rand(0,strlen($listado))];
        str_shuffle($listado);
    }
    return $password;
}

//Metodo para convertir fecha en formato yy-mm-dd => dd/mm/yy
function convertirFechaVista($fecha)
{
    return date ( 'd/m/Y' , strtotime($fecha) );
}

//Metodo para obtener la fecha con formato Lunes 1 de enero de 2017
function obtenerFechaCompletaDeHoy()
{
    $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City'));
    $mes = $dateByZone->format("n");
    $m = obtenerMes($mes);
    $dateComplete = $dateByZone->format("j") . " de " . $m . " del " . $dateByZone->format("Y");
    return $dateComplete;
}

//Convertir fecha yy-mm-dd a 1 de enero de 2017
function convertirAFechaCompleta($fecha)
{
    $dateByZone = new DateTime($fecha, new DateTimeZone('America/Mexico_City'));
    $mes = $dateByZone->format("n");
    $m = obtenerMes($mes);
    $dateComplete = $dateByZone->format("j") . " de " . $m . " del " . $dateByZone->format("Y");
    return $dateComplete;
}

//obtener el nombre del mes mediante el numero que le corresponde
function obtenerMes($mes)
{
    $m = "";
    switch ($mes) {
        case 1: $m = "Enero";
            break;
        case 2: $m = "Febrero";
            break;
        case 3: $m = "Marzo";
            break;
        case 4: $m = "Abril";
            break;
        case 5: $m = "Mayo";
            break;
        case 6: $m = "Junio";
            break;
        case 7: $m = "Julio";
            break;
        case 8: $m = "Agosto";
            break;
        case 9: $m = "Septiembre";
            break;
        case 10: $m = "Octubre";
            break;
        case 11: $m = "Noviembre";
            break;
        case 12: $m = "Diciembre";
            break;
    }
    return $m;
}

//obtener la extension de algun archivo
function obtenerExtension($str) 
{
        $arr = explode(".", $str);
        $ext = strtolower($arr[count($arr)-1]);
        return $ext;
}

//Metodo para construir una cadena sql
function contruyeCadenaSqlAct($array){
    //$array = Arreglo de campos y valores    
    $cadenaSql = "";
    $query = array();
    if(count($array)>0){
        foreach($array as $elem){
            $elem = (object)$elem; //convierte el arreglo en objeto
            $query[] = $elem->campo.'='."'".$elem->valor."'";            
        }
        $cadenaSql = implode(", ", $query);        
    }
    return $cadenaSql;
}

//reemplaza texto
function textoParaBD($texto)
{
    return trim(str_replace('?', '___', $texto));
}

//reemplaza texto
function textoParaMostrar($texto)
{
    return str_replace("___","?",$texto);
}

//Obtener parametro dada una url
function obtenerParametroURL($url, $nombre)
{
  $params = parse_url($url, PHP_URL_QUERY);
  $array = explode("&",$params); 
  $embed = "";
  foreach ($array as $key => $value) {
          $param = explode("=",$value);
          if($param[0] == $nombre)
          {
            $embed = $param[1];
            break;
          }
  } 
  return $embed;
}

//Reemplzaza caracteres
function cadenaEspeciales($cadena)
{
    $cadena = str_replace("\"", "&#39;", $cadena);
    $cadena = str_replace("'", "&#39;", $cadena);
    $cadena = str_replace("ñ", "&ntilde;", $cadena);
    $cadena = str_replace("á", "&aacute;", $cadena);
    $cadena = str_replace("é", "&eacute;", $cadena);
    $cadena = str_replace("í", "&iacute;", $cadena);
    $cadena = str_replace("ó", "&oacute;", $cadena);
    $cadena = str_replace("ú", "&uacute;", $cadena);

    return $cadena;
}


//Quitar la coma a las cantidades
function removerComas($dato){
   return str_replace(",", "", $dato);
}
//reemplazar caracteres a su entitie (',")
function reemplazarAEntities($dato){
   // return htmlentities($dato, ENT_QUOTES, "UTF-8");   
   // $arrC = array("&quot;"=>'"', "&#039;"=>"'");
   $arrC = array("&#039;"=>"'");
   foreach ($arrC as $key=>$val){
    $dato = str_replace($val, $key, $dato);
   } 
   return $dato;
}



//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//>>>METODOS ESPECIFICOS<<<<<<
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

//Obtener todos las partidas
function dropDownGaleria($idG){
    $galeriasObj = new catGaleriasObj();
    $colTiposGalerias = $galeriasObj->ObtTodosCatGalerias();
   
    $html = '';
    $html .= '<select class="form-control" name="ip_galeria" id="ip_galeria">';
        // $html .= '<option value="">-Selecciona-</option>';
        if(count($colTiposGalerias)>0){
            foreach($colTiposGalerias as $itemGal){
                $html .= '<option value="'.$itemGal->galeriaId.'">'.$itemGal->nombre.'</option>';
            }
        }
    $html .= '</select>';
    return $html;
}
