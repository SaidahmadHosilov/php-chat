<?php

class UserController
{
    public function actionRegister()
    {
        if(isset($_SESSION['user'])){
            unset($_SESSION['user']);
        }        

        $name = '';
        $phoneNumber = '';
        $password = '';
        $result = false;

        if(isset($_POST['submit'])){
            $name = $_POST['name'];
            $phoneNumber = $_POST['phone-number'];
            $password = $_POST['password'];

            $image = $_FILES['image']['name'] == '' ? 'no-image.png' : $_FILES['image']['name'];

            $errors = false;

            if(!User::checkName($name)){
                $errors['name'] = 'Ism 4 ta yoki undan ko\'p belgiga ega bo\'lishi kerak';
            }

            if(!User::checkPassword($password)){
                $errors['password'] = "Parol 6 ta belgidan yoki undan ko'p belgidan tashkil topishi kerak!";
            }

            if(!User::checkNumber($phoneNumber)){
                $errors['number'] = "Telefon raqamni to'g'ri kiriting!";
            }

            if(User::checkNumberExists($phoneNumber)){
                $errors['number'] = "Kechirasiz, bunday telefon raqam mavjud ";
            }

            // image upload
            $target_dir = "upload/";
            $target_file = $target_dir . basename($image);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            if( $_FILES['image']['name'] != '' ){
                // Check if image file is a actual image or fake image
                if(isset($_POST["submit"]) && empty($_FILES["image"])) {
                    $check = getimagesize($_FILES["image"]["tmp_name"]);
                    if($check !== false) {
                        $errors["image"] = "Bu rasm - " . $check["mime"] . ".";
                    } else {
                        $errors["image"] = "Bu rasm emas!";
                    }
                }

                // Check if file already exists
                if (file_exists($target_file)) {
                    $errors["image"] = "Kechirasiz, bunday rasm mavjud!";
                }

                // Check file size
                if ($_FILES["image"]["size"] > 5000000) {
                    $errors["image"] = "Kechirasiz, sizning rasmingiz katta hajmli!";
                }

                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                    $errors["image"] = "Kechirasiz, faqat JPG, JPEG, PNG & GIF tiplarni ishlatish mumkin.";
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if (!empty($errors)) {
                    // echo "Kechirasiz, rasm yuklanmadi, qaytadan urinib ko'ring!";
                    // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        // echo "Was done!";
                        // echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
                    } else {
                        // echo "Sorry!";
                        // echo "Sorry, there was an error uploading your file.";
                    }
                }
            }
            
            //image upload finish

            if($errors == false){
                
                $_SESSION['user'] = User::register( $name, $phoneNumber, $password, $image );
                $_SESSION['name'] = $name;
                // $_SESSION['products'] = [];
                header("Location: /");
            }
        }
        require_once( ROOT . "/views/user/register.php");

        return true;
    }

    public function actionLogin()
    {
        $name = '';
        $password = '';
        $result = false;
        
        if(isset($_POST['submit'])){

            $name = $_POST['name'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $errors = false;
            
            if(!User::checkName($name)){
                $errors['name'] = 'Ism 4 ta yoki undan ko\'p belgiga ega bo\'lishi kerak';
            }

            if(!User::checkPassword($password)){
                $errors['password'] = "Parol 6 ta belgidan yoki undan ko'p belgidan tashkil topishi kerak!";
            }

            $userId = User::isExistsProfile( $password, $name );

            if( !$userId == false && empty($errors) ){
                $_SESSION['user'] = $userId; 
                // $_SESSION['products'] = Cart::getProductCartById($_SESSION['user']);
                header("Location: /");
            } else {
                $errors['isNotExist'] = 'Login Yoki Parol Xato';
            }
        }

        require_once( ROOT . "/views/user/login.php");

        return true;
    }

    public function actionLogout()
    {
        if(isset($_SESSION['user'])){
            unset($_SESSION['user']);
        }

        header("Location: /login");
    }

    public function actionProfileView($user_id)
    {
        $user = User::getUser($user_id);
        $userPosts = User::getUserPosts($user_id);
        $currentUser = $_SESSION['user'] ?? '';

        require_once( ROOT . "/views/user/view.php");

        return true;
    }

    public function actionProfileEdit($user_id)
    {
        $user = User::getUser($user_id);

        if($_SESSION['user'] != $user_id){
            echo "404 Not Found";
            exit;
        }

        $name = '';

        if(isset($_POST['submit'])){
            $name = $_POST['name'] ?? '';
            $password = $_POST['password'] ?? '';
            $passwordRepeat = $_POST['passwordRepeat'] ?? '';
            
            $image = $_FILES['image']['name'] == '' ? $user['image'] : $_FILES['image']['name'];
            $oldPhoto = $user['image'];

            $errors = false;
            if(!User::checkName($name)){
                $errors['name'] = 'Ism 4 ta yoki undan ko\'p belgiga ega bo\'lishi kerak';
            }

            if(!User::checkNewPassword($password, $passwordRepeat)){
                $errors['password'] = 'Yangi parol kiritishda xatolik!';
            }

            if($image != $user['image']){
                // image upload
                $target_dir = "upload/";
                $target_file = $target_dir . basename($image);
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                if( $_FILES['image']['name'] != '' ){
                    // Check if image file is a actual image or fake image
                    if(isset($_POST["submit"]) && empty($_FILES["image"])) {
                        $check = getimagesize($_FILES["image"]["tmp_name"]);
                        if($check !== false) {
                            $errors["image"] = "Bu rasm - " . $check["mime"] . ".";
                        } else {
                            $errors["image"] = "Bu rasm emas!";
                        }
                    }

                    // Check if file already exists
                    if (file_exists($target_file)) {
                        $errors["image"] = "Kechirasiz, bunday rasm mavjud!";
                    }

                    // Check file size
                    if ($_FILES["image"]["size"] > 5000000) {
                        $errors["image"] = "Kechirasiz, sizning rasmingiz katta hajmli!";
                    }

                    // Allow certain file formats
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif" ) {
                        $errors["image"] = "Kechirasiz, faqat JPG, JPEG, PNG & GIF tiplarni ishlatish mumkin.";
                        $uploadOk = 0;
                    }

                    // Check if $uploadOk is set to 0 by an error
                    if ( $errors != false ) {
                        // echo "Kechirasiz, rasm yuklanmadi, qaytadan urinib ko'ring!";
                        // if everything is ok, try to upload file
                    } else {
                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                            // echo "Was done!";
                            // echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
                        } else {
                            // echo "Sorry!";
                            // echo "Sorry, there was an error uploading your file.";
                        }
                    }
                }
                
                if(file_exists('upload/' . basename($oldPhoto)) && $oldPhoto != 'no-image.png'){
                    unlink('upload/' . basename($oldPhoto));
                }
                //image upload finish
            } 

            if($errors == false){
                User::updateUserData($user['id'], $name, $image, $password);
                header("Location: /");
            }
        }

        require_once( ROOT . "/views/user/edit.php");

        return true;
    }

    public function actionProfileDelete($user_id)
    {
        User::deleteAccount(intval($user_id));

        header("Location: /register");
        exit;
    }
}