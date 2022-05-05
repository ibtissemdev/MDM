<?php

print_r($_GET);
$data= explode('/', filter_var($_GET['id']), FILTER_SANITIZE_URL);
print_r($data);