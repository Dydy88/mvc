<?php

namespace App\Controllers;

use App\Models\AnnoncesModel;

class AdminController extends Controller
{


    public function index()
    {

        if ($this->isAdmin()) {
            $this->render('admin/index');
        }
    }


    /***
     * Vérifie si un Utilisateur possède les droits Admin
     */
    private function isAdmin()
    {

        //On vérifier si le role est de l'utilisateur est Admin
        if (isset($_SESSION['user']) && in_array('ROLE_ADMIN', $_SESSION['user']['roles'])) {
            // On est admin
            return true;
        } else {
            // On n'est pas admin
            $_SESSION['erreur'] = "Vous n'avez pas accès à cette zone";
            header('Location: /');
            exit;
        }
    }


    /**
     * Affiche la liste des annonces sous forme de tableau
     * @return void 
     */
    public function annonces()
    {
        if ($this->isAdmin()) {

            $annoncesModel = new AnnoncesModel;
            $annonces = $annoncesModel->findAll();
            $this->render('admin/annonces', ['adminAnnonces' => $annonces]);
        }
    }


    /**
     * Supprime une annonce si on est admin
     * @param int $id 
     * @return void 
     * Delete si un ID qui n'exitse pas, aucun message d'erreur, 0 ligne seront retourné.
     */
    public function supprimeAnnonce(int $id)
    {
        if ($this->isAdmin()) {
            $annonce = new AnnoncesModel;
            $annonce->delete($id);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }


    /**
     * Active ou Désactive une Annonce
     * @param int $id 
     * @return void 
     */
    public function activeAnnonce(int $id)
    {

        if ($this->isAdmin()) {
            $annoncesModel = new AnnoncesModel;
            $annoncesArray = $annoncesModel->find($id);

            //On vérifie si un Objet a bien été reàu
            if ($annoncesArray) {
                // On hydrate
                if ($annoncesArray->actif === 0) {
                    $annoncesModel->setActif(1);
                } else {
                    $annoncesModel->setActif(0);
                }
                $annoncesModel->update($annoncesArray->id);
            }
        }
    }
}
