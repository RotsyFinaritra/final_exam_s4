<?php

class AppController {
    public function home() {
        Flight::render("home.php");
    }

    public function prets() {
        Flight::render("prets.php");
    }

    public function ajouterDemande() {
        Flight::render("demande_form.php");
    }
}
