<?php
/*
 *  © 2017 Framelova. All rights reserved. Privacy Policy
 *  Creado: 01/12/2017
 *  Por: JCR
 *  Descripción: This is called via Ajax to return data type json to app
 */
include_once '../brules/utilsObj.php';
include_once '../brules/usuariosObj.php';
include_once '../brules/catConfiguracionesObj.php';
include_once '../brules/EmailFunctionsObj.php';
include_once '../brules/sesionInvalidaObj.php';
include_once '../brules/registroDispositivosObj.php';
include_once '../brules/catAyudasObj.php';
//Nuevos
include_once '../brules/gamaModelosObj.php';
include_once '../brules/versionGeneralesObj.php';
include_once '../brules/versionColoresObj.php';
include_once '../brules/versionPlanesObj.php';
include_once '../brules/versionPreciosObj.php';
include_once '../brules/versionRequisitosObj.php';
include_once '../brules/versionZonasObj.php';
include_once '../brules/versionZonasActivasObj.php';
include_once '../brules/catProspectosObj.php';
include_once '../brules/catActualizacionesObj.php';
include_once '../brules/catTransmisionesObj.php';
include_once '../brules/galeriaModelosObj.php';
include_once '../brules/catTasasObj.php';
include_once '../brules/promocionesObj.php';
include_once '../brules/catAgenciasObj.php';
include_once '../brules/videoModelosObj.php';
include_once '../brules/estadisticasAppObj.php';
include_once '../brules/inventariosObj.php';
include_once '../brules/inventarioPermisosObj.php';


//Fisrt check the function name
$function="";
if(isset($_GET['funct'])){
  $function= $_GET['funct'];
}
elseif(isset($_POST['funct'])){
  $function= $_POST['funct'];
}

switch ($function)
{  
    case "loginUser":
        loginUser($_GET['callback'], $_GET['email'], $_GET['password'], $_GET['idRegDispositivo'], $_GET['plataforma']);
      break;
    case "configuracionPorId":
        configuracionPorId($_GET['callback'], $_GET['idConf']);
      break;
      case "configuracionesPorId":
        configuracionesPorId($_GET['callback'], $_GET['idConf']);
      break;
    case "sincronizacionAppWeb":
        sincronizacionAppWeb($_GET['callback'], $_GET['arrActualizaciones'], $_GET['updApp']);
      break;      
    case "enviarCorreoCaracVersion":
        enviarCorreoCaracVersion($_GET['callback'], $_GET['gralVersId'], $_GET['email'], $_GET['nombreVendedor'], $_GET['emailVendedor'], $_GET['idVendedor']);
      break;
    case "enviarCorreoPlanes":
        enviarCorreoPlanes($_GET['callback'], $_GET['gralVersId'], $_GET['email'], $_GET['nombreVendedor'], $_GET['emailVendedor'], $_GET['idVendedor'], $_GET['idsPlan']);
      break;
    case "enviarCorreoRequisitos":
        enviarCorreoRequisitos($_GET['callback'], $_GET['gralVersId'], $_GET['email'], $_GET['idsReq'], $_GET['nombreVendedor'], $_GET['emailVendedor'], $_GET['idVendedor']);
      break; 
    case "enviarCorreoPrecios":
        enviarCorreoPrecios($_GET['callback'], $_GET['gralVersId'], $_GET['email'], $_GET['nombreVendedor'], $_GET['emailVendedor']);
      break;    
    case "salvarProspectos":
        salvarProspectos($_GET['callback']);
      break;
    case "enviarCorreoCotizacion":
        enviarCorreoCotizacion($_GET['callback'], $_GET['email'], $_GET['gralVersId'], $_GET['modelo'], $_GET['version'], $_GET['costo'], $_GET['sa'], $_GET['sv'], $_GET['sd'],
                              $_GET['cxa'], $_GET['subtotal'], $_GET['ptcEnganche'], $_GET['enganche'], $_GET['taf'],                               
                              $_GET['m12'], $_GET['m24'], $_GET['m36'], $_GET['m48'], $_GET['m60'], $_GET['textoPrecio'],
                              $_GET['nombreVend'], $_GET['emailVend'], $_GET['nombreCliente'], $_GET['emailCliente'] , $_GET['telCliente'],
                              $_GET['sge']
                              );
      break;
    case "eliminarArchivo":
        eliminarArchivo($_GET['callback'], $_GET['ruta']);
       break;  
    case "obtProspectosPorIdUsr":
        obtProspectosPorIdUsr($_GET['callback'], $_GET['idUsuario']);
       break;
    case "salvarPedidoVenta":
        salvarPedidoVenta($_GET['callback']);
      break;
    case "salvarEstadistica":
        salvarEstadistica();
      break;  

    //Consultas a WebServices
    case "busquedaInventario":
        busquedaInventario($_GET['callback'], $_GET['idUsuario'], $_GET['vin'], $_GET['modelo'], $_GET['year'], $_GET['version'], $_GET['color'], $_GET['codigo']);
      break;  
    case "rptProspectos":
        rptProspectos($_GET['callback'], $_GET['idUsuario']);
      break;
    case "inventarioPermisos":
        inventarioPermisos($_GET['callback']);
      break;  
        

    //Consultas a WebServices
    case "busquedaInventario2":
        busquedaInventario2($_GET['callback'], $_GET['idUsuario'], $_GET['vin'], $_GET['modelo'], $_GET['year'], $_GET['version'], $_GET['color'], $_GET['codigo']);
      break;  



    //QUITAR
     // case "obtImagenesSincronizar":
     //  obtImagenesSincronizar($_GET['strColGModelos'], $_GET['strColVerGral'], $_GET['strColVerColores'], $_GET['strColVerZonasActivas'], $_GET['strColGaleriasModelo'], $_GET['strColVerPlanes'], $_GET['strColVerRequisitos'], $_GET['strColPromociones']);
     // break;
    
        
    default:
      echo "Not valid call";
}

/*
 *  autenticacion de usuario
*/

function loginUser($callback, $email, $password, $idRegDispositivo, $plataforma){
    $userObj = new usuariosObj();
    $regDispObj = new registroDispositivo();
    $userObj->LoginUser(stripslashes(trim($email)), stripslashes(trim($password)));
    
    //verifica que el usuario exista
    if($userObj->idUsuario>0){
        //solo pueden acceder los agentes de venta
        if($userObj->idRol==3){
          $arr = array("success"=>true, "idUsr"=>$userObj->idUsuario, "userName"=>$userObj->nombre,
                     "password"=>$userObj->password, "active"=>$userObj->activo, "email"=>$email, 
                     "idRol"=>$userObj->idRol, "agenciaId"=>$userObj->agenciaId            
              );        
        }else{
            $arr = array("success"=>false);
        }      
    }else{
       $arr = array("success"=>false);
    }
    echo $callback . '(' . json_encode($arr) . ');';
}

//Obtener el aviso de privaciadad
function configuracionPorId($callback, $idConf){
    $confObj = new catConfiguracionesObj();
    $confObj->ObtConfiguracionByID($idConf);
    //verifica
    if($confObj->idConfiguracion>0){
         $arr = array("success"=>true, "idConf"=>$idConf, "nombre"=>$confObj->nombre, "valor"=>$confObj->valor);
     }else{
         $arr = array("success"=>false);
     }
     echo $callback . '(' . json_encode($arr) . ');';
}

//Obtener configuraciones por id (menu seminuevos, leyenda imagenes ilustrativas)
function configuracionesPorId($callback, $idConf){
    $confObj = new catConfiguracionesObj();    
    // $colConfig  = $confObj->ObtConfiguracionesPorIds(true, 1, $idConf);
    $colConfig  = $confObj->ObtConfiguracionesPorIds(true, 1);
    $arr = array("success"=>false);
        
    if(count($colConfig)>0){
      $arr = array("success"=>true, "colConfig"=>$colConfig);
    }

    echo $callback . '(' . json_encode($arr) . ');';
}



//Metodo para descargar todos los datos en la aplicacion
function sincronizacionAppWeb($callback, $arrActualizaciones, $updApp, $opc=0){
  include "../common/config.php";
  $userObj = new usuariosObj();
  $gMObj = new gamaModelosObj();
  $verGralObj = new versionGeneralesObj();
  $verColoresObj = new versionColoresObj();
  $verPlanesObj = new versionPlanesObj();
  $verPreciosObj = new versionPreciosObj();
  $verRequisitosObj = new versionRequisitosObj();
  $verZonasObj = new versionZonasObj();
  $verZonasActivasObj = new versionZonasActivasObj();
  $catActObj = new catActualizacionesObj();
  $galeriaMObj = new galeriaModelosObj();
  $tasaObj = new catTasasObj();
  $promoObj = new promocionesObj();
  $videoMObj = new videoModelosObj();
  $agenciaObj = new catAgenciasObj();
  
  // $arrAct = json_decode($arrActualizaciones);
  // echo "<pre>";
  // print_r($arrAct);
  // echo "</pre>";
  // exit();

  if($updApp==1){
    //Descargar toda la informacion por ser la primera vez
    $strColGModelos = true;
    $strColVerGral = true;
    $strColVerColores = true;
    $strColVerPlanes = true;
    $strColVerPrecios = true;
    $strColVerRequisitos = true;
    $strColVerZonas = true;
    $strColVerZonasActivas = true;
    $strColUsuarios = true;
    $strColGaleriasModelo = true;
    $strColTasas = true;
    $strColPromociones = true;
    $strColVideosModelo = true;
    $strColAgencias = true;
  }else{
    //Verificar si tiene actualizacion en alguna tabla
    if($arrActualizaciones!=""){
      //Obtener tabla de actualizacion para comparar
      // $colActualizaciones = $catActObj->ObtActualizaciones(true);
      $arrAct = json_decode($arrActualizaciones);
      foreach ($arrAct as $eleAct){
        $catActObj = new catActualizacionesObj();
        $catActObj->ObtDatosActualizacionPorTabla($eleAct->tabla);
        // echo $eleAct->tabla .'<br/>';
        // echo "Antes: ".$eleAct->fechaActualizacion ." - despues: ". $catActObj->fechaActualizacion.'<br/>';        

        switch ($eleAct->tabla) {
          case 'usuarios': $strColUsuarios =  ($eleAct->fechaActualizacion != $catActObj->fechaActualizacion) ?true :false; break;
          case 'gama_modelos': $strColGModelos =  ($eleAct->fechaActualizacion != $catActObj->fechaActualizacion) ?true :false; break;
          case 'version_generales': $strColVerGral =  ($eleAct->fechaActualizacion != $catActObj->fechaActualizacion) ?true :false; break;
          case 'version_planes': $strColVerPlanes =  ($eleAct->fechaActualizacion != $catActObj->fechaActualizacion) ?true :false; break;
          case 'version_precios': $strColVerPrecios =  ($eleAct->fechaActualizacion != $catActObj->fechaActualizacion) ?true :false; break;
          case 'version_requisitos': $strColVerRequisitos =  ($eleAct->fechaActualizacion != $catActObj->fechaActualizacion) ?true :false; break;
          case 'version_colores': $strColVerColores =  ($eleAct->fechaActualizacion != $catActObj->fechaActualizacion) ?true :false; break;       
          case 'version_zonas': $strColVerZonas =  ($eleAct->fechaActualizacion != $catActObj->fechaActualizacion) ?true :false; break;
          case 'version_zonasactivas': $strColVerZonasActivas =  ($eleAct->fechaActualizacion != $catActObj->fechaActualizacion) ?true :false; break;
          case 'galeria_modelos': $strColGaleriasModelo =  ($eleAct->fechaActualizacion != $catActObj->fechaActualizacion) ?true :false; break;
          case 'cat_tasas': $strColTasas =  ($eleAct->fechaActualizacion != $catActObj->fechaActualizacion) ?true :false; break;
          case 'promociones': $strColPromociones =  ($eleAct->fechaActualizacion != $catActObj->fechaActualizacion) ?true :false; break;
          case 'video_modelos': $strColVideosModelo =  ($eleAct->fechaActualizacion != $catActObj->fechaActualizacion) ?true :false; break;
          case 'cat_agencias': $strColAgencias =  ($eleAct->fechaActualizacion != $catActObj->fechaActualizacion) ?true :false; break;
        }
      }
      // echo "<pre>";
      // print_r($arrAct);
      // echo "</pre>";
      // exit();

      //Descargar toda la informacion por ser la primera vez REMOVER 
      //$strColGModelos = true;
      //$strColVerGral = true;
      //$strColVerColores = true;
      //$strColVerPlanes = true;
      //$strColVerPrecios = true;
      //$strColVerRequisitos = true;
      //$strColVerZonas = true;
      //$strColVerZonasActivas = true;
      //$strColUsuarios = true;
    }else{
      //Descargar toda la informacion por ser la primera vez
      $strColGModelos = true;
      $strColVerGral = true;
      $strColVerColores = true;
      $strColVerPlanes = true;
      $strColVerPrecios = true;
      $strColVerRequisitos = true;
      $strColVerZonas = true;
      $strColVerZonasActivas = true;
      $strColUsuarios = true;
      $strColGaleriasModelo = true;
      $strColTasas = true;
      $strColPromociones = true;
      $strColVideosModelo = true;
      $strColAgencias = true;
    }
  }
  
  //seteo de arreglos
  $colGModelos = array(); $colVerGral = array(); $colVerColores = array();
  $colVerPlanes = array(); $colVerPrecios = array(); $colVerRequisitos = array();
  $colVerZonas = array(); $colVerZonasActivas = array(); $colUsuarios = array();
  $colGaleriasModelo = array(); $colTasas = array(); $colPromociones = array();
  $colVerPreciosOrdenados = array(); $colVideosModelo = array(); $colAgencias = array();

  //Si hay cambios actualiza
  if($strColGModelos==true){
    //Col gama de modelos
    $colGModelos = $gMObj->ObtTodosGamaModelos(true, 1); 
    // if(count($colGModelos)>0){
    //   //convertir la imagen del modelo en base 64    
    //   foreach($colGModelos as $elem){
    //       $imagen = ($elem->imagen!="") ?imagenBase64($elem->imagen):"";
    //       $colGModelos[$elem->gModeloId]->imagen = $imagen;        
    //   }
    // }
  }

  //Si hay cambios actualiza
  if($strColVerGral==true){
    //Col versiones generales
    $colVerGral = $verGralObj->ObtVersionesGenerales(true, 1);
    // if(count($colVerGral)>0){
    //   //convertir la imagen del modelo en base 64    
    //   foreach($colVerGral as $elem){
    //      $imagen = ($elem->imagen!="") ?imagenBase64($elem->imagen):"";
    //      $colVerGral[$elem->gralVersId]->imagen = $imagen;       
    //   }
    // }    

    //caracteristicas //DESHABILITADO
    // if(count($colVerGral)>0){
    //    foreach($colVerGral as $elem){
    //       $colVerGral[$elem->gralVersId]->caracteristicas = str_replace("../upload/", $siteURL."upload/", $elem->caracteristicas);
    //    } 
    // }
  }

  //Si hay cambios actualiza
  if($strColVerColores==true){
    //Col de versiones colores
    $colVerColores = $verColoresObj->ObtVersionesColores(true, "", 1); 
    // if(count($colVerColores)>0){
    //   //convertir la imagen del modelo en base 64    
    //   foreach($colVerColores as $elem){
    //      $imagenAuto = ($elem->imagenAuto!="") ?imagenBase64($elem->imagenAuto):"";
    //      $imagenColor = ($elem->imagenColor!="") ?imagenBase64($elem->imagenColor):"";
    //      $colVerColores[$elem->coloresVersId]->imagenAuto = $imagenAuto;
    //      $colVerColores[$elem->coloresVersId]->imagenColor = $imagenColor;
    //   }
    // }
  }

  //Si hay cambios actualiza
  if($strColVerPlanes==true){
    //Col de versiones planes
    $colVerPlanes = $verPlanesObj->ObtVersionesPlanes(true, "", 1);    
    //caracteristicas //DESHABILITADO
    // if(count($colVerPlanes)>0){
    //    foreach($colVerPlanes as $elemP){
    //       $colVerPlanes[$elemP->planVersId]->caracteristicas = str_replace("../upload/", $siteURL."upload/", $elemP->caracteristicas);
    //    } 
    // }
  }

  //Si hay cambios actualiza
  if($strColVerPrecios==true){
    //Col de versiones precios
    $colVerPrecios = $verPreciosObj->ObtVersionesPrecios(true, "", 1);
  }

  //Si hay cambios actualiza  
  if($strColVerRequisitos==true){
    //Col de versiones requisitos
    $colVerRequisitos = $verRequisitosObj->ObtVersionesRequisitos(true, "", 1, 1);    
    //caracteristicas
    if(count($colVerRequisitos)>0){
       foreach($colVerRequisitos as $elemC){
          // $colVerRequisitos[$elemC->reqVersId]->caracteristicas = str_replace("../upload/", $siteURL."upload/", $elemC->caracteristicas); //DESHABILITADO
          // Implementado el 06/03/18
          if($elemC->urlCartaBuro!=""){
            $colVerRequisitos[$elemC->reqVersId]->urlCartaBuro = str_replace("../upload/", $siteURL."upload/", $elemC->urlCartaBuro);
          }
          if($elemC->urlSolicitudCred!=""){
            $colVerRequisitos[$elemC->reqVersId]->urlSolicitudCred = str_replace("../upload/", $siteURL."upload/", $elemC->urlSolicitudCred);
          }
       } 
    }
  }

  //Si hay cambios actualiza
  if($strColVerZonas==true){
    //Col de versiones requisitos
    $colVerZonas = $verZonasObj->ObtVersionesZonas(true);
  }

  //Si hay cambios actualiza  
  if($strColVerZonasActivas==true){
    //Col de zonas activas
    $colVerZonasActivas = $verZonasActivasObj->ObtVersionesZonasActivas(true);
    // if(count($colVerZonasActivas)>0){
    //   //convertir la imagen del modelo en base 64    
    //   foreach($colVerZonasActivas as $elem){
    //      if($elem->imagenes!=""){
    //         $elemImagenes = json_decode($elem->imagenes);
            
    //         //convertir la imagen de la zona activa en base 64          
    //         $jsonImagen = array();
    //         foreach($elemImagenes as $elemZA){
    //           $imagen = ($elemZA->imagen!="") ?imagenBase64($elemZA->imagen):"";
    //           $jsonImagen[] = '{"titulo":"'.$elemZA->titulo.'","descripcion":"'.$elemZA->descripcion.'","imagen":"'.$imagen.'"}';          
    //         }      
    //         $colVerZonasActivas[$elem->activaZonaId]->imagenes = "[".implode(",", $jsonImagen)."]";
    //      }
    //   }
    // }
  }

  //Si hay cambios actualiza
  if($strColUsuarios==true){
    //Col de usuarios  
    $colUsuarios = $userObj->obtTodosUsuarios(true);
  }

  //Si hay cambios actualiza
  if($strColGaleriasModelo==true){
    //Col de galerias por modelo  
    $colGaleriasModelo = $galeriaMObj->ObtGaleriasModelo(true, 1);    
  }

  //Si hay cambios actualiza
  if($strColTasas==true){
    //Col de tasas de interes
    $colTasas = $tasaObj->ObtTodasTasas(true, 1);    
  }

  //Si hay cambios actualiza
  if($strColPromociones==true){
    //Col de promociones    
    $colPromociones = $promoObj->ObtVersionesPromociones(true, "", 1);
    // //DESHABILITADO
    // if(count($colPromociones)>0){
    //     foreach($colPromociones as $elemPromo){
    //     $colPromociones[$elemPromo->promocionId]->caracteristicas = str_replace("../upload/", $siteURL."upload/", $elemPromo->caracteristicas);
    //   }
    // }    
  }

  //Obtener el objeto de arreglos de conceptos-fuentes
  //Si hay cambios actualiza
  if($strColVerPrecios==true){    
    $colVerPreciosOrdenados = obtPreciosOrdenados();    
  }

  //Si hay cambios actualiza imp. 27/11/19
  if($strColVideosModelo==true){
    //Col de videos por modelo
    $colVideosModelo = $videoMObj->ObtVideosModelo(true, 1);
  }

  //Si hay cambios actualiza imp. 04/02/20
  if($strColAgencias==true){
    //Col de agencias
    $colAgencias = $agenciaObj->ObtTodasAgencias(true, 1);
  }

	
  //Obtener las rutas de paquetes de imagenes para descargar
  $colImgSincronizar = obtImagenesSincronizar($strColGModelos, $strColVerGral, $strColVerColores, $strColVerZonasActivas, $strColGaleriasModelo, $strColVerPlanes, $strColVerRequisitos, $strColPromociones, $strColVideosModelo, $strColAgencias);
  $strColImgSincronizar = false;
  if(count($colImgSincronizar)>0){
  	$strColImgSincronizar = true;
  }

  
  //Col de actualizaciones
  $colActualizaciones = $catActObj->ObtActualizaciones(true);

  // echo "<pre>";
  // // print_r($colActualizaciones);   
  // print_r($colPromociones);
  // echo "</pre>";
  // exit();

   $arr = array("arrGModelos"=>array("act"=>$strColGModelos, "colGModelos"=>$colGModelos),
                "arrVerGral"=>array("act"=>$strColVerGral, "colVerGral"=>$colVerGral),
                "arrVerColores"=>array("act"=>$strColVerColores, "colVerColores"=>$colVerColores),
                "arrVerPlanes"=>array("act"=>$strColVerPlanes, "colVerPlanes"=>$colVerPlanes),
                "arrVerPrecios"=>array("act"=>$strColVerPrecios, "colVerPrecios"=>$colVerPrecios),
                "arrVerRequisitos"=>array("act"=>$strColVerRequisitos, "colVerRequisitos"=>$colVerRequisitos),
                "arrVerZonas"=>array("act"=>$strColVerZonas, "colVerZonas"=>$colVerZonas),
                "arrVerZonasActivas"=>array("act"=>$strColVerZonasActivas, "colVerZonasActivas"=>$colVerZonasActivas),
                "arrUsuarios"=>array("act"=>$strColUsuarios, "colUsuarios"=>$colUsuarios),
                "arrGaleriasModelo"=>array("act"=>$strColGaleriasModelo, "colGaleriasModelo"=>$colGaleriasModelo),
                "arrTasas"=>array("act"=>$strColTasas, "colTasas"=>$colTasas),
                "arrPromociones"=>array("act"=>$strColPromociones, "colPromociones"=>$colPromociones),
                "arrVerPreciosOrdenados"=>array("act"=>$strColVerPrecios, "colVerPreciosOrdenados"=>$colVerPreciosOrdenados),
                "arrVideosModelo"=>array("act"=>$strColVideosModelo, "colVideosModelo"=>$colVideosModelo),
                "arrAgencias"=>array("act"=>$strColAgencias, "colAgencias"=>$colAgencias),
                "arrImgSincronizar"=>array("act"=>$strColImgSincronizar, "colImgSincronizar"=>$colImgSincronizar),
                "arrActualizaciones"=>array("act"=>true, "colActualizaciones"=>$colActualizaciones),
               );
  
  //si es 1 entonces regresar el arreglo de lo contrario  regresa en json  
  // $opc=1;
  if($opc==1){
    return $arr;
    // echo "<pre>";print_r($arr);echo "</pre>";
  }else{
    echo $callback . '(' . json_encode($arr) . ');';
  }
}

//Obtener todas las imagenes y comprimirlas en un zip
function obtImagenesSincronizar($strColGModelos, $strColVerGral, $strColVerColores, $strColVerZonasActivas, $strColGaleriasModelo, $strColVerPlanes, $strColVerRequisitos, $strColPromociones, $strColVideosModelo, $strColAgencias){
    include "../common/config.php";
    $userObj = new usuariosObj();
    $gMObj = new gamaModelosObj();
    $verGralObj = new versionGeneralesObj();
    $verColoresObj = new versionColoresObj();
    $verPlanesObj = new versionPlanesObj();
    $verPreciosObj = new versionPreciosObj();
    $verRequisitosObj = new versionRequisitosObj();
    $verZonasObj = new versionZonasObj();
    $verZonasActivasObj = new versionZonasActivasObj();
    $catActObj = new catActualizacionesObj();
    $galeriaMObj = new galeriaModelosObj();
    $tasaObj = new catTasasObj();
    $promoObj = new promocionesObj();
    $videoMObj = new videoModelosObj();
    $agenciaObj = new catAgenciasObj();

    //seteo de arreglos
    $colGModelos = array(); $colVerGral = array(); $colVerColores = array();
    $colVerPlanes = array(); $colVerPrecios = array(); $colVerRequisitos = array();
    $colVerZonas = array(); $colVerZonasActivas = array(); $colUsuarios = array();
    $colGaleriasModelo = array(); $colTasas = array(); $colPromociones = array();
    $colVideosModelo = array(); $colAgencias = array();

    $arrImgTmp = array(); //Arreglo de nombres de los archivos a empaquetar
    $arrUrlPkg = array(); //Arreglo de las rutas de los archivos empaquetados
    $rutasCaracImgTmp = array();
    $rutasImgExtr = array();


    if($strColGModelos==true){
	    $colGModelos = $gMObj->ObtTodosGamaModelos(true, 1); 
	    if(count($colGModelos)>0){
	       //obtener las imagenes
	       foreach($colGModelos as $elem){
	           if($elem->imagen!="" && file_exists($elem->imagen)){              
	              $arrImgTmp[] = $elem->imagen;
	           }
	       }
	    }
	}

    //Col versiones generales
    if($strColVerGral==true){
	    $colVerGral = $verGralObj->ObtVersionesGenerales(true, 1);     
	    if(count($colVerGral)>0){
	       //obtener las imagenes 
	       foreach($colVerGral as $elem){
	          if($elem->imagen!="" && file_exists($elem->imagen)){
	              $arrImgTmp[] = $elem->imagen;
	          }

            //Obtener de las caracteristicas implementado el 20/04/18
            if($elem->caracteristicas!=""){
              @preg_match_all('~<img.*?src=["\']+(.*?)["\']+~', $elem->caracteristicas, $rutasCaracImgTmp);
              if(isset($rutasCaracImgTmp[1]) && count($rutasCaracImgTmp[1])>0){
                foreach ($rutasCaracImgTmp[1] as $imgYinymce){
                    if(file_exists($imgYinymce)){                      
                      $rutasImgExtr[] = $imgYinymce;
                    }
                }
                // echo "<pre>";
                // print_r($rutasCaracImgTmp[1]);
                // echo "</pre>";
              }              
            }

            //obtener enlaces de videos
            if($elem->caracteristicas!=""){
              @preg_match_all('~<source.*?src=["\']+(.*?)["\']+~', $elem->caracteristicas, $rutasCaracImgTmp);
              if(isset($rutasCaracImgTmp[1]) && count($rutasCaracImgTmp[1])>0){
                foreach ($rutasCaracImgTmp[1] as $imgYinymce){
                    if(file_exists($imgYinymce)){                      
                      $rutasImgExtr[] = $imgYinymce;
                    }
                }
                // echo "<pre>";
                // print_r($rutasCaracImgTmp[1]);
                // echo "</pre>";
              }              
            }
            //Fin Obtener de las caracteristicas

            // Obtener url de pdfs - caracteristicas
            if($elem->urlPdf!="" && file_exists($elem->urlPdf)){
                $arrImgTmp[] = $elem->urlPdf;
            }
            
	       }
	    }
	}


	if($strColVerColores==true){
	    $colVerColores = $verColoresObj->ObtVersionesColores(true, "", 1); 
	    if(count($colVerColores)>0){
	       //obtener las imagenes   
	       foreach($colVerColores as $elem){
	          if($elem->imagenAuto!="" && file_exists($elem->imagenAuto)){
	              $arrImgTmp[] = $elem->imagenAuto;              
	          }
	          if($elem->imagenColor!="" && file_exists($elem->imagenColor)){
	              $arrImgTmp[] = $elem->imagenColor;
	          }
	       }
	    }
	}

	if($strColVerZonasActivas==true){
	    $colVerZonasActivas = $verZonasActivasObj->ObtVersionesZonasActivas(true);
	    if(count($colVerZonasActivas)>0){       
	       foreach($colVerZonasActivas as $elem){
	          if($elem->imagenes!=""){
	             $elemImagenes = json_decode($elem->imagenes);
	             //obtener imagenes
	             $jsonImagen = array();
	             foreach($elemImagenes as $elemZA){
	                if(isset($elemZA->imagen) && $elemZA->imagen!="" && file_exists($elemZA->imagen)){
	                    $arrImgTmp[] = $elemZA->imagen;
	                }
	             }                   
	          }
	       }
	    }
	}

	if($strColGaleriasModelo==true){
	    //Col de galerias por modelo  
	    $colGaleriasModelo = $galeriaMObj->ObtGaleriasModelo(true, 1); 
	    if(count($colGaleriasModelo)>0){
	      //obtener las imagenes   
	       foreach($colGaleriasModelo as $elem){
	          if($elem->imagen!="" && file_exists($elem->imagen)){
	              $arrImgTmp[] = $elem->imagen;              
	          }          
	       }
	    }
	}


  //>>>OBTENER IMAGENES DE LOS TEXTOS ENRIQUECIDOS
  //Col de versiones planes
  if($strColVerPlanes==true){    
    $colVerPlanes = $verPlanesObj->ObtVersionesPlanes(true, "", 1);    
    //caracteristicas
    if(count($colVerPlanes)>0){
       foreach($colVerPlanes as $elemP){
          //Obtener de las caracteristicas implementado el 20/04/18
          if($elemP->caracteristicas!=""){
            @preg_match_all('~<img.*?src=["\']+(.*?)["\']+~', $elemP->caracteristicas, $rutasCaracImgTmp);
            if(isset($rutasCaracImgTmp[1]) && count($rutasCaracImgTmp[1])>0){
              foreach ($rutasCaracImgTmp[1] as $imgYinymce){
                  if(file_exists($imgYinymce)){                    
                    $rutasImgExtr[] = $imgYinymce;
                  }
              }
              // echo "<pre>";
              // print_r($rutasCaracImgTmp[1]);
              // echo "</pre>";
            }              
          }

          //obtener enlaces de videos
          if($elemP->caracteristicas!=""){
            @preg_match_all('~<source.*?src=["\']+(.*?)["\']+~', $elemP->caracteristicas, $rutasCaracImgTmp);
            if(isset($rutasCaracImgTmp[1]) && count($rutasCaracImgTmp[1])>0){
              foreach ($rutasCaracImgTmp[1] as $imgYinymce){
                  if(file_exists($imgYinymce)){                    
                    $rutasImgExtr[] = $imgYinymce;
                  }
              }            
            }
          }
          //Fin Obtener de las caracteristicas
       } 
    }
  }

  //Col de versiones requisitos
  if($strColVerRequisitos==true){    
    $colVerRequisitos = $verRequisitosObj->ObtVersionesRequisitos(true, "", 1, 1);    
    //caracteristicas
    if(count($colVerRequisitos)>0){
       foreach($colVerRequisitos as $elemC){
          //Obtener de las caracteristicas implementado el 20/04/18
          if($elemC->caracteristicas!=""){
            @preg_match_all('~<img.*?src=["\']+(.*?)["\']+~', $elemC->caracteristicas, $rutasCaracImgTmp);
            if(isset($rutasCaracImgTmp[1]) && count($rutasCaracImgTmp[1])>0){
              foreach ($rutasCaracImgTmp[1] as $imgYinymce){
                  if(file_exists($imgYinymce)){                    
                    $rutasImgExtr[] = $imgYinymce;
                  }
              }
              // echo "<pre>";
              // print_r($rutasCaracImgTmp[1]);
              // echo "</pre>";
            }              
          }

          //obtener enlaces de videos
          if($elemC->caracteristicas!=""){
            @preg_match_all('~<source.*?src=["\']+(.*?)["\']+~', $elemC->caracteristicas, $rutasCaracImgTmp);
            if(isset($rutasCaracImgTmp[1]) && count($rutasCaracImgTmp[1])>0){
              foreach ($rutasCaracImgTmp[1] as $imgYinymce){
                  if(file_exists($imgYinymce)){                    
                    $rutasImgExtr[] = $imgYinymce;
                  }
              }
            }
          }
          //Fin Obtener de las caracteristicas            
       } 
    }
  }

  //Col de promociones    
  if($strColPromociones==true){  
    $colPromociones = $promoObj->ObtVersionesPromociones(true, "", 1);
    if(count($colPromociones)>0){
      foreach($colPromociones as $elemPromo){
        //Obtener de las caracteristicas implementado el 20/04/18
        if($elemPromo->caracteristicas!=""){
          @preg_match_all('~<img.*?src=["\']+(.*?)["\']+~', $elemPromo->caracteristicas, $rutasCaracImgTmp);
          if(isset($rutasCaracImgTmp[1]) && count($rutasCaracImgTmp[1])>0){
            foreach ($rutasCaracImgTmp[1] as $imgYinymce){
                if(file_exists($imgYinymce)){                    
                  $rutasImgExtr[] = $imgYinymce;
                }
            }
            // echo "<pre>";
            // print_r($rutasCaracImgTmp[1]);
            // echo "</pre>";
          }
        }

        //obtener enlaces de videos
        if($elemPromo->caracteristicas!=""){
          @preg_match_all('~<source.*?src=["\']+(.*?)["\']+~', $elemPromo->caracteristicas, $rutasCaracImgTmp);
          if(isset($rutasCaracImgTmp[1]) && count($rutasCaracImgTmp[1])>0){
            foreach ($rutasCaracImgTmp[1] as $imgYinymce){
                if(file_exists($imgYinymce)){                    
                  $rutasImgExtr[] = $imgYinymce;
                }
            }            
          }
        }
        //Fin Obtener de las caracteristicas
      }    
    }    
  }

  // Col videos modelos
  if($strColVideosModelo==true){
      //Col de videos por modelo  
      $colVideosModelo = $videoMObj->ObtVideosModelo(true, 1); 
      if(count($colVideosModelo)>0){
        //obtener las imagenes   
         foreach($colVideosModelo as $elem){
            if($elem->url!="" && file_exists($elem->url)){
                $arrImgTmp[] = $elem->url;
            }
         }
      }
  }

  // Col agencias
  if($strColAgencias==true){
      $colAgencias = $agenciaObj->ObtTodasAgencias(true, 1);
      if(count($colAgencias)>0){
        //obtener las imagenes
         foreach($colAgencias as $elem){
            if($elem->logo!="" && file_exists($elem->logo)){
                $arrImgTmp[] = $elem->logo;
            }
         }
      }
  }   


  //>>>
  //>Inicio agregar Imagenes de los textos enriquecidos implementado el 20/04/18
  //>>>
  //Volver unico el arreglo
  if(count($rutasImgExtr)>0){
    $rutasImgExtr = array_unique($rutasImgExtr);
    foreach ($rutasImgExtr as $elemImgExt){
      $arrImgTmp[] = $elemImgExt;
    }
  }

  // echo "<pre>";
  // // print_r($rutasImgExtr);
  // print_r($arrImgTmp);
  // echo "</pre>";    
  // exit();
  //Fin agregar Imagenes de los textos enriquecidos




    ini_set('memory_limit', '-1');
    set_time_limit(0);
    
    //Partir arreglo en paquetes de 250 a 300 imagenes
    $arrImgTmp = array_chunk($arrImgTmp, 20000);

    if(count($arrImgTmp)>0){      
       $zipNo = 1;
       foreach ($arrImgTmp as $arrElemImg) {    
       		$clave = generarPassword(7, TRUE, TRUE);
            //Crear Zip
            $zip = new ZipArchive();
            $filenameZip = "../pkgziptmp/descarga".$clave.".zip";
            if($zip->open($filenameZip, ZIPARCHIVE::CREATE)===true){
              if(count($arrImgTmp)>0){
                foreach ($arrElemImg as $elemImg) {                  
                  $zip->addFile($elemImg); 
                }        
              }      
              $zip->close();
              //Salvar la ruta del archivo
              $arrUrlPkg[] = str_replace("../", "", $filenameZip);
            }else{
            	//echo "Error creando ".$filenameZip; 
        	}                        
          $zipNo++;
       }     
    }

    //Regresar las rutas de los archivos creados
    return $arrUrlPkg;
    // if(count($arrUrlPkg)>0){    	
    //     // $arr = array("success"=>true, "arrUrlPkg"=>$arrUrlPkg);
    // }else{
    // 	// $arr = array("success"=>false);
    // }
    // echo $callback . '(' . json_encode($arr) . ');';

    // echo '<pre>';
    // print_r($arrImgTmp);
    // print_r($arrUrlPkg);
    // echo '</pre>';

    /*
    //Crear Zip
    $zip = new ZipArchive();
    $filenameZip = "prueba.zip";
    if($zip->open($filenameZip, ZIPARCHIVE::CREATE)===true){
      if(count($arrImgTmp)>0){
        foreach ($arrImgTmp as $elemImg) {
          $zip->addFile($elemImg); 
        }        
      }      
      $zip->close();
      //header("Content-type: application/octet-stream");
      //header("Content-disposition: attachment; filename=".$filenameZip);
      //readfile($filenameZip);
    }else{
      echo "Error creando ".$filenameZip;
    }
    */
}

//Enviar correo con las caracteristicas de cierta version
function enviarCorreoCaracVersion($callback, $gralVersId, $email, $nombreVendedor, $emailVendedor, $idVendedor){
  $emailObj = new EmailFunctions();
  $userObj = new usuariosObj();
  $agenciaObj = new catAgenciasObj();
  
  $versionGeneralesObj = new versionGeneralesObj();
  $versionGeneralesObj->versionGeneralPorId($gralVersId);
  
  $gamaModelosObj = new gamaModelosObj();
  $gamaModelosObj->ObtDatosGModeloPorId($versionGeneralesObj->gModeloId);
  
  include "../common/config.php";

  $subject = "Z Motors - Características del modelo ".$gamaModelosObj->modelo." ".  $gamaModelosObj->anio." versión ".$versionGeneralesObj->version;
  $imagen = str_replace("..", "", $versionGeneralesObj->imagen);
  $htmlImg = '<img src="'.$siteURL.$imagen.'" width="200px">';
  $caracteristicas = $versionGeneralesObj->caracteristicas;
  $versionNombre = $versionGeneralesObj->version;
  $precioDesde = "$ ".number_format($versionGeneralesObj->precioInicial,2);
  $adjunto = $versionGeneralesObj->urlPdf;  
  
  $res = $emailObj->EnviarCaracVersion($subject, $email, $htmlImg, $caracteristicas, $versionNombre, $precioDesde, $nombreVendedor, $emailVendedor, $adjunto);
  // Copia al vendedor (agente de ventas)
  $emailObj->EnviarCaracVersion($subject, $emailVendedor, $htmlImg, $caracteristicas, $versionNombre, $precioDesde, $nombreVendedor, $emailVendedor, $adjunto);
  
  // Obtener datos del prospecto por id  para mandar copia al gte de ventas
  if($idVendedor!=""){
    $userObj->UserByID($idVendedor);
    //Obtener los datos para enviar a los gerentes          
    if($userObj->agenciaId>0){
      $agenciaObj->AgenciaPorId($userObj->agenciaId);      
      // $gtVentas = $agenciaObj->gtVentas;
      $correoGtVentas = $agenciaObj->correoGtVentas;
      if($correoGtVentas!=""){
        $emailObj->EnviarCaracVersion($subject, $correoGtVentas, $htmlImg, $caracteristicas, $versionNombre, $precioDesde, $nombreVendedor, $emailVendedor, $adjunto);
      }
    }
  }  

  if($res>0){
    $arr = array("success"=>true, "res"=>$res);
  }else{
    $arr = array("success"=>false, "res"=>$res);  
  }  
  echo $callback . '(' . json_encode($arr) . ');';
}


//Enviar correo con los planes de cierta version
function enviarCorreoPlanes($callback, $gralVersId, $email, $nombreVendedor, $emailVendedor, $idVendedor, $idsPlan){
  include "../common/config.php";
  $emailObj = new EmailFunctions();
  $userObj = new usuariosObj();
  $agenciaObj = new catAgenciasObj();
  
  $versionGeneralesObj = new versionGeneralesObj();  
  $gamaModelosObj = new gamaModelosObj();    
  $verPlanesObj = new versionPlanesObj();

  $versionGeneralesObj->versionGeneralPorId($gralVersId);  
  $gamaModelosObj->ObtDatosGModeloPorId($versionGeneralesObj->gModeloId);
  // $planes = $verPlanesObj->ObtVersionesPlanes(false, $gralVersId, 1);
  $planes = $verPlanesObj->ObtVariosPlanesPorId(true, $idsPlan);  //Imp. 18/12/19

  $modeloVersion = $gamaModelosObj->modelo." ".  $gamaModelosObj->anio." versión ".$versionGeneralesObj->version; 
  $subject = "Z Motors - Planes del modelo ".$modeloVersion;
  $params = (object)array("arrPlanes"=>$planes, "modeloVersion"=>$modeloVersion, "nombreVendedor"=>$nombreVendedor, "emailVendedor"=>$emailVendedor);
    
  $res = $emailObj->enviarPlanesVersion($subject, $email, $params);
  // Copia al vendedor (agente de ventas)
  $emailObj->enviarPlanesVersion($subject, $emailVendedor, $params);

  // Obtener datos del prospecto por id  para mandar copia al gte de ventas
  if($idVendedor!=""){
    $userObj->UserByID($idVendedor);
    //Obtener los datos para enviar a los gerentes          
    if($userObj->agenciaId>0){
      $agenciaObj->AgenciaPorId($userObj->agenciaId);      
      // $gtVentas = $agenciaObj->gtVentas;
      $correoGtVentas = $agenciaObj->correoGtVentas;
      if($correoGtVentas!=""){
        $emailObj->enviarPlanesVersion($subject, $correoGtVentas, $params);        
      }
    }
  }

  if($res>0){
    $arr = array("success"=>true, "res"=>$res);
  }else{
    $arr = array("success"=>false, "res"=>$res);  
  }  
  echo $callback . '(' . json_encode($arr) . ');';
}

//Enviar correo con los requisitos de cierta version
function enviarCorreoRequisitos($callback, $gralVersId, $email, $idsReq, $nombreVendedor, $emailVendedor, $idVendedor){
  include "../common/config.php";
  $emailObj = new EmailFunctions();
  $userObj = new usuariosObj();
  $agenciaObj = new catAgenciasObj();
  
  $versionGeneralesObj = new versionGeneralesObj();  
  $gamaModelosObj = new gamaModelosObj();    
  $verRequisitosObj = new versionRequisitosObj();

  $versionGeneralesObj->versionGeneralPorId($gralVersId);  
  $gamaModelosObj->ObtDatosGModeloPorId($versionGeneralesObj->gModeloId);
  // $requisitos = $verRequisitosObj->ObtVersionesRequisitos(false, $gralVersId, 1); //Por el id de la version
  // $requisitos = $verRequisitosObj->ObtVersionesRequisitos(true, "", 1, 1);
  $requisitos = $verRequisitosObj->ObtVariosRequisitosPorId(true, $idsReq);

  $modeloVersion = $gamaModelosObj->modelo." ".  $gamaModelosObj->anio." versión ".$versionGeneralesObj->version; 
  $subject = "Z Motors - Requisitos del modelo ".$modeloVersion;  
  $params = (object)array("arrRequisitos"=>$requisitos, "modeloVersion"=>$modeloVersion, "nombreVendedor"=>$nombreVendedor, "emailVendedor"=>$emailVendedor);
    
  $res = $emailObj->enviarRequisitosVersion($subject, $email, $params);
  // Copia al vendedor (agente de ventas)
  $emailObj->enviarRequisitosVersion($subject, $emailVendedor, $params);

  // Obtener datos del prospecto por id  para mandar copia al gte de ventas
  if($idVendedor!=""){
    $userObj->UserByID($idVendedor);
    //Obtener los datos para enviar a los gerentes          
    if($userObj->agenciaId>0){
      $agenciaObj->AgenciaPorId($userObj->agenciaId);      
      // $gtVentas = $agenciaObj->gtVentas;
      $correoGtVentas = $agenciaObj->correoGtVentas;
      if($correoGtVentas!=""){
        $emailObj->enviarRequisitosVersion($subject, $correoGtVentas, $params);
      }
    }
  }
  
  if($res>0){
    $arr = array("success"=>true, "res"=>$res);
  }else{
    $arr = array("success"=>false, "res"=>$res);  
  }  
  echo $callback . '(' . json_encode($arr) . ');';
}

//Enviar correo con los precios de cierta version
function enviarCorreoPrecios($callback, $gralVersId, $email, $nombreVendedor, $emailVendedor){
  include "../common/config.php";
  $emailObj = new EmailFunctions();
  
  $versionGeneralesObj = new versionGeneralesObj();  
  $gamaModelosObj = new gamaModelosObj();      
  $verPreciosObj = new versionPreciosObj();

  $versionGeneralesObj->versionGeneralPorId($gralVersId);  
  $gamaModelosObj->ObtDatosGModeloPorId($versionGeneralesObj->gModeloId);
  // $precios = $verPreciosObj->ObtVersionesPrecios(false, $gralVersId, 1);

  //Nueva interfaz de vizualizacion
  $precios = obtPreciosOrdenados();
  $precios = $precios[$gralVersId];  

  $modeloVersion = $gamaModelosObj->modelo." ".  $gamaModelosObj->anio." versión ".$versionGeneralesObj->version; 
  $subject = "Z Motors - Precios del modelo ".$modeloVersion; 
  $params = (object)array("arrPrecios"=>$precios, "modeloVersion"=>$modeloVersion, "nombreVendedor"=>$nombreVendedor, "emailVendedor"=>$emailVendedor);
  
  $res = $emailObj->enviarPreciosVersion($subject, $email, $params);
  if($res>0){
    $arr = array("success"=>true, "res"=>$res);
  }else{
    $arr = array("success"=>false, "res"=>$res);  
  }  
  echo $callback . '(' . json_encode($arr) . ');';
}


//Salvar los prospecto cuando se presiona el boton de sincronizar
function salvarProspectos($callback){
  $arrRes = array();
  $arrProspectos = json_decode($_POST['datosJson']);

  if(count($arrProspectos)>0){    
      foreach ($arrProspectos as $elemJson){       
        $prospObj = new catProspectosObj();        
        $elem = json_decode($elemJson); //Convierte en objeto para obtener los datos
        $prospObj->prospectoId = 0;

        //Logica para actualizar o crear
        $prospObj->datosProspectoPorEmail($elem->ic_email);
        if($prospObj->prospectoId>0){ //Actualiza
          $prospObj->usuarioId = $elem->idVendedor;
          $prospObj->nombre = $elem->ic_nombre." ".$elem->ic_apaterno." ".$elem->ic_amaterno;
          $prospObj->direccion = $elem->ic_calle." ".$elem->ic_numero." ".$elem->ic_colonia;
          $prospObj->telefono = $elem->ic_telefono;
          $prospObj->email = $elem->ic_email;
          $prospObj->datosJson = $elemJson;
          $prospObj->fechaAlta = (isset($elem->fechaAlta) && $elem->fechaAlta!="") ?$elem->fechaAlta:"";

          $prospObj->prospectoId = $prospObj->prospectoId;
          $respAct = $prospObj->actProspecto();
          
          if($respAct>0){
            $arrRes[] = true;
          }
        }else{ //Crea registro
          $prospObj->usuarioId = $elem->idVendedor;
          // $prospObj->nombre = $elem->ic_nombre." ".$elem->ic_apaterno." ".$elem->ic_amaterno;
          $prospObj->nombre = $elem->ic_nombre;
          $prospObj->direccion = $elem->ic_calle." ".$elem->ic_numero." ".$elem->ic_colonia;
          $prospObj->telefono = $elem->ic_telefonocel;
          $prospObj->email = $elem->ic_email;
          $prospObj->datosJson = $elemJson;
          $prospObj->fechaAlta = (isset($elem->fechaAlta) && $elem->fechaAlta!="") ?$elem->fechaAlta:"";
          $prospObj->salvarProspecto();
        }
              
        if($prospObj->prospectoId>0){
          $arrRes[] = true;
        }
        // $arr = array("success"=>true, "prospectos"=>$elem->ic_nombre);
     }
      
      if(count($arrRes)>0){
        $arr = array("success"=>true);
      }else{
        $arr = array("success"=>false);    
      }
    
  }else{
    $arr = array("success"=>false);
  }
  
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Max-Age: 1000');
  // echo $callback . '(' . json_encode($arr) . ');';
  echo json_encode($arr);
}

//Enviar por correo la cotizacion
function enviarCorreoCotizacion($callback, $email, $gralVersId, $modelo, $version, $costo, $sa, $sv, $sd,
                              $cxa, $subtotal, $ptcEnganche, $enganche, $taf, 
                              $m12, $m24, $m36, $m48, $m60, $textoPrecio,
                              $nombreVend, $emailVend, $nombreCliente, $emailCliente , $telCliente, $sge){
  include "../common/config.php";
  $emailObj = new EmailFunctions();  
  $versionGeneralesObj = new versionGeneralesObj();  
  $gamaModelosObj = new gamaModelosObj();
  
  $versionGeneralesObj->versionGeneralPorId($gralVersId);  
  $gamaModelosObj->ObtDatosGModeloPorId($versionGeneralesObj->gModeloId);  

  $modeloVersion = $gamaModelosObj->modelo." ".  $gamaModelosObj->anio." versión ".$versionGeneralesObj->version; 
  $subject = "Z Motors - Cotización del modelo ".$modeloVersion;  
  
  $params = (object)array("modelo"=>$modelo, "version"=>$version, "costo"=>$costo, "sa"=>$sa, "sv"=>$sv, "sd"=>$sd,
                          "cxa"=>$cxa, "subtotal"=>$subtotal, "ptcEnganche"=>$ptcEnganche, "enganche"=>$enganche, "taf"=>$taf,
                          "m12"=>$m12, "m24"=>$m24, "m36"=>$m36, "m48"=>$m48, "m60"=>$m60, "textoPrecio"=>$textoPrecio,
                          "nombreVend"=>$nombreVend, "emailVend"=>$emailVend, "nombreCliente"=>$nombreCliente, 
                          "emailCliente"=>$emailCliente, "telCliente"=>$telCliente, "sge"=>$sge);

  $res = $emailObj->enviarCotizacionVersion($subject, $email, $params);  
  if($res>0){
    $arr = array("success"=>true, "res"=>$res);
  }else{
    $arr = array("success"=>false, "res"=>$res);  
  }  
  echo $callback . '(' . json_encode($arr) . ');';
}

//Obtener todos los precios ordenados por id de la version general 
function obtPreciosOrdenados(){
    $verPreciosObj = new versionPreciosObj();
    $colVerPrecios = $verPreciosObj->ObtVersionesPrecios(true, "", 1);

    //Arreglos 
    $arrGralVersIds = array();
    $arrConceptos = array(); //transimiones    
    $arrFinal = array();

    $arrLista = array();    
    $arrContado = array();
    
    if(count($colVerPrecios)>0){
      //obtener arreglo de gralVersId y volverlo unico
      foreach ($colVerPrecios as $elem1) {          
          $arrGralVersIds[$elem1->gralVersId] = $elem1->gralVersId;
      }

      foreach ($arrGralVersIds as $elemGral) { 
          //Obtener las transmisiones o conceptos
          foreach ($colVerPrecios as $elem2){
              if($elemGral==$elem2->gralVersId){                                
                $arrConceptos[$elem2->transmisionId] = $elem2->transmision;                
              }
          }
          
          $arrFinal[$elemGral] = array("gralVersId"=>$elemGral, "arrConceptos"=>$arrConceptos);
          $arrConceptos = array();
      }

      foreach ($arrFinal as $elemFinalTmp) { 
          foreach ($elemFinalTmp['arrConceptos'] as $keyConcepto=>$elemConcepto){
              foreach ($colVerPrecios as $elem3){
                 if($elem3->transmisionId==$keyConcepto && $elem3->gralVersId==$elemFinalTmp['gralVersId']){
                    $arrFuentes[] = $elem3->concPrecioId.'|'.$elem3->conceptoPrecio.'|'.$elem3->precio;
                 }   
              }
              $arrFinal[$elemFinalTmp['gralVersId']]['arrConceptos'][$keyConcepto] = array("concepto"=>$elemConcepto, "fuentes"=>$arrFuentes);              
              $arrFuentes = array();
          }          
      }
      
      if(count($arrFinal)>0){
        $arrFinal = $arrFinal;        
      }
    }

    return $arrFinal;
    // echo "<pre>";      
    // print_r($arr);
    // echo "</pre>";    
}


//Eliminar un archivo del server
function eliminarArchivo($callback, $ruta){	
	$ruta = "../".$ruta;

	//verifica si existe archivo
	if($ruta!="" && file_exists($ruta)) {
		@unlink($ruta);
		//vuelve a verificar para mandar respuesta
		if(file_exists($ruta)) {
			$arr = array("success"=>false, "resp"=>"No fue posible borrar el archivo");
		}else{
			$arr = array("success"=>true, "resp"=>"Archivo borrado");			
		}	    
	}else {
	   $arr = array("success"=>false, "resp"=>"El archivo no existe");
	}
	echo $callback . '(' . json_encode($arr) . ');';
}


//Obtener prospectos por el id del vendedor
function obtProspectosPorIdUsr($callback, $idUsuario){
    $prospectoObj = new catProspectosObj();
    //Col prospectos por el id del usuario vendedor
    $colProspectos = array();  
    if($idUsuario>0){
      $strcolProspectos = true;
      $colProspectos = $prospectoObj->obtTodosProspectosPorUsuario(true, $idUsuario);      
    }else{
      $strcolProspectos = false;
    }
    
    $arr = array("success"=>true, "arrProspectos"=>array("act"=>$strcolProspectos, "colProspectos"=>$colProspectos));
    echo $callback . '(' . json_encode($arr) . ');';
}


//Enviar pedido de venta correo a los gerentes asociados al vendedor
function salvarPedidoVenta($callback){
  $userObj = new usuariosObj();
  $agenciaObj = new catAgenciasObj();
  $emailObj = new EmailFunctions();  
  $arrRes = array();
  $arrPedidoVenta = json_decode($_POST['datosJson']);
  /*
  $arrPedidoVenta = array();
  $arrPedidoVenta["pv_nocliente"] = "5623123121";
  $arrPedidoVenta["pv_fecha"] = "04/07/2018";
  $arrPedidoVenta["pv_nombre"] = "Jair Castañeda Lopez";
  $arrPedidoVenta["pv_direccion"] = "Blvd 5 de mayo 47656";
  $arrPedidoVenta["pv_colonia"] = "Centro pruebla";
  $arrPedidoVenta["pv_delegacion"] = "N/A";
  $arrPedidoVenta["pv_estado"] = "Puebla";
  $arrPedidoVenta["pv_codigopostal"] = "72000";
  $arrPedidoVenta["pv_fechanacimiento"] = "12/10/1987";
  $arrPedidoVenta["pv_email"] = "je@tesl.com";
  $arrPedidoVenta["pv_celular"] = "54621321";
  $arrPedidoVenta["pv_oficina"] = "115542112";
  $arrPedidoVenta["pv_casa"] = "515561221";
  $arrPedidoVenta["pv_otro"] = "693564564";
  $arrPedidoVenta["pv_tipopersona"] = "pv_moral";
  $arrPedidoVenta["pv_tipopersonarfc"] = "RFC876786SS";
  $arrPedidoVenta["pv_tipoventa"] = "pv_crediestrena";
  $arrPedidoVenta["pv_cualbanco"] = "";
  $arrPedidoVenta["pv_nombrecontacto"] = "Maria Sandoval Gutierrez";
  $arrPedidoVenta["pv_telcontacto"] = "87678687";
  $arrPedidoVenta["pv_tiposubvencion"] = "65113213";
  $arrPedidoVenta["pv_catprecioflotilla"] = "678678";
  $arrPedidoVenta["pv_auto"] = "Seat";
  $arrPedidoVenta["pv_serie"] = "654231321ASDASDAAs8855";
  $arrPedidoVenta["pv_motor"] = "1.6";
  $arrPedidoVenta["pv_color"] = "Negro";
  $arrPedidoVenta["pv_modelo"] = "Ibiza 2018";
  $arrPedidoVenta["pv_anticipocliente"] = "851,212.00";
  $arrPedidoVenta["pv_pagobanco"] = "5.00";
  $arrPedidoVenta["pv_pagovwl"] = "854,212.00";
  $arrPedidoVenta["pv_notacreditosubvencion"] = "821,552.00";
  $arrPedidoVenta["pv_placastenencia"] = "758.90";
  $arrPedidoVenta["pv_preciollenoauto"] = "51,212.00";
  $arrPedidoVenta["pv_totalfacturar"] = "68,231.00";
  $arrPedidoVenta["pv_comentario"] = "Esta es una prueba que no puede pasar mas aya de ";
  $arrPedidoVenta["idVendedor"] = "3";
  $arrPedidoVenta["idProspecto"] = "TBlRhTXW";

  $_POST['nombreVendedor'] = "Carlos";
  $_POST['emailVendedor'] = "carlos@test.com";
  $_POST['clientePedido'] = "Marcos";
  $_POST['idVendedor'] = "3";
  $arrPedidoVenta = (object)$arrPedidoVenta;
  */

  // Obtener datos del prospecto por id
  $idVendedor = $_POST['idVendedor'];
  $userObj->UserByID($idVendedor);
  //Obtener los datos para enviar a los gerentes
  $gtVwsf = "";
  $correoGtvwsf = "";
  $gtVentas = "";  
  $correoGtVentas = "";
  $gtGral = "";  
  $correoGtGral = "";
  if($userObj->agenciaId>0){
    $agenciaObj->AgenciaPorId($userObj->agenciaId);
    $gtVwsf = $agenciaObj->gtVwsf;
    $correoGtvwsf = $agenciaObj->correoGtvwsf;
    $gtVentas = $agenciaObj->gtVentas;
    $correoGtVentas = $agenciaObj->correoGtVentas;
    $gtGral = $agenciaObj->gtGral;
    $correoGtGral = $agenciaObj->correoGtGral;
  }
  
  //Crear pdf
  //liberias para creacion de pdf
  require_once '../brules/PDFgenerarPdfObj.php';
  require_once '../brules/PDFgetContentHtmlToStringObj.php';
  include  '../common/config.php';
  $dirname = dirname(__DIR__);
  // include  $dirname.'/common/config.php';

  //establecer la zona horaria
  $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );  
  $contHtmlToStrObj = new getContentHtmlToStringObj();
  $genpdfObj = new generarPdfObj();
  $aleatorio = generarPassword(4, true, true, false, false); 

  $colDatosReporte = array();
  $colDatosReporte = $arrPedidoVenta;

  // $gerentevwsf = "AGUSTIN HERRERA";
  // $gerenteventas = "VICTOR HUGO MARTINEZ";
  // $gerentegral = "SERGIO CORONA";

  //arreglo general
  $arrDatosGrales = array("siteURL"=>$siteURL, "titulo"=>"PEDIDO DE VENTA DE AUTO NUEVO", "fecha"=>$arrPedidoVenta->pv_fecha,
                          "nocliente"=>$arrPedidoVenta->pv_nocliente, "nombreVendedor"=>$_POST['nombreVendedor'], "emailVendedor"=>$_POST['emailVendedor'], 
                          "clientePedido"=>$_POST['clientePedido'], "gerentevwsf"=>$gtVwsf, "gerenteventas"=>$gtVentas, "gerentegral"=>$gtGral
                         );
  
  
  //Generar post
  $_POST['arrDatosGrales'] = $arrDatosGrales;
  $_POST['arrFilas'] = (count($colDatosReporte)>0) ?$colDatosReporte :array();
  $_POST['totalArrFilas'] = count($colDatosReporte);
  $url = $siteURL.'pdfPedidoVenta.php';
  $rutaDescarga = '../upload/pdfPedidoVenta/';
  
  $result = $contHtmlToStrObj->getContentHtmlToString($url, $_POST); //rellenar el html con los datos del post
  // echo $result;

  $fechaHoyF3 = $dateByZone->format('Y_m_d'); //fecha Actual  
  $genpdfObj->GenerarFormatoPDF("pedidoVenta_".$fechaHoyF3.'_'.$aleatorio, $result, 0, 1, $rutaDescarga); //imprimir pdf en portrait
  sleep(4);

  //verificar que el archivo se genero correctamente
   $archivoPdf = $dirname.'/upload/pdfPedidoVenta/pedidoVenta_'.$fechaHoyF3.'_'.$aleatorio.'.pdf';   
   if(file_exists($archivoPdf)){
      $urlArchivo = $siteURL.'upload/pdfPedidoVenta/pedidoVenta_'.$fechaHoyF3.'_'.$aleatorio.'.pdf';
      $arr = array("success"=>true, "urlArchivo"=>$urlArchivo, "nombreArchivo"=>'pedidoVenta_'.$fechaHoyF3.'_'.$aleatorio.'.pdf', "agenciaId"=>$userObj->agenciaId);

      //Logica para enviar correo a los gerentes
      //Gerentes de VWSF 
      $subject = "Z Motors - Nuevo pedido de venta de auto nuevo"; 
      $adjunto = '../upload/pdfPedidoVenta/pedidoVenta_'.$fechaHoyF3.'_'.$aleatorio.'.pdf';
      $params = (object)array("nombreVendedor"=>trim($_POST['nombreVendedor']), "correoVendedor"=>trim($_POST['emailVendedor']), "clientePedido"=>trim($_POST['clientePedido']),
                                "adjunto"=>$adjunto, "rutaAbsAdjunto"=>$urlArchivo);

      if($correoGtvwsf!=""){
        $emailGte = trim($correoGtvwsf); //"carlos.ramirez@framelova.com";
        $res = $emailObj->EnviarPedidoVenta($subject, $emailGte, $params);
        // if($res>0){
        //   $arr = array("success"=>true, "res"=>$res);
        // }else{
        //   $arr = array("success"=>false, "res"=>$res);  
        // }  
      }
      if($correoGtVentas!=""){
        $emailGte = trim($correoGtVentas); //"carlos.ramirez@framelova.com";
        $res = $emailObj->EnviarPedidoVenta($subject, $emailGte, $params);
        // if($res>0){
        //   $arr = array("success"=>true, "res"=>$res);
        // }else{
        //   $arr = array("success"=>false, "res"=>$res);  
        // }  
      }
      if($correoGtGral!=""){
        $emailGte = trim($correoGtGral); //"carlos.ramirez@framelova.com";
        $res = $emailObj->EnviarPedidoVenta($subject, $emailGte, $params);
        // if($res>0){
        //   $arr = array("success"=>true, "res"=>$res);
        // }else{
        //   $arr = array("success"=>false, "res"=>$res);  
        // }  
      }
      //Enviar copia al vendedor
      if($params->correoVendedor!=""){
        $emailVendedor = trim($_POST['emailVendedor']);
        $emailObj->EnviarPedidoVenta($subject, $emailVendedor, $params);
      }
   }else{
      $arr = array("success"=>false);  
   }    
  
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Max-Age: 1000');    
  echo json_encode($arr);  

  // echo "<pre>";
  // print_r($arrPedidoVenta);
  // echo "</pre>";
}



//Ejemplo por post 
function ejemploPost($callback, $POST){
  // $arr = array("success"=>true, "res"=>$res);
  // echo $callback . '(' . json_encode($arr) . ');';
  echo $callback . '(' . json_encode($POST) . ');';  
}


// Obtiene coleccion de modelos por busqueda en el inventario
function busquedaInventario($callback, $idUsuario, $vin, $modelo, $year, $version, $color, $codigo){
  include  '../common/config.php';  
  $dirname = dirname(__DIR__);  
  $arr = array("success"=>false);

  // Busca en tabla de base de datos
  if($opcBusquedaInventario>0){
    buscarInventarioDB($callback, $idUsuario, $vin, $modelo, $year, $version, $color, $codigo);  
  }else{ // Busca en webservice
    buscarInventarioWS($callback, $idUsuario, $vin, $modelo, $year, $version, $color, $codigo);
  }
}

// Imp. 26/02/20
// Buscar en web service
function buscarInventarioWS($callback, $idUsuario, $vin, $modelo, $year, $version, $color, $codigo){
  $wsdl = "https://inventario.mega-invoice.com/ws/interface_inventario.php?wsdl"; //url ws
  //obtener el rfc y dealer por el id del usuario (vendedor)

  //Puebla (Z MOTORS SA DE CV)
  $agencia = "Z MOTORS PUEBLA";
  // $codigo = "";
  $rfc = "ZMO970117GJ5";
  $dealer= "2015";    
  $client = new SoapClient($wsdl);  
  try{    
    // $arr = $client->Buscar(array("codigo"=>$codigo, "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>$vin, "modelo"=>$modelo, "year"=>$year, "version"=>$version, "color"=>$color));   
    // $arr = $client->Buscar(array("codigo"=>$codigo, "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>"", "year"=>"", "version"=>"", "color"=>""));   
    // $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "year"=>"", "version"=>"", "color"=>""));   
    // $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "anio"=>"", "version"=>"", "color"=>"", "clave_v"=>$codigo, "condicion"=>"nuevo", "disponibilidad"=>"1"));   
    $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "anio"=>"", "version"=>"", "color"=>"", "clv_vehicular"=>$codigo, "condicion"=>"nuevo", "disponibilidad"=>"1"));   
  }catch (SoapFault $e){
    $arr = (object)array("result"=>"error_busqueda");    
  }
  if($arr->result!="error_busqueda"){
    if(isset($arr->datos_vehiculo)){
      if(is_array($arr->datos_vehiculo)){
        // echo "Es array ".$agencia."<br/>";
        //agregar variables para que sean leidas en el app y asi no recompilar 
        //Implementado el 02/07/2019
        $datos_vehiculo = $arr->datos_vehiculo;
        foreach ($datos_vehiculo as $key => $elemDato) {      
          $datos_vehiculo[$key]->{'año'} = $elemDato->anio;
          $datos_vehiculo[$key]->{'colorInt'} = $elemDato->color_interior;
          $datos_vehiculo[$key]->{'potencia'} = $elemDato->hp;
          $datos_vehiculo[$key]->{'condicion'} = $elemDato->condicion_uso;          
        }
      }else{
        // echo "No es array ".$agencia."<br/>";
        $datos_vehiculo = $arr->datos_vehiculo;
        $datos_vehiculo->{'año'} = $datos_vehiculo->anio;
        $datos_vehiculo->{'colorInt'} = $datos_vehiculo->color_interior;
        $datos_vehiculo->{'potencia'} = $datos_vehiculo->hp;
        $datos_vehiculo->{'condicion'} = $datos_vehiculo->condicion_uso;
      }
      $arr->{'datos_vehiculo'} = $datos_vehiculo;    
    }

    $datosAgencias[0] = array("agencia"=>$agencia, "inventarioAgencia"=>$arr);
    $arrFinal = array("success"=>true, "datosAgencias"=>$datosAgencias);
  }else{
    $arrFinal = array("success"=>false);
  }
  
  //Cholula
  $agencia = "Z MOTORS CHOLULA";
  // $codigo = "";
  $rfc = "ZMO970117GJ5";
  $dealer= "20151";
  $client = new SoapClient($wsdl);  
  try{
    // $arr = $client->Buscar(array("codigo"=>$codigo, "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>$vin, "modelo"=>$modelo, "year"=>$year, "version"=>$version, "color"=>$color));   
    // $arr = $client->Buscar(array("codigo"=>$codigo, "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>"", "year"=>"", "version"=>"", "color"=>""));   
    // $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "year"=>"", "version"=>"", "color"=>""));   
    // $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "anio"=>"", "version"=>"", "color"=>"", "clave_v"=>$codigo, "condicion"=>"nuevo", "disponibilidad"=>"1"));   
    $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "anio"=>"", "version"=>"", "color"=>"", "clv_vehicular"=>$codigo, "condicion"=>"nuevo", "disponibilidad"=>"1"));   
  }catch (SoapFault $e){
    $arr = (object)array("result"=>"error_busqueda");    
  }  
  if($arr->result!="error_busqueda"){
    if(isset($arr->datos_vehiculo)){
      if(is_array($arr->datos_vehiculo)){
        // echo "Es array ".$agencia."<br/>";
        //agregar variables para que sean leidas en el app y asi no recompilar 
        //Implementado el 02/07/2019
        $datos_vehiculo = $arr->datos_vehiculo;
        foreach ($datos_vehiculo as $key => $elemDato) {      
          $datos_vehiculo[$key]->{'año'} = $elemDato->anio;
          $datos_vehiculo[$key]->{'colorInt'} = $elemDato->color_interior;
          $datos_vehiculo[$key]->{'potencia'} = $elemDato->hp;
          $datos_vehiculo[$key]->{'condicion'} = $elemDato->condicion_uso;          
        }
      }else{
        // echo "No es array ".$agencia."<br/>";
        $datos_vehiculo = $arr->datos_vehiculo;
        $datos_vehiculo->{'año'} = $datos_vehiculo->anio;
        $datos_vehiculo->{'colorInt'} = $datos_vehiculo->color_interior;
        $datos_vehiculo->{'potencia'} = $datos_vehiculo->hp;
        $datos_vehiculo->{'condicion'} = $datos_vehiculo->condicion_uso;
      }
      $arr->{'datos_vehiculo'} = $datos_vehiculo;    
    }

    $datosAgencias[1] = array("agencia"=>$agencia, "inventarioAgencia"=>$arr);
    $arrFinal = array("success"=>true, "datosAgencias"=>$datosAgencias);
  }else{
    $arrFinal = array("success"=>false);
  }

  //Mexico
  $agencia = "Z MOTORS M&Eacute;XICO";
  // $codigo = "";
  $rfc = "ZMM030710TA6";
  $dealer= "1431";
  $client = new SoapClient($wsdl);  
  try{
    // $arr = $client->Buscar(array("codigo"=>$codigo, "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>$vin, "modelo"=>$modelo, "year"=>$year, "version"=>$version, "color"=>$color));   
    // $arr = $client->Buscar(array("codigo"=>$codigo, "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>"", "year"=>"", "version"=>"", "color"=>""));   
    // $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "year"=>"", "version"=>"", "color"=>""));
    // $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "anio"=>"", "version"=>"", "color"=>"", "clave_v"=>$codigo, "condicion"=>"nuevo", "disponibilidad"=>"1"));
    $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "anio"=>"", "version"=>"", "color"=>"", "clv_vehicular"=>$codigo, "condicion"=>"nuevo", "disponibilidad"=>"1"));
  }catch (SoapFault $e){
    $arr = (object)array("result"=>"error_busqueda");    
  }  
  if($arr->result!="error_busqueda"){
    if(isset($arr->datos_vehiculo)){
      if(is_array($arr->datos_vehiculo)){
        // echo "Es array ".$agencia."<br/>";
        //agregar variables para que sean leidas en el app y asi no recompilar 
        //Implementado el 02/07/2019
        $datos_vehiculo = $arr->datos_vehiculo;
        foreach ($datos_vehiculo as $key => $elemDato) {      
          $datos_vehiculo[$key]->{'año'} = $elemDato->anio;
          $datos_vehiculo[$key]->{'colorInt'} = $elemDato->color_interior;
          $datos_vehiculo[$key]->{'potencia'} = $elemDato->hp;
          $datos_vehiculo[$key]->{'condicion'} = $elemDato->condicion_uso;          
        }
      }else{
        // echo "No es array ".$agencia."<br/>";
        $datos_vehiculo = $arr->datos_vehiculo;
        $datos_vehiculo->{'año'} = $datos_vehiculo->anio;
        $datos_vehiculo->{'colorInt'} = $datos_vehiculo->color_interior;
        $datos_vehiculo->{'potencia'} = $datos_vehiculo->hp;
        $datos_vehiculo->{'condicion'} = $datos_vehiculo->condicion_uso;
      }
      $arr->{'datos_vehiculo'} = $datos_vehiculo;    
    }
    
    $datosAgencias[2] = array("agencia"=>$agencia, "inventarioAgencia"=>$arr);
    $arrFinal = array("success"=>true, "datosAgencias"=>$datosAgencias);
  }else{
    $arrFinal = array("success"=>false);
  }
  
  
  echo $callback . '(' . json_encode($arrFinal) . ');';
  // echo "<pre>";
  // print_r($arrFinal);
  // echo "</pre>";
}

// Imp. 26/02/20
// Buscar en base de datos 
function buscarInventarioDB($callback, $idUsuario, $vin, $modelo, $year, $version, $color, $codigo){
  include  '../common/config.php';
  $inventarioObj = new inventariosObj();
  // "codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "anio"=>"", "version"=>"", "color"=>"", "clv_vehicular"=>$codigo, "condicion"=>"nuevo", "disponibilidad"=>"1")
  $agencia = $concecionaria;
  $arrFinal = array("success"=>false); 
  $datosAgencias = array();

  //Imp. 09/03/20
  // Obtener todas las agencias disponibles 
  $arrAgencias = $inventarioObj->ObtAgencias(true);  

  $version = "";
  $color = "";
  $condicion = "nuevo";
  $disponibilidad = 1;

  if(count($arrAgencias)>0){
    //obtener datos del auto ordenadamente por la agencia
    foreach ($arrAgencias as $elem) {
      $datos_vehiculo = $inventarioObj->ObtInventario(true, "", "", $vin, $modelo, "", $version, $color, $codigo, $condicion, $disponibilidad, $elem->agencia);

      if(count($datos_vehiculo)>0){
        foreach ($datos_vehiculo as $key => $elemDato) {      
          $datos_vehiculo[$key]->{'año'} = $elemDato->anio;
          $datos_vehiculo[$key]->{'colorInt'} = $elemDato->color_interior;
          $datos_vehiculo[$key]->{'potencia'} = $elemDato->hp;
          $datos_vehiculo[$key]->{'condicion'} = $elemDato->condicion_uso;          
        }
        $arrResult = array("result"=>"E000", "message"=>"Proceso completado correctamente", "datos_vehiculo"=>$datos_vehiculo);  
        $datosAgencias[] = array("agencia"=>strtoupper($elem->agencia), "inventarioAgencia"=>(object)$arrResult);
      }
    }  
  }else{ //Como no se encontro nombre de la agencia entonces todo todo por defecto
    $datos_vehiculo = $inventarioObj->ObtInventario(true, "", "", $vin, $modelo, "", $version, $color, $codigo, $condicion, $disponibilidad);

    if(count($datos_vehiculo)>0){
      foreach ($datos_vehiculo as $key => $elemDato) {      
        $datos_vehiculo[$key]->{'año'} = $elemDato->anio;
        $datos_vehiculo[$key]->{'colorInt'} = $elemDato->color_interior;
        $datos_vehiculo[$key]->{'potencia'} = $elemDato->hp;
        $datos_vehiculo[$key]->{'condicion'} = $elemDato->condicion_uso;          
      }
      $arrResult = array("result"=>"E000", "message"=>"Proceso completado correctamente", "datos_vehiculo"=>$datos_vehiculo);

      $datosAgencias[] = array("agencia"=>$agencia, "inventarioAgencia"=>(object)$arrResult);      
    }
  }

  // Comprobar que existan resultados
  if(count($datosAgencias)>0){
    $arrFinal = array("success"=>true, "datosAgencias"=>$datosAgencias);
  }

  echo $callback . '(' . json_encode($arrFinal) . ');';

  // echo "<pre>";
  // print_r($arrFinal);
  // echo "</pre>";  
}

// Imp. 27/02/20
// Obtener los permisos de los campos extras del inventarios
function inventarioPermisos($callback){
  $invPermisoObj = new inventarioPermisosObj();
  $arr = array("success"=>false);
  $colInventarioPermisos = $invPermisoObj->ObtInventarioPermisos(true);

  if(count($colInventarioPermisos)>0){
    $arr = array("success"=>true, "colInventarioPermisos"=>$colInventarioPermisos);
  }
  echo $callback . '(' . json_encode($arr) . ');';  
}


//Salvar la estadistica segun usuario (vendedor) 
//Imp. 17/01/2020
function salvarEstadistica(){
  $arr = array("success"=>false);  
  $arrRes = array();
  $arrEstadisticas = $_POST['datos'];

  if($arrEstadisticas!=""){
    $arrEstadisticas = explode("|-|", $_POST['datos']);

    if(count($arrEstadisticas)>0){
      foreach ($arrEstadisticas as $elem){
        // echo $elem."<br/>";
        $datoArr = explode("|", $elem);
        // echo $datoArr[2];

        
        if(count($datoArr)>0){
          $estadisticaAppObj = new estadisticasAppObj();

          $estadisticaAppObj->fechaLog = $datoArr[0];          
          $estadisticaAppObj->usuarioId = $datoArr[1];
          $estadisticaAppObj->nombre = $datoArr[2];
          $estadisticaAppObj->accion = $datoArr[3];

          $estadisticaAppObj->idModelo = $datoArr[4];
          $estadisticaAppObj->modelo = $datoArr[5];
          $estadisticaAppObj->idVersion = $datoArr[6];
          $estadisticaAppObj->version = $datoArr[7];
          $estadisticaAppObj->emailProspecto = $datoArr[8];
          $estadisticaAppObj->prospecto = $datoArr[9];
          
          $estadisticaAppObj->salvarEstadisticaApp();

          if($estadisticaAppObj->estadisticaId>0){
            $arrRes[] = $estadisticaAppObj->estadisticaId;
          }          
        }
      }
    }
  }
  
  if(count($arrRes)>0){
    $arr = array("success"=>true, "arrRes"=>$arrRes);
  }
    
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Max-Age: 1000');  
  echo json_encode($arr);
}


// Reporte excel 
function rptProspectos($callback, $idUsuario){
  include  '../common/config.php';
  // include_once '../brules/PHPExcel/PHPExcel/IOFactory.php';

  $dirname = dirname(__DIR__);
  //Col prospectos por el id del usuario vendedor
  $colProspectos = array();
  $prospectoObj = new catProspectosObj();
  $arr = array("success"=>false);
  
  // Obtener todos los prospectos por el id del usuario
  if($idUsuario>0){    
    // $colProspectos = $prospectoObj->obtTodosProspectosPorUsuario(true, $idUsuario);
    $timeZ = obtDateTimeZone();
    $arrMotivocompra = (object)array("vi_valordinero"=>"Valor por dinero", "vi_seguridad"=>"Seguridad", "vi_estatuspre"=>"Estatus/Prestigio", "vi_apdeportiva"=>"Apariencia Deportiva",
                     "vi_desempenio"=>"Desempe&ntilde;o", "vi_confort"=>"Confort", "vi_disenio"=>"Dise&ntilde;o", "vi_respaldomarca"=>"Respaldo Marca VW", 
                     "vi_economia"=>"Econom&iacute;a"); //, "vi_otro_mot"=>"Otro"

    $arrReqFun = (object)array("vi_absebd"=>"ABS/EBD", "vi_aireacondicionado"=>"Aire Acondicionado", "vi_rastreador"=>"Rastreador", "vi_remolque"=>"Remolque", "vi_ventelectricas"=>"Ventanas El&eacute;ctricas",
                       "vi_navsatelital"=>"Navegaci&oacute;n", "vi_quemacocos"=>"Quema Cocos", "vi_estandar"=>"Estandar", "vi_automatico"=>"Automat&iacute;co");

    $arrEquiFun = (object)array("vi_bluetooth"=>"Bluetooth", "vi_rines"=>"Rines", "vi_loderas"=>"Loderas", "vi_portaequipajes"=>"Portaequipajes", "vi_pantiasalto"=>"Pel&iacute;cula Anti-asalto",
                        "vi_lucesxenon"=>"Luces de Xenon", "vi_vidriospol"=>"Vidrios Polarizados", "vi_ganchoremolque"=>"Gancho de Remolque", "vi_farosantiniebla"=>"Faros Anti-niebla",
                        "vi_sensoresreversa"=>"Sensores de Reversa",
                       );

    $colProspectos = $prospectoObj->obtProspectosParaExportar(true, "", $idUsuario);
    // echo "<pre>";   
    // print_r($colProspectos);
    // echo "</pre>";

    set_time_limit(36000);
      date_default_timezone_set('America/Mexico_City');

      if (PHP_SAPI == 'cli')
              die('Este archivo solo se puede ver desde un navegador web');

      // Se crea el objeto PHPExcel
      $objPHPExcel = new PHPExcel();

      // Se asignan las propiedades del libro
      $objPHPExcel->getProperties()->setCreator("ZMotors") //Autor
                                  ->setLastModifiedBy("ZMotors") //Ultimo usuario que lo modifico
                                  ->setTitle("Reporte Prospectos")
                                  ->setSubject("Reporte Prospectos")
                                  ->setDescription("Reporte Prospectos")
                                  ->setKeywords("Reporte, Prospectos")
                                  ->setCategory("Reportes");
      
      //Opcion limitada
      if($opcExelProspecto>0){
        $titulosColumnas = array('Fecha Alta', 'Agencia', 'Vendedor', 'Prospecto', 'Tel&eacute;fono', 'Email');
      }else{ //Opcion completa 
        $titulosColumnas = array('Fecha Alta', 'Agencia', 'Vendedor', 'Prospecto', 'Direcci&oacute;n', 'Tel&eacute;fono', 'Email', 'Marca', 'Modelo', 'A&ntilde;o', 'Desea intercambiar este veh&iacute;culo', 'C&oacute;mo fue/es financiado este veh&iacute;culo', 'Uso del veh&iacute;culo actual', 'Donde se usa el veh&iacute;culo actual', 'Qu&eacute; equipamiento y caracter&iacute;sticas valora de su veh&iacute;culo actual', 'Porque raz&oacute;n quiere cambiar su veh&iacute;culo',
                               'Modelo', 'A&ntilde;o', 'Qu&eacute; uso tendra el nuevo autom&oacute;vil', 'Precio dispuesto a pagar', 'Otros modelos dentro del rango de inter&eacute;s', 'Requiere una prueba de manejo', 'Requiere financiamiento', 'Fecha de la prueba de manejo', 'Color preferido', 'Motivos para la compra', 'Requisitos funcionales', 'Equipamiento funcionales', 'Cu&aacute;ntos miembros hay en su familia', 'Qui&eacute;n va a manejar el veh&iacute;culo', 
                               'Cada cuando cambia su veh&iacute;culo', 'Fecha de propuesta de compra', 'Fecha propuesta para la llamada de seguimiento', 'Hora propuesta seguimiento', 'Qu&eacute; es lo que le gusta de este veh&iacute;culo', 'Comentarios');
      }    
      
      $tituloFechaConsulta = "Reporte de prospectos el ".html_entity_decode("d&iacute;a", ENT_QUOTES, "UTF-8")." ".$timeZ->fechaF2;
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $tituloFechaConsulta);
      $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:AK1');
      
      $totalTituloCol = count($titulosColumnas);
      $colA = 'A';
      $filaTitulo = 2;
      for($i=1; $i<=$totalTituloCol; $i++){                        
          $objPHPExcel->getActiveSheet()->setCellValue($colA.$filaTitulo, html_entity_decode($titulosColumnas[$i-1], ENT_QUOTES, "UTF-8"));
          $colA++;
      }
      
      $contLetra = "$colA"."1";
      $iIns=3;

      //CICLO
      
      if(count($colProspectos) > 0)
      {                            
          // $colBucle = "A"; 
          $filaBucle = 3;

          foreach ($colProspectos as $detalle) {
             $usuariosObj = new usuariosObj();
             $usuariosObj->UserByID((isset($detalle->usuarioId))?$detalle->usuarioId:0);
             $vendedor = $usuariosObj->nombre;
             $datosJson = json_decode($detalle->datosJson);             
             
             // echo "<pre>";
             // print_r($datosJson);
             // echo "</pre>";

            //Opcion limitada
            if($opcExelProspecto>0){ 
                $objPHPExcel->getActiveSheet()->setCellValue("A".$filaBucle, (isset($detalle->fechaAlta))?$detalle->fechaAlta:"");
                $objPHPExcel->getActiveSheet()->setCellValue("B".$filaBucle, (isset($detalle->agencia))?$detalle->agencia:"");
                $objPHPExcel->getActiveSheet()->setCellValue("C".$filaBucle, $vendedor);
                $objPHPExcel->getActiveSheet()->setCellValue("D".$filaBucle, (isset($detalle->nombre))?$detalle->nombre:"");
                $objPHPExcel->getActiveSheet()->setCellValue("E".$filaBucle, (isset($detalle->direccion))?$detalle->telefono:"");
                $objPHPExcel->getActiveSheet()->setCellValue("F".$filaBucle, (isset($detalle->telefono))?$detalle->email:"");
            }else{ //Opcion completa 
                $objPHPExcel->getActiveSheet()->setCellValue("A".$filaBucle, (isset($detalle->fechaAlta))?$detalle->fechaAlta:"");
                $objPHPExcel->getActiveSheet()->setCellValue("B".$filaBucle, (isset($detalle->agencia))?$detalle->agencia:"");
                $objPHPExcel->getActiveSheet()->setCellValue("C".$filaBucle, $vendedor);
                $objPHPExcel->getActiveSheet()->setCellValue("D".$filaBucle, (isset($detalle->nombre))?$detalle->nombre:"");
                $objPHPExcel->getActiveSheet()->setCellValue("E".$filaBucle, (isset($detalle->direccion))?$detalle->direccion:"");
                $objPHPExcel->getActiveSheet()->setCellValue("F".$filaBucle, (isset($detalle->telefono))?$detalle->telefono:"");
                $objPHPExcel->getActiveSheet()->setCellValue("G".$filaBucle, (isset($detalle->email))?$detalle->email:"");
                $objPHPExcel->getActiveSheet()->setCellValue("H".$filaBucle, (isset($datosJson->va_marca))?$datosJson->va_marca:"");
                $objPHPExcel->getActiveSheet()->setCellValue("I".$filaBucle, (isset($datosJson->va_modelo))?$datosJson->va_modelo:"");
                $objPHPExcel->getActiveSheet()->setCellValue("J".$filaBucle, (isset($datosJson->va_anio))?$datosJson->va_anio:"");
                $objPHPExcel->getActiveSheet()->setCellValue("K".$filaBucle, (isset($datosJson->va_intervehiculo))?$datosJson->va_intervehiculo:"");
                $objPHPExcel->getActiveSheet()->setCellValue("L".$filaBucle, (isset($datosJson->va_financiado))?$datosJson->va_financiado:"");
                $objPHPExcel->getActiveSheet()->setCellValue("M".$filaBucle, (isset($datosJson->va_usovehiculo))?$datosJson->va_usovehiculo:"");
                $objPHPExcel->getActiveSheet()->setCellValue("N".$filaBucle, (isset($datosJson->va_dondeusavehiculo))?$datosJson->va_dondeusavehiculo:"");
                $objPHPExcel->getActiveSheet()->setCellValue("O".$filaBucle, (isset($datosJson->va_equivalor))?$datosJson->va_equivalor:"");
                $objPHPExcel->getActiveSheet()->setCellValue("P".$filaBucle, (isset($datosJson->va_cambiarvehiculo))?$datosJson->va_cambiarvehiculo:"");
                $objPHPExcel->getActiveSheet()->setCellValue("Q".$filaBucle, (isset($datosJson->vi_modelo))?$datosJson->vi_modelo:"");
                $objPHPExcel->getActiveSheet()->setCellValue("R".$filaBucle, (isset($datosJson->vi_anio))?$datosJson->vi_anio:"");
                $objPHPExcel->getActiveSheet()->setCellValue("S".$filaBucle, (isset($datosJson->vi_uso))?$datosJson->vi_uso:"");
                $objPHPExcel->getActiveSheet()->setCellValue("T".$filaBucle, (isset($datosJson->vi_precio))?$datosJson->vi_precio:"");            
                $vi_modelo1 = (isset($datosJson->vi_modelo1))?$datosJson->vi_modelo1.",":"";
                $vi_modelo2 = (isset($datosJson->vi_modelo2))?$datosJson->vi_modelo2.",":"";
                $vi_modelo3 = (isset($datosJson->vi_modelo3))?$datosJson->vi_modelo3.",":"";            
                $objPHPExcel->getActiveSheet()->setCellValue("U".$filaBucle, ($vi_modelo1!="" && $vi_modelo1!=",")?$vi_modelo1:"".($vi_modelo2!="" && $vi_modelo2!=",")?$vi_modelo2:"".($vi_modelo3!="" && $vi_modelo3!=",")?$vi_modelo3:"");
                $objPHPExcel->getActiveSheet()->setCellValue("V".$filaBucle, (isset($datosJson->vi_pruebamanejo))?$datosJson->vi_pruebamanejo:"");
                $objPHPExcel->getActiveSheet()->setCellValue("W".$filaBucle, (isset($datosJson->vi_financiamiento))?$datosJson->vi_financiamiento:"");
                $objPHPExcel->getActiveSheet()->setCellValue("X".$filaBucle, (isset($datosJson->vi_fpruebamanejo))?$datosJson->vi_fpruebamanejo:"");
                $vi_color1 = (isset($datosJson->vi_color1))?$datosJson->vi_color1.",":"";
                $vi_color2 = (isset($datosJson->vi_color2))?$datosJson->vi_color2.",":"";
                $vi_color3 = (isset($datosJson->vi_color3))?$datosJson->vi_color3.",":"";
                $objPHPExcel->getActiveSheet()->setCellValue("Y".$filaBucle, ($vi_color1!="" && $vi_color1!=",")?$vi_color1:"".($vi_color2!="" && $vi_color2!=",")?$vi_color2:"".($vi_color3!="" && $vi_color3!=",")?$vi_color3:"");
                if(count($datosJson->vi_motivocompra)>0){
                   $arrMotivos = array(); 
                   foreach ($datosJson->vi_motivocompra as $elemMC){
                      foreach ($arrMotivocompra as $key => $elemArr){
                        if($key==$elemMC){
                          $arrMotivos[] = $elemArr;                      
                          break;
                        }
                      }                                              
                    }
                    $vi_motivocompra = implode(",", $arrMotivos);
                    //Otro
                    // foreach ($datosJson->vi_motivocompra as $elemMC){                                                                                            
                    //   if($elemMC == "vi_otro_mot"){                     
                    //     <input type="text" id="vi_otro_mot_inp" name="vi_otro_mot_inp" value="echo $datosJson->vi_otro_mot_inp;" class="form-control" readonly>                      
                    //   }
                    // }
                }else{
                    $vi_motivocompra = "";
                }
                $objPHPExcel->getActiveSheet()->setCellValue("Z".$filaBucle, $vi_motivocompra);

                if(count($datosJson->vi_requisitos)>0){
                  $arrRequisitos = array(); 
                   foreach ($datosJson->vi_requisitos as $elemRF){
                      foreach ($arrReqFun as $key => $elemArr){
                        if($key==$elemRF){
                          $arrRequisitos[] = $elemArr;                      
                          break;
                        }
                      }                                              
                    }
                    $vi_requisitos = implode(",", $arrRequisitos);
                    //Otro
                    // foreach ($datosJson->vi_requisitos as $elemRF){                                                                                            
                    //   if($elemRF == "vi_otro_req"){                     
                    //     <input type="text" id="vi_otro_req_inp" name="vi_otro_req_inp" value="echo $datosJson->vi_otro_req_inp;" class="form-control" readonly>                      
                    //   }
                    // }
                }else{
                    $vi_requisitos = "";
                }
                $objPHPExcel->getActiveSheet()->setCellValue("AA".$filaBucle, $vi_requisitos);
                if(count($datosJson->vi_equipamiento)>0){
                   $arrEquipamiento = array();  
                   foreach ($datosJson->vi_equipamiento as $elemEQ){
                      foreach ($arrEquiFun as $key => $elemArr){
                        if($key==$elemEQ){
                          $arrEquipamiento[] = $elemArr;
                          break;
                        }
                      }                                              
                    }
                     $vi_equipamiento = implode(",", $arrEquipamiento);
                    //Otro
                    // foreach ($datosJson->vi_equipamiento as $elemEQ){                                                                                            
                    //   if($elemEQ == "vi_otro_eq"){                     
                    //     // <input type="text" id="vi_otro_eq_inp" name="vi_otro_eq_inp" value="echo $datosJson->vi_otro_eq_inp;" class="form-control" readonly>                      
                    //   }
                    // }
                }else{
                    $vi_equipamiento = "";
                }
                $objPHPExcel->getActiveSheet()->setCellValue("AB".$filaBucle, $vi_equipamiento);
                $objPHPExcel->getActiveSheet()->setCellValue("AC".$filaBucle, (isset($datosJson->vi_miembrosfamilia))?$datosJson->vi_miembrosfamilia:"");
                $objPHPExcel->getActiveSheet()->setCellValue("AD".$filaBucle, (isset($datosJson->vi_quienmaneja))?$datosJson->vi_quienmaneja:"");
                $objPHPExcel->getActiveSheet()->setCellValue("AE".$filaBucle, (isset($datosJson->vi_cambiavehiculo))?$datosJson->vi_cambiavehiculo:"");
                $objPHPExcel->getActiveSheet()->setCellValue("AF".$filaBucle, (isset($datosJson->vi_fpropuestacompra))?$datosJson->vi_fpropuestacompra:"");
                $objPHPExcel->getActiveSheet()->setCellValue("AG".$filaBucle, (isset($datosJson->vi_fseguimiento))?$datosJson->vi_fseguimiento:"");
                $objPHPExcel->getActiveSheet()->setCellValue("AH".$filaBucle, (isset($datosJson->vi_horaseguimiento))?$datosJson->vi_horaseguimiento:"");
                $objPHPExcel->getActiveSheet()->setCellValue("AI".$filaBucle, (isset($datosJson->gustoporvehiculo))?$datosJson->gustoporvehiculo:"");
                $objPHPExcel->getActiveSheet()->setCellValue("AJ".$filaBucle, (isset($datosJson->gralcomentario))?$datosJson->gralcomentario:"");
            }
                
            $filaBucle++;
          }
          // exit();
      }
      
    //FIN CICLO

                                
      $estiloTituloColumnas = array('font' => array('name'=> 'Arial', 
                                                    'size'=>8, 
                                                    'bold'=> false, 
                                                    'color'=> array('rgb' => '000000')
                                                   ),
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                         'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                         'wrap'=>TRUE
                                                       )
                                  );

      $estiloInformacion = new PHPExcel_Style();
      $estiloInformacion->applyFromArray(array('font' => array('name'=>'Arial',
                                                               'size'=>8,
                                                               'color'=> array('rgb' => '000000')
                                                              )
                                              )
                                        );

      $filaGris = array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => 'D8D8D8')
                       );
      $estiloFechaConsulta = array('font' => array('name'=> 'Arial',
                                                    'size'=>11,
                                                    'bold'=> true,
                                                    'color'=> array('rgb' => '000000')                    
                                                   ),
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                         'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                         'wrap'=>TRUE
                                                       )
                                  );

     $estiloEncabezados = array('font' => array('name'=> 'Arial',
                                                    'size'=> 9,
                                                    'bold'=> true,
                                                    'color'=> array('rgb' => '000000')                    
                                                   ),
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                         'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                         'wrap'=>TRUE
                                                       )
                                  );


      $objPHPExcel->getActiveSheet()->getStyle('A1:AK1')->applyFromArray($estiloTituloColumnas);
      // $objPHPExcel->getActiveSheet()->getStyle('A3:AK3')->applyFromArray($estiloEncabezados);
      $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, 'A3:AK3'.($filaBucle-1));

      for($j = 'A'; $j <=$contLetra; $j++){
          $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($j)->setAutoSize(TRUE);
      }
      //Estilo a fecha consulta
      $objPHPExcel->getActiveSheet()->getStyle("A1:AK1")->applyFromArray($estiloFechaConsulta);
          
      
      // Se asigna el nombre a la hoja
      $nReporte = "Reporte Prospectos";
      $objPHPExcel->getActiveSheet()->setTitle($nReporte);
      // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
      $objPHPExcel->setActiveSheetIndex(0);
      // Inmovilizar paneles         
      $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,3);

      $titleFile = str_replace(" ", "", $nReporte)."_".str_replace("/", "", $timeZ->fechaF2).".xls";
      // header('Content-Type: application/vnd.ms-excel');
      // header("Content-Disposition: attachment;filename=$titleFile");
      // header('Cache-Control: max-age=0');

      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
      // $objWriter->save('php://output');              
      if(!file_exists('../upload/excelProspectos/'.$titleFile)){
        $file = fopen('../upload/excelProspectos/'.$titleFile, "w");
        fclose($file);        
      }
      sleep(1);
      $objWriter->save('../upload/excelProspectos/'.$titleFile);

      sleep(2);
      //verificar que el archivo se genero correctamente
      $archivoXls = $dirname.'/upload/excelProspectos/'.$titleFile;
      if(file_exists($archivoXls)){
        $urlArchivo = $siteURL.'/upload/excelProspectos/'.$titleFile;

        $arr = array("success"=>true, "urlArchivo"=>$urlArchivo);
      }
  }

  echo $callback . '(' . json_encode($arr) . ');';
}



// Obtiene coleccion de modelos por busqueda en el inventario  BORRAR SOLO ES UNA PRUEBA
function busquedaInventario2($callback, $idUsuario, $vin, $modelo, $year, $version, $color, $codigo){
  $wsdl = "https://inventario.mega-invoice.com/ws/interface_inventario.php?wsdl"; //url ws
  //obtener el rfc y dealer por el id del usuario (vendedor)

  //Puebla (Z MOTORS SA DE CV)
  $agencia = "Z MOTORS PUEBLA";
  // $codigo = "";
  $rfc = "ZMO970117GJ5";
  $dealer= "2015";    
  $client = new SoapClient($wsdl);  
  try{    
    // $arr = $client->Buscar(array("codigo"=>$codigo, "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>$vin, "modelo"=>$modelo, "year"=>$year, "version"=>$version, "color"=>$color));   
    // $arr = $client->Buscar(array("codigo"=>$codigo, "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>"", "year"=>"", "version"=>"", "color"=>""));   
    // $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "year"=>"", "version"=>"", "color"=>""));   
    // $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "anio"=>"", "version"=>"", "color"=>"", "clave_v"=>$codigo, "condicion"=>"nuevo", "disponibilidad"=>"1"));   
    $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "anio"=>"", "version"=>"", "color"=>"", "clv_vehicular"=>$codigo, "condicion"=>"nuevo", "disponibilidad"=>"1"));   
  }catch (SoapFault $e){
    $arr = (object)array("result"=>"error_busqueda");    
  }
  if($arr->result!="error_busqueda"){
    if(isset($arr->datos_vehiculo)){
      if(is_array($arr->datos_vehiculo)){
        // echo "Es array ".$agencia."<br/>";
        //agregar variables para que sean leidas en el app y asi no recompilar 
        //Implementado el 02/07/2019
        $datos_vehiculo = $arr->datos_vehiculo;
        foreach ($datos_vehiculo as $key => $elemDato) {      
          $datos_vehiculo[$key]->{'año'} = $elemDato->anio;
          $datos_vehiculo[$key]->{'colorInt'} = $elemDato->color_interior;
          $datos_vehiculo[$key]->{'potencia'} = $elemDato->hp;
          $datos_vehiculo[$key]->{'condicion'} = $elemDato->condicion_uso;          
        }
      }else{
        // echo "No es array ".$agencia."<br/>";
        $datos_vehiculo = $arr->datos_vehiculo;
        $datos_vehiculo->{'año'} = $datos_vehiculo->anio;
        $datos_vehiculo->{'colorInt'} = $datos_vehiculo->color_interior;
        $datos_vehiculo->{'potencia'} = $datos_vehiculo->hp;
        $datos_vehiculo->{'condicion'} = $datos_vehiculo->condicion_uso;
      }
      $arr->{'datos_vehiculo'} = $datos_vehiculo;    
    }

    $datosAgencias[0] = array("agencia"=>$agencia, "inventarioAgencia"=>$arr);
    $arrFinal = array("datosAgencias"=>$datosAgencias);
  }else{
    $arrFinal = array();
  }
  
  //Cholula
  $agencia = "Z MOTORS CHOLULA";
  // $codigo = "";
  $rfc = "ZMO970117GJ5";
  $dealer= "20151";
  $client = new SoapClient($wsdl);  
  try{
    // $arr = $client->Buscar(array("codigo"=>$codigo, "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>$vin, "modelo"=>$modelo, "year"=>$year, "version"=>$version, "color"=>$color));   
    // $arr = $client->Buscar(array("codigo"=>$codigo, "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>"", "year"=>"", "version"=>"", "color"=>""));   
    // $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "year"=>"", "version"=>"", "color"=>""));   
    // $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "anio"=>"", "version"=>"", "color"=>"", "clave_v"=>$codigo, "condicion"=>"nuevo", "disponibilidad"=>"1"));   
    $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "anio"=>"", "version"=>"", "color"=>"", "clv_vehicular"=>$codigo, "condicion"=>"nuevo", "disponibilidad"=>"1"));   
  }catch (SoapFault $e){
    $arr = (object)array("result"=>"error_busqueda");    
  }  
  if($arr->result!="error_busqueda"){
    if(isset($arr->datos_vehiculo)){
      if(is_array($arr->datos_vehiculo)){
        // echo "Es array ".$agencia."<br/>";
        //agregar variables para que sean leidas en el app y asi no recompilar 
        //Implementado el 02/07/2019
        $datos_vehiculo = $arr->datos_vehiculo;
        foreach ($datos_vehiculo as $key => $elemDato) {      
          $datos_vehiculo[$key]->{'año'} = $elemDato->anio;
          $datos_vehiculo[$key]->{'colorInt'} = $elemDato->color_interior;
          $datos_vehiculo[$key]->{'potencia'} = $elemDato->hp;
          $datos_vehiculo[$key]->{'condicion'} = $elemDato->condicion_uso;          
        }
      }else{
        // echo "No es array ".$agencia."<br/>";
        $datos_vehiculo = $arr->datos_vehiculo;
        $datos_vehiculo->{'año'} = $datos_vehiculo->anio;
        $datos_vehiculo->{'colorInt'} = $datos_vehiculo->color_interior;
        $datos_vehiculo->{'potencia'} = $datos_vehiculo->hp;
        $datos_vehiculo->{'condicion'} = $datos_vehiculo->condicion_uso;
      }
      $arr->{'datos_vehiculo'} = $datos_vehiculo;    
    }

    $datosAgencias[1] = array("agencia"=>$agencia, "inventarioAgencia"=>$arr);
    $arrFinal = array("datosAgencias"=>$datosAgencias);
  }else{
    $arrFinal = array();
  }

  //Mexico
  $agencia = "Z MOTORS M&Eacute;XICO";
  // $codigo = "";
  $rfc = "ZMM030710TA6";
  $dealer= "1431";
  $client = new SoapClient($wsdl);  
  try{
    // $arr = $client->Buscar(array("codigo"=>$codigo, "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>$vin, "modelo"=>$modelo, "year"=>$year, "version"=>$version, "color"=>$color));   
    // $arr = $client->Buscar(array("codigo"=>$codigo, "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>"", "year"=>"", "version"=>"", "color"=>""));   
    // $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "year"=>"", "version"=>"", "color"=>""));
    // $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "anio"=>"", "version"=>"", "color"=>"", "clave_v"=>$codigo, "condicion"=>"nuevo", "disponibilidad"=>"1"));
    $arr = $client->Buscar(array("codigo"=>"", "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>"", "modelo"=>$modelo, "anio"=>"", "version"=>"", "color"=>"", "clv_vehicular"=>$codigo, "condicion"=>"nuevo", "disponibilidad"=>"1"));
  }catch (SoapFault $e){
    $arr = (object)array("result"=>"error_busqueda");    
  }  
  if($arr->result!="error_busqueda"){
    if(isset($arr->datos_vehiculo)){
      if(is_array($arr->datos_vehiculo)){
        // echo "Es array ".$agencia."<br/>";
        //agregar variables para que sean leidas en el app y asi no recompilar 
        //Implementado el 02/07/2019
        $datos_vehiculo = $arr->datos_vehiculo;
        foreach ($datos_vehiculo as $key => $elemDato) {      
          $datos_vehiculo[$key]->{'año'} = $elemDato->anio;
          $datos_vehiculo[$key]->{'colorInt'} = $elemDato->color_interior;
          $datos_vehiculo[$key]->{'potencia'} = $elemDato->hp;
          $datos_vehiculo[$key]->{'condicion'} = $elemDato->condicion_uso;          
        }
      }else{
        // echo "No es array ".$agencia."<br/>";
        $datos_vehiculo = $arr->datos_vehiculo;
        $datos_vehiculo->{'año'} = $datos_vehiculo->anio;
        $datos_vehiculo->{'colorInt'} = $datos_vehiculo->color_interior;
        $datos_vehiculo->{'potencia'} = $datos_vehiculo->hp;
        $datos_vehiculo->{'condicion'} = $datos_vehiculo->condicion_uso;
      }
      $arr->{'datos_vehiculo'} = $datos_vehiculo;    
    }
    
    $datosAgencias[2] = array("agencia"=>$agencia, "inventarioAgencia"=>$arr);
    $arrFinal = array("datosAgencias"=>$datosAgencias);
  }else{
    $arrFinal = array();
  }
  
  
  // echo $callback . '(' . json_encode($arrFinal) . ');';

  echo "<pre>";
  print_r($arrFinal);
  echo "</pre>";
}
?>