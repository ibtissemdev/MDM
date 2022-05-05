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
            <td><img src="<?= $product['description'] ?>" alt="photo du produit"></td>
            <td><?= $product['description'] ?></td>
            <td><?= $product['price'] ?></td>
            <td><?= $product['purchase_date'] ?></td>
            <td><?= $product['category_id'] ?></td>
            <td><?= $product['statut_id'] ?></td>
            <td><a href=""><button>Modification</button></a>
                <a href="http://localhost/MDM/api/delete/<?= $product['id_product'] ?>"><button>Suppression</button></a>
            </td>
        </tr>

</table>
<?php
$content = ob_get_clean();
$title="afficher". $product['description'];
require 'layout.php';
?>