<?php
// require_once "../models/Product.php";
require_once "./models/managerProduct.php";


class ControllerProduct {
private $productsManager;
//On instancie le controller qui gère des manageurs
public function __construct()
{
    $this->productsManager= new ManagerProduct();
    $this->productsManager->chargementProducts();
}

public function supprimer($id){
echo $this->productsManager->delete($id);
   // require "./views/displayAll.php";
}

public function afficherTout() {
$products=$this->productsManager->viewAssets();
//echo "<pre>",print_r($products),"</pre>"; 
    require "./views/displayAll.php";
}
 public function afficherUn($id) {
    $product=$this->productsManager->viewAssets($id);
    echo "<pre>",print_r($product),"</pre>"; 
    require './views/displayOne.php';
    
 }

public function miseAJour($id){//afficher le produit à modifier
    $this->productsManager->update($id);
}

public function miseAJourRequete ($id_product,$id_statut) { //On modifie le statut du produit
  return  $this->productsManager->updateRequest($id_product,$id_statut);
}

public function getProductsManager() { return $this->productsManager;}
public function setProductsManager($productsManager) { $this->productsManager = $productsManager; return $this;
}

public function categorie($categorie) {
    $products=$this->productsManager->rechercheCategorie($categorie);
    //echo "<pre>",print_r($products),"</pre>"; 
        require "./views/displayAll.php";

}

public function statut($statut) {
    $products=$this->productsManager->rechercheStatut($statut);
    //echo "<pre>",print_r($products),"</pre>"; 
        require "./views/displayAll.php";

}


}