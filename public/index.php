<?php

// On définit une constante contenant le dossier racine des Vues Utilisateurs
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR);

// On importe les namespaces nécessaires
use App\Autoloader;
use App\Core\Routeur;

// On importe l'Autoloader
require_once '../autoloader.php';
Autoloader::register();


//On instancie le Routeur
$app = new Routeur();

//On démarre l'application
$app->start();

