<?php

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use Dompdf\Dompdf;
require_once './../../vendor/phpoffice/phpword/bootstrap.php';
$phpWord = new TemplateProcessor('./../../templates/enroll_report_template2.docx');
//$phpWord = new TemplateProcessor('./2021-2022_enrollment_report.docx');
//$renderer_name = Settings::PDF_RENDERER_TCPDF;
//$renderer_library_path =  realpath('./../../test/TCPDF');

$renderer_name = Settings::PDF_RENDERER_DOMPDF;
$renderer_library_path =  realpath('./../../vendor/dompdf/dompdf');
Settings::setPdfRenderer($renderer_name, $renderer_library_path);
//Settings::setPdfRendererPath();
//Settings::setPdfRendererName('Dompdf');


$phpWord = IOFactory::load("./2021-2022_enrollment_report.docx");
$xmlWriter = IOFactory::createWriter($phpWord,'PDF');
$xmlWriter->save('result.pdf');  // Save to PDF

