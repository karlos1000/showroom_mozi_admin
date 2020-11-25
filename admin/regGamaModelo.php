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
include_once '../brules/gamaModelosObj.php';
include_once '../brules/catActualizacionesObj.php';
$gMObj = new gamaModelosObj();
$dTZoneObj = obtDateTimeZone();
$antAnio = aniosPrevPos(7, $dTZoneObj->anio, "prev");
$despAnio = aniosPrevPos(5, $dTZoneObj->anio, "pos");
$regId = (isset($_GET['regId'])) ?$_GET['regId'] :0;

$tipoAviso = '';
if(isset($_POST['btnCrearRegistro'])){      
    $regId = $_POST['regId'];

    //Verifica si hay imagen por subir    
    $paramsImg = (object)array("folderDestino"=>"../upload/modelosImg/", "arrExt"=>array("jpg","png","jpeg","gif"));
    $resArrImg = subirImagen($_FILES, $paramsImg);
    
    //Setear parametros
    $gMObj->modelo  = $_POST['modelo'];
    $gMObj->anio    = $_POST['anio'];
    $gMObj->eslogan = $_POST['eslogan'];
    $gMObj->activo  = (isset($_POST['activo'])) ?$_POST['activo'] :0;
    $gMObj->camion = (isset($_POST['camion'])) ?$_POST['camion'] :0;
    $gMObj->nuevo = (isset($_POST['esnuevo'])) ?$_POST['esnuevo'] :0;
    $gMObj->mostrarAnio = (isset($_POST['mostrarAnio'])) ?$_POST['mostrarAnio'] :0;    
    
    //Insertar nuevo modelo
    if($regId==0){        
        $gMObj->imagen  = '../upload/modelosImg/'.$resArrImg->nImg;

        $gMObj->InsertGamaModelo();           
        $regId = $gMObj->gModeloId;
        $tipoAviso = ($regId > 0)?1:0;
        $actualizacionesObj = new catActualizacionesObj();
        $actualizacionesObj->updActualizacion("gama_modelos");

        //Actualizar el numero del orden
        $gMObj->ActNumOrdenModelo($regId);
    }else{
     //Actualizar modelo        
        $gMObj->imagen  = ($resArrImg->result==true) ?'../upload/modelosImg/'.$resArrImg->nImg:$_POST['hid_imagen'];
        $gMObj->gModeloId = $regId;

        $respAct = $gMObj->ActGamaModelo();
        $tipoAviso = ($respAct > 0)?1:2;
        if($respAct>0){
          $actualizacionesObj = new catActualizacionesObj();
          $actualizacionesObj->updActualizacion("gama_modelos");
        }
    }
}

//Obtener datos del modelo
$gMObj->ObtDatosGModeloPorId($regId);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Registro Gama Modelo</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>    
    <link href="../css/style.css" rel="stylesheet" type="text/css" />

    <!-- <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"> -->
    <link href="../css/alertify.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="../css/alertify.default.css" rel="stylesheet" type="text/css" /> -->
    <link rel="stylesheet" href="../css/themes/semantic.min.css"/>
    <script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/additional-methods.js"></script>
    <!-- <script type="text/javascript" src="../js/dosjquery.validate.min.js"></script> -->
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
                            <h1 class="titulo">Registro Gama Modelo </h1>
                          </div>
                          <div class="col-md-4">
                            <a href="galeriamodelo.php?idM=<?php echo $regId; ?>" target="_blank" class="btn btn-success" role="button">Agregar Galer&iacute;a</a>                            
                            <a href="videosmodelo.php?idM=<?php echo $regId; ?>" target="_blank" class="btn btn-success" role="button">Agregar Video</a>
                          </div>
                        </div>
                        

                        <input type="hidden" id="tipoAviso" value="<?php echo $tipoAviso?>">
                        <div class="cont_registrogamamodelo">
                          <form role="form" id="formRegGamaModelo" name="formRegGamaModelo" method="post" action="" enctype="multipart/form-data">                                                           
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="modelo">Modelo:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="modelo" name="modelo" value="<?php echo $gMObj->modelo;?>" class="form-control required">
                                  </div>
                              </div>
                              <div class="row">
                                 <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                     <label for="anio">A&ntilde;o:</label>
                                 </div>
                                 <div class="col-md-6 col-sm-6 col-xs-6">
                                     <select id="anio" name="anio" class="form-control required">
                                       <option value="">--Selecciona--</option>
                                       <?php 
                                        foreach (range($despAnio, $antAnio) as $anioEach) {
                                            $selAnio = ($anioEach==$gMObj->anio) ?"selected" :"";                                            
                                            ?>
                                            <option <?php echo $selAnio;?> value="<?php echo $anioEach; ?>"><?php echo $anioEach; ?></option> 
                                            <?php                                             
                                        }
                                       ?>
                                   </select>
                                 </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="eslogan">Eslogan:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="eslogan" name="eslogan" value="<?php echo $gMObj->eslogan;?>" class="form-control">
                                  </div>
                              </div>
                                <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="imagen">Imagen:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                    <!-- accept="image/*"  -->                                                                        
                                     <?php if($gMObj->imagen!=''){ ?>
                                        <img src="<?php echo ''.$gMObj->imagen;?>" height="68"><br/>
                                        <input type="file" name="imagen" id="imagen" size="10" class="form-control" accept="image/gif, image/jpg, image/jpeg, image/png" />
                                        <input type="hidden" name="hid_imagen" id="hid_imagen" value="<?php echo $gMObj->imagen; ?>">
                                    <?php }else{ ?>                
                                        <input type="file" name="imagen" id="imagen" size="10" class="form-control required" accept="image/gif, image/jpg, image/jpeg, image/png" />
                                    <?php } ?>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="activo">Activo:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                     <input type="checkbox" id="activo" name="activo" value="<?php echo ($gMObj->activo==1) ?1:0;?>" class="form-control" <?php echo ($gMObj->activo==1)?"checked":""; ?> >
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="camion">Es cami&oacute;n:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                     <input type="checkbox" id="camion" name="camion" value="<?php echo ($gMObj->camion==1) ?1:0;?>" class="form-control" <?php echo ($gMObj->camion==1)?"checked":""; ?> >
                                  </div>
                                </div>
                                
                                <!-- implementado el 15/01/19 -->
                                <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="esnuevo">Es nuevo:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                     <input type="checkbox" id="esnuevo" name="esnuevo" value="<?php echo ($gMObj->nuevo==1) ?1:0;?>" class="form-control" <?php echo ($gMObj->nuevo==1)?"checked":""; ?> >
                                  </div>
                                </div>
                                <!-- implementado el 15/01/19 -->
                                <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="mostrarAnio">Mostrar a&ntilde;o:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                     <input type="checkbox" id="mostrarAnio" name="mostrarAnio" value="<?php echo ($gMObj->mostrarAnio==1) ?1:0;?>" class="form-control" <?php echo ($gMObj->mostrarAnio==1)?"checked":""; ?> >
                                  </div>
                                </div>
                                
                                <div class="new_line"></div>
                                <div class="row">
                                  <div class="col-md-offset-1 col-md-3">
                                    <button type="submit" id="btnCrearRegistro" name="btnCrearRegistro" class="btn btn-success">Aceptar</button>                                    
                                  </div>
                                  <div class="col-md-offset-5 col-md-3">
                                    <a href="gamamodelos.php" class="btn btn-success" role="button">Regresar</a>
                                  </div>
                                </div>

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
