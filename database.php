<?php

$host = 'localhost';
$dbName = '';

$user = '';
$pass = '';

try {
  $db = new PDO('mysql:host=' . $host . ';dbname=' . $dbName, $user, $pass);
} catch (PDOException $e) {
  die('Connection failed. (' . $e->getMessage() . ')');
}
