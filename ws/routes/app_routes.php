<?php
require_once __DIR__ . '/../controllers/AppController.php';

$appController = new AppController();

Flight::route('GET /', [$appController, 'home']);
