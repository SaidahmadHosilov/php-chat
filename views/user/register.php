<!-- Header -->
<?php require_once( ROOT . '/views/layouts/header.php' ); ?>
<!-- !Header -->

    <div class="container-form">
        <form method="POST" action="" id="signup" enctype="multipart/form-data">
            <div class="header">
                <h3>Sign Up</h3>
                <!-- <p>You want to fill out this form</p> -->
            </div>
            <div class="sep"></div>
            <div class="inputs">
                <input type="text" class="form-name-register" value="<?=$name?>" name="name" placeholder="Name..." autofocus />
                <p class="errors"><?=$errors['name'] ?? ''?></p>
                <input type="tel" id="phone-number" value="<?=$phoneNumber?>" placeholder="Phone number..." name="phone-number" autofocus />
                <p class="errors"><?=$errors['number'] ?? ''?></p>
                <input type="password" value="<?=$password?>" placeholder="Password..." name="password" />
                <p class="errors"><?=$errors['password'] ?? ''?></p>
                <input type="file" name="image"/>
                <p class="errors"><?=$errors['image'] ?? ''?></p>
                <!-- <div class="checkboxy">
                    <input name="cecky" id="checky" value="1" type="checkbox" /><label class="terms">I accept the terms of use</label>
                </div> -->
                <a href="login">Avval ro'yxatdan o'tgan bo'lsangiz bu yerni bosing</a>
                <button name="submit" type="submit" id="submit">SIGN UP</button>
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