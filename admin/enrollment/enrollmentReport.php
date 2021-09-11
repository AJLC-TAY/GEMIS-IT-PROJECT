<?php

use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\TblWidth;

require_once './../../class/Administration.php';
require_once './../../vendor/phpoffice/phpword/bootstrap.php';
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('./../../templates/enroll_report_template.docx');

# ENROLLMENT DATA
// $admin = new Administration();
// $tracks = $admin->getEnrollmentReportData();

$tracks = $_POST['tracks'];

# DATA
const ARIAL = 'Arial';
const SIZE = '12';

$school_year = $_POST['school-year'];
$title_desc = $_POST['report-title'];
$signatory_desc = $_POST['signatory'];
$position_desc = $_POST['position'];
$date_desc = date('F j, Y', strtotime($_POST['date']));

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
$row->addCell(2000)->addTextRun($cellHCentered)->addText('Track', $bold + $default_font_style + $text_center);
$row->addCell(3000)->addTextRun($cellHCentered)->addText('Strand', $bold + $default_font_style + $text_center);

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
	foreach($track_value as $strand_id => $strand_count) {
		$is_last_element = $strand_id == array_key_last($track_value);

		$rejected = $rejected_count_list[] = $strand_count[0];
		$accepted = $accepted_count_list[] = $strand_count[1];

		// Strand name
		$cell2 = $table->addCell(3000);
		$textrun2 = $cell2->addTextRun($cellHCentered);
		$textrun2->addText($strand_id, $default_font_style);

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
$cell2 = $table->addCell(6000, array('gridSpan' => 3, 'valign' => 'center'));
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



$filename = $school_year."_enrollment_report.docx";
$tmpFile = "./temp/temp_test.pdf";
$outFile = "./temp/$filename";
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=".$filename);
header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
// header("Content-Type: application/pdf");
header("Content-Transfer-Encoding: binary");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
// header("Expires: 0");
header('Refresh: 0; url=enrollment/enrollList.php');

$templateProcessor->saveAs("php://output");


// $templateProcessor->saveAs($outFile);
// $domPdfPath = realpath('./../../vendor/dompdf/dompdf');
// Settings::setPdfRendererPath($domPdfPath);
// Settings::setPdfRendererName('DomPDF');

// //Load temp file
// $phpWord = IOFactory::load($outFile); 
// //Save it
// $xmlWriter = IOFactory::createWriter($phpWord , 'PDF');
// $xmlWriter->save('result.pdf');  
// // $xmlWriter->save("php://output");
// // unlink($outFile);


// $url = 'https://api2.docconversionapi.com/jobs/create';
// $fields = array(
// 	'inputFile' => 'https://www.docconversionapi.com/samples/example.docx',
// 	'conversionParameters' => '{}',
// 	'outputFormat' => 'pdf',
// 	'async' => 'false'
// );
// //url-ify the data for the POST
// foreach ($fields as $key => $value) {
// 	$fields_string .= $key . '=' . $value . '&';
// }
// $fields_string = rtrim($fields_string, '&');
// //open connection
// $ch = curl_init();
// //set the url, number of POST vars, POST data
// curl_setopt($ch, CURLOPT_HTTPHEADER, array(
// 	'X-ApplicationID: Get your key from https://app.docconversionapi.com/#/applications',
// 	'X-SecretKey: Get your key from https://app.docconversionapi.com/#/applications'
// ));
// curl_setopt($ch, CURLOPT_URL, $url);
// curl_setopt($ch, CURLOPT_POST, count($fields));
// curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
// //execute post
// $result = curl_exec($ch);
// print $result;

?>
