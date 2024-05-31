<?php
  require_once('./class_User.php');
  require_once('./class_Car.php');
  session_start();
  if($_SESSION['user']===null){
    header('Location: ./auth/login.php');
  }

  $user = unserialize($_SESSION['user']);
  if($user->get_user_type()==='renter'){
    header('Location: ./Car.php');
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MYRB | My Rental Buddy</title>
  <link rel="stylesheet" href="./style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>

<body>
  <div class="container2">
    <div class="logo">
      <img src="./asset/logo.png" alt="logo">
    </div>
    <div class="nav">
      <a href="car.php?search=available">Cars</a>
      <a href="user.php">My Account</a>
      <a href="./auth/login.php">Sign Out</a>
    </div>
  </div>
  <div class="main-container">
    <form action="InsertCar.php" method="post">
      <div class="container">
        <div style="margin: auto;padding: 32px;border: 1px solid black;border-radius: 10px;">
          <h2 style="text-align: left; font-weight: normal;">Admin</h2>
          <h1 style="text-align: left">Insert Car</h1>
          <div class="field">
            <label for="plates">Plates*</label>
            <div class="input-box">
              <input type="text" name="plates" placeholder="Enter plates" required>
            </div>
          </div>
          <div class="field">
            <label for="model">Model*</label>
            <div class="input-box">
              <input type="text" name="model" placeholder="Enter model" required>
            </div>
          </div>
          <div class="field">
            <label for="type">Type*</label>
            <div class="input-box">
              <input type="text" name="type" placeholder="Enter type" required>
            </div>
          </div>
          <div class="field">
            <label for="cost-per-day">Cost Per Day*</label>
            <div class="input-box">
              <input type="number" name="cost-per-day" placeholder="Enter cost per day" required>
            </div>
          </div>
          <div class="field">
            <label for="cost-overdue">Cost Overdue Per Day*</label>
            <div class="input-box">
              <input type="number" name="cost-overdue" placeholder="Enter cost overdue per day" required>
            </div>
          </div>
          <div class="field">
            <input class="button" type="submit" value="Submit" name="submit">

<?php
  if(isset($_POST['submit'])){
    $plates = strtoupper($_POST['plates']);
    $model = ucwords($_POST['model']);
    $type = strtolower($_POST['type']);
    $cost_per_day = $_POST['cost-per-day'];
    $cost_overdue = $_POST['cost-overdue'];

    $plates_list = $user->get_plates();
    if(in_array($plates, $plates_list)){
      echo '<p style="margin-top: 20px; color: red;">Duplicate plates. Please enter the correct plate number.</p>';
    } else {
      $user->insert_car($plates, $model, $type, $cost_per_day, $cost_overdue);
      header('location: ./Car.php?search=available');
    }
  }
?>
          </div>
        </div>
      </div>
    </form>
  </div>
  
  
</body>
</html>