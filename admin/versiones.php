<?php
session_start();
$checkRol = ($_SESSION['idRol']==1 || $_SESSION['idRol']==2 || $_SESSION['idRol']==4) ?true :false;

if($_SESSION['status']!= "ok" || $checkRol!=true)
        header("location: logout.php");

include_once '../common/screenPortions.php';
include '../brules/utilsObj.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
libreriasKool();
$localization = "../brules/KoolControls/KoolCalendar/localization/es.xml";
include_once '../brules/gamaModelosObj.php';
include_once '../brules/versionGeneralesObj.php';
include_once '../brules/versionColoresObj.php';
include_once '../brules/versionPlanesObj.php';
include_once '../brules/versionPreciosObj.php';
include_once '../brules/versionRequisitosObj.php';
include_once '../brules/versionZonasObj.php';
include_once '../brules/versionZonasActivasObj.php';
include_once '../brules/catActualizacionesObj.php';

$gamaModelosObj = new gamaModelosObj();
$versionGeneralesObj = new versionGeneralesObj();
$verColorObj = new versionColoresObj();
$verPlanObj = new versionPlanesObj();
$verPrecioObj = new versionPreciosObj();
$verReqObj = new versionRequisitosObj();
$verZonaObj = new versionZonasObj();
$verZonaActivaObj = new versionZonasActivasObj();

$modelos = $gamaModelosObj->ObtTodosGamaModelos();
$nombreVersionB = '';
$modeloVersionB = '';
$activoVersionB = -1;
if(isset($_GET['buscarModelos'])){
    if(isset($_GET['nombreVersionB'])){
        $nombreVersionB = $_GET['nombreVersionB'];        
    }
    if(isset($_GET['modeloVersionB'])){
        $modeloVersionB = $_GET['modeloVersionB'];        
    } 
    if(isset($_GET['activoVersionB'])){
        $activoVersionB = $_GET['activoVersionB'];        
    }     
}

//Eliminar la version y todo su contenido
if(isset($_POST['eliminarVersion'])){
    $idsGralVers = $_POST['gralVersId'];
    // echo "es :".$idsGralVers.'<br/>';

    //Obtener los ids de los colores por gralVersId
    $verColorObj->idsColoresVersPorIdsVersion($idsGralVers);  
    $idsColoresVers = $verColorObj->idsColoresVers;  
    // echo "es :".$idsColoresVers.'<br/>';

    //Obtener los ids de los planes por gralVersId
    $verPlanObj->idsPlanesVersPorIdsVersion($idsGralVers);
    $idsPlanVers = $verPlanObj->idsPlanVers;  
    // echo "es :".$idsPlanVers.'<br/>';

    //Obtener los ids de los precios por gralVersId
    $verPrecioObj->idsPreciosVersPorIdsVersion($idsGralVers);
    $idsPrecioVers = $verPrecioObj->idsPrecioVers;
    // echo "es :".$idsPrecioVers.'<br/>';

    //Obtener los ids de los requisitos por gralVersId
    $verReqObj->idsReqVersPorIdsVersion($idsGralVers);
    $idsReqVers = $verReqObj->idsReqVers;
    // echo "es :".$idsReqVers.'<br/>';

    //Obtener los ids de las zonas por coloresVersId
    $verZonaObj->idsZonasVersPorIdsColor($idsColoresVers);
    $idsZonaVers = $verZonaObj->idsZonaVers;
    // echo "es :".$idsZonaVers.'<br/>';

    //Obtener los ids de las zonas activar por zonaVersId
    $verZonaActivaObj->idsZonasActivasVersPorIdsZona($idsZonaVers);
    $idsZonaActivaVers = $verZonaActivaObj->idsZonaActivaVers;
    // echo "es :".$idsZonaActivaVers.'<br/>';

    //>>Proceso para eliminar registros escalonados<<<
    //Eliminar zonas activas
    if($idsZonaActivaVers!=""){
        $borrarZA = $verZonaActivaObj->EliminarVersionZonaActiva($idsZonaActivaVers);
        // echo ($borrarZA!=0) ?"Zona(s) activa(s) borradas<br/>":"";
    }

    //Eliminar zonas
    if($idsZonaVers!=""){
        $borrarZ = $verZonaObj->EliminarVersionZona($idsZonaVers);
        // echo ($borrarZ!=0) ?"Zona(s) borrada(s)<br/>":"";
    }

    //Eliminar requisitos
    if($idsReqVers!=""){
        $borrarReq = $verReqObj->EliminarVersionRequisito($idsReqVers);
        // echo ($borrarReq!=0) ?"Requisito(s) borrado(s)<br/>":"";
    }

    //Eliminar precios
    if($idsPrecioVers!=""){
        $borrarPrecio = $verPrecioObj->EliminarVersionPrecio($idsPrecioVers);
        // echo ($borrarPrecio!=0) ?"Precio(s) borrado(s)<br/>":"";
    }

    //Eliminar planes
    if($idsPlanVers!=""){
        $borrarPlan = $verPlanObj->EliminarVersionPlan($idsPlanVers);
        // echo ($borrarPlan!=0) ?"Plan(es) borrado(s)<br/>":"";
    }

    //Eliminar colores
    if($idsColoresVers!=""){
        $borrarColor = $verColorObj->eliminarVersionColor($idsColoresVers);
        // echo ($borrarColor!=0) ?"Color(es) borrado(s)<br/>":"";
    }

    //Eliminar generales
    if($idsGralVers!=""){
        $borrarGrales = $versionGeneralesObj->EliminarVersionGeneral($idsGralVers);
        // echo ($borrarGrales!=0) ?"General(es) borrado(s)<br/>":"";
    }    
}

//Salvar el orden de las versiones
if(isset($_POST['ordenVersiones'])){
     // OrdenarModelos($datos)   
    $idsOrdenados = explode(",", $_POST['arrIdsVersiones']);
    $resOrdenar = $versionGeneralesObj->OrdenarVersiones($idsOrdenados);
    if(count($resOrdenar)>0){
        $actualizacionesObj = new catActualizacionesObj();
        $actualizacionesObj->updActualizacion("version_generales");
    }
}

$resultGrid = $versionGeneralesObj->GetVersionGeneralGrid($modeloVersionB, $nombreVersionB, $activoVersionB);
//Obtener todos los modelos para el ordenamiento
$colVersiones = $versionGeneralesObj->ObtVersionesGenerales();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Versiones</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>    
    <link href="../css/style.css" rel="stylesheet" type="text/css" />
    <link href="../js/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css" />

    <!-- <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"> -->
    <link href="../css/alertify.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="../css/alertify.default.css" rel="stylesheet" type="text/css" /> -->
    <link rel="stylesheet" href="../css/themes/semantic.min.css"/>
    <script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>    
    <script type="text/javascript" src="../js/fancybox/jquery.fancybox.pack.js"></script>

    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="../js/alertify.js"></script>
    <script>var tieneAlertify = true;</script>
    <script type="text/javascript" src="../js/functions.js"></script>

    <script>
        $(function() {
            var icons = {
              header: "ui-icon-arrowthick-2-n-s",              
            };
          $( "#sortable" ).sortable({icons: icons,});
          $( "#sortable" ).disableSelection();
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
                        <h1 class="titulo">Versiones</h1>  
                        <div class="alert alert-info" role="alert">
                          <b>Nota</b>: Recuerde que la versi&oacute;n puede ser diferente de los modelos
                        </div>     
                        <div class="filtro_estatus">
                            <form role="form" id="formFiltroCtasPorCobrar" name="formFiltroComunicados" method="get" action="">
                                 <div class="row">
                                    <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                        <label for="fechaDel">Modelo</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <select class="form-control" name="modeloVersionB" id="modeloVersionB">
                                            <option value="">--Todos--</option>
                                            <?php
                                            foreach ($modelos as $modelo) {
                                                $selectedAnio = '';
                                                if($modeloVersionB == $modelo->gModeloId)
                                                {
                                                    $selectedAnio = "selected";
                                                }
                                                echo '<option value="'.$modelo->gModeloId.'" '.$selectedAnio.'>'.$modelo->modelo.'-'.$modelo->anio.'</option>';
                                            }
                                             ?>
                                        </select>
                                    </div>
                                    <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                        <label for="fechaDel">Version</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <input class="form-control" type="text" id="nombreVersionB" name="nombreVersionB" value="<?php echo $nombreVersionB ?>">
                                    </div>
                                    <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                        <label for="fechaDel">Activo</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <select class="form-control" name="activoVersionB" id="activoVersionB">
                                            <option value="-1" <?php echo ($activoVersionB==-1)?"selected":"" ?>>Todos</option>
                                            <option value="0" <?php echo ($activoVersionB==0)?"selected":"" ?>>No</option>
                                            <option value="1" <?php echo ($activoVersionB==1)?"selected":"" ?>>Si</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-2 col-sm-2 col-xs-2">
                                        <input type="submit" name="buscarModelos" value="Buscar" class="btn btn-primary" role="button"/>                                        
                                </div>
                            </div>
                            </form>
                        </div>      
                        
                        <?php if($_SESSION['idRol']==1 || $_SESSION['idRol']==2){ ?>
                        <div class="row">
                            <div class="col-md-offset-8 col-md-1">
                                <a href="version.php" class="btn btn-success" role="button">Nuevo</a>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-2">
                                <a href="#fancyOrdenModelo" class="btn btn-success" id="btnAgregarOrdenModelo" role="button">Ordenar Versiones</a>
                            </div>
                        </div>
                        <?php } ?>
                        <br/>
                        <div>
                            <form name="grids" method="post">
                                <?php
                                echo $koolajax->Render();
                                echo '<div id="contsGrid">';
                                if($resultGrid != null)
                                {
                                    echo $resultGrid->Render();
                                }                                
                                echo '</div>';
                                ?>
                            </form>
                        </div>

                        <!--Ordenamiento de versiones-->
                        <div id="fancyOrdenModelo" style="display:none;width:500px;height:200px;">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <h4><b>Ordena las versiones como se quieren mostrar en la app</b></h4>
                                </div>
                            </div>
                            <br/>

                            <div class="lista_versiones_ordenar">
                                <ul id="sortable">                                    
                                    <?php 
                                        $modeloAuto = "";
                                        foreach($colVersiones as $elem){
                                            //obtener el nombre del modelo
                                            if($elem->gModeloId!="" && $elem->gModeloId>0){
                                                $gamaModelosObj->ObtDatosGModeloPorId($elem->gModeloId);                                                
                                                $modeloAuto = $gamaModelosObj->modelo;
                                            }
                                    ?>
                                        <!-- img src="<?php echo $elem->imagen; ?>" style="width:72px"> -->
                                        <li class="ui-state-default" id="id_ver_<?php echo $elem->gralVersId;?>"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php echo $modeloAuto." - ".$elem->version; ?> </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            
                            <br/>    
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-4">                                    
                                    <a href="javascript:void(0);" onclick="salvarOrdenVersion();" class="btn btn-primary" role="button">Salvar orden</a> 
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <input type="button" value="Cancelar" class="btn btn-primary" role="button" onclick="parent.$.fancybox.close();" >
                                </div>
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

    <?php 
    //Mostrar mensaje al haber salvado correctamente los planes - por cada modelo seleccionado
    if(isset($borrarGrales) && $borrarGrales>0){
        $actualizacionesObj = new catActualizacionesObj();
        $actualizacionesObj->updActualizacion("version_generales");
        echo '<script type="text/javascript">alertify.success("Versi&oacute;n borrada correctamente");</script>';
    }
    ?>

    <link rel="stylesheet" href="../css/jquery-ui.css" />
</body>
</html>
