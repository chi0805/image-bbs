<?php

class UserRepository extends DbRepository {
    public function insert($name, $password) {
        $salt = substr(bin2hex(random_bytes(20)), 0, 20);
        $password = $this->hashPassword($salt . $password);
        $now = new DateTime();
        
        $sql = "INSERT INTO fw_user (`name`, `password`, `salt`, `created_at`) VALUES (
            :name, :password, :salt, :created_at
        )";
        $params = [
            ':name'       => $name,
            ':password'   => $password,
            ':salt'       => $salt,
            ':created_at' => $now->format('Y-m-d H:i:s'),
        ];
        $stmt = $this->execute($sql, $params);

    }

    public function hashPassword($password) {
        return sha1($password);
    }

    public function fetchByUsername($user_name) {
        $sql = "SELECT id,name,password,salt FROM `fw_user` WHERE name = :user_name;";
        $params = [
            ':user_name' => $user_name,
        ];
        $results = $this->fetch($sql, $params);
 
        return $results;
    }

    public function fetchByUserId($user_id) {
        $sql = "SELECT id,name,password,salt FROM `fw_user` WHERE id = :user_id;";
        $params = [
            ':user_id' => $user_id,
        ];
        $results = $this->fetch($sql, $params);
 
        return $results;
    }

    public function isPasswordCorrect($correct_pass, $input_pass, $salt) {
        $hash_input_pass = $this->hashPassword($salt . $input_pass);
        if ($correct_pass === $hash_input_pass) {
            return true;
        }

        return false;
    }
}
