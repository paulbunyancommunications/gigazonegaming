<?php
// This is global bootstrap for autoloading
require dirname(__DIR__) . '/vendor/autoload.php';
$dotenv = new \Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();
