<?php
// Paths to Entities that we want Doctrine to see
$paths = array(
     "module/Application/src/Application/Entity",
);

// Tells Doctrine what mode we want
$isDevMode = true;

// Doctrine connection configuration
$dbParams = array(
    'driver' => 'pdo_pgsql',
    'host'   => 'localhost',
    'port'   => '5432',
    'user' => 'postgres',
    'password' => 'password',
    'dbname' => 'sadb'
);