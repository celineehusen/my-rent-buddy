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
  $payload = $_SESSION['rent_payload'];
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
    <form action="RentConfirmation.php?id=<?php echo $id?>" method="post">
      <div class="container">
        <div style="margin: auto;padding: 32px;border: 1px solid black;border-radius: 10px;">
          <h2 style="text-align: left; font-weight: normal;">Confirmation</h2>
          <div class="field">
            <label for="">Duration</label>
            <div class="input-box">
              <input type="text" value="<?php  echo $payload->duration ?> day(s)" readonly >
            </div>
          </div>
          <div class="field">
            <label for="">Total Cost</label>
            <div class="input-box">
              <input type="text" name="" value="$<?php  echo $payload->total_cost ?>" readonly >
            </div>
          </div>
          <div class="field">
            <div class="main-container" style="justify-content: space-between;">
              <a href="RentCar.php?id=<?php echo $id?>">Back</a>
              <input class="button" type="submit" value="Submit" name="submit">
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>  
  <div>
    <?php
      if(isset($_POST['submit'])){
        $user_id = $user->get_id();
        $car_id = $id;
        $start_date = $payload->start_date;
        $end_date = $payload->end_date;
        $total_cost = $payload->total_cost;
        $duration = $payload->duration;
        $actual_return = null;

        echo '<pre>';
        echo print_r($payload);
        echo '</pre>';

        // insert new record
        $sql = "INSERT INTO `rental_record` (`rental_id`, `user_id`, `car_id`, `start_date`, `end_date`, `actual_return_date`, `total_cost`, `duration`) VALUES (NULL, '$user_id', '$car_id', '$start_date', '$end_date', NULL, $total_cost, $duration);";
        echo $sql;
        User::load($conn, $sql);

        // change car status
        $sql = "UPDATE cars SET status = 'rented' WHERE car_id = ".$car_id.";";
        User::load($conn, $sql);
        $_SESSION['rent_payload'] = null;

        header('location: ./User.php');
      }
    ?>
  </div>

</body>
</html>
