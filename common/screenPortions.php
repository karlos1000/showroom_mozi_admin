<?php
/*
 *  © 2012 Framelova. All rights reserved. Privacy Policy
 *  Creado: 11/01/2017
 *  Por: JCRG
 *  Description: Contain necessary methods to call portions screen used as part
 *  of the template
 */

//logo page
function getLogo($level=false){
    $level=($level==true)?'../':'';
    //$logo = '<div class="logo"><a href="index.php"><img src="'.$level.'images/aguilar-29.png" /></a></div>';
    $logo = "";
    return $logo;
}

//Menu dependiendo de los permisos
function getAdminMenu(){
    $menu = '';

    $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/aguilar_superadmin-28.png"><a href="index.php">Inicio</a></li>';
    if($_SESSION['idRol'] == 1 || $_SESSION['idRol'] == 2){
        $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/modelos.png"><a href="gamamodelos.php">Gama Modelos</a></li>';      
        $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/versiones.png"><a href="versiones.php">Versiones</a></li>';
        $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/planes.png"><a href="planestreeview.php">Planes - Modelos</a></li>';
        $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/promociones.png"><a href="promostreeview.php">Promociones - Modelos</a></li>';
        $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/prospectos.png"><a href="prospectos.php">Prospectos</a></li>';
        $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/prospectos.png"><a href="estadisticas.php">Estad&iacute;sticas App</a></li>';
        $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/catalogos.png"><a href="catalogos.php">Cat&aacute;logos</a></li>';    
        $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/catalogos.png"><a href="subirInventario.php">Subir Inventario</a></li>';
    }
    if($_SESSION['idRol'] == 4){
        $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/versiones.png"><a href="versiones.php">Versiones</a></li>';
        $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/prospectos.png"><a href="prospectos.php">Prospectos</a></li>';
        $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/prospectos.png"><a href="estadisticas.php">Estad&iacute;sticas App</a></li>';
        $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/catalogos.png"><a href="catalogos.php">Cat&aacute;logos</a></li>';    
    }

    // elseif($_SESSION['idRol'] == 3){
    //   if($_SESSION["permisos"]["Menu_Busqueda_Ver"] == 1){
    //     $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/aguilar_superadmin-13.png"><a href="busqueda.php">Busqueda clientes</a></li>';
    //     }
    //     if($_SESSION["permisos"]["Menu_EditarInfoCliente_Ver"] == 1){
    //     $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/aguilar_superadmin_editar_info_clientes-41.png"><a href="busqueda2.php">Editar Info cliente</a></li>';    
    //     }
    //     if($_SESSION["permisos"]["Menu_Aclaraciones_Ver"] == 1){
    //     $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/aguilar_superadmin-14.png"><a href="aclaraciones.php">Aclaraciones</a></li>';
    //     }
    //     if($_SESSION["permisos"]["Menu_Garantias_Ver"] == 1){
    //     $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/aguilar_superadmin-15.png"><a href="garantias.php">Garantias</a></li>';
    //     }
    //     $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/aguilar_superadmin-28.png"><a href="comunicacionint.php">Comunicaci&oacute;n interna</a></li>';                                
    //     if($_SESSION["permisos"]["Menu_Comisiones_Ver"] == 1){
    //     $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/aguilar_superadmin-03.png"><a href="comisiones.php">Comisiones</a></li>';
    //     }
        
    //     if($_SESSION["permisos"]["Menu_Momentos_Ver"] == 1){
    //       $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/aguilar_superadmin-03.png"><a href="momentos.php">Momentos</a></li>';
    //     }
    //     if($_SESSION["permisos"]["Menu_Catalagos_Ver"] == 1){
    //     $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/aguilar_superadmin-03.png"><a href="catalogos.php">Cat&aacute;logos</a></li>';
    //     }
    // }    
    $menu .= '<li><img src="../images/iconos/iconos_menu_lateral/aguilar_superadmin-15.png"><a href="../admin/logout.php">Cerrar Sesi&oacute;n</a></li>';

    return $menu;
}

//footer Page
function getPiePag($level=false){
    $level=($level==true)?'../':'';
    $html = "";
    $html .= '<div class="footer_site text-muted text-center">
                   ShowRoom 2017 Todos los derechos reservados.<br>
                   <!--Powered by: <a href="http://framelova.com"><img src="'.$level.'images/framelova.png"></a>-->
               </div>';

    return $html;
}

//usuario
function getUsrForHeader($usrName){  
  $hdr = '<div class="user text-right cursorPointer">
            <div class="dropdown">
              <div class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><strong>Usuario <span class="caret"></span>:</strong> '.$usrName.' </div>
              <ul class="dropdown-menu">      
                <li><a class="user" href="../admin/logout.php">Cerrar sesi&oacute;n</a></li>
              </ul>
            </div>
          </div>
        ';
  return $hdr;
}

//header Page
function getHeaderMain($myusername, $bool){
	$html = "";
		$html .= '<header>
            		 <div class="container">
            			<div class="row">
            				<div class="col-md-9 col-sm-3 col-xs-3"> <div class="logo"><a href="index.php"><img src="../images/logo.png" /></a></div></div>
            				<div class="colmenu col-md-3 col-sm-3 col-xs-6">'.getUsrForHeader($myusername).'</div>
            			</div>
            	   </div>
            	</header>
            	<div class="panel-heading">
              		<div class="container">
            			<div class="row">
            				<div class="colmenu col-md-3 col-sm-3 col-xs-3">'.getLogo($bool).'</div>
            			</div>
                   </div>
             </div>';
	return $html;
}

function getNav($menu){
  $html = "";
  $html .= '
      <nav class="navbar navbar-default" role="navigation">
          <div class="cont_menu">
              <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="#">Men&uacute;</a>
              </div>
              <div class="collapse navbar-collapse navbar-ex1-collapse">
                  <ul class="nav navbar-nav">';
                      $html .= $menu;
                  $html .= '</ul>
              </div>
          </div>
      </nav>';
    echo  $html;
}

?>
