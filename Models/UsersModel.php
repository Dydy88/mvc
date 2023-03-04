<?php

namespace App\Models;

class usersModel extends Model
{

    protected $id;
    protected $email;
    protected $password;
    protected $roles;
    

    public function __construct()
    {
        $this->table = 'users';
    }


    /**
     * Récupérer un user à partir de son e-mail (Surcharger la Méthode Model.php)
     * @param string $email 
     * @return mixed 
     */ 
    public function findOneByEmail(string $email)
    {
        //{$this->table} Les accolades ne sont plus obligatoire depuis PHP 7.1
        return $this->requete("SELECT * FROM $this->table WHERE email = ?", [$email])->fetch();
    }

    /**
     * Crée la session de l'utilisateur
     * Ils peut etre recommandé de ne pas enregistrer le mot de passe dans la Session
     * @return void 
     */
    public function setSession()
    {
        $_SESSION['user'] = [
            'id' => $this->id,
            'email' => $this->email,
            'roles' => $this->roles
        ];
    }

    /*
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }


    /**
     * Get the value of roles
     */
    public function getRoles():array
    {
        return $this->roles;
        //On mets le roles user par défault, on retourne un tableau unique.
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /**
     * Set the value of roles
     * @return  self
     */
    public function setRoles($roles)
    {
        //On décode le JSON de la Base de Donnée.
        $this->roles = json_decode($roles);
        return $this;
    }
}
