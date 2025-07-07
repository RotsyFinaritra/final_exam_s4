<?php
require_once __DIR__ . '/../controllers/PretController.php';

$pretController = new PretController();

Flight::route('GET /prets', [$pretController, 'getAll']);
Flight::route('GET /prets/@id', [$pretController, 'getOne']);
Flight::route('POST /prets', [$pretController, 'create']);
Flight::route('PUT /prets/@id', [$pretController, 'update']);
Flight::route('DELETE /prets/@id', [$pretController, 'delete']);

Flight::start();
