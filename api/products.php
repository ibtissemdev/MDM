<?php
//constante que l'on peut utiliser dans tout le site
define("CHEMIN", str_replace("products.php", "", (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));

require_once "controllers/controllerProduct.php";
$controller = new ControllerProduct;
var_dump($_SERVER['REQUEST_METHOD']);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            $url = explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
            if (!empty($url[1]) && $url[0] == 'display') {
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
            }
        }
        break;

    case 'POST':
        //var_dump($_POST["description"]);

        if (@$_POST['recherche_categorie'] && !isset($_POST['recherche_statut']) && !isset($_POST['description'])) {
            // print_r($_POST);
            $controller->categorie($_POST['recherche_categorie']);
        } else if ($_POST['recherche_statut']&& !isset($_POST['description']) && !isset($_POST['recherche_categorie']) ) {
            $controller->statut($_POST['recherche_statut']);
          

        } else if ($_POST["description"] && !isset($_POST['recherche_statut']) && !isset($_POST['recherche_categorie'])) {
            $controller->description($_POST["description"]);
            //print_r($_POST["description"]);
       
        } else {
            $keys = [];
            $champs = [];
            $values = [];
            //error_log(print_r($_POST, 1)); 
            foreach ($_POST as $key => $value) {
                $keys[] = $key;
                $champs[] = '?';
                $values[] = $value;
            }
            array_splice($keys, 3, 1);
            array_splice($champs, 3, 1);
            array_splice($values, 3, 1);

            $keys = implode(",", $keys);
            $champs = implode(",", $champs);
            $conn = $controller->getProductsManager()->getPdo();

            print_r($keys);
            print_r($champs);
            print_r($values);
            $sth = $conn->prepare("INSERT INTO  produits ($keys) VALUES ($champs)");
            $sth->execute($values);

            error_log(print_r($sth, 1));

            //Rempli la table de liaison categorie
            $produits_id = $conn->lastInsertId();      //Récupère l'id de la dernière entrée
            print_r($produits_id);

            $category_id = $_POST['category_id'];

            $sth = $conn->prepare("INSERT INTO  liaison_categorie (category_id,produits_id) VALUES (?,?)");

            $sth->execute([$category_id, $produits_id]);

            $sth = $conn->prepare("SELECT code From produits WHERE id_product=$produits_id");
            $sth->execute();
            $result = $sth->fetch(); //récupère le category_id de la dernière entrée

            $code = $result['code'];


            //REmpli la table de liason assets
            $sth = $conn->prepare("SELECT id_assets From assets WHERE nom_fichier LIKE '%$code%'");
            $sth->execute();
            $result = $sth->fetch(); //récupère le category_id de la dernière entrée

            $id_assets = $result['id_assets'];
            $sth = $conn->prepare("INSERT INTO  liaison_assets (produits_id,assets_id,drapeau) VALUES ($produits_id,$id_assets,1)");
            $sth->execute();
        }


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
            $sth = $conn->prepare("UPDATE produits SET $keys WHERE id_product =$url[1]");
            error_log(print_r($sth, 1));
            $sth->execute($values);
        }

        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $url = explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
            $controller->supprimer($url[1]);
            $sth = $conn->prepare("DELETE FROM produits  WHERE id_product=$url[1]");
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
