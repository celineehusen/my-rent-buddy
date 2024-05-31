<?php
  class Car {
    private $car_id;
    private $plates;
    private $model;
    private $type;
    private $status;
    private $cost_per_day;
    private $cost_overdue_per_day;

    function __construct($car_id, $plates, $model, $type, $status, $cost_per_day, $cost_overdue_per_day){
      $this->car_id = $car_id;
      $this->plates = $plates;
      $this->model = $model;
      $this->type = $type;
      $this->status = $status;
      $this->cost_per_day = $cost_per_day;
      $this->cost_overdue_per_day = $cost_overdue_per_day;
    }

    public function setStatus($status){
      $this->status = $status;
    }

    public function getPlates(){
        return $this->plates;
    }

    public function getType(){
      return $this->type;
    }

    public function getModel(){
      return $this->model;
    }

    public function getPrice(){
      return $this->cost_per_day;
    }

    public function getOverdueCost(){
      return $this->cost_overdue_per_day;
    }

    public function getId(){
      return $this->car_id;
    }

    public function getStatus(){
      return ucwords($this->status);
    }

    public function print_car(){
      echo "car_id: $this->car_id<br>
      plates: $this->plates<br>
      model: $this->model<br>
      type: $this->type<br>
      status: $this->status<br>
      cost_per_day: $this->cost_per_day<br>
      cost_overdue_per_day: $this->cost_overdue_per_day<br>";
    }

    public static function get_car_by_id($cars, $id){
      $result;
      foreach($cars as $car){
        if($car->getId() === $id){
          $result = $car;
          break;
        }
      }
      return $result;
    }

    public static function load($conn, $sql){
      try {
        $result = mysqli_query($conn, $sql);
        // get all data
        if ($result->num_rows > 0) {
            $res = $result->fetch_all(MYSQLI_ASSOC);  
        } 
      } catch (mysqli_sql_exception $e){
        die("Error: " . mysqli_error($conn));
      }
      return $res;
    }

    public static function filter_cars($conn, $plates, $model, $type, $status){
      if($status === 'overdue'){
        $sql = 'SELECT * FROM cars LEFT JOIN rental_record ON rental_record.car_id = cars.car_id WHERE ';
      } else {
        $sql = "SELECT * FROM cars WHERE ";
      }
      
      if((!$plates) && (!$model) && (!$type) && $status==='all'){
        $sql = "SELECT * FROM cars;";
        return self::load($conn, $sql);
      }
      if($plates){
        $temp = strtoupper($plates);
        $sql = $sql. "plates LIKE '%{$temp}%' ";
      }
  
      if($model){
        $temp = ucwords($model);
        if($plates){
          $sql = $sql."AND ";
        }
        $sql = $sql. "model LIKE '%{$temp}%' ";
      }
  
      if($type){
        $temp = strtolower($type);
        if($model || $plates){
          $sql = $sql."AND ";
        }
        $sql = $sql. "type LIKE '%{$temp}%' ";
      }
      if($status){
        if($status!=='all'){
          if($type || $model || $plates){
            $sql = $sql."AND ";
          }
          if($status === 'overdue'){
            $sql = $sql. 'rental_record.actual_return_date IS NULL AND rental_record.end_date < NOW()';
          } else {
            $sql = $sql. "status='$status'";
          }
        }
      }
  
      $sql = $sql.';';
      return self::load($conn, $sql);
    }
  }
?>