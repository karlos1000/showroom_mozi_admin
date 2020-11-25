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
$prosObj = new catProspectosObj();
$usuariosObj = new usuariosObj();
$agenciaObj = new catAgenciasObj();

$idP = (isset($_GET['idP'])) ?$_GET['idP'] :0;
//Obtener datos del modelo
$prosObj->datosProspectoPorId($idP);
$datosJson = json_decode($prosObj->datosJson);
$arrMotivocompra = (object)array("vi_valordinero"=>"Valor por dinero", "vi_seguridad"=>"Seguridad", "vi_estatuspre"=>"Estatus/Prestigio", "vi_apdeportiva"=>"Apariencia Deportiva",
                         "vi_desempenio"=>"Desempe&ntilde;o", "vi_confort"=>"Confort", "vi_disenio"=>"Dise&ntilde;o", "vi_respaldomarca"=>"Respaldo Marca VW", 
                         "vi_economia"=>"Econom&iacute;a"); //, "vi_otro_mot"=>"Otro"

$arrReqFun = (object)array("vi_absebd"=>"ABS/EBD", "vi_aireacondicionado"=>"Aire Acondicionado", "vi_rastreador"=>"Rastreador", "vi_remolque"=>"Remolque", "vi_ventelectricas"=>"Ventanas El&eacute;ctricas",
                           "vi_navsatelital"=>"Navegaci&oacute;n", "vi_quemacocos"=>"Quema Cocos", "vi_estandar"=>"Estandar", "vi_automatico"=>"Automat&iacute;co");

$arrEquiFun = (object)array("vi_bluetooth"=>"Bluetooth", "vi_rines"=>"Rines", "vi_loderas"=>"Loderas", "vi_portaequipajes"=>"Portaequipajes", "vi_pantiasalto"=>"Pel&iacute;cula Anti-asalto",
                            "vi_lucesxenon"=>"Luces de Xenon", "vi_vidriospol"=>"Vidrios Polarizados", "vi_ganchoremolque"=>"Gancho de Remolque", "vi_farosantiniebla"=>"Faros Anti-niebla",
                            "vi_sensoresreversa"=>"Sensores de Reversa",
                           );
// echo "<pre>";
// print_r($datosJson);
// echo "</pre>";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Detalle prospecto</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>    
    <link href="../css/style.css" rel="stylesheet" type="text/css" />

    <!-- <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"> -->
    <link href="../css/alertify.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="../css/alertify.default.css" rel="stylesheet" type="text/css" /> -->
    <link rel="stylesheet" href="../css/themes/semantic.min.css"/>
    <script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/additional-methods.js"></script>
    <!-- <script type="text/javascript" src="../js/dosjquery.validate.min.js"></script> -->
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
                        <h1 class="titulo">Detalle Prospecto</h1>
                        <input type="hidden" id="tipoAviso" value="<?php echo $tipoAviso?>">
                        <div class="cont_registrogamamodelo">
                          <form role="form" id="formRegGamaModelo" name="formRegGamaModelo" method="post" action="" enctype="multipart/form-data">                                                           
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="fechaAlta">Fecha Alta:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="fechaAlta" name="fechaAlta" value="<?php echo $prosObj->fechaAlta;?>" class="form-control" readonly>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="agencia">Agencia:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <?php 
                                        $agencia = "";
                                        $usrArr = $usuariosObj->obtTodosUsuarios();                                                    
                                        foreach($usrArr as $usrTmp)
                                        {   
                                            if($usrTmp->idUsuario==$datosJson->idVendedor){
                                              $agenciaObj->AgenciaPorId($usrTmp->agenciaId);
                                              $agencia = $agenciaObj->nombre;
                                            }
                                        }   
                                      ?>
                                      <input type="text" id="agencia" name="agencia" value="<?php echo $agencia;?>" class="form-control" readonly>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="idVendedor">Vendedor:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <?php 
                                        $vendedor = "";
                                        $usrArr = $usuariosObj->obtTodosUsuarios();                                                    
                                        foreach($usrArr as $usrTmp)
                                        {   
                                            if($usrTmp->idUsuario==$datosJson->idVendedor){
                                              $vendedor = $usrTmp->nombre;
                                            }
                                        }   
                                      ?>
                                      <input type="text" id="idVendedor" name="idVendedor" value="<?php echo $vendedor;?>" class="form-control" readonly>
                                  </div>
                              </div>
                              <div class="new_line"><h2>Informaci&oacute;n del cliente</h2></div>
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="ic_nombre">Nombre:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="ic_nombre" name="ic_nombre" value="<?php echo $prosObj->nombre;?>" class="form-control" readonly>
                                  </div>
                              </div>
                              <!-- <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="ic_direccion">Direcci&oacute;n:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="ic_direccion" name="ic_direccion" value="<?php echo $prosObj->direccion;?>" class="form-control" readonly>
                                  </div>
                              </div> -->
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="ic_telefono">Tel&eacute;fono:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="ic_telefono" name="ic_telefono" value="<?php echo $prosObj->telefono;?>" class="form-control" readonly>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="ic_email">Email:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="ic_email" name="ic_email" value="<?php echo $prosObj->email;?>" class="form-control" readonly>
                                  </div>
                              </div>
                              <!-- <div class="new_line"><h2>Datos del veh&iacute;culo actual</h2></div>

                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="va_marca">Marca:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="va_marca" name="va_marca" value="<?php echo $datosJson->va_marca;?>" class="form-control" readonly>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="va_modelo">Modelo:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="va_modelo" name="va_modelo" value="<?php echo $datosJson->va_modelo;?>" class="form-control" readonly>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="va_anio">A&ntilde;o:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="va_anio" name="va_anio" value="<?php echo $datosJson->va_anio;?>" class="form-control" readonly>
                                  </div>
                              </div>                              
                              <div class="row">
                                 <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                     <label for="va_intervehiculo">¿Desea intercambiar este veh&iacute;culo?:</label>
                                 </div>
                                 <div class="col-md-6 col-sm-6 col-xs-6">
                                     <select id="va_intervehiculo" name="va_intervehiculo" class="form-control required" disabled>
                                       <option value=""></option>
                                       <option value="si" <?php echo ($datosJson->va_intervehiculo=="si")?"selected":"";?> >Si</option>
                                       <option value="no" <?php echo ($datosJson->va_intervehiculo=="no")?"selected":"";?> >No</option>
                                   </select>
                                 </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="va_financiado">¿Cómo fue/es financiado este veh&iacute;culo?:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="va_financiado" name="va_financiado" value="<?php echo $datosJson->va_financiado;?>" class="form-control" readonly>
                                  </div>
                              </div>
                              <div class="row">
                                 <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                     <label for="va_usovehiculo">¿Uso del veh&iacute;culo actual?:</label>
                                 </div>
                                 <div class="col-md-6 col-sm-6 col-xs-6">
                                     <select id="va_usovehiculo" name="va_usovehiculo" class="form-control required" disabled>
                                       <option value=""></option>
                                       <option value="particular" <?php echo ($datosJson->va_usovehiculo=="particular")?"selected":"";?> >Particular</option>
                                       <option value="profesional" <?php echo ($datosJson->va_usovehiculo=="profesional")?"selected":"";?> >Profesional</option>
                                       <option value="ambos" <?php echo ($datosJson->va_usovehiculo=="ambos")?"selected":"";?> >Ambos</option>
                                   </select>
                                 </div>
                               </div>
                               <div class="row">
                                 <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                     <label for="va_dondeusavehiculo">¿Donde se usa el veh&iacute;culo actual?:</label>
                                 </div>
                                 <div class="col-md-6 col-sm-6 col-xs-6">
                                     <select id="va_dondeusavehiculo" name="va_dondeusavehiculo" class="form-control required" disabled>
                                       <option value=""></option>
                                       <option value="ciudad" <?php echo ($datosJson->va_dondeusavehiculo=="ciudad")?"selected":"";?> >Ciudad</option>
                                       <option value="carretera" <?php echo ($datosJson->va_dondeusavehiculo=="carretera")?"selected":"";?> >Carretera</option>
                                       <option value="ambos" <?php echo ($datosJson->va_dondeusavehiculo=="ambos")?"selected":"";?> >Ambos</option>
                                   </select>
                                 </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="va_equivalor">¿Qué equipamiento y caracter&iacute;sticas valora de su veh&iacute;culo actual?:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="va_equivalor" name="va_equivalor" value="<?php echo $datosJson->va_equivalor;?>" class="form-control" readonly>
                                  </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="va_cambiarvehiculo">¿Porque raz&oacute;n quiere cambiar su veh&iacute;culo?:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="va_cambiarvehiculo" name="va_cambiarvehiculo" value="<?php echo $datosJson->va_cambiarvehiculo;?>" class="form-control" readonly>
                                  </div>
                               </div> -->

                              <div class="new_line"><h2>Veh&iacute;culo de inter&eacute;s</h2></div>                              
                              <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="vi_modelo">Modelo:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="vi_modelo" name="vi_modelo" value="<?php echo $datosJson->vi_modelo;?>" class="form-control" readonly>
                                  </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="vi_anio">Versi&oacute;n:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="vi_anio" name="vi_anio" value="<?php echo $datosJson->vi_anio;?>" class="form-control" readonly>
                                  </div>
                               </div>
                               <!-- <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="vi_uso">¿Qué uso tendra el nuevo autom&oacute;vil?:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="vi_uso" name="vi_uso" value="<?php echo $datosJson->vi_uso;?>" class="form-control" readonly>
                                  </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="vi_precio">Precio dispuesto a pagar:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="vi_precio" name="vi_precio" value="<?php echo $datosJson->vi_precio;?>" class="form-control" readonly>
                                  </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label>Otros modelos dentro del rango de inter&eacute;s:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="vi_modelo1" name="vi_modelo1" value="<?php echo $datosJson->vi_modelo1;?>" class="form-control" readonly>
                                      <input type="text" id="vi_modelo2" name="vi_modelo2" value="<?php echo $datosJson->vi_modelo2;?>" class="form-control" readonly>
                                      <input type="text" id="vi_modelo3" name="vi_modelo3" value="<?php echo $datosJson->vi_modelo3;?>" class="form-control" readonly>
                                  </div>
                               </div>
                               <div class="row">
                                 <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                     <label for="vi_pruebamanejo">¿Requiere una prueba de manejo?:</label>
                                 </div>
                                 <div class="col-md-6 col-sm-6 col-xs-6">
                                     <select id="vi_pruebamanejo" name="vi_pruebamanejo" class="form-control required" disabled>
                                       <option value=""></option>
                                       <option value="si" <?php echo ($datosJson->vi_pruebamanejo=="si")?"selected":"";?> >Si</option>
                                       <option value="no" <?php echo ($datosJson->vi_pruebamanejo=="no")?"selected":"";?> >No</option>
                                   </select>
                                 </div>
                               </div>
                               <div class="row">
                                 <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                     <label for="vi_financiamiento">¿Requiere financiamiento?:</label>
                                 </div>
                                 <div class="col-md-6 col-sm-6 col-xs-6">
                                     <select id="vi_financiamiento" name="vi_financiamiento" class="form-control required" disabled>
                                       <option value=""></option>
                                       <option value="si" <?php echo ($datosJson->vi_financiamiento=="si")?"selected":"";?> >Si</option>
                                       <option value="no" <?php echo ($datosJson->vi_financiamiento=="no")?"selected":"";?> >No</option>
                                   </select>
                                 </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="vi_fpruebamanejo">Fecha de la prueba de manejo:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="vi_fpruebamanejo" name="vi_fpruebamanejo" value="<?php echo $datosJson->vi_fpruebamanejo;?>" class="form-control" readonly>
                                  </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label>Color preferido:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="vi_color1" name="vi_color1" value="<?php echo $datosJson->vi_color1;?>" class="form-control" readonly>
                                      <input type="text" id="vi_color2" name="vi_color2" value="<?php echo $datosJson->vi_color2;?>" class="form-control" readonly>
                                      <input type="text" id="vi_color3" name="vi_color3" value="<?php echo $datosJson->vi_color3;?>" class="form-control" readonly>
                                  </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label>Motivos para la compra:</label>
                                  </div>
                                  <div class="col-md-8 col-sm-8 col-xs-8">
                                      <?php
                                        if(count($datosJson->vi_motivocompra)>0){
                                           foreach ($datosJson->vi_motivocompra as $elemMC){
                                              foreach ($arrMotivocompra as $key => $elemArr){
                                                if($key==$elemMC){
                                                  echo $elemArr.',';
                                                  break;
                                                }
                                              }                                              
                                            }
                                            //Otro
                                            foreach ($datosJson->vi_motivocompra as $elemMC){                                                                                            
                                              if($elemMC == "vi_otro_mot"){ 
                                                ?>
                                                <input type="text" id="vi_otro_mot_inp" name="vi_otro_mot_inp" value="<?php echo $datosJson->vi_otro_mot_inp;?>" class="form-control" readonly>  
                                                <?php
                                              }
                                            }
                                        }
                                       ?>
                                  </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label>Requisitos funcionales:</label>
                                  </div>
                                  <div class="col-md-8 col-sm-8 col-xs-8">
                                      <?php
                                        if(count($datosJson->vi_requisitos)>0){
                                           foreach ($datosJson->vi_requisitos as $elemRF){
                                              foreach ($arrReqFun as $key => $elemArr){
                                                if($key==$elemRF){
                                                  echo $elemArr.',';
                                                  break;
                                                }
                                              }                                              
                                            }
                                            //Otro
                                            foreach ($datosJson->vi_requisitos as $elemRF){                                                                                            
                                              if($elemRF == "vi_otro_req"){ 
                                                ?>
                                                <input type="text" id="vi_otro_req_inp" name="vi_otro_req_inp" value="<?php echo $datosJson->vi_otro_req_inp;?>" class="form-control" readonly>  
                                                <?php
                                              }
                                            }
                                        }
                                       ?>
                                  </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label>Equipamiento funcionales:</label>
                                  </div>
                                  <div class="col-md-8 col-sm-8 col-xs-8">
                                      <?php
                                        if(count($datosJson->vi_equipamiento)>0){
                                           foreach ($datosJson->vi_equipamiento as $elemEQ){
                                              foreach ($arrEquiFun as $key => $elemArr){
                                                if($key==$elemEQ){
                                                  echo $elemArr.',';
                                                  break;
                                                }
                                              }                                              
                                            }
                                            //Otro
                                            foreach ($datosJson->vi_equipamiento as $elemEQ){                                                                                            
                                              if($elemEQ == "vi_otro_eq"){ 
                                                ?>
                                                <input type="text" id="vi_otro_eq_inp" name="vi_otro_eq_inp" value="<?php echo $datosJson->vi_otro_eq_inp;?>" class="form-control" readonly>  
                                                <?php
                                              }
                                            }
                                        }
                                       ?>
                                  </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="vi_miembrosfamilia">¿Cu&aacute;ntos miembros hay en su familia?:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="vi_miembrosfamilia" name="vi_miembrosfamilia" value="<?php echo $datosJson->vi_miembrosfamilia;?>" class="form-control" readonly>
                                  </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="vi_quienmaneja">¿Quién va a manejar el veh&iacute;culo?:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="vi_quienmaneja" name="vi_quienmaneja" value="<?php echo $datosJson->vi_quienmaneja;?>" class="form-control" readonly>
                                  </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="vi_cambiavehiculo">¿Cada cuando cambia su veh&iacute;culo?:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="vi_cambiavehiculo" name="vi_cambiavehiculo" value="<?php echo $datosJson->vi_cambiavehiculo;?>" class="form-control" readonly>
                                  </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="vi_fpropuestacompra">Fecha de propuesta de compra:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="vi_fpropuestacompra" name="vi_fpropuestacompra" value="<?php echo $datosJson->vi_fpropuestacompra;?>" class="form-control" readonly>
                                  </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="vi_fseguimiento">Fecha propuesta para la llamada de seguimiento:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="vi_fseguimiento" name="vi_fseguimiento" value="<?php echo $datosJson->vi_fseguimiento;?>" class="form-control" readonly>
                                  </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="vi_horaseguimiento">Hora propuesta seguimiento:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="vi_horaseguimiento" name="vi_horaseguimiento" value="<?php echo $datosJson->vi_horaseguimiento;?>" class="form-control" readonly>
                                  </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="gustoporvehiculo">¿Qu&eacute; es lo que le gusta de este veh&iacute;culo?:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="gustoporvehiculo" name="gustoporvehiculo" value="<?php echo $datosJson->gustoporvehiculo;?>" class="form-control" readonly>
                                  </div>
                               </div>
                               <div class="row">
                                  <div class="text-right form-group col-md-2 col-sm-2 col-xs-3">
                                      <label for="gralcomentario">Comentarios:</label>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-4">
                                      <input type="text" id="gralcomentario" name="gralcomentario" value="<?php echo $datosJson->gralcomentario;?>" class="form-control" readonly>
                                  </div>
                               </div> -->

                              
                              <div class="new_line"></div>
                              <div class="row">
                                <div class="col-md-offset-1 col-md-3">                                    
                                </div>
                                <div class="col-md-offset-5 col-md-3">
                                  <a href="prospectos.php" class="btn btn-success" role="button">Regresar</a>
                                </div>
                              </div>
                              <br/>
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
</body>
</html>
