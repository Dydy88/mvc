<?php

namespace App\Controllers;

class AcceuilController extends Controller
{
    public function index()
    {
        $this->render('accueil');
        
        //Choisir un Tempate diffèrent 
        //$this->render('accueil', [], 'other_tempate');

    }


    public function erreur404()
    {
        $this->render('404');
    }
}