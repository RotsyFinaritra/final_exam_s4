<?php
require_once 'config.php';
require 'vendor/autoload.php';
require 'db.php';
require 'controllers/EtudiantController.php';

$etudiantController = new EtudiantController();

Flight::route('GET /etudiants', [$etudiantController, 'getAll']);
Flight::route('GET /etudiants/@id', [$etudiantController, 'getById']);
Flight::route('POST /etudiants', [$etudiantController, 'create']);
Flight::route('PUT /etudiants/@id', [$etudiantController, 'update']);
Flight::route('DELETE /etudiants/@id', [$etudiantController, 'delete']);

Flight::start();
