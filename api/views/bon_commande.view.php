<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pdf</title>
    <style>
        table {
           width: 100%; 
        }
      
    </style>
</head>

<body>
    <h1>Liste des contacts</h1>
    <table>
        <thead>
            <th>Fournisseur</th>
            <th>Code produit</th>
            <th>Prix unitaire</th>
            <th>Adresse</th>
        </thead>
        <tbody>
            <?php foreach ($liste_a_commander as $product) : ?>
                <tr>
                    <td><?= "Fournisseur : ".$product[6] ?></td>
                    <td><?= "Code produit : ".$product[1] ?></td>
                    <td><?= "Prix unitaire : ".$product[3] ?></td>
                    <td><?= "Adresse : "?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</body>

</html>