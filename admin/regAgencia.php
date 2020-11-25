<?php
session_start();
$checkRol = ($_SESSION['idRol']==1 || $_SESSION['idRol']==2) ?true :false;

if($_SESSION['status']!= "ok" || $checkRol!=true)
        header("location: logout.php");

include_once '../common/screenPortions.php';
include '../brules/utilsObj.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
libreriasKool();
$localization = "../brules/KoolControls/KoolCalendar/localization/es.xml";
include_once '../brules/catActualizacionesObj.php';
include_once '../brules/catAgenciasObj.php';

$agenciaObj = new catAgenciasObj();
$dTZoneObj = obtDateTimeZone();
$antAnio = aniosPrevPos(7, $dTZoneObj->anio, "prev");
$despAnio = aniosPrevPos(5, $dTZoneObj->anio, "pos");
$regId = (isset($_GET['regId'])) ?$_GET['regId'] :0;
$tipoAviso = '';

if(isset($_POST['btnCrearRegistro'])){
    $regId = $_POST['regId'];

    /*echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    exit();*/

    //Verifica si hay imagen por subir    
    $paramsImg = (object)array("folderDestino"=>"../upload/agencias/", "arrExt"=>array("jpg","png","jpeg"));
    $resArrImg = subirImagen($_FILES, $paramsImg);
    
    //Setear parametros
    $agenciaObj->nombre  = $_POST['nombre_agencia'];
    $agenciaObj->enlaceCot  = $_POST['enlacevwfs_agencia'];
    $agenciaObj->enlaceBank  = $_POST['enlacevwfsbank_agencia'];
    $agenciaObj->urlsActivas  = (isset($_POST['urlactivas_agencia'])) ?$_POST['urlactivas_agencia'] :0;      
    $agenciaObj->gtVwsf  = $_POST['gtevwsf_agencia'];
    $agenciaObj->correoGtvwsf  = $_POST['correovwsf_agencia'];
    $agenciaObj->gtVentas  = $_POST['gteventas_agencia'];
    $agenciaObj->correoGtVentas  = $_POST['correoventas_agencia'];
    $agenciaObj->gtGral  = $_POST['gtegral_agencia'];
    $agenciaObj->correoGtGral  = $_POST['correogral_agencia'];
    $agenciaObj->activo  = 1;
          
    //Insertar
    if($regId==0){        
        $agenciaObj->logo  = '../upload/agencias/'.$resArrImg->nImg;

        $agenciaObj->InsertAgencia();

        $regId = $agenciaObj->agenciaId;
        $tipoAviso = ($regId > 0)?1:0;
        $actualizacionesObj = new catActualizacionesObj();
        $actualizacionesObj->updActualizacion("cat_agencias");
    }else{
        //Actualizar
        $agenciaObj->logo  = ($resArrImg->result==true) ?'../upload/agencias/'.$resArrImg->nImg:$_POST['hid_imagen'];
        $agenciaObj->agenciaId = $regId;

        $respAct = $agenciaObj->ActAgencia();
        $tipoAviso = ($respAct > 0)?1:2;
        if($respAct>0){
          $actualizacionesObj = new catActualizacionesObj();
          $actualizacionesObj->updActualizacion("cat_agencias");
        }
    }
}

//Obtener datos de la agencia
$agenciaObj->AgenciaPorId($regId);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Registro Agencia</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>    
    <link href="../css/style.css" rel="stylesheet" type="text/css" />  
    <link href="../css/alertify.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../css/themes/semantic.min.css"/>
    <script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/additional-methods.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>    
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="../js/alertify.js"></script>
    <script>var tieneAlertify = true;</script>
    <script type="text/javascript" src="../js/functions.js"></script>
    <script>
      $( document ).ready(function() {
        mostrarAlertify();
      });
    </script>
</head>
<body>
    <?php echo getHeaderMain($_SESSION['myusername'], true);?>
    <?php $menu = getAdminMenu(); ?>

    <section class="section-internas">
        <div class="panel-body">
            <div class="container">
                <div class="row">
                    <div class="colmenu col-md-3 menu_bg ">
                        <?php echo getNav($menu); ?>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                          <div class="col-md-8">
                            <h1 class="titulo">Registro Agencia</h1>
                          </div>
                        </div>
                        

                        <input type="hidden" id="tipoAviso" value="<?php echo $tipoAviso?>">
                        <div class="cont_registrogamamodelo">
                          <form role="form" id="formRegAgencia" name="formRegAgencia" method="post" action="" enctype="multipart/form-data">                                                           
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="nombre_agencia">Nombre:</label>
                                  </div>
                                  <div class="col-md-6 col-sm-6 col-xs-6">
                                      <input type="text" id="nombre_agencia" name="nombre_agencia" value="<?php echo $agenciaObj->nombre;?>" class="form-control required">
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="enlacevwfs_agencia">Enlace VWFS:</label>
                                  </div>
                                  <div class="col-md-6 col-sm-6 col-xs-6">
                                      <input type="text" id="enlacevwfs_agencia" name="enlacevwfs_agencia" value="<?php echo $agenciaObj->enlaceCot;?>" class="form-control required">
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="enlacevwfsbank_agencia">Enlace VWFS Bank:</label>
                                  </div>
                                  <div class="col-md-6 col-sm-6 col-xs-6">
                                      <input type="text" id="enlacevwfsbank_agencia" name="enlacevwfsbank_agencia" value="<?php echo $agenciaObj->enlaceBank;?>" class="form-control required">
                                  </div>
                              </div>
                              <div class="row">
                                <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                    <label for="urlactivas_agencia">Url Activas:</label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                   <input type="checkbox" id="urlactivas_agencia" name="urlactivas_agencia" value="<?php echo ($agenciaObj->urlsActivas==1) ?1:0;?>" class="form-control" <?php echo ($agenciaObj->urlsActivas==1)?"checked":""; ?> >
                                </div>
                              </div>
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="gtevwsf_agencia">Gerente VWSF:</label>
                                  </div>
                                  <div class="col-md-6 col-sm-6 col-xs-6">
                                      <input type="text" id="gtevwsf_agencia" name="gtevwsf_agencia" value="<?php echo $agenciaObj->gtVwsf;?>" class="form-control">
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="correovwsf_agencia">Correo VWSF:</label>
                                  </div>
                                  <div class="col-md-6 col-sm-6 col-xs-6">
                                      <input type="text" id="correovwsf_agencia" name="correovwsf_agencia" value="<?php echo $agenciaObj->correoGtvwsf;?>" class="form-control">
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="gteventas_agencia">Gerente Ventas:</label>
                                  </div>
                                  <div class="col-md-6 col-sm-6 col-xs-6">
                                      <input type="text" id="gteventas_agencia" name="gteventas_agencia" value="<?php echo $agenciaObj->gtVentas;?>" class="form-control">
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="correoventas_agencia">Correo Ventas:</label>
                                  </div>
                                  <div class="col-md-6 col-sm-6 col-xs-6">
                                      <input type="text" id="correoventas_agencia" name="correoventas_agencia" value="<?php echo $agenciaObj->correoGtVentas;?>" class="form-control">
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="gtegral_agencia">Gerente General:</label>
                                  </div>
                                  <div class="col-md-6 col-sm-6 col-xs-6">
                                      <input type="text" id="gtegral_agencia" name="gtegral_agencia" value="<?php echo $agenciaObj->gtGral;?>" class="form-control">
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="correogral_agencia">Correo General:</label>
                                  </div>
                                  <div class="col-md-6 col-sm-6 col-xs-6">
                                      <input type="text" id="correogral_agencia" name="correogral_agencia" value="<?php echo $agenciaObj->correoGtGral;?>" class="form-control">
                                  </div>
                              </div>
                                                             
                              <div class="row">
                                <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                    <label for="logo_agencia">Logo:</label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                   <?php if($agenciaObj->logo!=''){ ?>
                                      <img src="<?php echo ''.$agenciaObj->logo;?>" height="68"><br/>
                                      <input type="file" name="imagen" id="imagen" size="10" class="form-control" accept="image/jpg, image/jpeg, image/png" />
                                      <input type="hidden" name="hid_imagen" id="hid_imagen" value="<?php echo $agenciaObj->logo; ?>">
                                  <?php }else{ ?>
                                      <input type="file" name="imagen" id="imagen" size="10" class="form-control required" accept="image/jpg, image/jpeg, image/png" />
                                  <?php } ?>
                                </div>
                              </div>
                                
                              <div class="new_line"></div>
                              <div class="row">
                                <div class="col-md-offset-1 col-md-3">
                                  <button type="submit" id="btnCrearRegistro" name="btnCrearRegistro" class="btn btn-success">Aceptar</button>                                    
                                </div>
                                <div class="col-md-offset-5 col-md-3">
                                  <a href="catalogos.php?catalog=catAgencias" class="btn btn-success" role="button">Regresar</a>
                                </div>
                              </div>
                              <div class="new_line"></div>

                              <input type="hidden" id="regId" name="regId" value="<?php echo $regId;?>">
                          </form> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="panel-footer">
            <?php echo getPiePag(true); ?>
        </div>
    </footer>
</body>
</html>
