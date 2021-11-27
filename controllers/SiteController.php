<?php

use function GuzzleHttp\Promise\all;

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
        $allUsers = User::getAllUsers();
        $users = User::getUsers();
        $groups = Chat::getGroups();
        $result = array_merge($users, $groups);
        
        require_once( ROOT . '/views/site/index.php' );
        return true;
    }

    public function actionSelectGroup()
    {
        $userId = $_GET['user_id'] ?? '';
        $grId = $_GET['gr_id'] ?? '';
        $arr = [];

        $grInfo = Chat::getGroupInfo($grId);
        $users = explode(',',json_decode($grInfo['users']));
        $getAllSMS = Chat::getAllGroupSMS($grId);
        
        array_push($arr, $grInfo);
        array_push($arr, $users);
        array_push($arr, $getAllSMS);
        array_push($arr, $_SESSION['user']);

        echo (json_encode($arr));
        exit;
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
        if( $_POST['is_group'] ){
            $userId = $_POST['userId'] ?? '';
            $grId = $_POST['gr_id'] ?? '';
            $grUsers = $_POST['gr_users'] ?? '';
            $images = $_POST['images'] ?? '';
            $text = $_POST['text'] ?? '';

            Chat::insertDataToGroupChat($userId, $grId, $text, $images);

            $currentGroupInfo = Chat::getGroupInfo($grId);
            $getAllSMS = Chat::getAllGroupSMS($grId);

            $app_id = '1299406';
            $app_key = '623bdc4243d5196d58af';
            $app_secret = 'd444b96446d0a817f770';
            $app_cluster = 'ap2';

            $pusher = new Pusher\Pusher($app_key, $app_secret, $app_id, ['cluster' => $app_cluster]);

            $data['message'] = array(
                'current_group' => $currentGroupInfo,
                'all_sms' => $getAllSMS,
                'user_id' => $userId,
                'gr_users' => $grUsers
            );

            $pusher->trigger('demo_pusher', 'group-chat-content', $data);
            exit;
        } else {
            if(!isset($_POST['userToId']) || $_POST['userToId'] == "null"){
                echo 'error';
                exit;
            }

            $userId = $_POST['userId'] ?? '';
            $userToId = $_POST['userToId'] ?? '';
            $images = $_POST['images'] ?? '';
            $text = $_POST['text'] ?? '';

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
    }

    public function actionDeleteGrChat()
    {
        $userId = $_GET['user_id'] ?? '';
        $grId = $_GET['gr_id'] ?? '';
        $currentTime = $_GET['time'] ?? '';
        $user = $_SESSION['user'];
        
        Chat::deleteGroupSMS($userId, $grId, $currentTime);
        $getGrSMS = Chat::getAllGroupSMS($grId);

        echo json_encode([$getGrSMS, $user]);
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