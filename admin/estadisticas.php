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
include_once '../brules/estadisticasAppObj.php';
include_once '../brules/usuariosObj.php';
include_once '../brules/catAgenciasObj.php';

//establecer la zona horaria
$timeZone = obtDateTimeZone();
$estadisticaAppObj = new estadisticasAppObj();
$usuariosObj = new usuariosObj();

//obt col de agentes de ventas
$colAgentes = $estadisticaAppObj->obtEstAgentes();
//obt col de modelos
$colModelos = $estadisticaAppObj->obtEstModelos();
// col opciones
$colOpciones = (object)array("1"=>"Prospectos capturados", "2"=>"Inicios de sesi&oacute;n", "3"=>"Entrada a mesa de trabajo", 
                     "4"=>"Entrada a calculadora", "5"=>"Guardar cotizaci&oacute;n", "6"=>"Env&iacute;o de caracter&iacute;sticas");

$fil_opcion = 0;
$fil_agente = 0;
$fil_modelo = 0;
// $fil_tipo = 0;

$filter_fechaDel = diasPrevPos(30, $timeZone->fecha, "prev"); //Solo muestra los de 1 mes atras
$filter_fechaAl = $timeZone->fechaF2;
if(isset($_GET['buscarEstadistica'])){
    if(isset($_GET['fil_opcion'])){
        $fil_opcion = $_GET['fil_opcion'];
    }
    if(isset($_GET['fil_agente'])){
        $fil_agente = $_GET['fil_agente'];
    }
    if(isset($_GET['fil_modelo'])){
        $fil_modelo = $_GET['fil_modelo'];        
    }
    // if(isset($_GET['fil_tipo'])){
    //     $fil_tipo = $_GET['fil_tipo'];        
    // }

    $filter_fechaDel = $_GET['filter_fechaDel'];
    $filter_fechaAl = $_GET['filter_fechaAl'];    
}


$result = $estadisticaAppObj->GetEstadisticasGrid(conversionFechas($filter_fechaDel), conversionFechas($filter_fechaAl), $fil_opcion, $fil_agente, $fil_modelo);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Estad&iacute;sticas App</title>
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
    <script type="text/javascript" src="../js/accounting.min.js"></script>
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
                        <h1 class="titulo">Estad&iacute;stica</h1>
                        <div class="filtro_estatus">
                            <form role="form" id="formBuscarEstadistica" name="formBuscarEstadistica" method="get" action="">
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
                                    
                                    
                                    <div class="col-md-offset-1 col-md-1 col-sm-1 col-xs-1">
                                       <input type="submit" name="buscarEstadistica" value="Buscar" class="btn btn-primary" role="button"/>                                        
                                    </div>
                                    <div class="col-md-1 col-sm-1 col-xs-1">
                                      <input type="button" id="limpiarFiltrosEstadistica" value="Limpiar" class="btn btn-primary" role="button"/>  
                                    </div>
                                </div>                                   
                                <div class="row">
                                    <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                        <label for="fil_opcion">Opciones:</label>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <select class="form-control" name="fil_opcion" id="fil_opcion">
                                            <option value="0">--Seleccionar--</option>
                                            <?php
                                            foreach ($colOpciones as $key => $elem) {
                                                $selectOpcion = '';
                                                if($fil_opcion == $key){
                                                    $selectOpcion = "selected";
                                                }
                                                echo '<option value="'.$key.'" '.$selectOpcion.'>'.$elem.'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                    
                                <!-- <div class="row" id="sel_tipo">
                                    <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                        <label for="fil_tipo">Tipo:</label>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <select class="form-control" name="fil_tipo" id="fil_tipo">
                                            <option value="0">--Seleccionar--</option>
                                            <option value="1" ?php echo ($fil_tipo==1)?"selected":"" ?>>Global</option>
                                            <option value="2" ?php echo ($fil_tipo==2)?"selected":"" ?>>Por agente</option>
                                        </select>
                                    </div>
                                </div> -->

                                <div class="row" id="sel_agente" style="display:none;">
                                    <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                        <label for="fil_agente">Agente:</label>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <select class="form-control" name="fil_agente" id="fil_agente">
                                            <option value="0">--Todos--</option>
                                            <?php
                                            foreach ($colAgentes as $elem) {
                                                $selectAgente = '';
                                                if($fil_agente == $elem->usuarioId){
                                                    $selectAgente = "selected";
                                                }
                                                echo '<option value="'.$elem->usuarioId.'" '.$selectAgente.'>'.ucwords($elem->nombre).'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row" id="sel_modelo" style="display:none;">
                                    <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                        <label for="fil_modelo">Modelo:</label>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <select class="form-control" name="fil_modelo" id="fil_modelo">
                                            <option value="0">--Todos--</option>
                                            <?php
                                            foreach ($colModelos as $elem) {
                                                $selectModelo = '';
                                                if($fil_modelo == $elem->idModelo){
                                                    $selectModelo = "selected";
                                                }
                                                echo '<option value="'.$elem->idModelo.'" '.$selectModelo.'>'.ucwords($elem->modelo).'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                            </form>
                        </div>                         
                        
                        <div>
                            <div class="row">
                                <!-- <div class="col-md-5 col-sm-5 col-xs-5">
                                    <div class="infoTotalProsp">Total de resultados encontrados: <span id="totalProspectos">0</span></div>
                                </div> -->
                                <div class="col-md-offset-10 col-md-2 col-sm-2 col-xs-2">
                                    <input type="button" id="exportarEstadistica" value="Exportar" class="btn btn-primary" role="button"/>
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
    
    <link rel="stylesheet" href="../css/jquery-ui.css">    
    <link rel="stylesheet" href="../css/jquery.timepicker.min.css">
    <script src="../js/jquery.timepicker.min.js"></script>
    <script src="../js/spanish_datapicker.js"></script>
    
    <input type="hidden" id="hfil_opcion" value="<?php echo $fil_opcion;?>">
    <input type="hidden" id="hfil_agente" value="<?php echo $fil_agente;?>">
    <input type="hidden" id="hfil_modelo" value="<?php echo $fil_modelo;?>">
    
    
</body>
</html>
