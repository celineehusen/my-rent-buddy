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

  $id = $_REQUEST['id'];
  $conn = new mysqli("localhost","root","","rental_buddy");

  function get_cars($conn, $user){
    $res = $user->get_all_cars($conn);
    $cars = [];
    foreach($res as $item){
      $car = new Car($item['car_id'],$item['plates'],$item['model'],$item['type'],$item['status'],$item['cost_per_day'],$item['cost_overdue_per_day']);
      array_push($cars, $car);
    }
    return $cars;
  }

  $all_cars = get_cars($conn, $user);
  $selected_car = Car::get_car_by_id($all_cars, $id);
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
    <form action="ChangeStatus.php?id=<?php echo $id?>" method="post">
      <div class="container">
        <div style="margin: auto;padding: 32px;border: 1px solid black;border-radius: 10px;">
          <h2 style="text-align: left; font-weight: normal;">Admin</h2>
          <h1 style="text-align: left">Change Status</h1>
          <p style="text-align: left">Car ID: <?php  echo $selected_car->getId() ?></p>
          <p style="text-align: left">Plates: <?php  echo $selected_car->getPlates() ?></p>
          <p style="text-align: left">Model: <?php  echo $selected_car->getModel() ?></p>
          <p style="text-align: left">Current Status: <?php  echo $selected_car->getStatus() ?></p>
          <div class="field">
            <label for="status">New Status*</label>
            <div class="input-box">
              <select name="status" id="" style="width: 90%; padding-left: 0;">
                <option value="available">Available</option>
                <option value="rented">Rented</option>
                <option value="overdue">Overdue</option>
              </select>
            </div>
          </div>
          <div class="field">
            <div class="main-container" style="justify-content: space-between;">
              <a href="./Car.php?search=available">Cancel</a>
              <input class="button" type="submit" value="Submit" name="submit" style="width: 100px">
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>  
<?php
  if(isset($_POST['submit'])){
    $status = $_POST['status'];
    $user->set_status($conn, $id, $status);
    header('location: ./Car.php?search=available');
  }
?>
</body>
</html>