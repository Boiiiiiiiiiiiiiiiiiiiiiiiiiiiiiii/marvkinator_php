<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  header('Content-Type: application/json');
  header("Access-Control-Allow-Origin: *");

  $servername = "sql302.infinityfree.com";
  $username = "if0_38847752";
  $password = "v97bmsgREYD2";
  $dbname = "if0_38847752_akinator_db";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch(PDOEception $e){
    echo "Connection failed: ". $e->getMessage();
  }
?>