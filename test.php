<?php

/*$path = 'images/aguilar-29.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

echo $base64;
*/

/*
$path = 'showroom_051217.rar';
echo filesize($path).'<br/>';
$sizeFile = formatSizeUnits(filesize($path));
echo $sizeFile.'<br/>';

function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}
*/

// include_once 'brules/versionGeneralesObj.php';
// $verGralObj = new versionGeneralesObj();
// $colVerGral = $verGralObj->ObtVersionesGenerales(true, 1);
// echo "<pre>";
// print_r($colVerGral);
// echo "</pre>";

// //Extraer enlaces 
// $match = array();
// // $string = "The text you want to filter goes here. http://google.com, https://www.youtube.com/watch?v=K_m7NEDMrV0,https://instagram.com/hellow/";
// $string = '<p><img src="http://127.0.0.1:8080/showroom/upload/tinymceImg/b8i09y5f5p.jpg" alt="" width="1239" height="90" /></p>
// <p><img src="http://127.0.0.1:8080/showroom/upload/tinymceImg/q493htvrgv.jpg" alt="" width="1248" height="325" /></p>
// <p style="text-align: justify;">&nbsp;</p>';

// preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $string, $match);

// echo "<pre>";
// print_r($match[0]);
// echo "</pre>";


//-----------------------
/*
$rutasImg = array();
$string2 = '<p><img src="../upload/tinymceImg/b8i09y5f5p.jpg" alt="" width="1239" height="90" /></p>
<p><img src="../upload/tinymceImg/q493htvrgv.jpg" alt="" width="1248" height="325" /></p>
<p style="text-align: justify;">&nbsp;</p>

<p><img src="../upload/tinymceImg/o9w3739tqk.jpg" alt="" width="1239" height="90" /></p>
<p><img src="../upload/tinymceImg/2pjm566g3h.jpg" alt="" width="1094" height="492" /></p>

<p><img src="../upload/tinymceImg/rtnbtodeeu.jpg" alt="" width="1259" height="320" /></p>
<p><img src="../upload/tinymceImg/fln2j1okuk.jpg" alt="" width="1256" height="290" /></p>
<p>
<video controls="controls" width="138" height="69">
<source src="../upload/tinymceImg/lbtdwpn3s8.mp4" type="video/mp4"></video>
</p>
';

@preg_match_all('~<img.*?src=["\']+(.*?)["\']+~', $string2, $rutasImg);
@preg_match_all('~<source.*?src=["\']+(.*?)["\']+~', $string2, $rutasImg);
echo "<pre>";
print_r($rutasImg[1]);
echo "</pre>";
*/

/*$file = 'https://stackoverflow.com/questions/2280394/how-can-i-check-if-a-url-exists-via-php';
$file_headers = @get_headers($file);
if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
    $exists = false;
    echo "false";
}
else {
    $exists = true;
    echo "true";
}
*/


/*$url = 'http://framelova.net/temp/showroomzmtemp/admin/catalogos.php?catalog=catAgencias';
$array = @get_headers($url);
$string = $array[0];
if(strpos($string,"200"))
{
echo 'url exists';
}
else
{
echo 'url does not exist';
}*/
// $url = 'https://stackoverflow.com/questions/2280394/how-can-i-check-if-a-url-exists-via-php';
// echo "es: ". isValidUrl($url);


include_once 'brules/promocionesObj.php';
$promoObj = new promocionesObj();
$strColPromociones = true;
$colPromociones = array();
$rutasImgExtr = array();
$rutasCaracImgTmp = array();

//Col de promociones    
  if($strColPromociones==true){  
    $colPromociones = $promoObj->ObtVersionesPromociones(true, "", 1);

    echo "<pre>";
    print_r($colPromociones);
    echo "</pre>";

    if(count($colPromociones)>0){
      foreach($colPromociones as $elemPromo){
        //Obtener de las caracteristicas implementado el 20/04/18
        if($elemPromo->caracteristicas!=""){
          @preg_match_all('~<img.*?src=["\']+(.*?)["\']+~', $elemPromo->caracteristicas, $rutasCaracImgTmp);
          if(isset($rutasCaracImgTmp[1]) && count($rutasCaracImgTmp[1])>0){
            foreach ($rutasCaracImgTmp[1] as $imgYinymce){
                if(file_exists($imgYinymce)){                    
                  $rutasImgExtr[] = $imgYinymce;
                }
            }
            echo "<pre>";
            print_r($rutasCaracImgTmp[1]);
            // print_r($rutasImgExtr);
            echo "</pre>";
          }

          @preg_match_all('~<source.*?src=["\']+(.*?)["\']+~', $elemPromo->caracteristicas, $rutasCaracImgTmp);
          if(isset($rutasCaracImgTmp[1]) && count($rutasCaracImgTmp[1])>0){
            foreach ($rutasCaracImgTmp[1] as $imgYinymce){
                if(file_exists($imgYinymce)){                    
                  $rutasImgExtr[] = $imgYinymce;
                }
            }
            echo "<pre>";
            print_r($rutasCaracImgTmp[1]);
            // print_r($rutasImgExtr);
            echo "</pre>";
          }
        }
        //Fin Obtener de las caracteristicas
      }
    }    
  }

// echo "<pre>";
// print_r($rutasImgExtr);
// echo "</pre>";