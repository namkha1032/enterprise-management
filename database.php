<?php  
// Database configuration  
$serverName = "localhost:3307";
$user = 'root';
$pass = '';
$dbName = 'webpro';
$conn = new mysqli($serverName, $user, $pass, $dbName) or die("Unable to connect");
