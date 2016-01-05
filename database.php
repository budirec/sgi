<?php

$host = 'localhost';
$dbName = 'budirec';

$player = 'budirec';
$pass = 'budirec';

try {
  $db = new PDO('mysql:host=' . $host . ';dbname=' . $dbName, $player, $pass);
} catch (PDOException $e) {
  die('Connection failed. (' . $e->getMessage() . ')');
}
