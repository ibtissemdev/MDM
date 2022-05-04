<?php

$POST = array(); //tableau qui va contenir les données reçues
parse_str(file_get_contents('php://input'), $POST);

$url = "http://localhost/MDM/api/products.php"; // ajouter le produit 1

$data = array(
    'code' => 'PAI',
    'description' => 'Panettone italien',
    'price' => '539',
    'category_id' => '3',
    'statut_id' => '2',
    'supplier_id' => '6',
    'purchase_date' => '2022-04_01 10:40:00',
    'expiration_date' => '2022-10-01 10:40:00'
);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//true pour retourner le transfert en tant que chaîne de caractères de la valeur retournée par curl_exec() au lieu de l'afficher directement.
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
$response = curl_exec($ch);
var_dump($response);
if (!$response) {
return false;
}