<!-- Closed post template -->
<?php

function printPost($post_id, $user_id, $username, $title, $description, $time){
    if(strlen($description) > 150){
        $description = substr($description, 0, 150) . "...";
    }
    $img = displayUserImage($user_id);
    echo '<div class="post bg-secondary-subtle">
    <div class="post-header d-flex flex-column align-items-start mb-3">
        <div class="post-details d-flex align-items-center">
        '.$img.'
        <a class="nav-link hover-ul" href="profile.php?profileid='.$user_id.'"><h4 class="username">'.$username.'</h4></a>
        </div>
        <p class="post-date mb-0">'.$time.'</p>
    </div>
    <div class="post-content">
        <h2 class="post-title">'.$title.'</h2>
        <p class="post-description">
            '.$description.'
        </p>
        <a href="post.php?postid='.$post_id.'" class="read-more">Read More</a>
    </div>
</div>';
}

?>