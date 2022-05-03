<?php
require_once "database.php";
$connection = new Database;
$pdo = $connection->getPdo();


$method = $_SERVER['REQUEST_METHOD'];

//error_log($method);
switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $url = explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
            error_log(print_r(($url[0]), 1));
            if ($url[1]) {
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

        $produits_id = $pdo->lastInsertId();      //Récupère l'id de la dernière entrée

        $sth = $pdo->prepare("SELECT category_id From produits WHERE id_product=$produits_id"); 
        $sth->execute();
        $result = $sth->fetch(); //récupère le category_id de la dernière entrée
        $category_id = implode("", $result);
        echo $category_id;

        $sth = $pdo->prepare("INSERT INTO  liaison_categorie (category_id, produits_id) VALUES ($category_id,$produits_id)");
        $sth->execute();
        error_log(print_r($sth, 1));



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

            $sth = $pdo->prepare("INSERT INTO produits (code,description,price,category_id,statut_id,supplier_id,purchase_date,expiration_date) SELECT 'TVF2',description,price,category_id,statut_id,supplier_id,purchase_date,expiration_date FROM produits where id_product =$url[1]");
            $sth->execute();//Duplique une entrée
        }
        break;
    default:
        echo 'Je ne connais pas la méthode';
        break;
}
