<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MYRB | My Rental Buddy</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>

<body>
  <div class="logo">
    <img src="../asset/logo.png" alt="logo">
  </div>
  <div class="main-container">
    <form action="login.php" method="post">
      <div class="container">
        <div style="margin: auto;padding: 32px;border: 1px solid black;border-radius: 10px;">
          <h2 style="text-align: left; font-weight: normal;">Welcome !</h2>
          <h1 style="text-align: left">Log In</h1>
          <div class="field">
            <label for="user-type">User Type*</label>
            <div class="input-box">
              <select name="user-type" id="" style="width: 90%; padding-left: 0;">
                <option value="renter" selected>Renter</option>
                <option value="admin">Administrator</option>
              </select>
            </div>
          </div>
          <div class="field">
            <label for="email">Email Address*</label>
            <div class="input-box">
              <input type="text" name="email" placeholder="Enter email address" required>
            </div>
          </div>
          <div class="field">
            <label for="password">Password*</label>
            <div class="input-box">
              <input type="password" name="password" placeholder="Enter password" required>
            </div>
          </div>
          <div class="field">
            <input class="button" type="submit" value="Login" name="submit">

<?php
  session_start();
  $_SESSION['user'] = null;
  require('../class_User.php');
  $conn = new mysqli("localhost","root","","rental_buddy");

  if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    if($email && $password){
      $user_data = User::get_user($conn, $email, $password, $_POST['user-type']);
      if($user_data){
        if($user_data['type'] === 'admin'){
          $user = new Administrator(
            $user_data['user_id'],
            $user_data['full_name'],
            $user_data['surename'],
            $user_data['phone'],
            $user_data['email'],
            $user_data['password'],
          );
        } else {
          $user = new Renter(
            $user_data['user_id'],
            $user_data['full_name'],
            $user_data['surename'],
            $user_data['phone'],
            $user_data['email'],
            $user_data['password'],
          );
        }
        $_SESSION['user'] = serialize($user);
        header('location: ../Car.php?search=available');
      } else {
        echo '<p style="margin-top: 20px; color: red;">User not found. Please try again</p>';
      }
    }
  }
?>

          </div>
          <div class="field">
            <a href="signup.php" style="margin-bottom: 10px; font-size: 16px; width: auto">Don't have an Account? <strong>Register</strong></a>
          </div>
        </div>
      </div>
    </form>
    
    <div>
      <img src="../asset/illustration.jpeg" alt="">
    </div>
  </div>

  
  
  
</body>
</html>