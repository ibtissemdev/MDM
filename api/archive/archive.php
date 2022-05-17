<?php

require '../../vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


// Fonction pour récupérer l'élément maximum d'un tableau à deux dimension
function maxValueInArray($array, $keyToSearch)
{
    $currentMax = NULL;
    //On parcourt le premier tableau
    foreach($array as $arr)
    { //On parcourt le tableau assiociatif qui est dans la premier tableau
        foreach($arr as $key => $value)
        { // si la clé est égale à la clé recherchée et que la valeur est supérieur à la valeur max courante alors elle devient le nouveau maximimun
            if ($key == $keyToSearch && ($value >= $currentMax))
            {
                $currentMax = $value;
            }
        }
    }

    return $currentMax;
}

$fichier=scandir('.');
//print_r ($fichier);
// $fichier_a_traiter=$fichier[3];
//print_r($fichier_a_traiter);
$tableau_xlsx=['xlsx',"xls"];


$valid_file=[]; 

for($i=0;$i<count($fichier);$i++) {
//Récupérer l'extension du fichier
$extension=pathinfo($fichier[$i],PATHINFO_EXTENSION);
    if (in_array($extension,$tableau_xlsx)) {
        //Création d'un tableau assiociatif 
$tmp_table = ['name'=>$fichier[$i], 'filemtime' =>filemtime($fichier[$i])]; 

        $valid_file[]=$tmp_table;
        //filemtime($fichier[$i]) . '<br>';
        
    }

}
$max = maxValueInArray($valid_file, 'filemtime');
print_r($max);
for($i=0;$i<count($valid_file);$i++){
    if ($valid_file[$i]['filemtime']==$max){
      

         echo $valid_file[$i]['name'];
    }
}




//                                            array       key

// $plusGrand= max ($valid_file['filemtime']);

// print_r($plusGrand);
//print_r ($valid_file). '<br>';


// foreach ($valid_file as $fichier_a_traiter ) {
//  // echo 'date :' . date('Y/m/d',filemtime($fichier_a_traiter )) .'<br>';
//  filemtime($fichier_a_traiter ) . '<br>';

// // $last_state []= $fichier_a_traiter; 

//  $plusGrand= max (filemtime($fichier_a_traiter ));
// }


// print_r($plusGrand)  ;