<?php

class Chat
{
    public static function deleteGroupById($gr_id)
    {
        $db = Db::getConnection();

        $stmt2 = $db->prepare("DELETE FROM groups_chat WHERE group_id = :id");
        $stmt2->bindParam(':id', $gr_id, PDO::PARAM_INT);
        $stmt2->execute();

        $stmt = $db->prepare("DELETE FROM groups WHERE id = :id");
        $stmt->bindParam(':id', $gr_id, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    }

    public static function deleteGroupSMS($userId, $grId, $currentTime)
    {
        $db = Db::getConnection();
        $sql = "DELETE FROM groups_chat 
                WHERE group_id = :gr_id AND user_id = :uId AND time = :time";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':gr_id', $grId, PDO::PARAM_INT);
        $stmt->bindParam(':uId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':time', $currentTime, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public static function insertDataToGroupChat($userId, $grId, $text, $images)
    {
        $db = Db::getConnection();
        $date = date("Y-m-d H:i:s");
        $images = base64_encode($images);

        $sql = "INSERT INTO groups_chat(group_id, user_id, sms, image, time)
                VALUES(:group_id, :uId, :sms, :img, :time)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':group_id', $grId, PDO::PARAM_INT);
        $stmt->bindParam(':uId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':sms', $text, PDO::PARAM_STR);
        $stmt->bindParam(':img', $images, PDO::PARAM_STR);
        $stmt->bindParam(':time', $date, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public static function getAllGroupSMS($grId)
    {
        $db = Db::getConnection();
        $sql = "SELECT groups_chat.*, users.name as uname, users.image as uimage 
        FROM groups_chat INNER JOIN users ON users.id = groups_chat.user_id 
        WHERE groups_chat.group_id = :id
        ORDER BY time DESC";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $grId, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $arr = [];
        $i = 0;

        while($row = $stmt->fetch()){
            $arr[$i]['id'] = $row['id'];
            $arr[$i]['group_id'] = $row['group_id'];
            $arr[$i]['user_id'] = $row['user_id'];
            $arr[$i]['sms'] = $row['sms'];
            $arr[$i]['image'] = base64_decode($row['image']);
            $arr[$i]['time'] = $row['time'];
            $arr[$i]['uname'] = $row['uname'];
            $arr[$i]['uimage'] = $row['uimage'];
            $i++;
        }
        
        return $arr;
    }

    public static function getGroupInfo($gr_id)
    {
        $db = Db::getConnection();
        $sql = "SELECT * FROM groups WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $gr_id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetch();
    }

    public static function insertNewGroupChat($user_id, $name, $image, $users)
    {
        $db = Db::getConnection();
        $users = json_encode($users); 
        $date = time();
        
        $sql = "INSERT INTO groups(name, image, users, time, admin)
                VALUES(:name, :image, :users, :time, :admin)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':users', $users, PDO::PARAM_STR);
        $stmt->bindParam(':time', $date, PDO::PARAM_STR);
        $stmt->bindParam(':admin', $user_id, PDO::PARAM_STR);
        $stmt->execute();
    }

    public static function getGroups()
    {
        $db = Db::getConnection();
        $sql = "SELECT * FROM groups";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $arr = array();

        foreach( $stmt as $st ){
            $st['users'] = explode(',', json_decode($st['users']));
            
            for($i = 0; $i < count($st['users']); $i++){
                if( $_SESSION['user'] == $st['users'][$i] ) array_push($arr, $st);
            }
        }

        return $arr;
    }
}