<?php
require '../vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
require_once "controllers/controllerProduct.php";
$controller = new ControllerProduct;
$controller->afficherTout();
// echo '<pre>',print_r($controller->getProductsManager()->chargementProductsExport()),'</pre>' ;

$montableauBDD = $controller->getProductsManager()->chargementProductsExport();
// echo '<pre>',print_r($montableauBDD),'</pre>' ;

// echo '<pre>',print_r($montableauBDD[1]),'</pre>' ;
for ($h = 0; $h < count($montableauBDD); $h++) {
    // on associe chaque cellule à son libellé pour chaque enregistrement ou ligne
    $lastData[$h]['code'] = $montableauBDD[$h]['code'];
    $lastData[$h]['description'] = $montableauBDD[$h]['description'];
    $lastData[$h]['price'] = floatval($montableauBDD[$h]['price']);
    $lastData[$h]['categorie'] = $montableauBDD[$h]['nom'];
    $lastData[$h]['statut'] = $montableauBDD[$h]['nom_statut'];
    $lastData[$h]['fournisseur'] = $montableauBDD[$h]['nom_fournisseur'];
    $lastData[$h]['purchase_date'] = $montableauBDD[$h]['purchase_date'];
    //error_log(print_r('date : '. $lastData[$h]['purchase_date']));
    $lastData[$h]['expiration_date'] = $montableauBDD[$h]['expiration_date'];
    $lastData[$h]['nom_fichier'] = $montableauBDD[$h]['nom_fichier'];
    echo '<pre>', print_r($lastData[$h]['code']), '</pre>';
}
//echo '<pre>',print_r( $lastData[1]['code']),'</pre>' ;
//echo '<pre>',print_r($lastData),'</pre>' ;


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
// $sheet->setCellValue('A1', 'Hello World !');

// $writer = new Xlsx($spreadsheet);
// $writer->save('export/export_'.date('y_m_d').'.xlsx');

// $arrayData = [
//     [NULL, 2010, 2011, 2012],
//     ['Q1',   12,   15,   21],
//     ['Q2',   56,   73,   86],
//     ['Q3',   52,   61,   69],
//     ['Q4',   30,   32,    0],
// ];
// $spreadsheet->getActiveSheet()
//     ->fromArray(
//         $arrayData,  // The data to set
//         NULL,        // Array values with this value will not be set
//         'A'         // Top left coordinate of the worksheet range where
//         //    we want to set these values (default is A1)
//     );

$header = array(
    'A'=>'Index',
    'B'=>'Code',
    'C'=>'Description',
    'D'=>'Price',
    'E'=>'Categorie',
    'F'=>'Statut',
    'G'=>'Fournisseut',
    'H'=>'Date de fabrication',
    'I'=>'Date d\'expiration',
    'J'=>'nom_fichier',
  );
echo count($header);
  foreach ($header as $key =>$value) {
    $sheet->setCellValue($key.'1', $value);
          }
for ($i = 2; $i < count($lastData); $i++) {


    for ($j = 'A'; $j <= 'J'; $j++) {

        switch ($j) {
            case 'A':
                $sheet->setCellValue($j . $i, $i-1);
                break;
                case 'B':
                    $sheet->setCellValue($j . $i, $lastData[$i - 2]['code']);
                    break;
            case 'C':
                $sheet->setCellValue($j . $i, $lastData[$i - 2]['description']);
                break;
            case 'D':
                $sheet->setCellValue($j . $i, $lastData[$i - 2]['price']/100);
                break;
            case 'E':
                $sheet->setCellValue($j . $i, $lastData[$i - 2]['categorie']);
                break;
            case 'F':
                $sheet->setCellValue($j . $i, $lastData[$i - 2]['statut']);
                break;
            case 'G':
                $sheet->setCellValue($j . $i, $lastData[$i - 2]['fournisseur']);
                break;
            case 'H':
                $sheet->setCellValue($j . $i, $lastData[$i - 2]['purchase_date']);
                break;
            case 'I':
                $sheet->setCellValue($j . $i, $lastData[$i - 2]['expiration_date']);
                break;
            case 'J':
                $sheet->setCellValue($j . $i, $lastData[$i - 2]['nom_fichier']);
                break;
         
        }
    
    }
}


   $writer = new Xlsx($spreadsheet);
$fichier_tpm='export/export_'.date('y_m_d').'.xlsx';

    $writer->save($fichier_tpm);