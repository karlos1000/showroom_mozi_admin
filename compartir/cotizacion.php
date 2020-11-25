<?php
$dirname = dirname(__DIR__);

include_once $dirname.'/common/screenPortions.php';
include $dirname.'/brules/utilsObj.php';
require_once $dirname.'/brules/KoolControls/KoolAjax/koolajax.php';
libreriasKool();
$localization = "../brules/KoolControls/KoolCalendar/localization/es.xml";
include_once $dirname.'/brules/versionGeneralesObj.php';
include $dirname.'/common/config.php';

// $gralVersId = (isset($_GET['gralVersId'])) ?$_GET['gralVersId']:0;
// $versionGeneralesObj = new versionGeneralesObj();
// $gamaModelosObj = new gamaModelosObj();

// $versionGeneralesObj->versionGeneralPorId($gralVersId);
// $imagen = str_replace("..", "", $versionGeneralesObj->imagen);
// $versionNombre = $versionGeneralesObj->version;
// $precioDesde = "$ ".number_format($versionGeneralesObj->precioInicial,2);
// $caracteristicas = $versionGeneralesObj->caracteristicas;

// $gamaModelosObj->ObtDatosGModeloPorId($gralVersId);
// $modelo = $gamaModelosObj->modelo;
// $anio = $gamaModelosObj->anio;

$modelo = $_GET['modelo'];


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title></title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>    
    <link href="../css/style.css" rel="stylesheet" type="text/css" />

    <!-- <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"> -->
    <link href="../css/alertify.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="../css/alertify.default.css" rel="stylesheet" type="text/css" /> -->
    <link rel="stylesheet" href="../css/themes/semantic.min.css"/>
    <script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>    
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="../js/alertify.js"></script>
    <script>var tieneAlertify = true;</script>
    <script type="text/javascript" src="../js/functions.js"></script>
</head>
<body>    
    <section class="section-internas">
        <div class="panel-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-9">
                    <?php echo $modelo; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- <footer>
        <div class="panel-footer">
            <?php echo getPiePag(true); ?>
        </div>
    </footer> -->
</body>
</html>
