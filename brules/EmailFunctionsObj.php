<?php
/*
 *  © 2017 Framelova. All rights reserved. Privacy Policy
 *  Creado: 24/01/2017
 *  Por: J. Carlos Ramirez G - JCRG
 *  Descripción: Functions need for sent emails
 */

$dirname = dirname(__DIR__);
include_once  $dirname.'/common/class.phpmailer.php';
include_once  $dirname.'/common/class.smtp.php';

class EmailFunctions{

    //private $_emailBlocked = "carlos.ramirez@framelova.com";

	 //Metodo que ejecuta el envio el correo
    private function SendDataMail($subject, $email, $mailHtml, $attached=""){        
        $sfrom = 'Z Motors <info@zmotors.com.mx>';
        $sheader= $this->GetHeader($sfrom,'', $attached);

        if($attached!=""){
          // $archivo = file_get_contents($attached); //leeo del origen temporal el archivo y lo guardo como un string en la misma variable (piso la variable $archivo que antes contenía la ruta con el string del archivo)
          // $archivo = chunk_split(base64_encode($attached)); //codifico el string leido del archivo en base64 y la fragmento segun RFC 2045          

          // //armado del mensaje y attachment
          // $archivoNombre = "adjunto.pdf";
          // // $mailHtmlSend = "--=A=G=R=O=\r\n";
          // // $mailHtmlSend .= "Content-type:text/plain; charset=utf-8\r\n";
          // // $mailHtmlSend .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
          // $mailHtmlSend = $mailHtml . "\r\n\r\n";
          // $mailHtmlSend .= "--=A=G=R=O=\r\n";
          // $mailHtmlSend .= "Content-Type: application/octet-stream; Name=\"". $nombrearchivo . "\"\r\n";
          // $mailHtmlSend .= "Content-Transfer-Encoding: base64\r\n";
          // $mailHtmlSend .= "Content-Disposition: attachment; filename=\"" . $nombrearchivo . "\"\r\n";
          // $mailHtmlSend .= $archivo . "\r\n\r\n";
          // $mailHtmlSend .= "--=A=G=R=O=--";     
          // $mailHtml = $mailHtmlSend;  
        }

        $res = @mail($email,$subject,$mailHtml,$sheader);

        if($res!=true) {
           $statusSend = '0';
        } else {
           $statusSend = '1';
        }

        return $statusSend;
    }

    //Metodo que ejecuta el envio el correo
    private function SendDataMailCustom($subject, $email, $mailHtml, $nameServer="", $emailServer=""){
        $sfrom = "$nameServer <$emailServer>";
        $sheader= $this->GetHeader($sfrom,'', $attached);      
        $res = @mail($email,$subject,$mailHtml,$sheader);

        if($res!=true) {
           $statusSend = '0';
        } else {
           $statusSend = '1';
        }

        return $statusSend;
    }

  	//Header del email
  	private function GetHeader($sfrom, $bcc, $attached){
        $sheader = "From:".$sfrom."\nReply-To:".$sfrom."\n";
        if($bcc != ''){
            $sheader=$sheader.'Bcc:'.$bcc."\n";
        }
    		$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n";
    		$sheader=$sheader."Mime-Version: 1.0\n";
        $sheader=$sheader."X-Priority: 3\n";
        $sheader=$sheader."X-MSMail-Priority: Normal\n";
		    $sheader=$sheader."Content-Type: text/html; charset=utf-8";   

        if($attached!=""){
          $headers .= "Content-Type: multipart/mixed; boundary=\"=A=G=R=O=\"\r\n\r\n";
        }

        return $sheader;
    }

    //Metodo encargado para enviar correos usando phpmailer
    public function EmailSmptNoPass($email="",$nombreEmail="", $body="",$subject="",$attached=""){
        // $body = 'haciendo pruebas sin smpt';
        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";                      
        $mail->SetFrom('info@zmotors.com.mx', 'Z Motors');
        // servicio@autoforum.com.mx
        // $mail->AddReplyTo("carlos.ramirez@framelova.com", "Z Motors");
        
        $mail->AddAddress($email, "");
        // $mail->AddBCC("carlos.ramirez@framelova.com", "");        

        $mail->Subject = $subject;
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $mail->MsgHTML($body);

        //adjuntar documento
        if($attached!=""){
          $mail->AddAttachment($attached); // ruta del archivo
        }

        if(!$mail->Send()) {
            $statusSend = '0';
          } else {
            $statusSend = '1';
          }

        return $statusSend;
    }

    //Metodo encargado para enviar correos usando phpmailer
    public function EmailSmptNoPassCustom($email="",$nombreEmail="", $body="",$subject="",$attached="", $nameServer="", $emailServer=""){        
        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";                      
        $mail->SetFrom($emailServer, $nameServer);        
        // $mail->AddReplyTo("carlos.ramirez@framelova.com", "Z Motors");
        
        $mail->AddAddress($email, "");
        // $mail->AddBCC("carlos.ramirez@framelova.com", "");        

        $mail->Subject = $subject;
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $mail->MsgHTML($body);

        //adjuntar documento
        if($attached!=""){
          $mail->AddAttachment($attached); // ruta del archivo
        }

        if(!$mail->Send()) {
            $statusSend = '0';
          } else {
            $statusSend = '1';
          }

        return $statusSend;
    }


     //>>>>>>>>METODOS ENCARGADOS PARA CONTRUIR Y ENVIAR EL CORREO
    
    public function EnviarDatosDeAcceso($email, $nombreUsuario, $passCliente)
    {
      $subject = 'Datos de acceso';
      $mailHtml = $this->datosAccesoMailBody($nombreUsuario, $email, $passCliente);
      
      //       $resMail =  $this->SendDataMail($subject, $email, $mailHtml);
       $resMail =  $this->EmailSmptNoPass($email, $nombreUsuario, $mailHtml, $subject, "");
      // $this->SendDataMail($subject, "karlos.0@live.com.mx", $mailHtml);
      // $this->SendDataMail($subject, "carlos.ramirez@framelova.com", $mailHtml);

       // echo $mailHtml;
      return $resMail;          
    }
    
    public function RecuperarDatosDeAcceso($email, $nombreUsuario, $passCliente)
    {
      $subject = 'Recuperar contraseña';
      $mailHtml = $this->recuperarDatosAccesoMailBody($nombreUsuario, $email, $passCliente);
      //       $resMail =  $this->SendDataMail($subject, $email, $mailHtml);
       $resMail =  $this->EmailSmptNoPass($email, $nombreUsuario, $mailHtml, $subject, "");
      // $this->SendDataMail($subject, "karlos.0@live.com.mx", $mailHtml);
      // $this->SendDataMail($subject, "carlos.ramirez@framelova.com", $mailHtml);

      // echo $mailHtml;
      return $resMail;      
    }
  
    //Enviar caracteristicas
    public function EnviarCaracVersion($subject, $email, $htmlImg, $caracteristicas, $versionNombre = "", $precioDesde = "", $nombreVendedor="", $correoVendedor="", $adjunto)
    { 
      // $email = "jair.castaneda@framelova.com";
      $mailHtml = $this->enviarCaracVersionBody($htmlImg, $caracteristicas, $versionNombre, $precioDesde, $nombreVendedor, $correoVendedor);
      // echo $mailHtml;            
      // $resMail =  $this->EmailSmptNoPass($email, "", $mailHtml, $subject, $adjunto);      
      $resMail =  $this->EmailSmptNoPassCustom($email, "", $mailHtml, $subject, $adjunto, $nombreVendedor, $correoVendedor);      
      return $resMail;
    }

    //Enviar planes 
    public function enviarPlanesVersion($subject, $email, $datos)
    {       
      $mailHtml = $this->enviarPlanesVersionBody($datos->arrPlanes, $datos->modeloVersion, $datos->nombreVendedor, $datos->emailVendedor);
      // echo $mailHtml;
      // $resMail =  $this->SendDataMail($subject, $email, $mailHtml);//HABILITAR
      $resMail =  $this->SendDataMailCustom($subject, $email, $mailHtml, $datos->nombreVendedor, $datos->emailVendedor);//HABILITAR      
      // $resMail = 1;//DESHABILITAR
      return $resMail;
    }

    //Enviar requisitos 
    public function enviarRequisitosVersion($subject, $email, $datos)
    {       
      $mailHtml = $this->enviarRequisitosVersionBody($datos->arrRequisitos, $datos->modeloVersion, $datos->nombreVendedor, $datos->emailVendedor);
      // echo $mailHtml;      
      // $resMail =  $this->SendDataMail($subject, $email, $mailHtml);//HABILITAR
      $resMail =  $this->SendDataMailCustom($subject, $email, $mailHtml, $datos->nombreVendedor, $datos->emailVendedor);//HABILITAR      
      // $resMail = 1;//DESHABILITAR
      return $resMail;
    }

    //Enviar precios 
    public function enviarPreciosVersion($subject, $email, $datos)
    {       
      $mailHtml = $this->enviarPreciosVersionBody($datos->arrPrecios, $datos->modeloVersion, $datos->nombreVendedor, $datos->emailVendedor);
      // echo $mailHtml;      
      // $resMail =  $this->SendDataMail($subject, $email, $mailHtml);//HABILITAR
      $resMail =  $this->SendDataMailCustom($subject, $email, $mailHtml, $datos->nombreVendedor, $datos->emailVendedor);//HABILITAR      
      // $resMail = 1;//DESHABILITAR
      return $resMail;
    }

    //Enviar cotizacion 
    public function enviarCotizacionVersion($subject, $email, $datos)
    {       
      $mailHtml = $this->enviarCotizacionVersionBody($datos);
      // echo $mailHtml;      
      $resMail =  $this->SendDataMail($subject, $email, $mailHtml);//HABILITAR      
      return $resMail;
    }

    //Enviar pedido de venta
    public function EnviarPedidoVenta($subject, $email, $datos)
    {
      $mailHtml = $this->EnviarPedidoVentaBody($datos);
      // echo $mailHtml;
      $resMail =  $this->EmailSmptNoPassCustom($email, "", $mailHtml, $subject, $datos->adjunto, $datos->nombreVendedor, $datos->correoVendedor);
      return $resMail;
    }


    //>>>>>>>>CUERPOS HTML  
    //Cuerpo para enviar las caracteristicas
    private function enviarCaracVersionBody($htmlImg, $caracteristicas, $versionNombre, $precioDesde, $nombreVendedor, $correoVendedor){
      $dirname = dirname(__DIR__);
      include  $dirname.'/common/config.php';
      // $segObj = new seguridadObj();
      // $param1 = $segObj->encriptarCadena('param1='.$idUsuario);
        $html = '<html><body>';
            $html .= '<table style="width:600px;" >';               
                $html .= "<thead>";
                    $html .= '<tr>
                                <td style="padding:25px 0;">
                                  <div><b>Vendedor</b>: '.$nombreVendedor.'</div>
                                  <div><b>Email Vendedor</b>: '.$correoVendedor.'</div>                                
                                </td>
                              </tr>';                    

                    $html .= '<tr><td>'.$htmlImg.'</td></tr>';
                    $html .= "<tr>";  
                       $html .= '<td style="padding: 10px;"><h3>'.$versionNombre.' .</h3><p>Desde '.$precioDesde.'</p></td>';                       
                   $html .= "</tr>"; 
                   $html .= "<thead>";     

                   $html .= "<tbody>";                   
                   $html .= "<tr>";                         
                       $html .= '<td style="padding: 10px;">
                                   '.$caracteristicas.'
                                </td>';                       
                   $html .= "</tr>";      
                $html .= "</tbody>";
                $html .= "<tfoot>";
                    $html .= "<tr>";
                        $html .= '<td style="font-size: 12px; margin-top: 30px;"><br/>Este mensaje es enviado de forma automática favor de no responder.</td>';
                    $html .= "</tr>"; 
                $html .= "</tfoot>";
            $html .= '</table>';        
        $html .= '</body></html>';
        return $html;
    }

    //Cuerpo para enviar los planes
    private function enviarPlanesVersionBody($datos, $versionNombre, $nombreVendedor, $correoVendedor){
      $dirname = dirname(__DIR__);
      include  $dirname.'/common/config.php';

      //Estilo
      $conceptoCss = "text-align:left;text-transform: uppercase;border-bottom: 1px solid;color: #004690;font-weight: 800;margin-bottom: 1em;padding-bottom: .5em;";

        $html = '<html><body>';
            $html .= '<table style="width:600px;" >';               
                $html .= "<thead>";
                   $html .= '<tr>
                                <td style="padding:25px 0;">
                                  <div><b>Vendedor</b>: '.$nombreVendedor.'</div>
                                  <div><b>Email Vendedor</b>: '.$correoVendedor.'</div>                                
                                </td>
                              </tr>';

                   $html .= "<tr>";  
                       $html .= '<td style="padding:10px;color: #004690;font-size:1.3em;text-transform:uppercase;"><h3>'.$versionNombre.' .</h3></td>';
                   $html .= "</tr>";      
                $html .= "</thead>";   

                   $html .= "<tbody>";
                   $html .= "<tr>";  
                       $html .= '<td style="padding: 10px;">';
                                  foreach ($datos as $plan) {
                                      $caracteristicas = str_replace("../upload/", $siteURL."upload/", $plan->caracteristicas);
                                      $html .= '<h3 style="'.$conceptoCss.'">'.$plan->conceptoPlan.'</h3>';
                                      $html .= '<div>'.$caracteristicas.'</div>';
                                  }
                            $html .= '                            
                                </td>';                       
                   $html .= "</tr>";      
                $html .= "</tbody>";
                $html .= "<tfoot>";
                    $html .= "<tr>";
                        $html .= '<td style="font-size: 12px; margin-top: 30px;"><br/>Este mensaje es enviado de forma automática favor de no responder.</td>';
                    $html .= "</tr>"; 
                $html .= "</tfoot>";
            $html .= '</table>';        
        $html .= '</body></html>';
        return $html;
    }

    //Cuerpo para enviar los requisitos
    private function enviarRequisitosVersionBody($datos, $versionNombre, $nombreVendedor, $correoVendedor){
      $dirname = dirname(__DIR__);
      include  $dirname.'/common/config.php';

      //Estilo
      $conceptoCss = "text-align:left;text-transform: uppercase;border-bottom: 1px solid;color: #004690;font-weight: 800;margin-bottom: 1em;padding-bottom: .5em;";

        $html = '<html><body>';
            $html .= '<table style="width:600px;" >';               
                $html .= "<thead>";                
                   $html .= '<tr>
                                <td style="padding:25px 0;">
                                  <div><b>Vendedor</b>: '.$nombreVendedor.'</div>
                                  <div><b>Email Vendedor</b>: '.$correoVendedor.'</div>                                
                                </td>
                              </tr>';

                   $html .= "<tr>";  
                       $html .= '<td style="padding:10px;color: #004690;font-size:1.3em;text-transform:uppercase;"><h3>'.$versionNombre.' .</h3></td>';
                   $html .= "</tr>"; 
                $html .= "</thead>";

                   $html .= "<tbody>";
                   $html .= "<tr>";  
                       $html .= '<td style="padding: 10px;">';                       
                                  foreach ($datos as $requisito) {
                                      $html .= '<h3 style="'.$conceptoCss.'">'.$requisito->concepto.'</h3>';
                                      $html .= '<div>'.$requisito->caracteristicas.'</div>';

                                      if($requisito->urlCartaBuro!="" || $requisito->urlCartaBuro!=""){                                        
                                        $html .= '<h5 style="'.$conceptoCss.'">Archivos asociados</h5>';
                                        if($requisito->urlCartaBuro!=""){
                                          $urlCartaBuro = $siteURL.str_replace("../", "", $requisito->urlCartaBuro);
                                          $html .= '<div><a href="'.$urlCartaBuro.'" target="_blank">Ver Carta Buro</a></div>';
                                        }
                                        if($requisito->urlSolicitudCred!=""){
                                          $urlSolicitudCred = $siteURL.str_replace("../", "", $requisito->urlSolicitudCred);
                                          $html .= '<div><a href="'.$urlSolicitudCred.'" target="_blank">Ver Solicitud Crédito</a></div>';
                                        }
                                      }                                      
                                  }
                            $html .= '                            
                                </td>';                       
                   $html .= "</tr>";      
                $html .= "</tbody>";
                $html .= "<tfoot>";
                    $html .= "<tr>";
                        $html .= '<td style="font-size: 12px; margin-top: 30px;"><br/>Este mensaje es enviado de forma automática favor de no responder.</td>';
                    $html .= "</tr>"; 
                $html .= "</tfoot>";
            $html .= '</table>';        
        $html .= '</body></html>';
        return $html;
    }

    //Cuerpo para enviar los precios
    private function enviarPreciosVersionBody($datos, $versionNombre, $nombreVendedor, $correoVendedor){
      $dirname = dirname(__DIR__);
      include  $dirname.'/common/config.php';

      //Estilo
      $conceptoCss = "text-align:left;text-transform: uppercase;border-bottom: 1px solid;color: #004690;font-weight: 800;margin-bottom: 1em;padding-bottom: .5em;";      
      $cssTable = "border-collapse: collapse;border: 1px solid black;width:600px;";

        $html = '<html><body>';
            $html .= '<div style="padding:25px 0;">
                          <div><b>Vendedor</b>: '.$nombreVendedor.'</div>
                          <div><b>Email Vendedor</b>: '.$correoVendedor.'</div>
                      </div>';

            $html .= '<div style="padding:10px;color: #004690;font-size:1.3em;text-transform:uppercase;"><h3>'.$versionNombre.' .</h3></div>';
            $html .= '<table style="width:600px;" border="1">';  
                $html .='<thead>';            
                    $html .='<tr>';
                              $html .='<th data-column-id="concepto">Concepto/Fuente</th>';
                              $html .='<th data-column-id="lista">Lista</th>';
                              $html .='<th data-column-id="contado">Contado</th>';
                              $html .='<th data-column-id="vwfs">VWFS</th>';
                      $html .='</tr>';
              $html .='</thead>';

              $html .= "<tbody>";
                
                $arrConceptos = $datos['arrConceptos'];
                foreach ($arrConceptos as $key=>$elemArr){
                  $html .='<tr>';
                  foreach ($elemArr as $keydos=>$elem){
                    if($keydos=="concepto"){                      
                      $html .='<td>'.$elem.'</td>';                      
                      // echo $keydos.'<br/>';
                    }
                                                      
                    if($keydos=="fuentes"){                        
                        $arrFuentes = $elem;
                        foreach ($arrFuentes as $elemFuente) {
                          $explFuentes = explode("|",$elemFuente);                        

                          //Columna lista
                          if($explFuentes[0]==1){
                            $html .='<td>$'.number_format($explFuentes[2],2).'</td>';
                          }
                          //Columna Contado
                          elseif($explFuentes[0]==2){
                            $html .='<td>$'.number_format($explFuentes[2],2).'</td>';
                          }
                          //Columna VWFS
                          elseif($explFuentes[0]==5){
                            $html .='<td>$'.number_format($explFuentes[2],2).'</td>';
                          }                                                  
                        }                                              
                      }
                  }
                  $html .='</tr>';
                }                
              $html .= "</tbody>";             


                /*$html .= "<tbody>";                
                   $html .= "<tr>";  
                       $html .= '<td style="padding:10px;color: #004690;font-size:1.3em;text-transform:uppercase;"><h3>'.$versionNombre.' .</h3></td>';
                   $html .= "</tr>";      
                   $html .= "<tbody>";
                   $html .= "<tr>";  
                       $html .= '<td style="padding: 10px;">';                       
                                  foreach ($datos as $precio) {
                                      $catTransmisionesObj = new catTransmisionesObj();
                                      $catTransmisionesObj->ObtDatosTransmisionPorId($precio->transmisionId);
                                      // echo '<h3>'.$precio->conceptoPrecio.' - '.$catTransmisionesObj->transmision.'</h3>';
                                      // echo "$ ".number_format($precio->precio,2).'<hr>';

                                      $html .= '<h3 style="'.$conceptoCss.'">'.$precio->conceptoPrecio.' - '.$catTransmisionesObj->transmision.'</h3>';
                                      $html .= '<div>'.'$ '.number_format($precio->precio,2).'</div>';
                                  }
                            $html .= '                            
                                </td>';                       
                   $html .= "</tr>";      
                $html .= "</tbody>";
                $html .= "<tfoot>";
                    $html .= "<tr>";
                        $html .= '<td style="font-size: 12px; margin-top: 30px;"><br/>Este mensaje es enviado de forma automática favor de no responder.</td>';
                    $html .= "</tr>"; 
                $html .= "</tfoot>"*/;
            $html .= '</table>';        
        $html .= '</body></html>';
        return $html;
    }

    //Cuerpo para enviar la cotizacion
    private function enviarCotizacionVersionBody($datos){
      $dirname = dirname(__DIR__);
      include  $dirname.'/common/config.php';

      //Estilo  
      $cssTable = "border-collapse: collapse;border: 1px solid black;";
      $cssLogo = "display: inline-block;width: 20%;vertical-align: middle;";
      $cssTituloCot = "display: inline-block;font-size: x-large;font-weight: 600;";    

        $html = '<html><body>'; 

              $html .= '<div>';
                $html .= '<div style="'.$cssLogo.'"><img src="'.$siteURL.'images/logo.png" height="80"></div>';
                $html .= '<div style="'.$cssTituloCot.'">COTIZACIÓN</div>';
              $html .= '</div>';
              $html .= '<br/>';

              //Datos del vendedor
              $html .= '<table border="1" style="'.$cssTable.'">';
                $html .= '<tr>';
                  $html .= '<td colspan="2">Datos del vendedor</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td>Nombre</td>';
                  $html .= '<td>'.$datos->nombreVend.'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td>Correo</td>';
                  $html .= '<td>'.$datos->emailVend.'</td>';
                $html .= '</tr>';
              $html .= '</table>'; 
              $html .= '<br/>'; 

              //Datos del cliente en caso de existir
              if($datos->nombreCliente!=""){
              $html .= '<table border="1" style="'.$cssTable.'">';
                $html .= '<tr>';
                  $html .= '<td colspan="2">Datos del cliente</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td>Nombre</td>';
                  $html .= '<td>'.$datos->nombreCliente.'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td>Correo</td>';
                  $html .= '<td>'.$datos->emailCliente.'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td>Teléfono</td>';
                  $html .= '<td>'.$datos->telCliente.'</td>';
                $html .= '</tr>';
              $html .= '</table>'; 
              $html .= '<br/>';
              }
              
              $html .= '<table border="1" style="'.$cssTable.'">';
                $html .= '<tr>';
                  $html .= '<td colspan="2">Datos del Vehículo</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td>Modelo</td>';
                  $html .= '<td>'.$datos->modelo.'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td>Versión</td>';
                  $html .= '<td>'.$datos->version.'</td>';
                $html .= '</tr>';
              $html .= '</table>'; 
              $html .= '<br/>';

              $html .= '<table border="1" style="'.$cssTable.'">';
                $html .= '<tr>';
                  $html .= '<td width="125">Costo</td>';
                  $html .= '<td width="114">'.$datos->textoPrecio.'</td>';
                  $html .= '<td width="84">&nbsp;</td>';
                  $html .= '<td width="108">Subtotal</td>';
                  $html .= '<td width="135">'.$datos->subtotal.'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td>Seguro Automotriz</td>';
                  $html .= '<td>'.$datos->sa.'</td>';
                  $html .= '<td>&nbsp;</td>';
                  $html .= '<td>Ptc. Enganche</td>';
                  $html .= '<td>'.$datos->ptcEnganche.'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td>Seguro de Vida</td>';
                  $html .= '<td>'.$datos->sv.'</td>';
                  $html .= '<td>&nbsp;</td>';
                  $html .= '<td>Enganche</td>';
                  $html .= '<td>'.$datos->enganche.'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td>Seguro Desempleo</td>';
                  $html .= '<td>'.$datos->sd.'</td>';
                  $html .= '<td>&nbsp;</td>';
                  $html .= '<td>Total a Financiar</td>';
                  $html .= '<td>'.$datos->taf.'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td>Garant&iacute;a Extendida</td>';
                  $html .= '<td>'.$datos->sge.'</td>';
                  $html .= '<td>&nbsp;</td>';
                  $html .= '<td></td>';
                  $html .= '<td></td>';
                $html .= '</tr>';

                $html .= '<tr>';
                  $html .= '<td>Comisión/Apertura</td>';
                  $html .= '<td>'.$datos->cxa.'</td>';
                  $html .= '<td>&nbsp;</td>';
                  // $html .= '<td>Tasa</td>';    
                  // $html .= '<td>'.$datos->tasa.'</td>';
                  $html .= '<td></td>';    
                  $html .= '<td></td>';
                $html .= '</tr>';    
              $html .= '</table>';
              $html .= '<br/>';

              // $html .= '<table border="1" style="'.$cssTable.'">';   
              //   $html .= '<tr>';   
              //     $html .= '<td colspan="5">MENSUALIDADES</td>';   
              //   $html .= '</tr>';    
              //   $html .= '<tr>';   
              //     $html .= '<td width="122">12</td>';    
              //     $html .= '<td width="115">'.$datos->m12.'</td>';
              //     $html .= '<td width="84">&nbsp;</td>';   
              //     $html .= '<td width="111">24</td>';    
              //     $html .= '<td width="134">'.$datos->m24.'</td>';
              //   $html .= '</tr>';    
              //   $html .= '<tr>';   
              //     $html .= '<td>36</td>';    
              //     $html .= '<td>'.$datos->m36.'</td>';    
              //     $html .= '<td>&nbsp;</td>';    
              //     $html .= '<td>48</td>';    
              //     $html .= '<td>'.$datos->m48.'</td>';   
              //   $html .= '</tr>';    
              //   $html .= '<tr>';   
              //     $html .= '<td>60</td>';    
              //     $html .= '<td>'.$datos->m60.'</td>';   
              //     $html .= '<td>&nbsp;</td>';    
              //     $html .= '<td>&nbsp;</td>';    
              //     $html .= '<td>&nbsp;</td>';
              //   $html .= '</tr>';    
              // $html .= '</table>';

              $html .= '<table border="1" style="width:600px; '.$cssTable.'">';
                $html .= '<tr>';
                  $html .= '<td colspan="5">MENSUALIDADES</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td>12</td>';
                  $html .= '<td>24</td>';
                  $html .= '<td>36</td>';
                  $html .= '<td>48</td>';
                  $html .= '<td>60</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td>'.$datos->m12.'</td>';
                  $html .= '<td>'.$datos->m24.'</td>';
                  $html .= '<td>'.$datos->m36.'</td>';
                  $html .= '<td>'.$datos->m48.'</td>';
                  $html .= '<td>'.$datos->m60.'</td>';
                $html .= '</tr>';
              $html .= '</table>';

               $html .= '<table style="width:600px;" >';
                $html .= "<tfoot>";
                    $html .= "<tr>";
                        $html .= '<td style="font-size: 12px; margin-top: 30px;"><br/>Este mensaje es enviado de forma automática favor de no responder.</td>';
                    $html .= "</tr>"; 
                $html .= "</tfoot>";
              $html .= '</table>';


            // $html .= '<table style="width:600px;" >';               
            //     $html .= "<tbody>";                
            //        $html .= "<tr>";  
            //            $html .= '<td style="padding:10px;color: #004690;font-size:1.3em;text-transform:uppercase;"><h3>'.$datos->modelo .' '. $datos->version.' .</h3></td>';
            //        $html .= "</tr>";      
            //        $html .= "<tbody>";
            //        $html .= "<tr>";  
            //            $html .= '<td style="padding: 10px;">';                       
                                  
            //                 $html .= '                            
            //                     </td>';                       
            //        $html .= "</tr>";      
            //     $html .= "</tbody>";
            //     $html .= "<tfoot>";
            //         $html .= "<tr>";
            //             $html .= '<td style="font-size: 12px; margin-top: 30px;"><br/>Este mensaje es enviado de forma automática favor de no responder.</td>';
            //         $html .= "</tr>"; 
            //     $html .= "</tfoot>";
            // $html .= '</table>';        
        $html .= '</body></html>';
        return $html;
    }


    //Cuerpo para enviar el pedido de venta de auto
    private function EnviarPedidoVentaBody($datos){
      $dirname = dirname(__DIR__);
      include  $dirname.'/common/config.php';

      // <br/> si no lo puede ver copie y pegue esta ruta '.$datos->rutaAbsAdjunto.' 
      //Estilo  
      $cssTable = "border-collapse: collapse;border: 1px solid black;";
      $cssLogo = "display: inline-block;width: 20%;vertical-align: middle;";
      $cssTituloCot = "display: inline-block;font-size: x-large;font-weight: 600;";    

        $html = '<html><body>'; 

              $html .= '<div>';
                $html .= '<div style="'.$cssLogo.'"><img src="'.$siteURL.'images/logo.png" height="80"></div>';
                $html .= '<div style="'.$cssTituloCot.'">PEDIDO DE VENTA DE AUTO NUEVO</div>';
              $html .= '</div>';
              $html .= '<br/>';

              $html .= '<div>';
                $html .= '<div>Ha recibido un nuevo pedido de venta de auto del siguiente agente, el detalle se encuentra en el archivo pdf adjunto:</div>';
              $html .= '</div>';
              $html .= '<br/>';              

              //Datos del vendedor
              $html .= '<table border="1" style="'.$cssTable.'">';
                $html .= '<tr>';
                  $html .= '<td colspan="2">Datos del vendedor</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td>Nombre</td>';
                  $html .= '<td>'.$datos->nombreVendedor.'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td>Correo</td>';
                  $html .= '<td>'.$datos->correoVendedor.'</td>';
                $html .= '</tr>';
              $html .= '</table>'; 
              $html .= '<br/>';
              
              // $html .= '<table border="1" style="'.$cssTable.'">';
              //   $html .= '<tr>';
              //     $html .= '<td colspan="2">Datos del Vehículo</td>';
              //   $html .= '</tr>';
              //   $html .= '<tr>';
              //     $html .= '<td>Modelo</td>';
              //     $html .= '<td>'.$datos->modelo.'</td>';
              //   $html .= '</tr>';
              //   $html .= '<tr>';
              //     $html .= '<td>Versión</td>';
              //     $html .= '<td>'.$datos->version.'</td>';
              //   $html .= '</tr>';
              // $html .= '</table>'; 
              // $html .= '<br/>';

              // $html .= '<table border="1" style="'.$cssTable.'">';
              //   $html .= '<tr>';
              //     $html .= '<td width="125">Costo</td>';
              //     $html .= '<td width="114">'.$datos->textoPrecio.'</td>';
              //     $html .= '<td width="84">&nbsp;</td>';
              //     $html .= '<td width="108">Subtotal</td>';
              //     $html .= '<td width="135">'.$datos->subtotal.'</td>';
              //   $html .= '</tr>';
              //   $html .= '<tr>';
              //     $html .= '<td>Seguro Automotriz</td>';
              //     $html .= '<td>'.$datos->sa.'</td>';
              //     $html .= '<td>&nbsp;</td>';
              //     $html .= '<td>Ptc. Enganche</td>';
              //     $html .= '<td>'.$datos->ptcEnganche.'</td>';
              //   $html .= '</tr>';
              //   $html .= '<tr>';
              //     $html .= '<td>Seguro de Vida</td>';
              //     $html .= '<td>'.$datos->sv.'</td>';
              //     $html .= '<td>&nbsp;</td>';
              //     $html .= '<td>Enganche</td>';
              //     $html .= '<td>'.$datos->enganche.'</td>';
              //   $html .= '</tr>';
              //   $html .= '<tr>';
              //     $html .= '<td>Seguro Desempleo</td>';
              //     $html .= '<td>'.$datos->sd.'</td>';
              //     $html .= '<td>&nbsp;</td>';
              //     $html .= '<td>Total a Financiar</td>';
              //     $html .= '<td>'.$datos->taf.'</td>';
              //   $html .= '</tr>';
              //   $html .= '<tr>';
              //     $html .= '<td>Comisión/Apertura</td>';
              //     $html .= '<td>'.$datos->cxa.'</td>';
              //     $html .= '<td>&nbsp;</td>';
              //     // $html .= '<td>Tasa</td>';    
              //     // $html .= '<td>'.$datos->tasa.'</td>';
              //     $html .= '<td></td>';    
              //     $html .= '<td></td>';
              //   $html .= '</tr>';    
              // $html .= '</table>';
              // $html .= '<br/>';
              
              // $html .= '<table border="1" style="width:600px; '.$cssTable.'">';
              //   $html .= '<tr>';
              //     $html .= '<td colspan="5">MENSUALIDADES</td>';
              //   $html .= '</tr>';
              //   $html .= '<tr>';
              //     $html .= '<td>12</td>';
              //     $html .= '<td>24</td>';
              //     $html .= '<td>36</td>';
              //     $html .= '<td>48</td>';
              //     $html .= '<td>60</td>';
              //   $html .= '</tr>';
              //   $html .= '<tr>';
              //     $html .= '<td>'.$datos->m12.'</td>';
              //     $html .= '<td>'.$datos->m24.'</td>';
              //     $html .= '<td>'.$datos->m36.'</td>';
              //     $html .= '<td>'.$datos->m48.'</td>';
              //     $html .= '<td>'.$datos->m60.'</td>';
              //   $html .= '</tr>';
              // $html .= '</table>';

               $html .= '<table style="width:600px;" >';
                $html .= "<tfoot>";
                    $html .= "<tr>";
                        $html .= '<td style="font-size: 12px; margin-top: 30px;"><br/>Este mensaje es enviado de forma automática favor de no responder.</td>';
                    $html .= "</tr>"; 
                $html .= "</tfoot>";
              $html .= '</table>';        
        $html .= '</body></html>';
        return $html;
    }



    //Html para correo de datos de acceso
    private function recuperarDatosAccesoMailBody($nombre, $email, $password){
        $html = '<html><body>';
            // $html .= '<table style="width:600px;" >';               
            //     $html .= "<tbody>";
            //        $html .= "<tr>";  
            //            $html .= '<td style="padding: 10px;">Estimado '.$nombre.' recibimos su solicitud de recuperacion de contraseña.</td>';                       
            //        $html .= "</tr>";      
            //        $html .= "<tbody>";
            //        $html .= "<tr>";  
            //            $html .= '<td style="padding: 10px;">Sus datos de acceso son:.</td>';
            //            $html .= '<td>E-mail: '.$email.'<br> Contraseña: '.$password.'</td>';
            //        $html .= "</tr>";      
            //     $html .= "</tbody>";
            //     $html .= "<tfoot>";
            //         $html .= "<tr>";
            //             $html .= '<td style="font-size: 12px; margin-top: 30px;"><br/>Este mensaje es enviado de forma automática favor de no responder.</td>';
            //         $html .= "</tr>"; 
            //     $html .= "</tfoot>";
            // $html .= '</table>';

          $html .= '<table style="width:600px;" >';            
              $html .= '<tbody>';
                $html .= '<tr>';
                  $html .= '<td colspan="2" style="padding: 10px;">Estimado <b>'.$nombre.'</b>, recibimos su solicitud de recuperación de contraseña. tus datos de acceso a la aplicación son:</td>';
                $html .= '</tr>';
              $html .= '</tbody>';
              $html .= '<tbody>';
                $html .= '<tr>';
                  $html .= '<td width="115"><b>E-mail:</b></td>';
                  $html .= '<td width="475">'.$email.'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td><b>Contraseña</b></td>';
                  $html .= '<td>'.$password.'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td colspan="2">&nbsp;</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td colspan="2"><p>Una vez iniciado sesión podrás cambiar tu contraseña <br/> en la sección de perfil.</p></td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td colspan="2">&nbsp;</td>';
                $html .= '</tr>';
              $html .= '</tbody>';
              $html .= '<tfoot>';
                $html .= '<tr>';
                  $html .= '<td colspan="2" style="font-size: 12px; margin-top: 30px;">Este mensaje es enviado de forma automática favor de no responder.</td>';
                $html .= '</tr>';
              $html .= '</tfoot>';
          $html .= '</table>';


        $html .= '</body></html>';
        return $html;
    }
    private function datosAccesoMailBody($nombre, $email, $password){
        $html = '<html><body>';

            // $html .= '<table style="width:600px;" >';               
            //     $html .= "<tbody>";
            //        $html .= "<tr>";  
            //            $html .= '<td style="padding: 10px;">Estimado '.$nombre.' los datos de acceso a su cuenta aguilar son:</td>';
            //            $html .= '<td>E-mail: '.$email.'<br> Contraseña: '.$password.'</td>';
            //        $html .= "</tr>";      
            //     $html .= "</tbody>";

            //     $html .= "<tfoot>";
            //         $html .= "<tr>";
            //             $html .= '<td style="font-size: 12px; margin-top: 30px;"><br/>Este mensaje es enviado de forma automática favor de no responder.</td>';
            //         $html .= "</tr>"; 
            //     $html .= "</tfoot>";
            // $html .= '</table>';  

            $html .= '<table style="width:600px;" >';            
              $html .= '<tbody>';
                $html .= '<tr>';
                  $html .= '<td colspan="2" style="padding: 10px;">Estimado <b>'.$nombre.'</b>, tus datos de acceso a la aplicación son:</td>';
                $html .= '</tr>';
              $html .= '</tbody>';
              $html .= '<tbody>';
                $html .= '<tr>';
                  $html .= '<td width="115"><b>E-mail:</b></td>';
                  $html .= '<td width="475">'.$email.'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td><b>Contraseña</b></td>';
                  $html .= '<td>'.$password.'</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td colspan="2">&nbsp;</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td colspan="2"><p>Una vez iniciado sesión podrás cambiar tu contraseña <br/> en la sección de perfil.</p></td>';
                $html .= '</tr>';
                $html .= '<tr>';
                  $html .= '<td colspan="2">&nbsp;</td>';
                $html .= '</tr>';
              $html .= '</tbody>';
              $html .= '<tfoot>';
                $html .= '<tr>';
                  $html .= '<td colspan="2" style="font-size: 12px; margin-top: 30px;">Este mensaje es enviado de forma automática favor de no responder.</td>';
                $html .= '</tr>';
              $html .= '</tfoot>';
            $html .= '</table>';


        $html .= '</body></html>';

        return $html;
    }

}
