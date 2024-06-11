<?php

class User {

    public $username;
    public $password;
    public $auth = false;

    public function __construct() {

    }

    public function test () {
        $db = db_connect();
        $statement = $db->prepare("SELECT * FROM users;");
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);
        return $rows;
    }

    // public function authenticate($username, $password) {
    //     /*
    //      * if username and password good then
    //      * $this->auth = true;
    //      */
    //     $username = strtolower($username);
    //     $db = db_connect();
        
    //     $statement = $db->prepare("SELECT * FROM users WHERE username = :name;");
    //     $statement->bindValue(':name', $username);
    //     $statement->execute();
    //     $rows = $statement->fetch(PDO::FETCH_ASSOC);

    //     if (password_verify($password, $rows['password'])) {
    //         $_SESSION['auth'] = 1;
    //         $_SESSION['username'] = ucwords($username);
    //         unset($_SESSION['failedAuth']);
    //         header('Location: /home');
    //         die;
    //     } else {
    //         if(isset($_SESSION['failedAuth'])) {
    //             $_SESSION['failedAuth'] ++; // increment
    //         } else {
    //             $_SESSION['failedAuth'] = 1;
    //         }
    //         header('Location: /login');
    //         die;
    //     }
    // }

    public function authenticate($username, $password) {
        $username = strtolower($username);
        $db = db_connect();

        // Check if user is locked out
        $lockout = $this->is_locked_out($username);
        if ($lockout['is_locked']) {
            $_SESSION['lockout_time'] = time() + $lockout['remaining_time'];
            $_SESSION['error'] = "Too many failed attempts. Please try again after " . $lockout['remaining_time'] . " seconds.";
            header('Location: /login');
            die;
        }

        $statement = $db->prepare("SELECT * FROM users WHERE username = :name;");
        $statement->bindValue(':name', $username);
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);

        if ($rows && password_verify($password, $rows['password'])) {
            $_SESSION['auth'] = 1;
            $_SESSION['username'] = ucwords($username);
            unset($_SESSION['failedAuth']);
            unset($_SESSION['lockout_time']);
            $this->log_attempt($username, 'good');
            header('Location: /home');
            die;
        } else {
            if (isset($_SESSION['failedAuth'])) {
                $_SESSION['failedAuth']++;
            } else {
                $_SESSION['failedAuth'] = 1;
            }
            $this->log_attempt($username, 'bad');
            $_SESSION['error'] = "Invalid username or password.";
            header('Location: /login');
            die;
        }
    }



    public function create_user($username, $email, $password) {
        $db = db_connect();
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $statement = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $statement->bindParam(':username', $username);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':password', $hashed_password);
        $statement->execute();
    }

    public function user_exists($username) {
        $db = db_connect();
        $statement = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $statement->bindParam(':username', $username);
        $statement->execute();
        return $statement->fetchColumn() > 0;
    }

    private function log_attempt($username, $attempt) {
            $db = db_connect();
            $statement = $db->prepare("INSERT INTO log (username, attempt) VALUES (:username, :attempt)");
            $statement->bindParam(':username', $username);
            $statement->bindParam(':attempt', $attempt);
            $statement->execute();
        }

        private function is_locked_out($username) {
            $db = db_connect();
            $statement = $db->prepare("SELECT attempt_time FROM log WHERE username = :username AND attempt = 'bad' ORDER BY attempt_time DESC LIMIT 3");
            $statement->bindParam(':username', $username);
            $statement->execute();
            $attempts = $statement->fetchAll(PDO::FETCH_ASSOC);

            if (count($attempts) < 3) {
                return ['is_locked' => false, 'remaining_time' => 0];
            }

            $last_attempt_time = strtotime($attempts[0]['attempt_time']);
            $lockout_duration = 60; // 60 seconds

            $remaining_time = $lockout_duration - (time() - $last_attempt_time);
            if ($remaining_time > 0) {
                return ['is_locked' => true, 'remaining_time' => $remaining_time];
            }

            return ['is_locked' => false, 'remaining_time' => 0];
        }
}
?>