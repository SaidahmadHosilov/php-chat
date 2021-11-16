<?php

class User
{
    public static function getUserImage($userId)
    {
        $db = Db::getConnection();
        $sql = "SELECT * FROM users WHERE id = $userId";
        $result = $db->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        return $result->fetch();
    }

    public static function insertDataToChat($userId, $userToId, $message)
    {
        $db = Db::getConnection();
        $date = date('Y-m-d H:i:s');

        $sql = "INSERT INTO sms( user_id, user_to_id, text, time)
        VALUES ( $userId, $userToId, '$message', '$date' )";
        $db->query($sql);
    }

    public static function getAllSMS($userId, $userToId)
    {
        $db = Db::getConnection();
        $arr = [];

        $sql = "SELECT * FROM sms WHERE user_id = $userId AND user_to_id = $userToId
                UNION ALL 
                SELECT * FROM sms WHERE user_id = $userToId AND user_to_id = $userId 
                ORDER BY time";
        $result = $db->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $arr = $result->fetchAll();
        
        return $arr;
    }

    public static function getUserToInfo($user_to_id)
    {
        $db = Db::getConnection();
        $sql = "SELECT * FROM users WHERE id = $user_to_id";
        $result = $db->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        
        return $result->fetch();
    }

    public static function checkNewPassword($pass, $passR)
    {
        if( empty($pass) && empty($passR) ){
            return true;
        } else if ( $pass != $passR || strlen($pass) < 6 ) {
            return false;
        } else {
            return true;
        }

    }

    public static function getUsers()
    {
        $db = Db::getConnection();

        $sql = "SELECT * FROM users";
        $result = $db->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        
        return $result->fetchAll();
    }

    public static function register( $name, $number, $password, $image )
    {
        $db = Db::getConnection();
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (name, phone, image, password )
                VALUES (:name, :phone, :image, :password)"; 

        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':phone', $number, PDO::PARAM_STR);
        $result->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $result->bindParam(':image', $image, PDO::PARAM_STR);
        $result->execute();
        
        $data = $db->query('SELECT id FROM users WHERE phone="'.$number.'"');
        $data->bindParam(':phone', $number, PDO::PARAM_STR);
        $data->setFetchMode(PDO::FETCH_ASSOC);
        $row = $data->fetch();

        return $row['id'];
    }

    public static function checkName($name)
    {
        if(strlen($name) > 3){
            return true;
        }
        return false;
    }

    public static function checkPassword($password)
    {
        if( strlen($password) >= 6 ){
            return true;
        }
        return false;
    }

    public static function checkNumber($number)
    {
        if( strlen($number) == 19 ) {
            return true;
        }
        return false;
    }

    public static function checkNumberExists($number)
    {
        $db = Db::getConnection();

        $sql = $db->query("SELECT * FROM users");
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sql->fetchAll();

        for( $i = 0; $i < count($result); $i ++ ){
            if( $result[$i]['phone'] == $number ){
                return true;
            }
        }
        return false;
        exit;
    }

    public static function isExistsProfile($password, $name) // asl password 159753
    {
        $db = Db::getConnection();

        $sql = $db->query("SELECT id, name, password FROM users");
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sql->fetchAll();
        $pass = '';

        for ( $i = 0;  $i < count($result) ;  $i ++ ) {
            $pass = $result[$i]['password'];

            if(password_verify($password, $pass) && $result[$i]['name'] == $name) {
                return $result[$i]['id'];
            }  
        }

        return false;
    }

    public static function getUser($id)
    {
        $db = Db::getConnection();

        $sql = $db->query("SELECT * FROM users WHERE id = $id");
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sql->fetch();
        
        return $result;
    }

    public static function getTeamUsers()
    {
        $db = Db::getConnection();
        $sql = "SELECT * FROM users WHERE roll = 'admin' OR roll = 'operator'";
        $result = $db->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        
        return $result->fetchAll();       
    }

    public static function getUserPosts($user_id)
    {
        $db = Db::getConnection();

        $sql = "SELECT * FROM posts WHERE user_id = $user_id";
        $result = $db->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        return $result->fetchAll();
    }

    public static function updateUserData($user_id, $name, $image, $pass)
    {
        $db = Db::getConnection();
        if( empty($pass) ){
            $sql = "UPDATE users SET name = '$name', image = '$image' WHERE id = $user_id";
        } else {
            $password = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET name = '$name', image = '$image', password = '$password' WHERE id = $user_id";
        }
        $result = $db->query($sql);

        return true;
    }

    public static function deleteAccount($user_id)
    {
        $user_id = intval($user_id);
        $db = Db::getConnection();
        $photos = $db->query("SELECT image FROM users WHERE id = $user_id");
        $photos->setFetchMode(PDO::FETCH_ASSOC);
        $photos = $photos->fetch();

        if( $photos['image'] != 'no-post.png' ) {
            unlink('upload/' . $photos['image']);
        }

        $db->query("DELETE FROM users WHERE id = $user_id");
        
        if(isset($_SESSION['user'])){
            unset($_SESSION['user']);
        }

        return true;
    }
}