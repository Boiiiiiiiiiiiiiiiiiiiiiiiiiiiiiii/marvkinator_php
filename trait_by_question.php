<?php
  header('Content-Type: application/json');
  header("Access-Control-Allow-Origin: *");

  include "connection.php";

  $question = $_GET['question'];

  $sql = "SELECT trait FROM traits_questions_table WHERE question = :question"; // <- FIXED HERE
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(":question", $question);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result) {
    echo json_encode($result);
  } else {
      echo json_encode(["error" => "No matching trait found."]);
  }
?>