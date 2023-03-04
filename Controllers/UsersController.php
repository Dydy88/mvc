<?php

namespace App\Controllers;

use App\Core\Form;
use App\Core\Session;
use App\Models\usersModel;

class UsersController extends Controller
{

  /**
   * Connexion des utilisateurs
   * @return void 
   */
  public function login()
  {
    //On instancie la Session.

    // On vérifie si le formulaire est complet
    if (Form::validate($_POST, ['email', 'password'])) {


      //On vérifie si le format d'email est correct.
      if (!Form::validateEmail($_POST['email'])) {

        $_SESSION['erreur'] = 'L\'adresse e-mail possède un format incorrect';
        header('Location: /users/login');
        exit;
      }

      // On va chercher dans la base de données l'utilisateur avec l'email entré
      $usersModel = new UsersModel;
      $userArray = $usersModel->findOneByEmail(htmlentities($_POST['email']));

      // Si l'utilisateur n'existe pas (Addresse Mail Incorrect)
      if (!$userArray) {
        // On envoie un message de session
        $_SESSION['erreur'] = 'L\'adresse e-mail et/ou le mot de passe est incorrect';
        header('Location: /users/login');
        exit;
      }

      // L'utilisateur existe on vérifie son Mot de Passe.
      $user = $usersModel->hydrate($userArray);
      if (password_verify(htmlentities($_POST['password']), $usersModel->getPassword())) {

        //On connecte l'utilisateur.
        $usersModel->setSession();
        header('Location: /');
        exit;

      } else {
        // Mauvais mot de passe
        $_SESSION['erreur'] = 'L\'adresse e-mail et/ou le mot de passe est incorrect';
        header('Location: /users/login');
        exit;
      }
    }

    //On construit notre formulaire
    $form = new Form;
    $form->debutForm()
      ->ajoutLabelFor('email', 'Email')
      ->ajoutInput('email', 'email', ['id' => 'email', 'class' => 'form-control'])
      ->ajoutLabelFor('password', 'Mot de passe')
      ->ajoutInput('password', 'password', ['id' => 'password', 'class' => 'form-control'])
      ->ajoutBouton('Me connecter', ['class' => 'btn btn-primary'])
      ->finForm();
    //->ajoutSelect('voiture', ['Peugeot' => 'Peugeot'], ['Citroen' => 'Citroen'], ['SEAT' => 'SEAT'])

    $this->render('login', ['loginFormConnect' => $form->create()]);
  }


  /**
   * Inscription des utilisateurs
   * @return void 
   */
  public function register()
  {
    //On vérifie si le formulaire est valide
    if (Form::validate($_POST, ['email', 'password', 'password2'])) {

      //On vérifie si les mots de passe sont identique
      $test = Form::confirmPassword($_POST['password'], $_POST['password2']);
      if ($test == false) {
        $_SESSION['erreur'] = 'Les mots de passe ne correspondant pas';
        header('Location: /users/register');
        exit;
      }

      //On vérifie si le mot de passe contient bien 5 carctère
      if (Form::taillePassword(5, $_POST['password'])) {
        $_SESSION['erreur'] = 'Le Mots de Passe doit faire au minimun 5 caractères.';
        header('Location: /users/register');
        exit;
      }

      //On sécurise le formulaire
      $email = htmlentities($_POST['email']);
      $password = htmlentities(password_hash($_POST['password'], PASSWORD_BCRYPT));

      //On hydrate les informations du formuliare
      $user = new usersModel;
      $user->setEmail($email)
        ->setPassword($password);

      //On stocke l'utilisateur en BDD
      $user->create();
      //var_dump($user);
    }

    $form = new Form;
    $form->debutForm()
      ->ajoutLabelFor('email', 'E-mail :')
      ->ajoutInput('email', 'email', ['id' => 'email', 'class' => 'form-control'])
      ->ajoutLabelFor('pass', 'Mot de passe :')
      ->ajoutInput('password', 'password', ['id' => 'pass', 'class' => 'form-control'])
      ->ajoutLabelFor('pass', 'Confirmation du Mot de passe :')
      ->ajoutInput('password', 'password2', ['id' => 'pass', 'class' => 'form-control'])
      ->ajoutBouton('M\'inscrire', ['class' => 'btn btn-primary'])
      ->finForm();

    $this->render('register', ['registerForm' => $form->create()]);
  }


  /**
   * Déconnexion de l'utilisateur
   * @return exit 
   */
  public function logout()
  {
    unset($_SESSION['user']);
    header('Location: /');
    //header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
}
