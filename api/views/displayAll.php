
<?php
//tampon

ob_start(); ?>
<!--mes éléments à afficher-->

<div class="recherche_statut">
<form action="" method="post">
  <label for="recherche_statut">Recherche</label>
  <select name="recherche_statut" id="recherche_statut">
  <option value= "" disabled selected hidden>Statut</option>
    <option value="1">En cours d’approvisionnement</option>
    <option value="2">En stock</option>
    <option value="3">Epuisé</option>
    <option value="4">Retiré des rayons</option>
  </select>
  <button class="boutton" type="search">Envoyer</button>
</form>
</div>

<div class="recherche_categorie">
<form action="" method="post">
  <label for="recherche_categorie">Catégorie</label>
  <select name="recherche_categorie" id="recherche_categorie">
  <option value= "" disabled selected hidden>Catégorie</option>
    <option value="1">Boulangerie/Pâtisseri</option>
    <option value="2">Epicerie salée</option>
    <option value="3">Epicerie sucrée</option>
    <option value="4">Boissons</option>
    <option value="5">Fromagerie</option>
    <option value="6">Poissonnerie</option>
    <option value="7">Boucherie</option>
    <option value="8">Libre-service</option>
    <option value="9">Vente à l’étalage</option>
    <option value="10">Tête de gondole</option>
  </select>
  <button class="boutton" type="search">Envoyer</button>
</form>
</div>

<div>
<form action="recherche.php" method="post">
  <label for="description">Recherche</label>
<input type="search" name="description" id="description">
<button type='search' >Envoyer</button>

</form>
</div>
<table>
    <tr>
        <th>Visuel principal</th>
        <th>Description</th>
        <th>Prix en €</th>
        <th>Date de péremption</th>
        <th>Catégorie</th>
        <th>Statut</th>
        <th>Actions</th>
    </tr>
    <?php

//echo "<pre>",print_r(count($products)),"</pre>";

    foreach($products as $product) : ?>
   <?php      

           // echo "<pre>",print_r($produits),"</pre>"; 
   ?>
        <tr>
        <td><img src="<?=CHEMIN.$product['chemin'].$product['nom_fichier']?>" alt="<?= $product['description'] ?>"> </td>
        <?php error_log(print_r($product,1));
      
        ?>
            <td><?= $product['description'] ?></td>
            <td><?= $product['price'] ?></td>
            <td><?= $product['expiration_date'] ?></td>
            <td><?= $product['nom'] ?></td>
            <td><?= $product['nom_statut'] ?></td>
            <td><a href="<?=CHEMIN?>update/<?= $product['id_product'] ?>"><button>Modification</button></a>
                <a href="<?=CHEMIN?>delete/<?= $product['id_product'] ?>"><button>Suppression</button></a>
                <a href="<?=CHEMIN?>display/<?= $product['id_product'] ?>"><button>Afficher</button></a>
            </td>
        </tr>
    <?php endforeach ?>
</table>




<?php
$content = ob_get_clean();
$title="afficher tous les produits";
require 'layout.php';
?>