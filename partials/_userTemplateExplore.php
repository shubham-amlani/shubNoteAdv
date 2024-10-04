<?php
function printUser($user_id, $username, $user_bio){
    $img = displayUserImage($user_id);
    echo '<div class="profile-card d-flex d-flex bg-secondary-subtle justify-content-between p-3 my-2">
    <div class="d-flex">
       '.$img.'
       <a href="profile.php?profileid='.$user_id.'" class="hover-ul nav-link ">
        <p class="username fs-4">'.$username.'</p>
    </a>
    </div>
    <div class="d-flex">
    <form action="handlers/_handleFollowUnfollow.php" method="post">
    <input type="hidden" name="page" value="explore.php">';
    if($_SESSION['user_id'] == $user_id){
        echo '';
    }
    else if(checkFollow($_SESSION['user_id'], $user_id)){
        echo '<input type="hidden" name="unfollower_user_id" value="'.$_SESSION['user_id'].'">
        <input type="hidden" name="unfollowed_user_id" value="'.$user_id.'">
        <button class="btn btn-outline-secondary">Unfollow</button>';
    }
    else{
        echo '<input type="hidden" name="follower_user_id" value="'.$_SESSION['user_id'].'">
        <input type="hidden" name="followed_user_id" value="'.$user_id.'">
        <button class="btn btn-primary">Follow</button>';
    }
echo '</form>
    </div>
    </div>';
}
?>