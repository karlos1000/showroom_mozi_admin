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
include_once '../brules/gamaModelosObj.php';
include_once '../brules/catGaleriasObj.php';
include_once '../brules/galeriaModelosObj.php';
include_once '../brules/videoModelosObj.php';

//establecer la zona horaria
$dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
$dateTime = $dateByZone->format('Y-m-d H:i:s'); //fecha Actual

$gMObj = new gamaModelosObj();
$catGaleriasObj = new catGaleriasObj();
$galeriaMObj = new galeriaModelosObj();
$videoMObj = new videoModelosObj();

$idM = (isset($_GET['idM'])) ?$_GET['idM'] :0;
$titulo = "";
$resultGrid = null;

$tiposGalerias = $catGaleriasObj->ObtTodosCatGalerias();
//Obtener datos del modelo
$gMObj->ObtDatosGModeloPorId($idM);
$titulo = $gMObj->modelo;

//Obtener los datos del grid
$resultGrid = $videoMObj->GetVideosModeloGrid($idM);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Videos Modelos - <?php echo $titulo; ?></title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">-->
    <link href="../css/style.css" rel="stylesheet" type="text/css" />
    <link href="../css/styleupload.css" rel="stylesheet" type="text/css" />
    <link href="../js/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css" />
    <link href="../css/alertify.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../css/themes/semantic.min.css"/>
    <style>
        .defaultKUL .kulClearAll{display:none;} 
        .defaultKUL .kulUploadAll{display:none;}
    </style>

    <link href="../js/uploader-master/css/jquery.dm-uploader.min.css" rel="stylesheet" type="text/css" />
    <link href="../js/uploader-master/styles.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
    <!--<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>-->
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>    
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="../js/fancybox/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="../js/accounting.min.js"></script> 
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/additional-methods.js"></script>
    <script type="text/javascript" src="../js/alertify.js"></script>
    <script>var tieneAlertify = true;</script>
    <!--<script type="text/javascript" src="../js/uploadmultiVGM.js"></script>-->
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
                        <div class="row">
                          <div class="col-md-9">                            
                            <h1 class="titulo">Videos Modelo - <?php echo $titulo; ?></h1>
                          </div>
                          <div class="col-md-3">
                            <input class="btn btn-success" name="buttonclose" type="button" onclick="window.close();" value="Cerrar" />
                          </div>
                        </div>
                        
                        <!-- <div class="row">
                          <div class="col-md-4 col-sm-4 col-xs-4">
                            <label>Galer&iacute;a:</label>
                          </div>                                  
                          <div class="col-md-4 col-sm-4 col-xs-4" id="cont_selgaleria">
                            <?php
                            if(count($tiposGalerias) > 0)
                            {                                  
                              echo '<select class="form-control" name="id_galeria" id="id_galeria">';
                              echo '<option value="">--Seleccionar--</option>';
                              foreach ($tiposGalerias as $galeria) {
                                $selectedGaleria = '';                                    
                                echo '<option value="'.$galeria->galeriaId.'" '.$selectedGaleria.'>'.$galeria->nombre.'</option>';                                                  
                              }
                              echo '</select>';
                            }
                            ?>
                          </div> 
                        </div> -->                        
                        
                        <div class="alert alert-info" role="alert">
                          <b>Nota</b>: Solo se permiten subir archivos MP4
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                              
                              <!-- Our markup, the important part here! -->
                              <div id="drag-and-drop-zone" class="dm-uploader p-5">
                                <h3 class="mb-5 mt-5 text-muted">Arrastre y suelte el archivo aquí</h3>

                                <div class="btn btn-primary btn-block mb-5">
                                    <span>Abrir el buscador de archivos</span>
                                    <input type="file" title='Click to add Files' />
                                </div>
                              </div><!-- /uploader -->

                            </div>
                            <div class="col-md-6 col-sm-12">
                              <div class="card h-100">
                                <div class="card-header">
                                  Lista de archivos
                                </div>

                                <ul class="list-unstyled p-2 d-flex flex-column col" id="files">
                                  <li class="text-muted text-center empty"></li>
                                </ul>
                              </div>
                            </div>
                        </div><!-- /file list -->   

                        <!-- <div class="row">
                            <div class="col-12">
                                 <div class="card h-100">
                                    <div class="card-header">
                                      Mensajes de depuración
                                  </div>

                                  <ul class="list-group list-group-flush" id="debug">
                                      <li class="list-group-item text-muted empty"></li>
                                  </ul>
                                </div>
                            </div>
                        </div> -->
                        


                        <!-- <div id="dragAndDropFiles" class="uploadArea">
                            <h1>Arrastra las imagenes aqu&iacute; </h1>
                        </div> -->
                        <!-- <form name="demoFiler" id="demoFiler" enctype="multipart/form-data" method="post"> -->
                            <!-- <input type="file" name="multiUpload" id="multiUpload" multiple  accept="image/gif, image/jpg, image/jpeg, image/png" />   -->
                            
                            <!-- <div id="idUpbtn" >
                                <input type="submit" name="submitHandler" id="submitHandler" value="Subir imagenes" class="buttonUpload" />    
                            </div>
                            <br/> -->
                            
                            <div>
                                <!-- <div class="row">
                                  <div class="col-md-offset-9 col-md-3">                            
                                    <input class="btn btn-success" id="borradoMultiple" type="button" value="Borrado Multiple" />
                                  </div>
                                </div> -->
                                <br/>
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
                                                                            
                            <input type="hidden" id="urlIcons" value="../images"  /> 
                            <input type="hidden" id="idGM" value="<?php echo $idM;?>" />                            
                            <input type="hidden" id="countTotalImgs" value="0" />
                        <!-- </form> -->
                        <div class="progressBar">
                            <div class="status"></div>
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


    <div id="fancyImgGaleria" style="display:none;width:800px;height:400px;">

        <form role="form" id="formImagenGal" name="formImagenGal" method="get" action="" enctype="multipart/form-data">                              
            <input type="hidden" id="idActImg" value="0">

            <!-- <input type="hidden" id="idimagen_imggal" value="">
            <input type="hidden" id="zonaVersId_imggal" value=""> -->
            
                                
            <h4><span id="spanTituloZona"></span> Editar informaci&oacute;n imagen</h4>
            
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-3">
                    <label>Galer&iacute;a</label>
                </div>
                <div class="col-md-9 col-sm-9 col-xs-9">
                    <?php
                    if(count($tiposGalerias) > 0)
                    {                                  
                      echo '<select class="form-control" name="act_id_galeria" id="act_id_galeria">';                      
                      foreach ($tiposGalerias as $galeria) {                        
                        echo '<option value="'.$galeria->galeriaId.'">'.$galeria->nombre.'</option>';                                                  
                      }
                      echo '</select>';
                    }
                    ?>
                </div> 
            </div>
                                
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-3">
                    <label>Titulo</label>
                </div>
                <div class="col-md-9 col-sm-9 col-xs-9">
                <input type="text" name="titulo_imggal" id="titulo_imggal" value="" class="form-control">
                </div> 
            </div>
        
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-3">
                    <label>Descripci&oacute;n</label>
                </div>
                <div class="col-md-9 col-sm-9 col-xs-9" id="div_desc_imggal">
                <textarea class="form-control" rows="4" name="descripcion_imggal" id="descripcion_imggal"></textarea>
                </div> 
            </div>
            <br/>
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-3"></div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <input class="form-control" type="file" name="imgGalSubir" id="imgGalSubir" accept="image/gif, image/jpg, image/jpeg, image/png">
                    <input type="hidden" name="nombreImagenGalAct" id="nombreImagenGalAct"  value="">
                </div>
                <div class="col-md-3 col-sm-3 col-xs-3">
                    <img height="100px" src="" id="imagen_imggal" name="imagen_imggal">
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-3">
                    <label>Precio</label>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-3">
                <input type="text" name="precio_imggal" id="precio_imggal" value="" class="form-control text-right" onchange="muestraValorMoneda(this.id, this.value)">
                </div> 
            </div>
            <br/>
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <input type="button" id="btnGuardarImgGal" name="btnGuardarImgGal" class="btn btn-primary" onclick="guardarImgGalGamaModelo()" value="Guardar">
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <input type="button" value="Cancelar" class="btn btn-primary" role="button" onclick="parent.$.fancybox.close();" id="btnCancelarEdImgGal">
                </div>
            </div>
                                
                                
        </form>
    </div>

    <!-- vista para borrado multiple -->
    <input type="hidden" id="idVistaCheck" value="v_galmodelos">


    
    <script src="../js/uploader-master/js/jquery.dm-uploader.min.js"></script>
    <script src="../js/uploader-master/demo-ui.js"></script>
    <script src="../js/uploader-master/demo-config.js"></script>    
    
    <!-- File item template -->    
    <script type="text/html" id="files-template">
      <li class="media">
        <div class="media-body mb-1">
          <p class="mb-2">
            <strong>%%filename%%</strong> - Status: <span class="text-muted">Waiting</span>
          </p>
          <div class="progress mb-2">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
              role="progressbar"
              style="width: 0%" 
              aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            </div>
          </div>
          <hr class="mt-1 mb-1" />
        </div>
      </li>
    </script>
    
    <!-- Debug item template -->        
    <script type="text/html" id="debug-template">
      <li class="list-group-item text-%%color%%"><strong>%%date%%</strong>: %%message%%</li>
    </script>

</body>
</html>
