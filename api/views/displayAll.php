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
   
        <tr>
            <td><img src="<?= $product['description'] ?>" alt="photo du produit"></td>
            <td><?= $product['description'] ?></td>
            <td><?= $product['price'] ?></td>
            <td><?= $product['purchase_date'] ?></td>
            <td><?= $product['category_id'] ?></td>
            <td><?= $product['statut_id'] ?></td>
            <td><a href="http://localhost/MDM/api/update/<?= $product['id_product'] ?>"><button>Modification</button></a>
                <a href="http://localhost/MDM/api/delete/<?= $product['id_product'] ?>"><button>Suppression</button></a>
                <a href="http://localhost/MDM/api/display/<?= $product['id_product'] ?>"><button>Afficher</button></a>
            </td>
        </tr>
    <?php endforeach ?>
</table>
<?php
$content = ob_get_clean();
$title="afficher tous les produits";
require 'layout.php';
?>