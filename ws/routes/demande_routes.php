<?php
require_once __DIR__ . '/../controllers/DemandePretController.php';

$demandePretController = new DemandePretController();

Flight::route('GET /demande_prets/list', [$demandePretController, 'getAll']);
Flight::route('GET /demande_prets/@id', [$demandePretController, 'getOne']);
Flight::route('POST /demande_prets/create', [$demandePretController, 'create']);
Flight::route('PUT /demande_prets/@id', [$demandePretController, 'update']);
Flight::route('DELETE /demande_prets/@id', [$demandePretController, 'delete']);