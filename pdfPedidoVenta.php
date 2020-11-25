<?php
$arrDatosGrales = (object)$_POST['arrDatosGrales'];
$totalArrFilas = $_POST['totalArrFilas'];
$colDetalles  = array();

if($totalArrFilas>0){
  $colDetalles = (object)$_POST['arrFilas'];
}
// echo "<pre>";
// print_r($arrDatosGrales);
// print_r($colDetalles);
// echo "</pre>";

?>
<style>
    .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
        border: 1px solid #ddd;
    }
    .table-sinbordered>tbody>tr>td, .table-sinbordered>tbody>tr>th, .table-sinbordered>tfoot>tr>td, .table-sinbordered>tfoot>tr>th, .table-sinbordered>thead>tr>td, .table-sinbordered>thead>tr>th {
        border: none;
    }
    .table-sinbordertop{
      border-top: none;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }
    table td[class*=col-], table th[class*=col-] {
        position: static;
        display: table-cell;
        float: none;
    }
    .table>thead>tr>th {
        vertical-align: bottom;
        border-bottom: 2px solid #ddd;
    }
    th {
        text-align: left;
    }
    td, th, tr {
        padding: 2px;
        color: #2f2f2f;
    }
    .tblContEstimacion {
        font-size: 11px;
    }
    .font_small {
        font-size: 10px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .content_block div {
        display: inline-block;
        width: 48%;
    }
    .titulo{
        font-size: 20px;
        font-weight: bold;
    }
    .titulo2{
        font-size: 16px;
        font-weight: bold;
    }
    .text_center{
        text-align: center;
    }
    .paddin_OC{
        padding: 20px 0;
    }
    .text-center {
        text-align: center;
    }
    .subTitle{
      font-size: 13px;
      font-weight: 800;
      color: #1b2859 !important;
      border-bottom: 1px solid #1b2859 !important;
    }
    .text_right{
        text-align: right;
    }
    .fechaCompleta{
        font-size: 10px;
    }
    .subyadoRojo{
        border-bottom: 1px double red;
    }
    .logo{
        float: left;
    }
    .padding_top{
      padding-top: 5px;
    }
    .padding_top_small{
      padding-top: 1px;
    }
    .negrita{
      font-size: 11px;
      font-weight: 700;
    }
	  table td {
     min-width: 20px !important;
     /*min-height: 20px;*/
    }
    .border_top{
      border-top:1px solid #000;      
    }
    .padding_bottom{
      padding-bottom: 10px;
    }    
</style>
<!-- <div>
    <h1>
        <span class="logo"><img class="logo" src="<?php echo $arrDatosGrales->siteURL;?>images/logo_aguilar.png" width="50" alt="Aguilar" title="Aguilar"></span>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span class="text-center">Estado de cuenta</span>
    </h1>    
</div> -->

<table class="table-sinbordered tblContEstimacion" width="545">
  <tr>
    <td width=""><img src="<?php echo $arrDatosGrales->siteURL;?>images/pdflogo1.png" width="50"></td>
    <td colspan="2" class="text_center titulo2">Z MOTORS SA DE CV<br/>CONCESIONARIA AUTORIZADA VOLKSWAGEN</td>
    <td width=""><img src="<?php echo $arrDatosGrales->siteURL;?>images/pdflogo2.png" width="50"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" class="text_center titulo">PEDIDO DE VENTA DE AUTO NUEVO</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>No. CLIENTE:</td>
    <td><?php echo $colDetalles->pv_nocliente; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="325">&nbsp;</td>
    <td width="97">FECHA:</td>
    <td><?php echo $colDetalles->pv_fecha; ?></td>
  </tr>
</table>
<table class="table-sinbordered tblContEstimacion" min-width="545">
  <tr>
    <td colspan="5" class="subTitle"><b>DATOS DEL CLIENTE:</b></td>
  </tr>
  <tr>
    <td colspan="5">NOMBRE: <?php echo $colDetalles->pv_nombre; ?></td>
  </tr>
  <tr>
    <td colspan="2">DIRECCION: <?php echo $colDetalles->pv_direccion; ?></td>
    <td colspan="3">COL: <?php echo $colDetalles->pv_colonia; ?></td>
  </tr>
  <tr>
    <td width="293">DELEGACION: <?php echo $colDetalles->pv_delegacion; ?></td>
    <td colspan="2">ESTADO: <?php echo $colDetalles->pv_estado; ?></td>
    <td width="116" colspan="2">CP: <?php echo $colDetalles->pv_codigopostal; ?></td>
  </tr>
  <tr>
    <td>FECHA DE NACIMIENTO: <?php echo $colDetalles->pv_fechanacimiento; ?></td>
    <td colspan="4">E-MAIL: <?php echo $colDetalles->pv_email; ?></td>
  </tr>
</table>
<table class="table-sinbordered tblContEstimacion" min-width="545">
  <tr>
    <td width="110" class="subTitle padding_top">TELEFONOS:</td>
    <td colspan="3" class="subTitle padding_top">(ANOTAR 2 TELEFONOS NO SE RECIBIRA SOLO 1)</td>
  </tr>
  <tr>
    <td>1 CELULAR:</td>
    <td min-width="198"><?php echo $colDetalles->pv_celular; ?></td>
    <td width="65">3 CASA:</td>
    <td min-width="209"><?php echo $colDetalles->pv_casa; ?></td>
  </tr>
  <tr>
    <td>2 OFICINA:</td>
    <td><?php echo $colDetalles->pv_oficina; ?></td>
    <td>4 OTRO:</td>
    <td><?php echo $colDetalles->pv_otro; ?></td>
  </tr>
  <tr>
    <td colspan="4" class="subTitle padding_top">TIPO DE PERSONA:</td>
  </tr>
  <tr>
    <td> CONSUMIDOR: <?php echo ($colDetalles->pv_tipopersona=="pv_consumidor")?"&#215;":""; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>FISICA: <?php echo ($colDetalles->pv_tipopersona=="pv_fisica")?"&#215;":""; ?></td>
    <td>&nbsp;</td>
    <td rowspan="2">RFC:</td>
    <td rowspan="2"><?php echo $colDetalles->pv_tipopersonarfc; ?></td>
  </tr>
  <tr>
    <td>MORAL: <?php echo ($colDetalles->pv_tipopersona=="pv_moral")?"&#215;":""; ?></td>
    <td>&nbsp;</td>
  </tr>
</table>
<table class="table-sinbordered tblContEstimacion" min-width="545">
  <tr>
    <td colspan="4" class="subTitle padding_top">TIPO DE VENTA:</td>
  </tr>
  <tr>
    <td width="159">CONTADO: <?php echo ($colDetalles->pv_tipoventa=="pv_contado")?"&#215;":""; ?></td>
    <td min-width="160">&nbsp;</td>
    <td width="105">CREDIT VWL: <?php echo ($colDetalles->pv_tipoventa=="pv_creditvwl")?"&#215;":""; ?></td>
    <td min-width="158">&nbsp;</td>
  </tr>
  <tr>
    <td>CREDIESTRENA VWL: <?php echo ($colDetalles->pv_tipoventa=="pv_crediestrena")?"&#215;":""; ?></td>
    <td>&nbsp;</td>
    <td>VW LEASING: <?php echo ($colDetalles->pv_tipoventa=="pv_vwleasing")?"&#215;":""; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>CREDITO CASA: <?php echo ($colDetalles->pv_tipoventa=="pv_creditocasa")?"&#215;":""; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>BANCO: <?php echo ($colDetalles->pv_tipoventa=="pv_banco")?"&#215;":""; ?></td>
    <td>&nbsp;</td>
    <td>CUAL: <?php echo $colDetalles->pv_cualbanco; ?></td>
    <td>&nbsp;</td>
  </tr>
</table>
<table class="table-sinbordered tblContEstimacion" min-width="545" style="margin-top:10px;">
  <tr>
    <td colspan="2">NOMBRE COMPLETO DEL CONTACTO: <?php echo $colDetalles->pv_nombrecontacto; ?></td>
    <td min-width="130">&nbsp;</td>
    <td min-width="30">TEL:</td>
    <td min-width="103"><?php echo $colDetalles->pv_telcontacto; ?></td>
  </tr>
  <tr>
    <td width="144">TIPO DE SUBVENCION:</td>
    <td width="151"><?php echo $colDetalles->pv_tiposubvencion; ?></td>
    <td colspan="2">CATEGORIA PRECIO FLOTILLA:</td>
    <td><?php echo $colDetalles->pv_catprecioflotilla; ?></td>
  </tr>
  <tr>
    <td>AUTO:</td>
    <td><?php echo $colDetalles->pv_auto; ?></td>
    <td colspan="2">ANTICIPO CLIENTE:</td>
    <td>$ <?php echo $colDetalles->pv_anticipocliente; ?></td>
  </tr>
  <tr>
    <td>SERIE:</td>
    <td><?php echo $colDetalles->pv_serie; ?></td>
    <td colspan="2">PAGO DE BANCO:</td>
    <td>$ <?php echo $colDetalles->pv_pagobanco; ?></td>
  </tr>
  <tr>
    <td>MOTOR:</td>
    <td><?php echo $colDetalles->pv_motor; ?></td>
    <td colspan="2">PAGO DE VWL:</td>
    <td>$ <?php echo $colDetalles->pv_pagovwl; ?></td>
  </tr>
  <tr>
    <td>COLOR:</td>
    <td><?php echo $colDetalles->pv_color; ?></td>
    <td colspan="2">NOTA DE CREDITO P/SUBVENCION</td>
    <td>$ <?php echo $colDetalles->pv_notacreditosubvencion; ?></td>
  </tr>
  <tr>
    <td>MOD:</td>
    <td><?php echo $colDetalles->pv_modelo; ?></td>
    <td colspan="2">PLACAS Y TENENCIA:</td>
    <td>$ <?php echo $colDetalles->pv_placastenencia; ?></td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td><span style="font-weight:bold;font-size:11px;">PRECIO LLENO DEL AUTO:</span></td>
    <td>$ <?php echo $colDetalles->pv_preciollenoauto; ?></td>
    <td colspan="2"><span class="negrita">TOTAL A FACTURAR:</span></td>
    <td>$ <?php echo $colDetalles->pv_totalfacturar; ?></td>
  </tr>
  <tr>
    <td>OBSERVACIONES:</td>
    <td colspan="4"><?php echo $colDetalles->pv_comentario; ?></td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>  
</table>
<table class="table-sinbordered tblContEstimacion" min-width="545" style="text-align:justify;margin-top:-15px;">
  <tr>
    <td>La aceptación de este  contrato implica la obligación por parte del comprador de cubrir incondicionalmente a Z MOTORS S.A. de C.V. los gastos que hubiera afectado, en caso de cancelación por cualquier motivo del 15% del precio del auto que se toma del enganche. Si por circunstancias fortuitas ajenas a Z MOTORS S.A. de C.V. el precio del vehículo sufriera un incremento antes de su entrega, el comprador acepta el nuevo precio independiente al pago total que haya hecho. En caso contrario sera reembolsado su enganche. El comprador reconoce que  la  disponibilidad de  vehículos por parte de Z MOTORS S.A. de C.V. esta sujeta al fabricante y no tiene  responsabilidad alguna en el retraso de la entrega del automóvil.</td>
  </tr>
</table>
<table class="table-sinbordered tblContEstimacion" min-width="545" style="margin-top:-20px;">
  <tr>
    <td colspan="7">&nbsp;</td>
  </tr>
  <tr>
    <td min-width="13">&nbsp;</td>
    <td min-width="88">&nbsp;</td>
    <td min-width="120" class="text_center"><div class="padding_bottom"><b>VENDEDOR</b></div></td>
    <td min-width="106">&nbsp;</td>
    <td min-width="135" class="text_center"><div class="padding_bottom"><b>CLIENTE</b></div></td>
    <td min-width="96">&nbsp;</td>
    <td min-width="12">&nbsp;</td>
  </tr>  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="text_center"><div class="border_top"><?php echo strtoupper($arrDatosGrales->nombreVendedor); ?></div></td>
    <td>&nbsp;</td>
    <td class="text_center"><div class="border_top"><?php echo strtoupper($colDetalles->pv_nombre); ?></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="text_center"><div class="padding_bottom"><b>GERENTE VWSF</b></div></td>
    <td>&nbsp;</td>
    <td class="text_center"><div class="padding_bottom"><b>GERENTE DE VENTAS</b></div></td>
    <td>&nbsp;</td>
    <td class="text_center"><div class="padding_bottom"><b>GERENTE GENERAL</b></div></td>
    <td>&nbsp;</td>
  </tr>  
  <tr>
    <td>&nbsp;</td>
    <td class="text_center"><div class="border_top"><?php echo $arrDatosGrales->gerentevwsf; ?></div></td>
    <td>&nbsp;</td>
    <td class="text_center"><div class="border_top"><?php echo $arrDatosGrales->gerenteventas; ?></div></td>
    <td>&nbsp;</td>
    <td class="text_center"><div class="border_top"><?php echo $arrDatosGrales->gerentegral; ?></div></td>
    <td>&nbsp;</td>
  </tr>
</table>