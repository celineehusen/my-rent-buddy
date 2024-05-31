<?php
  require_once('./class_User.php');
  require_once('./class_Car.php');
  session_start();
  if($_SESSION['user']===null){
    header('Location: ./auth/login.php');
  }

  $user = unserialize($_SESSION['user']);
  $conn = new mysqli("localhost","root","","rental_buddy");
  $user_detail = $user->get_user_details();

  function getOverdueCost($selected_car){
    $end_date = strtotime($selected_car['end_date']);
    $actual_end_date = strtotime(date("Y-m-d"));
    $diff = $actual_end_date - $end_date;
    $days = floor($diff / (60 * 60 * 24));
    if(date($selected_car['end_date']) > date("Y-m-d")){
      return 0;
    }
    return $days * $selected_car['cost_overdue_per_day'];
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
  <div class="main-container" style="width: 90%; margin: auto">
    <div class="container" style="width: 100%">
      <div style="margin: auto;padding: 32px;border: 1px solid black;border-radius: 10px; width: 100%">
        <h1 style="text-align: left">My Account</h1>
        <p style="text-align: left">User ID: <?php echo $user->get_id() ?></p>
        <p style="text-align: left">Name: <?php echo $user_detail->full_name ?></p>
        <p style="text-align: left">Phone: <?php  echo $user_detail->phone ?></p>
        <p style="text-align: left">Email: <?php  echo $user_detail->email?></p>
        <p style="text-align: left">Type: <?php  echo ucwords($user->get_user_type()) ?></p>
      </div>
    </div>
  </div>  
  <div class="main-container" style="width: 90%; margin: auto">
    <div class="container" style="width: 100%">
      <div style="margin: auto;padding: 32px;border: 1px solid black;border-radius: 10px; width: 100%">
        <h1 style="text-align: left">Ongoing Rent</h1>
        <?php
          $sql = "SELECT * FROM `rental_record` LEFT JOIN cars ON rental_record.car_id = cars.car_id WHERE user_id=".$user->get_id()." AND actual_return_date IS NULL;";
          $result = User::load($conn, $sql);
          if($result){
            foreach($result as $item){
              echo '<div class="results" style="margin: 0 0 20 0; width: 95%"><div><p>Car ID</p><p>';
              echo $item['car_id'];
              echo '</p></div><div><p>Plates</p><p>';
              echo strtoupper($item['plates']);
              echo '</p></div><div><p>Model</p><p>';
              echo ucwords($item['model']);
              echo '</p></div><div><p>Type</p><p>';
              echo ucwords($item['type']);
              echo '</p></div><div><p>Start Date</p><p>';
              echo $item['start_date'];
              echo '</p></div><div><p>End Date<p><p style="color: red">';
              echo $item['end_date'];
              echo '</p></div><div><p>Duration</p><p>';
              echo $item['duration'].' day(s)';
              echo '</p></div><div><p>Estimated Cost</p><p>';
              echo '$'.$item['total_cost'];
              echo '</p></div><div><a href="ReturnCar.php?id='.$item['car_id'].'"><button>Return Now</button></a>';
              echo '</div></div>';
            }
          } else {
            echo '<p>No ongoing rent.</p>';
          }
        ?>
      </div>
    </div>
  </div>  
  <div class="main-container" style="width: 90%; margin: auto">
    <div class="container" style="width: 100%">
      <div style="margin: auto;padding: 32px;border: 1px solid black;border-radius: 10px; width: 100%">
        <h1 style="text-align: left">Rent History</h1>
        <?php
          $sql = "SELECT * FROM `rental_record` LEFT JOIN cars ON rental_record.car_id = cars.car_id WHERE user_id=".$user->get_id()." AND actual_return_date IS NOT NULL;";
          $result = User::load($conn, $sql);
          if($result){
            foreach($result as $item){
              echo '<div class="results" style="margin: 0 0 20 0; width: 95%"><div><p>Car ID</p><p>';
              echo $item['car_id'];
              echo '</p></div><div><p>Plates</p><p>';
              echo strtoupper($item['plates']);
              echo '</p></div><div><p>Model</p><p>';
              echo ucwords($item['model']);
              echo '</p></div><div><p>Type</p><p>';
              echo ucwords($item['type']);
              echo '</p></div><div><p>Start Date</p><p>';
              echo $item['start_date'];
              echo '</p></div><div><p>End Date</p><p>';
              echo $item['end_date'];
              echo '</p></div><div><p>Return Date</p><p>';
              echo $item['actual_return_date'];
              echo '</p></div><div><p>Total Duration</p><p>';
              echo $item['duration'].' day(s)';
              echo '</p></div><div><p>Overdue Cost</p><p>';
              echo '$'.getOverdueCost($item);
              echo '</p></div><div><p>Total Cost</p><p>';
              echo '$'.$item['total_cost'];
              echo '</p></div></div>';
            }
          } else {
            echo '<p>No rent history.</p>';
          }
        ?>
    </div>
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