<?php
require_once __DIR__ . '/vendor/autoload.php'; 
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
pg_connect("host=".$_ENV['DB_HOST']." dbname=".$_ENV['DB_NAME']." user=".$_ENV['DB_USER']." password=".$_ENV['DB_PASSWORD'] );

