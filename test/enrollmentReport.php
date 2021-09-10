<?php

use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\TblWidth;

require_once '../class/Administration.php';
require_once './vendor/phpoffice/phpword/bootstrap.php';
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../templates/report_template.docx');


# DATA
const ARIAL = 'Arial';
const SIZE = '12';
$school_year = "2021-2022";
$title_desc = "Enrollment Report";
$signatory_desc = "Alvin John Cutay";
$position_desc = "Student";
$date_desc = date("F j, Y");

$default_font_style = array("name" => ARIAL, "size" => SIZE);
$bold = ["bold" => true ];

# Set Values
$title = new TextRun();
$signatory = new TextRun();
$position = new TextRun();
$sy = new TextRun();
$date = new TextRun();

$position->addText($position_desc, $default_font_style);
$signatory->addText($signatory_desc,  $default_font_style);
$title->addText($title_desc, $bold + $default_font_style);
$date->addText($date_desc, $bold + $default_font_style);
$sy->addText($school_year, $default_font_style);

$templateProcessor->setComplexValue('title', $title);
$templateProcessor->setComplexValue("signatory", $signatory);
$templateProcessor->setComplexValue("position", $position);
$templateProcessor->setComplexValue("date", $date);
$templateProcessor->setComplexValue('school_year', $sy);

$row = 10;

# DATA
$tracks = [
	"Academic" => [
		"ABM" => [90, 10], // Accepted, rejected
		"HuMSS" => [80, 3] 
	], 
	"TVL" => [
		"Bread & Pastry" => [70, 4], 
		"Other" => [10, 5] 
	]
];

# CELL STYLES
$cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center');
$cellRowContinue = array('vMerge' => 'continue');
$cellColSpan = array('gridSpan' => 2);
$cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);
$cellHEnd = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END);
$cellVCentered = array('valign' => 'center');
$text_center = ['align' => 'center'];

$table = new Table(['borderSize' => 6, 'width' => 10500, 'unit' => TblWidth::TWIP]);

# HEADER
$row = $table->addRow();
$row->addCell(2000)->addText('Track', $bold + $default_font_style + $text_center);
$row->addCell(3000)->addText('Strand', $bold + $default_font_style + $text_center);

$row->addCell(2000, $cellColSpan)
    ->addTextRun($cellHCentered)
	->addText('Accepted', $bold + $default_font_style + $text_center);
// $row->addCell(null, null);
$row->addCell(2000, $cellColSpan)
	->addTextRun($cellHCentered)
	->addText('Rejected', $bold + $default_font_style + $text_center);
# HEADER END

# ROWS
$accepted_grand_total = [];
$rejected_grand_total = [];
foreach($tracks as $track_id => $track_value) {
	$table->addRow();
	$cell1 = $table->addCell(2000, $cellRowSpan);
	$textrun1 = $cell1->addTextRun($cellHCentered);
	$textrun1->addText($track_id, $default_font_style);

	$accepted_count_list = [];
	$rejected_count_list = [];
	foreach($track_value as $tv_id => $tv_value) {
		$is_last_element = $tv_id == array_key_last($track_value);

		$accepted = $accepted_count_list[] = $tv_value[0];
		$rejected = $rejected_count_list[] = $tv_value[1];

		// Strand name
		$cell2 = $table->addCell(3000);
		$textrun2 = $cell2->addTextRun($cellHCentered);
		$textrun2->addText($tv_id, $default_font_style);

		// Accepted count
		$cell3 = $table->addCell(1000);
		$textrun3 = $cell3->addTextRun($cellHCentered);
		$textrun3->addText($accepted, $default_font_style);

		// Accepted sub total column
		if ($is_last_element) { 			// if the element is the last key, calculate total
			$accepted_grand_total[] = $total_accepted = array_sum($accepted_count_list);
			$cell4 = $table->addCell(1000);
			$textrun4 = $cell4->addTextRun($cellHCentered);
			$textrun4->addText($total_accepted, $default_font_style);
		} else {
			$cell4 = $table->addCell(1000, $cellRowContinue);
		}

		// 
		$cell5 = $table->addCell(1000);
		$textrun5 = $cell5->addTextRun($cellHCentered);
		$textrun5->addText($rejected, $default_font_style);

		if ($is_last_element) {
			$rejected_grand_total[] = $total_rejected = array_sum($rejected_count_list);
			$cell6 = $table->addCell(1000);
			$textrun6 = $cell6->addTextRun($cellHCentered);
			$textrun6->addText($total_rejected, $default_font_style);
		} else {
			$cell6 = $table->addCell(1000, $cellRowContinue);
			$table->addRow();
			$table->addCell(null, $cellRowContinue);
		}
	}
}
$table->addRow();
$cell2 = $table->addCell(8000, array('gridSpan' => 3, 'valign' => 'center'));
$textrun2 = $cell2->addTextRun($cellHEnd);
$textrun2->addText('Total', $default_font_style);

$cell6 = $table->addCell(2000);
$textrun6 = $cell6->addTextRun($cellHCentered);
$textrun6->addText(array_sum($accepted_grand_total), $default_font_style);

$table->addCell(null, $cellRowContinue);

$cell6 = $table->addCell(2000);
$textrun6 = $cell6->addTextRun($cellHCentered);
$textrun6->addText(array_sum($rejected_grand_total), $default_font_style);

$templateProcessor->setComplexBlock('table', $table);



$filename = "template_test.docx";
$tmpFile = "./E1/temp_test.php";
$outFile = "./E1/$filename";
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=".$filename);
header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
// header("Content-Type: application/pdf");
header("Content-Transfer-Encoding: binary");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Expires: 0");


// $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($templateProcessor , 'PDF');    
// $pdfWriter->save($filename.".pdf");
// unlink($wordPdf);
$templateProcessor->saveAs("php://output");


// $templateProcessor->saveAs($outFile);
// $phpWord = IOFactory::load($outfile);

// $phpWord->save("php://output");
  

// use \PhpOffice\PhpWord\Settings;

// Settings::setPdfRendererPath($outFile);
// Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);

// //Load temp file
// $phpWord = IOFactory::load($outfile); 

// //Save it
// $xmlWriter = IOFactory::createWriter($phpWord , 'PDF');
// $xmlWriter->save('result.pdf');
// unlink($tmpFile);      

     
// $domPdfPath = realpath('./vendor/dompdf/dompdf');
// Settings::setPdfRendererPath($domPdfPath);
// Settings::setPdfRendererName('DomPDF');

// $phpWord = IOFactory::load($outFile); 
// $xmlWriter = IOFactory::createWriter($phpWord , 'PDF');
// $xmlWriter->save($tmpFile); 