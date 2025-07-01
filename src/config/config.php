<?php
require_once __DIR__ . '/../../vendor/autoload.php'; // ou le chemin vers autoload.php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); // dossier où est le .env
$dotenv->load();
