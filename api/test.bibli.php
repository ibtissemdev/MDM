<?php

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$fichier=scandir('input/');
print_r ($fichier[2]);
$fichier_a_traiter="input/".$fichier[2];
print_r($fichier_a_traiter);

$spreadsheet = new Spreadsheet();
$classeur=\PhpOffice\PhpSpreadsheet\IOFactory::load($fichier_a_traiter);
$sheet=$spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Hello World !');



$writer = new Xlsx($spreadsheet);
$writer->save($fichier[2].date('y_m_d').'.xlsx');