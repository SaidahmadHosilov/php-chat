<?php

class User
{
    public static function isOnlineUser($id)
    {
        $db = Db::getConnection();
        $first = $db->query("SELECT user_id FROM is_online WHERE user_id = '$id'");
        $first->setFetchMode(PDO::FETCH_ASSOC);
        $first = $first->fetch();
        // $date = date('Y-m-d H:i:s');
        if( $first ){
            $sql = "UPDATE is_online SET last_time = 'online' WHERE user_id = $id";
        } else {
            $sql = "INSERT INTO is_online(user_id, last_time) VALUES('$id', 'online')";
        }
        $db->query($sql);

        return true;
    }

    public static function isNotOnlineUser($id)
    {
        $db = Db::getConnection();
        $first = $db->query("SELECT user_id FROM is_online WHERE user_id = '$id'");
        $first->setFetchMode(PDO::FETCH_ASSOC);
        $first = $first->fetch();
        // $date = date('Y-m-d H:i:s');
        if( $first ){
            $sql = "UPDATE is_online SET last_time = 'offline' WHERE user_id = $id";
        } else {
            $sql = "INSERT INTO is_online(user_id, last_time) VALUES('$id', 'offline')";
        }
        $db->query($sql);

        return true;
    }

    public static function deleteChat($id, $time)
    {
        $db = Db::getConnection();
        $sql = "DELETE FROM sms WHERE user_id = $id AND time = '$time'";
        $db->query($sql);
        return true;
    }

    public static function searchPeople($name)
    {
        $db = Db::getConnection();
        $sql = "SELECT * FROM users WHERE name LIKE '%$name%'";
        $result = $db->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        return $result->fetchAll();
    }

    public static function getUserImage($userId)
    {
        $db = Db::getConnection();
        $sql = "SELECT * FROM users WHERE id = $userId";
        $result = $db->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        return $result->fetch();
    } 

    public static function insertDataToChat($userId, $userToId, $message, $images)
    {
        $db = Db::getConnection();
        $date = date('Y-m-d H:i:s');
        $images = base64_encode($images);
        $sql = "INSERT INTO sms( user_id, user_to_id, text, images, time)
        VALUES ( :uid, :utoid, :msg, :img, :date )";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':utoid', $userToId, PDO::PARAM_INT);
        $stmt->bindParam(':msg', $message, PDO::PARAM_STR);
        $stmt->bindParam(':img', $images, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();
    }

    public static function getAllSMS($userId, $userToId)
    {
        $db = Db::getConnection();
        $arr = [];
        if( $userId == $userToId ){
            $sql = "SELECT * FROM sms 
                WHERE user_id = $userId AND user_to_id = $userToId ORDER BY time DESC";
        } else {
            $sql = "SELECT * FROM sms WHERE user_id = $userId AND user_to_id = $userToId
                UNION ALL 
                SELECT * FROM sms WHERE user_id = $userToId AND user_to_id = $userId 
                ORDER BY time DESC";
        }
        
        $result = $db->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $arr = [];
        $i = 0;

        while($row = $result->fetch()){
            $arr[$i]['id'] = $row['id'];
            $arr[$i]['user_id'] = $row['user_id'];
            $arr[$i]['user_to_id'] = $row['user_to_id'];
            $arr[$i]['text'] = $row['text'];
            $arr[$i]['images'] = base64_decode($row['images']);
            $arr[$i]['time'] = $row['time'];
            $i++;
        }
        
        
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
        $userId = $_SESSION['user'];
        $sql = "SELECT user_id as id FROM sms WHERE user_id = $userId OR user_to_id = $userId 
                GROUP BY user_id
                UNION
                SELECT user_to_id FROM sms WHERE user_id = $userId OR user_to_id = $userId 
                GROUP BY user_to_id";
        $ides = $db->query($sql);
        $ides->setFetchMode(PDO::FETCH_ASSOC);
        $ides = $ides->fetchAll();

        $stmt = $db->query("SELECT * FROM users");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        // $stmt = $stmt->fetchAll();
        $i = 0;
        $arr = array();
        
        while($row = $stmt->fetch()){
            for($k = 0; $k < count($ides); $k++ ){
                if( $ides[$k]['id'] == $row['id'] ){
                    $arr[$i]['id'] = $row['id'];
                    $arr[$i]['name'] = $row['name'];
                    $arr[$i]['phone'] = $row['phone'];
                    $arr[$i]['image'] = $row['image'];
                }
            }
            $i++;
        }

        return $arr;
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