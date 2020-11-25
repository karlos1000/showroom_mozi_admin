<?php
session_start();
$idRol = $_SESSION['idRol'];
switch ($idRol) {
    case 1: case 2: case 4:  $rol = true; break;
    default: $rol = false; break;
}
if($_SESSION['status']!= "ok" || $rol!=true)
        header("location: logout.php");

include_once '../common/screenPortions.php';
include '../brules/utilsObj.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
libreriasKool();
$localization = "../brules/KoolControls/KoolCalendar/localization/es.xml";
include_once '../brules/usuariosObj.php';
include_once '../brules/rolesObj.php';
include_once '../brules/sesionInvalidaObj.php';
include_once '../brules/catAyudasObj.php';
include_once '../brules/catGaleriasObj.php';
include_once '../brules/catConceptoPreciosObj.php';
include_once '../brules/catConceptoPlanesObj.php';
include_once '../brules/versionPlanesObj.php';
include_once '../brules/versionRequisitosObj.php';
include_once '../brules/catTransmisionesObj.php';
include_once '../brules/catTasasObj.php';
include_once '../brules/promocionesObj.php';
include_once '../brules/catAgenciasObj.php';
include_once '../brules/catActualizacionesObj.php';
include_once '../brules/catConfiguracionesObj.php';
include_once '../brules/inventarioPermisosObj.php';

$selected = '';
$result = '';
$optCat = false;
$msjResponse = "";

// Eliminar promociones
if(isset($_POST['eliminarPromocion'])){
    $promosObj = new promocionesObj();
    $promosObj->EliminarVersionPromocionPorIdPadre($_POST['promocionId']); //Borrar todas las promociones anteriores por el id padre
    $respDelProm = $promosObj->EliminarVersionPromocion($_POST['promocionId']);    
}

// Eliminar planes preconfigurados
if(isset($_POST['eliminarPlanPreconf'])){    
    $versPlanObj = new versionPlanesObj();        
    $versPlanObj->EliminarVersionPlanPorIdPadre($_POST['planId']); //Borrar todos los planes anteriores por el id padre 
    $respDelPlan = $versPlanObj->EliminarVersionPlan($_POST['planId']); //Borra el plan preconfigurado por su id
}

//Continuar si esta seleccionado algun catalogo
if(isset($_GET['catalog'])){
    $optCat = true;
    $selected = $_GET['catalog'];

    if($selected == 'usuarios'){
        $accObj = new usuariosObj();
        $result = $accObj->GetUsersGrid();
    }
    elseif($selected == 'roles'){
        $accObj = new rolesObj();
        $result = $accObj->GetRolesGrid();
    }    
    elseif($selected == 'catgalerias'){
        $accObj = new catGaleriasObj();
        $result = $accObj->GetCatGaleriasGrid();
    }   
    elseif($selected == 'catConceptoPrecios'){
        $accObj = new catConceptoPreciosObj();
        $result = $accObj->GetCatConceptoPreciosGrid();
    }
    elseif($selected == 'catConceptoPlanes'){
        $accObj = new catConceptoPlanesObj();
        $result = $accObj->GetCatConceptoPlanesGrid();
    }
    elseif($selected == 'catPlanesPreconfigurados'){
        $accObj = new versionPlanesObj();
        $result = $accObj->GetVersionPlanGrid("", "", -1, 1);
    }
    elseif($selected == 'catRequisitosPreconfigurados'){        
        $accObj = new versionRequisitosObj();
        $result = $accObj->GetVersionRequisitoGrid("",1);
    }
    elseif($selected == 'catTransmisiones'){
        $accObj = new catTransmisionesObj();
        $result = $accObj->GetTransmisionesGrid("");
    }
    elseif($selected == 'catTasas'){
        $accObj = new catTasasObj();
        $result = $accObj->GetTasasGrid();
    }
    elseif($selected == 'catPromociones'){
        $accObj = new promocionesObj();
        $result = $accObj->GetVersionPromocionGrid("", 1);
    }
    elseif($selected == 'catAgencias'){
        $accObj = new catAgenciasObj();
        $result = $accObj->GetAgenciaGrid(1);
    }
    elseif($selected == 'catConfig'){
        $accObj = new catConfiguracionesObj();
        $result = $accObj->ObtConfiguracionesGrid();
    }
    elseif($selected == 'camposInventario'){
        $accObj = new inventarioPermisosObj();
        $result = $accObj->ObtInventarioPermisosGrid();
    }  

    // elseif($selected == 'sesionInvalida'){
    //     $accObj = new sesionInvalidaObj();
    //     // $result = $accObj->ObtSesionesInvalidasGrid();
    // }
}
else{
  $optCat = true;

  // Verifica el rol
  if($idRol==4){
    $accObj = new catTasasObj();
    $result = $accObj->GetTasasGrid();
    $selected = 'catTasas';
  }else{    
    $accObj = new usuariosObj();
    $result = $accObj->GetUsersGrid();
    $selected = 'usuarios';
  }  
}

$conceptoPlanObj = new catConceptoPlanesObj();
$conceptosPlanes = $conceptoPlanObj->ObtTodosCatConceptoPlanes();

//Mensajes 
if(isset($_GET['mensaje']))
{
    switch ($_GET['mensaje']) {
        case 1:
            $mensaje = 'Error al tratar de recuperar la imagen';
            break;
        case 2:
            $mensaje = 'El archivo que intento subir, no es una imagen';
            break;
        case 3:
            $mensaje = 'La imagen se ha subido correctamente';
            break;
        case 4:
            $mensaje = 'No se pudo subir el archivo al servidor';
            break;
        default:
            $mensaje = 'Error';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Cat&aacute;logos</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>    
    <link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css"/>    
    <link href="../js/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css" />
    <link href="../css/style.css" rel="stylesheet" type="text/css" />
    <link href="../css/styleupload.css" rel="stylesheet" type="text/css" />
    <link href="../css/alertify.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="../css/alertify.default.css" rel="stylesheet" type="text/css" /> -->
    <link rel="stylesheet" href="../css/themes/semantic.min.css"/>
    <script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>

    <script type="text/javascript" src="../js/bootstrap.min.js"></script>    
    <script type="text/javascript" src="../js/fancybox/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script>var tieneAlertify = true;</script>
    <script type="text/javascript" src="../js/functions.js"></script>
    <script type="text/javascript" src="../js/blueupload.js"></script>
    <script type="text/javascript" src="../js/alertify.js"></script>

    <script type="text/javascript" src="../js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="../js/jquery_base64/jquery.base64.js"></script>
    <script type="text/javascript" src="../js/functions.js"></script>
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
                        <h1 class="titulo">Cat&aacute;logos </h1>
                        <!--Mostrar en caso de presionar el boton de guardar-->
                        <?php if ($msjResponse != "") { ?>
                            <div class="new_line alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?php echo $msjResponse; ?>
                            </div>
                        <?php } ?>
                         <?php if (isset($mensaje)) { ?>
                            <div class="new_line alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?php echo $mensaje; ?>
                            </div>
                        <?php } ?>

                        <?php // Verifica el rol
                            if($idRol!=4): ?>
                            <div class="filtro_cat">
                                <div class="row">
                                    <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                        <label for="catalogo">Catálogo:</label>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-6">                                        
                                        <select id="selectCatalog" onchange="obtenervalorfiltro(this);" class="form-control" required>
                                            <option value="">--Seleccionar--</option>                                                                                                                        
                                            <!--?php if($_SESSION["permisos"]["Catalogos_otros_ver"] == 1){ ?>
                                            <option value="sesionInvalida" <?php echo ($selected == "sesionInvalida") ? "selected" : ""; ?> >Sesion Invalida</option>
                                            ?php } ?-->
                                            <option value="usuarios" <?php echo ($selected == "usuarios") ? "selected" : ""; ?> >Usuarios</option>
                                            <option value="catAgencias" <?php echo ($selected == "catAgencias") ? "selected" : ""; ?> >Agencias</option>
                                            <option value="roles" <?php echo ($selected == "roles") ? "selected" : ""; ?> >Roles</option>
                                            <option value="catgalerias" <?php echo ($selected == "catgalerias") ? "selected" : ""; ?> >Galer&iacute;as</option>
                                            <option value="catConceptoPrecios" <?php echo ($selected == "catConceptoPrecios") ? "selected" : ""; ?> >Fuentes</option>
                                            <option value="catConceptoPlanes" <?php echo ($selected == "catConceptoPlanes") ? "selected" : ""; ?> >Tipo Planes</option>
                                            <option value="catPlanesPreconfigurados" <?php echo ($selected == "catPlanesPreconfigurados") ? "selected" : ""; ?> >Planes Preconfigurados</option>
                                            <option value="catRequisitosPreconfigurados" <?php echo ($selected == "catRequisitosPreconfigurados") ? "selected" : ""; ?> >Requisitos Preconfigurados</option>
                                            <option value="catTransmisiones" <?php echo ($selected == "catTransmisiones") ? "selected" : ""; ?> >Conceptos</option>
                                            <option value="catTasas" <?php echo ($selected == "catTasas") ? "selected" : ""; ?> >Tasas</option>
                                            <option value="catPromociones" <?php echo ($selected == "catPromociones") ? "selected" : ""; ?> >Promociones</option>                                        
                                            <option value="catConfig" <?php echo ($selected == "catConfig") ? "selected" : ""; ?> >Configuraciones</option>
                                            <option value="camposInventario" <?php echo ($selected == "camposInventario") ? "selected" : ""; ?> >Inventario Campos Extras</option>
                                                

                                            <!-- <option value="sesionInvalida" <?php echo ($selected == "sesionInvalida") ? "selected" : ""; ?> >Sesion Invalida</option> -->
                                        </select>
                                    </div>
                                    <?php if($selected == "catAgencias"){ ?>
                                        <a href="regAgencia.php" class="btn btn-success" role="button">Nuevo</a>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if($optCat==true){ ?>
                        <br/>
                        <div>
                            <form name="grids" method="post">
                                <?php
                                echo $koolajax->Render();
                                echo '<div id="contsGrid">';
                                if($result != null)
                                {
                                echo $result->Render();
                                    
                                    if($selected == "catPlanesPreconfigurados"){    
                                        echo '<div class="row">';
                                            echo '<div class="col-md-12 text-right">';
                                            echo '<a href="#fancyVersionPlan" class="btn btn-success" id="btnAgregarPlan" role="button" onclick="mostrarFancyAgregarPlan(0,\'\')">Agregar plan</a>';
                                            echo "</div>";
                                        echo "</div>";
                                    }

                                    if($selected == "catRequisitosPreconfigurados"){    
                                        echo '<div class="row">';
                                            echo '<div class="col-md-12 text-right">';
                                            echo '<a href="#fancyVersionReq" class="btn btn-success" id="btnAgregarRequisito" role="button" onclick="mostrarFancyAgregarRequisito(0,\'\')">Agregar requisito</a>';
                                            echo "</div>";
                                        echo "</div>";
                                    }

                                    if($selected == "catPromociones"){    
                                        echo '<div class="row">';
                                            echo '<div class="col-md-12 text-right">';
                                            echo '<a href="#fancyVersionPromocion" class="btn btn-success" id="btnAgregarPromocion" role="button" onclick="mostrarFancyAgregarPromocion(0,\'\')">Agregar promoci&oacute;n</a>';
                                            echo "</div>";
                                        echo "</div>";
                                    }                               
                                }                                
                                echo '</div>';
                                ?>
                            </form>
                        </div>
                        <?php } ?>
                    </div>                    
                </div>
            </div>
        </div>

        <!-- Para la edicion y creacion de planes preconfigurados -->
        <div id="fancyVersionPlan" style="display:none;width:800px;height:500px;">
            <form role="form" id="formVersionPlan" name="formVersionPlan" method="get" action="" >
                <input type="hidden" id="idVersionGral" name="idVersionGral" value="">
                <input type="hidden" id="idVersionPlan" name="idVersionPlan" value="">
                <input type="hidden" id="vp_preconfigurado" name="vp_preconfigurado" value="1">                
                
                <h4>Agregar/Editar Plan</h4>
                
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-3">
                        <label>Tipo plan</label>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-9">
                       <select class="form-control required" name="vp_idtipoplan" id="vp_idtipoplan">
                        <option value="">* --Seleccionar--</option>

                        <?php
                          foreach ($conceptosPlanes as $concepto) {
                            echo '<option value="'.$concepto->planId.'">'.$concepto->plan.'</option>';
                          }
                         ?>
                       </select>
                    </div>
                </div>

                <div class="row">
                  <div class="col-md-3 col-sm-3 col-xs-3">
                        <label>Caracter&iacute;sticas</label>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-9" id="div_vp_carac">
                      <textarea class="form-control required" name="vp_carac" id="vp_carac"></textarea>
                    </div>
                </div>

                <br/>
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <input type="button" role="button" id="btnGuardarPlan" name="btnGuardarPlan" class="btn btn-primary" onclick="guardarVersionPlan()" value="Guardar">
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <input type="button" value="Cancelar" class="btn btn-primary" role="button" onclick="parent.$.fancybox.close();" >
                    </div>
                </div>
                
            </form>
        </div>

        <!-- Para la edicion y creacion de requisitos preconfigurados -->
        <div id="fancyVersionReq" style="display:none;width:800px;height:500px;">
            <form role="form" id="formVersionReq" name="formVersionReq" method="get" action="" enctype="multipart/form-data">
                <input type="hidden" id="vr_idVersionGral" name="vr_idVersionGral" value="">
                 <input type="hidden" id="idVersionReq" name="idVersionReq" value="">
                 <input type="hidden" id="vr_preconfigurado" name="vr_preconfigurado" value="1">
                
                <h4>Agregar/Editar Requisito</h4>
                
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-3">
                        <label>Concepto</label>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-9">
                       <input class="form-control" type="text" name="vr_concepto" id="vr_concepto" value="">
                    </div>
                </div>

                <div class="row">
                  <div class="col-md-3 col-sm-3 col-xs-3">
                        <label>Caracter&iacute;sticas</label>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-9" id="div_vr_carac">
                      <textarea class="form-control required" name="vr_carac" id="vr_carac"></textarea>
                    </div>
                </div>                
                <br/>
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-3">Pdf Carta Buro</div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <input class="form-control" type="file" name="pdfCartaBuro" id="pdfCartaBuro" accept="application/pdf">                        
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-3" id="addContPdfCartaburo"></div>
                </div>                
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-3">Pdf Solicitud Cr&eacute;dito</div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <input class="form-control" type="file" name="pdfSolCredito" id="pdfSolCredito" accept="application/pdf">                        
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-3" id="addContPdfSolcredito"></div>
                </div>
                <br/>

                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <input type="button" role="button" id="btnGuardarReq" name="btnGuardarReq" class="btn btn-primary" onclick="guardarVersionReq()" value="Guardar">
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <input type="button" value="Cancelar" class="btn btn-primary" role="button" onclick="parent.$.fancybox.close();" >
                    </div>
                </div>
                
            </form>
        </div>

        <!-- Para la edicion y creacion de promociones -->
        <div id="fancyVersionPromocion" style="display:none;width:800px;height:500px;">
            <form role="form" id="formVersionPromo" name="formVersionPromo" method="get" action="" >
                <input type="hidden" id="promo_preconfigurado" name="promo_preconfigurado" value="1">
                <!-- <input type="hidden" id="vr_idVersionGral" name="vr_idVersionGral" value=""> -->
                <input type="hidden" id="idPromocion" name="idPromocion" value="">                
                
                <h4>Agregar/Editar Promoci&oacute;n</h4>
                
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-3">
                        <label>Concepto</label>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-9">
                       <input class="form-control" type="text" name="promo_concepto" id="promo_concepto" value="">
                    </div>
                </div>

                <div class="row">
                  <div class="col-md-3 col-sm-3 col-xs-3">
                        <label>Caracter&iacute;sticas</label>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-9" id="div_promo_carac">
                      <textarea class="form-control required" name="promo_carac" id="promo_carac"></textarea>
                    </div>
                </div>

                <br/>
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <input type="button" role="button" id="btnGuardarPromo" name="btnGuardarPromo" class="btn btn-primary" onclick="guardarPromocion()" value="Guardar">
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <input type="button" value="Cancelar" class="btn btn-primary" role="button" onclick="parent.$.fancybox.close();" >
                    </div>
                </div>
                
            </form>
        </div>


    </section>
    <footer>
        <div class="panel-footer">
            <?php echo getPiePag(true); ?>
        </div>
    </footer>

     <!--Para crear el grid-->
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/accounting.min.js"></script>


    <?php 
    //Mostrar mensaje al haber salvado correctamente los planes - por cada modelo seleccionado
    if(isset($respDelProm) && $respDelProm>0){
        $actualizacionesObj = new catActualizacionesObj();
        $actualizacionesObj->updActualizacion("promociones");
        echo '<script type="text/javascript">alertify.success("Promocion borrada correctamente");</script>';
    }

    if(isset($respDelPlan) && $respDelPlan>0){
        $actualizacionesObj = new catActualizacionesObj();
        $actualizacionesObj->updActualizacion("version_planes");
        echo '<script type="text/javascript">alertify.success("Plan borrado correctamente");</script>';   
    }    
    ?>
</body>
</html>
