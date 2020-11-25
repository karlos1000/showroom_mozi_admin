<?php
session_start();
$idRol = $_SESSION['idRol'];
switch ($idRol) {
    case 1: $rol = true;
    case 2: $rol = true; break;
    case 3: $rol = true; break;
        break;
    default: $rol = false;
        break;
}
if ($_SESSION['status'] != "ok" || $rol != true || $_SESSION["permisos"]["Menu_Configuraciones_Ver"] == 0)
        header("location: logout.php");

include_once '../common/screenPortions.php';
include_once '../brules/KoolControls/KoolGrid/koolgrid.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
require_once '../brules/KoolControls/KoolCalendar/koolcalendar.php';
include_once '../brules/KoolControls/KoolGrid/ext/datasources/MySQLiDataSource.php';
include_once '../brules/catConfiguracionesObj.php';
include_once '../brules/estadosCuentasObj.php';
include_once '../brules/estadosCuentasDetallesObj.php';
include_once '../brules/documentosObj.php';
$localization = "../brules/KoolControls/KoolCalendar/localization/es.xml";
//establecer la zona horaria
$dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
$dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual

$msjResponse = "";
$msjResponseE = "";
if(isset($_POST['guardar'])){
    $id = $_POST['idAviso'];   
    $valor = $_POST['valorAviso'];
    //$valor = str_replace("'", "\'", $valor);
    //$valor = str_replace('"', '\"', $valor);
    $valor = str_replace('?', '___', $valor);
    $valor = str_replace('\'', '', $valor);
    $confObj = new catConfiguracionesObj();
    $confObj->nombre = "Aviso de privacidad";
    $confObj->idConfiguracion = $id;
    $confObj->valor = $valor;
    $res = $confObj->ActualizarConfiguracion();
    if($res)
    {
    $msjResponse .= "La congifuracion 'Aviso de Privacidad' se actualizo correctamente<br>";
}
//}
//
//if(isset($_POST['guardarCorreoSug'])){
    $id = $_POST['idCorreoSug'];   
    $valor = $_POST['valorCorreoSug'];
    $confObj = new catConfiguracionesObj();
    $confObj->nombre = "Correo para sugerencias";
    $confObj->idConfiguracion = $id;
    $confObj->valor = $valor;
    $res = $confObj->ActualizarConfiguracion();
    if($res)
    {
    $msjResponse .= "La congifuracion 'Correo para sugerencias' se actualizo correctamente<br>";
    }
}
$estadoCuenta = new estadosCuentasObj();
$estadoCuenta->ObtEstadoCuentaByField("numContrato", "111222");
//echo "<pre>";
//print_r($estadoCuenta);
//echo "</pre>";
//echo $estadoCuenta->idEstadoCuenta;
$estadoCuentaDetalle = new estadosCuentasDetallesObj();
$detalles = $estadoCuentaDetalle->ObtTodasEstadosCuentasDetalles("", "", $estadoCuenta->idEstadoCuenta);
//echo "<pre>";
//print_r($detalles);
//echo "</pre>";
$documentosObj = new documentosObj();
$docs = $documentosObj->obtArrayDocumentosPorCliente("111222");
//echo "<pre>";
//print_r($docs);
//echo "</pre>";
$configuracionesObj = new catConfiguracionesObj();
$avisoPrivObj = new catConfiguracionesObj();
$avisoPrivObj->ObtConfiguracionByID(1);
$correoSugObj = new catConfiguracionesObj();
$correoSugObj->ObtConfiguracionByID(2);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Configuraciones</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>    
    <link href="../css/style.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>    
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="../js/functions.js"></script>
        <script type="text/javascript" src="../js/jquery.validate.js"></script>
</head>
<body>
    <?php echo getHeaderMain($_SESSION['myusername'], true);?>
    <?php $menu = getAdminMenu(); ?>

    <section class="section-internas">
        <div class="panel-body">
            <div class="container">
                <div class="row">
                        <div class="colmenu col-md-3 menu_bg">
                        <?php echo getNav($menu); ?>
                    </div>
                    <div class="col-md-9">
                        <h1 class="titulo">Configuraciones</h1>       
                            <!--Mostrar en caso de presionar el boton de guardar-->
                        <?php if ($msjResponse != "") { ?>
                            <div class="new_line alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?php echo $msjResponse; ?>
                            </div>
                        <?php } ?>
                            <div class="new_line"></div>
                                <form role="form" id="formAvisoPriv" name="formAvisoPriv" method="post" action="">
                                <input type="hidden" id="idAviso" name="idAviso" value="<?php echo $avisoPrivObj->idConfiguracion;?>">
                             <div class="media">
                                
                                    <div class="media-left media-middle">
                                        <label for="valorAviso"><?php echo $avisoPrivObj->nombre; ?></label>
                                    </div>
                                    <div class="media-body">
                                        <textarea name="valorAviso" id="valorAviso" rows="6" class="form-control" required=""><?php echo str_replace("___","?",$avisoPrivObj->valor); ?></textarea>
                                    </div>
<!--                                    <div class="media-right media-middle">
                                        <input type="submit" name="guardarAviso" value="Guardar" class="btn btn-primary" role="button"/>
                                    </div>-->
                                
                            </div>
<!--                            </form>
                            <form role="form" id="formCorreoSug" name="formCorreoSug" method="post" action="">-->
                                <input type="hidden" id="idCorreoSug" name="idCorreoSug" value="<?php echo $correoSugObj->idConfiguracion;?>">
                                 <div class="media">                                
                                    <div class="media-left media-middle">
                                       <label for="valorCorreoSug"><?php echo $correoSugObj->nombre; ?></label>
                                    </div>
                                    <div class="media-body">
                                        <input type="text" name="valorCorreoSug" id="valorCorreoSug" class="form-control" required="" value="<?php echo $correoSugObj->valor; ?>">
                                    </div>
<!--                                    <div class="media-right media-middle">
                                      <input type="submit" name="guardarCorreoSug" value="Guardar" class="btn btn-primary" role="button"/>
                                    </div>-->
                                    </div>
                            <div class="new_line"></div>
                            <div class="row">
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <input type="submit" name="guardar" value="Guardar" class="btn btn-primary" role="button"/>
                                </div>
                            </div>
                            </form>
                            
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
