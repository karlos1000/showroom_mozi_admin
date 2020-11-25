<?php
$dirname = dirname(__DIR__);
include_once  $dirname.'/brules/dompdf/dompdf_config.inc.php';
include $dirname.'/common/config.php';

class generarPdfObj {
    public function GenerarFormatoPDF($titulo, $cuerpoHtml, $orientation=0, $tipoDescarga=0, $rutaDescarga=""){
     //ob_start();
        // create new PDF document
        $html = '
        <html>
            <head>
            </head>

            <body>
                 ';

        $html .= $cuerpoHtml;

        $html .= '</body>
               </html>';

        //set_time_limit(0);
        $dompdf = new DOMPDF();
        if($orientation==0){
            $dompdf->set_paper("letter", "portrait"); //selecciona la orientación de la hoja en este caso horizontal
        }else{
            $dompdf->set_paper("letter", "landscape"); //selecciona la orientación de la hoja en este caso horizontal
        }
        //ini_set("memory_limit","32M");
        $dompdf->load_html($html);
        $dompdf->render();

        //Salvar automaticamente el pdf en la ruta especifica
        if($tipoDescarga==0){
            $dompdf->stream($titulo.".pdf", array('Attachment'=>0));   //abre en el navegador
        }
        //Salvar automaticamente el pdf en la ruta especifica
        if($tipoDescarga==1){            
            file_put_contents($rutaDescarga.$titulo.".pdf", $dompdf->output());
            // file_put_contents('../upload/pdfgarantias/'.$titulo.".pdf", $dompdf->output());
        }
        if($tipoDescarga==2){
            $dompdf->stream($titulo.".pdf", array('Attachment'=>1));   //abre el popop para guardarlo en disco
        }

        //ob_end_clean();
    }

}

?>
