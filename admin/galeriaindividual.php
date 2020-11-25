<?php
session_start();
$idRol = $_SESSION['idRol'];
switch ($idRol) {
    case 1: $rol = true; break;
    default: $rol = false; break;
}
if($_SESSION['status']!= "ok" || $rol!=true)
        header("location: logout.php");

include_once '../common/screenPortions.php';
include_once '../brules/KoolControls/KoolGrid/koolgrid.php';
require_once '../brules/KoolControls/KoolAjax/koolajax.php';
require_once '../brules/KoolControls/KoolCalendar/koolcalendar.php';
include_once '../brules/KoolControls/KoolGrid/ext/datasources/MySQLiDataSource.php';
$localization = "../brules/KoolControls/KoolCalendar/localization/es.xml";
include_once '../brules/versionZonasActivasObj.php';
include_once '../brules/versionZonasObj.php';
include_once '../brules/versionColoresObj.php';
include_once '../brules/catGaleriasObj.php';
include_once '../brules/catActualizacionesObj.php';
include '../brules/utilsObj.php';
//establecer la zona horaria
$dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
$dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual

$versionZonasActivasObj = new versionZonasActivasObj();
$versionZonasObj = new versionZonasObj();
$versionColoresObj = new versionColoresObj();
$catGaleriasObj = new catGaleriasObj();

$resultGrid = null;

$titulo = "";
$tipoAviso = '';

if(isset($_GET["zonaVersId"]))
{
    if($_GET["zonaVersId"] > 0)
    {
        $versionZonasObj->versionZonaPorId($_GET["zonaVersId"]);
        $versionZonasActivasObj->versionZonaActivaPorZona($versionZonasObj->zonaVersId);
        
        $versionColoresObj->versionColorPorId($versionZonasObj->coloresVersId);
        $catGaleriasObj->ObtGaleriaPorId($versionZonasObj->galeriaId);
        $titulo = $versionColoresObj->color." (".$catGaleriasObj->nombre.")";
        
        if(isset($_POST["ig_descripcion"]))
        {
            $paramsImg = (object)array("folderDestino"=>"../upload/zonasactivasImg/", "arrExt"=>array("jpg","png","jpeg","gif"));
            $resArrImg = subirImagen($_FILES, $paramsImg);

            if($resArrImg->result == true)
            {
                $res = $versionZonasActivasObj->actualizaJsonImagenes($versionZonasActivasObj->activaZonaId, '../upload/zonasactivasImg/'.$resArrImg->nImg, $versionZonasActivasObj->imagenes, $_POST["ig_titulo"], $_POST["ig_descripcion"]);
                $tipoAviso = ($res > 0)?1:0;

                $actualizacionesObj = new catActualizacionesObj();
                $actualizacionesObj->updActualizacion("version_zonas");
                $actualizacionesObj = new catActualizacionesObj();
                $actualizacionesObj->updActualizacion("version_zonasactivas");
            }
            else
            {
                $tipoAviso = 3;
            }
        }


        

        $resultGrid = $versionZonasActivasObj->GetVersionZonaActivaGrid($_GET["zonaVersId"]);
    }
    else
    {
        header("location: versiones.php");
    }
}
else
{
    header("location: versiones.php");
}





?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Editar galeria - <?php echo $titulo; ?></title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>    
    <link href="../css/style.css" rel="stylesheet" type="text/css" />
    <link href="../js/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css" />
    <link href="../css/alertify.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../css/themes/semantic.min.css"/>

    <script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>    
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="../js/fancybox/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/additional-methods.js"></script>
    <script type="text/javascript" src="../js/alertify.js"></script>
    <script>var tieneAlertify = true;</script>
    <script type="text/javascript" src="../js/functions.js"></script>
    <script type="text/javascript">
      $( document ).ready(function() {
        mostrarAlertify();       
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
                    <div class="colmenu col-md-3 menu_bg">
                        <?php echo getNav($menu); ?>
                    </div>
                    <div class="col-md-9">
                        <h1 class="titulo">Editar galeria - <?php echo $titulo; ?></h1>  
                          <input type="hidden" id="tipoAviso" value="<?php echo $tipoAviso?>">

                        <!-- <ol class="breadcrumb">
                          <li><a href="index.php">Inicio</a></li>
                          <li><a href="busqueda.php">Busqueda</a></li>                          
                          <li class="active">Subir documentos</li>
            
                        </ol>    -->  
                        <input class="btn btn-success" name="buttonclose" type="button" onclick="window.close();" value="Cerrar galeria" /> 
                        
                        <br><br>
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
                        <div class="row">
                                <div class="col-md-9"></div>
                                <div class="col-md-3">
                                    <a href="#fancyAgregarImgGal" class="btn btn-success" id="btnAgregarAgregarImgGal" role="button" onclick="mostrarFancyAgregarImgGal(0,'')">Agregar imagen</a>
                                  </div>
                              </div>
                    </div>                    
                </div>
            </div>
        </div>
    </section>

    <div id="fancyImgGaleria" style="display:none;width:300px;height:150px;">

        <form role="form" id="formImagenGal" name="formImagenGal" method="get" action="" enctype="multipart/form-data">                              
            <input type="hidden" id="idimagen_imggal" value="">
            <input type="hidden" id="zonaVersId_imggal" value="">
            
                                
            <h4><span id="spanTituloZona"></span> Editar informaci&oacute;n imagen</h4>

                                
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <label>Titulo</label>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-8">
                <input type="text" name="titulo_imggal" id="titulo_imggal" value="" class="form-control">
                </div> 
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <label>Descripci&oacute;n</label>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-8">
                <textarea class="form-control" rows="4" name="descripcion_imggal" id="descripcion_imggal"></textarea>
                </div> 
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <img width="100px" src="" id="imagen_imggal">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <input type="button" id="btnGuardarImgGal" name="btnGuardarImgGal" class="btn btn-primary" onclick="guardarImgGal()" value="Guardar">
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <input type="button" value="Cancelar" class="btn btn-primary" role="button" onclick="parent.$.fancybox.close();" >
                </div>
            </div>
                                
                                
        </form>
    </div>

    <div id="fancyAgregarImgGal" style="display:none;width:500px;height:200px;">
        <form role="form" id="formAgregarImgGal" name="formAgregarImgGal" method="post" action="" enctype="multipart/form-data">
            <input type="hidden" id="ig_idimagen" value="">
            <input type="hidden" id="ig_zonaVersId" value="">
            
            <h4>Agregar imagen a la galeria</h4>
            
            <div class="row">
                <div class="text-right col-md-4 col-sm-4 col-xs-4">
                    <label>T&iacute;tulo: </label>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-8">
                   <input class="form-control" type="text" name="ig_titulo" id="ig_titulo" value="" maxlength="150">
                </div>
            </div>

            <div class="row">
                <div class="text-right col-md-4 col-sm-4 col-xs-4">
                    <label>Descripci&oacute;n: </label>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-8">
                   <textarea class="form-control" name="ig_descripcion" id="ig_descripcion"></textarea>
                </div>
            </div>


            <div class="row">
              <div class="text-right form-group col-md-4 col-sm-4 col-xs-4">
                  <label for="imagen">Imagen:</label>
              </div>
              <div class="col-md-8 col-sm-8 col-xs-8" id="divImgGal">
                <!-- accept="image/*"  -->
                 <div id="divMostrarImgGal"></div>                                      
                                
                    <input type="file" name="imagen" id="ig_imagen" size="10" class="form-control required" accept="image/gif, image/jpg, image/jpeg, image/png" />
                
              </div>
            </div>

           


            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <input type="button" role="button" id="btnAgregarImgGal" name="btnAgregarImgGal" class="btn btn-primary" onclick="agregarImgGal()" value="Guardar">
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <input type="button" value="Cancelar" class="btn btn-primary" role="button" onclick="parent.$.fancybox.close();" >
                </div>
            </div>
            
        </form>
    </div>

    <footer>
        <div class="panel-footer">
            <?php echo getPiePag(true); ?>
        </div>
    </footer>
</body>
</html>
