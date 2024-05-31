<?php
  class User {
    private $id;
    private $full_name;
    private $surename;
    private $phone;
    private $email;
    private $type;
    private $password; // encrypt with md5
    private $conn;

    function __construct($id, $full_name, $surename, $phone, $email, $type, $password){
      $this->id = $id;
      $this->full_name = $full_name;
      $this->surename = $surename;
      $this->phone = $phone;
      $this->email = $email;
      $this->type = $type;
      $this->password = $password;
      $this->conn = new mysqli("localhost","root","","rental_buddy");
    }

    public function get_user_type(){
      return $this->type;
    }

    public function get_id(){
      return $this->id;
    }

    public function get_user_details(){
      $user = (object) array(
        'full_name' => $this->full_name, 
        'surename' => $this->surename, 
        'phone' => $this->phone, 
        'email' => $this->email
      );
      return $user;
    }

    public function print_user(){
      echo "id: $this->id<br>
      name: $this->full_name<br>
      surename: $this->surename<br>
      phone: $this->phone<br>
      email: $this->email<br>
      type: $this->type<br>
      pass: $this->password<br>";
    }

    public function get_available_cars(){
      $this->conn = new mysqli("localhost","root","","rental_buddy");
      $sql = 'SELECT * FROM cars WHERE status="available";';
      return self::load($this->conn, $sql);
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

    public static function get_user($conn, $email, $password, $user_type){
      $sql = "SELECT * FROM user WHERE email='".$email."' AND password='".$password."' AND type='".$user_type."';";
      return self::load($conn, $sql)[0];
    }

    public static function insert_user($conn, $user_type, $full_name, $surename, $email, $phone, $password){
      $sql = "INSERT INTO user (`user_id`, `full_name`, `surename`, `phone`, `email`, `type`, `password`) VALUES (NULL, '".$full_name."', '".$surename."', '".$phone."', '".$email."', '".$user_type."', '".$password."');";
      return self::load($conn, $sql);
    }
  }

  class Administrator extends User {
    function __construct($id, $full_name, $surename, $phone, $email, $password){
      User::__construct($id, $full_name, $surename, $phone, $email, 'admin', $password);
    }

    public function set_status($conn, $id, $status){
      $sql = "UPDATE cars SET status = '".$status."' WHERE car_id = ".$id.";";
      return self::load($conn, $sql);
    }

    public function insert_car($plates, $model, $type, $cost_per_day, $cost_overdue){
      $this->conn = new mysqli("localhost","root","","rental_buddy");
      $sql = "INSERT INTO `cars` (`car_id`, `plates`, `model`, `type`, `status`, `cost_per_day`, `cost_overdue_per_day`) VALUES (NULL, '".$plates."', '".$model."', '".$type."', 'available', ".$cost_per_day.", ".$cost_overdue.");";
      return self::load($this->conn, $sql);
    }

    public function get_plates(){
      $this->conn = new mysqli("localhost","root","","rental_buddy");
      $sql = "SELECT plates FROM `cars`;";
      $res = self::load($this->conn, $sql);
      $plates = [];
      foreach($res as $item){
        array_push($plates, $item['plates']);
      }
      return $plates;
    }

    public function get_all_cars($conn){
      $sql = 'SELECT * FROM cars';
      return self::load($conn, $sql);
    }
  }

  class Renter extends User {
    function __construct($id, $full_name, $surename, $phone, $email, $password){
      User::__construct($id, $full_name, $surename, $phone, $email, 'renter', $password);
    }
  }
?>