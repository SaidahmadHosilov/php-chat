<?php

class GroupController
{
    public function actionDeleteGroup($gr_id)
    {   
        if(Chat::deleteGroupById($gr_id)){
            header("Location: /");
        } else {
            echo "O'chirishda xatolik sodir bo'ldi!";
            exit;
        }
        return true;
    }

    public function actionCreateGroup()
    {
        $userId = $_SESSION['user'] ?? '';
        $image = $_FILES['file']['name'] ?? '';
        $name = $_POST['name'] ?? '';
        $users = $_POST['users'] ?? '';

        if( $image != '' ){
            if( $_FILES['file']['size'] > 4000000 ){
                echo json_encode(['error']);
                exit;
            }
        }

        if(!empty($_FILES))
        {
                        
            if(is_uploaded_file($_FILES['file']['tmp_name']))
            {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $allow_ext = array('jpg', 'png', 'svg', 'jpeg');
                if(in_array($ext, $allow_ext))
                {
                    $_source_path = $_FILES['file']['tmp_name'];
                    $target_path = 'upload/' . $_FILES['file']['name'];
                    move_uploaded_file($_source_path, $target_path);
                    //echo $ext;
                }
            }
        }
        if($image == '') $image = 'no-image.png';
        Chat::insertNewGroupChat($userId, $name, $image, $users);
        echo json_encode(['success']);
        exit;
    }

    public function actionAddMember()
    {
        $userId = $_GET['user_id'] ?? '';
        $user = User::getUser($userId);
        
        echo json_encode($user);
        exit;
    }
}