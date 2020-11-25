<?php
/*
 *  © 2013 Framelova. All rights reserved. Privacy Policy
 *  Creado: 31/03/2017
 *  Por: MJCS
 *  Descripción: This functions  are called via Ajax
 */
$dirname = dirname(__DIR__);
include_once $dirname.'/brules/usuariosObj.php';
include_once $dirname.'/brules/utilsObj.php';
include_once $dirname.'/brules/registroDispositivosObj.php';
include_once $dirname.'/brules/gamaModelosObj.php';
include_once $dirname.'/brules/versionGeneralesObj.php';
include_once $dirname.'/brules/versionPreciosObj.php';
include_once $dirname.'/brules/versionPlanesObj.php';
include_once $dirname.'/brules/versionRequisitosObj.php';
include_once $dirname.'/brules/versionColoresObj.php';
include_once $dirname.'/brules/versionZonasObj.php';
include_once $dirname.'/brules/versionZonasActivasObj.php';
include_once $dirname.'/brules/catActualizacionesObj.php';
include_once $dirname.'/brules/catGaleriasObj.php';
include_once $dirname.'/brules/galeriaModelosObj.php';
include_once $dirname.'/brules/promocionesObj.php';
include_once $dirname.'/brules/seguridadObj.php';
include_once $dirname.'/brules/videoModelosObj.php';

//Fisrt check the function name
$function= $_GET['funct'];
switch ($function)
{         
    case "ActivaDesactivaGModelo":
        ActivaDesactivaGModelo($_GET['callback'], $_GET['id'],$_GET['valor']);
        break;
    case "ActivaDesactivaVersionGral":
        ActivaDesactivaVersionGral($_GET['callback'], $_GET['id'],$_GET['valor']);
        break;

     case "ActivaDesactivaVersionPrecio":
        ActivaDesactivaVersionPrecio($_GET['callback'], $_GET['id'],$_GET['valor']);
        break;
    case "ActivaDesactivaVersionPlan":
        ActivaDesactivaVersionPlan($_GET['callback'], $_GET['id'],$_GET['valor']);
        break;
    case "guardarVersionPlan":
        guardarVersionPlan($_GET['callback'], $_GET['idVersionGral'], $_GET['idVersionPlan'] ,$_GET['idTipoPlan'], $_GET['caracteristicas'], $_GET['preconfigurado']);
        break;
    case "obtVersionPlan":
        obtVersionPlan($_GET['callback'], $_GET['idVersionPlan']);
        break;
    case "ActivaDesactivaVersionReq":
        ActivaDesactivaVersionReq($_GET['callback'], $_GET['id'],$_GET['valor']);
        break;
    case "guardarVersionReq":
        guardarVersionReq($_GET['callback'], $_GET['idVersionGral'], $_GET['idVersionReq'] ,$_GET['concepto'], $_GET['caracteristicas'], $_GET['preconfigurado']);
        break;
     case "obtVersionReq":
        obtVersionReq($_GET['callback'], $_GET['idVersionReq']);
        break;
     case "ActivaDesactivaVersionColor":
        ActivaDesactivaVersionColor($_GET['callback'], $_GET['id'],$_GET['valor']);
        break;
     case "eliminarColorVersion":
        eliminarColorVersion($_GET['callback'], $_GET['coloresVersId']);
        break;
    case "edicionZonas":
        edicionZonas($_GET['callback'], $_GET['opc'], $_GET['coloresVersId'], $_GET["galeriaId"], $_GET["coordenadas"], $_GET["activaZonaId"]);
        break;
    case "guardarImagenGaleria":
        guardarImagenGaleria($_GET['callback'], $_GET['zonaVersId'], $_GET['idImagen'], $_GET["titulo"], $_GET["descripcion"], $_GET["precio"]);
        break;
    case "ActivaDesactivaVersionZona":
        ActivaDesactivaVersionZona($_GET['callback'], $_GET['id'],$_GET['valor']);
        break;
    case "EliminarZona":
        EliminarZona($_GET['callback'], $_GET['zonaVersId']);
        break;
   	case "EliminarImgGal":
        EliminarImgGal($_GET['callback'], $_GET['activazonaid'], $_GET['idImagen']);
        break;
    case "updActualizacion":
        updActualizacion($_GET['callback'], $_GET['tabla']);
        break;
    case "actColorPordefecto":
        actColorPordefecto($_GET['callback'], $_GET['idColor'], $_GET['gralVersId']);
        break;    
    case "agregarGaleria":
        agregarGaleria($_GET['callback'], $_GET['galeria']);
        break;
    case "crearPlanesDesdePre":
        crearPlanesDesdePre($_GET['callback'], $_GET['ids'], $_GET['gralVersId']);
        break;
	case "crearReqDesdePre":
        crearReqDesdePre($_GET['callback'], $_GET['ids'], $_GET['gralVersId']);
        break; 
    case "ActivaDesactivaGaleriaModelo":
        ActivaDesactivaGaleriaModelo($_GET['callback'], $_GET['id'],$_GET['valor']);
        break; 
    case "guardarCatPromocion":
        guardarCatPromocion($_GET['callback'], $_GET['idPromocion'] ,$_GET['concepto'], $_GET['caracteristicas'], $_GET['preconfigurado']);
        break; 
    case "obtPromocionPorId":
        obtPromocionPorId($_GET['callback'], $_GET['idPromocion']);
        break;
    case "ActivaDesactivaPromocion":
        ActivaDesactivaPromocion($_GET['callback'], $_GET['id'],$_GET['valor']);
        break; 
    case "obtVersionPlanPorIdPadre":
        obtVersionPlanPorIdPadre($_GET['callback'], $_GET['idPadre']);
        break;   
    case "obtVersionPromoPorIdPadre":
        obtVersionPromoPorIdPadre($_GET['callback'], $_GET['idPadre']);
        break;    
    case "ActVersionReqPorCampo":        
        ActVersionReqPorCampo($_GET['callback'], $_GET['reqVersId'], $_GET['campo'], $_GET['valor']);
        break;
    case "BorradoMultiple":        
        BorradoMultiple($_GET['callback'], $_GET['idVistaCheck'], $_GET['idsCheckBorrado'], $_GET['activazonaid']);
        break;        
    // Implementado el 29/07/19
    case "encriptarStr":
        encriptarStr();
        break;
    
    // Implementado el 27/11/19    
    case "insertarInfoVideo":        
        insertarInfoVideo($_GET['callback'], $_GET['url'], $_GET['gModeloId']);
        break;
               
    default:
      echo "Not valid call";
}


function ActivaDesactivaGModelo($callback, $idModelo, $valor)
{
	$gamaModelosObj = new gamaModelosObj();
	$actualizacionesObj = new catActualizacionesObj();
	$actualizacionesObj->updActualizacion("gama_modelos");
	$res = $gamaModelosObj->ActCampoGamaModelo('activo', $valor, $idModelo);
	$return_arr = array("success"=>true, "res"=>$res);
	echo $callback . '(' . json_encode($return_arr) . ');';
}
function ActivaDesactivaVersionGral($callback, $idVersionGral, $valor)
{
	$versionGeneralesObj = new versionGeneralesObj();
	$actualizacionesObj = new catActualizacionesObj();
	$actualizacionesObj->updActualizacion("version_generales");
	$res = $versionGeneralesObj->ActCampoVersionGral('activo', $valor, $idVersionGral);
	$return_arr = array("success"=>true, "res"=>$res);
	echo $callback . '(' . json_encode($return_arr) . ');';
}
function ActivaDesactivaVersionPrecio($callback, $idVersionPrecio, $valor)
{
	$versionPreciosObj = new versionPreciosObj();
	$actualizacionesObj = new catActualizacionesObj();
	$actualizacionesObj->updActualizacion("version_precios");
	$res = $versionPreciosObj->ActCampoVersionPrecio('activo', $valor, $idVersionPrecio);
	$return_arr = array("success"=>true, "res"=>$res);
	echo $callback . '(' . json_encode($return_arr) . ');';
}
function ActivaDesactivaVersionPlan($callback, $idVersionPlan, $valor)
{
	$versionPlanObj = new versionPlanesObj();
	$actualizacionesObj = new catActualizacionesObj();

	//Borrar planes asociadas al planVersIdPadre (solo aplica cuando se desactivan)	
	if($valor==0){
        $versionPlanObj->EliminarVersionPlanPorIdPadre($idVersionPlan); //Borrar todos los planes anteriores por el id padre 		
	}
	
	$res = $versionPlanObj->ActCampoVersionPlan('activo', $valor, $idVersionPlan);
	if($res>0){
		$actualizacionesObj->updActualizacion("version_planes");
	}

	$return_arr = array("success"=>true, "res"=>$res);
	echo $callback . '(' . json_encode($return_arr) . ');';
}
function guardarVersionPlan($callback, $idVersionGral, $idVersionPlan, $idTipoPlan, $caracteristicas, $preconfigurado)
{

	//implementado el 26/07/19	(Por el mod_security)
	$caracteristicas = base64_decode($caracteristicas);	
	// echo $caracteristicas;
	// exit();
	$versionPlanObj = new versionPlanesObj();
	$versionPlanObj->gralVersId = ($idVersionGral!="")?$idVersionGral:"NULL";
	$versionPlanObj->planId = $idTipoPlan;
	$versionPlanObj->caracteristicas = $caracteristicas;
	$versionPlanObj->planVersId = $idVersionPlan;
	$versionPlanObj->preconfigurado = $preconfigurado;
	
	$res = 0;
	if($idVersionPlan == 0)
	{
		$versionPlanObj->planVersIdPadre = 0; //Cuando se crea un preconfigurado, implementado el 26/07/19
		$versionPlanObj->InsertVersionPlan();
		if($versionPlanObj->planVersId > 0)
		{
			$res = 1;
			$actualizacionesObj = new catActualizacionesObj();
			$actualizacionesObj->updActualizacion("version_planes");
		}
		else
		{
			$res = 0;
		}
	}
	else
	{
		//obtener la variable planVersIdPadre para ser seteada
		$versionPlanObj2 = new versionPlanesObj();
		$versionPlanObj2->versionPlanPorId($idVersionPlan);
		//Setear valor
		$versionPlanObj->planVersIdPadre = $versionPlanObj2->planVersIdPadre;
			
		$res = $versionPlanObj->ActVersionPlan();
		if($res > 0)
		{
			$res = 2;
			$actualizacionesObj = new catActualizacionesObj();
			$actualizacionesObj->updActualizacion("version_planes");
		}
		else
			$res = 3;
	}
	$return_arr = array("success"=>true, "res"=>$res);
	echo $callback . '(' . json_encode($return_arr) . ');';
}
function obtVersionPlan($callback, $idVersionPlan)
{
	$versionPlanObj = new versionPlanesObj();
	$versionPlanObj->versionPlanPorId($idVersionPlan);
	$return_arr = array("success"=>true, "idTipoPlan"=>$versionPlanObj->planId, "caracteristicas"=>$versionPlanObj->caracteristicas, "activo"=>$versionPlanObj->activo, "gralVersId"=>$versionPlanObj->gralVersId);
	echo $callback . '(' . json_encode($return_arr) . ');';
}
function ActivaDesactivaVersionReq($callback, $idVersionReq, $valor)
{
	$versionRequisitoObj = new versionRequisitosObj();
	$res = $versionRequisitoObj->ActCampoVersionReq('activo', $valor, $idVersionReq);
	$return_arr = array("success"=>true, "res"=>$res);
	$actualizacionesObj = new catActualizacionesObj();
	$actualizacionesObj->updActualizacion("version_requisitos");
	echo $callback . '(' . json_encode($return_arr) . ');';
}
function guardarVersionReq($callback, $idVersionGral, $idVersionReq, $concepto, $caracteristicas, $preconfigurado)
{
	//implementado el 26/07/19	(Por el mod_security)	
	$caracteristicas = base64_decode($caracteristicas);	
	// echo $caracteristicas;
	// exit();
	$versionRequisitoObj = new versionRequisitosObj();
	$versionRequisitoObj->gralVersId = ($idVersionGral!="")?$idVersionGral:"NULL";
	$versionRequisitoObj->concepto = $concepto;
	$versionRequisitoObj->caracteristicas = $caracteristicas;
	$versionRequisitoObj->reqVersId = $idVersionReq;
	$versionRequisitoObj->preconfigurado = $preconfigurado;
	$res = 0;
	if($idVersionReq == 0)
	{
		$versionRequisitoObj->InsertVersionReq();
		if($versionRequisitoObj->reqVersId > 0)
		{
			$res = 1;
			$actualizacionesObj = new catActualizacionesObj();
			$actualizacionesObj->updActualizacion("version_requisitos");
		}
		else
		{
			$res = 0;
		}
	}
	else
	{
		$res = $versionRequisitoObj->ActVersionReq();
		if($res > 0)
		{
			$res = 2;
			$actualizacionesObj = new catActualizacionesObj();
			$actualizacionesObj->updActualizacion("version_requisitos");
		}
		else
			$res = 3;
	}
	$return_arr = array("success"=>true, "res"=>$res, "reqVersId"=>$versionRequisitoObj->reqVersId);
	echo $callback . '(' . json_encode($return_arr) . ');';
}
function obtVersionReq($callback, $idVersionReq)
{
	$versionRequisitoObj = new versionRequisitosObj();
	$versionRequisitoObj->versionRequisitoPorId($idVersionReq);
	//Implementado el 06/03/18
	$urlCartaBuro = ($versionRequisitoObj->urlCartaBuro!="")?$versionRequisitoObj->urlCartaBuro:"";
	$urlSolicitudCred = ($versionRequisitoObj->urlSolicitudCred!="")?$versionRequisitoObj->urlSolicitudCred:"";
	
	$return_arr = array("success"=>true, "concepto"=>$versionRequisitoObj->concepto, "caracteristicas"=>$versionRequisitoObj->caracteristicas, "activo"=>$versionRequisitoObj->activo, "gralVersId"=>$versionRequisitoObj->gralVersId,
						"urlCartaBuro"=>$urlCartaBuro, "urlSolicitudCred"=>$urlSolicitudCred);
	echo $callback . '(' . json_encode($return_arr) . ');';
}
function ActivaDesactivaVersionColor($callback, $idVersionColor, $valor)
{
	$versionColorObj = new versionColoresObj();
	$res = $versionColorObj->ActCampoVersionColor('activo', $valor, $idVersionColor);
	$return_arr = array("success"=>true, "res"=>$res);
	$actualizacionesObj = new catActualizacionesObj();
	$actualizacionesObj->updActualizacion("version_colores");
	echo $callback . '(' . json_encode($return_arr) . ');';
}
function eliminarColorVersion($callback, $coloresVersId)
{
	$dirname = dirname(__DIR__);
	include $dirname.'/common/config.php';
	$versionColorObj = new versionColoresObj();
	$versionColorObj->versionColorPorId($coloresVersId);
	
	//Implementado 08/03/18
	//>>Borrar zonas y zonas activas asociadas al color	
		//>>Borrar el grupo de galeria que tiene asociado
		$verZonaObj = new versionZonasObj();
		//Obtener los ids de las zonas por coloresVersId
	    $verZonaObj->idsZonasVersPorIdsColor($coloresVersId);
	    $idsZonaVers = $verZonaObj->idsZonaVers;
	    // echo "es :".$idsZonaVers.'<br/>';

	    $verZonaActivaObj = new versionZonasActivasObj();
	    //Obtener los ids de las zonas activar por zonaVersId
	    $verZonaActivaObj->idsZonasActivasVersPorIdsZona($idsZonaVers);
	    $idsZonaActivaVers = $verZonaActivaObj->idsZonaActivaVers;
	    // echo "es :".$idsZonaActivaVers.'<br/>';

	    //>>Proceso para eliminar registros escalonados<<<
	    //Eliminar zonas activas
	    if($idsZonaActivaVers!=""){
	        $borrarZA = $verZonaActivaObj->EliminarVersionZonaActiva($idsZonaActivaVers);        
	        if($borrarZA!=0){ 
	        	$actualizacionesObj = new catActualizacionesObj();
	            $actualizacionesObj->updActualizacion("version_zonasactivas");
	        }
	    }
	    //Eliminar zonas
	    if($idsZonaVers!=""){
	        $borrarZ = $verZonaObj->EliminarVersionZona($idsZonaVers);
	        if($borrarZ!=0){
	        	$actualizacionesObj = new catActualizacionesObj();
	            $actualizacionesObj->updActualizacion("version_zonas");
	        }
	    }
	//>>Fin borrar zonas y zonas activas asociadas al color	

	$destino1 = $dirname.str_replace("..", "", $versionColorObj->imagenAuto);
	if(file_exists($destino1))
	{
		unlink($destino1);
	}
	$destino2 = $dirname.str_replace("..", "", $versionColorObj->imagenColor);
	if(file_exists($destino2))
	{
		unlink($destino2);
	}

	$res = $versionColorObj->eliminarVersionColor($coloresVersId);
	$actualizacionesObj = new catActualizacionesObj();
	$actualizacionesObj->updActualizacion("version_colores");
	$colores = $versionColorObj->ObtVersionesColores(false, $versionColorObj->gralVersId);
	$bloquearTab = false;
	if(count($colores) == 0)
	{
		$bloquearTab = true;
	}
	// $res = 0;
	$return_arr = array("success"=>true, "res"=>$res, "bloquearTab"=>$bloquearTab);
	echo $callback . '(' . json_encode($return_arr) . ');';
}
function edicionZonas($callback, $opc, $coloresVersId, $galeriaId, $coordenadas, $activaZonaId)
{
	$dirname = dirname(__DIR__);
	include $dirname.'/common/config.php';
	$versionZonasObj = new versionZonasObj();
	$versionZonasActivasObj = new versionZonasActivasObj();
	$versionZonasObj->coloresVersId = $coloresVersId;
	$versionZonasObj->galeriaId = $galeriaId;
	$result = 0;
	$existe = false;
	$update = 0;
	$update2 = 0;
	if($opc == 1)
	{		
		$versionZonasObj->versionZonaPorColorGaleria($coloresVersId, $galeriaId);
		if($versionZonasObj->zonaVersId > 0)
		{
			// $existe = true;
		}
		// if(!$existe)
		// {
		$versionZonasObj->InsertVersionZona();
		if($versionZonasObj->zonaVersId > 0)
		{
			$versionZonasActivasObj->zonaVersId = $versionZonasObj->zonaVersId;
			$versionZonasActivasObj->coordenada = $coordenadas;
			$versionZonasActivasObj->InsertVersionZonaActiva();
			if($versionZonasActivasObj->activaZonaId > 0)				
			{
				$result = $versionZonasActivasObj->activaZonaId;
				$actualizacionesObj = new catActualizacionesObj();
				$actualizacionesObj->updActualizacion("version_zonas");
				$actualizacionesObj = new catActualizacionesObj();
				$actualizacionesObj->updActualizacion("version_zonasactivas");
			}
		}
		// }
	}
	else
	{		
		$versionZonasActivasObj->versionZonaActivaPorId($activaZonaId); 
		$versionZonasActivasObj->activaZonaId = $activaZonaId;
		$versionZonasActivasObj->zonaVersId = $versionZonasActivasObj->zonaVersId;
		$versionZonasActivasObj->coordenada = $coordenadas;
		$update = $versionZonasActivasObj->ActVersionZonaActiva();
		$versionZonasObj->versionZonaPorId($versionZonasActivasObj->zonaVersId);
		$versionZonasObj->galeriaId = $galeriaId;
		$update2 = $versionZonasObj->ActVersionZona();
		$actualizacionesObj = new catActualizacionesObj();
		$actualizacionesObj->updActualizacion("version_zonas");
		$actualizacionesObj = new catActualizacionesObj();
		$actualizacionesObj->updActualizacion("version_zonasactivas");
	}
	$return_arr = array("success"=>true, "res"=>$result, "existe"=>$existe, "opc"=>$opc, "update"=>$update, "galeriaId"=>$galeriaId, "update2"=>$update2);
	echo $callback . '(' . json_encode($return_arr) . ');';
}
function guardarImagenGaleria($callback, $zonaVersId, $idImagen, $titulo, $descripcion, $precio)
{
	$versionZonasActivasObj = new versionZonasActivasObj();
	$versionZonasActivasObj->versionZonaActivaPorZona($zonaVersId);
	$jsonOriginal = $versionZonasActivasObj->imagenes;
	$objJson = json_decode($jsonOriginal);
	
	$keyUpd = -1;
	foreach ($objJson as $key => $obj) {
		if($obj->idImagen == $idImagen)
		{
			// echo "entre a if<br>";
			$keyUpd = $key;
		}
		else
		{
			// echo "no entre a if<br>";
		}
	}
	// echo $keyUpd."<br>";
	if($keyUpd != -1) 
	{
		// echo "<pre>";print_r($objJson);echo"</pre>";
		// $descripcion = cadenaEspeciales($descripcion);
		$descripcion = str_replace("\n", "", $descripcion);
		$descripcion = str_replace("\"", "&quot;", $descripcion);
		$titulo = cadenaEspeciales($titulo);
		$precio = removerComas($precio);
		$objJson[$keyUpd]->titulo = $titulo;
		$objJson[$keyUpd]->descripcion = $descripcion;
		$objJson[$keyUpd]->precio = $precio;
	}
	$res = $versionZonasActivasObj->ActCampoVersionZonaA("imagenes",json_encode($objJson),$versionZonasActivasObj->activaZonaId);
	$actualizacionesObj = new catActualizacionesObj();
	$actualizacionesObj->updActualizacion("version_zonasactivas");
	$return_arr = array("success"=>true, "res"=>$res);
	echo $callback . '(' . json_encode($return_arr) . ');';
}
function ActivaDesactivaVersionZona($callback, $zonaVersId, $valor)
{
	$versionZonasObj = new versionZonasObj();
	$res = $versionZonasObj->ActCampoVersionZona('activo', $valor, $zonaVersId);
	$return_arr = array("success"=>true, "res"=>$res);
	$actualizacionesObj = new catActualizacionesObj();
	$actualizacionesObj->updActualizacion("version_zonas");
	echo $callback . '(' . json_encode($return_arr) . ');';
}
function EliminarZona($callback, $zonaVersId)
{
	$versionZonasObj = new versionZonasObj();
	$versionZonasActivasObj = new versionZonasActivasObj();
	$versionZonasActivasObj->versionZonaActivaPorZona($zonaVersId);
	$activaZonaId = $versionZonasActivasObj->activaZonaId;
	$res = $versionZonasObj->EliminarVersionZona($zonaVersId);
	$res2 = 0;
	if($res > 0)
	{
		$res2 = $versionZonasActivasObj->EliminarVersionZonaActiva($zonaVersId);
		$actualizacionesObj = new catActualizacionesObj();
		$actualizacionesObj->updActualizacion("version_zonas");
		$actualizacionesObj = new catActualizacionesObj();
		$actualizacionesObj->updActualizacion("version_zonasactivas");
	}
	$return_arr = array("success"=>true, "res"=>$res, "res2"=>$res2, "activaZonaId"=>$activaZonaId);
	echo $callback . '(' . json_encode($return_arr) . ');';
}
function EliminarImgGal($callback, $activaZonaId, $idImagen)
{
	$versionZonasActivasObj = new versionZonasActivasObj();
	$versionZonasActivasObj->versionZonaActivaPorId($activaZonaId);

	$res = $versionZonasActivasObj->eliminarImgJson($activaZonaId, $idImagen, $versionZonasActivasObj->imagenes);
	$return_arr = array("success"=>true, "res"=>$res);
	
	$actualizacionesObj = new catActualizacionesObj();
	$actualizacionesObj->updActualizacion("version_zonasactivas");
	echo $callback . '(' . json_encode($return_arr) . ');';
}
function updActualizacion($callback, $tabla)
{
	$actualizacionesObj = new catActualizacionesObj();
	$actualizacionesObj->updActualizacion($tabla);
	$return_arr = array("success"=>true);
	echo $callback . '(' . json_encode($return_arr) . ');';
}

//Metodo para seleccionar por defecto el auto que se mostrara primeo en la mesa de control en la app
function actColorPordefecto($callback, $idColor, $gralVersId)
{	
	$versionColorObj = new versionColoresObj();	
	$versionColorObj->desactivarPorGralVers('porDefecto', $gralVersId);
	$res = $versionColorObj->ActCampoVersionColor('porDefecto', 1, $idColor);

	if($res>0){
		$actualizacionesObj = new catActualizacionesObj();
		$actualizacionesObj->updActualizacion("version_colores");
		$return_arr = array("success"=>true);
	}else{
		$return_arr = array("success"=>false);
	}
	echo $callback . '(' . json_encode($return_arr) . ');';
}

//Metodo para agregar la galeria
function agregarGaleria($callback, $galeria)
{	
	$galeriaObj = new catGaleriasObj();
	$galeriaObj->nombre = $galeria;
	$galeriaObj->activo = 1;
	$res = $galeriaObj->agregarGaleria();

	if($res>0){
		$return_arr = array("success"=>true);
	}else{
		$return_arr = array("success"=>false);
	}
	echo $callback . '(' . json_encode($return_arr) . ');';
}

//Agregar planes desde el catalogo de planes preconfigurados
function crearPlanesDesdePre($callback, $ids, $gralVersId)
{		
	$arrTotal = array();	
	if($ids!=""){
		$arrIds = explode(",", $ids);
		foreach ($arrIds as $id) {
			$versionPlanObj = new versionPlanesObj();
			$versionPlanObj->versionPlanPorId($id);

			//Setear datos
			$versionPlanObj->gralVersId = $gralVersId;
			$versionPlanObj->planId = $versionPlanObj->planId;
			$versionPlanObj->caracteristicas = $versionPlanObj->caracteristicas;
			// $versionPlanObj->activo = 1;
			$versionPlanObj->preconfigurado = 0;

			//Insertar	
			$versionPlanObj->InsertVersionPlan();
			if($versionPlanObj->planVersId>0){
				$arrTotal[] = $versionPlanObj->planVersId;
			}
		}

		if(count($arrTotal)>0){
			$actualizacionesObj = new catActualizacionesObj();
			$actualizacionesObj->updActualizacion("version_planes");

			$return_arr = array("success"=>true, "arrTotal"=>$arrTotal);
		}else{
			$return_arr = array("success"=>false);	
		}
	}else{
		$return_arr = array("success"=>false);
	}
	
	echo $callback . '(' . json_encode($return_arr) . ');';
}

//Agregar requisitos desde el catalogo de requisitos preconfigurados
function crearReqDesdePre($callback, $ids, $gralVersId)
{	
	$arrTotal = array();	
	if($ids!=""){
		$arrIds = explode(",", $ids);
		foreach ($arrIds as $id) {
			$versionRequisitoObj = new versionRequisitosObj();
			$versionRequisitoObj->versionRequisitoPorId($id);

			//Setear datos	        	       	        	        
			$versionRequisitoObj->gralVersId = $gralVersId;
			$versionRequisitoObj->concepto = $versionRequisitoObj->concepto;
			$versionRequisitoObj->caracteristicas = $versionRequisitoObj->caracteristicas;
			// $versionPlanObj->activo = 1;
			$versionRequisitoObj->preconfigurado = 0;

			//Insertar	
			$versionRequisitoObj->InsertVersionReq();
			if($versionRequisitoObj->reqVersId>0){
				$arrTotal[] = $versionRequisitoObj->reqVersId;
			}
		}

		if(count($arrTotal)>0){
			$actualizacionesObj = new catActualizacionesObj();
			$actualizacionesObj->updActualizacion("version_requisitos");

			$return_arr = array("success"=>true, "arrTotal"=>$arrTotal);
		}else{
			$return_arr = array("success"=>false);	
		}
	}else{
		$return_arr = array("success"=>false);
	}
	
	echo $callback . '(' . json_encode($return_arr) . ');';
}

//Activa o desactiva las galerias desde la vista gama de modelos
function ActivaDesactivaGaleriaModelo($callback, $id, $valor)
{
	$galeriaMObj = new galeriaModelosObj();
	$actualizacionesObj = new catActualizacionesObj();	

	$res = $galeriaMObj->ActCampoGaleriaModelo('activo', $valor, $id);
	if($res>0){
		$actualizacionesObj->updActualizacion("galeria_modelos");
	}

	$return_arr = array("success"=>true, "res"=>$res);	
	echo $callback . '(' . json_encode($return_arr) . ');';
}

//Guardar las promociones
function guardarCatPromocion($callback, $idPromocion, $concepto, $caracteristicas, $preconfigurado)
{	
	$promoObj = new promocionesObj();
	$actualizacionesObj = new catActualizacionesObj();
	//implementado el 26/07/19	(Por el mod_security)	
	$caracteristicas = base64_decode($caracteristicas);	
	// echo $caracteristicas;
	// exit();
	$idVersionGral = ""; //Por defecto vacio
	$promoObj->gralVersId = ($idVersionGral!="")?$idVersionGral:"NULL";
	$promoObj->concepto = $concepto;
	$promoObj->caracteristicas = $caracteristicas;
	$promoObj->promocionId = $idPromocion;
	$promoObj->preconfigurado = $preconfigurado;	

	$res = 0;
	if($idPromocion == 0)
	{			
		$promoObj->InsertVersionPromocion();
		if($promoObj->promocionId > 0){
			$res = 1;						
		}
	}else{		
		$res = $promoObj->ActVersionPromocion();
		if($res > 0){
			$res = 1;
		}
	}

	if($res==1){
		$actualizacionesObj->updActualizacion("promociones");
		$return_arr = array("success"=>true, "res"=>$res);
	}else{
		$return_arr = array("success"=>false);
	}
	
	echo $callback . '(' . json_encode($return_arr) . ');';
}
function obtPromocionPorId($callback, $idPromocion)
{
	$promoObj = new promocionesObj();
	$promoObj->versionPromocionPorId($idPromocion);	
	$return_arr = array("success"=>true, "concepto"=>$promoObj->concepto, "caracteristicas"=>$promoObj->caracteristicas, "activo"=>$promoObj->activo, "gralVersId"=>$promoObj->gralVersId);
	echo $callback . '(' . json_encode($return_arr) . ');';
}
//Activa o desactiva las galerias desde la vista gama de modelos
function ActivaDesactivaPromocion($callback, $id, $valor)
{
	$promoObj = new promocionesObj();
	$actualizacionesObj = new catActualizacionesObj();	

	//Borrar promociones asociadas al promocionIdPadre (solo aplica cuando se desactivan)	
	if($valor==0){
		$promoObj->EliminarVersionPromocionPorIdPadre($id);
	}

	$res = $promoObj->ActCampoVersionPromocion('activo', $valor, $id);
	if($res>0){
		$actualizacionesObj->updActualizacion("promociones");
	}

	$return_arr = array("success"=>true, "res"=>$res);	
	echo $callback . '(' . json_encode($return_arr) . ');';
}

function obtVersionPlanPorIdPadre($callback, $idPadre)
{
	$verGralObj = new versionPlanesObj();
	$colVersiones = $verGralObj->versionPlanPorIdPadre($idPadre);
		
	if(count($colVersiones)>0){	
	 	$return_arr = array("success"=>true, "colVersiones"=>$colVersiones);	
	}else{
	 	$return_arr = array("success"=>false);
	}	
    
	echo $callback . '(' . json_encode($return_arr) . ');';
}


function obtVersionPromoPorIdPadre($callback, $idPadre)
{
	$promoObj = new promocionesObj();
	$colVersiones = $promoObj->versionPromoPorIdPadre($idPadre);
		
	if(count($colVersiones)>0){	
	 	$return_arr = array("success"=>true, "colVersiones"=>$colVersiones);	
	}else{
	 	$return_arr = array("success"=>false);
	}	
    
	echo $callback . '(' . json_encode($return_arr) . ');';
}

//Actualiza requisitos por campo
function ActVersionReqPorCampo($callback, $reqVersId, $campo, $valor)
{
	$versionRequisitoObj = new versionRequisitosObj();	
	$res = $versionRequisitoObj->ActCampoVersionReq($campo, $valor, $reqVersId);	
	$return_arr = array("success"=>true, "res"=>$res);
	$actualizacionesObj = new catActualizacionesObj();
	$actualizacionesObj->updActualizacion("version_requisitos");
	echo $callback . '(' . json_encode($return_arr) . ');';
}

//Eliminar varios registros (galerias)
function BorradoMultiple($callback, $idVistaCheck, $idsCheckBorrado, $activazonaid)
{
	$galeriaMObj = new galeriaModelosObj();
	$actualizacionesObj = new catActualizacionesObj();		

	//Borra imagenes de galerias desde los modelos
	if($idVistaCheck=="v_galmodelos"){
		$respGM = $galeriaMObj->EliminarGaleriaModelo($idsCheckBorrado);
	    if($respGM>0){        
	        $actualizacionesObj->updActualizacion("galeria_modelos");
			$return_arr = array("success"=>true, "idVistaCheck"=>$idVistaCheck, "idsCheckBorrado"=>$idsCheckBorrado);	        
	    }else{
	    	$return_arr = array("success"=>false);	
	    }	
	}
	//Borra imagenes de galerias desde las versiones 
	elseif($idVistaCheck=="v_galversiones") {
		$arrBorrados = array();
		$expIdsCheckBorrado = explode(",", $idsCheckBorrado);
		if(count($expIdsCheckBorrado)>0){
			foreach ($expIdsCheckBorrado as $idImagen){
				$versionZonasActivasObj = new versionZonasActivasObj();
				$versionZonasActivasObj->versionZonaActivaPorId($activazonaid);
				$resDelImgZonaAct = $versionZonasActivasObj->eliminarImgJson($activazonaid, $idImagen, $versionZonasActivasObj->imagenes);
				if($resDelImgZonaAct>0){
					$arrBorrados[] = 1;
				}
			}
			if(count($arrBorrados)>0){
				$return_arr = array("success"=>true, "idVistaCheck"=>$idVistaCheck, "idsCheckBorrado"=>$idsCheckBorrado, "activazonaid"=>$activazonaid);				
				$actualizacionesObj = new catActualizacionesObj();
				$actualizacionesObj->updActualizacion("version_zonasactivas");				
			}else{
				$return_arr = array("success"=>false);
			}
		}else{
			$return_arr = array("success"=>false);
		}
	}
	else{
		$return_arr = array("success"=>false);
	}
	echo $callback . '(' . json_encode($return_arr) . ');';
}

// Implementdo el 29/07/19
//Encripta una cadena
function encriptarStr(){
  $callback = (isset($_GET['callback']) !="")?$_GET['callback']:"";
  $cadena = (isset($_GET['cadena']) !="")?$_GET['cadena']:"";
  $segObj = new seguridadObj();
  $cadena = $segObj->encriptarCadena($cadena);
  if($cadena!=""){
  	$return_arr = array("success"=>true, "cadena"=>$cadena);
  }else{
  	$return_arr = array("success"=>false);
  }
  echo $callback . '(' . json_encode($return_arr) . ');';  
}


//Metodo para insertar la informacion del video
function insertarInfoVideo($callback, $url, $gModeloId)
{		
	$actualizacionesObj = new catActualizacionesObj();
	$videoMObj = new videoModelosObj();
	$videoMObj->gModeloId = $gModeloId;
    $videoMObj->titulo = "";
    $videoMObj->descripcion = "";
    // $videoMObj->url = $url;
    $videoMObj->url = base64_decode($url);
    $videoMObj->activo = 1;
	$videoMObj->InsertVideoModelo();

	if($videoMObj->videoModeloId>0){
		$return_arr = array("success"=>true);
        $actualizacionesObj->updActualizacion("video_modelos");
	}else{
		$return_arr = array("success"=>false);
	}
	echo $callback . '(' . json_encode($return_arr) . ');';
}


?>