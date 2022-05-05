<?php
require_once 'database.php';
require_once 'Product.php';

class ManagerProduct extends Database
{
    private $products;

    public function ajoutProduct($product){
        $this->products[]= $product;
    }

    public function getProduct(){
        return $this->products;
    }
public function chargementProducts(){
    $mesProducts=$this->selectAll();
    //var_dump($mesProducts);
    foreach ($mesProducts as $product) {
        $p=new Product($product);
        $this->ajoutProduct($p);
      //  echo "<pre>",print_r($p),"</pre>";     
    }
}
    //Method GET
    public function selectAll()
    { //echo "<pre>",print_r($this->products),"</pre>";  
        $sth = $this->getPdo()->prepare("SELECT * From produits ");
        $sth->execute();
        $resultat = $sth->fetchAll(); //Afficher toutes les entrées (un tableau) dans le tableau
        return $resultat;
    }

    public function selectById($id)
    {
        $sth = $this->getPdo()->prepare("SELECT * From produits WHERE id_product=$id");
        $sth->execute();
        $resultat = $sth->fetch();
        return $resultat;
    }
    //Methode DELETE
    public function delete($id)
    {
        $sth =  $this->getPdo()->prepare("DELETE FROM produits  WHERE id_product=$id");
        $sth->execute();
        $products=$this->selectAll();
        require './views/displayAll.php';
        return 'Produit supprimé';
    }
public function displayOne($id){
    $product=$this->selectById($id);
        require './views/displayOne.php';
}


    public function displayAll()
    {
$products=$this->selectAll();
        require './views/displayAll.php';
     //  print_r ($products);
    }

    public function update ($id) { //afficher le produit à modifier
        $product=$this->selectById($id);
        require './views/modification.php';

    }

    public function updateRequest($id_product,$statut_id) {
        $sth = $this->getPdo()->prepare("UPDATE produits SET statut_id=$statut_id WHERE id_product =$id_product");
        error_log(print_r($sth, 1));
        $sth->execute();
        return 'update requête traitée';

    }

}
