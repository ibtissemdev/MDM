<?php
//require_once "database.php";



class Product extends Database {
protected $id_product;
protected $code;
protected $description;
protected $price;
protected $category_id;
protected $statut_id;
protected $purchase_date;
protected $expiration_date;
protected $nom_fournisseur;
protected $photo;


// public function __construct($data)
// {//Crée un objet product
//     extract($data); 
//     $this->id_product=$id_product;
//     $this->code = $code;
//     $this->description= $description;
//     $this->price=$price;
//     $this->category_id=$category_id;
//     $this->statut_id=$statut_id;
//     $this->purchase_date=$purchase_date;
//     $this->expiration_date=$expiration_date;
//     $this->photo='assets/'.$code.'-01.jpg';}

public function getId_product() { return $this->id_product;}
public function setId_product($id_product) { $this->id_product = $id_product; return $this;}

public function getCode() { return $this->code;}
public function setCode($code) { $this->code = $code; return $this;}

public function getDescription() { return $this->description;}
public function setDescription($description) { $this->description = $description; return $this;}

public function getPrice() { return $this->price;}
public function setPrice($price) { $this->price = $price; return $this;}

public function getCategory_id() { return $this->category_id;}
public function setCategory_id($Category_id) { $this->Category_id = $Category_id; return $this;}

public function getStatut_id() { return $this->statut_id;}
public function setStatut_id($statut_id) { $this->statut_id = $statut_id; return $this;}

public function getPurchase_date() { return $this->purchase_date;}
public function setPurchase_date($purchase_date) {$this->purchase_date = $purchase_date; return $this;}

public function getExpiration_date() { return $this->expiration_date;}
public function setExpiration_date($expiration_date) { $this->expiration_date = $expiration_date; return $this;}

public function getPhoto() { return $this->photo;}
public function setPhoto($photo) {
    $this->photo = 'assets/'.$this->code.'-01.jpg'; 
    return $this->photo=$photo;}

/**
 * Get the value of nom_fournisseur
 */ 
public function getNom_fournisseur()
{
return $this->nom_fournisseur;
}

/**
 * Set the value of nom_fournisseur
 *
 * @return  self
 */ 
public function setNom_fournisseur($nom_fournisseur)
{
$this->nom_fournisseur = $nom_fournisseur;

return $this;
}
}