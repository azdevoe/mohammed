<?php
$password = "Mohammed_2005";
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
echo "Hashed Password: " . $hashedPassword . "\n";
?>