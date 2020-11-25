<?php
//$wsdl = "http://inventario.mega-invoice.com/ws/interface_inventario.php?wsdl";
//soap php
//$client = new SoapClient($wsdl);

//echo "<pre>"; print_r($client->__getFunctions());	echo "</pre>";
//exit;

/*
//Buscar en el inventario de autos
function busquedaInventario($codigo, $rfc, $dealer, $vin, $modelo, $year, $version, $color){
	$wsdl = "http://inventario.mega-invoice.com/ws/interface_inventario.php?wsdl";
	$client = new SoapClient($wsdl);
	
	try{		
		$result = $client->Buscar(array("codigo"=>$codigo, "rfc"=>$rfc, "dealer"=>$dealer, "vin"=>$vin, "modelo"=>$modelo, "year"=>$year, "version"=>$version, "color"=>$color));		
	}catch (SoapFault $e){
		$result = (object)array("result"=>"error_busqueda");		
	}
	
	$callback = "asdasdasd";
	echo $callback . '(' . json_encode($result) . ');';
	
	//echo "<pre>";
	//print_r($result);
	//echo "</pre>";	
}

//Parametros
$codigo = "";
$rfc = "ZMO970117GJ5";
$dealer= "2015";
$vin = "";
$modelo = "vento";
$year = "";
$version = "";
$color = "";
busquedaInventario($codigo, $rfc, $dealer, $vin, $modelo, $year, $version, $color);
*/



//API REST DMS-app externa
/*
// required headers
header("Access-Control-Allow-Origin: http://10.159.111.11:8083/Cliente");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//http://10.159.111.11:8083/Cliente?authentication_id=xk25_2kmtE1a$Pke&numeroConcesionaria=9998&concesionaria=9998
*/
/*
$token = "080042cad6356ad5dc0a720c18b53b8e53d4c274";
//$authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
$authorization = 'authentication_id: xk25_2kmtE1a$Pke'; 
$authorization .= "numeroConcesionaria: 9998"; 
$authorization .= "concesionaria: 9998"; 
$authorization .= "timestamp: 2018-11-30T18:25:43.511Z"; 
*/
/*
header('Content-Type: application/json'); // Specify the type of data
$ch = curl_init('http://10.159.111.11:8083/Cliente'); // Initialise cURL
$post = json_encode($post); // Encode the data array into a JSON string

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, 1); // Specify the request method as POST
curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // Set the posted fields
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
$result = curl_exec($ch); // Execute the cURL statement
curl_close($ch); // Close the cURL connection
//return json_decode($result); // Return the received data
*/

/*
$data = array();
$ch = curl_init("http://10.159.111.11:8083/Cliente");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
//obtenemos la respuesta
$result = curl_exec($ch);
// Se cierra el recurso CURL y se liberan los recursos del sistema
curl_close($ch);

echo "<pre>";
print_r($result);
echo "</pre>";
*/
/*
header("Access-Control-Allow-Origin: http://10.159.111.11:8083/Cliente");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('authentication_id: xk25_2kmtE1a$Pke');
header("numeroConcesionaria: 9998");
header("concesionaria: 9998");
header("timestamp: 2018-11-30T18:25:43.511Z");

//$result = json_decode(file_get_contents("php://input"));
$result = file_get_contents("php://input");

echo "<pre>";
print_r($result);
echo "</pre>";
*/

/*
$query = http_build_query(array('authentication_id'=>'xk25_2kmtE1a$Pke', 'numeroConcesionaria'=>'9998', 'concesionaria'=>'9998', 'timestamp'=>'2018-11-30T18:25:43.511Z'));
// construct curl resource
$curl = curl_init("http://10.159.111.11:8083/Cliente?$query");
// additional options
curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => true));
// do the call
$answer = curl_exec($curl);
// clean up curl resource
curl_close($curl);
// done
//$result = json_decode($answer, true);

echo "<pre>";
print_r($answer);
echo "</pre>";
*/
/*
//url
$url = 'http://10.159.111.11:8083/Cliente'; 
//Credentials
$client_id  = "";
$client_pass= ""; 
//HTTP options
$opts = array('http' =>
    array(
        'method'    => 'GET',
        'header'    => array ('header' => 'User-Agent:MyAgent/1.0\r\n, authentication_id: xk25_2kmtE1a$Pke, numeroConcesionaria: 9998, concesionaria: 9998, timestamp: 2018-11-30T18:25:43.511Z ', 'Content-type: application/json'),
    )
);
//$opts = array('http'=>array('header' => "User-Agent:MyAgent/1.0\r\n"));

//Do request
$context = stream_context_create($opts);
$json = file_get_contents($url, false, $context);

$result = json_decode($json, true);
if(json_last_error() != JSON_ERROR_NONE){
    return null;
}
print_r($result);
*/

/*
$ch = curl_init('http://10.159.111.11:8083/Cliente');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{}');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'authentication_id: xk25_2kmtE1a$Pke',
	'numeroConcesionaria: 9998',
	'concesionaria: 9998',
	'timestamp: 2018-11-30T18:25:43.511Z',
	'Content-Type: application/json'
));
$response = curl_exec($ch);
//$response = json_decode($response);    

// echo '<pre>';
//    print_r(curl_getinfo($ch));
//    print_r(curl_errno($ch));
//    print_r(curl_error($ch));
// echo '</pre>';

curl_close($ch);

echo "<pre>";
print_r($response);
echo "</pre>";
*/


function callAPI($method, $url, $data){
   $curl = curl_init();

   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
         break;
      default:
		 //curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");	
		 curl_setopt($curl, CURLOPT_POSTFIELDS, '{}');	
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }
   //echo "url: ".$url.'<br/>';

   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'authentication_id: xk25_2kmtE1a$Pke',
	  'numeroConcesionaria: 9998',
	  'concesionaria: 9998',
	  'timestamp: 2018-11-30T18:25:43.511Z',
      'Content-Type: application/json',
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

   // EXECUTE:
   $result = curl_exec($curl);
   
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}

$get_data = callAPI('GET', 'http://10.159.111.11:8083/Cliente/', false);
//$response = json_decode($get_data, true);
$response = json_decode($get_data);
$errors = $response['response']['errors'];
$data = $response['response']['data'][0];

echo "<pre>";
print_r($get_data);
print_r($response);
print_r($errors);
print_r($data);
echo "</pre>";



