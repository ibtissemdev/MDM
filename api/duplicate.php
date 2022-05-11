<?php
$url = "http://localhost/MDM/api/products/44"; // dupliquer le produit 7
$data = array(
    'code' => 'BAG2',
    'description' => 'La baguette',
    'price' => '357',
    'category_id' => '1',
    'statut_id' => '3',
    'supplier_id' => '9',
    'purchase_date' => '2022-04_09 10:40:00',
    'expiration_date' => '2022-04-13 10:40:00'
);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DUPLICATE");
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
$response = curl_exec($ch);
var_dump($response);
if (!$response) {
 return false;
}