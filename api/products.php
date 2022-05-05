<?php
require_once "controllers/controllerProduct.php";
$controller = new ControllerProduct;
var_dump($_SERVER['REQUEST_METHOD']);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            $url = explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
            if (!empty($url[1]) && $url[0]=='display' ) {
                $controller->afficherUn($url[1]);
                // $sth = $this->getPdo->prepare("SELECT * From produits WHERE id_product=$url[1]");
                // $sth->execute();
                // $resultat = $sth->fetch();
                // echo "<pre>", print_r($resultat), "</pre>";
            } else if ($url[0] == 'products') {
                // $url = explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
                // if($url[0]=='products'){
                $controller->afficherTout();
            } else if ($url[0] == 'delete' && !empty($url[1])) {
                $controller->supprimer($url[1]);
            } else if ($url[0] == 'update' && !empty($url[1])) {
                $controller->miseAJour($url[1]);
               if (!empty($url[2])) {
        
                require "request.php";

               }
             
        }}
        break;

    case 'POST':
        $keys = [];
        $champs = [];
        $values = [];
        foreach ($_POST as $key => $value) {
            $keys[] = $key;
            $champs[] = '?';
            $values[] = $value;
        }
        $keys = implode(",", $keys);
        $champs = implode(",", $champs);
        $conn=$controller->getProductsManager()->getPdo();
        $sth = $conn->prepare("INSERT INTO  produits ($keys) VALUES ($champs)");
        $sth->execute($values);
        //Rempli la table de liaison categorie
        $produits_id = $conn->lastInsertId();      //Récupère l'id de la dernière entrée

        $sth = $conn->prepare("SELECT category_id,code From produits WHERE id_product=$produits_id");
        $sth->execute();
        $result = $sth->fetch(); //récupère le category_id de la dernière entrée
   

        //echo "<pre>", print_r($result), "</pre>";
        $category_id = $result['category_id'];

        // echo $category_id;

        $code = $result['code'];

        $sth = $conn->prepare("INSERT INTO  liaison_categorie (category_id, produits_id) VALUES ($category_id,$produits_id)");
        $sth->execute();
        // error_log(print_r($sth, 1));

        //REmpli la table de liason assets
        $sth = $conn->prepare("SELECT id_assets From assets WHERE nom_fichier LIKE '%$code%'");
        $sth->execute();
        $result = $sth->fetch(); //récupère le category_id de la dernière entrée

        $id_assets = $result['id_assets'];
        $sth = $conn->prepare("INSERT INTO  liaison_assets (produits_id,assets_id,drapeau) VALUES ($produits_id,$id_assets,1)");
        $sth->execute();

        break;

    case 'PUT':
        if (isset($_GET['id'])) {
            $url = explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
            $PUT = array(); //tableau qui va contenir les données reçues
            parse_str(file_get_contents('php://input'), $PUT);
            $keys = [];
            $values = [];

            foreach ($PUT as $key => $value) {
                $keys[] = "$key = ?";
                $values[] = $value;
            }
            $keys = implode(",", $keys);
            error_log(print_r($keys, 1));
            $sth = $pdo->prepare("UPDATE produits SET $keys WHERE id_product =$url[1]");
            error_log(print_r($sth, 1));
            $sth->execute($values);
        }

        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $url = explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
            $controller->supprimer($url[1]);
            $sth = $pdo->prepare("DELETE FROM produits  WHERE id_product=$url[1]");
            $sth->execute();
        }
        break;
    case 'DUPLICATE':
        if (isset($_GET['id'])) {
            $url = explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
            $DUPLICATE = array(); //tableau qui va contenir les données reçues
            parse_str(file_get_contents('php://input'), $DUPLICATE);
            $keys = [];
            $champs = [];
            $values = [];
            foreach ($DUPLICATE as $key => $value) {

                $keys[] = $key;
                $champs[] = '?';
                $values[] = $value;
            }
            $keys = implode(",", $keys);
            $champs = implode(",", $champs);
            $sth = $pdo->prepare("INSERT INTO produits ($keys) VALUES ($champs) ON DUPLICATE KEY UPDATE id_product =id_product+1");
            var_dump($sth);
            $sth->execute($values); //Duplique une entrée

        }
        break;
    default:
        echo 'Je ne connais pas la méthode';
        break;
}
