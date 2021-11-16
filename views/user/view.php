<!-- Header -->
<?php require_once( ROOT . '/views/layouts/header.php' ); ?>
<!-- !Header -->

<div class="container my-5">
    <h2>Information about <i><?=$user['name']?></i></h2>
    <div class="row my-5">
        <div class="col-md-4">
            <img src="/upload/profile_image/<?=$user['image']?>"
            style="width: 100%; height: 200px; object-fit:cover;">
        </div>
        <div class="col-md-8">
            <h3>Information of bio</h3>
            <p class="my-3">
                <?=$user['bio']?>
            </p>
            <span class="text-success">
                Email:
                    <?=$user['email']?>                
            </span> <br>
            <h5 class="text-success">
                Roll:
                    <?php
                        if( $user['roll'] == null )
                            echo "user";
                        else 
                            echo $user['roll'];
                    ?>                
            </h5>
        </div>
    </div>
    <div class="row">
        <h3 class="m-3">The posts of <?=$user['name']?></h3>
        <div class="col-md-12 post-entry-sidebar">
            <ul>
                <?php
                    if(!empty($userPosts)):
                        foreach($userPosts as $post):
                ?> 
                            <li class="d-flex justify-content-between">
                                <div class="post-left-div">
                                    <a href="/post/details/<?=$post['id']?>" class="d-flex align-items-center">
                                    <img src="/upload/profile_image/<?=$post['image']?>" alt="Image placeholder" 
                                    class="mr-4" style="max-width: 100px; height: 80px; object-fit:cover;">
                                    <div class="text">
                                        <h4><?=$post['title']?></h4>
                                        <div class="post-meta">
                                        <span class="mr-2">
                                            <?php 
                                                echo date('F', mktime(0, 0, 0, date( 'm', strtotime($post['created_at'])), 10))." ";
                                                echo date( 'm', strtotime($post['created_at'])) . ", ";
                                                echo date( 'Y', strtotime($post['created_at']));
                                            ?>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <?php if($currentUser == $user['id']): ?>
                                    <div class="post-right-div d-flex align-items-center">
                                        <a href="/post/edit/<?=$post['id']?>" class="btn btn-primary mr-2">Edit</a>
                                        <a href="/post/delete/<?=$post['id']?>" class="btn btn-danger" onclick="return confirm('Are you sure to delete?')">Delete</a>
                                    </div>
                                <?php endif; ?>
                                </a>
                            </li>
                <?php
                        endforeach;
                    else:
                ?>
                    <h3>Not Found Post</h3>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <?php 
        if($currentUser == $user['id']): 
    ?>
        <div class="row">
            <a href="/user/profile/edit/<?=$currentUser?>" class="btn btn-primary mr-3">Edit Your Profile</a>
            <a href="/user/profile/delete/<?=$currentUser?>" onclick="return confirm('Are you sure to delete?')" class="btn btn-danger">Delete Your Profile</a>
        </div>
    <?php endif; ?>
</div>

<!-- Footer -->
<?php require_once( ROOT . '/views/layouts/footer.php' ); ?>
<!-- !Footer --> 