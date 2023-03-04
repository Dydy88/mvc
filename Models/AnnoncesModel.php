<?php

namespace App\Models;

use App\Models\Model;

/** 
 * Modèle pour la table "annonces"
 * On définit une liste de Getter et de Setter de chaque propriété en protected
 * Celà nous permet d'y accèder depuis l'extérieur de la Class.
 * 
 * Return this sur les  setters va nous permettre de créer mes informations
 * directement depuis l'inscance de notre Model.
 */
class AnnoncesModel extends Model
{
    protected $id;
    protected $titre;
    protected $descriptions;
    protected $created_at;
    protected $actif;
    protected $users_id;



    public function __construct()
    {
        $this->table = 'annonces';
    }


    /** Obtenir la valeur de id*/
    public function getId(): int
    {
        return $this->id;
    }

    /** Définir la valeur de id*/
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**Obtenir la valeur de titre*/
    public function getTitre(): string
    {
        return $this->titre;
    }

    /** Définir la valeur de titre */
    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    /** Obtenir la valeur de description*/
    public function getDescription(): string
    {
        return $this->descriptions;
    }

    /** Définir la valeur de description */
    public function setDescription(string $description): self
    {
        $this->descriptions = $description;
        return $this;
    }

    /** Obtenir la valeur de created_at */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /** Définir la valeur de created_at */
    public function setCreatedAt($created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    /** Obtenir la valeur de actif */
    public function getActif(): int
    {
        return $this->actif;
    }

    /** Définir la valeur de actif */
    public function setActif(int $actif): self
    {
        $this->actif = $actif;
        return $this;
    }

    /** Obtenir la valeur de l'ID Users */
    public function getUsers_id(): int
    {
        return $this->users_id;
    }

    /** Définir la valeur de l'ID Users */
    public function setUsers_id(int $users_id): self
    {
        $this->users_id = $users_id;

        return $this;
    }

}
