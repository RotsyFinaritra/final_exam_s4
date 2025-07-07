<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/db.php';  
require 'routes/etudiant_routes.php';
require_once __DIR__ . '/routes/app_routes.php';
require_once __DIR__ . '/config.php';

Flight::start();