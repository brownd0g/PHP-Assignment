<?php

class Database implements JsonSerializable {
    
    private $name;
    private $age;
    private $email;
    private $phone;
    private $user_id;
    
    private $file = "database.txt";
    
    public function __construct($name, $age, $email, $phone, $user_id){
        $this->name = $name;
        $this->age = $age;
        $this->email = $email;
        $this->phone = $phone;
        $this->user_id = $user_id;

    }
    
    public function jsonSerialize() {
        return [
               'name' => $this->name,
               'age' => $this->age,
               'email' => $this->email,
               'phone' => $this->phone,
               'user_id' => $this->user_id
        ];
    }
    /*
    public function add_to_database() {
        $fp = fopen($this->file, "w");
        $data = $this->jsonSerialize();
        $json_data = json_encode($data);
        fwrite($fp, $data);
        fclose($fp);  
    }
*/
}