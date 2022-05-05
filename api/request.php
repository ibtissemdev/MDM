<?php

print_r($_GET);
$data= explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
print_r($data);


$answerPhp=$controller->miseAJourRequete($data[1],$data[2]);//On effectue la requête et on récupère la réponse dans la variable
echo json_encode($answerPhp);
