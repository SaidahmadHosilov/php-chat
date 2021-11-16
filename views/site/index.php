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
            <div class="chat-content">

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

            <form class="send-message" action="" method="POST">
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
 
<script src="/template/app.js"></script>
<!-- Footer -->
<?php require_once( ROOT . '/views/layouts/footer.php' ); ?>
<!-- !Footer --> 