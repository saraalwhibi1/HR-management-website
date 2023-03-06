<?php

define('DBHOST', "localhost");
define('DBNAME', "manage_me");
define('DBUSER', "root");
define('DBPASS', "root");

try {
    $pdo = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME . "", DBUSER, DBPASS);
} catch (PDOException $e) {
    die($e->getMessage());
}
