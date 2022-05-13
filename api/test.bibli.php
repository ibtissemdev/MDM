<?php

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$fichier=scandir('input/');
//print_r ($fichier[2]);
$fichier_a_traiter="input/".$fichier[2];
//print_r($fichier_a_traiter);

$spreadsheet = new Spreadsheet();                                   //1= pour pas modifier les cellules
$spreadsheet=\PhpOffice\PhpSpreadsheet\IOFactory::load($fichier_a_traiter,1);
$sheet=$spreadsheet->getActiveSheet();

$maxRow=$sheet->getHighestRow();
//echo '<pre>',print_r($maxRow),'</pre><br>';
$maxCol=$sheet->getHighestColumn();
//echo '<pre>',print_r($maxCol),'</pre><br>';

$recupAllData=[];

for ($i=2 ; $i<$maxRow ; $i++ ) {
$recupOneData=[];

    for ($j='B' ; $j<=$maxCol ; $j++) {

       
        // insérer toute une ligne dans le tableau $recupOneData si $j=B si $j=C...
        $recupOneData[]= $sheet->getCell($j.$i)->getCalculatedValue();

         
    }
    //On crée un tableau provisoire pour stocke chaque enregistrement
    $recupAllData[]= $recupOneData;
}

//print_r(count($recupAllData));

//echo '<pre>',print_r($recupOneData),'</pre><br>';

for ($h=0 ; $h<count($recupAllData) ; $h++) {
// on associe chaque cellule à son libellé pour chaque enregistrement ou ligne
    $lastData[$h]['code']= $recupAllData[$h][0];
    $lastData[$h]['description']= $recupAllData[$h][1];
    $lastData[$h]['price']= $recupAllData[$h][2]*100;
    $lastData[$h]['category_id']= $recupAllData[$h][3];
    $lastData[$h]['statut_id']= $recupAllData[$h][4];
    $lastData[$h]['supplier_id']= $recupAllData[$h][5];
    $lastData[$h]['purchase_date']= date('Y/m/d',($recupAllData[$h][6]-25569)*86400) ;
    error_log(print_r('date : '. $lastData[$h]['purchase_date']));
    $lastData[$h]['expiration_date']= date('Y/m/d',($recupAllData[$h][7]-25569)*86400);
    $lastData[$h]['nom_fichier']= $recupAllData[$h][8];
}



//echo '<pre>',print_r($lastData),'</pre><br>';

$url = "http://localhost/MDM/api/products.php"; // afficher tous les produits



    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//true pour retourner le transfert en tant que chaîne de caractères de la valeur retournée par curl_exec() au lieu de l'afficher directement.
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($lastData));
    $response = curl_exec($ch);
    var_dump($response);
    if (!$response) {
    return false;


}



// $sheet->setCellValue('A1', 'Hello World !');



// $writer = new Xlsx($spreadsheet);
// $writer->save($fichier[2].date('y_m_d').'.xlsx');