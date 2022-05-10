<?php

$POST = array(); //tableau qui va contenir les données reçues
parse_str(file_get_contents('php://input'), $POST);

$url = "http://localhost/MDM/api/products.php"; // ajouter le produit 1

$data = array(
    'code' => 'BAG',
    'description' => 'La baguette',
    'price' => '357',
    'category_id' => '1',
    'statut_id' => '3',
    'supplier_id' => '9',
    'purchase_date' => '2022-04_09 10:40:00',
    'expiration_date' => '2022-04-13 10:40:00'
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