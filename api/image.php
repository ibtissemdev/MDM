<?php
require_once "database.php";
$connection = new Database;
$pdo = $connection->getPdo();

$directory=scandir("assets/");

//var_dump($directory);
$extention = ".jpeg";
$extention2=".jpg";

for ($i=2 ; $i<count($directory); $i++) {
if (strpos($directory[$i],$extention )!==false) {
    $image[]=str_replace($extention,"",$directory[$i]);

} else if (strpos($directory[$i],$extention2 )!==false) {
    $image[]=str_replace($extention2,"",$directory[$i]);

}

}

echo "<pre>",print_r($image),"</pre>";

foreach ($image as $nom_image) {
  
    $sth=$pdo->prepare("INSERT INTO assets (nom_fichier,chemin) VALUES (:nom_image,'assets/')");
    $sth->bindParam(":nom_image",$nom_image,PDO::PARAM_STR);
    $sth->execute();
}
$sth = $pdo->prepare("SELECT code From produits WHERE id_product=$url[1]");
//     $sth->bindParam(":nom_image",$nom_image,PDO::PARAM_STR);
//     $sth->execute();

?>
