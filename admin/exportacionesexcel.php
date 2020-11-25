<?php
session_start();
$idRol = $_SESSION['idRol'];
switch ($idRol) {
    case 1: case 2: case 4:  $rol = true; break;
    default: $rol = false; break;
}
if($_SESSION['status']!= "ok" || $rol!=true)
        header("location: logout.php");

include '../brules/utilsObj.php';
include_once '../common/screenPortions.php';
include_once '../brules/PHPExcel/PHPExcel/IOFactory.php';
include_once '../brules/exportarExcelObj.php';
include_once '../brules/catProspectosObj.php';
include_once '../brules/usuariosObj.php';
include_once '../brules/estadisticasAppObj.php';

$expExcelObj = new exportarExcelObj();
//Exportar excel segun el seleccionado
if(isset($_POST["btn_exp_prospectos"])){  
	$_POST['fechaDel'] = conversionFechas($_POST['fechaDel']);
	$_POST['fechaAl'] = conversionFechas($_POST['fechaAl']);
    $expExcelObj->ExportarProspectos($_POST);
}

//Exportar excel segun el seleccionado (Estadisticas)
if(isset($_POST["btn_exp_estadisticas"])){  
	$_POST['fechaDel'] = conversionFechas($_POST['fechaDel']);
	$_POST['fechaAl'] = conversionFechas($_POST['fechaAl']);
    $expExcelObj->ExportarEstadisticas();
}
?>