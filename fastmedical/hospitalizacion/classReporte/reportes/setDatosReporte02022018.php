<?php

/* ../../classReporte/reportes/setDatosReporte.php?p1=recetaUnicaEstandarizada&p2=1&p3=4171974&p4=286267&p5=0030860&p6=4
 * 
 * p2 => modo de impresion de reporte --> (1=>label y datos, 0=>solo datos)
 * p3 => codigo de programacion
 * p4 => codigo de persona
 * p5 => codigo de medico
 * p6 => idReporte 
 */
header('Content-Type: text/html; charset=iso-8859-1');
require_once('../../ccontrol/control/ActionActoMedico.php');
require_once('../../ccontrol/control/ActionReporte.php');
require_once('../../clogica/LActoMedico.php');
require_once('../../ccontrol/control/ActionActoMedico.php');
try {
    $o_ActionReporte = new ActionReporte();
    $opcion = $_REQUEST["p1"];
    $modo = $_REQUEST["p2"];
    $parametros = array();
    $parametros["PDF_PAGE_FORMAT"] = "A4";
    $parametros["PDF_MARGIN_HEADER"] = 5;
    $parametros["PDF_MARGIN_FOOTER"] = 10;
    $parametros["AUTO_PAGE_BREAK"] = true;
    $parametros["PDF_MARGIN_BOTTOM"] = 25;
    $parametros["PDF_PAGE_ORIENTATION"] = "P";
    $parametros["PDF_MARGIN_LEFT"] = 15;
    $parametros["PDF_MARGIN_TOP"] = 27;
    $parametros["PDF_MARGIN_RIGHT"] = 15;
    $parametros["PRINT_HEADER"] = true;
    $parametros["PRINT_FOOTER"] = true;

    switch ($opcion) {
        case "recetaMedica": {
                require_once('generadorDeReportes.php');
                $setdat = new PluginMYPDF();

                $codProgramacion = $_REQUEST["p3"];
                $codPaciente = $_REQUEST["p4"];
                $codMedico = $_REQUEST["p5"];
                $idReporte = $_REQUEST["p6"];
                $nombreReporte = "RecetaMedica" . $codProgramacion;

                /* ===================================================================================================== */
                /* =======================================   Datos de Cabecera   ============================================ */
                $labelCabecera = $o_ActionReporte->labelReportePdf("recetamedica", $idReporte, 1);
                $datosPaciente = $o_ActionReporte->datosPaciente($codPaciente);
                $datosCabecera = array();
                $datosCabecera[2] = $codProgramacion;
                $datosCabecera[4] = $datosPaciente[0][0];
                $datosCabecera[5] = $datosPaciente[0][1] . " " . $datosPaciente[0][2] . " " . $datosPaciente[0][3];
                $datosCabecera[6] = date('d-m-Y', time() + 3600);
                $datosCabecera[7] = date('H:i:s', time() + 3600);

                /* ===================================================================================================== */
                /* =======================================   Datos de Detalle   ============================================ */
                $labelDetalle = $o_ActionReporte->labelReportePdf("recetamedica", $idReporte, 2);
                $datosDet = $o_ActionReporte->datosRecetaMedica($codProgramacion);
                $datosDetalle = array();
                foreach ($datosDet as $i => $value) {
                    $datosDetalle[$i][0] = $datosDet[$i][3];
                    $datosDetalle[$i][1] = utf8_encode($datosDet[$i][6]);
                    $datosDetalle[$i][2] = $datosDet[$i][4];
//                    $datosDetalle[$i][3]=$datosDet[$i][7];
                }

                /* ===================================================================================================== */
                /* =======================================   Datos de Pie   ============================================ */
                $datosPie = array();
                $labelPie = $o_ActionReporte->labelReportePdf("recetamedica", $idReporte, 3);
                $datosMedico = $o_ActionReporte->datosMedico($codMedico);
                $datosPie[0] = $datosMedico[0][1] . " " . $datosMedico[0][2] . " " . $datosMedico[0][3];
                $datosPie[1] = $datosMedico[0][0];

                /* ===================================================================================================== */
                /* =====================================   Todo Los atributos   ======================================== */
                $atributosReceta = $o_ActionReporte->atributosRecetaMedica($idReporte);
                $parametros["PDF_PAGE_FORMAT"] = "RECETA_MEDICA";
                $parametros["PRINT_HEADER"] = false;
                $parametros["PRINT_FOOTER"] = false;
                $parametros["PDF_MARGIN_LEFT"] = 10;
                $parametros["PDF_MARGIN_RIGHT"] = 10;
                $parametros["PDF_MARGIN_TOP"] = 10;
                $parametros["AUTO_PAGE_BREAK"] = false;
                $parametros["PDF_PAGE_ORIENTATION"] = "P";
                $setdat->generarMYPDF($atributosReceta, $labelCabecera, $labelDetalle, $labelPie, $datosCabecera, $datosDetalle, $datosPie, $modo, $nombreReporte, $parametros);
                break;
            }
        case "recetaUnicaEstandarizada": {
                require_once('generarRecetaMedica.php');
                $setdat = new generarMYPDFRME();

                $codProgramacion = $_REQUEST["p3"];
                $codPersona = $_REQUEST["p4"]; //para el paciente
                $codMedico = $_REQUEST["p5"];
                $idReporte = $_REQUEST["p6"];
                $nombreReporte = "RecetaMedica" . $codProgramacion;
                /* ===================================================================================================== */
                /* =======================================   Datos de Cabecera   ============================================ */
                $labelCabecera = $o_ActionReporte->labelReportePdf("recetaUnicaEstandarizada", $idReporte, 1);
                $datosPaciente = $o_ActionReporte->datosPacienteRecetaEstandarizada($codPersona, $codProgramacion);
                $diagnosticos = trim($datosPaciente[0][11]);

                for ($i = 1; $i < count($datosPaciente); $i++) {
                    $diagnosticos = $diagnosticos . " - " . trim($datosPaciente[$i][11]);
                }

                $datosCabecera = array();
                $datosCabecera[2] = $datosPaciente[0][8]; // codigorecetaunica
                $datosCabecera[3] = utf8_encode($datosPaciente[0][2]); //nombres
                $datosCabecera[4] = $datosPaciente[0][3]; //edad
                $datosCabecera[5] = $datosPaciente[0][4]; // Nro Historia clínica
                $datosCabecera[6] = $datosPaciente[0][7]; //tipo usuario
                $datosCabecera[7] = $datosPaciente[0][5]; //atencion
                $datosCabecera[8] = utf8_encode($datosPaciente[0][6]); //especialidad
                $datosCabecera[9] = $diagnosticos; //diagnostico presuntivo
                $datosCabecera[10] = $datosPaciente[0][9]; //dni
                $datosCabecera[11] = utf8_encode($datosPaciente[0][10]); //medico tratante
                $datosCabecera[12] = $datosPaciente[0][12]; //fecha de atencion
//print_r($datosCabecera);
                /* ===================================================================================================== */
                /* =======================================   Datos de Detalle   ============================================ */
                $labelDetalle = $o_ActionReporte->labelReportePdf("recetaUnicaEstandarizada", $idReporte, 2);
                $datosDet = $o_ActionReporte->datosRecetaMedica($codProgramacion);
//print_r($datosDet);
                $datosDetalle = array();
                foreach ($datosDet as $i => $value) {
//                    $datosDetalle[$i][0]=$datosDet[$i][3];
//                    $datosDetalle[$i][1]=$datosDet[$i][6];
//                    $datosDetalle[$i][2]=$datosDet[$i][4];
//                    $datosDetalle[$i][3]=$datosDet[$i][5];
                    $datosDetalle[$i][0] = $datosDet[$i][0];
                    $datosDetalle[$i][1] = $datosDet[$i][1];
                    $datosDetalle[$i][2] = $datosDet[$i][2];
                    $datosDetalle[$i][3] = $datosDet[$i][3];
                    $datosDetalle[$i][4] = $datosDet[$i]['vModoAplicacion'];
                }
                //print_r(  $datosDet);
                //  print_r(  $datosDetalle);
//               $datosDetalle = array();
//               $datosDetalle = array(
//                       0=>array("medicamento xxxxx es una prueba para generar receta medicas estandarizadas, dn ad sm dms ds","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana"),
//                       1=>array("medicamento xxxxx","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana"),
//                       2=>array("medicamento xxxxx","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana"),
//                       3=>array("medicamento xxxxx es una prueba para generar receta medicas estandarizadas, dn ad sm dms ds","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana"),
//                       4=>array("medicamento xxxxx","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana"),
//                       5=>array("medicamento xxxxx","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana"),
//                       6=>array("medicamento xxxxx es una prueba para generar receta medicas estandarizadas, dn ad sm dms ds","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana"),7=>array("medicamento xxxxx","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana"),8=>array("medicamento xxxxx","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana")
//                   );



                /* ===================================================================================================== */
                /* =======================================   Datos de Pie   ============================================ */
                $datosPie = array();
                $labelPie = $o_ActionReporte->labelReportePdf("recetaUnicaEstandarizada", $idReporte, 3);
                $fechasTratamiento = $o_ActionReporte->fechasTratamienos($codProgramacion);
//               exit ();
                if ($fechasTratamiento[0][1] == '01/01/1900') {
                    $datosPie[1] = "";
                } else {
                    $datosPie[1] = $fechasTratamiento[0][1]; //proxima cita sugerida
                }
                $datosPie[2] = $fechasTratamiento[0][2]; //fecha de vencimiento de la receta
                $datosPie[6] = $fechasTratamiento[0][3]; //Nro Orden Receta

                /* ===================================================================================================== */
                /* =====================================   Todo Los atributos   ======================================== */
                $atributosReceta = $o_ActionReporte->atributosRecetaMedica($idReporte);
                $parametros["PDF_PAGE_FORMAT"] = "RECETA_MEDICA_ESTANDARIZADA";
                $parametros["PRINT_HEADER"] = false;
                $parametros["PRINT_FOOTER"] = false;
                $parametros["PDF_MARGIN_LEFT"] = 6;
                $parametros["PDF_MARGIN_RIGHT"] = 6;
                $parametros["PDF_MARGIN_TOP"] = 6;
                $parametros["AUTO_PAGE_BREAK"] = false;
                $parametros["PDF_PAGE_ORIENTATION"] = "L";
                $parametros["CODIGO_DE_BARRAS"] = $fechasTratamiento[0][3]; //nro de la orden generada
                $setdat->generarMYPDF_RME($atributosReceta, $labelCabecera, $labelPie, $datosCabecera, $datosDetalle, $datosPie, $modo, $nombreReporte, $parametros);
                break;
            }
        case "recetaOrdenMedica": {
                require_once('generarMYPDF_RME_ORDEN_MEDICA.php');
                $setdat = new generarMYPDFRME_ORDENMEDICA();

                $codProgramacion = $_REQUEST["p3"];
                $codPersona = $_REQUEST["p4"]; //para el paciente
                $codMedico = $_REQUEST["p5"];
                $idReporte = $_REQUEST["p6"];
                $nombreReporte = "OrdenMedica" . $codProgramacion;
                /* ===================================================================================================== */
                /* =======================================   Datos de Cabecera   ============================================ */
                $labelCabecera = $o_ActionReporte->labelReportePdf("", $idReporte, 1);
                $datosPaciente = $o_ActionReporte->datosPacienteRecetaEstandarizada($codPersona, $codProgramacion);
                $diagnosticos = trim($datosPaciente[0][11]);

                for ($i = 1; $i < count($datosPaciente); $i++) {
                    $diagnosticos = $diagnosticos . " - " . trim($datosPaciente[$i][11]);
                }

                $datosCabecera = array();
                $datosCabecera[2] = $datosPaciente[0][8]; // codigorecetaunica
                $datosCabecera[3] = utf8_encode($datosPaciente[0][2]); //nombres
                $datosCabecera[4] = $datosPaciente[0][3]; //edad
                $datosCabecera[5] = $datosPaciente[0][4]; // Nro Historia clínica
                $datosCabecera[6] = $datosPaciente[0][7]; //tipo usuario
                $datosCabecera[7] = $datosPaciente[0][5]; //atencion
                $datosCabecera[8] = utf8_encode($datosPaciente[0][6]); //especialidad
                $datosCabecera[9] = $diagnosticos; //diagnostico presuntivo
                $datosCabecera[10] = $datosPaciente[0][9]; //dni
                $datosCabecera[11] = utf8_encode($datosPaciente[0][10]); //medico tratante
                $datosCabecera[12] = $datosPaciente[0][12]; //fecha de atencion
//print_r($datosCabecera);
                /* ===================================================================================================== */
                /* =======================================   Datos de Detalle   ============================================ */
                $labelDetalle = $o_ActionReporte->labelReportePdf("", $idReporte, 2);
                $datosDet = $o_ActionReporte->datosRecetaMedica($codProgramacion);
//print_r($datosDet);
                $datosDetalle = array();
                foreach ($datosDet as $i => $value) {
//                    $datosDetalle[$i][0]=$datosDet[$i][3];
//                    $datosDetalle[$i][1]=$datosDet[$i][6];
//                    $datosDetalle[$i][2]=$datosDet[$i][4];
//                    $datosDetalle[$i][3]=$datosDet[$i][5];
                    $datosDetalle[$i][0] = $datosDet[$i][0];
                    $datosDetalle[$i][1] = $datosDet[$i][1];
                    $datosDetalle[$i][2] = $datosDet[$i][2];
                    $datosDetalle[$i][3] = $datosDet[$i][3];
                    $datosDetalle[$i][4] = $o_ActionReporte->centroCostosPorServicio($datosDet[$i][4]);
                }
                //print_r($datosDetalle);
//print_r($datosDetalle);
//               $datosDetalle = array();
//               $datosDetalle = array(
//                       0=>array("medicamento xxxxx es una prueba para generar receta medicas estandarizadas, dn ad sm dms ds","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana"),
//                       1=>array("medicamento xxxxx","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana"),
//                       2=>array("medicamento xxxxx","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana"),
//                       3=>array("medicamento xxxxx es una prueba para generar receta medicas estandarizadas, dn ad sm dms ds","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana"),
//                       4=>array("medicamento xxxxx","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana"),
//                       5=>array("medicamento xxxxx","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana"),
//                       6=>array("medicamento xxxxx es una prueba para generar receta medicas estandarizadas, dn ad sm dms ds","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana"),7=>array("medicamento xxxxx","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana"),8=>array("medicamento xxxxx","230 mg", "expediente x","1000","dosis xx","2 al dia","oral","1semana")
//                   );



                /* ===================================================================================================== */
                /* =======================================   Datos de Pie   ============================================ */
                $datosPie = array();
                $labelPie = $o_ActionReporte->labelReportePdf("recetaUnicaEstandarizada", $idReporte, 3);
                $fechasTratamiento = $o_ActionReporte->fechasTratamienos($codProgramacion);
//               exit ();
                if ($fechasTratamiento[0][1] == '01/01/1900') {
                    $datosPie[1] = "";
                } else {
                    $datosPie[1] = $fechasTratamiento[0][1]; //proxima cita sugerida
                }
                if ($fechasTratamiento[0][2] == '01/01/1900') {
                    $datosPie[2] = "";
                } else {
                    $datosPie[2] = $fechasTratamiento[0][2]; //fecha de vencimiento de la receta
                }
                if ($fechasTratamiento[0][3] == '01/01/1900') {
                    $datosPie[6] = "";
                } else {
                    $datosPie[6] = $fechasTratamiento[0][3]; //fecha de vencimiento de la receta
                }




                /* ===================================================================================================== */
                /* =====================================   Todo Los atributos   ======================================== */
                $atributosReceta = $o_ActionReporte->atributosRecetaMedica($idReporte);
                $parametros["PDF_PAGE_FORMAT"] = "RECETA_MEDICA_ESTANDARIZADA";
                $parametros["PRINT_HEADER"] = false;
                $parametros["PRINT_FOOTER"] = false;
                $parametros["PDF_MARGIN_LEFT"] = 6;
                $parametros["PDF_MARGIN_RIGHT"] = 6;
                $parametros["PDF_MARGIN_TOP"] = 6;
                $parametros["AUTO_PAGE_BREAK"] = false;
                $parametros["PDF_PAGE_ORIENTATION"] = "L";
                $parametros["CODIGO_DE_BARRAS"] = $fechasTratamiento[0][3]; //nro de la orden generada
                //print_r($fechasTratamiento);
                $setdat->generarMYPDF_RME_ORDEN_MEDICA($atributosReceta, $labelCabecera, $labelPie, $datosCabecera, $datosDetalle, $datosPie, $modo, $nombreReporte, $parametros);
                break;
            }
        case "ticketCita": {
                require_once('generadorDeReportes.php');
                $setdat = new PluginMYPDF();

//orden del array: nroOrden, especialidad, paciente, medico, fecha, hora, ambienteLogio
                $arrayDatos = $_REQUEST["p3"];
                $idReporte = $_REQUEST["p4"];

                $datosTicketCita = explode("|", $arrayDatos);
                $nombreReporte = "Cita" . $datosTicketCita[0];

                /* ===================================================================================================== */
                /* =======================================   Datos de Cabecera   ============================================ */
                $labelCabecera = $o_ActionReporte->labelReportePdf("ticketcita", $idReporte, 1);
                $datosCabecera = array();
                $datosCabecera[0] = "";
                /* ===================================================================================================== */
                /* =======================================   Datos de Detalle   ============================================ */
                $labelDetalle = $o_ActionReporte->labelReportePdf("ticketcita", $idReporte, 2);
                for ($i = 0; $i < count($datosTicketCita) ; $i++) { //observacion (-1) por que entrar 7 columnas (del 0 al 6) pero se envia al generador de reporte solo 6 columas
                    $datosDetalle[0][$i] = $datosTicketCita[$i];
                }

//                $datosDetalle[0][0]=$datosTicketCita[0];
//                $datosDetalle[0][1]=$datosTicketCita[1];
//                $datosDetalle[0][2]=$datosTicketCita[2];
//                $datosDetalle[0][3]=$datosTicketCita[3];
//                $datosDetalle[0][4]=$datosTicketCita[4];
//                $datosDetalle[0][5]=$datosTicketCita[5];

                /* ===================================================================================================== */
                /* =======================================   Datos de Pie   ============================================ */
                $datosPie = array();
                $labelPie = $o_ActionReporte->labelReportePdf("ticketcita", $idReporte, 3);
//                $datosMedico=$o_ActionReporte->datosMedico($codMedico);
                $datosPie[0] = "";
//                $datosPie[2]="";

                /* ===================================================================================================== */
                /* =====================================   Todo Los atributos   ======================================== */
                $atributosReceta = $o_ActionReporte->atributosRecetaMedica($idReporte);

                $parametros["PDF_PAGE_FORMAT"] = "TICKET_ORDEN";
                $parametros["PDF_MARGIN_HEADER"] = 0;
                $parametros["PDF_MARGIN_FOOTER"] = 0;
                $parametros["AUTO_PAGE_BREAK"] = false;
                $parametros["PDF_MARGIN_BOTTOM"] = 0;
               // $parametros["PDF_PAGE_ORIENTATION"] = "L";
                $parametros["PDF_MARGIN_LEFT"] = 1;
                $parametros["PDF_MARGIN_TOP"] = 0;
                $parametros["PDF_MARGIN_RIGHT"] = 1;
                $parametros["PRINT_HEADER"] = false;
                $parametros["PRINT_FOOTER"] = false;

                $setdat->generarMYPDF($atributosReceta, $labelCabecera, $labelDetalle, $labelPie, $datosCabecera, $datosDetalle, $datosPie, $modo, $nombreReporte, $parametros);

                break;
            }

        case "ticketOrdenMedica": {
                require_once('generadorDeReportes.php');
                $setdat = new PluginMYPDF();


                $arrayDatos = "MEDICINA GENERAL|ROMERO PLASENCIA MARITZA|RIVERA C. OMAR|Viernes 10 Agosto 2012|5:30PM  |MED. GENERAL I - PROLIMA(ESSALUD)";
                $idReporte = $_REQUEST["p3"];
                $idTratamiento = $_REQUEST["p4"];
                //echo 'trar'.$idTratamiento;
                //$codProgramacion = $_REQUEST["p3"];
                $codPersona = $_REQUEST["p5"];
                //$datosTicketCita = explode("|", $arrayDatos);
                $datosOrdenMedica = array();
                $nombreReporte = "TicketOrdenMedica" . $idTratamiento;
                $datosOrdenMedica = $o_ActionReporte->aDatosPacienteTicketOrden($idTratamiento);
                /* ===================================================================================================== */
                /* =======================================   Datos de Cabecera   ============================================ */
                $labelCabecera = $o_ActionReporte->labelReportePdf("", $idReporte, 1);
                $datosCabecera = array();
                $datosCabecera[0] = "";
                /* ===================================================================================================== */
                /* =======================================   Datos de Detalle   ============================================ */
                $labelDetalle = $o_ActionReporte->labelReportePdf("", $idReporte, 2);
                for ($i = 0; $i < 5; $i++) { //observacion (-1) por que entrar 7 columnas (del 0 al 6) pero se envia al generador de reporte solo 6 columas
                    $datosDetalle[0][$i] = $datosOrdenMedica[0][$i];
                }

//                $datosDetalle[0][0]='0724108-2012';
//                $datosDetalle[0][1]= 'RIVERA C. OMAR';
//                $datosDetalle[0][2]='C. MEDICINA GENERAL';
//                $datosDetalle[0][3]='LAB(ANATOMIA PATOLOGICA) PAPANICOLAU CERVICO VAGINAL';
//                $datosDetalle[0][4]='DESCARTAR LOS HONGOS EN LOS PIES';
//                $datosDetalle[0][5]='viernes 13 Agosto 2012';

                /* ===================================================================================================== */
                /* =======================================   Datos de Pie   ============================================ */
                $datosPie = array();
                $labelPie = $o_ActionReporte->labelReportePdf("", $idReporte, 3);
//                $datosMedico=$o_ActionReporte->datosMedico($codMedico);
                $datosPie[0] = "";
//                $datosPie[2]="";

                /* ===================================================================================================== */
                /* =====================================   Todo Los atributos   ======================================== */
                $atributosReceta = $o_ActionReporte->atributosRecetaMedica($idReporte);

                $parametros["PDF_PAGE_FORMAT"] = "TICKET_ORDEN_MEDICA";
                $parametros["PDF_MARGIN_HEADER"] = 0;
                $parametros["PDF_MARGIN_FOOTER"] = 0;
                $parametros["AUTO_PAGE_BREAK"] = false;
                $parametros["PDF_MARGIN_BOTTOM"] = 0;

                $parametros["PDF_MARGIN_LEFT"] = 1;
                $parametros["PDF_MARGIN_TOP"] = 0;
                $parametros["PDF_MARGIN_RIGHT"] = 1;
                $parametros["PRINT_HEADER"] = false;
                $parametros["PRINT_FOOTER"] = false;

                $setdat->generarMYPDF($atributosReceta, $labelCabecera, $labelDetalle, $labelPie, $datosCabecera, $datosDetalle, $datosPie, $modo, $nombreReporte, $parametros);

                break;
            }
        case "historiasMamografias": {
                require_once('generarReporteMensual.php');
                $reporteHC = new generarReporteMensualMamografias();

                $idPaciente = 4567;
                $idReporte = 3;
                $datos['p2'] = $_REQUEST["p2"];
                $datos['p3'] = $_REQUEST["p3"];
                $modo = 1;
                $labelCabecera = $o_ActionReporte->labelReportePdf("vacio", 11000, 1000);
                $datosCabecera = array();
                $listaAtenciones = $o_ActionReporte->listaAtencionesMamografias($datos);
                $arrayHC = array();
                foreach ($listaAtenciones as $i => $value) {
                    $o_ActionReporte = new ActionReporte();
                    $oLActoMedico = new LActoMedico();

                    $datosMed = $oLActoMedico->atencionMedico($listaAtenciones[$i][0]);
                    $motivoConsulta = $o_ActionReporte->rptMotivoConsulta($listaAtenciones[$i][0]);
                    $triaje = $o_ActionReporte->rptTriaje($listaAtenciones[$i][0]);
                    $examenesMedicos = $o_ActionReporte->rptExamenesMedicos($listaAtenciones[$i][0]);
                    $datosExamenes = array();
                    if ($examenesMedicos) {
                        foreach ($examenesMedicos as $j => $filaExamen) {
                            $pruebasExamenes = $oLActoMedico->valoresCampos($listaAtenciones[$i][0], $filaExamen[0]);
                            if ($pruebasExamenes)
                                $datosExamenes[$j][0] = $pruebasExamenes;
                            else
                                $datosExamenes[$j][0] = null;
                        }
                        $arrayHC[$i][2] = $datosExamenes;
                    }else {
                        $arrayHC[$i][2] = null;
                    }

                    $diagnostico = $o_ActionReporte->rptDiagnostico($listaAtenciones[$i][0]);
                    $medicamentoso = $o_ActionReporte->rptTratamientos($listaAtenciones[$i][0], "1");
                    $practicaMedica = $o_ActionReporte->rptTratamientos($listaAtenciones[$i][0], "2");
                    $tratamientosx = array();
                    if ($medicamentoso)
                        $tratamientosx[0][0] = $medicamentoso;
                    else
                        $tratamientosx[0][0] = null;
                    if ($practicaMedica)
                        $tratamientosx[0][1] = $practicaMedica;
                    else
                        $tratamientosx[0][1] = null;

                    if ($motivoConsulta)
                        $arrayHC[$i][0] = $motivoConsulta;
                    else
                        $arrayHC[$i][0] = null;
                    if ($triaje)
                        $arrayHC[$i][1] = $triaje;
                    else
                        $arrayHC[$i][1] = null;
                    if ($diagnostico)
                        $arrayHC[$i][3] = $diagnostico;
                    else
                        $arrayHC[$i][3] = null;
                    if ($tratamientosx)
                        $arrayHC[$i][4] = $tratamientosx;
                    else
                        $arrayHC[$i][4] = null;
                    if ($datosMed) {
                        $arrayHC[$i][5] = $datosMed;
                        $arrayHC[$i][6] = $listaAtenciones[$i][4];
                    } else {
                        $arrayHC[$i][5] = null;
                        $arrayHC[$i][6] = null;
                    }
                }

                /* ===================================================================================================== */
                /* =======================================   Datos de Pie   ============================================ */


                /* ===================================================================================================== */
                /* =====================================   Todo Los atributos   ======================================== */
                $atributosHC = $o_ActionReporte->atributosRecetaMedica($idReporte);
                $parametros["PRINT_HEADER"] = true;
                $parametros["PRINT_FOOTER"] = false;
                $parametros["PDF_MARGIN_LEFT"] = 10;
                $parametros["PDF_MARGIN_RIGHT"] = 10;
                $parametros["PDF_MARGIN_TOP"] = 10;
                $parametros["AUTO_PAGE_BREAK"] = true;
                $datosPie = "";
                $nombreReporte = $numHC;
                $historiaOdontograma = "";
                $reporteHC->generarMYPDF_HC_Completo($atributosHC, $labelCabecera, $datosCabecera, $datosPie, $antecedentes, $arrayHC, $modo, $datos, $parametros, $listaAtenciones);
                break;
            }
        case "historiasPapanicolaou": {
                require_once('generarReporteMensualPapanicolaou.php');
                $reporteHC = new generarReporteMensualPapanicolaou();
                $o_ActionActoMedico = new ActionActoMedico();
                $datos['p2'] = $_REQUEST["p2"];
                $datos['p3'] = $_REQUEST["p3"];
                $listarPapanicolaum = $o_ActionActoMedico->listaAtencionespapanicolaum($datos);
                $parametros["PRINT_HEADER"] = false;
                $parametros["PRINT_FOOTER"] = false;
                $parametros["PDF_MARGIN_LEFT"] = 10;
                $parametros["PDF_MARGIN_RIGHT"] = 10;
                $parametros["PDF_MARGIN_TOP"] = 10;
                $parametros["AUTO_PAGE_BREAK"] = true;
                $reporteHC->generarMYPDF_HC_Completo($listarPapanicolaum, $parametros, $datos);
                break;
            }
        case "historiasPreventivas": {
                require_once('generarReporteMensualPreventivas.php');
                $reporteHC = new generarReporteMensualPreventivas();
                $idPaciente = 4567;
                $idReporte = 3;
                $numHC = $_REQUEST["p1"];
                $modo = 1;
                $dia = $_REQUEST["p2"];
                $labelCabecera = $o_ActionReporte->labelReportePdf("vacio", 11000, 1000);
                $datosCabecera = array();
                //echo 'Peche <br>';
                $listaAtenciones = $o_ActionReporte->listaAtencionesPreventivas($dia);
                $arrayHC = array();
                foreach ($listaAtenciones as $i => $value) {
                    $o_ActionReporte = new ActionReporte();
                    $oLActoMedico = new LActoMedico();

                    $datosMed = $oLActoMedico->atencionMedico($listaAtenciones[$i][0]);
                    $motivoConsulta = $o_ActionReporte->rptMotivoConsulta($listaAtenciones[$i][0]);
                    $triaje = $o_ActionReporte->rptTriaje($listaAtenciones[$i][0]);
                    $examenesMedicos = $o_ActionReporte->rptExamenesMedicos($listaAtenciones[$i][0]);
                    $datosExamenes = array();
                    if ($examenesMedicos) {
                        foreach ($examenesMedicos as $j => $filaExamen) {
                            $pruebasExamenes = $oLActoMedico->valoresCampos($listaAtenciones[$i][0], $filaExamen[0]);
                            if ($pruebasExamenes)
                                $datosExamenes[$j][0] = $pruebasExamenes;
                            else
                                $datosExamenes[$j][0] = null;
                        }
                        $arrayHC[$i][2] = $datosExamenes;
                    }else {
                        $arrayHC[$i][2] = null;
                    }

                    $diagnostico = $o_ActionReporte->rptDiagnostico($listaAtenciones[$i][0]);
                    $medicamentoso = $o_ActionReporte->rptTratamientos($listaAtenciones[$i][0], "1");
                    $practicaMedica = $o_ActionReporte->rptTratamientos($listaAtenciones[$i][0], "2");
                    $tratamientosx = array();
                    if ($medicamentoso)
                        $tratamientosx[0][0] = $medicamentoso;
                    else
                        $tratamientosx[0][0] = null;
                    if ($practicaMedica)
                        $tratamientosx[0][1] = $practicaMedica;
                    else
                        $tratamientosx[0][1] = null;

                    if ($motivoConsulta)
                        $arrayHC[$i][0] = $motivoConsulta;
                    else
                        $arrayHC[$i][0] = null;
                    if ($triaje)
                        $arrayHC[$i][1] = $triaje;
                    else
                        $arrayHC[$i][1] = null;
                    if ($diagnostico)
                        $arrayHC[$i][3] = $diagnostico;
                    else
                        $arrayHC[$i][3] = null;
                    if ($tratamientosx)
                        $arrayHC[$i][4] = $tratamientosx;
                    else
                        $arrayHC[$i][4] = null;
                    if ($datosMed) {
                        $arrayHC[$i][5] = $datosMed;
                        $arrayHC[$i][6] = $listaAtenciones[$i][4];
                    } else {
                        $arrayHC[$i][5] = null;
                        $arrayHC[$i][6] = null;
                    }
                    // echo $listaAtenciones[$i][0].'</br>';
                }

                /* ===================================================================================================== */
                /* =======================================   Datos de Pie   ============================================ */


                /* ===================================================================================================== */
                /* =====================================   Todo Los atributos   ======================================== */
                //echo 'peche1 <br>';
                $atributosHC = $o_ActionReporte->atributosRecetaMedica($idReporte);
                $parametros["PRINT_HEADER"] = false;
                $parametros["PRINT_FOOTER"] = false;
                $parametros["PDF_MARGIN_LEFT"] = 10;
                $parametros["PDF_MARGIN_RIGHT"] = 10;
                $parametros["PDF_MARGIN_TOP"] = 10;
                $parametros["AUTO_PAGE_BREAK"] = true;
                $datosPie = "";
                $nombreReporte = $numHC;
                $historiaOdontograma = "";
                //echo 'peche2 <br>';
                ob_flush();
                $reporteHC->generarMYPDF_HC_Completo($atributosHC, $labelCabecera, $datosCabecera, $datosPie, $antecedentes, $arrayHC, $modo, $dia, $parametros, $listaAtenciones);
                break;
            }
        case "historiaClinica" : {
                require_once('generarReporteHCCompleto.php');                

                $reporteHC = new generarMYPDFHCCompleto();

                $idPaciente = $_REQUEST["p3"];
                $idReporte = $_REQUEST["p4"];
                $numHC = $_REQUEST["p5"];
                if ($numHC != "NO_DATA") {

                    /* ===================================================================================================== */
                    /* =======================================   Datos de Cabecera   ============================================ */
                    $labelCabecera = $o_ActionReporte->labelReportePdf("historiaClinica", $idReporte, 1);
                    $datosPaciente = $o_ActionReporte->datosPaciente($idPaciente);
                    $datosCabecera = array();

                    $datosCabecera[4] = $numHC;
                    $datosCabecera[5] = $datosPaciente[0][2];
                    $datosCabecera[6] = $datosPaciente[0][3];
                    $datosCabecera[7] = $datosPaciente[0][1];
                    $datosCabecera[8] = $datosPaciente[0][9];
                    if ($datosPaciente[0][8] == 'sindata') {
                        $fechayedad = '';
                    } else {
                        $fechayedad = $datosPaciente[0][8] . " ( " . $datosPaciente[0][4] . " años )";
                    }
                    $datosCabecera[9] = $fechayedad;
                    $datosCabecera[10] = $datosPaciente[0][5] == 1 ? "MASCULINO" : "FEMENINO";
                    $datosCabecera[11] = trim($datosPaciente[0][11]);
                    $datosCabecera[12] = $datosPaciente[0][12] . " - " . $datosPaciente[0][13];
//---/home/samba/shares/2010_hmlo_personal/0374787.jpg
                    if (file_exists($datosPaciente[0][7] . "" . $datosPaciente[0][10]))  //verifico si existe la foto en la ruta
                        $datosCabecera[13] = $datosPaciente[0][6] . "" . $datosPaciente[0][10];
                    /* ===================================================================================================== */
                    /* =======================================   Datos de Detalle   ============================================ */
                    $listaAtenciones = $o_ActionReporte->listaAtenciones($idPaciente); //$listaAtenciones[$i][0]--> idPrigramacion
                    $antecedentes = $o_ActionReporte->rptAntecedentes($idPaciente); //serecupera los antecedentes de golpe

                    $arrayHC = array();
                    foreach ($listaAtenciones as $i => $value) {
                        $o_ActionReporte = new ActionReporte();
                        $oLActoMedico = new LActoMedico();

                        $datosMed = $oLActoMedico->atencionMedico($listaAtenciones[$i][0]);
                        $motivoConsulta = $o_ActionReporte->rptMotivoConsulta($listaAtenciones[$i][0]);
                        $triaje = $o_ActionReporte->rptTriaje($listaAtenciones[$i][0]);
//------------------------------------------------------------------------------------
                        $examenesMedicos = $o_ActionReporte->rptExamenesMedicos($listaAtenciones[$i][0]);
                        $datosExamenes = array();
                        if ($examenesMedicos) {
                            foreach ($examenesMedicos as $j => $filaExamen) {
                                $pruebasExamenes = $oLActoMedico->valoresCampos($listaAtenciones[$i][0], $filaExamen[0]);
                                if ($pruebasExamenes)
                                    $datosExamenes[$j][0] = $pruebasExamenes;
                                else
                                    $datosExamenes[$j][0] = null;
                            }
                            $arrayHC[$i][2] = $datosExamenes;
                        }else {
                            $arrayHC[$i][2] = null;
                        }
//------------------------------------------------------------------------------------

                        $diagnostico = $o_ActionReporte->rptDiagnostico($listaAtenciones[$i][0]);

                        $medicamentoso = $o_ActionReporte->rptTratamientos($listaAtenciones[$i][0], "1");
                        $practicaMedica = $o_ActionReporte->rptTratamientos($listaAtenciones[$i][0], "2");
                        $tratamientosx = array();
                        if ($medicamentoso)
                            $tratamientosx[0][0] = $medicamentoso;
                        else
                            $tratamientosx[0][0] = null;
                        if ($practicaMedica)
                            $tratamientosx[0][1] = $practicaMedica;
                        else
                            $tratamientosx[0][1] = null;

                        if ($motivoConsulta)
                            $arrayHC[$i][0] = $motivoConsulta;
                        else
                            $arrayHC[$i][0] = null;
                        if ($triaje)
                            $arrayHC[$i][1] = $triaje;
                        else
                            $arrayHC[$i][1] = null;
// Examenes
                        if ($diagnostico)
                            $arrayHC[$i][3] = $diagnostico;
                        else
                            $arrayHC[$i][3] = null;
                        if ($tratamientosx)
                            $arrayHC[$i][4] = $tratamientosx;
                        else
                            $arrayHC[$i][4] = null;
                        if ($datosMed) {
                            $arrayHC[$i][5] = $datosMed;
                            $arrayHC[$i][6] = $listaAtenciones[$i][4];
                        } else {
                            $arrayHC[$i][5] = null;
                            $arrayHC[$i][6] = null;
                        }
                    }

                    /* ===================================================================================================== */
                    /* =======================================   Datos de Pie   ============================================ */


                    /* ===================================================================================================== */
                    /* =====================================   Todo Los atributos   ======================================== */
                    $atributosHC = $o_ActionReporte->atributosRecetaMedica($idReporte);
                    $parametros["PRINT_HEADER"] = false;
                    $parametros["PRINT_FOOTER"] = false;
                    $parametros["PDF_MARGIN_LEFT"] = 10;
                    $parametros["PDF_MARGIN_RIGHT"] = 10;
                    $parametros["PDF_MARGIN_TOP"] = 10;
                    $parametros["AUTO_PAGE_BREAK"] = true;
                    $datosPie = "";
                    $nombreReporte = $numHC;
                    $historiaOdontograma = "";

                    $reporteHC->generarMYPDF_HC_Completo($atributosHC, $labelCabecera, $datosCabecera, $datosPie, $antecedentes, $arrayHC, $modo, $nombreReporte, $parametros);

                    break;
                }
               echo 'EL PACIENTE SELECCIONADO NO CENTA CON HISTORIA CLINICA';
                
                break;
            }



        case 'historiaClinicaXDia': {
                require_once('generarReporteHC.php');
                $reporteHC = new generarMYPDFHC();
                $idPaciente = $_REQUEST["p3"];
                $idReporte = $_REQUEST["p4"];
                $numHC = $_REQUEST["p5"];
                $idPrograma = $_REQUEST["p6"];
                /* ===================================================================================================== */
                /* =======================================   Datos de Cabecera   ============================================ */
                $labelCabecera = $o_ActionReporte->labelReportePdf("historiaClinica", $idReporte, 1);
                $datosPaciente = $o_ActionReporte->datosPacienteImprimirHIstoria($idPrograma);
                $datosCabecera = array();
                $datosCabecera[2] = $datosPaciente[0][0];
                $datosCabecera[3] = $datosPaciente[0][1];
                $datosCabecera[4] = $datosPaciente[0][2];
                $datosCabecera[5] = $datosPaciente[0][3];
                $datosCabecera[6] = $datosPaciente[0][4];
                $datosCabecera[7] = $datosPaciente[0][6];
                $datosCabecera[8] = $datosPaciente[0][5];
                $datosCabecera[9] = $datosPaciente[0][7];
                $datosCabecera[10] = $datosPaciente[0][8];
                $datosCabecera[11] = $datosPaciente[0][9];
                $datosCabecera[12] = $datosPaciente[0][10];
                $datosCabecera[13] = $datosPaciente[0][11];
                /* ===================================================================================================== */
                /* =======================================   Datos de Detalle   ============================================ */
                $o_ActionReporte = new ActionReporte();
                $oLActoMedico = new LActoMedico();
                $listaAtenciones = $o_ActionReporte->listaAtencionesXDia($idPrograma); //$listaAtenciones[$i][0]--> idPrigramacion
                $antecedentes = $o_ActionReporte->rptAntecedentesPRograma($idPrograma); //serecupera los antecedentes de golpe
                $historiaOdontograma = $oLActoMedico->listadoHistoriaDiente($idPrograma);
                $simbolosImagen = $oLActoMedico->listaImagenesOdontograma($idPrograma);
                
                $nroPlaca = $oLActoMedico->lstListarNumeroIFExistePlaca($idPrograma);
                
                
                $arrayHC = array();
                foreach ($listaAtenciones as $i => $value) {


                    $datosMed = $oLActoMedico->atencionMedico($listaAtenciones[$i][0]);
                    $motivoConsulta = $o_ActionReporte->rptMotivoConsulta($listaAtenciones[$i][0]);
                    $triaje = $o_ActionReporte->rptTriaje($listaAtenciones[$i][0]);
//------------------------------------------------------------------------------------
                    $examenesMedicos = $o_ActionReporte->rptExamenesMedicos($listaAtenciones[$i][0]);
                    $datosExamenes = array();
                    if ($examenesMedicos) {
                        foreach ($examenesMedicos as $j => $filaExamen) {
                            $pruebasExamenes = $oLActoMedico->valoresCampos($listaAtenciones[$i][0], $filaExamen[0]);
                            if ($pruebasExamenes)
                                $datosExamenes[$j][0] = $pruebasExamenes;
                            else
                                $datosExamenes[$j][0] = null;
                        }
                        $arrayHC[$i][2] = $datosExamenes;
                    }else {
                        $arrayHC[$i][2] = null;
                    }
//------------------------------------------------------------------------------------

                    $diagnostico = $o_ActionReporte->rptDiagnostico($listaAtenciones[$i][0]);

                    $medicamentoso = $o_ActionReporte->rptTratamientos($listaAtenciones[$i][0], "1");
                    $practicaMedica = $o_ActionReporte->rptTratamientos($listaAtenciones[$i][0], "2");
                    $tratamientosx = array();
                    if ($medicamentoso)
                        $tratamientosx[0][0] = $medicamentoso;
                    else
                        $tratamientosx[0][0] = null;
                    if ($practicaMedica)
                        $tratamientosx[0][1] = $practicaMedica;
                    else
                        $tratamientosx[0][1] = null;

                    if ($motivoConsulta)
                        $arrayHC[$i][0] = $motivoConsulta;
                    else
                        $arrayHC[$i][0] = null;
                    if ($triaje)
                        $arrayHC[$i][1] = $triaje;
                    else
                        $arrayHC[$i][1] = null;
// Examenes
                    if ($diagnostico)
                        $arrayHC[$i][3] = $diagnostico;
                    else
                        $arrayHC[$i][3] = null;
                    if ($tratamientosx)
                        $arrayHC[$i][4] = $tratamientosx;
                    else
                        $arrayHC[$i][4] = null;
                    if ($datosMed) {
                        $arrayHC[$i][5] = $datosMed;
                        $arrayHC[$i][6] = $listaAtenciones[$i][4];
                    } else {
                        $arrayHC[$i][5] = null;
                        $arrayHC[$i][6] = null;
                    }
                }

                /* ===================================================================================================== */
                /* =======================================   Datos de Pie   ============================================ */


                /* ===================================================================================================== */
                /* =====================================   Todo Los atributos   ======================================== */
                $atributosHC = $o_ActionReporte->atributosRecetaMedica($idReporte);
                $parametros["PRINT_HEADER"] = false;
                $parametros["PRINT_FOOTER"] = false;
                $parametros["PDF_MARGIN_LEFT"] = 10;
                $parametros["PDF_MARGIN_RIGHT"] = 10;
                $parametros["PDF_MARGIN_TOP"] = 5;
                $parametros["AUTO_PAGE_BREAK"] = true;
                $datosPie = "";
                $nombreReporte = $numHC;
                $reporteHC->generarMYPDF_HC($atributosHC, $labelCabecera, $datosCabecera, $datosPie, $antecedentes, $arrayHC, $modo, $nombreReporte, $parametros, $historiaOdontograma,$nroPlaca,$idPrograma);
                break;
            }

        case 'formatolaboratorio': {
                require_once('generadorDeReportesLaboratorio.php');
                $setdat = new LabPluginMYPDF();
                $idReporte = $_REQUEST["p3"];
                $codPacienteLab = $_REQUEST["p4"];
                $datosOrdenMedica = array();
                $nombreReporte = "Examen Laboratorio Nro: " . $codPacienteLab;
                $labelCabecera = $o_ActionReporte->labelReportePdf("", $idReporte, 1);
                $datosPaciente = $o_ActionReporte->datosPacientexExamen($codPacienteLab);
                $datosCabecera = array();
                $datosCabecera[4] = $codPacienteLab;
                $datosCabecera[5] = $datosPaciente[0][0];
                $datosCabecera[6] = $datosPaciente[0][1];
                $datosCabecera[7] = $datosPaciente[0][2];
                $datosCabecera[8] = $datosPaciente[0][3];
                $datosCabecera[9] = $datosPaciente[0][4];
                $datosCabecera[10] = $datosPaciente[0][5];
                $datosCabecera[11] = $datosPaciente[0][6];
                $datosCabecera[12] = $datosPaciente[0][7];
                $labelDetalle = $o_ActionReporte->labelReportePdf("", $idReporte, 2);
                $datosExamen = $o_ActionReporte->aDatosPuntoControlPaciente($codPacienteLab);
                $datosGrupo = $o_ActionReporte->agrupodeDatos($codPacienteLab);
                $datosExamenUni = $o_ActionReporte->adatosExamenUni($codPacienteLab);
                $labelPie = $o_ActionReporte->labelReportePdf("", $idReporte, 3);
                $datosPie[0] = $datosPaciente[0][8];
                $atributosReceta = $o_ActionReporte->atributosRecetaMedica($idReporte);
                $parametros["PDF_PAGE_FORMAT"] = "A4";
                $parametros["PDF_MARGIN_HEADER"] = 0;
                $parametros["PDF_MARGIN_FOOTER"] = 0;
                $parametros["AUTO_PAGE_BREAK"] = true;
                $parametros["PDF_MARGIN_BOTTOM"] = 10;
                $parametros["PDF_MARGIN_LEFT"] = 6;
                $parametros["PDF_MARGIN_RIGHT"] = 6;
                $parametros["PDF_MARGIN_TOP"] = 10;
                $parametros["PRINT_HEADER"] = false;
                $parametros["PRINT_FOOTER"] = false;
                $parametros["CODIGO_DE_BARRAS"] = $datosPaciente[0][8];
                $setdat->generarReporte($atributosReceta, $labelCabecera, $labelDetalle, $labelPie, $datosCabecera, $datosDetalle, $datosExamen, $datosExamenUni, $datosGrupo, $datosPie, $modo, $nombreReceta, $parametros);
                break;
            }

        case 'recibodepago': {
                // require_once('generarReporteRecibodePago.php');//generacionResiboPagpPDF
                require_once('generacionResiboPagpPDF.php');
                $reporteRecibo = new generarMYPDF_RECIBODEPAGO();
                $numeroRecibo = $_REQUEST["p3"];
                $idReporte = $_REQUEST["p4"];
                /* ===================================================================================================== */
                /* =======================================   Datos de Cabecera   ============================================ */
                $labelCabecera = $o_ActionReporte->labelReportePdf("recibodepago", $idReporte, 1);
                $datosEmpresa = $o_ActionReporte->datosEmpresaGeneraelRecibo('0110073');
                $datosPaciente = $o_ActionReporte->datosPacienteGeneraelRecibo($numeroRecibo);
                $datosFecha = $o_ActionReporte->fechaEmiteResibo();
                $datosCabecera = array();
                $datosCabecera[4] = $datosEmpresa[0][0];
                $datosCabecera[5] = $datosEmpresa[0][1];
                $datosCabecera[6] = $datosEmpresa[0][2];
                $datosCabecera[7] = $numeroRecibo;
                $datosCabecera[8] = $datosPaciente[0][0];
                $datosCabecera[9] = htmlentities($datosPaciente[0][1]); //$str_replace("ñ", "&ntilde;", $datosPaciente[0][1]);
                $datosCabecera[10] = $datosPaciente[0][2];
                $datosCabecera[11] = $datosPaciente[0][3];
                $datosCabecera[12] = $datosPaciente[0][4];
                $datosCabecera[13] = $datosFecha[0][0];
                /* ===================================================================================================== */
                /* =======================================   Datos de Detalle   ============================================ */
//                $labelDetalle = $o_ActionReporte->labelReportePdf("recibodepago", $idReporte, 2);
//                $datosDetalle = array();
                $labelDetalle = $o_ActionReporte->labelReportePdf("recibodepago", $idReporte, 2);
                $datosDet = $o_ActionReporte->datosDetalleReciboGenerado($numeroRecibo);
                $datosDetalle = array();
                foreach ($datosDet as $i => $value) {
                    $datosDetalle[$i] = $value;
                }
                /* ===================================================================================================== */
                /* =======================================   Datos de Pie   ============================================ */
                $labelPie = $o_ActionReporte->labelReportePdf("recibodepago", $idReporte, 3);
                $datosPieRecibo = $o_ActionReporte->datosPieReciboGenerado($numeroRecibo);
                $datosPie = array();
                $datosPie[4] = $datosPieRecibo[0][1];
                if ($datosPieRecibo[0][0] == '1') {
                    $datosPie[5] = $datosPieRecibo[0][2];
                    $datosPie[6] = $datosPieRecibo[0][3];
                    $datosPie[7] = $datosPieRecibo[0][4];
                    $datosPie[8] = $datosPieRecibo[0][5];
                }


                /* ===================================================================================================== */
                /* =====================================   Todo Los atributos   ======================================== */
                $atributosRecibo = $o_ActionReporte->atributosRecetaMedica($idReporte);
                $parametros["PDF_PAGE_FORMAT"] = "BOLETA_DE_PAGO";
                $parametros["PDF_MARGIN_HEADER"] = 0;
                $parametros["PDF_MARGIN_FOOTER"] = 0;
                $parametros["AUTO_PAGE_BREAK"] = false;
                $parametros["PDF_MARGIN_BOTTOM"] = 0;
                $parametros["PDF_PAGE_ORIENTATION"] = "P";
                $parametros["PDF_MARGIN_LEFT"] = 1;
                $parametros["PDF_MARGIN_TOP"] = 0;
                $parametros["PDF_MARGIN_RIGHT"] = 1;
                $parametros["PRINT_HEADER"] = false;
                $parametros["PRINT_FOOTER"] = false;
                //$datosPie = "";
                $nombreReporte = $numeroRecibo;
                $reporteRecibo->generarMYPDF_RECIBO($atributosRecibo, $labelCabecera, $labelDetalle, $labelPie, $datosCabecera, $datosDetalle, $datosPie, $modo, $nombreReporte, $parametros);
                break;
            }
        case 'recibodepagoImprimir': {
                // require_once('generarReporteRecibodePago.php');//generacionResiboPagpPDF
                require_once('generarResiboImprimir.php');
                $reporteRecibo = new generarMYPDF_RECIBODEPAGO();
                $numeroRecibo = $_REQUEST["p3"];
                $idReporte = $_REQUEST["p4"];
                $c_cod_per = $_REQUEST["p5"];
                /* ===================================================================================================== */
                /* =======================================   Datos de Cabecera   ============================================ */
                $labelCabecera = $o_ActionReporte->labelReportePdf("recibodepago", $idReporte, 1);
                $datosEmpresa = $o_ActionReporte->datosEmpresaGeneraelRecibo('0110073');
                $datosPaciente = $o_ActionReporte->datosPacienteGeneraelRecibo($numeroRecibo);
                $datosFecha = $o_ActionReporte->fechaEmiteResibo();
                $exitenciaHistoriaClinica = $o_ActionReporte->ExitenciaHistoriaClinica($c_cod_per);
                $datosCabecera = array();
                $datosCabecera[4] = $datosEmpresa[0][0];
                $datosCabecera[5] = $datosEmpresa[0][1];
                $datosCabecera[6] = $datosEmpresa[0][2];
                $datosCabecera[7] = substr($numeroRecibo, 0, 2); //////////MODIFICAR AQUI
                $datosCabecera[8] = $datosPaciente[0][0];
                $datosCabecera[9] = htmlentities($datosPaciente[0][1]); //$str_replace("ñ", "&ntilde;", $datosPaciente[0][1]);
                $datosCabecera[10] = $datosPaciente[0][2];
                $datosCabecera[11] = $datosPaciente[0][3];
                $datosCabecera[12] = $datosPaciente[0][4];
                $datosCabecera[13] = $datosFecha[0][0];
                $datosCabecera[14] = substr($numeroRecibo, 4, strlen($numeroRecibo));
                $datosCabecera[15] = $numeroRecibo;
                $datosCabecera[16] = $exitenciaHistoriaClinica[0][0];

                /* ===================================================================================================== */
                /* =======================================   Datos de Detalle   ============================================ */
//                $labelDetalle = $o_ActionReporte->labelReportePdf("recibodepago", $idReporte, 2);
//                $datosDetalle = array();
                $labelDetalle = $o_ActionReporte->labelReportePdf("recibodepago", $idReporte, 2);
                $datosDet = $o_ActionReporte->datosDetalleReciboGenerado($numeroRecibo);
                $datosDetalle = array();
                foreach ($datosDet as $i => $value) {
                    $datosDetalle[$i] = $value;
                }
                /* ===================================================================================================== */
                /* =======================================   Datos de Pie   ============================================ */
                $labelPie = $o_ActionReporte->labelReportePdf("recibodepago", $idReporte, 3);
                $datosPieRecibo = $o_ActionReporte->datosPieReciboGenerado($numeroRecibo);
                $datosPie = array();
                $datosPie[4] = $datosPieRecibo[0][1];
                if ($datosPieRecibo[0][0] == '1') {
                    $datosPie[5] = $datosPieRecibo[0][2];
                    $datosPie[6] = $datosPieRecibo[0][3];
                    $datosPie[7] = $datosPieRecibo[0][4];
                    $datosPie[8] = $datosPieRecibo[0][5];
                }


                /* ===================================================================================================== */
                /* =====================================   Todo Los atributos   ======================================== */
                $atributosRecibo = $o_ActionReporte->atributosRecetaMedica($idReporte);
                $parametros["PDF_PAGE_FORMAT"] = "BOLETA_DE_PAGO";
                $parametros["PDF_MARGIN_HEADER"] = 0;
                $parametros["PDF_MARGIN_FOOTER"] = 0;
                $parametros["AUTO_PAGE_BREAK"] = false;
                $parametros["PDF_MARGIN_BOTTOM"] = 0;
                $parametros["PDF_PAGE_ORIENTATION"] = "P";
                $parametros["PDF_MARGIN_LEFT"] = 1;
                $parametros["PDF_MARGIN_TOP"] = 0;
                $parametros["PDF_MARGIN_RIGHT"] = 1;
                $parametros["PRINT_HEADER"] = false;
                $parametros["PRINT_FOOTER"] = false;
                //$datosPie = "";
                $nombreReporte = $numeroRecibo;
                $reporteRecibo->generarMYPDF_RECIBO($atributosRecibo, $labelCabecera, $labelDetalle, $labelPie, $datosCabecera, $datosDetalle, $datosPie, $modo, $nombreReporte, $parametros);
                break;
            }

        case 'carnetSanidad': {
                // require_once('generarReporteRecibodePago.php');//generacionResiboPagpPDF
                require_once('generarCarnetSanidad.php');
                $reporteRecibo = new generarMYPDFRMECARNET();
                $DNI = $_REQUEST["p3"];
                $nombreCompleto = $_REQUEST["p4"];
                $tipoCertificado = $_REQUEST["p5"];
                $c_cod_per = $_REQUEST["p6"];
                $idReporte = $_REQUEST["p7"];
                $apellidos = $_REQUEST["p8"];
                $nombre = $_REQUEST["p9"];
                $fechaActual = $_REQUEST["p10"];
                $fechaCaducidad = $_REQUEST["p11"];
//                print_r($tipoCertificado);
                /* ===================================================================================================== */
                /* =======================================   Datos de Cabecera   ============================================ */
                $labelCabecera = $o_ActionReporte->labelReportePdf("CarnetSanidad", $idReporte, 1);
//                $datosEmpresa = $o_ActionReporte->datosEmpresaGeneraelRecibo('0110073');
//                $datosPaciente = $o_ActionReporte->datosPacienteGeneraelRecibo($numeroRecibo);
//                $datosFecha = $o_ActionReporte->fechaEmiteResibo();
//                $exitenciaHistoriaClinica = $o_ActionReporte->ExitenciaHistoriaClinica($c_cod_per);
                $datosCabecera = array();
//                $datosCabecera[4] = $datosEmpresa[0][0];
//                $datosCabecera[5] = $datosEmpresa[0][1];
//                $datosCabecera[6] = $datosEmpresa[0][2];
//                $datosCabecera[7] = substr($numeroRecibo, 0, 2); //////////MODIFICAR AQUI
//                $datosCabecera[8] = $datosPaciente[0][0];
//                $datosCabecera[9] = htmlentities($datosPaciente[0][1]); //$str_replace("ñ", "&ntilde;", $datosPaciente[0][1]);
//                $datosCabecera[10] = $datosPaciente[0][2];
//                $datosCabecera[11] = $datosPaciente[0][3];
//                $datosCabecera[12] = $datosPaciente[0][4];
//                $datosCabecera[13] = $datosFecha[0][0];
//                $datosCabecera[14] = substr($numeroRecibo, 4, strlen($numeroRecibo));
//                $datosCabecera[15] = $numeroRecibo;
//                $datosCabecera[16] = $exitenciaHistoriaClinica[0][0];
                $datosCabecera[0] = $DNI;
                $datosCabecera[1] = $nombreCompleto;
                $datosCabecera[2] = $tipoCertificado;
                $datosCabecera[3] = $c_cod_per;
                $datosCabecera[4] = $apellidos;
                $datosCabecera[5] = $nombre;
                $datosCabecera[6] = $fechaActual;
                $datosCabecera[7] = $fechaCaducidad;

                /* ===================================================================================================== */
                /* =======================================   Datos de Pie   ============================================ */

                $datosPie[0] = '';
                /* ===================================================================================================== */
                /* =====================================   Todo Los atributos   ======================================== */
//                $atributosRecibo = $o_ActionReporte->atributosRecetaMedica($idReporte);
                $atributosRecibo = $o_ActionReporte->atributosRecetaMedica($idReporte);
                $parametros["PDF_PAGE_FORMAT"] = "CERNET_SANIDAD";
                $parametros["PDF_MARGIN_HEADER"] = 0;
                $parametros["PDF_MARGIN_FOOTER"] = 0;
                $parametros["AUTO_PAGE_BREAK"] = false;
                $parametros["PDF_MARGIN_BOTTOM"] = 0;
                $parametros["PDF_PAGE_ORIENTATION"] = "L";
                $parametros["PDF_MARGIN_LEFT"] = 10;
                $parametros["PDF_MARGIN_TOP"] = 0;
                $parametros["PDF_MARGIN_RIGHT"] = 0;
                $parametros["PRINT_HEADER"] = false;
                $parametros["PRINT_FOOTER"] = false;
                //$datosPie = "";
                $nombreReporte = 'Carnet Sanidad';
                $reporteRecibo->generarMYPDF_CARNET_SANIDAD($datosCabecera, $modo, $nombreReporte, $parametros, $labelCabecera, $atributosRecibo);
                break;
            }
        case "entregaResultadoImagenes": {
                require_once('generadorDeReportes.php');
                $setdat = new PluginMYPDF();

//orden del array: nroOrden, especialidad, paciente, medico, fecha, hora, ambienteLogio
                $arrayDatos = $_REQUEST["p3"];
                $idReporte = $_REQUEST["p4"];
                $iIdUbicacionesImagenes = $_REQUEST["p5"];
                $datosExamen = $o_ActionReporte->aDatosExamenes($iIdUbicacionesImagenes);
                $datosTicketCita = explode("|", $arrayDatos);

                $nombreReporte = "Cita" . $datosTicketCita[0];

                /* ===================================================================================================== */
                /* =======================================   Datos de Cabecera   ============================================ */
                $labelCabecera = $o_ActionReporte->labelReportePdf("ticketcita", $idReporte, 1);
                $datosCabecera = array();
                $datosPie = array();
                $datosCabecera[0] = "";
                /* ===================================================================================================== */
                /* =======================================   Datos de Detalle   ============================================ */
                $labelDetalle = $o_ActionReporte->labelReportePdf("ticketcita", $idReporte, 2);
//                for ($i = 0; $i < count($datosTicketCita) - 1; $i++) { //observacion (-1) por que entrar 7 columnas (del 0 al 6) pero se envia al generador de reporte solo 6 columas
//                    $datosDetalle[0][$i] = $datosTicketCita[$i];
//                }
                $datosCabecera[1] = $datosTicketCita[2];
                $datosCabecera[3] = utf8_encode($datosTicketCita[3]);
                // $datosDetalle[0][0]=$datosTicketCita[2];
                $datosDetalle[0][0] = utf8_encode(strtoupper($datosExamen[0][0]));
                //$datosDetalle[1][0]=$datosTicketCita[2];
                $datosDetalle[1][0] = utf8_encode($datosExamen[1][0]);
                //$datosDetalle[2][0]=$datosExamen[2][0];
                $datosPie[0] = utf8_encode($datosExamen[0][1]);
                $datosPie[1] = utf8_encode($datosExamen[0][2]);
                $datosPie[2] = utf8_encode($datosExamen[0][3]);
                $datosPie[3] = utf8_encode($datosExamen[0][4]);
                /* ===================================================================================================== */
                /* =======================================   Datos de Pie   ============================================ */
                //
                $labelPie = $o_ActionReporte->labelReportePdf("ticketcita", $idReporte, 3);
//                $datosMedico=$o_ActionReporte->datosMedico($codMedico);
//                $datosPie[2]="";

                /* ===================================================================================================== */
                /* =====================================   Todo Los atributos   ======================================== */
                $atributosReceta = $o_ActionReporte->atributosRecetaMedica($idReporte);
                //print_r($atributosReceta);
                $parametros["PDF_PAGE_FORMAT"] = "TICKET_CARGO";
                $parametros["PDF_MARGIN_HEADER"] = 0;
                $parametros["PDF_MARGIN_FOOTER"] = 0;
                $parametros["AUTO_PAGE_BREAK"] = false;
                $parametros["PDF_MARGIN_BOTTOM"] = 0;
                //$parametros["PDF_PAGE_ORIENTATION"] = "L";
                $parametros["PDF_MARGIN_LEFT"] = 1;
                $parametros["PDF_MARGIN_TOP"] = 0;
                $parametros["PDF_MARGIN_RIGHT"] = 1;
                $parametros["PRINT_HEADER"] = false;
                $parametros["PRINT_FOOTER"] = false;
                //$parametros[""]
                $setdat->generarMYPDF($atributosReceta, $labelCabecera, $labelDetalle, $labelPie, $datosCabecera, $datosDetalle, $datosPie, $modo, $nombreReporte, $parametros);
                break;
            }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
