<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/brules/catProspectosObj.php';

class exportarExcelObj {

    //Metodo para exportar prospectos
    public function ExportarProspectos($POST){
        $timeZ = obtDateTimeZone();
        $prosObj = new catProspectosObj();        
        $arrMotivocompra = (object)array("vi_valordinero"=>"Valor por dinero", "vi_seguridad"=>"Seguridad", "vi_estatuspre"=>"Estatus/Prestigio", "vi_apdeportiva"=>"Apariencia Deportiva",
                         "vi_desempenio"=>"Desempe&ntilde;o", "vi_confort"=>"Confort", "vi_disenio"=>"Dise&ntilde;o", "vi_respaldomarca"=>"Respaldo Marca VW", 
                         "vi_economia"=>"Econom&iacute;a"); //, "vi_otro_mot"=>"Otro"

        $arrReqFun = (object)array("vi_absebd"=>"ABS/EBD", "vi_aireacondicionado"=>"Aire Acondicionado", "vi_rastreador"=>"Rastreador", "vi_remolque"=>"Remolque", "vi_ventelectricas"=>"Ventanas El&eacute;ctricas",
                           "vi_navsatelital"=>"Navegaci&oacute;n", "vi_quemacocos"=>"Quema Cocos", "vi_estandar"=>"Estandar", "vi_automatico"=>"Automat&iacute;co");

        $arrEquiFun = (object)array("vi_bluetooth"=>"Bluetooth", "vi_rines"=>"Rines", "vi_loderas"=>"Loderas", "vi_portaequipajes"=>"Portaequipajes", "vi_pantiasalto"=>"Pel&iacute;cula Anti-asalto",
                            "vi_lucesxenon"=>"Luces de Xenon", "vi_vidriospol"=>"Vidrios Polarizados", "vi_ganchoremolque"=>"Gancho de Remolque", "vi_farosantiniebla"=>"Faros Anti-niebla",
                            "vi_sensoresreversa"=>"Sensores de Reversa",
                           );

        $colProspectos = $prosObj->obtProspectosParaExportar(true, $_POST['agenciaId'], $_POST['vendedorId'], $_POST['prospecto'], $_POST['fechaDel'], $_POST['fechaAl']);
        // echo "<pre>";
        // // print_r($_POST);
        // print_r($colProspectos);
        // echo "</pre>";
       

      set_time_limit(36000);
      date_default_timezone_set('America/Mexico_City');

      if (PHP_SAPI == 'cli')
              die('Este archivo solo se puede ver desde un navegador web');

      // Se crea el objeto PHPExcel
      $objPHPExcel = new PHPExcel();

      // Se asignan las propiedades del libro
      $objPHPExcel->getProperties()->setCreator("ZMotors") //Autor
                                  ->setLastModifiedBy("ZMotors") //Ultimo usuario que lo modifico
                                  ->setTitle("Reporte Prospectos")
                                  ->setSubject("Reporte Prospectos")
                                  ->setDescription("Reporte Prospectos")
                                  ->setKeywords("Reporte, Prospectos")
                                  ->setCategory("Reportes");
      
      $titulosColumnas = array('Fecha Alta', 'Agencia', 'Vendedor', 'Nombre', 'Direcci&oacute;n', 'Tel&eacute;fono', 'Email', 'Marca', 'Modelo', 'A&ntilde;o', 'Desea intercambiar este veh&iacute;culo', 'C&oacute;mo fue/es financiado este veh&iacute;culo', 'Uso del veh&iacute;culo actual', 'Donde se usa el veh&iacute;culo actual', 'Qu&eacute; equipamiento y caracter&iacute;sticas valora de su veh&iacute;culo actual', 'Porque raz&oacute;n quiere cambiar su veh&iacute;culo',
                               'Modelo', 'A&ntilde;o', 'Qu&eacute; uso tendra el nuevo autom&oacute;vil', 'Precio dispuesto a pagar', 'Otros modelos dentro del rango de inter&eacute;s', 'Requiere una prueba de manejo', 'Requiere financiamiento', 'Fecha de la prueba de manejo', 'Color preferido', 'Motivos para la compra', 'Requisitos funcionales', 'Equipamiento funcionales', 'Cu&aacute;ntos miembros hay en su familia', 'Qui&eacute;n va a manejar el veh&iacute;culo', 
                               'Cada cuando cambia su veh&iacute;culo', 'Fecha de propuesta de compra', 'Fecha propuesta para la llamada de seguimiento', 'Hora propuesta seguimiento', 'Qu&eacute; es lo que le gusta de este veh&iacute;culo', 'Comentarios');
      
      $tituloFechaConsulta = "Reporte de prospectos el ".html_entity_decode("d&iacute;a", ENT_QUOTES, "UTF-8")." ".$timeZ->fechaF2;
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $tituloFechaConsulta);
      $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:AK1');

      $totalTituloCol = count($titulosColumnas);
      $colA = 'A';       
      $filaTitulo = 2;
      for($i=1; $i<=$totalTituloCol; $i++){                        
          $objPHPExcel->getActiveSheet()->setCellValue($colA.$filaTitulo, html_entity_decode($titulosColumnas[$i-1], ENT_QUOTES, "UTF-8"));
          $colA++;
      } 

      // $colA = 'A';
      $contLetra = "$colA"."1";
      $iIns=3;

      //CICLO
      
      if(count($colProspectos) > 0)
      {                            
          // $colBucle = "A"; 
          $filaBucle = 3;

          foreach ($colProspectos as $detalle) {
             $usuariosObj = new usuariosObj();
             $usuariosObj->UserByID((isset($detalle->usuarioId))?$detalle->usuarioId:0);
             $vendedor = $usuariosObj->nombre;
             $datosJson = json_decode($detalle->datosJson);             
             
             // echo "<pre>";
             // print_r($datosJson);
             // echo "</pre>";

            $objPHPExcel->getActiveSheet()->setCellValue("A".$filaBucle, (isset($detalle->fechaAlta))?$detalle->fechaAlta:"");
            $objPHPExcel->getActiveSheet()->setCellValue("B".$filaBucle, (isset($detalle->agencia))?$detalle->agencia:"");
            $objPHPExcel->getActiveSheet()->setCellValue("C".$filaBucle, $vendedor);
            $objPHPExcel->getActiveSheet()->setCellValue("D".$filaBucle, (isset($detalle->nombre))?$detalle->nombre:"");
            $objPHPExcel->getActiveSheet()->setCellValue("E".$filaBucle, (isset($detalle->direccion))?$detalle->direccion:"");
            $objPHPExcel->getActiveSheet()->setCellValue("F".$filaBucle, (isset($detalle->telefono))?$detalle->telefono:"");
            $objPHPExcel->getActiveSheet()->setCellValue("G".$filaBucle, (isset($detalle->email))?$detalle->email:"");
            $objPHPExcel->getActiveSheet()->setCellValue("H".$filaBucle, (isset($datosJson->va_marca))?$datosJson->va_marca:"");
            $objPHPExcel->getActiveSheet()->setCellValue("I".$filaBucle, (isset($datosJson->va_modelo))?$datosJson->va_modelo:"");
            $objPHPExcel->getActiveSheet()->setCellValue("J".$filaBucle, (isset($datosJson->va_anio))?$datosJson->va_anio:"");
            $objPHPExcel->getActiveSheet()->setCellValue("K".$filaBucle, (isset($datosJson->va_intervehiculo))?$datosJson->va_intervehiculo:"");
            $objPHPExcel->getActiveSheet()->setCellValue("L".$filaBucle, (isset($datosJson->va_financiado))?$datosJson->va_financiado:"");
            $objPHPExcel->getActiveSheet()->setCellValue("M".$filaBucle, (isset($datosJson->va_usovehiculo))?$datosJson->va_usovehiculo:"");
            $objPHPExcel->getActiveSheet()->setCellValue("N".$filaBucle, (isset($datosJson->va_dondeusavehiculo))?$datosJson->va_dondeusavehiculo:"");
            $objPHPExcel->getActiveSheet()->setCellValue("O".$filaBucle, (isset($datosJson->va_equivalor))?$datosJson->va_equivalor:"");
            $objPHPExcel->getActiveSheet()->setCellValue("P".$filaBucle, (isset($datosJson->va_cambiarvehiculo))?$datosJson->va_cambiarvehiculo:"");
            $objPHPExcel->getActiveSheet()->setCellValue("Q".$filaBucle, (isset($datosJson->vi_modelo))?$datosJson->vi_modelo:"");
            $objPHPExcel->getActiveSheet()->setCellValue("R".$filaBucle, (isset($datosJson->vi_anio))?$datosJson->vi_anio:"");
            $objPHPExcel->getActiveSheet()->setCellValue("S".$filaBucle, (isset($datosJson->vi_uso))?$datosJson->vi_uso:"");
            $objPHPExcel->getActiveSheet()->setCellValue("T".$filaBucle, (isset($datosJson->vi_precio))?$datosJson->vi_precio:"");            
            $vi_modelo1 = (isset($datosJson->vi_modelo1))?$datosJson->vi_modelo1.",":"";
            $vi_modelo2 = (isset($datosJson->vi_modelo2))?$datosJson->vi_modelo2.",":"";
            $vi_modelo3 = (isset($datosJson->vi_modelo3))?$datosJson->vi_modelo3.",":"";            
            $objPHPExcel->getActiveSheet()->setCellValue("U".$filaBucle, ($vi_modelo1!="" && $vi_modelo1!=",")?$vi_modelo1:"".($vi_modelo2!="" && $vi_modelo2!=",")?$vi_modelo2:"".($vi_modelo3!="" && $vi_modelo3!=",")?$vi_modelo3:"");
            $objPHPExcel->getActiveSheet()->setCellValue("V".$filaBucle, (isset($datosJson->vi_pruebamanejo))?$datosJson->vi_pruebamanejo:"");
            $objPHPExcel->getActiveSheet()->setCellValue("W".$filaBucle, (isset($datosJson->vi_financiamiento))?$datosJson->vi_financiamiento:"");
            $objPHPExcel->getActiveSheet()->setCellValue("X".$filaBucle, (isset($datosJson->vi_fpruebamanejo))?$datosJson->vi_fpruebamanejo:"");
            $vi_color1 = (isset($datosJson->vi_color1))?$datosJson->vi_color1.",":"";
            $vi_color2 = (isset($datosJson->vi_color2))?$datosJson->vi_color2.",":"";
            $vi_color3 = (isset($datosJson->vi_color3))?$datosJson->vi_color3.",":"";
            $objPHPExcel->getActiveSheet()->setCellValue("Y".$filaBucle, ($vi_color1!="" && $vi_color1!=",")?$vi_color1:"".($vi_color2!="" && $vi_color2!=",")?$vi_color2:"".($vi_color3!="" && $vi_color3!=",")?$vi_color3:"");
            if(count($datosJson->vi_motivocompra)>0){
               $arrMotivos = array(); 
               foreach ($datosJson->vi_motivocompra as $elemMC){
                  foreach ($arrMotivocompra as $key => $elemArr){
                    if($key==$elemMC){
                      $arrMotivos[] = $elemArr;                      
                      break;
                    }
                  }                                              
                }
                $vi_motivocompra = implode(",", $arrMotivos);
                //Otro
                // foreach ($datosJson->vi_motivocompra as $elemMC){                                                                                            
                //   if($elemMC == "vi_otro_mot"){                     
                //     <input type="text" id="vi_otro_mot_inp" name="vi_otro_mot_inp" value="echo $datosJson->vi_otro_mot_inp;" class="form-control" readonly>                      
                //   }
                // }
            }else{
                $vi_motivocompra = "";
            }
            $objPHPExcel->getActiveSheet()->setCellValue("Z".$filaBucle, $vi_motivocompra);

            if(count($datosJson->vi_requisitos)>0){
              $arrRequisitos = array(); 
               foreach ($datosJson->vi_requisitos as $elemRF){
                  foreach ($arrReqFun as $key => $elemArr){
                    if($key==$elemRF){
                      $arrRequisitos[] = $elemArr;                      
                      break;
                    }
                  }                                              
                }
                $vi_requisitos = implode(",", $arrRequisitos);
                //Otro
                // foreach ($datosJson->vi_requisitos as $elemRF){                                                                                            
                //   if($elemRF == "vi_otro_req"){                     
                //     <input type="text" id="vi_otro_req_inp" name="vi_otro_req_inp" value="echo $datosJson->vi_otro_req_inp;" class="form-control" readonly>                      
                //   }
                // }
            }else{
                $vi_requisitos = "";
            }
            $objPHPExcel->getActiveSheet()->setCellValue("AA".$filaBucle, $vi_requisitos);
            if(count($datosJson->vi_equipamiento)>0){
               $arrEquipamiento = array();  
               foreach ($datosJson->vi_equipamiento as $elemEQ){
                  foreach ($arrEquiFun as $key => $elemArr){
                    if($key==$elemEQ){
                      $arrEquipamiento[] = $elemArr;
                      break;
                    }
                  }                                              
                }
                 $vi_equipamiento = implode(",", $arrEquipamiento);
                //Otro
                // foreach ($datosJson->vi_equipamiento as $elemEQ){                                                                                            
                //   if($elemEQ == "vi_otro_eq"){                     
                //     // <input type="text" id="vi_otro_eq_inp" name="vi_otro_eq_inp" value="echo $datosJson->vi_otro_eq_inp;" class="form-control" readonly>                      
                //   }
                // }
            }else{
                $vi_equipamiento = "";
            }
            $objPHPExcel->getActiveSheet()->setCellValue("AB".$filaBucle, $vi_equipamiento);
            $objPHPExcel->getActiveSheet()->setCellValue("AC".$filaBucle, (isset($datosJson->vi_miembrosfamilia))?$datosJson->vi_miembrosfamilia:"");
            $objPHPExcel->getActiveSheet()->setCellValue("AD".$filaBucle, (isset($datosJson->vi_quienmaneja))?$datosJson->vi_quienmaneja:"");
            $objPHPExcel->getActiveSheet()->setCellValue("AE".$filaBucle, (isset($datosJson->vi_cambiavehiculo))?$datosJson->vi_cambiavehiculo:"");
            $objPHPExcel->getActiveSheet()->setCellValue("AF".$filaBucle, (isset($datosJson->vi_fpropuestacompra))?$datosJson->vi_fpropuestacompra:"");
            $objPHPExcel->getActiveSheet()->setCellValue("AG".$filaBucle, (isset($datosJson->vi_fseguimiento))?$datosJson->vi_fseguimiento:"");
            $objPHPExcel->getActiveSheet()->setCellValue("AH".$filaBucle, (isset($datosJson->vi_horaseguimiento))?$datosJson->vi_horaseguimiento:"");
            $objPHPExcel->getActiveSheet()->setCellValue("AI".$filaBucle, (isset($datosJson->gustoporvehiculo))?$datosJson->gustoporvehiculo:"");
            $objPHPExcel->getActiveSheet()->setCellValue("AJ".$filaBucle, (isset($datosJson->gralcomentario))?$datosJson->gralcomentario:"");

            $filaBucle++;
          }
          // exit();
      }else{
        echo "No existe datos para exportar";
        exit();
      }
      
    //FIN CICLO

                                
      $estiloTituloColumnas = array('font' => array('name'=> 'Arial', 
                                                    'size'=>8, 
                                                    'bold'=> false, 
                                                    'color'=> array('rgb' => '000000')
                                                   ),
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                         'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                         'wrap'=>TRUE
                                                       )
                                  );

      $estiloInformacion = new PHPExcel_Style();
      $estiloInformacion->applyFromArray(array('font' => array('name'=>'Arial',
                                                               'size'=>8,
                                                               'color'=> array('rgb' => '000000')
                                                              )
                                              )
                                        );

      $filaGris = array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => 'D8D8D8')
                       );
      $estiloFechaConsulta = array('font' => array('name'=> 'Arial',
                                                    'size'=>11,
                                                    'bold'=> true,
                                                    'color'=> array('rgb' => '000000')                    
                                                   ),
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                         'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                         'wrap'=>TRUE
                                                       )
                                  );

     $estiloEncabezados = array('font' => array('name'=> 'Arial',
                                                    'size'=> 9,
                                                    'bold'=> true,
                                                    'color'=> array('rgb' => '000000')                    
                                                   ),
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                         'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                         'wrap'=>TRUE
                                                       )
                                  );


      $objPHPExcel->getActiveSheet()->getStyle('A1:AK1')->applyFromArray($estiloTituloColumnas);
      // $objPHPExcel->getActiveSheet()->getStyle('A3:AK3')->applyFromArray($estiloEncabezados);
      $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, 'A3:AK3'.($filaBucle-1));

      for($j = 'A'; $j <=$contLetra; $j++){
          $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($j)->setAutoSize(TRUE);
      }
      //Estilo a fecha consulta
      $objPHPExcel->getActiveSheet()->getStyle("A1:AK1")->applyFromArray($estiloFechaConsulta);
          
      
      // Se asigna el nombre a la hoja
      $nReporte = "Reporte Prospectos";
      $objPHPExcel->getActiveSheet()->setTitle($nReporte);
      // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
      $objPHPExcel->setActiveSheetIndex(0);
      // Inmovilizar paneles         
      $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,3);

      $titleFile = str_replace(" ", "", $nReporte)."_".$timeZ->fechaF2.".xls";
      header('Content-Type: application/vnd.ms-excel');
      header("Content-Disposition: attachment;filename=$titleFile");
      header('Cache-Control: max-age=0');

      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
      $objWriter->save('php://output');
      exit;
    }


    //Configuracion de estilos
    private function configEstilos()
    {
        $estiloTituloColumnas = array('font' => array('name'=> 'Arial', 
                                                    'size'=>8, 
                                                    'bold'=> false, 
                                                    'color'=> array('rgb' => '000000')
                                                   ),
                                      'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                           'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                           'wrap'=>TRUE
                                                          )
                                  );

        $estiloInformacion = new PHPExcel_Style();
        $estiloInformacion->applyFromArray(array('font' => array('name'=>'Arial',
                                                               'size'=>8,
                                                               'color'=> array('rgb' => '000000')
                                                              )
                                              )
                                        );

        $filaGris = array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                          'startcolor' => array('rgb' => 'D8D8D8')
                         );

        $estiloFechaConsulta = array('font' => array('name'=> 'Arial',
                                                    'size'=>11,
                                                    'bold'=> true,
                                                    'color'=> array('rgb' => '000000')                    
                                                   ),
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                         'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                         'wrap'=>TRUE
                                                       )
                                  );

        $estiloEncabezados = array('font' => array('name'=> 'Arial',
                                                    'size'=> 9,
                                                    'bold'=> true,
                                                    'color'=> array('rgb' => '000000')                    
                                                   ),
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                         'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                         'wrap'=>TRUE
                                                       )
                                  );


        return (object)array("estiloTituloColumnas"=>$estiloTituloColumnas, "estiloInformacion"=>$estiloInformacion, "filaGris"=>$filaGris, "estiloFechaConsulta"=>$estiloFechaConsulta, "estiloEncabezados"=>$estiloEncabezados);
    }


    // Exportar estadistica     
    public function ExportarEstadisticas(){
      $timeZ = obtDateTimeZone();
      $estadisticaAppObj = new estadisticasAppObj();
      $colEstadisticas = $estadisticaAppObj->obtEstadisticasRpt($_POST['fechaDel'], $_POST['fechaAl'], $_POST['opcion'], $_POST['agente'], $_POST['modelo']);

      // echo "<pre>";
      // // print_r($_POST);
      // print_r($colEstadisticas);
      // echo "</pre>";
                           
      set_time_limit(36000);
      date_default_timezone_set('America/Mexico_City');

      if (PHP_SAPI == 'cli')
              die('Este archivo solo se puede ver desde un navegador web');

      // Se crea el objeto PHPExcel
      $objPHPExcel = new PHPExcel();

      // Se asignan las propiedades del libro
      $objPHPExcel->getProperties()->setCreator("ZMotors") //Autor
                                  ->setLastModifiedBy("ZMotors") //Ultimo usuario que lo modifico
                                  ->setTitle("Reporte Estadistica")
                                  ->setSubject("Reporte Estadistica")
                                  ->setDescription("Reporte Estadistica")
                                  ->setKeywords("Reporte, Estadistica")
                                  ->setCategory("Reportes");
      
      $titulosColumnas = array('Fecha Registrado', 'Vendedor', 'Evento', 'Modelo', 'Versi&oacute;n', 'Correo prospecto', 'Prospecto');
      
      $tituloFechaConsulta = "Reporte estadisticas el ".html_entity_decode("d&iacute;a", ENT_QUOTES, "UTF-8")." ".$timeZ->fechaF2;
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $tituloFechaConsulta);
      $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');

      $totalTituloCol = count($titulosColumnas);
      $colA = 'A';       
      $filaTitulo = 2;
      for($i=1; $i<=$totalTituloCol; $i++){                        
          $objPHPExcel->getActiveSheet()->setCellValue($colA.$filaTitulo, html_entity_decode($titulosColumnas[$i-1], ENT_QUOTES, "UTF-8"));
          $colA++;
      } 

      // $colA = 'A';
      $contLetra = "$colA"."1";
      $iIns=3;

      //CICLO
      
      // if(count($colEstadisticas) > 0)
      // {
          // $colBucle = "A"; 
          $filaBucle = 3;

          foreach ($colEstadisticas as $detalle) {
            $objPHPExcel->getActiveSheet()->setCellValue("A".$filaBucle, (isset($detalle->fechaLog2))?$detalle->fechaLog2:"");
            $objPHPExcel->getActiveSheet()->setCellValue("B".$filaBucle, (isset($detalle->nombre))?ucwords($detalle->nombre):"");
            $objPHPExcel->getActiveSheet()->setCellValue("C".$filaBucle, (isset($detalle->accion))?$detalle->accion:"");
            $objPHPExcel->getActiveSheet()->setCellValue("D".$filaBucle, (isset($detalle->modelo))?$detalle->modelo:"");
            $objPHPExcel->getActiveSheet()->setCellValue("E".$filaBucle, (isset($detalle->version))?$detalle->version:"");
            $objPHPExcel->getActiveSheet()->setCellValue("F".$filaBucle, (isset($detalle->emailProspecto))?$detalle->emailProspecto:"");
            $objPHPExcel->getActiveSheet()->setCellValue("G".$filaBucle, (isset($detalle->prospecto))?$detalle->prospecto:"");
          
            $filaBucle++;
          }
          // exit();
      // }else{
      //   echo "No existe datos para exportar";
      //   exit();
      // }      
      //FIN CICLO

                                
      $estiloTituloColumnas = array('font' => array('name'=> 'Arial', 
                                                    'size'=>8, 
                                                    'bold'=> false, 
                                                    'color'=> array('rgb' => '000000')
                                                   ),
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                         'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                         'wrap'=>TRUE
                                                       )
                                  );

      $estiloInformacion = new PHPExcel_Style();
      $estiloInformacion->applyFromArray(array('font' => array('name'=>'Arial',
                                                               'size'=>8,
                                                               'color'=> array('rgb' => '000000')
                                                              )
                                              )
                                        );

      $filaGris = array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => 'D8D8D8')
                       );
      $estiloFechaConsulta = array('font' => array('name'=> 'Arial',
                                                    'size'=>11,
                                                    'bold'=> true,
                                                    'color'=> array('rgb' => '000000')                    
                                                   ),
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                         'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                         'wrap'=>TRUE
                                                       )
                                  );

      $estiloEncabezados = array('font' => array('name'=> 'Arial',
                                                    'size'=> 9,
                                                    'bold'=> true,
                                                    'color'=> array('rgb' => '000000')                    
                                                   ),
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                         'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                         'wrap'=>TRUE
                                                       )
                                  );


      $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($estiloTituloColumnas);
      $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, 'A3:H2'.($filaBucle-1));

      for($j = 'A'; $j <=$contLetra; $j++){
          $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($j)->setAutoSize(TRUE);
      }
      //Estilo a fecha consulta
      $objPHPExcel->getActiveSheet()->getStyle("A1:H1")->applyFromArray($estiloFechaConsulta);
          
      
      // Se asigna el nombre a la hoja
      $nReporte = "Reporte Estadisticas";
      $objPHPExcel->getActiveSheet()->setTitle($nReporte);
      // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
      $objPHPExcel->setActiveSheetIndex(0);
      // Inmovilizar paneles         
      $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,3);

      $titleFile = str_replace(" ", "", $nReporte)."_".$timeZ->fechaF2.".xls";
      header('Content-Type: application/vnd.ms-excel');
      header("Content-Disposition: attachment;filename=$titleFile");
      header('Cache-Control: max-age=0');

      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
      $objWriter->save('php://output');
      exit;
    }

}
