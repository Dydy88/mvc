<?php

namespace App\Models;

use App\Core\DB;
use PDOStatement;

/**
 * Créer l'interaction avec la BDD
 * On effectue toute les requetes SQL que l'on va utilisé.
 */

class Model extends DB
{

    protected $table;  // Table de la base de données
    private $db;      //Contient l'instance de la BDD


    /**
     * Méthode qui exécutera les requêtes
     * @param string $sql Requête SQL à exécuter
     * @param array $attributes Attributs à ajouter à la requête 
     * @return PDOStatement|false 
     */
    public function requete(string $query, ?array $params = null): ?PDOStatement
    {
        //On utilise le Singleton pour ne récupèrer qu'une seul Instance de la BDD.
        $this->db = DB::getInstance();

        if ($params) {
            $req = $this->db->pdo->prepare($query);
            $req->execute($params);
        } else {
            $req = $this->db->pdo->query($query);
        }
        //var_dump($req);
        return $req;
    }


    /**
     * Hydratation des données (attention au convention de nommage des Setters)
     * @param array $donnees Tableau associatif des données
     * @return self Retourne l'objet hydraté
     */
    public function hydrate(array|object $donnees)
    {
        foreach ($donnees as $key => $value) {
            // On récupère le nom du setter correspondant à l'attribut.
            $setter = 'set' . ucfirst($key);

            // Si le setter correspondant existe.
            if (method_exists($this, $setter)) {
                // On appelle le setter.
                $this->$setter($value);
            }
        }
        return $this;
    }


    /**
     * Sélection de tous les enregistrements d'une table avec une Requete Query
     * @return array Tableau des enregistrements trouvés
     */
    public function findAll(): ?array
    {
        return $this->requete("SELECT * FROM {$this->table}")->fetchAll();
    }


    /**
     * Sélection d'un enregistrement suivant son id avec une Requete Query
     * @param int $id id de l'enregistrement
     * @return objet contenant l'enregistrement trouvé
     */
    public function find(int $id)
    {
        $query = $this->requete("SELECT * FROM {$this->table} WHERE id = $id");
        return $query->fetch();
    }


    /**
     * Insertion d'un enregistrement suivant un tableau de données avec une requete Preparer
     * @param Model $model Objet à créer
     * @return bool
     */
    public function create()
    {
        $champs  = [];    //Va contenir le noms des champs de la base, nommé dans les propriétés protected de Annonces Model
        $inter   = [];    //Contient les valeurs de la preparation de la requete soit "?"
        $valeurs = [];    //Contient les valeurs dédinit dans les Setters 

        // On boucle pour éclater le tableau
        foreach ($this as $champ => $valeur) {

            // INSERT INTO annonces (titre, description, actif) VALUES (?, ?, ?)
            if ($valeur !== null && $champ != 'db' && $champ != 'table') {
                $champs[] = $champ;
                $inter[] = "?";
                $valeurs[] = $valeur;
            }
        }

        // On transforme le tableau "champs" en une chaine de caractères
        $liste_champs = implode(', ', $champs);
        $liste_inter = implode(', ', $inter);

        //$valeur lui reste un tableau, qui sera executé par la méthode PDO->execute();
        //var_dump($valeurs);

        // On exécute la requête preparer
        //return $this->requete("INSERT INTO  {$this->table}  (titre, descriptions, actif) VALUES (?,?,?)");
        return $this->requete("INSERT INTO {$this->table} ($liste_champs) VALUES($liste_inter)", $valeurs);
    }


    /**
     * Mise à jour d'un enregistrement suivant un tableau de données avec une requete Preparer
     * @param int $id id de l'enregistrement à modifier
     * @param Model $model Objet à modifier
     * @return bool
     */
    public function update(int $id)
    {
        // On boucle pour éclater le tableau
        foreach ($this as $champ => $valeur) {

            // UPDATE annonces SET titre = ?, description = ?, actif = ? WHERE id= ?
            if ($valeur !== null && $champ != 'db' && $champ != 'table') {
                $champs[] = "$champ = ?";
                $valeurs[] = $valeur;
            }
        }

        // On transforme le tableau "champs" en une chaine de caractères
        $liste_champs = implode(', ', $champs);

        //On rajoute l'ID dans le tableau Valeur[] pour savoir quelle ligne modifier en BDD.
        //l'ID est aussi passé en paramètre à l'appel de la méthode.
        $valeurs[] = $id;
        var_dump($valeurs);

        // On exécute la requête
        //UPDATE annonces  SET  titre = ?, descriptions = ?, actif = ?  WHERE id = ?
        return $this->requete("UPDATE {$this->table}  SET  $liste_champs  WHERE id = ?", $valeurs);
    }



    /**
     * Suppression d'un enregistrement avec une requete preparer
     * @param int $id id de l'enregistrement à supprimer
     * @return bool 
     */
    public function delete(int $id)
    {
        //On passe l'ID sous forme de tableau car il est exécuté avec la méthode exécute qui prend un tableau.
        return $this->requete("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }


}
