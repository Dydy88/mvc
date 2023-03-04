<?php

namespace App\Controllers;

/** Controlleur Principale */
abstract class Controller
{
    //protected $template = 'template';

    /**
     * Afficher une vue
     * @param string $fichier
     * @param array $data
     * @return void
     */
    public function render(string $fichier, array $donnees = [], string $template = 'template')
    {
        // On démarre le buffer de sortie
        ob_start();

        //On extrait le contenu des données 
        extract($donnees);

        //On rends la vues associés.
        require_once ROOT . "$fichier.php";

        // On stocke le contenu dans $content
        $content = ob_get_clean();

        // On fabrique le "template"
        require_once ROOT . "$template.php";
    }
}
