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
$products=$this->productsManager->view();
//echo "<pre>",print_r($products),"</pre>"; 
    require "./views/displayAll.php";
}
 public function afficherUn($id) {
    $product=$this->productsManager->view($id);
    //echo "<pre>",print_r($product),"</pre>"; 
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
    $products=$this->productsManager->searchCategorie($categorie);
    //echo "<pre>",print_r($products),"</pre>"; 
        require "./views/displayAll.php";

}

public function statut($statut) {
    $products=$this->productsManager->searchStatut($statut);
    //echo "<pre>",print_r($products),"</pre>"; 
        require "./views/displayAll.php";

}

public function description($description) {
    $products=$this->productsManager->searchDescription($description);
    //echo "<pre>",print_r($products),"</pre>"; 
        require "./views/displayAll.php";

}

public function miseAJourPut($data,$cible) {
    $products=$this->productsManager->updateProductPut($data,$cible);
    require "./views/displayAll.php";
}

public function dupliquer($data) {
    $products=$this->productsManager->updateProductDuplicate($data);
    require "./views/displayAll.php";

}

public function ajoutPost($data) {
foreach ($data as $enregistrements) {
    error_log("Controleur ajoutPost ".print_r($enregistrements,1));
    $products=$this->productsManager->insertProduct($enregistrements);

}
    require "./views/displayAll.php";
}

}