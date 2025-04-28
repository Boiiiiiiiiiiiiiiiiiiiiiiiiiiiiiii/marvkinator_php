<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  header('Content-Type: application/json');
  header("Access-Control-Allow-Origin: *");

  $servername = "bncjpie37hb9qataurjt-mysql.services.clever-cloud.com";
  $username = "uyn3ieoabrdydg0k";
  $password = "XyoRz4khyfh27MMn5TeE";
  $dbname = "bncjpie37hb9qataurjt";
  $port = "your-new-port";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch(PDOEception $e){
    echo "Connection failed: ". $e->getMessage();
  }
?>