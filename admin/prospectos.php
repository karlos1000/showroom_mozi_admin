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
include_once '../brules/catProspectosObj.php';
include_once '../brules/usuariosObj.php';
include_once '../brules/catAgenciasObj.php';

//establecer la zona horaria
$timeZone = obtDateTimeZone();
$prosObj = new catProspectosObj();
$usuariosObj = new usuariosObj();
$agenciaObj = new catAgenciasObj();
$vendedores = $usuariosObj->obtUsuariosByIdRol(3);
$colAgencias = $agenciaObj->ObtTodasAgencias(false);


$nombreProspecB = '';
$vendedorB = '';
$activoVersionB = -1;
$agenciaId = 0;
$filter_fechaDel = diasPrevPos(30, $timeZone->fecha, "prev"); //Solo muestra los de 1 mes atras
$filter_fechaAl = $timeZone->fechaF2;
if(isset($_GET['buscarModelos'])){
    if(isset($_GET['nombreProspecB'])){
        $nombreProspecB = $_GET['nombreProspecB'];
    }
    if(isset($_GET['vendedorB'])){
        $vendedorB = $_GET['vendedorB'];        
    }
    if(isset($_GET['agenciaId'])){
        $agenciaId = $_GET['agenciaId'];        
    }

    $filter_fechaDel = $_GET['filter_fechaDel'];
    $filter_fechaAl = $_GET['filter_fechaAl'];    
}
// Eliminar prospecto 
// if(isset($_POST['eliminarProspecto'])){
//     $respDelProsp = $prosObj->EliminarProspectoPorId($_POST['prospectoId']); //Borrar prospecto por su id    
// }
$result = $prosObj->GetCatProspectoGrid($vendedorB, $nombreProspecB, $agenciaId, conversionFechas($filter_fechaDel), conversionFechas($filter_fechaAl));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Prospectos</title>
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
                        <h1 class="titulo">Prospectos</h1>       
                        <div class="filtro_estatus">
                            <form role="form" id="formFiltroCtasPorCobrar" name="formFiltroComunicados" method="get" action="">
                               <div class="row">
                                      <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                        <label for="filter_fechaDel">Del:</label>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <input type="text" id="filter_fechaDel" name="filter_fechaDel" class="form-control" value="<?php echo $filter_fechaDel;?>" style="width:60%;display:inline-block;" readonly>
                                    </div>

                                    <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                        <label for="filter_fechaAl">Al:</label>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <input type="text" id="filter_fechaAl" name="filter_fechaAl" class="form-control" value="<?php echo $filter_fechaAl;?>" style="width:60%;display:inline-block;" readonly>
                                    </div>
                                </div>                                   
                                 <div class="row">
                                    <?php if($_SESSION['idRol']==1){ ?>
                                    <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                        <label for="agenciaId">Agencia:</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <select class="form-control" name="agenciaId" id="agenciaId">
                                            <option value="0">--Todos--</option>
                                            <?php
                                            foreach ($colAgencias as $elemAgencia) {
                                                $selectedAgencia = '';
                                                if($agenciaId == $elemAgencia->agenciaId)
                                                {
                                                    $selectedAgencia = "selected";
                                                }
                                                echo '<option value="'.$elemAgencia->agenciaId.'" '.$selectedAgencia.'>'.$elemAgencia->nombre.'</option>';
                                            }
                                             ?>
                                        </select>
                                    </div>
                                    <?php } ?>

                                    <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                        <label for="vendedorB">Vendedor:</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <select class="form-control" name="vendedorB" id="vendedorB">
                                            <option value="">Todos</option>
                                            <?php
                                            foreach ($vendedores as $vendedor) {
                                                $selectedVend = '';
                                                if($vendedorB == $vendedor->idUsuario)
                                                {
                                                    $selectedVend = "selected";
                                                }
                                                echo '<option value="'.$vendedor->idUsuario.'" '.$selectedVend.'>'.$vendedor->nombre.'</option>';
                                            }
                                             ?>
                                        </select>
                                    </div>
                                    <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                        <label for="nombreProspecB">Prospecto:</label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <input class="form-control" type="text" id="nombreProspecB" name="nombreProspecB" value="<?php echo $nombreProspecB ?>">
                                    </div>
                                </div>
                                <div class="row">                                  
                                  <div class="col-md-2 col-sm-2 col-xs-2">                                    
                                    <input type="submit" name="buscarModelos" value="Buscar" class="btn btn-primary" role="button"/>                                        
                                  </div>
                                  <div class="col-md-2 col-sm-2 col-xs-2">
                                    <input type="button" id="limpiarFiltrosProsp" value="Limpiar" class="btn btn-primary" role="button"/>  
                                  </div>                                
                            </div>
                            </form>
                        </div> 
                        <br/>       
                        
                        <div>
                            <div class="row">
                                <div class="col-md-5 col-sm-5 col-xs-5">
                                    <div class="infoTotalProsp">Total de resultados encontrados: <span id="totalProspectos">0</span></div>
                                </div>
                                <div class="col-md-offset-5 col-md-2 col-sm-2 col-xs-2">
                                    <input type="button" id="exportarProsp" value="Exportar" class="btn btn-primary" role="button"/>  
                                </div>
                            </div>
                            <br/>
                            <form name="grids" method="post">
                                <?php
                                    echo $koolajax->Render();
                                    echo '<div id="contsGrid">';
                                    if($result != null)
                                    {
                                        echo $result->Render();
                                    }                                
                                    echo '</div>';
                                ?>
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
    <?php 
    // if(isset($respDelProsp) && $respDelProsp>0){
    //     echo '<script type="text/javascript">alertify.success("Prospecto borrado correctamente");</script>';
    // }    
    ?>

    <link rel="stylesheet" href="../css/jquery-ui.css">    
    <link rel="stylesheet" href="../css/jquery.timepicker.min.css">
    <script src="../js/jquery.timepicker.min.js"></script>
    <script src="../js/spanish_datapicker.js"></script>
    <script>    
    $( document ).ready(function() {        
         var infoTotalProsp = $("#catProspectoGrid .kgrInfo").text();                  
         var infoTotalProspExp = infoTotalProsp.split("total de");
         infoTotalProspExp = infoTotalProspExp[1].trim();
         infoTotalProspExp = infoTotalProspExp.split(".");
         $("#totalProspectos").html(infoTotalProspExp[0]);         
    });        
    </script>
</body>
</html>
