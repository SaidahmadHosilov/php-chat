<!-- Header -->
<?php require_once( ROOT . '/views/layouts/header.php' ); ?>
<!-- !Header -->

    <div class="modal-group-container">
        <div class="modal-main">
            <div class="modal-in">
                <div class="modal-header">
                    <img src="/template/images/close.svg">
                </div>
                <div class="modal-body">
                    <h1>Create Group</h1>
                    <form action="" method="POST">
                        <label for="grname">Name: </label>
                        <input type="text" name="name" id="grname">
                        <label for="grpic" class="add-img-group">Picture: 
                            <img src="/template/images/add-image.svg" alt="">
                        </label>
                        <input type="file" accept="png, jpg, jpeg, svg" name="grpic" id="grpic">
                        <label for="grmembers">Add Member:</label>
                        <select name="grmembers" id="grmembers">
                            <option value="">...</option>
                            <?php 
                                foreach($allUsers as $us): 
                                    if( $us['id'] != $_SESSION['user'] ):
                            ?>
                                    <option value="<?=$us['id']?>"><?=$us['name']?></option>
                            <?php
                                    endif; 
                                endforeach; 
                            ?>
                        </select>
                        <div class="members-list">

                        </div>
                        <button type="submit" data-id="<?=$_SESSION['user']?>" name="submit" id="create-group-submit">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
            <div class="people" id="people-list-box">
                <ul>
                    <?php 
                        foreach( $result as $item ): 
                            if(!array_key_exists('admin', $item)):
                    ?>
                                <li>
                                    <a class="select-chat-user" 
                                    data-userToId="<?=$item['id']?>" data-userId="<?=$_SESSION['user']?>">
                                        <div class="person-box">
                                            <img src="/upload/<?=$item['image']?>" alt="">
                                            <div class="per-info">
                                                <div class="name"><?=$item['name']?></div>
                                                <div class="status"> <div class="online-class"></div> online</div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                    <?php 
                        else: 
                            if( in_array($_SESSION['user'], $item['users']) ):
                    ?>
                                    <li>
                                        <a class="select-group-chat" 
                                        data-grId="<?=$item['id']?>" data-userId="<?=$_SESSION['user']?>">
                                            <img src="/template/images/group.svg">
                                            <div class="person-box">
                                                <img src="/upload/<?=$item['image']?>" alt="">
                                                <div class="per-info">
                                                    <div class="name"><?=$item['name']?></div>
                                                    <div class="status"> <?=count($item['users'])?> people </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                    <?php
                            endif;
                        endif; 
                    endforeach; 
                ?>
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
                </div>
                <div class="control-panel">
                    <a>
                        <img class="light-mode" src="/template/images/sun.svg">
                        <img class="dark-mode" src="/template/images/moon.svg">
                    </a>
                    <a id="group-modal">
                        <acronym title="Create Group">
                            <img src="/template/images/add-group.svg">
                        </acronym>
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
            <div class="chat-content" id="chat-content" data-id="<?=$_SESSION['user']?>">

            </div>

            <form class="send-message" id="form-ajax-send">
                <div class="for-pic"></div>
                <div class="send-container">
                    <button id="send-message-form" 
                        data-userId="<?=$_SESSION['user']?>" name="submit" class="send-img">
                        <img src="/template/images/sent.svg" class="send-icon">
                    </button>
                    <form id="uploadImage" method="post" enctype="multipart/form-data">
                        <label for="share-image"><img src="/template/images/share-image.svg" class="share-image"></label>
                        <input type="file" name="image-upload" id="share-image" accept=".jpg, .png, .svg" />
                    </form>
                    
                    <textarea type="text" name="text" placeholder="Enter text here..." id="send-input"></textarea>
                </div>
            </form>
        </div>
    </div>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#share-image').on('change', function(){
            var file_data = $('#share-image').prop('files')[0];   
            var form_data = new FormData();                  
            form_data.append('file', file_data);
            $.ajax(
            {
                type: "POST",
                dataType: 'text',  // <-- what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                url: '/image/send',
                data: form_data,
                success: function(info)
                {
                    $(".for-pic").append(info)
                }
            });
        });
    })
    

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('623bdc4243d5196d58af', {
        cluster: 'ap2'
    });

    var channel = pusher.subscribe('demo_pusher');

    channel.bind('group-chat-content', function(data) {
        var groupInfo = data['message'].current_group; // Saidahmad
        var allSMS = data['message'].all_sms;   // SMS user_id = S, user_to_id = K
        var userId = data['message'].user_id;   
        var grUsers = (data['message'].gr_users).split(',');   
        var output = '';
        var output2 = '';
        var query = document.location.search.substr(1);
        var queryObj = parseQuery(query);

        if( userId == <?=$_SESSION['user']?> ){
            for(let i = 0; i < grUsers.length; i++){
                if( grUsers[i] == userId ) grUsers.splice(i, 1)
            }
            output = '';
            for( const sms of allSMS ){
                if( <?=$_SESSION['user']?> == sms.user_id ){
                    output += '<div class="chat-me">'+
                                    '<div class="chat-cart">'+
                                        '<div class="chat-text" data-id="'+sms.user_id+'">'+
                                            '<a href="/delete/grChat" class="delete-sms-gr"><img src="/template/images/delete-sms.svg"></a>'+
                                            '<div class="images-chat">'+ sms.image +'</div>'+
                                            '<p>' + sms.sms + '</p>'+
                                            '<div class="chat-time">'+
                                                '<span>' + sms.time + '</span>'+
                                            '</div>'+
                                            '<div class="chat-arr"></div>'+
                                        '</div>'+
                                        '<img src="/upload/'+ sms.uimage +'" alt="">'+
                                    '</div>'+
                                '</div>';
                } else {
                    output += '<div class="chat-to">'+
                                    '<div class="chat-cart">'+
                                        '<div class="chat-text" data-id="'+sms.user_id+'">'+
                                            '<div class="images-chat">'+ sms.image +'</div>'+
                                            '<p>' + sms.sms + '</p>'+
                                            '<div class="chat-time">'+
                                                '<span>' + sms.time + '</span>'+
                                            '</div>'+
                                            '<div class="chat-arr"></div>'+
                                        '</div>'+
                                        '<img src="/upload/'+ sms.uimage +'" alt="">'+
                                    '</div>'+
                                '</div>';
                }
            }
            $('#chat-content').html(output);
            $('#send-input').val("");
        } else if(grUsers.includes((<?=$_SESSION['user']?>).toString())){
            var checkInGroup = document.querySelector('.header-chat img').getAttribute('data-name');
            var checkInGroupId = document.querySelector('.header-chat img').getAttribute('data-id');
            if( checkInGroup == 'group' && groupInfo.id == checkInGroupId ){
                output = '';
                for( const sms of allSMS ){
                    if( <?=$_SESSION['user']?> == sms.user_id ){
                        output += '<div class="chat-me">'+
                                        '<div class="chat-cart">'+
                                            '<div class="chat-text" data-id="'+sms.user_id+'">'+
                                                '<a href="/delete/grChat" class="delete-sms-gr"><img src="/template/images/delete-sms.svg"></a>'+
                                                '<div class="images-chat">'+ sms.image +'</div>'+
                                                '<p>' + sms.sms + '</p>'+
                                                '<div class="chat-time">'+
                                                    '<span>' + sms.time + '</span>'+
                                                '</div>'+
                                                '<div class="chat-arr"></div>'+
                                            '</div>'+
                                            '<img src="/upload/'+ sms.uimage +'" alt="">'+
                                        '</div>'+
                                    '</div>';
                    } else {
                        output += '<div class="chat-to">'+
                                        '<div class="chat-cart">'+
                                            '<div class="chat-text" data-id="'+sms.user_id+'">'+
                                                '<div class="images-chat">'+ sms.image +'</div>'+
                                                '<p>' + sms.sms + '</p>'+
                                                '<div class="chat-time">'+
                                                    '<span>' + sms.time + '</span>'+
                                                '</div>'+
                                                '<div class="chat-arr"></div>'+
                                            '</div>'+
                                            '<img src="/upload/'+ sms.uimage +'" alt="">'+
                                        '</div>'+
                                    '</div>';
                    }
                }
                $('#chat-content').html(output);
            }
        }

        for(const deleteBtn of document.querySelectorAll('.delete-sms-gr')){
            deleteBtn.addEventListener('click', deleteGrChat);
        }
        if(queryObj.hasOwnProperty('dark_mode') ){
            if( queryObj.dark_mode == 'on' ){
                document.querySelector('.dark-mode').click();
            } 
        }
    });

    channel.bind('chat-content', function(data) {
        var userInfo = data['message'].current_user; // Saidahmad
        var userToInfo = data['message'].user_to_info;  // Kamoliddin
        var allSMS = data['message'].all_sms;   // SMS user_id = S, user_to_id = K
        var output = '';
        var output2 = '';

        if( userInfo.id == <?=$_SESSION['user']?> ){
            var spId = $('.header-chat img').attr('data-id');
            if( spId == userToInfo.id ){
                output = '';
                for( const sms of allSMS ){
                    if( spId == sms.user_to_id ){
                        output += '<div class="chat-me">'+
                                        '<div class="chat-cart">'+
                                            '<div class="chat-text" data-id="'+sms.user_id+'">'+
                                                '<a href="/delete/chat" class="delete-sms"><img src="/template/images/delete-sms.svg"></a>'+
                                                '<div class="images-chat">'+ sms.images +'</div>'+
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
                                                '<div class="images-chat">'+ sms.images +'</div>'+
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
            }
            $('#chat-content').html(output);
            $('#send-input').val("");
            for(const deleteBtn of document.querySelectorAll('.delete-sms')){
                deleteBtn.addEventListener('click', deleteChat);
            }
        }

        if(  userToInfo.id == <?=$_SESSION['user']?> ){
            let query = document.location.search.substr(1);
            var queryObj = parseQuery(query);
            var spId2 = $('.header-chat img').attr('data-id');
            if( spId2 == userInfo.id ){
                output2 = '';
                for( const sms of allSMS ){
                    if( spId2 == sms.user_id ){
                        output2 += '<div class="chat-to">'+
                                        '<div class="chat-cart">'+
                                            '<div class="chat-text">'+
                                                '<div class="images-chat">'+ sms.images +'</div>'+
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
                        output2 += '<div class="chat-me">'+
                                        '<div class="chat-cart">'+
                                            '<div class="chat-text" data-id="'+sms.user_to_id+'">'+
                                                '<a href="/delete/chat" class="delete-sms"><img src="/template/images/delete-sms.svg"></a>'+
                                                '<div class="images-chat">'+ sms.images +'</div>'+
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
            }
            $('#chat-content').html(output2);
            if(queryObj.hasOwnProperty('dark_mode') ){
                    if( queryObj.dark_mode == 'on' ){
                        for(const text of document.querySelectorAll('.chat-me .chat-text'))
                        { text.style.background = '#38385c' }
                        for(const text of document.querySelectorAll('.chat-me .chat-arr'))
                        { text.style.background = '#38385c' }
                        for(const text of document.querySelectorAll('.chat-to .chat-text'))
                        { text.style.background = '#202c44' }
                        for(const text of document.querySelectorAll('.chat-to .chat-arr'))
                        { text.style.background = '#202c44' }
                    } else {
                        for(const text of document.querySelectorAll('.chat-me .chat-text'))
                        { text.style.background = '#eee' }
                        for(const text of document.querySelectorAll('.chat-me .chat-arr'))
                        { text.style.background = '#eee' }
                        for(const text of document.querySelectorAll('.chat-to .chat-text'))
                        { text.style.background = '#e9effb' }
                        for(const text of document.querySelectorAll('.chat-to .chat-arr'))
                        { text.style.background = '#e9effb' }
                    }
                }
        }
    });
    for(const deleteBtn of document.querySelectorAll('.delete-sms')){
        deleteBtn.addEventListener('click', deleteChat);
    }
</script> 
<script src="/template/app.js"></script>
<script>
    <?php if($darkMode == 'on'): ?>
        document.querySelector('.dark-mode').click();
    <?php endif; ?>
</script>
<!-- Footer -->
<?php require_once( ROOT . '/views/layouts/footer.php' ); ?>
<!-- !Footer --> 