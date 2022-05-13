<?php
//constante que l'on peut utiliser dans tout le site
define("CHEMIN", str_replace("products.php", "", (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));

require_once "controllers/controllerProduct.php";
$controller = new ControllerProduct;
//var_dump($_SERVER['REQUEST_METHOD']);

switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        if (isset($_GET['id'])) {
            $url = explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
            if (!empty($url[1]) && $url[0] == 'display') {
                $controller->afficherUn($url[1]);
            } else if ($url[0] == 'products') {

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
        //error_log("traitement de la méthode POST : " . print_r($_POST, 1));

        if (@$_POST['recherche_categorie'] && !isset($_POST['recherche_statut']) && !isset($_POST['description'])) {
            error_log("POST categorie : " . print_r($_POST, 1));
            $controller->categorie($_POST['recherche_categorie']);
        }
        if (@$_POST['recherche_statut'] && !isset($_POST['description']) && !isset($_POST['recherche_categorie'])) {
            error_log("POST statut : " . print_r($_POST, 1));
            $controller->statut($_POST['recherche_statut']);
        }
        if (@$_POST["description"] && !isset($_POST['recherche_statut']) && !isset($_POST['recherche_categorie'])) {
            error_log("POST description : " . print_r($_POST, 1));
            $controller->description($_POST["description"]);
            //print_r($_POST["description"]);
        }


        if (isset($_POST) && !isset($_POST['recherche_categorie']) && !isset($_POST['recherche_statut']) && empty($_POST['description'])) {

            $POST = array(); //tableau qui va contenir les données reçues
            parse_str(file_get_contents('php://input'), $POST);
            $controller->ajoutPost($POST);
        }
        break;

    case 'PUT':
        if (isset($_GET['id'])) {
            $url = explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
            $PUT = array(); //tableau qui va contenir les données reçues
            parse_str(file_get_contents('php://input'), $PUT);
            $controller->miseAJourPut($PUT, $url[1]);
        }

        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $url = explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
            $controller->supprimer($url[1]);
            // $sth = $conn->prepare("DELETE FROM produits  WHERE id_product=$url[1]");
            // $sth->execute();
        }
        break;
    case 'DUPLICATE':
        if (isset($_GET['id'])) {
            $url = explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
            $DUPLICATE = array(); //tableau qui va contenir les données reçues
            parse_str(file_get_contents('php://input'), $DUPLICATE);
            $controller->dupliquer($DUPLICATE);
        }
        break;
    default:
        echo 'Je ne connais pas la méthode';
        break;
}
