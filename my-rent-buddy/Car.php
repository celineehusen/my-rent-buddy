<?php
  require_once('./class_User.php');
  require_once('./class_Car.php');
  session_start();
  if($_SESSION['user']===null){
    header('Location: ./auth/login.php');
  }

  $user = unserialize($_SESSION['user']);
  $conn = new mysqli("localhost","root","","rental_buddy");
  $queryParams = $_REQUEST['search'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MYRB | My Rental Buddy</title>
  <link rel="stylesheet" href="style.css">
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
  <div style="margin: auto">
    <h2 style="font-weight: normal; text-align: center">Cars</h2>
    <div class="main-container" style="margin: auto; width: 300px">
      <?php
        if($user->get_user_type() === 'admin'){
          echo '<a href="InsertCar.php"><button style="border-radius: 60px;">Insert Car</button></a>';
        }
      ?>
    </div>
    
    <form action="car.php?search=filter" class="main-container search-box" method="post">
      <div class="main-container search-car" style="margin-right: 50px">
        <div>
          <label for="plates"><strong>Plates</strong></label>
          <input type="text" placeholder="Enter plates" name="plates">
        </div>
        <div>
          <label for="model"><strong>Model</strong></label>
          <input type="text" placeholder="Enter model" name="model">
        </div>
        <div style="display: flex; align-items: center">
          <label for="type"><strong>Type</strong></label>
          <input type="text" placeholder="Enter type" name="type">
        </div>
        <?php
          if($user->get_user_type() === 'admin'){
            echo '
            <div class="field" style="width: 150px">
              <label for="status"></label>
              <div >
                <select name="status" id="">
                  <option value="all">All</option>
                  <option value="available" selected>Available</option>
                  <option value="overdue">Overdue</option>
                  <option value="rented">Rented</option>
                </select>
              </div>
            </div>
            ';
          }
        ?>
      </div>
      <input type="submit" value="Search" style="width: 150px" name="submit">
    </form>
  </div>

  <!-- search results -->
  <div>
<?php
  if($queryParams === 'available'){
    $results = $user->get_available_cars();
  }else if($queryParams === 'filter') {
    $plates = $_POST['plates'];
    $model = $_POST['model'];
    $type = $_POST['type'];
    if($user->get_user_type()==='admin'){
      $status = $_POST['status'];
    } else if($user->get_user_type()==='renter'){
      $status = 'available';
    }

    $filter_text = "<div class='results'><p>Filter Applied:";
    if($plates){
      $filter_text = $filter_text." ( Plates - ".strtoupper($plates)." ) ";
    }
    if($model){
      $filter_text = $filter_text." ( Model - ".ucwords($model)." ) ";
    }
    if($type){
      $filter_text = $filter_text." ( Type - ".ucwords($type)." ) ";
    }
    if($status){
      $filter_text = $filter_text." ( Status - ".ucwords($status)." ) ";
    }
    $filter_text = $filter_text."</p></div>";
    echo $filter_text;

    $results = Car::filter_cars($conn, $plates, $model, $type, $status);
  }

  $cars = [];
  foreach($results as $item){
    $car = new Car($item['car_id'],$item['plates'],$item['model'],$item['type'],$item['status'],$item['cost_per_day'],$item['cost_overdue_per_day']);
    array_push($cars, $car);
  }

  if(class_exists('Car')){
    foreach($cars as $car){
      echo '<div class="results"><div><p>Car ID</p><p>';
      echo $car->getId();
      echo '</p></div><div><p>Plates</p><p>';
      echo strtoupper($car->getPlates());
      echo '</p></div><div><p>Model</p><p>';
      echo ucwords($car->getModel());
      echo '</p></div><div><p>Type</p><p>';
      echo ucwords($car->getType());
      echo '</p></div><div><p>Cost per day</p><p>';
      echo "$".$car->getPrice();
      if($user->get_user_type()==='admin'){
        echo '</p></div><div><a href="ChangeStatus.php?id='.$car->getId().'"><button>Change status</button></a><p>';
      } else {
        echo '</p></div><div><a href="RentCar.php?id='.$car->getId().'"><button>Rent</button></a><p>';
      }
      echo '</p></div></div>';
    }
  }
?>

  </div>
</body>
</html>
