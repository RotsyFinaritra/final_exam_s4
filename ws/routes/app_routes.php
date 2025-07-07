<?php
require_once __DIR__ . '/../controllers/AppController.php';

$appController = new AppController();

Flight::route('GET /', [$appController, 'home']);
Flight::route('GET /prets_view', [$appController, 'prets']);
Flight::route('GET /demande_form_view', [$appController, 'ajouterDemande']);
