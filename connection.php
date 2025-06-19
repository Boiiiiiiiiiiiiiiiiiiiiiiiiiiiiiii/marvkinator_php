<?php
  error_reporting(0);
  ini_set('display_errors', 0);
  header('Content-Type: application/json');
  header("Access-Control-Allow-Origin: *");

  $servername = "bqgwxblgaohhiogylvnt-mysql.services.clever-cloud.com";
  $username = "ukycwrgkfpwjwphl";
  $password = "T8TpccLSsiSoNeitHtjS";
  $dbname = "bqgwxblgaohhiogylvnt";
  $port = "3306";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES 'utf8mb4'");
  }
  catch(PDOEception $e){
    echo "Connection failed: ". $e->getMessage();
  }
?>