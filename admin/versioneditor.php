<?php
session_start();
$idRol = $_SESSION['idRol'];
switch ($idRol) {  
    case 1: $rol = true; break;
    case 2: $rol = true; break;
    case 4: $rol = true; break;
    default: $rol = false; break;
}
if($_SESSION['status']!= "ok" || $rol!=true)
        header("location: logout.php");

include_once '../common/screenPortions.php';
include '../brules/utilsObj.php';
include_once '../brules/KoolControls/KoolGrid/koolgrid.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
require_once '../brules/KoolControls/KoolCalendar/koolcalendar.php';
include_once '../brules/KoolControls/KoolGrid/ext/datasources/MySQLiDataSource.php';
$localization = "../brules/KoolControls/KoolCalendar/localization/es.xml";
include_once '../brules/versionGeneralesObj.php';
include_once '../brules/versionPreciosObj.php';
include_once '../brules/versionPlanesObj.php';
include_once '../brules/versionRequisitosObj.php';
include_once '../brules/versionColoresObj.php';
include_once '../brules/versionZonasObj.php';
include_once '../brules/versionZonasActivasObj.php';
include_once '../brules/gamaModelosObj.php';
include_once '../brules/catConceptoPlanesObj.php';
include_once '../brules/catGaleriasObj.php';
include_once '../brules/catActualizacionesObj.php';
include_once '../brules/catConceptoPreciosObj.php';

//establecer la zona horaria
$dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
$dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual

$versionGeneralesObj = new versionGeneralesObj();
$verId = (isset($_GET['verId'])) ?$_GET['verId'] :0;


$gamaModelosObj = new gamaModelosObj();
$modelos = $gamaModelosObj->ObtTodosGamaModelos(false, 1);

$versionPreciosObj = new versionPreciosObj();
$versionPlanesObj = new versionPlanesObj();
$versionRequisitosObj = new versionRequisitosObj();
$versionColoresObj = new versionColoresObj();
$versionZonasObj = new versionZonasObj();
$versionZonasActivasObj = new versionZonasActivasObj();

$conceptoPlanObj = new catConceptoPlanesObj();
$galeriasObj = new catGaleriasObj();

$conceptosPlanes = $conceptoPlanObj->ObtTodosCatConceptoPlanes();
$tiposGalerias = $galeriasObj->ObtTodosCatGalerias();

$conceptosObj = new catConceptoPreciosObj();
$conceptos = $conceptosObj->ObtTodosCatConceptoPrecios();
$tipoAviso = '';

$tabActiveGral = "active";
$tabActiveColor = "";
$tabActiveImgP = "";
$tabActivePrec = "";
$tabActivePlan = "";
$msg = "";
$contColoresVersion = 0;
$idColorImgPrincipal = (isset($_GET["idColorImgPrincipal"]))?$_GET["idColorImgPrincipal"]:"";
$cambiarState = 0;
if($idColorImgPrincipal != "")
{
  $tabActiveGral = "";
  $tabActiveColor = "";
  $tabActiveImgP = "active";
  $tabActivePrec = "";
  $tabActivePlan = "";
}

if(isset($_POST["vg_modelo"]))
{ 
    $verId = $_POST['idVersionGral'];
    $versionGeneralesObj->gModeloId = $_POST["vg_modelo"];
    $versionGeneralesObj->version = $_POST["vg_version"];
    $versionGeneralesObj->precioInicial = removerComas($_POST["vg_precioi"]);
    $versionGeneralesObj->caracteristicas = reemplazarAEntities($_POST["vgcaracteristicas"]);
    $versionGeneralesObj->sAutomotriz = removerComas($_POST["vg_sAutomotriz"]);
    $versionGeneralesObj->sVida = removerComas($_POST["vg_sVida"]);
    $versionGeneralesObj->sDesempleo = removerComas($_POST["vg_sDesempleo"]);
    $versionGeneralesObj->sGarantiaExt = removerComas($_POST["vg_sGarantiaExt"]);  //Imp. 25/11/19
    $versionGeneralesObj->codigoVersion = $_POST["vg_codigoversion"];

    //Verifica si hay imagen por subir    
    $paramsImg = (object)array("folderDestino"=>"../upload/vmodelosImg/", "arrExt"=>array("jpg","png","jpeg","gif"));
    $resArrImg = subirImagen($_FILES, $paramsImg);

    //subir pdf de caracteristicas completas
    $paramsPdf = (object)array("folderDestino"=>"../upload/pdfs/", "arrExt"=>array("pdf"), "nameFile"=>"pdfCarac");
    $resArrPdf = subirArchivo($_FILES, $paramsPdf);
    // echo "<pre>";
    // print_r($resArrPdf);
    // echo "</pre>";

    if($verId==0){        
        $versionGeneralesObj->imagen  = '../upload/vmodelosImg/'.$resArrImg->nImg;
        $versionGeneralesObj->urlPdf  = ($resArrPdf->result==true) ?'../upload/pdfs/'.$resArrPdf->nArchivo:"";

        $versionGeneralesObj->InsertVersionGral();           
        $verId = $versionGeneralesObj->gralVersId;

        $tipoAviso = ($verId > 0)?1:0;
        if($verId > 0)
        {
          $actualizacionesObj = new catActualizacionesObj();
          $actualizacionesObj->updActualizacion("version_generales");
        }
        // header("version.php?verId=".$verId);
        $cambiarState = 1;
    }
    else
    {      
        $versionGeneralesObj->imagen  = ($resArrImg->result==true) ?'../upload/vmodelosImg/'.$resArrImg->nImg:$_POST['hid_imagen'];
        $versionGeneralesObj->urlPdf  = ($resArrPdf->result==true) ?'../upload/pdfs/'.$resArrPdf->nArchivo:$_POST['hid_pdf'];
        $versionGeneralesObj->gralVersId = $verId;

        $paramsImg = (object)array("folderDestino"=>"../upload/vmodelosImg/", "arrExt"=>array("jpg","png","jpeg","gif"));
        $resArrImg = subirImagen($_FILES, $paramsImg);

        $respAct = $versionGeneralesObj->ActVersionGral();
        $tipoAviso = ($respAct > 0)?1:2;
        if($respAct>0){
          $actualizacionesObj = new catActualizacionesObj();
          $actualizacionesObj->updActualizacion("version_generales");
        }
    }
}


if( isset($_POST["idVersionColor"]) )
{
    $tabActiveGral = "";
    $tabActiveColor = "active";
    $tabActiveImgP =  "";
    $tabActivePrec = "";
    $tabActivePlan = "";

    $idVersionColor = $_POST['idVersionColor'];
    $versionColoresObj->color = $_POST["vc_color"];
    $versionColoresObj->gralVersId = $_POST["vc_idVersionGral"];

    
    //Verifica si hay imagen por subir    
    $paramsImg = (object)array("folderDestino"=>"../upload/coloresImg/", "arrExt"=>array("jpg","png","jpeg","gif"));
    $resArrImg = subirImagen($_FILES, $paramsImg);
    $paramsImg = (object)array("folderDestino"=>"../upload/coloresImg/btn_colorImg/", "arrExt"=>array("jpg","png","jpeg","gif"));
    $resArrImg2 = subirImagen($_FILES, $paramsImg, 1);

    if($idVersionColor==0)
    {     
        if($resArrImg->result==true && $resArrImg2->result==true)
        {
            $versionColoresObj->imagenAuto  = '../upload/coloresImg/'.$resArrImg->nImg;
            $versionColoresObj->imagenColor  = '../upload/coloresImg/btn_colorImg/'.$resArrImg2->nImg;

            $versionColoresObj->InsertVersionColor();           
            $idVersionColor = $versionColoresObj->coloresVersId;

            $tipoAviso = ($idVersionColor > 0)?1:0;
            if($idVersionColor > 0)
            {
              $actualizacionesObj = new catActualizacionesObj();
              $actualizacionesObj->updActualizacion("version_colores");
            }
        }
        else
        {
          $msg = $resArrImg->respImg."<br>".$resArrImg2->respImg;
        }        
    }
    else
    {
        $versionColoresObj->imagenAuto  = ($resArrImg->result==true) ?'../upload/coloresImg/'.$resArrImg->nImg:$_POST['hid_imagen'];
         $versionColoresObj->imagenColor  = ($resArrImg2->result==true) ?'../upload/coloresImg/btn_colorImg/'.$resArrImg2->nImg:$_POST['hid_imagenc'];
        $versionColoresObj->coloresVersId = $idVersionColor;

        $respAct = $versionColoresObj->ActVersionColor();
        $tipoAviso = ($respAct > 0)?1:2;
        if($respAct>0){
          $actualizacionesObj = new catActualizacionesObj();
          $actualizacionesObj->updActualizacion("version_colores");
        }
    }
}

if( isset($_POST["idVersionColorPordefecto"]) )
{
    $tabActiveGral = "";
    $tabActiveColor = "active";
    $tabActiveImgP =  "";
    $tabActivePrec = "";
    $tabActivePlan = "";
    $tipoAviso = 1;
}


if($verId > 0)
{  
  $conceptoPrecioB = '';
  $activoPrecioB = -1;
  $conceptoPlanB = '';
  $activoPlanB = -1;
  if(isset($_GET['buscarPrecios'])){
      if(isset($_GET['conceptoPrecioB'])){
          $conceptoPrecioB = $_GET['conceptoPrecioB'];        
      } 
      if(isset($_GET['activoPrecioB'])){
          $activoPrecioB = $_GET['activoPrecioB'];        
      }
      $tabActiveGral = "";
      $tabActiveColor = "";
      $tabActiveImgP =  "";
      $tabActivePrec = "active";
      $tabActivePlan = "";     
  }
  if(isset($_GET['buscarPlanes'])){
      if(isset($_GET['conceptoPlanB'])){
          $conceptoPlanB = $_GET['conceptoPlanB'];        
      } 
      if(isset($_GET['activoPlanB'])){
          $activoPlanB = $_GET['activoPlanB'];        
      }
      $tabActiveGral = "";
      $tabActiveColor = "";
      $tabActiveImgP =  "";
      $tabActivePrec = "";
      $tabActivePlan = "active";     
  }
  // echo "consultar<br>";
  $versionGeneralesObj->versionGeneralPorId($verId);
  $gamaModelosObj->ObtDatosGModeloPorId($versionGeneralesObj->gModeloId);

  $resultGridPrecios = $versionPreciosObj->GetVersionPrecioGrid($verId, $conceptoPrecioB, $activoPrecioB );
  $resultGridPlanes = $versionPlanesObj->GetVersionPlanGrid($verId, $conceptoPlanB, $activoPlanB);
  $resultGridRequisitos = $versionRequisitosObj->GetVersionRequisitoGrid($verId);
  $resultGridColores = $versionColoresObj->GetVersionColorGrid($verId);

  $coloresVersion = $versionColoresObj->ObtVersionesColores(false, $verId);

  $contColoresVersion = count($coloresVersion);
  // @header("version.php?verId=".$verId);
}
else
{  
  $resultGridPrecios = null;
  $resultGridPlanes = null;
  $resultGridRequisitos = null;
  $resultGridColores = null;
}
$claseTabs = ($verId > 0)?'':'tabdisabled';
$claseTabImgP = ($contColoresVersion == 0)?'tabdisabled':'';
$claseTabGal = ($contColoresVersion == 0)?'tabdisabled':'';
// echo $verId."<br>";
$tituloPagina = ($verId > 0)?'Editar - '.$gamaModelosObj->modelo.' '.$gamaModelosObj->anio.' '.$versionGeneralesObj->version:'Agregar nueva version';


//Metodos para recargar los dropdowns
$idG = 0;
dropDownGaleria(trim($idG));
$koolajax->enableFunction("dropDownGaleria");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo $tituloPagina ?> </title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>    
    <link href="../css/style.css" rel="stylesheet" type="text/css" />
    <link href="../js/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css" />

    <link href="../css/alertify.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../css/themes/semantic.min.css"/>
    <link rel="stylesheet" type="text/css" href="../css/interact.css"/>

    <script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>    
    <!--<script type="text/javascript" src="../js/jquery-ui.js"></script>-->
    <script type="text/javascript" src="../js/fancybox/jquery.fancybox.pack.js"></script>
     <script type="text/javascript" src="../js/accounting.min.js"></script> 
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/additional-methods.js"></script>
    <script type="text/javascript" src="../js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="../js/jquery_base64/jquery.base64.js"></script>
    <script type="text/javascript" src="../js/functions.js"></script>
    <script src="../js/popupoverlay/js/jquery.popupoverlay.js"></script>
    <script type="text/javascript">
      $( document ).ready(function() {
        var params = {selector:"#vgcaracteristicas", height:"230", btnImg:true, readonly:1};
        opcionesTinymce(params);

        mostrarAlertify();
        if($("#claseTabGal").val() != "")
        {
            $("#btnMenu6").addClass($("#claseTabGal").val());
        }
        // versionColorGrid.refresh();
        // versionColorGrid.commit();
        console.log($("#cambiarState").val());
        if($("#cambiarState").val() == 1)
        {
          var stateObj = {};
          window.history.pushState(stateObj, "Cambiar url version", "version.php?verId="+$("#idVersionGral").val());
        }

        // Initialize popup
        $('#my_popup').popup();
      });

      
    </script>
   <script type="text/javascript" src="../js/alertify.js"></script>
   <script src="../js/interact.js"></script>
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
                    <div class="colmenu col-md-3 menu_bg">
                        <?php echo getNav($menu); ?>
                    </div>
                    <div class="col-md-9">
                        <h1 class="titulo"><?php echo $tituloPagina ?> </h1>  

                        <?php if($msg != ''){ ?>

                          <div class="alert alert-info">
                            <?php echo $msg ?>
                          </div>
                        <?php } ?>
                       <!--  <ol class="breadcrumb">
                          <li><a href="index.php">Inicio</a></li>
                          <li><a href="busqueda.php">Busqueda</a></li>                          
                          <li class="active">Subir documentos</li>
                        </ol>   -->   
                        
                        <div class="row">
                                 <ul class="nav nav-tabs">
                              <li class="<?php echo $tabActiveGral ?>"><a data-toggle="tab" href="#home">Datos Grals.</a></li>
                              <!-- <li class="<?php echo $tabActiveColor ?>"><a data-toggle="tab" href="#menu1" class="<?php echo $claseTabs ?>">Colores</a></li> -->
                              <!-- <li class="<?php echo $tabActiveImgP ?>"><a data-toggle="tab" href="#menu2" class="<?php echo $claseTabImgP ?>" id="btnMenu2">Imagen principal</a></li> -->
                              <!-- <li><a data-toggle="tab" href="#menu6" class="<?php echo $claseTabGal ?> btnMenu6" id="btnMenu6">Galer&iacute;as</a></li> -->
                              <li class="<?php echo $tabActivePrec; ?>"><a data-toggle="tab" href="#menu3" class="<?php echo $claseTabs ?>">Precios</a></li>
                              <!-- <li class="<?php echo $tabActivePlan; ?>"><a data-toggle="tab" href="#menu4" class="<?php echo $claseTabs ?>">Planes</a></li> -->
                              <!-- <li><a data-toggle="tab" href="#menu5" class="<?php echo $claseTabs ?>">Requisitos</a></li> -->
                            </ul>
                            
                            <div class="tab-content">

                              <!-- home -->
                              <div id="home" class="tab-pane fade in <?php echo $tabActiveGral ?>">
                                <h3>Datos generales</h3>
                                <input type="hidden" id="tipoAviso" value="<?php echo $tipoAviso?>">
                                <form role="form" id="formVersionGral" name="formVersionGral" method="post" action="" enctype="multipart/form-data"> 
                                  <input type="hidden" name="idVersionGral" id="idVersionGral" value="<?php echo $verId; ?>">
                                  <input type="hidden" name="cambiarState" id="cambiarState" value="<?php echo $cambiarState; ?>">                                  

                                <div class="row">
                                    <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                        <label for="vg_modelo">Modelo:</label>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <select class="form-control required sel_sololectura" name="vg_modelo" id="vg_modelo">
                                          <option value="">Seleccione...</option>
                                          <?php
                                          foreach ($modelos as $modelo) {
                                            $selected = ($modelo->gModeloId==$versionGeneralesObj->gModeloId)?"selected":"";
                                            echo '<option value="'.$modelo->gModeloId.'" '.$selected.'>'.$modelo->modelo.' '.$modelo->anio.'</option>';
                                          }
                                           ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                        <label for="vg_version">Versi&oacute;n:</label>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <input type="text" id="vg_version" name="vg_version" value="<?php echo $versionGeneralesObj->version;?>" class="form-control required" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                        <label for="vg_codigoversion">C&oacute;digo Versi&oacute;n:</label>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <input type="text" id="vg_codigoversion" name="vg_codigoversion" value="<?php echo $versionGeneralesObj->codigoVersion;?>" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                        <label for="vg_precioi">Precio inicial:</label>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <input type="text" id="vg_precioi" name="vg_precioi" value="<?php echo ($versionGeneralesObj->precioInicial > 0)?number_format($versionGeneralesObj->precioInicial,2):'';?>" class="form-control text-right required" onchange="muestraValorMoneda(this.id, this.value)">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                        <label for="vg_sAutomotriz">Seguro Automotriz:</label>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <input type="text" id="vg_sAutomotriz" name="vg_sAutomotriz" value="<?php echo ($versionGeneralesObj->sAutomotriz > 0)?number_format($versionGeneralesObj->sAutomotriz,2):'0.00';?>" class="form-control text-right required" onchange="muestraValorMoneda(this.id, this.value)">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                        <label for="vg_sVida">Seguro Vida:</label>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <input type="text" id="vg_sVida" name="vg_sVida" value="<?php echo ($versionGeneralesObj->sVida > 0)?number_format($versionGeneralesObj->sVida,2):'0.00';?>" class="form-control text-right required" onchange="muestraValorMoneda(this.id, this.value)">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                        <label for="vg_sDesempleo">Seguro Desempleo:</label>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <input type="text" id="vg_sDesempleo" name="vg_sDesempleo" value="<?php echo ($versionGeneralesObj->sDesempleo > 0)?number_format($versionGeneralesObj->sDesempleo,2):'0.00';?>" class="form-control text-right required" onchange="muestraValorMoneda(this.id, this.value)">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                        <label for="vg_sGarantiaExt">Garant&iacute;a Extendida:</label>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <input type="text" id="vg_sGarantiaExt" name="vg_sGarantiaExt" value="<?php echo ($versionGeneralesObj->sGarantiaExt > 0)?number_format($versionGeneralesObj->sGarantiaExt,2):'0.00';?>" class="form-control text-right required" onchange="muestraValorMoneda(this.id, this.value)">
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                        <label for="vgcaracteristicas">Caracter&iacute;sticas:</label>
                                    </div>
                                    <div class="col-md-9 col-sm-9 col-xs-9">
                                        <textarea class="form-control required" name="vgcaracteristicas" id="vgcaracteristicas" rows="5" ><?php echo $versionGeneralesObj->caracteristicas; ?></textarea>
                                        
                                    </div>
                                </div>
                                
                                <br/>
                                <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="imagen">Imagen miniatura:</label>
                                  </div>
                                  <div class="col-md-8 col-sm-8 col-xs-8">
                                    <!-- accept="image/*"  -->                                                                        
                                     <?php if($versionGeneralesObj->imagen!=''){ ?>                                        
                                        <div style="display:inline-block;">
                                          <input type="file" name="imagen" id="vg_imagen" size="10" class="form-control sel_sololectura" accept="image/gif, image/jpg, image/jpeg, image/png"/>
                                        </div>
                                        <div style="display:inline-block;">
                                          <img src="<?php echo ''.$versionGeneralesObj->imagen;?>" height="100"><br/>
                                        </div>
                                        <input type="hidden" name="hid_imagen" id="hid_imagen" value="<?php echo $versionGeneralesObj->imagen; ?>">
                                    <?php }else{ ?>                
                                        <input type="file" name="imagen" id="vg_imagen" size="10" class="form-control required sel_sololectura" accept="image/gif, image/jpg, image/jpeg, image/png" />
                                    <?php } ?>
                                  </div>
                                </div>
                                
                                <!-- pdf -->
                                <br/>
                                <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="pdfCarac">Caracter&iacute;sticas PDF:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                    <!-- accept="image/*"  -->                                                                        
                                     <?php if($versionGeneralesObj->urlPdf!=''){ ?>                                        
                                        <!-- <a href="<?php echo $versionGeneralesObj->urlPdf; ?>" target="_blank">VER PDF</a> -->
                                        <input type="file" name="pdfCarac" id="pdfCarac" size="10" class="form-control sel_sololectura" accept="application/pdf, application/x-download" />                                        
                                    <?php }else{ ?>                
                                        <input type="file" name="pdfCarac" id="pdfCarac" size="10" class="form-control sel_sololectura" accept="application/pdf, application/x-download" />
                                    <?php } ?>
                                    <input type="hidden" name="hid_pdf" id="hid_pdf" value="<?php echo $versionGeneralesObj->urlPdf; ?>">
                                  </div>
                                </div>


                                <div class="new_line"></div>
                                <div class="row">
                                  <div class="col-md-offset-1 col-md-3">
                                    <a href="javascript:void(0)" class="btn btn-success" role="button" onclick="verificaFormVG()">Guardar</a>                                 
                                  </div>
                                  
                                </div>


                              </form>
                              </div>
                              <!--fin home-->

                              <!-- colores -->
                              <div id="menu1" class="tab-pane fade in <?php echo $tabActiveColor ?>">
                                <h3>Colores</h3>
                                <div style="width:10%;">
                                  <form name="gridcolores" method="post">
                                      <?php
                                      echo $koolajax->Render();
                                      echo '<div id="contsGrid">';
                                      if($resultGridColores != null)
                                      {
                                          $hayColorPorDefecto = false;
                                          $contColores = 0;
                                          foreach ($coloresVersion as $color) {
                                            if($color->porDefecto == 1)
                                            {
                                              $hayColorPorDefecto = true;
                                            }
                                          }
                                          foreach ($coloresVersion as $color) {
                                            if (!$hayColorPorDefecto && $contColores == 0) {
                                                $color->ActCampoVersionColor("porDefecto", 1, $color->coloresVersId);
                                                $resultGridColores = $versionColoresObj->GetVersionColorGrid($verId);
                                                                }    
                                                $contColores++;
                                          }
                                          echo $resultGridColores->Render();
                                      }                                
                                      echo '</div>';
                                      ?>
                                  </form>
                              </div>


                              <div class="row">
                                <div class="col-md-9"></div>
                                <div class="col-md-3">
                                    <a href="#fancyVersionColor" class="btn btn-success" id="btnAgregarColor" role="button" onclick="mostrarFancyAgregarColor(0,'', '', '')">Agregar color</a>
                                  </div>
                              </div>
                              </div>
                              <!--fin colores-->

                              <!-- imagen principal -->
                              <div id="menu2" class="tab-pane fade in <?php echo $tabActiveImgP ?>">
                                <!-- <h3>Imagen princial</h3> -->
                                <div class="alert alert-info">
                                  <strong>Recuerde:</strong> 
                                  <ul>
                                    <li>Para agregar una zona, seleccionar el color y tipo de galer&iacute;a y de doble click en la imagen para agregarla.</li>
                                    <li>Para eliminar una zona, ir a la pesta&ntilde;a galer&iacute;as.</li>
                                    <li>Al arrastrar una zona activa abre la edici&oacute;n de la galer&iacute;a seleccionada.</li>
                                    <li>Para abrir la galer&iacute;a de imagenes hacer clic sobre la zona activa deseada.</li>
                                </ul>
                                </div>
                                <br>
                                <div class="row">
                                  <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label>Color:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                <?php
                                if($contColoresVersion > 0)
                                {
                                  $contColores = 0;
                                  $arrZonas = array();
                                  $coloresVersIdSelected = 0;
                                  $galeriaIdSelected = 0;
                                  $imagenAuto = '';
                                  $hayColorPorDefecto = false;
                                  foreach ($coloresVersion as $color) {
                                    if($color->porDefecto == 1)
                                    {
                                      $hayColorPorDefecto = true;
                                    }
                                  }
                                  // echo "<pre>";print_r($coloresVersion);echo "</pre>";

                                    echo '<select class="form-control" name="ip_colores" id="ip_colores" onchange="cambiarColorImgPrincipal(this.value)">';

                                    foreach ($coloresVersion as $color) {                                                                            
                                      $selectedColor = ($color->porDefecto==1) ?'selected':'';    
                                      if($idColorImgPrincipal == "")
                                      {
                                        if($color->porDefecto==1){                                          
                                          $arrZonas = $versionZonasObj->ObtVersionesZonas(false, $color->coloresVersId);
                                          // $selectedColor = 'selected';                                      
                                          // $selectedColor = ($color->porDefecto==1) ?'selected':'';    
                                          $coloresVersIdSelected = $color->coloresVersId;
                                          $imagenAuto = $color->imagenAuto;                                          
                                        }
                                        elseif (!$hayColorPorDefecto && $contColores == 0) {
                                            $arrZonas = $versionZonasObj->ObtVersionesZonas(false, $color->coloresVersId);
                                            $coloresVersIdSelected = $color->coloresVersId;
                                            $imagenAuto = $color->imagenAuto;
                                            $selectedColor = 'selected'; 
                                            // $color->ActCampoVersionColor("porDefecto", 1, $color->coloresVersId);
                                        }                                        
                                      }
                                      elseif ($idColorImgPrincipal == $color->coloresVersId) {                                          
                                          $arrZonas = $versionZonasObj->ObtVersionesZonas(false, $color->coloresVersId);
                                          $selectedColor = ($color->coloresVersId==$idColorImgPrincipal) ?'selected':'';    
                                          // $selectedColor = 'selected';
                                          // $selectedColor = ($color->porDefecto==1) ?'selected':'';
                                          $coloresVersIdSelected = $color->coloresVersId;
                                          $imagenAuto = $color->imagenAuto;
                                      }
                                      echo '<option value="'.$color->coloresVersId.'" '.$selectedColor.'>'.$color->color.'</option>';
                                      $contColores++;
                                    }

                                    echo '</select>';
                                

                                 ?>
                               


                               <?php
                               $infoImagen = getimagesize($imagenAuto);
                                echo '<input type="hidden" id="anchoImg" value="'.$infoImagen[0].'">';
                                echo '<input type="hidden" id="altoImg" value="'.$infoImagen[1].'">';
                                ?>
                               <div class="row">
                                  <div class="col-md-12 col-sm-12 col-xs-12">
                                     <div id="cont_hostpot">
                                        <img src="<?php echo $imagenAuto; ?>" class="">   
                                      </div> 
                                <input type="hidden" id="ctr_hostpot" name="ctr_hostpot" value="<?php echo count($arrZonas); ?>"/>
                                    <?php
                                
                                foreach ($arrZonas as $zona) {
                                  $zonasActivas = $versionZonasActivasObj->ObtVersionesZonasActivas(false, $zona->zonaVersId);

                                  foreach ($zonasActivas as $zonaActiva) {
                                     echo '<input type="hidden" id="zonaactiva_'.$zonaActiva->activaZonaId.'" name="punto_'.$zonaActiva->activaZonaId.'" class="punto_hidden" value="'.$zonaActiva->coordenada.'" data-galeriaid="'.$zona->galeriaId.'">';
                                        }

                                      }
                                
                                if(count($arrZonas) == 0)
                                {
                                    $claseTabGal = 'tabdisabled';
                                }
                                     ?>

                                  </div>
                                </div>

                                <?php                                 
                                // //Obtener los ids de los colores por gralVersId
                                // $versionColoresObj->idsColoresVersPorIdsVersion($verId);
                                // $resultGridZonas = $versionZonasObj->GetVersionZonaGrid($versionColoresObj->idsColoresVers);                                
                                // $resultGridZonas = $versionZonasObj->GetVersionZonaGrid($coloresVersIdSelected);
                                ?>
                                <br><br>
                                <div class="row">
                                  <div class="col-md-12 col-sm-12 col-xs-12">
                                    </div> 
                               </div>
                                <?php

                              }//Fin si hay colores 
                              else
                              {
                                  $claseTabGal = 'tabdisabled';
                              }

                              ?>
                              </div> 
                               </div>
                                 <a href="#fancyVersionZona" class="btn btn-success oculto" id="btnAgregarZona" role="button" onclick="mostrarFancyAgregarZona()">Agregar plan</a>
                              </div>
                              <!--fin imagen principal-->
                              <!--Inicio galerias-->
                              <?php 
                              //Obtener los ids de los colores por gralVersId
                              $versionColoresObj->idsColoresVersPorIdsVersion($verId);
                              $resultGridZonas = $versionZonasObj->GetVersionZonaGrid(($versionColoresObj->idsColoresVers!="")?$versionColoresObj->idsColoresVers:"0");                              
                              ?>
                              <div id="menu6" class="tab-pane fade">                                 
                                 <div class="row">
                                    <div class="col-md-9 col-sm-9 col-xs-9">
                                      <h3>Galer&iacute;as</h3>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                      <!-- <a href="javascript:void(0);" class="btn btn-success btnMenu6 " role="button">Actualizar Galer&iacute;as</a> -->
                                    </div>
                                  </div> 
                                 <div style="width:10%;">
                                  <form name="gridzonas" method="post">
                                      <?php
                                      echo $koolajax->Render();
                                      echo '<div id="contsGrid">';
                                      if($resultGridZonas != null)
                                      {
                                          echo $resultGridZonas->Render();
                                      }                                
                                      echo '</div>';
                                      ?>
                                  </form>
                              </div> 
                              </div>
                              <!--Fin galerias-->

                              <!-- precios -->
                              <div id="menu3" class="tab-pane fade in <?php echo $tabActivePrec; ?>">
                                <h3>Precios</h3>
                                <div class="filtro_estatus">
                                  <form role="form" id="formFiltroCtasPorCobrar" name="formFiltroComunicados" method="get" action="">
                                      <input type="hidden" name="verId" value="<?php echo $verId; ?>" >
                                       <div class="row">
                                          <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                              <label for="fechaDel">Concepto</label>
                                          </div>
                                          <div class="col-md-2 col-sm-2 col-xs-2">
                                              <select class="form-control" name="conceptoPrecioB" id="conceptoPrecioB">
                                                  <option value="">Todos</option>
                                                  <?php
                                                  foreach ($conceptos as $concepto) {
                                                      $selectedConc = '';
                                                      if($conceptoPrecioB === $concepto->concPrecioId)
                                                      {
                                                          $selectedConc = "selected";
                                                      }
                                                      echo '<option value="'.$concepto->concPrecioId.'" '.$selectedConc.'>'.$concepto->concepto.'</option>';
                                                  }
                                                   ?>
                                              </select>
                                          </div>
                                          <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                              <label for="fechaDel">Activo</label>
                                          </div>
                                          <div class="col-md-2 col-sm-2 col-xs-2">
                                              <select class="form-control" name="activoPrecioB" id="activoPrecioB">
                                                  <option value="-1" <?php echo ($activoPrecioB==-1)?"selected":"" ?>>Todos</option>
                                                  <option value="0" <?php echo ($activoPrecioB==0)?"selected":"" ?>>No</option>
                                                  <option value="1" <?php echo ($activoPrecioB==1)?"selected":"" ?>>Si</option>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                              <input type="submit" name="buscarPrecios" value="Buscar" class="btn btn-primary" role="button"/>                                        
                                      </div>
                                  </div>
                                  </form>
                              </div> 
                                <div style="width:10%;">
                                  <form name="gridprecios" method="post">
                                      <?php
                                      echo $koolajax->Render();
                                      echo '<div id="contsGrid">';
                                      if($resultGridPrecios != null)
                                      {
                                          echo $resultGridPrecios->Render();
                                      }                                
                                      echo '</div>';
                                      ?>
                                  </form>
                              </div>
                              </div>
                              <!--fin precios-->

                              <!-- planes -->
                              <div id="menu4" class="tab-pane fade in <?php echo $tabActivePlan; ?>">
                                <h3>Planes</h3>
                                <div class="filtro_estatus">
                                  <form role="form" id="formFiltroCtasPorCobrar2" name="formFiltroComunicados" method="get" action="">
                                      <input type="hidden" name="verId" value="<?php echo $verId; ?>" >
                                       <div class="row">
                                          <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                              <label for="fechaDel">Concepto</label>
                                          </div>
                                          <div class="col-md-2 col-sm-2 col-xs-2">
                                              <select class="form-control" name="conceptoPlanB" id="conceptoPlanB">
                                                  <option value="">Todos</option>
                                                  <?php
                                                  foreach ($conceptosPlanes as $concepto) {
                                                      $selectedConc = '';
                                                      if($conceptoPlanB === $concepto->planId )
                                                      {
                                                          $selectedConc = "selected";
                                                      }
                                                      echo '<option value="'.$concepto->planId  .'" '.$selectedConc.'>'.$concepto->plan.'</option>';
                                                  }
                                                   ?>
                                              </select>
                                          </div>
                                          <div class="text-right form-group col-md-1 col-sm-1 col-xs-1">
                                              <label for="fechaDel">Activo</label>
                                          </div>
                                          <div class="col-md-2 col-sm-2 col-xs-2">
                                              <select class="form-control" name="activoPlanB" id="activoPlanB">
                                                  <option value="-1" <?php echo ($activoPlanB==-1)?"selected":"" ?>>Todos</option>
                                                  <option value="0" <?php echo ($activoPlanB==0)?"selected":"" ?>>No</option>
                                                  <option value="1" <?php echo ($activoPlanB==1)?"selected":"" ?>>Si</option>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                              <input type="submit" name="buscarPlanes" value="Buscar" class="btn btn-primary" role="button"/>                                        
                                      </div>
                                  </div>
                                  </form>
                              </div> 
                                <div style="width:10%;">
                                  <form name="gridplanes" method="post">
                                      <?php
                                      echo $koolajax->Render();
                                      echo '<div id="contsGrid">';
                                      if($resultGridPlanes != null)
                                      {
                                          echo $resultGridPlanes->Render();
                                      }                                
                                      echo '</div>';
                                      ?>
                                  </form>
                              </div>


                              <div class="row">
                                <div class="col-md-9"></div>
                                <div class="col-md-3">
                                    <!-- <a href="#fancyVersionPlan" class="btn btn-success" id="btnAgregarPlan" role="button" onclick="mostrarFancyAgregarPlan(0,'')">Agregar plan</a> -->
                                    <!-- <a href="#fancyVersionPlanPre" class="btn btn-success" id="btnAgregarPlanPre" role="button">Agregar plan/es</a> -->
                                  </div>
                              </div>

                              </div>

                              <!-- fin planes -->

                              <!-- requisitos -->
                              <div id="menu5" class="tab-pane fade">
                                <h3>Requisitos</h3>
                                
                                <div style="width:10%;">
                                  <form name="gridrequisitos" method="post">
                                      <?php
                                      echo $koolajax->Render();
                                      echo '<div id="contsGrid">';
                                      if($resultGridRequisitos != null)
                                      {
                                          echo $resultGridRequisitos->Render();
                                      }                                
                                      echo '</div>';
                                      ?>
                                  </form>
                              </div>


                              <div class="row">
                                <div class="col-md-9"></div>
                                <div class="col-md-3">
                                    <!-- <a href="#fancyVersionReq" class="btn btn-success" id="btnAgregarRequisito" role="button" onclick="mostrarFancyAgregarRequisito(0,'')">Agregar requisito</a> -->
                                    <a href="#fancyVersionReqPre" class="btn btn-success" id="btnAgregarRequisitoPre" role="button">Agregar requisito</a>
                                  </div>
                              </div>




                              </div>
                              <!-- fin requisitos -->
                            </div><!--fin vistas tabs-->
                            </div><!--fin tabs-->

                            <div class="col-md-offset-5 col-md-3">
                                    <a href="versiones.php" class="btn btn-success" role="button">Regresar</a>
                                  </div>


                    </div>  <!--fin contenido col9-->




















                    <!-- fancys-->

                    <div id="fancyVersionZona" style="display:none;width:400px;height:150px;">
                            <form role="form" id="formVersionZona" name="formVersionZona" method="get" action="" >                              
                               <input type="hidden" id="opc_version_zona" value="">
                               <input type="hidden" id="idpunto_version_zona" value="">
                               <input type="hidden" id="x_version_zona" value="">
                               <input type="hidden" id="y_version_zona" value="">                                
                                                                
                                <div class="row">
                                  <div class="col-md-7 col-sm-7 col-xs-7">
                                      <h4><span id="spanTituloZona"></span> Zona</h4>
                                  </div>
                                  <div class="col-md-5 col-sm-5 col-xs-5">
                                      <input type="button" value="Agregar Galer&iacute;a" class="btn btn-info" role="button" id="btn_agregargaleria">
                                  </div>
                                </div>
                                <div class="row" id="cont_nueva_galeria" style="display:none;">
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <label>Nueva Galer&iacute;a</label>
                                    </div>
                                    <div class="col-md-5 col-sm-5 col-xs-5">
                                       <input class="form-control" type="text" id="nueva_galeria">
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                       <input type="button" value="Aceptar" class="btn btn-primary" role="button" id="btn_aceptaragregargaleria">
                                    </div>
                                </div>
                                <div class="row"><br/><br/></div>                                
                                        
                                <div class="row">
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                    <label>Galer&iacute;a:</label>
                                  </div>                                  
                                  <div class="col-md-8 col-sm-8 col-xs-8" id="cont_selgaleria">
                                    <?php
                                    if(count($tiposGalerias) > 0)
                                    {
                                      $contTipoGal = 0;
                                      echo '<select class="form-control" name="ip_galeria" id="ip_galeria">';
                                      foreach ($tiposGalerias as $galeria) {
                                        $selectedGaleria = '';
                                        if($contTipoGal == 0)
                                        {
                                            // $galeriaIdSelected = $galeria->galeriaId;
                                          $selectedGaleria = 'selected';
                                        }
                                        echo '<option value="'.$galeria->galeriaId.'" '.$selectedGaleria.'>'.$galeria->nombre.'</option>';              
                                        $contTipoGal++;
                                      }
                                      echo '</select>';
                                    }
                                    ?>
                                  </div> 
                                </div>
                               <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <input type="button" id="btnGuardarZona" name="btnGuardarPlan" class="btn btn-primary" onclick="guardarVersionZona()" value="Guardar">
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <input type="button" value="Cancelar" class="btn btn-primary" role="button" onclick="cancelarAgregarZona()" >
                                    </div>
                                </div>
                            </form>
                        </div>
                    <div id="fancyVersionPlan" style="display:none;width:800px;height:400px;">
                            <form role="form" id="formVersionPlan" name="formVersionPlan" method="get" action="" >
                                <input type="hidden" id="idVersionGral" name="idVersionGral" value="<?php echo $verId; ?>">
                                 <input type="hidden" id="idVersionPlan" name="idVersionPlan" value="">
                                
                                <h4>Agregar/Editar Plan</h4>
                                
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <label>Tipo plan</label>
                                    </div>
                                    <div class="col-md-9 col-sm-9 col-xs-9">
                                       <select class="form-control required" name="vp_idtipoplan" id="vp_idtipoplan">
                                        <option value="">Seleccione...</option>

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

                        <!-- Mostrar todos los planes -->
                        <?php                           
                          $colVerPlanes = $versionPlanesObj->ObtVersionesPlanes(true, "", 1, 1);
                          // echo "<pre>"; print_r($colVerPlanes);  echo "</pre>";
                        ?>
                        <div id="fancyVersionPlanPre" style="display:none;width:800px;height:400px;">
                            <form role="form" id="formVersionPlanPre" name="formVersionPlanPre" method="get" action="" >
                                <input type="hidden" id="planes_pre_verId" value="<?php echo $verId; ?>" >

                                <h4>Agregar Plan/es</h4>                                
                                <?php if(count($colVerPlanes)>0){ ?>
                                <div class="row contListaPlanPre">       
                                    <?php foreach ($colVerPlanes as $elem) { ?>
                                    <div class="col-sm-4">
                                        <br/>
                                        <div class="card">
                                          <div class="card-body">
                                            <h4 class="card-title"><b><?php echo $elem->conceptoPlan;?></b></h4>
                                            <div id="caracplan_<?php echo $elem->planVersId;?>" style="display:none;"><?php echo $elem->caracteristicas;?></div>                                            

                                            <div class="custom-control custom-checkbox">
                                              <span><a href="javascript:void(0);" class="badge badge-light my_popup_open" onclick="verDetallePopup(<?php echo $elem->planVersId;?>, 'plan');">Ver detalle</a></span>

                                              <input type="checkbox" class="selectCheck" value="<?php echo $elem->planVersId;?>" id="checkplan_<?php echo $elem->planVersId;?>" >
                                              <label class="custom-control-label" for="checkplan_<?php echo $elem->planVersId;?>">Seleccionar</label>
                                            </div>

                                          </div>
                                        </div>                                      
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                                                                
                                <br/><br/>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <input type="button" role="button" id="btnGuardarPlanPre" name="btnGuardarPlanPre" class="btn btn-primary" onclick="guardarVersionPlanPre()" value="Agregar seleccionados">                                        
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <!-- <input type="button" value="Cancelar" class="btn btn-primary" role="button" onclick="parent.$.fancybox.close();" > -->
                                    </div>
                                </div>
                                  
                            </form>
                        </div>

                        
                        <div id="fancyVersionReq" style="display:none;width:800px;height:400px;">
                            <form role="form" id="formVersionReq" name="formVersionReq" method="get" action="" >
                                <input type="hidden" id="vr_idVersionGral" name="vr_idVersionGral" value="<?php echo $verId; ?>">
                                 <input type="hidden" id="idVersionReq" name="idVersionReq" value="">
                                
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
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <input type="button" role="button" id="btnGuardarReq" name="btnGuardarReq" class="btn btn-primary" onclick="guardarVersionReq()" value="Guardar">
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <input type="button" value="Cancelar" class="btn btn-primary" role="button" onclick="parent.$.fancybox.close();" >
                                    </div>
                                </div>
                                
                            </form>
                        </div>


                        <!-- Mostrar todos los requisitos -->
                        <?php                           
                          $colVerRequisitos = $versionRequisitosObj->ObtVersionesRequisitos(true, "", 1, 1);
                          // echo "<pre>"; print_r($colVerRequisitos);  echo "</pre>";
                        ?>
                        <div id="fancyVersionReqPre" style="display:none;width:800px;height:400px;">
                            <form role="form" id="formVersionReqPre" name="formVersionReqPre" method="get" action="" >
                                <input type="hidden" id="req_pre_verId" value="<?php echo $verId; ?>" >

                                <h4>Agregar Requisito/s</h4>                                
                                <?php if(count($colVerRequisitos)>0){ ?>
                                <div class="row contListaReqPre">       
                                    <?php foreach ($colVerRequisitos as $elem) { ?>
                                    <div class="col-sm-4">
                                        <br/>
                                        <div class="card">
                                          <div class="card-body">
                                            <h4 class="card-title"><b><?php echo $elem->concepto;?></b></h4>
                                            <div id="caracreq_<?php echo $elem->reqVersId;?>" style="display:none;"><?php echo $elem->caracteristicas;?></div>                                            

                                            <div class="custom-control custom-checkbox">
                                              <span><a href="javascript:void(0);" class="badge badge-light my_popup_open" onclick="verDetallePopup(<?php echo $elem->reqVersId;?>, 'requisito');">Ver detalle</a></span>

                                              <input type="checkbox" class="selectCheck" value="<?php echo $elem->reqVersId;?>" id="checkreq_<?php echo $elem->reqVersId;?>" >
                                              <label class="custom-control-label" for="checkreq_<?php echo $elem->reqVersId;?>">Seleccionar</label>
                                            </div>

                                          </div>
                                        </div>                                      
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                                                                
                                <br/><br/>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <input type="button" role="button" id="btnGuardarReqPre" name="btnGuardarReqPre" class="btn btn-primary" onclick="guardarVersionReqPre()" value="Agregar seleccionados">                                        
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <!-- <input type="button" value="Cancelar" class="btn btn-primary" role="button" onclick="parent.$.fancybox.close();" > -->
                                    </div>
                                </div>
                                  
                            </form>
                        </div>

                        <!-- Mostrar contenido completo -->
                        <div id="my_popup" style="background:#fff;padding:30px;width:50% !important;">
                          <div id="contenidodin_popup"></div>
                          <button class="my_popup_close">Cerrar</button>
                        </div>


                        <div id="fancyVersionColor" style="display:none;width:500px;height:200px;">
                            <form role="form" id="formVersionColor" name="formVersionColor" method="post" action="" enctype="multipart/form-data">
                                <input type="hidden" id="vc_idVersionGral" name="vc_idVersionGral" value="<?php echo $verId; ?>">
                                 <input type="hidden" id="idVersionColor" name="idVersionColor" value="">
                                
                                <h4>Agregar/Editar Color</h4>
                                
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <label>Color</label>
                                    </div>
                                    <div class="col-md-8 col-sm-8 col-xs-8">
                                       <input class="form-control required" type="text" name="vc_color" id="vc_color" value="" maxlength="150">
                                    </div>
                                </div>


                                <div class="row">
                                  <div class="text-right form-group col-md-4 col-sm-4 col-xs-4">
                                      <label for="imagen">Imagen auto:</label>
                                  </div>
                                  <div class="col-md-8 col-sm-8 col-xs-8" id="divImgAuto">
                                    <!-- accept="image/*"  -->
                                     <div id="divMostrarImgAuto"></div>                                      
                                                    
                                        <input type="file" name="imagen" id="vc_imagena" size="10" class="form-control required" accept="image/gif, image/jpg, image/jpeg, image/png" />
                                    
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="text-right form-group col-md-4 col-sm-4 col-xs-4">
                                      <label for="imagen">Imagen color:</label>
                                  </div>
                                  <div class="col-md-8 col-sm-8 col-xs-8" id="divImgColor">
                                    <!-- accept="image/*"  -->
                                        <div id="divMostrarImgColor"></div>
                                        <input type="file" name="imagenc" id="vc_imagenc" size="10" class="form-control required" accept="image/gif, image/jpg, image/jpeg, image/png" />
                                    
                                  </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <input type="button" role="button" id="btnGuardarColor" name="btnGuardarColor" class="btn btn-primary" onclick="guardarVersionColor()" value="Guardar">
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <input type="button" value="Cancelar" class="btn btn-primary" role="button" onclick="parent.$.fancybox.close();" >
                                    </div>
                                </div>
                                
                            </form>
                        </div>





                    <!-- fin fancys-->
                    <!-- <input type="hidden" id="claseTabGal" value="<?php echo $claseTabGal ?>"> -->
                    <input type="hidden" id="claseTabGal" value="">
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="panel-footer">
            <?php echo getPiePag(true); ?>
        </div>
    </footer>
<script src="../js/init_interact.js"></script>
</body>
</html>
