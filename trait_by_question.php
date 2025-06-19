<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  header('Content-Type: application/json');
  header("Access-Control-Allow-Origin: *");

  include "connection.php";

  try {
    $question = $_GET['question'] ?? '';
    if (!$question) {
      throw new Exception("Missing 'question' parameter.");
    }

    $sql = "SELECT trait FROM traits_questions WHERE question = :question";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":question", $question);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
      echo json_encode($result);
    } else {
      echo json_encode(["error" => "No matching trait found."]);
    }
  } catch (Exception $e) {
      echo json_encode([
          "error" => $e->getMessage(),
          "trace" => $e->getTraceAsString(),
          "input" => $_GET['question'] ?? null
      ]);
  }
?>