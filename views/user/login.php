<!-- Header -->
<?php require_once( ROOT . '/views/layouts/header.php' ); ?>
<!-- !Header -->

    <div class="container-form">
        <form method="POST" action="" id="signup">
            <div class="header">
                <h3>Sign In</h3>
                <!-- <p>You want to fill out this form</p> -->
            </div>
            <div class="sep"></div>
            <div class="inputs">
                <input type="text" class="form-name-login" value="<?=$name?>" name="name" placeholder="Name..." autofocus />
                <p class="errors"><?=$errors['name'] ?? '' ?></p>
                <input type="password" placeholder="Password..." value="<?=$password?>" name="password" />
                <p class="errors"><?=$errors['password'] ?? '' ?></p>
                <p class="errors">
                    <?php 
                        if(empty($errors['name']) && empty($errors['password'])){
                            echo $errors['isNotExist'] ?? ''; 
                        } 
                    ?>
                </p>

                <a>Yangi bo'lsangiz <a style="color:blue; font-weight:bold" href="/register">bu yerni</a>  bosing</a>
                <button type="submit" name="submit" id="submit" href="#">SIGN IN</button>
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="./template/jquery.maskedinput.js"></script>
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