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
include_once '../brules/versionGeneralesObj.php';
include_once '../brules/versionColoresObj.php';
include_once '../brules/versionPlanesObj.php';
include_once '../brules/versionPreciosObj.php';
include_once '../brules/versionRequisitosObj.php';
include_once '../brules/versionZonasObj.php';
include_once '../brules/versionZonasActivasObj.php';
include_once '../brules/catActualizacionesObj.php';

$gMObj = new gamaModelosObj();
$verGralObj = new versionGeneralesObj();
$verColorObj = new versionColoresObj();
$verPlanObj = new versionPlanesObj();
$verPrecioObj = new versionPreciosObj();
$verReqObj = new versionRequisitosObj();
$verZonaObj = new versionZonasObj();
$verZonaActivaObj = new versionZonasActivasObj();
$actualizacionesObj = new catActualizacionesObj();          

$nombreModeloB = '';
$anioModeloB = '';
$activoModeloB = '';

$msgAlert = "";
if(isset($_POST['eliminarModelo'])){
    //obtener ids de las versiones por el id del modelo
    $verGralObj->idsVersGeneralPorGModeloId($_POST['gModeloId']);
    $idsGralVers = $verGralObj->idsGralVers;
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
        if($borrarZA!=0){ 
            $actualizacionesObj->updActualizacion("version_zonasactivas");  
            $msgAlert .= "Zona(s) activa(s) borradas<br/>";
        }
    }

    //Eliminar zonas
    if($idsZonaVers!=""){
        $borrarZ = $verZonaObj->EliminarVersionZona($idsZonaVers);
        if($borrarZ!=0){
            $actualizacionesObj->updActualizacion("version_zonas");    
            $msgAlert .= "Zona(s) borrada(s)<br/>";
        }
    }

    //Eliminar requisitos
    if($idsReqVers!=""){
        $borrarReq = $verReqObj->EliminarVersionRequisito($idsReqVers);        
        if($borrarReq!=0){
           $actualizacionesObj->updActualizacion("version_requisitos"); 
           $msgAlert .= "Requisito(s) borrado(s)<br/>";
        }
    }

    //Eliminar precios
    if($idsPrecioVers!=""){
        $borrarPrecio = $verPrecioObj->EliminarVersionPrecio($idsPrecioVers);        
        if($borrarPrecio!=0){
           $actualizacionesObj->updActualizacion("version_precios");
           $msgAlert .= "Precio(s) borrado(s)<br/>";
        }
    }

    //Eliminar planes
    if($idsPlanVers!=""){
        $borrarPlan = $verPlanObj->EliminarVersionPlan($idsPlanVers);        
        if($borrarPlan!=0){
           $actualizacionesObj->updActualizacion("version_planes"); 
           $msgAlert .= "Plan(es) borrado(s)<br/>";
        }
    }

    //Eliminar colores
    if($idsColoresVers!=""){
        $borrarColor = $verColorObj->eliminarVersionColor($idsColoresVers);
        
        if($borrarColor!=0){
           $actualizacionesObj->updActualizacion("version_colores");    
           $msgAlert .= "Color(es) borrado(s)<br/>";
        }
    }

    //Eliminar generales
    if($idsGralVers!=""){
        $borrarGrales = $verGralObj->EliminarVersionGeneral($idsGralVers);        
        if($borrarGrales!=0){
          $actualizacionesObj->updActualizacion("version_generales");
          $msgAlert .= "General(es) borrado(s)<br/>";
        }
    }

    //Eliminar gama modelos
    $borrarGM = $gMObj->EliminarGamaModelo($_POST['gModeloId']);    
    if($borrarGM!=0){
        $actualizacionesObj->updActualizacion("gama_modelos");
        $msgAlert .= "Gama Modelo(s) borrado(s)<br/>";
    }

    //Mostrar mensaje        
    echo '<script type="text/javascript">setTimeout(function(){ msgAlertify("'.$msgAlert.'"); }, 1500);</script>';
}

//$resultGrid = $gMObj->GetGamaModelosGrid();
if(isset($_GET['buscarModelos'])){
    if(isset($_GET['nombreModeloB'])){
        $nombreModeloB = $_GET['nombreModeloB'];        
    }
    if(isset($_GET['anioModeloB'])){
        $anioModeloB = $_GET['anioModeloB'];        
    } 
    if(isset($_GET['activoModeloB'])){
        $activoModeloB = $_GET['activoModeloB'];        
    }     
}

//Salvar el orden de los modelos
if(isset($_POST['ordenModelos'])){
     // OrdenarModelos($datos)   
    $idsOrdenados = explode(",", $_POST['arrIdsModelos']);
    $resOrdenar = $gMObj->OrdenarModelos($idsOrdenados);
    if(count($resOrdenar)>0){
        $actualizacionesObj = new catActualizacionesObj();
        $actualizacionesObj->updActualizacion("gama_modelos");
    }
}


//Obtener todos los modelos en el grid
$resultGrid = $gMObj->GetGamaModelosGrid($nombreModeloB, $anioModeloB, $activoModeloB); 
//Obtener todos los modelos para el ordenamiento
$colModelos = $gMObj->ObtTodosGamaModelos();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Gama de modelos</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>    
    <link href="../css/style.css" rel="stylesheet" type="text/css" />
    <link href="../js/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css" />

    <!-- <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"> -->
    <link href="../css/alertify.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="../css/alertify.default.css" rel="stylesheet" type="text/css" /> -->
    <link rel="stylesheet" href="../css/themes/semantic.min.css"/>
    <link rel="stylesheet" type="text/css" href="../css/interact.css"/>
    <script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>  
    <script type="text/javascript" src="../js/fancybox/jquery.fancybox.pack.js"></script>

    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script src="../js/interact.js"></script>
    <script type="text/javascript" src="../js/alertify.js"></script>
    <script>var tieneAlertify = true;</script>
    <script type="text/javascript" src="../js/functions.js"></script>

    <script>
        $(function() {
          $( "#sortable" ).sortable();
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
                        <h1 class="titulo">Gama de Modelos</h1>       
                        <div class="filtro_estatus">
                            <form role="form" id="formFiltroCtasPorCobrar" name="formFiltroComunicados" method="get" action="">
                                 <div class="row">
                                    <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                        <label for="fechaDel">Modelo</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <input class="form-control" type="text" id="nombreModeloB" name="nombreModeloB" value="<?php echo $nombreModeloB ?>">
                                    </div>
                                    <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                        <label for="fechaDel">A&ntilde;o</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <select class="form-control" name="anioModeloB" id="anioModeloB">
                                            <option value="">Todos</option>
                                            <?php
                                            $arrAnios = array("2015", "2016", "2017", "2018", "2019", "2020", "2021", "2022", "2023", "2024", "2025", "2026", "2027", "2028",);
                                            foreach ($arrAnios as $anio) {
                                                $selectedAnio = '';
                                                if($anioModeloB == $anio)
                                                {
                                                    $selectedAnio = "selected";
                                                }
                                                echo '<option value="'.$anio.'" '.$selectedAnio.'>'.$anio.'</option>';
                                            }
                                             ?>
                                        </select>
                                    </div>
                                    <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                        <label for="fechaDel">Activo</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <select class="form-control" name="activoModeloB" id="activoModeloB">
                                            <option value="-1" <?php echo ($activoModeloB==-1)?"selected":"" ?>>Todos</option>
                                            <option value="0" <?php echo ($activoModeloB==0)?"selected":"" ?>>No</option>
                                            <option value="1" <?php echo ($activoModeloB==1)?"selected":"" ?>>Si</option>
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
                        
                        <div class="row">
                            <div class="col-md-offset-8 col-md-1">
                                <a href="regGamaModelo.php" class="btn btn-success" role="button">Nuevo</a>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-2">
                                <a href="#fancyOrdenModelo" class="btn btn-success" id="btnAgregarOrdenModelo" role="button">Ordenar Modelos</a>
                            </div>
                        </div>
                        <br/>
                        <div style="width:10%;">
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
                        

                        <!--Ordenamiento de gama de modelos-->
                        <div id="fancyOrdenModelo" style="display:none;width:500px;height:200px;">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <h4><b>Ordena los modelos como se quieren mostrar en la app</b></h4>
                                </div>
                            </div>
                            <br/>

                            <div class="lista_modelos_ordenar">
                                <ul id="sortable">                                    
                                    <?php foreach($colModelos as $elem){ ?>
                                        <!-- img src="<?php echo $elem->imagen; ?>" style="width:72px"> -->
                                        <li class="ui-state-default" id="id_mod_<?php echo $elem->gModeloId;?>"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php echo $elem->modelo; ?> </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            
                            <br/>    
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-4">                                    
                                    <a href="javascript:void(0);" onclick="salvarOrdenModelo();" class="btn btn-primary" role="button">Salvar orden</a> 
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <input type="button" value="Cancelar" class="btn btn-primary" role="button" onclick="parent.$.fancybox.close();" >
                                </div>
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
    
    <link rel="stylesheet" href="../css/jquery-ui.css" />
    
</body>
</html>
