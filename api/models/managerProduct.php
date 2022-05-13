<?php
require_once 'database.php';
require_once 'Product.php';


class ManagerProduct extends Database
{
    private $products;
    private const JOIN= 'SELECT *,nom_fichier,chemin,id_assets FROM produits 
        INNER JOIN liaison_categorie ON produits.id_product=liaison_categorie.produits_id 
        INNER JOIN category ON liaison_categorie.category_id=category.id_category 
        INNER JOIN statut ON produits.statut_id=statut.id_statut 
        INNER JOIN liaison_assets ON produits.id_product=liaison_assets.produits_id
        INNER JOIN assets ON liaison_assets.assets_id=assets.id_assets
        ';


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
        $mesProducts = $this->view();
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
        $products = $this->view();
        $sth =  $this->getPdo()->prepare("DELETE FROM liaison_categorie  WHERE produits_id=$id");
        $sth->execute();
        $products = $this->view();
        $sth =  $this->getPdo()->prepare("DELETE FROM liaison_assets  WHERE produits_id=$id");
        $sth->execute();
        $products = $this->view();

        require './views/displayAll.php';
        return 'Produit supprimé';
    }
    // public function displayOne($id)
    // {
    //     $product = $this->viewAssets($id);
    // }


    // public function displayAll()
    // {
    //     $products = $this->viewAssets();
    //     $products[] = $this->viewAssets($products['code']);
    //     require './views/displayAll.php';
    //     //  print_r ($products);
    // }

    public function update($id)
    { //afficher le produit à modifier
        $product = $this->view($id);
        require './views/modification.php';
    }

    public function updateRequest($id_product, $statut_id)
    {
        $sth = $this->getPdo()->prepare("UPDATE produits SET statut_id=$statut_id WHERE id_product =$id_product");
        // error_log(print_r($sth, 1));
        $sth->execute();
        return 'update requête traitée';
    }

    // private function visuel($resultat)

    // {

    //     for ($i = 0; $i < count($resultat); $i++) {

    //         $code = $resultat[$i]['code'];
    //         $sth = $this->getPdo()->prepare("SELECT id_assets, nom_fichier, chemin From assets WHERE nom_fichier LIKE '%$code%'");
    //         $sth->execute();
    //         $result = $sth->fetch(); 
    //         error_log(print_r($result,1));


    //         $resultat[$i]['nom_fichier'] = $result['nom_fichier'];
    //         $resultat[$i]['chemin'] = $result['chemin'];
            
    //     }
    //     //error_log(print_r($resultat, 1));
    //     return $resultat;
    // }



    public function view($id = null)
    {
        if ($id == null) {
            $sth = $this->getPdo()->prepare(
                SELF::JOIN
            );
            $sth->execute();
            // $resultat = $sth->fetchall();
        } else {
            $sth = $this->getPdo()->prepare(
                SELF::JOIN.
                "WHERE id_product=$id"
            );
            $sth->execute();
            // $resultat = $sth->fetchall();
            //echo '<pre>', print_r($resultat), '</pre>';

        }
        $resultat =$sth->fetchAll();
        // for ($i = 0; $i < count($resultat); $i++) {

        //     $code = $resultat[$i]['code'];
        //     $sth = $this->getPdo()->prepare("SELECT nom_fichier, chemin From assets WHERE nom_fichier LIKE '%$code%'");
        //     $sth->execute();
        //     $result = $sth->fetch(); //récupère le category_id de la dernière entrée
        //     $resultat[$i]['nom_fichier'] = $result['nom_fichier'];
        //     $resultat[$i]['chemin'] = $result['chemin'];
        // }
       // error_log(print_r($resultat,1));
        return $resultat;
    }

    

    public function searchStatut($statut)
    {
        $sth = $this->getPdo()->prepare(
            SELF::JOIN.
         "WHERE id_statut=$statut");
        $sth->execute();
        $resultat = $sth->fetchAll();
        return $resultat;
    }

    public function searchCategorie($categorie)
    {
        $sth = $this->getPdo()->prepare(
            SELF::JOIN.
            "WHERE id_category=$categorie"
        );
        $sth->execute();
        $resultat =$sth->fetchAll();

        return $resultat;
    }

    public function searchDescription($description)
    {
        $sth = $this->getPdo()->prepare(
            SELF::JOIN.
            "WHERE MATCH(description) AGAINST ('$description')"
        );
        $sth->execute();
        // $resultat = $sth->fetchall();
        $resultat = $sth->fetchAll();
        //  echo '<pre>', print_r($resultat), '</pre>';

        return $resultat;
    }

    public function updateProductPut($data, $cible)
    {
        $keys = [];
        $values = [];

        foreach ($data as $key => $value) {
            $keys[] = "$key = ?";
            $values[] = $value;
        }
        array_splice($keys, 3, 1);
        array_splice($champs, 3, 1);
        array_splice($values, 3, 1);
        $keys = implode(",", $keys);
        $sth = $this->getPdo()->prepare("UPDATE produits SET $keys WHERE id_product =$cible");

        $sth->execute($values);
        $sth =$this->getPdo()->prepare("SELECT MAX(id_product) FROM produits");
        $sth->execute();
        $result = $sth->fetch(); //récupère le category_id de la dernière entrée
        error_log("dernier id : ".print_r( $result, 1));

        //Rempli la table de liaison categorie

        $produits_id = $result;     //Récupère l'id de la dernière entrée
        $category_id = $_POST['category_id'];

        $sth =$this->getPdo()->prepare("INSERT INTO  liaison_categorie (category_id,produits_id) VALUES (?,?)");

        $sth->execute([$category_id, $produits_id]);

        $sth =$this->getPdo()->prepare("SELECT code From produits WHERE id_product=$produits_id");
        $sth->execute();
        $result = $sth->fetch(); //récupère le category_id de la dernière entrée

        $code = $result['code'];
        //REmpli la table de liason assets
        $sth =$this->getPdo()->prepare("SELECT id_assets From assets WHERE nom_fichier LIKE '%$code%'");
        $sth->execute();
        $result = $sth->fetch(); //récupère le category_id de la dernière entrée

        $id_assets = $result['id_assets'];
        $sth =$this->getPdo()->prepare("INSERT INTO  liaison_assets (produits_id,assets_id,drapeau) VALUES ($produits_id,$id_assets,1)");
        $sth->execute();
    }

    public function updateProductDuplicate($data)
    {
        $keys = [];
        $champs = [];
        $values = [];
        foreach ($data as $key => $value) {

            $keys[] = $key;
            $champs[] = '?';
            $values[] = $value;
        }
        array_splice($keys, 3, 1);
        array_splice($champs, 3, 1);
        array_splice($values, 3, 1);
        $keys = implode(",", $keys);
        $champs = implode(",", $champs);
        $sth = $this->getPdo()->prepare("INSERT INTO produits ($keys) VALUES ($champs) ON DUPLICATE KEY UPDATE id_product =id_product+1");
        //var_dump($sth);
        $sth->execute($values); //Duplique une entrée
        $sth =$this->getPdo()->prepare("SELECT MAX(id_product) FROM produits");
        $sth->execute();
        $result = $sth->fetch(); //récupère le category_id de la dernière entrée

        //Rempli la table de liaison categorie

        $produits_id = implode($result);     //Récupère l'id de la dernière entrée


        $category_id = $data['category_id'];

        //error_log("la dernière entrée : ".print_r($resultat, 1));

        $sth =$this->getPdo()->prepare("INSERT INTO  liaison_categorie (category_id,produits_id) VALUES (?,?)");
        $sth->execute([$category_id, $produits_id]);
        $sth =$this->getPdo()->prepare("SELECT code From produits WHERE id_product=$produits_id");
        $sth->execute();
        $result = $sth->fetch(); //récupère le category_id de la dernière entrée
        $code = rtrim($result['code'],2);
    
        //REmpli la table de liason assets
        $sth =$this->getPdo()->prepare("SELECT id_assets From assets WHERE nom_fichier LIKE '%$code%'");
        $sth->execute();
        $result1 = $sth->fetch(); //récupère le category_id de la dernière entrée
     
         $id_assets =($result1['id_assets']);
         $sth =$this->getPdo()->prepare("INSERT INTO  liaison_assets (produits_id,assets_id,drapeau) VALUES ($produits_id,$id_assets,1)");
         $sth->execute();

    }

    public function insertProduct($data) {

        echo 'je suis ici';
      // error_log('CONTENU DATA : *****************************************'.print_r($data['supplier_id'],1));
$nomFournisseur=$data['supplier_id'];

//print_r('excel :'.$nomFournisseur. "<br>");


$sth =$this->getPdo()->prepare("SELECT nom FROM suppliers WHERE nom='$nomFournisseur' ");
$sth->execute();
$result = $sth->fetch();
$result=implode($result);


//print_r ('bdd :'.$result. "<br>");


 if ($nomFournisseur==$result) {
      echo "il existe déjà<br><br>" ;}
       else {

        $sth =$this->getPdo()->prepare("INSERT INTO  suppliers (nom) VALUES (:nomFournisseur)");
        $sth->bindParam(':nomFournisseur',$nomFournisseur,PDO::PARAM_STR);
        $sth->execute();

      }



       $sth =$this->getPdo()->prepare("SELECT MAX(id_suppliers) FROM suppliers");
        $sth->execute();
       $result = $sth->fetch(); //récupère l'id_suppliers de la dernière entrée

       $id_suppliers= implode($result);
       //print_r($id_suppliers);



       //  $sth->execute($values);
//echo "<prep>",print_r($data), "</pre>";die;



   error_log('CONTENU DATA : *****************************************'.print_r($data));


        //  //error_log(print_r($_POST,1));
         $keys = [];
         $champs = [];
         $values = [];
         //  error_log(print_r($data, 1)); 

         $data['supplier_id']=$id_suppliers;


         foreach ($data as $key => $value) {
             $keys[] = $key;
             $champs[] = '?';
             $values[] = $value;
         }

         array_splice($keys, 3, 1);
         array_splice($champs, 3, 1);
         array_splice($values, 3, 1);
        
   
         // l'index est remplacé en réatribuant un numéro d'index après l'élément supprimé
         array_splice($keys, 7, 1);
         array_splice($champs, 7, 1);
         array_splice($values, 7, 1);
         
         $keys = implode(",", $keys);
         $champs = implode(",", $champs);
       
        //  error_log(print_r($keys,1));
        //  print_r($keys);
        //  print_r($champs);
        //  print_r($values);


         $sth =$this->getPdo()->prepare("INSERT INTO  produits ($keys) VALUES ($champs)");

         $sth->execute($values);

         $sth=$this->getPdo()->lastInsertId();

         error_log(print_r($sth,1));

         $sth =$this->getPdo()->prepare("SELECT MAX(id_product) FROM produits");
         $sth->execute();
         $result = $sth->fetch(); //récupère le category_id de la dernière entrée
         error_log("dernier id : ".print_r( $result, 1));

         //Rempli la table de liaison categorie

         $produits_id = implode($result);     //Récupère l'id de la dernière entrée
     
         // error_log("le dernier id : ".print_r($produits_id, 1));die;

         $category_id = $data['category_id'];

         $sth =$this->getPdo()->prepare("INSERT INTO  liaison_categorie (category_id,produits_id) VALUES (?,?)");

         $sth->execute([$category_id, $produits_id]);

         $sth =$this->getPdo()->prepare("SELECT code From produits WHERE id_product=$produits_id");
         $sth->execute();
         $result = $sth->fetch(); //récupère le category_id de la dernière entrée

         $code = $result['code'];
         //REmpli la table de liason assets
         $sth =$this->getPdo()->prepare("SELECT id_assets From assets WHERE nom_fichier LIKE '%$code%'");
         $sth->execute();
         $result = $sth->fetch(); //récupère le category_id de la dernière entrée

         if($data['nom_fichier']==null) {
            $id_assets =29;  
         } else {

            $id_assets = $result['id_assets'];
         }

         $sth =$this->getPdo()->prepare("INSERT INTO  liaison_assets (produits_id,assets_id,drapeau) VALUES ($produits_id,$id_assets,1)");
         $sth->execute();
    }
}
