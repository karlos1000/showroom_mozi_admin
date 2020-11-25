<?php
$arrDatosGrales = (object)$_POST['arrDatosGrales'];
$totalArrFilas = $_POST['totalArrFilas'];
$colDetalles  = array();

if($totalArrFilas>0){
  $colDetalles = $_POST['arrFilas'];
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
    td, th {
        padding: 0;
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
    .tituloRecibo{
        font-size: 20px;
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
</style>
<div>
    <h1>
        <span class="logo"><img class="logo" src="<?php echo $arrDatosGrales->siteURL;?>images/logo_aguilar.png" width="50" alt="Aguilar" title="Aguilar"></span>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span class="text-center">Estado de cuenta</span>
    </h1>
    <!-- <p class="text-right fechaCompleta">Puebla, Pue. a <?php echo $arrDatosGrales->fechaCompleta; ?></p> -->
</div>
<!-- <div class="tituloRecibo text_center paddin_OC">COTIZACIÓN</div> -->
<div class="paddin_OC"></div>
<div id="printContEstimation">
  <table class="table table-bordered tblContEstimacion" width="300">
    <tr>
      <td width="70" class="text_right">Nombre:</td>
      <td width="230"><?php echo $arrDatosGrales->cliente; ?></td>
    </tr>
    <tr>
      <td class="text_right">Contrato No.:</td>
      <td ><?php echo $arrDatosGrales->contrato; ?></td>
    </tr>
    <tr>
      <td class="text_right">Saldo:</td>
      <td class="text_right"><?php echo $arrDatosGrales->saldo; ?></td>
    </tr>
    <tr>
      <td class="text_right">Última actualización:</td>
      <td><?php echo $arrDatosGrales->ultimaActSaldo; ?></td>
    </tr>
    <tr>
      <td class="text_right">Monto a devolver:</td>
      <td class="text_right"><?php echo $arrDatosGrales->montoDevolver; ?></td>
    </tr>
    <tr>
      <td class="text_right">Fecha devolución:</td>
      <td><?php echo $arrDatosGrales->fechaDevolucion; ?></td>
    </tr>
  </table>
</div>
<br/>

<?php
if(count($colDetalles)>0){
  ?>
  <div id="printContEstimation">
    <table class="table table-bordered tblContEstimacion" width="545">
      <tr>
        <th class="subTitle">Concepto</th>
        <th class="subTitle">Estatus</th>
        <th class="subTitle">Vence</th>
        <th class="subTitle">Monto</th>
      </tr>
      <?php
        if(count($colDetalles)>0){
          foreach ($colDetalles as  $elemDetCot) {
            ?>
            <tr>
              <td><?php echo $elemDetCot["concepto"]; ?></td>
              <td><?php echo $elemDetCot["estatus"]; ?></td>
              <td><?php echo $elemDetCot["venceCompleta"]; ?></td>
              <td class="text_right"><?php echo "$ ".$elemDetCot["monto"]; ?></td>
            </tr>
            <?php
          }
          ?>
          <?php
        }
      ?>
    </table>
  </div>
  <?php
}
?>
