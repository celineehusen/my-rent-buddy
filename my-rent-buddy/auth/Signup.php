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
    <form action="signup.php" method="post">
      <div class="container">
        <div style="margin: auto;padding: 32px;border: 1px solid black;border-radius: 10px;">
          <h2 style="text-align: left; font-weight: normal;">Welcome !</h2>
          <h1 style="text-align: left">Sign Up</h1>
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
            <label for="full-name">Full Name*</label>
            <div class="input-box">
              <input type="text" name="full-name" placeholder="Enter full name" required>
            </div>
          </div>
          <div class="field">
            <label for="surename">Surename*</label>
            <div class="input-box">
              <input type="text" name="surename" placeholder="Enter surename" required>
            </div>
          </div>
          <div class="field">
            <label for="email">Email Address*</label>
            <div class="input-box">
              <input type="text" name="email" placeholder="Enter email address (test@gmail.com)" required>
            </div>
          </div>
          <div class="field">
            <label for="phone">Phone*</label>
            <div class="input-box">
              <input type="text" name="phone" placeholder="Enter phone (Australian)" required>
            </div>
          </div>
          <div class="field">
            <label for="password">Password*</label>
            <div class="input-box">
              <input type="password" name="password" placeholder="Enter password" required>
            </div>
          </div>
          <div class="field">
            <label for="confirm-password">Confirm Password*</label>
            <div class="input-box">
              <input type="password" name="confirm-password" placeholder="Enter confirm password" required>
            </div>
          </div>
          <div class="field">
            <input class="button" type="submit" value="Sign Up" name="submit">

<?php
  session_start();
  require('../class_User.php');

  try {
    $conn = new mysqli("localhost","root","","rental_buddy");
  } catch ( mysqli_sql_exception $e) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
  }

  function is_valid($conn, $user_type, $full_name, $surename, $email, $phone, $password, $confirmed_password){
    if(!$email || !$password || !$confirmed_password || !$full_name || !$surename || !$phone) { 
      echo '<p style="margin-top: 20px; color: red;">Please fill out the form properly.</p>';
      return false; 
    }
    if(!($password === $confirmed_password)){
      echo '<p style="margin-top: 20px; color: red;">Password not match.</p>';
      return false;
    }

    if(preg_match("/^04[0-9]{8}$/", $phone) == 0){
      echo '<p style="margin-top: 20px; color: red;">Please enter Australian phone number.</p>';
      return false;
    }

    if(preg_match("/^[a-zA-Z0-9_]*@[a-z]*.com$/", $email) == 0){
      echo '<p style="margin-top: 20px; color: red;">Please enter email in the correct format.</p>';
      return false;
    }

    $sql = "SELECT * FROM user WHERE email='".$email."' AND type='".$user_type."';";
      
    try {
      $result = mysqli_query($conn, $sql);
      $user_data; 
      // get all data
      if ($result->num_rows > 0) {
        $user_data = $result->fetch_all(MYSQLI_ASSOC)[0];  
      }
    } catch (mysqli_sql_exception $e){
      die("Error: " . mysqli_error($conn));
    }

    if($user_data) echo '<p style="margin-top: 20px; color: red;">User with the email address is already exist.</p>';
    return $user_data? false : true;
  }

  if(isset($_POST['submit'])){
    $user_type = $_POST['user-type'];
    $full_name = ucwords($_POST['full-name']);
    $surename = ucwords($_POST['surename']);
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = md5($_POST['password']);
    $confirmed_password = md5($_POST['confirm-password']);

    if(is_valid($conn, $user_type, $full_name, $surename, $email, $phone, $password, $confirmed_password)){
      if($user_type === 'admin'){
        User::insert_user($conn, 'admin', $full_name, $surename, $email, $phone, $password);
      } else {
        User::insert_user($conn, 'renter', $full_name, $surename, $email, $phone, $password);
      }
      $user_data = User::get_user($conn, $email, $password, $user_type);
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
      }
    }
  }
?>

          </div>
          <div class="field">
            <a href="login.php" style="margin-bottom: 10px; font-size: 16px; width: auto">Already have an Account? <strong>Log In</strong></a>
          </div>
        </div>
      </div>
    </form>
  </div>
  
  
</body>
</html>