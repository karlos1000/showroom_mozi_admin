<?php
$dirname = dirname(__FILE__);
include_once $dirname.'/brules/versionZonasActivasObj.php';
include_once $dirname.'/brules/utilsObj.php';
include_once $dirname.'/brules/catActualizacionesObj.php';
include_once $dirname.'/brules/galeriaModelosObj.php';
include_once $dirname.'/brules/videoModelosObj.php';

$function= $_GET['funct'];
switch ($function)
{
    case "uploadImagesGM":
        uploadImagesGM();
    break;
    case "uploadImageIndGM":
        uploadImageIndGM();
    break;
    case "uploadImagesTinymce":
        uploadImagesTinymce();
    break;
    case "uploadImagesFromGM":  //Sube las imagenes desde la vista de galerias gama de modelos
        uploadImagesFromGM();
    break;
    case "actualizarImagesFromGM":  //Actualiza datos desde la vista de galerias gama de modelos
        actualizarImagesFromGM();
    break;
    case "eliminarImagesFromGM":  //eliminar datos desde la vista de galerias gama de modelos
        eliminarImagesFromGM();
    break;  
    //Subir imagenes pasandole parametros de destino  
    case "uploadGeneralImages":  //eliminar datos desde la vista de galerias gama de modelos
        uploadGeneralImages();
    break;
    //Subir imagenes para la galeria 360
    case "uploadImages360":
        uploadImages360();
    break;
    case "eliminarVideoFromGM":  //eliminar video
        eliminarVideoFromGM();
    break;  
}
                    
         
/**
 * Inicio de metodos para subir imagenes para las galerias de gama de modelos
 */    
function uploadImagesGM(){
    // $model = JModelLegacy::getInstance('Globalmodelsbk', 'MoziModel');                         
    $save_folder = 'upload/zonasactivasImg/'; 
    //disenio, galeria, motores, recorrido_virtual, seguridad, tecnologia, versiones                
    
    //Obtener la extrension
    $extension = obtenerExtension($_FILES['file']['name']);
    //Cambiar nombre a la imagen 
    $nuevaImg = generarPassword(10, TRUE, TRUE, FALSE, FALSE).".".$extension;
    $destino = $save_folder.$nuevaImg;

    if(move_uploaded_file($_FILES['file']['tmp_name'], $destino))
    {      
        $versionZonaActivaObj = new versionZonasActivasObj();
        $versionZonaActivaObj->versionZonaActivaPorId($_POST['idGM']);

        $res = $versionZonaActivaObj->actualizaJsonImagenes($_POST['idGM'], "../".$destino, $versionZonaActivaObj->imagenes);
        $actualizacionesObj = new catActualizacionesObj();
        $actualizacionesObj->updActualizacion("version_zonasactivas");
        echo $_POST['index'];                                          
    }
    else
    {
        echo '0';
        // echo false;
    }
}    

function uploadImageIndGM(){
    // $model = JModelLegacy::getInstance('Globalmodelsbk', 'MoziModel');                         
    $save_folder = 'upload/zonasactivasImg/'; 
    //disenio, galeria, motores, recorrido_virtual, seguridad, tecnologia, versiones                
    //Obtener la extrension
    $extension = obtenerExtension($_FILES['file']['name']);
    //Cambiar nombre a la imagen 
    $nuevaImg = generarPassword(10, TRUE, TRUE, FALSE, FALSE).".".$extension;
    $destino = $save_folder.$nuevaImg;
    if(move_uploaded_file($_FILES['file']['tmp_name'], $destino))
    {   
        $versionZonasActivasObj = new versionZonasActivasObj();
        $versionZonasActivasObj->versionZonaActivaPorZona($_POST['zonaVersId']);
        $jsonOriginal = $versionZonasActivasObj->imagenes;
        $objJson = json_decode($jsonOriginal);
        $keyUpd = -1;
        foreach ($objJson as $key => $obj) {
            if($obj->idImagen == $_POST['idImagen'])
            {
                $keyUpd = $key;
            }
            else
            {                
            }
        }
        if($keyUpd != -1) 
        {
            $imgAnt = $objJson[$keyUpd]->imagen;
            $imgAnt = str_replace("../", "", $imgAnt);
            if(file_exists($imgAnt))
            {
                unlink($imgAnt);
            }
            $descripcion = str_replace("\n", "", $_POST['descripcion']);
            $descripcion = str_replace("\"", "&quot;", $descripcion);
            $titulo = cadenaEspeciales($_POST['titulo']);
            $precio = removerComas($_POST['precio']);

            $objJson[$keyUpd]->titulo = $titulo;
            $objJson[$keyUpd]->descripcion = $descripcion;
            $objJson[$keyUpd]->imagen = "../".$destino;
            $objJson[$keyUpd]->precio = $precio;
        }
        $res = $versionZonasActivasObj->ActCampoVersionZonaA("imagenes",json_encode($objJson),$versionZonasActivasObj->activaZonaId);
        $actualizacionesObj = new catActualizacionesObj();
        $actualizacionesObj->updActualizacion("version_zonasactivas");     
        echo $res;                                          
    }
    else
    {
        echo '';
    }
}

function uploadImagesTinymce(){    
    $save_folder = 'upload/tinymceImg/';
        
    //Obtener la extrension
    $extension = obtenerExtension($_FILES['file']['name']);
    //Cambiar nombre a la imagen 
    $nuevaImg = generarPassword(10, TRUE, TRUE, FALSE, FALSE).".".$extension;
    $destino = $save_folder.$nuevaImg;

    if(move_uploaded_file($_FILES['file']['tmp_name'], $destino))
    {              
       $res = array("resp"=>true, "ruta"=>"../".$destino);       
    }
    else
    {
        $res = array("resp"=>false);            
    }
    echo json_encode($res);
} 


/**
 * Inicio de metodos para subir imagenes para las galerias de 360
 */
function uploadImages360(){
    $idFolder = $_POST['idGM'];//(isset($_POST['idGM']) && $_POST['idGM']!="")?$_POST['idGM']:0;
    //Crear folder segun el id de la zona activa en la carpeta 260
    $save_folder = 'upload/360/'.$idFolder.'/';
    if(!file_exists($save_folder)){
        mkdir($save_folder, 0777, true);
    }
    //$save_folder = 'upload/360/'.$idFolder.'/';
    //Obtener la extrension
    $extension = obtenerExtension($_FILES['file']['name']);
    $expFile = explode(".",$_FILES['file']['name']);
    //Cambiar nombre a la imagen
    //$nuevaImg = generarPassword(10, TRUE, TRUE, FALSE, FALSE).".".$extension;
    $destino = $save_folder.$expFile[0].".png"; //cambiar extension a png

    if(move_uploaded_file($_FILES['file']['tmp_name'], $destino))
    {
        $versionZonaActivaObj = new versionZonasActivasObj();
        $versionZonaActivaObj->versionZonaActivaPorId($_POST['idGM']);

        $res = $versionZonaActivaObj->actualizaJsonImagenes($_POST['idGM'], "../".$destino, $versionZonaActivaObj->imagenes);
        $actualizacionesObj = new catActualizacionesObj();
        $actualizacionesObj->updActualizacion("version_zonasactivas");
        echo $_POST['index'];
    }
    else
    {
        echo '0';
        // echo false;
    }
}
/**
 * Inicio de metodos para subir imagenes desde la vista galerias de gama de modelos
 */    
function uploadImagesFromGM(){    
    $actualizacionesObj = new catActualizacionesObj();
    $save_folder = 'upload/galeriaGralGM/';
    
    //Obtener la extrension
    $extension = obtenerExtension($_FILES['file']['name']);
    //Cambiar nombre a la imagen 
    $nuevaImg = generarPassword(10, TRUE, TRUE, FALSE, FALSE).".".$extension;
    $destino = $save_folder.$nuevaImg;

    if(move_uploaded_file($_FILES['file']['tmp_name'], $destino))
    {      
        $galeriaMObj = new galeriaModelosObj();
        //Setear datos
        $galeriaMObj->gModeloId = $_POST['idGM'];
        $galeriaMObj->galeriaId = $_POST['galeriaId'];
        $galeriaMObj->activo = 1;
        $galeriaMObj->imagen = "../".$destino;
        $galeriaMObj->InsertGaleriaModelo();

        if($galeriaMObj->galeriaModeloId>0){            
            $actualizacionesObj->updActualizacion("galeria_modelos");
        }

        // echo $_POST['index'];                                          
        $arr = array("idGM"=>$_POST['idGM'], "galeriaId"=>$_POST['galeriaId'], "idReg"=>$galeriaMObj->galeriaModeloId);
        echo json_encode($arr);
    }
    else
    {
        echo '0';
        // echo false;
    }
}

function actualizarImagesFromGM(){
    $galeriaMObj = new galeriaModelosObj();  
    $actualizacionesObj = new catActualizacionesObj();  
    $save_folder = 'upload/galeriaGralGM/';

    //Setear datos
    $galeriaMObj->galeriaModeloId = $_POST['galeriaModeloId'];
    $galeriaMObj->gModeloId = $_POST['gModeloId'];
    $galeriaMObj->galeriaId = $_POST['galeriaId'];
    $galeriaMObj->titulo = $_POST['titulo'];
    $galeriaMObj->descripcion = $_POST['descripcion'];
    $galeriaMObj->precio = removerComas($_POST['precio']);
    $galeriaMObj->activo = 1;

    if($_POST['archivoSubir']==1){
        //Obtener la extrension
        $extension = obtenerExtension($_FILES['file']['name']);
        //Cambiar nombre a la imagen 
        $nuevaImg = generarPassword(10, TRUE, TRUE, FALSE, FALSE).".".$extension;
        $destino = $save_folder.$nuevaImg;

        if(move_uploaded_file($_FILES['file']['tmp_name'], $destino))
        {
            $galeriaMObj->imagen = "../".$destino;
            $respAct = $galeriaMObj->ActGaleriaModelo();

            if($respAct>0){                
                $actualizacionesObj->updActualizacion("galeria_modelos");
            }        
            echo $respAct;

            // echo $_POST['index'];                                          
            // $arr = array("idGM"=>$_POST['idGM'], "galeriaId"=>$_POST['galeriaId']);
            // echo json_encode($arr);
        }else{
            echo '0';
        }
    }else{                
        $galeriaMObj->imagen = $_POST['imagen'];
        $respAct = $galeriaMObj->ActGaleriaModelo();

        if($respAct>0){            
            $actualizacionesObj->updActualizacion("galeria_modelos");
        }
        echo $respAct;
    }    
}

function eliminarImagesFromGM(){
    $galeriaMObj = new galeriaModelosObj();
    $actualizacionesObj = new catActualizacionesObj();

    $respAct = $galeriaMObj->EliminarGaleriaModelo($_POST['galeriaModeloId']);

    if($respAct>0){        
        $actualizacionesObj->updActualizacion("galeria_modelos");
    }
    echo $respAct;
}


//Subir archivo pasandole la ruta de destino
function uploadGeneralImages(){    
    $save_folder = $_POST['saveFolder'];
        
    //Obtener la extrension
    $extension = obtenerExtension($_FILES['file']['name']);
    //Cambiar nombre a la imagen 
    $newNameImg = generarPassword(10, TRUE, TRUE, FALSE, FALSE).".".$extension;
    $destino = $save_folder.$newNameImg;

    if(move_uploaded_file($_FILES['file']['tmp_name'], $destino))
    {              
       $res = array("resp"=>true, "ruta"=>"../".$destino);       
    }
    else
    {
        $res = array("resp"=>false);            
    }
    echo json_encode($res);
}

// Elimina el video seleccionado
function eliminarVideoFromGM(){
    $videoMObj = new videoModelosObj();    
    $actualizacionesObj = new catActualizacionesObj();

    $respAct = $videoMObj->EliminarVideoModelo($_POST['videoModeloId']);

    if($respAct>0){        
        $actualizacionesObj->updActualizacion("video_modelos");
    }
    echo $respAct;
}


/**
 * Metodo para borrar imagenes de gama de modelos
*/    
// function delImageGM(){
//     $nameImg = $_POST['pathImg'];                                              
//     $path = JPATH_SITE.'/media/com_mozi/imagenesmozi/'.$nameImg;          
//     if(file_exists($path)){
//         unlink ($path);                
//     } 
// }


?>
