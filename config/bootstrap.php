<?php
date_default_timezone_set("America/Toronto"); // Set the default timezone
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(- 1);

include 'vendor/autoload.php';
include 'config/doctrine-config.php';