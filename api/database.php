<?php

class Database {
 // Connexion à la base de données
 private $host = "localhost";
 private $db_name = "micromarket";
 private $username = "root";
 private $password = "";
 protected $pdo;

 public function getPdo()
 {
         $this->pdo = new PDO("mysql:host=" . $this->host.";dbname=".$this->db_name, $this->username, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        echo "connexion réussie";
return $this->pdo;
 } 
    
}