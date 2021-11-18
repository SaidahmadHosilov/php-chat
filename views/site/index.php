<!-- Header -->
<?php require_once( ROOT . '/views/layouts/header.php' ); ?>
<!-- !Header -->

    <div class="container">
        <img src="/template/images/close.svg" class="close-menu">
        <img src="/template/images/hamburger-menu.svg" class="hamburger-menu">
        <div class="people-list">
            <form class="people-search" action="" method="POST">
                <div class="search-container">
                    <button class="search-img" type="submit">
                        <img src="/template/images/search.svg" class="search-icon">
                    </button>
                    <input type="search" name="people-search" placeholder="Search..." id="search-input">
                </div>
            </form>
            <div class="people">
                <ul>
                    <?php foreach( $users as $person ): ?>
                        <li>
                            <!-- <form action="#" method="POST"> -->
                                <a class="select-chat-user" 
                                data-userToId="<?=$person['id']?>" data-userId="<?=$_SESSION['user']?>">
                                    <div class="person-box">
                                        <img src="/upload/<?=$person['image']?>" alt="">
                                        <div class="per-info">
                                            <div class="name"><?=$person['name']?></div>
                                            <div class="status"> <div class="online-class"></div> online</div>
                                        </div>
                                    </div>
                                </a>
                            <!-- </form> -->
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="chat">
            <div class="header-chat-top">
                <div class="header-chat">
                    <?php if(!empty($_SESSION['user'])): ?>
                            <img src="/upload/<?=$user['image']?>">
                            <div class="per-info">
                                <div class="name"><?=$user['name']?></div>
                            </div>
                    <?php endif; ?>
                    <!-- <img src="/upload/no-image.png">
                        <div class="per-info">
                            <div class="name">Otabek</div>
                            <div class="status">Last seen recently</div>
                        </div> -->
                </div>
                <div class="control-panel">
                    <a>
                        <img class="light-mode" src="/template/images/sun.svg">
                        <img class="dark-mode" src="/template/images/moon.svg">
                    </a>
                    <a href="/profile/edit/<?=$_SESSION['user']?>">
                        <acronym title="Edit Profile">
                            <img src="/template/images/edit.svg">
                        </acronym>
                    </a>
                    <a href="/logout">
                        <acronym title="Logout">
                            <img src="/template/images/logout.svg">
                        </acronym>
                    </a>
                </div>
            </div>
            <div class="chat-content" id="chat-content">

                <!-- <div class="chat-to">
                    <div class="chat-cart">
                        <div class="chat-text">
                            <p>Hi Aiden, how are you? How is the project coming along?</p>
                            <div class="chat-time">
                                <span>10:10 AM, Today</span>
                            </div>
                            <div class="chat-arr"></div>
                        </div>
                        <img src="/upload/no-image.png" alt="">
                    </div>
                </div>-->
            </div>

            <form class="send-message" id="form-ajax-send">
                <div class="send-container">
                    <button id="send-message-form" 
                        data-userId="<?=$_SESSION['user']?>" name="submit" class="send-img">
                        <img src="/template/images/sent.svg" class="send-icon">
                    </button>
                    <input type="text" name="text" placeholder="Enter text here..." id="send-input">
                </div>
            </form>
        </div>
    </div>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('623bdc4243d5196d58af', {
        cluster: 'ap2'
    });

    var channel = pusher.subscribe('demo_pusher');
    channel.bind('chat-content', function(data) {
        var userInfo = data['message'].current_user;
        var userToInfo = data['message'].user_to_info;
        var allSMS = data['message'].all_sms;
        var outputUser = '';
        var output = '';

        outputUser +=   '<img data-id="'+ userToInfo.id +'" src="/upload/'+ userToInfo.image +'">'+
                            '<div class="per-info">'+
                                '<div class="name">'+ userToInfo.name +'</div>'+
                                '<div class="status">'+ userToInfo.phone +'</div>'+
                            '</div>';

        for( const sms of allSMS ){
            if( userInfo.id == sms.user_id ){
                output += '<div class="chat-me">'+
                                '<div class="chat-cart">'+
                                    '<div class="chat-text">'+
                                        '<p>' + sms.text + '</p>'+
                                        '<div class="chat-time">'+
                                            '<span>' + sms.time + '</span>'+
                                        '</div>'+
                                        '<div class="chat-arr"></div>'+
                                    '</div>'+
                                    '<img src="/upload/'+ userInfo.image +'" alt="">'+
                                '</div>'+
                            '</div>';
            } else {
                output += '<div class="chat-to">'+
                                '<div class="chat-cart">'+
                                    '<div class="chat-text">'+
                                        '<p>' + sms.text + '</p>'+
                                        '<div class="chat-time">'+
                                            '<span>' + sms.time + '</span>'+
                                        '</div>'+
                                        '<div class="chat-arr"></div>'+
                                    '</div>'+
                                    '<img src="/upload/'+ userToInfo.image +'" alt="">'+
                                '</div>'+
                            '</div>';
            }
        }

        $('#send-input').val("");
        $('.header-chat').html(outputUser); 
        $('#chat-content').html(output); 
});
</script>


<!-- <script type="text/javascript">
    // selectChat
    // $(document).ready(function(){
    //     $('.select-chat-user').each(function(){
    //         $(this).on('click', function(ev){
    //             $.ajax({
    //                 url: "/select/chat",
    //                 method: 'GET',
    //                 data:{
    //                     fromUser: <?=$_SESSION['user']?>,
    //                     toUser: $(this).attr('data-usertoid'),
    //                     // message: $('#send-input').val(),
    //                 },
    //                 dataType: 'text',
    //                 success: function(data){
    //                     data = jQuery.parseJSON(data)
    //                     $(".header-chat").html(data[0]);
    //                     $(".chat-content").html(data[1]);
    //                 }
    //             })
    //             ev.preventDefault();
    //         })
    //     })
    // });
    // // addChat
    // $(document).ready(function(){
    //     $('#send-message-form').on('click', function(ev){
    //         $.ajax({
    //             url: "/insert/chat",
    //             method: 'GET',
    //             data:{
    //                 fromUser: <?=$_SESSION['user']?>,
    //                 toUser: $('.header-chat img').attr('data-id'),
    //                 message: $('#send-input').val(),
    //             },
    //             dataType: 'text',
    //             success: function(data){
    //                 $("#send-input").val("");
    //             }
    //         })
    //         ev.preventDefault();
    //     })
    // });
</script> -->
 
<script src="/template/app.js"></script>
<script>
    <?php if($darkMode == 'on'): ?>
        console.log(document.querySelector('.dark-mode').click());
    <?php endif; ?>
</script>
<!-- Footer -->
<?php require_once( ROOT . '/views/layouts/footer.php' ); ?>
<!-- !Footer --> 