<?php
require_once 'database.php';
require_once 'Product.php';

class ManagerProduct extends Database
{
    private $products;

    public function ajoutProduct($product)
    {
        $this->products[] = $product;
    }

    public function getProduct()
    {
        return $this->products;
    }
    public function chargementProducts()
    {
        $mesProducts = $this->viewAssets();
        //var_dump($mesProducts);
        foreach ($mesProducts as $product) {
            $p = new Product($product);
            $this->ajoutProduct($p);
            //  echo "<pre>",print_r($p),"</pre>";     
        }
    }
   
    //Methode DELETE
    public function delete($id)
    {
        $sth =  $this->getPdo()->prepare("DELETE FROM produits  WHERE id_product=$id");
        $sth->execute();
        $products = $this->viewAssets();
        require './views/displayAll.php';
        return 'Produit supprimé';
    }
    public function displayOne($id)
    {
        $product = $this->viewAssets($id);
  
    }


    public function displayAll()
    {
        $products = $this->viewAssets();
        $products[] = $this->viewAssets($products['code']);
        require './views/displayAll.php';
        //  print_r ($products);
    }

    public function update($id)
    { //afficher le produit à modifier
        $product = $this->viewAssets($id);
        require './views/modification.php';
    }

    public function updateRequest($id_product, $statut_id)
    {
        $sth = $this->getPdo()->prepare("UPDATE produits SET statut_id=$statut_id WHERE id_product =$id_product");
        error_log(print_r($sth, 1));
        $sth->execute();
        return 'update requête traitée';
    }

    public function viewAssets($id = null)
    {
        if ($id == null) {
            $sth = $this->getPdo()->prepare("SELECT * From produits");
            $sth->execute();
            $resultat = $sth->fetchall();
        } else {
            $sth = $this->getPdo()->prepare("SELECT * From produits WHERE id_product=$id");
            $sth->execute();
            $resultat = $sth->fetchall();
            //echo '<pre>', print_r($resultat), '</pre>';

        }
            for ($i = 0; $i < count($resultat); $i++) {

                $code = $resultat[$i]['code'];
                $sth = $this->getPdo()->prepare("SELECT nom_fichier, chemin From assets WHERE nom_fichier LIKE '%$code%'");
                $sth->execute();
                $result = $sth->fetch(); //récupère le category_id de la dernière entrée
                $resultat[$i]['nom_fichier'] = $result['nom_fichier'];
                $resultat[$i]['chemin'] = $result['chemin'];
            }
       // echo '<pre>', print_r($resultat), '</pre>';
        return $resultat;
    }

   }
