<?php
require_once "database.php";
$connection = new Database;
$pdo = $connection->getPdo();

//error_log($method);
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            $url = explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
            error_log(print_r(($url[0]), 1));
            if (!empty($url[1])) {
                //error_log(print_r($url),1);
                $sth = $pdo->prepare("SELECT * From produits WHERE id_product=$url[1]");
                $sth->execute();
                $resultat = $sth->fetch(); //Afficher toutes les entrées (un tableau) dans le tableau
                echo "<pre>", print_r($resultat), "</pre>";
            } else if ($url[0] == 'products') {
                $url = explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
                // if($url[0]=='products'){
                $sth = $pdo->prepare("SELECT * From produits ");
                $sth->execute();
                $resultat = $sth->fetchAll(); //Afficher toutes les entrées (un tableau) dans le tableau
                echo "<pre>", print_r($resultat), "</pre>";
                error_log(print_r(($resultat), 1));
                // }
            }
        }
        break;

    case 'POST':
        //error_log(print_r($_POST, 1));
        $keys = [];
        $champs = [];
        $values = [];

        foreach ($_POST as $key => $value) {
            if ($value != null && $key != 'table' && $key != 'pdo') {
                $keys[] = $key;
                $champs[] = '?';
                $values[] = $value;
            }
        }
        $keys = implode(",", $keys);
        $champs = implode(",", $champs);
        $sth = $pdo->prepare("INSERT INTO  produits ($keys) VALUES ($champs)");
        $sth->execute($values);
        //Rempli la table de liaison categorie
        $produits_id = $pdo->lastInsertId();      //Récupère l'id de la dernière entrée

        $sth = $pdo->prepare("SELECT category_id,code From produits WHERE id_product=$produits_id");
        $sth->execute();
        $result = $sth->fetch(); //récupère le category_id de la dernière entrée

        echo "<pre>", print_r($result), "</pre>";
        $category_id = $result['category_id']; //implode("", $result['category_id']);
        echo $category_id;

        $code = $result['code'];

        $sth = $pdo->prepare("INSERT INTO  liaison_categorie (category_id, produits_id) VALUES ($category_id,$produits_id)");
        $sth->execute();
        error_log(print_r($sth, 1));

        //REmpli la table de liason assets
        $sth = $pdo->prepare("SELECT id_assets From assets WHERE nom_fichier LIKE '%$code%'");
        $sth->execute();
        $result = $sth->fetch(); //récupère le category_id de la dernière entrée

        $id_assets=$result['id_assets'];
        $sth = $pdo->prepare("INSERT INTO  liaison_assets (produits_id,assets_id,drapeau) VALUES ($produits_id,$id_assets,1)");
        $sth->execute();


        break;

    case 'PUT':
        if (isset($_GET['id'])) {
            $url = explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
            $PUT = array(); //tableau qui va contenir les données reçues
            parse_str(file_get_contents('php://input'), $PUT);
            // error_log(print_r($PUT, 1));
            $keys = [];
            $values = [];

            foreach ($PUT as $key => $value) {
                if ($value != null && $key != 'table' && $key != 'pdo') {
                    $keys[] = "$key = ?";
                    $values[] = $value;
                }
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
            $sth = $pdo->prepare("DELETE FROM produits  WHERE id_product=$url[1]");
            $sth->execute();
        }
        break;
    case 'DUPLICATE':
        if (isset($_GET['id'])) {
            $url = explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
            $DUPLICATE = array(); //tableau qui va contenir les données reçues
            parse_str(file_get_contents('php://input'), $DUPLICATE);

            // $sth = $pdo->prepare("INSERT INTO produits (code,description,price,category_id,statut_id,supplier_id,purchase_date,expiration_date) SELECT code,description,price,category_id,statut_id,supplier_id,purchase_date,expiration_date FROM produits where id_product =$url[1]");
            // $sth->execute(); //Duplique une entrée
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
        $sth = $pdo->prepare("INSERT INTO produits ($keys) VALUES ($champs) ON DUPLICATE KEY UPDATE code=".$DUPLICATE['code']." where id_product =$url[1]");
        var_dump($sth);
            $sth->execute($values); //Duplique une entrée
        
        }
        break;
    default:
        echo 'Je ne connais pas la méthode';
        break;
}
