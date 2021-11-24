<?php

class SiteController
{
    public function actionIndex()
    {
        $darkMode = $_GET['dark_mode'] ?? '';

        if(empty($_SESSION['user']) ){
            header("Location: /login");
        } else {
            $user = User::getUser($_SESSION['user']);
        }

        $users = User::getUsers();
        
        require_once( ROOT . '/views/site/index.php' );
        return true;
    }

    public function actionSelectChat()
    {
        $userId = $_GET['userId'] ?? '';
        $userToId = $_GET['userToId'] ?? '';
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
        if(!isset($_POST['userToId']) || $_POST['userToId'] == "null"){
            echo 'error';
            exit;
        }

        $userId = $_POST['userId'] ?? '';
        $userToId = $_POST['userToId'] ?? '';
        $images = $_POST['images'] ?? '';
        $text = $_POST['text'] ?? '';

        // $text = preg_replace('/(%2B)+/i', '+', $text);
        // $text = preg_replace('/(%2F)+/i', '/', $text);
        // $text = preg_replace('/(%27)+/i', '\'', $text);

        User::insertDataToChat($userId, $userToId, $text, $images);

        $currentUserImage = User::getUserImage($userId);
        $userToInfo = User::getUserToInfo($userToId);
        $getAllSMS = User::getAllSMS($userId, $userToId);

        $app_id = '1299406';
        $app_key = '623bdc4243d5196d58af';
        $app_secret = 'd444b96446d0a817f770';
        $app_cluster = 'ap2';

        $pusher = new Pusher\Pusher($app_key, $app_secret, $app_id, ['cluster' => $app_cluster]);

        $data['message'] = array(
            'current_user' => $currentUserImage,
            'user_to_info' => $userToInfo,
            'all_sms' => $getAllSMS,
        );

        $pusher->trigger('demo_pusher', 'chat-content', $data);
        exit;
    }

    public function actionDeleteChat()
    {
        $currentId = $_GET['sms_id'] ?? '';
        $toId = $_GET['toId'] ?? '';
        $currentTime = $_GET['time'] ?? '';
        User::deleteChat($currentId, $currentTime);

        $currentUserImage = User::getUserImage($currentId);
        $userToInfo = User::getUserToInfo($toId);
        $getAllSMS = User::getAllSMS($currentId, $toId);

        echo json_encode([$currentUserImage, $getAllSMS, $userToInfo]);
        exit;       
    }
}