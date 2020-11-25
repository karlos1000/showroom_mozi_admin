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
include_once '../brules/inventariosObj.php';
require_once '../brules/PHPExcel/PHPExcel/IOFactory.php';

$inventarioObj = new inventariosObj();
$dTZoneObj = obtDateTimeZone();
$tipoAviso = '';
$printMsg = false;
$msgFinal = "";

// Importar datos del excel 
if(isset($_POST['btnSubirInventario'])){    
    //metodo para importar el archivo
    $printMsg = true;
    $resImport = $inventarioObj->ImportarInventario($_FILES);
    if(is_array($resImport)){
      $totalFilas = count($resImport["arrSalvados"]) + count($resImport["arrNoSalvados"]);
      $msgFinal = "Se salvaron <strong>".count($resImport["arrSalvados"]) ."</strong> registros de un total de <strong>".$totalFilas ."</strong> filas.";
    }else{
      $msgFinal = $resImport;
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Registro Inventario</title>
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
                            <h1 class="titulo">Registrar Inventario</h1>
                          </div>                          
                        </div>
    
                        <div class="alert alert-info" role="alert">
                          <b>Nota:</b> Solo se permiten archivos xls y que respeten la estructura correcta de columnas, ver ejemplo <a href="../images/col_inventario.png" target="_blank"><b>aqu&iacute;</b></a>

                          <!-- <b>Nota:</b> Solo se permiten archivos xls -->
                        </div>
                        
                      
                        <div class="cont_registroinventario">
                          <form role="form" id="formRegInventario" name="formRegInventario" method="post" action="" enctype="multipart/form-data">                                                           
                              <!-- <div class="row">
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
                                      <label for="activo">Activo:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                     <input type="checkbox" id="activo" name="activo" value="<?php echo ($gMObj->activo==1) ?1:0;?>" class="form-control" <?php echo ($gMObj->activo==1)?"checked":""; ?> >
                                  </div>
                                </div>
                                -->
                                
                                <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="nameFile">Archivo (xls):</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                    <input type="file" name="nameFile" id="nameFile" size="10" class="form-control required" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
                                  </div>
                                </div>
                                                              
                               
                                
                                <div class="new_line"></div>
                                <div class="row">
                                  <div class="col-md-offset-1 col-md-3">
                                    <button type="submit" id="btnSubirInventario" name="btnSubirInventario" class="btn btn-success">Importar</button>                                    
                                  </div>                                  
                                </div>                              
                          </form> 
                          
                          <!--Imprimir el mensaje-->    
                          <?php if ($printMsg == true) { ?>
                            <div class="row" id="cont_msg_resp">
                              <div class="text-center form-group col-md-offset-6  col-md-6 col-sm-6 col-xs-6">
                                <div class="alert alert-warning" role="alert">
                                <?php echo $msgFinal; ?>
                                </div>
                              </div>
                            </div>                            
                          <?php } ?>
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
