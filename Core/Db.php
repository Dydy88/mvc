<?php

namespace App\Core;

class DB
{

    private $host = "127.0.0.1";
    private $database_name = "mvc";
    private $login = "root";
    private $root  = "root";
    
    protected  $pdo;
    protected static $instance;


    /**Fonction constructrice de connexion a la Base de Donnée */
    private function __construct()
    {

        try {
            $this->pdo = new \PDO('mysql:host=' . $this->host . ';dbname=' . $this->database_name, $this->login, $this->root);
            $this->pdo->exec('SET NAMES utf8');
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
            
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    /** Création d'un Singleton pour ne possèder qu'une Instance de DB */
    //:self indiqué que la méthode renvoie une instance de l'objet lui même
    public static function getInstance(): DB
    {
        if (is_null(self::$instance)) {
            self::$instance = new DB();
        }
        return self::$instance;
    }
}
