<?php

namespace App\Core;

class Session
{

    /** On démarre la session à l'instance de la Class. */
    public function __construct()    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }


    /** Permet de définir un Message Flash*/
    public function setFlash(string $type, string $message):void {
        $_SESSION['flash'][$type] = $message;
    }


    /** On vérifie si un Message Flash existe  */
    public function hasFlashes(){
        return isset($_SESSION['flash']);
    }

    /*** On affiche le message flash puis on le détruit. */
    public function getFlash(){
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }


    public function redirect($page)
    {
        header('Location:' . $page);
        exit();
    }
}
