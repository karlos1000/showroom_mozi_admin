<?php
$dirname = dirname(__DIR__);

include_once $dirname.'/common/screenPortions.php';
include_once $dirname.'/common/config.php';
include_once $dirname.'/brules/KoolControls/KoolGrid/koolgrid.php';
require_once $dirname.'/brules/KoolControls/KoolAjax/koolajax.php';
require_once $dirname.'/brules/KoolControls/KoolCalendar/koolcalendar.php';
include_once $dirname.'/brules/KoolControls/KoolGrid/ext/datasources/MySQLiDataSource.php';
include_once $dirname.'/brules/versionGeneralesObj.php';
include_once $dirname.'/brules/gamaModelosObj.php';
include_once $dirname.'/brules/versionPreciosObj.php';
include_once $dirname.'/brules/catConceptoPreciosObj.php';
include_once $dirname.'/brules/catTransmisionesObj.php';
include_once $dirname.'/brules/utilsObj.php';
$localization = $dirname."/brules/KoolControls/KoolCalendar/localization/es.xml";
//establecer la zona horaria
$dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
$dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual

$urlSite = $siteURL."upload/comunicados/";
$urlShare = "";
        
$msjResponse = "";
$msjResponseE = "";
$gralVersId = (isset($_GET['gralVersId']))?$_GET['gralVersId']:0;
$versionGeneralesObj = new versionGeneralesObj();
$gamaModelosObj = new gamaModelosObj();
$versionPreciosObj =  new versionPreciosObj();


if($gralVersId > 0)
{
    $precios = $versionPreciosObj->ObtVersionesPrecios(false, $gralVersId, 1);
    $versionGeneralesObj->versionGeneralPorId($gralVersId);
    $gamaModelosObj->ObtDatosGModeloPorId($versionGeneralesObj->gModeloId);
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Planes <?php echo $gamaModelosObj->modelo."-".$gamaModelosObj->anio. " ".$versionGeneralesObj->version ?></title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>    
    <link href="../css/style.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>    
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="../js/functions.js"></script>
</head>
<body>
   
    <section class="section-internas">
        <div class="panel-body">
            <div class="container">
                <?php if($gralVersId>0 && $versionGeneralesObj->version!=""){ ?>
                <div class="row">
                    <div class="col-md-9">
                        <h1 class="titulo">Precios <?php echo $gamaModelosObj->modelo."-".$gamaModelosObj->anio. " ".$versionGeneralesObj->version ?></h1>       
                        
                        <?php
                        foreach ($precios as $precio) {
                            $catTransmisionesObj = new catTransmisionesObj();
                            $catTransmisionesObj->ObtDatosTransmisionPorId($precio->transmisionId);
                            echo '<h3>'.$precio->conceptoPrecio.' - '.$catTransmisionesObj->transmision.'</h3>';
                            echo "$ ".number_format($precio->precio,2).'<hr>';
                        }

                         ?>                    
                    </div>                    
                </div>
                <?php } ?>
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
