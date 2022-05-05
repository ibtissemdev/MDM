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
 
        <tr>
            <td><img src="<?=CHEMIN.$product[0]['chemin'].$product[0]['nom_fichier']?>" alt="photo du produit"></td>
            <td><?= $product[0]['description'] ?></td>
            <td><?= $product[0]['price'] ?></td>
            <td><?= $product[0]['purchase_date'] ?></td>
            <td><?= $product[0]['category_id'] ?></td>
            <td><?= $product[0]['statut_id'] ?></td>
            <td><a href="<?=CHEMIN?>products/"><button>retour</button></a>
                <a href="<?=CHEMIN?>delete/<?= $product[0]['id_product'] ?>"><button>Suppression</button></a>
            </td>
        </tr>

</table>
<?php
$content = ob_get_clean();
$title="afficher". $product[0]['description'];
require 'layout.php';
?>