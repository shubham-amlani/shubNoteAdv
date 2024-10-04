<!-- Closed post template -->
<?php

function printPost($username, $title, $time, $post_id, $user_id){
    // include 'handlers/_handleDisplayImage.php';
    $img = displayUserImage($user_id);
    echo '<div class="profile-card d-flex d-flex bg-secondary-subtle justify-content-between p-2 mx-4 my-3">
    <div class="d-flex">
    '.
    $img
        .'
    <div>
            <div class="">
            <a class="hover-ul nav-link " href="profile.php?profileid='.$user_id.'">
            <b class="username fs-6">'.$username.'</b>
            </a>
            <p class="text-secondary my-0"><b>'.$time.'</b></p>
            </div>
            <a class="hover-ul" href="post.php?postid='.$post_id.'">
            <b class="title m-0">'.$title.'</b>
            </a>
        </div>
    </div>
</div>';
}

?>