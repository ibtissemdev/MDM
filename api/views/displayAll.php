
<?php
//tampon

ob_start(); ?>
<!--mes éléments à afficher-->

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
            <td><?= $product['purchase_date'] ?></td>
            <td><?= $product['category_id'] ?></td>
            <td><?= $product['statut_id'] ?></td>
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