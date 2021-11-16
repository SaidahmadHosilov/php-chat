<!-- Header -->
<?php require_once( ROOT . '/views/layouts/header.php' ); ?>
<!-- !Header -->

    <div class="container-form">
        <form method="POST" action="" id="signup" enctype="multipart/form-data">
            <div class="header">
                <h3>Edit Your Profile</h3>
                <!-- <p>You want to fill out this form</p> -->
            </div>
            <div class="sep"></div>
            <div class="inputs">                
                <input type="text" class="form-name-register" value="<?=$user['name']?>" name="name" placeholder="Name..." autofocus />
                <p class="errors"><?=$errors['name'] ?? ''?></p>
                
                <input type="password" value="" placeholder="Password..." name="password" />
                <input type="password" value="" placeholder="Repeat password..." name="passwordRepeat" />
                <p class="errors"><?=$errors['password'] ?? ''?></p>

                <input type="file" name="image"/>
                <p class="errors"><?=$errors['image'] ?? ''?></p>
                
                <img src="/upload/<?=$user['image']?>" style="width:100%; height:300px; object-fit:cover;">
                
                <button name="submit" type="submit" id="submit">Update</button>
                <a href="/profile/delete/<?=$_SESSION['user']?>" id="submit" style="color: #fff; background: red" 
                onclick="return confirm('Your profile will be deleted absolutely?');">Delete Your Account</a>
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/template/jquery.maskedinput.js"></script>
    <script>
        $(document).ready(function($){
            $.mask.definitions['9'] = '';
            $.mask.definitions['x'] = '[0-9]';
            $("#phone-number").mask("+998 (xx) xxx-xx-xx");
        })
    </script>
    
<!-- Footer -->
<?php require_once( ROOT . '/views/layouts/footer.php' ); ?>
<!-- !Footer --> 