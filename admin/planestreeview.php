<?php
session_start();
$checkRol = ($_SESSION['idRol']==1 || $_SESSION['idRol']==2) ?true :false;

if($_SESSION['status']!= "ok" || $checkRol!=true)
        header("location: logout.php");

include_once '../common/screenPortions.php';
include '../brules/utilsObj.php';
// require_once '../brules/KoolControls/KoolAjax/koolajax.php';
// libreriasKool();
// $localization = "../brules/KoolControls/KoolCalendar/localization/es.xml";
include_once '../brules/KoolControls/KoolGrid/koolgrid.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
require_once '../brules/KoolControls/KoolCalendar/koolcalendar.php';
include_once '../brules/KoolControls/KoolGrid/ext/datasources/MySQLiDataSource.php';
$localization = "../brules/KoolControls/KoolCalendar/localization/es.xml";

include_once '../brules/gamaModelosObj.php';
include_once '../brules/versionGeneralesObj.php';
include_once '../brules/versionPlanesObj.php';
include_once $dirname.'/brules/catActualizacionesObj.php';

$gMObj = new gamaModelosObj();
$verGralObj = new versionGeneralesObj();
$versionPlanesObj = new versionPlanesObj();

$colModelos = $gMObj->ObtTodosGamaModelos(true, 1);
$colVersionesModelo = $verGralObj->ObtVersionesGenerales(true);
$colVerPlanes = $versionPlanesObj->ObtVersionesPlanes(true, "", 1, 1);

 $arrTotalSalvados = array();
if(isset($_POST['arrIdsGralVersion'])){    
    //Salvar planes para las versiones seleccionadas   
    $arrIdsPlanes = explode(",", $_POST['arrIdsPlan']);
    $arrIdsGralVersion = explode(",", $_POST['arrIdsGralVersion']);  

    //Agregar planes    
    foreach ($arrIdsPlanes as $id) {
        $versPlanObj = new versionPlanesObj();        
        $versPlanObj->EliminarVersionPlanPorIdPadre($id); //Borrar todos los planes anteriores por el id padre    
        
        //Insetar pla/es por cada version de auto
        foreach ($arrIdsGralVersion as $gralVersId) {
            $versionPlanObj = new versionPlanesObj();
            $versionPlanObj->versionPlanPorId($id);

            //Setear datos
            $versionPlanObj->gralVersId = $gralVersId;
            $versionPlanObj->planId = $versionPlanObj->planId;
            $versionPlanObj->caracteristicas = $versionPlanObj->caracteristicas;        
            $versionPlanObj->preconfigurado = 0;
            $versionPlanObj->planVersIdPadre = $versionPlanObj->planVersId;

            //Insertar  
            $versionPlanObj->InsertVersionPlan();
            if($versionPlanObj->planVersId>0){
                $arrTotalSalvados[] = $versionPlanObj->planVersId;
            }
        }
    }
}

//Metodos koolajax
// $planVersIdPadre = 0;
// obtIdsPlanesVers(trim($planVersIdPadre));
// $koolajax->enableFunction("obtIdsPlanesVers");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Planes - Modelo</title>
    
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>    
    <link href="../css/style.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="../js/checktree/css/checkTree.css"/>

    <!-- <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"> -->
    <link href="../css/alertify.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="../css/alertify.default.css" rel="stylesheet" type="text/css" /> -->
    <link rel="stylesheet" href="../css/themes/semantic.min.css"/>
    <script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
        
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>    
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="../js/alertify.js"></script>
    <script>var tieneAlertify = true;</script>
    
    <link rel="stylesheet" href="../js/checktree/css/checkTree.css"/>  
    <script type="text/javascript" src="../js/checktree/js/checkTree.js"></script>

    <script type="text/javascript" src="../js/functions.js"></script>
    <script src="../js/popupoverlay/js/jquery.popupoverlay.js"></script>

    <script type="text/javascript">
        $( document ).ready(function() {        
        // Initialize popup
        $('#my_popup').popup();
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
                        <h1 class="titulo">Planes - Modelos</h1>
                         
                        <div class="row">
                          <div class="col-md-4 col-sm-4 col-xs-4">
                            <label>Seleccionar Plan:</label>
                          </div>                                  
                          <div class="col-md-4 col-sm-4 col-xs-4">
                            <?php if(count($colVerPlanes)>0){ ?>                            
                              <select class="form-control" name="id_plan" id="id_plan">                              
                                <option value="">* --Seleccionar--</option>
                                <?php foreach ($colVerPlanes as $elem) { ?>
                                  <option value="<?php echo $elem->planVersId;?>"><?php echo $elem->conceptoPlan;?></option>
                                <?php } ?>
                              </select>
                          <?php } ?>
                          </div>
                          
                          <span id="cont_verDetallePopup"></span>
                        </div>  

                        <!-- caracteristicas -->
                        <div id="cont_caracteristicas"></div>                        

                                                              
                        <br/>
                        <hr>
                        
                        <br/>
                        <div class="cont_modelos_versiones" style="display:none;">
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Seleccionar Modelos - Versiones</h4>    
                                </div>
                                <div class="col-md-7 text-right">                                    
                                    <input type="button" role="button" class="btn btn-primary" onclick="salvarPlanesVersionesModelos();" value="Salvar planes-modelos">
                                </div>
                            </div>
                            
                            <!--<div id="tree-container"></div>-->
                            
                            <?php if(count($colModelos)>0) { ?>
                              <ul class="checktree">
                                <?php foreach($colModelos as $elem){ ?>
                                       <li><input id="modelo_<?php echo $elem->gModeloId;?>" type="checkbox" /><label for="modelo_<?php echo $elem->gModeloId;?>"><?php echo $elem->modelo;?></label>
                                       <?php     
                                          if(count($colVersionesModelo)>0){ ?>
                                            <ul>                                              
                                              <?php                                               
                                              foreach($colVersionesModelo as $elemV){                               
                                                 if($elem->gModeloId==$elemV->gModeloId){ ?>
                                                    <li><input id="vers_<?php echo $elemV->gralVersId;?>" type="checkbox" value="<?php echo $elemV->gralVersId;?>" /><label for="vers_<?php echo $elemV->gralVersId;?>"><?php echo $elemV->version;?></label></li>
                                                   <?php } 
                                              } ?>
                                            </ul>
                                    <?php } ?>
                                       </li>
                                <?php } ?>          
                              </ul>  
                            <?php } ?>                            
                        </div>
                        <br/><br/><br/>
                        

                        <!-- Mostrar contenido completo -->
                        <div id="my_popup" style="background:#fff;padding:30px;">
                          <div id="contenidodin_popup"></div>
                          <button class="my_popup_close btn btn-success">Cerrar</button>
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
    
        
    <script>
        var mockData = [];

        <?php if(count($colModelos)>0) { ?>
            var arrColModelos = [];
            <?php 
                  foreach($colModelos as $elem){ ?>     
                      //Datos modelo                   
                      var datosModelo = {id: 'id'+<?php echo "'".$elem->gModeloId."'";?>, label:'<?php echo $elem->modelo;?>', value:<?php echo "'".$elem->gModeloId."'";?>, checked:false};
                      //Datos de la version por modelo                      
                      var childrenArr = [];
                      <?php     
                        if(count($colVersionesModelo)>0){                            
                            foreach($colVersionesModelo as $elemV){                               
                               if($elem->gModeloId==$elemV->gModeloId){
                                 ?>
                                 var childrenElem = {item:{id: 'id'+<?php echo "'".$elemV->gralVersId."'";?>, label:'<?php echo $elemV->version;?>', value:<?php echo "'".$elemV->gralVersId."'";?>, checked: false}};
                                 childrenArr.push(childrenElem);
                                 <?php    
                               }
                            }
                            ?>
                            // console.log(childrenArr);
                            // console.log("---------");
                            <?php
                        }
                      ?>                      

                      //Agregamos al arreglo general
                      mockData.push({
                                        item:datosModelo,
                                        children:childrenArr
                                    });
            <?php } ?>
        <?php } ?>
      
      $(function(){
        $("ul.checktree").checktree();
      });    
    </script>

    
    <?php 
    //Mostrar mensaje al haber salvado correctamente los planes - por cada modelo seleccionado
    if(count($arrTotalSalvados)>0){
        $actualizacionesObj = new catActualizacionesObj();
        $actualizacionesObj->updActualizacion("version_planes");
        echo '<script type="text/javascript">alertify.success("Datos guardados correctamente");</script>';
    }
    ?>
</body>
</html>
