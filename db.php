<?php
$host = "localhost";
$dbname = "here_i_am";
$username = "root"; // change this if your MySQL username is different
$password = "";     // change this if your MySQL has a password

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}
