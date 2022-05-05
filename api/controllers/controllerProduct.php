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

    $this->productsManager->displayAll();
   // require "./views/displayAll.php";
}
 public function afficherUn($id) {
    $this->productsManager->displayOne($id);
 }

public function miseAJour($id){

    $this->productsManager->update($id);
}


}