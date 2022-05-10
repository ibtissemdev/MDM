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
            <td><?= $product[0]['expiration_date'] ?></td>
            <td><?= $product[0]['nom'] ?></td>
            <td><form action="">
<select name="statut_id" id="" onchange="request(this.value,<?= $product[0]['id_product'] ?>)">
<option value="1" <?=($product[0]['statut_id']==1)? "selected": ""?>>En stock</option>
<option value="2" <?=($product[0]['statut_id']==2)? "selected": ""?>>En cours d’approvisionnement</option>
<option value="3" <?=($product[0]['statut_id']==3)? "selected": ""?>>Epuisé</option>
<option value="4" <?=($product[0]['statut_id']==4)? "selected": ""?>>Retiré des rayond</option>
</select>
            </form></td>
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