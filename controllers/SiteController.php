<?php

class SiteController
{
    public function actionIndex()
    {
        if(empty($_SESSION['user']) ){
            header("Location: /login");
        } else {
            $user = User::getUser($_SESSION['user']);
        }
        // echo "<pre>";
        // var_dump($user);
        // echo "</pre>";
        // exit;
        $users = User::getUsers();

        require_once( ROOT . '/views/site/index.php' );

        return true;
    }

    public function actionSelectChat()
    {
        $userId = $_POST['userId'] ?? '';
        $userToId = $_POST['userToId'] ?? '';
        $arr = [];

        $currentUserImage = User::getUserImage($userId);
        $userToInfo = User::getUserToInfo($userToId);
        
        $getAllSMS = User::getAllSMS($userId, $userToId);

        array_push($arr, $userToInfo);
        array_push($arr, $getAllSMS);
        array_push($arr, $currentUserImage);

        echo (json_encode($arr));
        exit;
    }

    public function actionSendChat()
    {
        $userId = $_POST['userId'] ?? '';
        $userToId = $_POST['userToId'] ?? '';
        $message = $_POST['text'] ?? '';
        $arr = [];

        User::insertDataToChat($userId, $userToId, $message);

        $userToInfo = User::getUserToInfo($userToId);
        
        $getAllSMS = User::getAllSMS($userId, $userToId);

        array_push($arr, $userToInfo);
        array_push($arr, $getAllSMS);

        echo (json_encode($arr));
        exit;
    }
}