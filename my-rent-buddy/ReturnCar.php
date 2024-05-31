<?php
  require_once('./class_User.php');
  require_once('./class_Car.php');
  session_start();
  if($_SESSION['user']===null){
    header('Location: ./auth/login.php');
  }

  $user = unserialize($_SESSION['user']);
  if($user->get_user_type()==='admin'){
    header('Location: ./Car.php');
  }

  $id = $_REQUEST['id'];
  if(!$id){
    header('Location: ./user.php');
  }
  $conn = new mysqli("localhost","root","","rental_buddy");

  $sql = "SELECT * FROM `rental_record` LEFT JOIN cars ON rental_record.car_id = cars.car_id WHERE user_id=".$user->get_id()." AND rental_record.car_id=".$id." AND actual_return_date IS NULL;";
  $result = User::load($conn, $sql);
  $selected_car = $result[0];
  $total_duration = getTotalDuration($selected_car);
  $total_overdue = getOverdueDay($selected_car, $total_duration);
  $overdue_cost = getOverdueCost($selected_car, $total_overdue);
  $total_cost = getTotalCost($selected_car, $total_duration, $overdue_cost);

  function getTotalDuration($selected_car){
    if($selected_car['end_date'] === date("Y-m-d")) {
      return $selected_car['duration'];
    }
    $start_date = strtotime($selected_car['start_date']);
    $actual_end_date = strtotime(date("Y-m-d"));
    $diff = $actual_end_date - $start_date;
    $days = floor($diff / (60 * 60 * 24));
    return $days;
  }

  function getOverdueDay($selected_car, $total_duration){
    if(date($selected_car['end_date']) > date("Y-m-d")){
      return 0;
    }
    return $total_duration - $selected_car['duration'];
  }

  function getTotalCost($selected_car, $total_duration, $overdue_cost){
    $cost = ($total_duration * $selected_car['cost_per_day']) + $overdue_cost;
    return $cost;
  }

  function getOverdueCost($selected_car, $total_overdue){
    return $total_overdue * $selected_car['cost_overdue_per_day'];
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
    <form action="ReturnCar.php?id=<?php echo $id?>" method="post">
      <div class="container">
        <div style="margin: auto;padding: 32px;border: 1px solid black;border-radius: 10px;">
          <h2 style="text-align: left; font-weight: normal;">Renter</h2>
          <h1 style="text-align: left">Return Car</h1>
          <div class="field">
            <label for="">Car ID</label>
            <div class="input-box">
              <input type="text" value="<?php echo $id?>" readonly >
            </div>
          </div>
          <div class="field">
            <label for="email">Plates</label>
            <div class="input-box">
              <input type="text" name="email" value="<?php echo $selected_car['plates']?>" readonly >
            </div>
          </div>
          <div class="field">
            <label for="email">Model</label>
            <div class="input-box">
              <input type="text" name="email" value="<?php echo $selected_car['model']?>" readonly >
            </div>
          </div>
          <div class="field">
            <label for="email">Start Date</label>
            <div class="input-box">
              <input type="date" name="email" value="<?php echo $selected_car['start_date']?>" readonly >
            </div>
          </div>
          <div class="field">
            <label for="email">End Date (Today)</label>
            <div class="input-box">
              <input type="date" name="email" value="<?php echo date("Y-m-d")?>" readonly >
            </div>
          </div>
          <div class="field">
            <label for="email">Total Duration</label>
            <div class="input-box">
              <input type="text" name="email" value="<?php echo $total_duration ?> day(s)" readonly>
            </div>
          </div>
          <div class="field">
            <label for="email">Overdue Days</label>
            <div class="input-box">
              <input type="text" name="email" value="<?php echo $total_overdue ?> day(s)" readonly>
            </div>
          </div>
          <div class="field">
            <label for="">Overdue Cost</label>
            <div class="input-box">
              <input type="text" name="" value="$<?php echo $overdue_cost ?>" readonly>
            </div>
          </div>
          <div class="field">
            <label for="email">Total Cost</label>
            <div class="input-box">
              <input type="text" name="email" value="$<?php echo $total_cost ?>" readonly>
            </div>
          </div>
          <div class="field">
            <div class="main-container" style="justify-content: space-between;">
              <a href="./Car.php?search=available">Cancel</a>
              <input class="button" type="submit" value="Pay Now" name="submit">
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>  
<?php
  if(isset($_POST['submit'])){
    $now = date("Y-m-d");
    $user_id = $user->get_id();
    $rent_id = $selected_car['rental_id'];
    $sql = "UPDATE rental_record SET duration=$total_duration, total_cost=$total_cost, actual_return_date='$now' WHERE rental_id=$rent_id;";
    echo $sql;
    User::load($conn, $sql);

    // change car status
    $sql = "UPDATE cars SET status = 'available' WHERE car_id = ".$selected_car['car_id'].";";
    User::load($conn, $sql);
    header('location: ./User.php');;
  }
?>
</body>
</html>