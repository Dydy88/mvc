<?php

namespace App\Controllers;
use App\Core\Form;
use App\Models\AnnoncesModel;

class AnnoncesController extends Controller
{
    /**
     * Cette méthode affichera une page listant toutes les annonces de la base de données
     * @return void 
     */
    public function index()
    {
        //On instancie le modele correspondant à la table annonce
        $annoncesModel = new AnnoncesModel;
        $annonces = $annoncesModel->findAll();
        
        //On genère la vue avec la fonction Render (fichier, donnes associés)
        $this->render('annonces',  ['annonces' => $annonces]);
        //$this->render('annonces',  compact('annonces'));
    }

    /**
     * Méthode permettant d'afficher un article à partir de son slug
     * @param int $id
     * @return void
     */
    public function lire(int $id)
    {
        // On instancie le modèle et on récupère un article particulier
        $model = new AnnoncesModel;
        $annonce = $model->find($id);

        // On envoie les données à la vue
        $this->render('annonce', ['annonce' => $annonce]);
    }

    /**
     * Ajouter une annonce
     * @return void 
     */
    public function ajouter()
    {
        // On vérifie si la session contient les informations d'un utilisateur
        if (isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) {

            // On vérifie si le formulaire est complet
            if (Form::validate($_POST, ['titre', 'description'])) {

                // On instancie notre modèle
                $annonce = new AnnoncesModel;

                // On hydrate
                $annonce->setTitre(htmlentities($_POST['titre']))
                    ->setDescription(htmlentities($_POST['description']))
                    ->setUsers_id($_SESSION['user']['id']);

                // On enregistre
                $annonce->create();

                // On redirige
                $_SESSION['message'] = "Votre annonce a été enregistrée avec succès";
                header('Location: /');
                exit;
            } else {

                //Permet de conserver les valeurs preéalablement remplie dans le formualire
                if (isset($_POST['titre'])) {
                    $titre = htmlentities($_POST['titre']);
                } else {
                    $titre = '';
                }
                //Version Terniaire.
                $description = isset($_POST['description']) ? htmlentities($_POST['description']) : '';

                // Le formulaire est incomplet
                if(!empty($_POST)){
                    $_SESSION['erreur'] = "Le formulaire est incomplet";
                }
            }

            //On créer le Formulaire d'Ajout
            $form = new Form;
            $form->debutForm()
                ->ajoutLabelFor('titre', 'Titre de l\'annonce :')
                ->ajoutInput('text', 'titre', [
                    'class' => 'form-control',
                    'value' => $titre
                ])
                ->ajoutLabelFor('description', 'Description')
                ->ajoutTextarea('description', $description, [
                    'id' => 'description',
                    'class' => 'form-control',
                    'rows' => '8'
                ])
                ->ajoutBouton('Valider', ['class' => 'btn btn-primary']);
            $this->render('ajouter', ['form' => $form->create()]);
        } else {
            $_SESSION['erreur'] = "Vous devez vous connecter pour ajouter une annonce";
            header('Location: /user/login');
            exit;
        }
    }


    /**
     * Modifier une annonce
     * @param int $id 
     * @return void 
     */
    public function modifier(int $id)
    {

        if (isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) {

            // On instancie notre modèle
            $annoncesModel = new AnnoncesModel;

            // On cherche l'annonce avec l'id $id
            $annonce = $annoncesModel->find($id);

            // Si l'annonce n'existe pas, on retourne à la liste des annonces
            if (!$annonce) {
                http_response_code(404);
                $_SESSION['erreur'] = "L'annonce recherchée n'existe pas";
                header('Location: /annonces');
                exit;
            }
    
            // On vérifie si l'utilisateur est propriétaire de l'annonce ou admin
            if ($annonce->users_id !== $_SESSION['user']['id'])

              if(!in_array('ROLE_ADMIN', $_SESSION['user']['roles'])) {
                $_SESSION['erreur'] = "Vous n'avez pas accès à cette page";
                header('Location: /annonces');
                exit;
            }

            // On traite le formulaire
            if (Form::validate($_POST, ['titre', 'description'])) {

                // On stocke l'annonce
                $annonceModif = new AnnoncesModel;

                // On hydrate
                $annonceModif->setId($annonce->id)
                    ->setTitre(htmlentities($_POST['titre']))
                    ->setDescription(htmlentities($_POST['description']));

                // On met à jour l'annonce
                $annonceModif->update($annonce->id);
                // On redirige
                $_SESSION['message'] = "Votre annonce a été modifiée avec succès";
                header('Location: /admin/annonces');
                exit;
            }

            $form = new Form;
            $form->debutForm()
                ->ajoutLabelFor('titre', 'Titre de l\'annonce :')
                ->ajoutInput('text', 'titre', [
                    'id' => 'titre',
                    'class' => 'form-control',
                    'value' => $annonce->titre
                ])
                ->ajoutLabelFor('description', 'Texte de l\'annonce')
                ->ajoutTextarea('description', $annonce->descriptions, [
                    'id' => 'description',
                    'class' => 'form-control',
                    'rows' => '10'

                ])
                ->ajoutBouton('Modifier', ['class' => 'btn btn-primary'])
                ->finForm();

            // On envoie à la vue
            $this->render('modifier', ['formUpdate' => $form->create()]);
        } else {
            // L'utilisateur n'est pas connecté
            $_SESSION['erreur'] = "Vous devez être connecté(e) pour accéder à cette page";
            header('Location: /user/login');
            exit;
        }
    }
}
