<?php
  session_start();
  if($_SESSION['user']===null){
    header('Location: ./auth/login.php');
  }
  header('Location: ./Car.php?search=available');
?>