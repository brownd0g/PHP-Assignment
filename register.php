<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
    require "class/Database.php";
    


    $responses = [
        400 => "Bad Request",
        404 => "Not Found",
        405 => "Method Not Allowed",
        500 => "Internal server error"
    ];

    function send_error($code, $message) {
        global $responses;
        $codeString = (string)$code;
        header($_SERVER['SERVER_PROTOCOL']." - ".$codeString.$resonses[$code].": ".$message, true, $code);
        $body = array("error"=>$codeString." - ".$responses[$code].": ".$message);
        echo json_encode($body);
        die();
    }

    function check_parameter($param, &$results, $doFunction) {
        if (isset($_POST[$param])){
            if($doFunction()){
                $results[$param] = $_POST[$param];
            }
        } else {
            send_error(400, "$param parameter missing!");
        }
    }

    function validate_name() {
        $name = $_POST["name"];
        if (strlen($name) < 2 || strlen($name) > 100) {
            send_error(400, "You must enter a name longer than 2 characters and no more than 100 characters."); 
        } else if(!preg_match("/^[a-zA-Z ]*$/", $name)) { 
            send_error(400, "Please use alphabetical letters only");
        } else {
            return true;    
        }
    }

    function validate_age() {
        $age = $_POST["age"];
        $ageInt = (int)$age;
        if (!preg_match("/^[0-9]*$/", $age)) {
            send_error(400, "Your age must be a number only");
        } else {
            $ageInt = intval($age);
            if ($ageInt < 13 || $ageInt > 130) {
                send_error(400, "You must enter an age between 13 and 130.");
            } else {
                return true;
            }
        }
    }

    function validate_email() {
        $email = $_POST["email"];
        if (!preg_match("/^[a-zA-Z-]([\w-.]+)?@([\w-]+\.)+[\w]+$/", $email)) {
            send_error(400, "You must enter a correct email");
        } else {
            return true;
        }
    }

    function validate_phone() {
        $phone = $_POST["phone"];
        if (strlen($_POST["phone"]) !== 10) {
            send_error(400, "phone number must be 10 digits");
        } else if (!preg_match("/^[0-9]*$/", $phone)) {
            send_error(400, "your phone number must contain only numbers");
        } else if (!preg_match("/^04/", $phone)) {
            send_error(400, "Your phone number must begin with 04.");
        } else {
            return true;
        }
    }
    
    // check for post request
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        send_error(400, "please use POST requests only.");
    }
    // array for data
    $result_array = array();

    // check all fields
    check_parameter("name", $result_array, validate_name);
    check_parameter("age", $result_array, validate_age);
    check_parameter("email", $result_array, validate_email);

     
    if (isset($_POST["phone"])){
        if (validate_phone()){
            $result_array["phone"] = $_POST["phone"];
        }
    }
    
    // get the user id
    $result_array["user_id"] = rand(10000, 99999);
    $return_val = array("user_id"=>$result_array["user_id"]);
    
    // creating the object from the data
    $data = new Database($result_array["name"], $result_array["age"], $result_array["email"], $result_array["phone"], $result_array["user_id"]);
    
    // writing to a gile
    $fp = fopen("database.txt", "w");
    $json_data = json_encode(new Database($result_array["name"], $result_array["age"], $result_array["email"], $result_array["phone"],    $result_array["user_id"]), JSON_PRETTY_PRINT);
    fwrite($fp, $json_data);
    fclose($fp);  
    
    // returning id
    echo json_encode($return_val);


?>