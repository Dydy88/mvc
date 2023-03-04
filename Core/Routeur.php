<?php

namespace App\Core;

use App\Controllers\AcceuilController;

/** Routeur Principal */
class Routeur
{
    public function start()
    {
        //On vérifie si la session existe, sinon on démarre une session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        //Recupère l'URL saisie et on retire le dernier slash de l'URL (trailing slash)
        $uri = $_SERVER['REQUEST_URI'];

        //On vérifie que uri n'est pas vide, et on récpère le dernier index de $uri.
        if (!empty($uri) && $uri != '/'  && $uri[-1] === '/') {

            //On enlève le dernier slash
            $uri = substr($uri, 0, -1);

            //On evnoie un code redirection permanente, et on reidirge sans le slash
            http_response_code(301);
            header('Location:' . $uri);
            exit();
        }

        /** On gère les diffèrents paramètres de l'URL */
        $params = explode('/', $_GET['p']);
        var_dump($params);


        if ($params[0] != "") {

            //On recupère le nom du controlleur a instancier auquel on ajoute son namespace
            //array_shift: Enleve la 1er valeur  du tableau. Cette caleur sera placé dans $controller.
            $controller = '\\App\\Controllers\\' . ucfirst(array_shift($params)) . 'Controller';
            var_dump($controller);


            /**
             * On sauvegarde le 2ème paramètre de l'URL pour appeler la méthode correspondante sans la Class si elle  existe.
             * Si non on appel la méthode index par défault
             */
            if (isset($params[0])) {
                $action = array_shift($params);
            } else {
                //On appels la méthode Index du Controller demandé si aucune autre paramètre n'est spécifier dans l'URL.
                $action = 'index';
            }

            /*
             *  On appels la Class et la Méthode associé en fonction des paramètre reçu dans l'URL
             *  class_exists   => Vérifie si la Class existe avec son Namespaces définits
             *  methodes_existe => Vérifie si une méthode existe pour une class donnée.
             */
            if (class_exists($controller) &&  method_exists($controller, $action)) {

                // On instancie le contrôleur principal
                $controller = new $controller();

                //On vérifie si l'on possède encore des paramètre
                if (isset($params[0])) {

                    //Démonte un tableau et Envoie les paramètres les uns après les autres (string, int)
                    call_user_func_array([$controller, $action], $params);
                
                } else {
                    //On envoie seulement au Controller le Nom de la Class avec sa méthode a appelé (ex: UsersController, Login)
                    $controller->$action();
                }

            } else {
                // On envoie le code réponse 404 et une page d'erreur 404.
                http_response_code(404);
                $controller = new AcceuilController;
                $controller->erreur404();
            }
        } else {
            // Si aucun paramètre n'est défini dans l'URL, on instancie le contrôleur index de AcceuilController
            $controller = new AcceuilController;
            $controller->index();
        }
    }
}
