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
            <td><form action="">
<select name="statut_id" id="" onchange="request($this.value,<?= $product['id_product'] ?>)">
<option value="1" <?=($product['statut_id']==1)? "selected": ""?>>En stock</option>
<option value="2" <?=($product['statut_id']==2)? "selected": ""?>>En cours d’approvisionnement</option>
<option value="3" <?=($product['statut_id']==3)? "selected": ""?>>Epuisé</option>
<option value="4" <?=($product['statut_id']==4)? "selected": ""?>>Retiré des rayond</option>
</select>
            </form></td>
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