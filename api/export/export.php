<?php
require '../../vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once "../controllers/controllerProduct.php";
//$montableauBDD => Récupérer tous les produits de la BDD
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
    echo '<pre>', print_r($lastData[$h]['statut']), '</pre>';
}
//echo '<pre>',print_r( $lastData[1]['code']),'</pre>' ;
//echo '<pre>',print_r($lastData),'</pre>' ;

// $lastData => GENERATION DU TABLEAU avec l'entête 
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

//Création d'une entête
$header = array( 
    'A' => 'Index',
    'B' => 'Code',
    'C' => 'Description',
    'D' => 'Price',
    'E' => 'Categorie',
    'F' => 'Statut',
    'G' => 'Fournisseut',
    'H' => 'Date de fabrication',
    'I' => 'Date d\'expiration',
    'J' => 'nom_fichier',
);
echo count($header);
foreach ($header as $key => $value) {
    $sheet->setCellValue($key . '1', $value);
}
for ($i = 2; $i < count($lastData); $i++) {
// On entre les valeurs dans les cellules du tableau excel

    for ($j = 'A'; $j <= 'J'; $j++) {

        switch ($j) {
            case 'A':
                $sheet->setCellValue($j . $i, $i - 1);
                break;
            case 'B':
                $sheet->setCellValue($j . $i, $lastData[$i - 2]['code']);
                break;
            case 'C':
                $sheet->setCellValue($j . $i, $lastData[$i - 2]['description']);
                break;
            case 'D':
                $sheet->setCellValue($j . $i, $lastData[$i - 2]['price'] / 100);
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

//Création du fichier excel avec la date
//    $writer = new Xlsx($spreadsheet);
// $fichier_tpm='export_'.date('y_m_d_i').'.xlsx';

//     $writer->save($fichier_tpm);

// Fonction pour récupérer l'élément maximum d'un tableau à deux dimensions
function maxValueInArray($array, $keyToSearch)
{
    $currentMax = NULL;
    //On parcourt le premier tableau
    foreach ($array as $arr) { //On parcourt le tableau assiociatif qui est dans la premier tableau
        foreach ($arr as $key => $value) { // si la clé est égale à la clé recherchée et que la valeur est supérieur à la valeur max courante alors elle devient le nouveau maximimun
            if ($key == $keyToSearch && ($value >= $currentMax)) {
                $currentMax = $value;
            }
        }
    }

    return $currentMax;
}
//$fichier => Lister les fichiers dans le dossier courant 
$fichier = scandir('.');
//print_r ($fichier);
// $fichier_a_traiter=$fichier[3];
//print_r($fichier_a_traiter);


$tableau_xlsx = ['xlsx', "xls"]; // Créer un tableau avec les extensions possibles d'excel 


$valid_file = [];

for ($i = 0; $i < count($fichier); $i++) {
    //Récupérer l'extension du fichier
    $extension = pathinfo($fichier[$i], PATHINFO_EXTENSION);
    if (in_array($extension, $tableau_xlsx)) {
        //Création d'un tableau assiociatif 
        $tmp_table = ['name' => $fichier[$i], 'filemtime' => filemtime($fichier[$i])]; // associer le nom du fichier à sa date de création
        $valid_file[] = $tmp_table;
        //filemtime($fichier[$i]) . '<br>';

    }
}
$max = maxValueInArray($valid_file, 'filemtime');
//print_r($max);
for ($i = 0; $i < count($valid_file); $i++) {
    //Sélectionner le fichier plus récent
    if ($valid_file[$i]['filemtime'] == $max) {

        $spreadsheet = new Spreadsheet(); //Lecture seul du fichier le plus récent  1= pour ne pas modifier les cellules
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($valid_file[$i]['name'], 1); 
        $sheet = $spreadsheet->getActiveSheet();

        $maxRow = $sheet->getHighestRow();
        //echo '<pre>',print_r($maxRow),'</pre><br>';
        $maxCol = $sheet->getHighestColumn();
        //echo '<pre>',print_r($maxCol),'</pre><br>';

        // echo $valid_file[$i]['name'];
    }
}
//$liste_a_commander => LISTE DES PRODUITS A COMMANDER
$liste_a_commander = [];

for ($i = 2; $i < $maxRow; $i++) {

    for ($j = 'A'; $j <= $maxCol; $j++) {

        if ($sheet->getCell($j . $i)->getValue() == 'Epuisé') {

            //echo   $i . '<br>';

            for ($k = 'A'; $k <= $maxCol; $k++) {
                $liste_a_commander[$i - 2][] = $sheet->getCell($k . $i)->getValue();
            }
        }
    }
}



//CLASSEMENT DES PRODUITS PAR FOURNISSEURS 
for ($i = 0; $i < count($liste_a_commander); $i++) {

    if ($liste_a_commander [$i][6] == $liste_fournisseur) {

    }
}
//print_r($liste_a_commander[6]); 

echo '<pre>', print_r($liste_a_commander), '</pre><br>';
