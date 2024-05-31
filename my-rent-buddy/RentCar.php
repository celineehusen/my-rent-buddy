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
  $conn = new mysqli("localhost","root","","rental_buddy");

  function getCars($conn, $user){
    $res = $user->get_available_cars($conn);
    $cars = [];
    foreach($res as $item){
      $car = new Car($item['car_id'],$item['plates'],$item['model'],$item['type'],$item['status'],$item['cost_per_day'],$item['cost_overdue_per_day']);
      array_push($cars, $car);
    }
    return $cars;
  }

  function getTotalCost($cost_per_day, $duration){
    return $cost_per_day * $duration;
  }

  function getDuration($start_date, $end_date){
    $start = strtotime($start_date);
    $end = strtotime($end_date);
    $diff = $end - $start;
    $days = floor($diff / (60 * 60 * 24));
    return $days;
  }

  $all_cars = getCars($conn, $user);
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
    <form action="RentCar.php?id=<?php echo $id?>" method="post">
      <div class="container">
        <div style="margin: auto;padding: 32px;border: 1px solid black;border-radius: 10px;">
          <h2 style="text-align: left; font-weight: normal;">Renter</h2>
          <h1 style="text-align: left">Rent Car</h1>
          <div class="field">
            <label for="">Car ID</label>
            <div class="input-box">
              <input type="text" value="<?php  echo $selected_car->getId() ?>" readonly >
            </div>
          </div>
          <div class="field">
            <label for="">Plates</label>
            <div class="input-box">
              <input type="text" name="" value="<?php  echo $selected_car->getPlates() ?>" readonly >
            </div>
          </div>
          <div class="field">
            <label for="">Model</label>
            <div class="input-box">
              <input type="text" name="" value="<?php  echo $selected_car->getModel() ?>" readonly >
            </div>
          </div>
          <div class="field">
            <label for="">Cost Per Day</label>
            <div class="input-box">
              <input type="text" name="" value="$<?php  echo $selected_car->getPrice() ?>" readonly>
            </div>
          </div>
          <div class="field">
            <label for="">Cost Overdue Per Day</label>
            <div class="input-box">
              <input type="text" name="" value="$<?php  echo $selected_car->getOverdueCost() ?>" readonly>
            </div>
          </div>
          <div class="field">
            <label for="start-date">Start Date</label>
            <div class="input-box">
              <input type="date" name="start-date" value="<?php echo date("Y-m-d")?>" required>
            </div>
          </div>
          <div class="field">
            <label for="end-date">End Date</label>
            <div class="input-box">
              <input type="date" name="end-date" required>
            </div>
          </div>
          <div class="field">
            <div class="main-container" style="justify-content: space-between;">
              <a href="./Car.php?search=available">Cancel</a>
              <input class="button" type="submit" value="Continue" name="submit">
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>  
  <div>
    <?php
      if(isset($_POST['submit'])){
        $id = $selected_car->getId();
        $start_date = $_POST['start-date'];
        $end_date = $_POST['end-date'];
        $duration = getDuration($start_date, $end_date);
        $total_cost = getTotalCost($selected_car->getPrice(), $duration);


        $rent_payload = (object)[
          'id' => $id,
          'start_date' => $start_date,
          'end_date' => $end_date,
          'duration' => $duration,
          'total_cost' => $total_cost
        ];

        $_SESSION['rent_payload'] = $rent_payload;
        $url = './RentConfirmation.php?id='.$id;
        header('location: '.$url);
      }
    ?>
  </div>

</body>
</html>