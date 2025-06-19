<?php
  header('Access-Control-Allow-Origin: *');

  $name = $_GET['name'];
  $hobby = $_GET['hobby'];

  echo "hi $name, your hobby must be $hobby";
?>