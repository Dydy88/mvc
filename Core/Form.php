<?php

namespace App\Core;

/** Form Builder =  Générateur de Formulaire  */
class Form
{

    private $formCode = "";

    /**
     * Générer le formulaire HTML
     * @return string 
     */
    public function create()
    {
        return $this->formCode;
        //var_dump($this->formCode);
    }


    /**
     * Valide si tous les champs proposés sont remplis
     * @param array $form Tableau contenant les champs à vérifier (en général issu de $_POST ou $_GET)
     * @param array $fields Tableau listant les champs à vérifier
     * @return bool 
     */
    public static function validate(array $form, array $fields): bool
    {
        // On parcourt chaque champ
        foreach ($fields as $field) {
            // Si le champ est absent ou vide dans le tableau
            if (!isset($form[$field]) || empty($form[$field])) {
                // On sort en retournant false
                return false;
            }
        }
        // Ici le formulaire est "valide"
        return true;
    }



    /**
     * Vérifie si l'email correspond a une address Email
     * @param string $email Utilisateur
     * @return bool 
     */
    public static function validateEmail(string $email): bool
    {
        if (!filter_var(($email), FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }


    /**
     * On vérifie si le champs Password sont identique
     * Retourne True si le mots de passe est identique
     * Retourne False si le mots de passe est diffèrent.
     * @param string $password de l'utilisateur
     * @param string $password2 Confirmation du mot de passe de l'utilisateur
     * @return bool 
     */
    public static function confirmPassword(string $password, string $password2): bool
    {
        if ($password !== $password2) {
            return false;
        }
        return true;
    }


    /**
     * Vérifie si le password contient x Caractères
     * @param string $password Utilisateur
     * @param int    $taille du mot de passe
     * @return bool 
     */
    public static function taillePassword(int $taille, string $password): bool
    {
        if (strlen($password) >= $taille) {
            return false;
        }
        return true;
    }


    /**
     * Ajoute les attributs envoyés à la balise
     * @param array $attributs Tableau associatif ['class' => 'form-control', 'required' => true]
     * @return string Chaine de caractères générée
     */
    public function ajoutAttributs(array $attributs): string
    {
        // On initialise une chaîne de caractères
        $str = '';

        // On liste les attributs "courts"
        $courts = ['checked', 'disabled', 'readonly', 'multiple', 'required', 'autofocus', 'novalidate', 'formnovalidate'];

        // On boucle sur le tableau d'attributs
        foreach ($attributs as $attribut => $valeur) {
            // Si l'attribut est dans la liste des attributs courts
            if (in_array($attribut, $courts) && $valeur == true) {
                $str .= " $attribut";
            } else {
                // On ajoute attribut='valeur'
                $str .= "$attribut=\"$valeur\"";
            }
        }
        return $str;
    }


    /**
     * Balise d'ouverture du formulaire
     * @param string $methode Méthode du formulaire (post ou get)
     * @param string $action Action du formulaire
     * @param array $attributs Attributs
     * @return Form 
     */
    public function debutForm(string $methode = 'post', string $action = '#', array $attributs = []): self
    {
        // On crée la balise form
        $this->formCode .= "<form action='$action' method='$methode'";

        // On ajoute les attributs éventuels        
        if ($attributs) {
            $this->formCode .= $this->ajoutAttributs($attributs) . '>';
        } else {
            $this->formCode .=  '>';
        }
        return $this;
    }


    /**
     * Balise de fermeture du formulaire
     * @return Form 
     */
    public function finForm(): self
    {
        $this->formCode .= '</form>';
        return $this;
    }


    /**
     * Ajout d'un label
     * @param string $for 
     * @param string $texte 
     * @param array $attributs 
     * @return Form 
     */
    public function ajoutLabelFor(string $for, string $texte, array $attributs = []): self
    {
        // On ouvre la balise
        $this->formCode .= "<label for='$for'";

        // On ajoute les attributs
        if ($attributs) {
            $this->formCode .= $this->ajoutAttributs($attributs) . '>';
        }

        // On ajoute le texte
        $this->formCode .= ">$texte</label>";
        return $this;
    }


    /**
     * Ajout d'un champ input
     * @param string $type 
     * @param string $nom 
     * @param array $attributs 
     * @return Form
     */
    public function ajoutInput(string $type, string $nom, array $attributs = []): self
    {
        // On ouvre la balise
        $this->formCode .= "<input type='$type' name='$nom'";

        // On ajoute les attributs
        if ($attributs) {
            $this->formCode .= $this->ajoutAttributs($attributs) . '>';
        } else {
            $this->formCode .=  '>';
        }
        return $this;
    }


    /**
     * Ajoute un champ textarea
     * @param string $nom Nom du champ
     * @param string $valeur Valeur du champ
     * @param array $attributs Attributs
     * @return Form Retourne l'objet
     */
    public function ajoutTextarea(string $nom, string $valeur = '', array $attributs = []): self
    {
        // On ouvre la balise
        $this->formCode .= "<textarea name='$nom'";

        // On ajoute les attributs
        if ($attributs) {
            $this->formCode .= $this->ajoutAttributs($attributs) . '>';
        } else {
            $this->formCode .=  '>';
        }

        // On ajoute le texte
        $this->formCode .= "$valeur</textarea>";
        return $this;
    }


    /**
     * Liste déroulante
     * @param string $nom Nom du champ
     * @param array $options Liste des options (tableau associatif)
     * @param array $attributs 
     * @return Form
     */
    public function ajoutSelect(string $nom, array $options, array $attributs = []): self
    {
        // On crée le select
        $this->formCode .= "<select name='$nom'";

        // On ajoute les attributs
        if ($attributs) {
            $this->formCode .= $this->ajoutAttributs($attributs) . '>';
        } else {
            $this->formCode .=  '>';
        }

        // On ajoute les options
        foreach ($options as $valeur => $texte) {
            $this->formCode .= "<option value=\"$valeur\">$texte</option>";
        }

        // On ferme le select
        $this->formCode .= '</select>';
        return $this;
    }


    /**
     * Ajoute un bouton
     * @param string $texte 
     * @param array $attributs 
     * @return Form
     */
    public function ajoutBouton(string $texte, array $attributs = []): self
    {
        // On ouvre le bouton
        $this->formCode .= '<button ';

        // On ajoute les attributs
        if ($attributs) {
            $this->formCode .= $this->ajoutAttributs($attributs) . '>';
        } else {
            $this->formCode .=  '>';
        }

        // On ajoute le texte et on ferme
        $this->formCode .= "$texte</button>";
        return $this;
    }
}
