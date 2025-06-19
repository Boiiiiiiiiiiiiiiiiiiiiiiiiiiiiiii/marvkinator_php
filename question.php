<?php
  ob_start(); // Start output buffering
  error_reporting(0);
  ini_set('display_errors', 0);
  header('Content-Type: application/json');
  header("Access-Control-Allow-Origin: *");

  include "connection.php";

  $trait = $_GET['trait'];

  $sql = "SELECT question FROM traits_questions_table WHERE trait = :trait"; // <- FIXED HERE
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(":trait", $trait);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  echo json_encode($result);
  ob_clean();
?>