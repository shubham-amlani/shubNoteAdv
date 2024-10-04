<?php
// Function to display user's profile image
function displayUserProfileImage($user_id) {
    include 'partials/_dbconnect.php';
    $sql = "SELECT `image_path` FROM `users` WHERE `user_id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($image_path);
    $stmt->fetch();
    $stmt->close();
    if ($image_path) {
        return '<img src="'.$image_path.'" alt="Profile Image" class="user-profile-image" />';
    }
    else{
        return '<img src="images/user-default.png" alt="Profile Image" class="user-profile-image d-inline" />';
    }
}

// Function to display user images
function displayUserImage($user_id) {
    include 'partials/_dbconnect.php';
    $sql = "SELECT `image_path` FROM `users` WHERE `user_id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($image_path);
    $stmt->fetch();
    $stmt->close();
    if ($image_path) {
        return '<img src="'.$image_path.'" alt="Profile Image" class="user-image" />';
    }
    else{
        return '<img src="images/user-default.png" alt="Profile Image" class="user-image d-inline" />';
    }
}

// Function to get number of followers of a user
function numFollowers($user_id){
    include 'partials/_dbconnect.php';
    $sql = 'SELECT * FROM `followers` WHERE `followed_user_id`=?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows;
}

// Function to get number of followings of a user
function numFollowing($user_id){
    include 'partials/_dbconnect.php';
    $sql = 'SELECT * FROM `followers` WHERE `follower_user_id`=?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows;
}

// Function to get number of posts of a user
function numPosts($user_id){
    include 'partials/_dbconnect.php';
    $sql = 'SELECT * FROM `posts` WHERE `post_user_id`=?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows;
}

// Function to get number of public posts of a user
function numPublicPosts($user_id){
    include 'partials/_dbconnect.php';
    $sql = 'SELECT * FROM `posts` WHERE `post_user_id`=? AND `is_private`=0';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows;
}

// Function to print a post
function printPost($post_id, $user_id, $username, $time, $post_title, $post_description,  $is_private=0){
    $img = displayUserImage($user_id);
    $post_description = substr($post_description, 0, 150) . "...";
    
    echo '<div class="post d-flex flex-column">
    <div class="upper-post d-flex ">'.$img.'<div class="upper-post-right d-flex flex-column">
    <a class="nav-link m-0 p-0" href="profile.php?profileid='.$user_id.'"><span class="username">'.$username.'</span></a>
            <span class="post-date">'.$time.'</span>';
            if($is_private == 1){
                echo '<span class="private-post-indicator rounded">(Private)</span>';
            }
echo    '</div>
    </div>
    <hr>
    <div class="lower-post my-2">
        <div class="lower-post-up d-flex flex-column">
            <span class="post-title">'.$post_title.'</span>
            <a href="post.php?postid='.$post_id.'"><span class="post-description">'.nl2br($post_description).'</span></a>
        </div>
    </div>
</div>';
}

// Function to print comments
function printComment($comment_id, $comment_user_id, $comment_username, $comment_time, $comment_content){
    $img = displayUserImage($comment_user_id);
    echo '<div class="user d-flex flex-column">
    <div class="upper-post d-flex">'.$img.'<div class="upper-post-right d-flex flex-column">
            <a class="nav-link m-0 p-0" href="profile.php?profileid='.$comment_user_id.'"><span class="username">'.$comment_username.'</span></a>
            <span class="post-date">'.$comment_time.'</span>';
            if($comment_user_id == $_SESSION['user_id']){
                echo '<div class="btns my-2"><button type="button" class="btn btn-primary editCommentBtn" data-bs-toggle="modal" data-bs-target="#editCommentModal">
                Edit
                </button>
                <button type="button" class="btn btn-danger deleteCommentBtn" data-bs-toggle="modal" data-bs-target="#deleteCommentModal">
                Delete
                </button>
            </div>';
            }
echo    '</div>
    </div>
    <p class="comment mt-2 comment-content" data-commentid="'.$comment_id.'">'.$comment_content.'</p>
</div>';
}


//Function to check if a user follows another user

function checkFollow($follower_id, $followed_id){
    include '_dbconnect.php';
    $sql = "SELECT * FROM `followers` WHERE `follower_user_id`=? AND `followed_user_id`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $follower_id, $followed_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        $stmt->close();
        return true;
    }
    else{
        $stmt->close();
        return false;
    }
}

// Function to print user for follow/unfollow
function printUser($user_id, $username, $name, $page){
    $img = displayUserImage($user_id);
    echo '<div class="user d-flex user justify-content-between align-items-center">
    <div class="user-left d-flex">
        '.$img.'
        <div class="user-mid d-flex flex-column">
        <a class="nav-link m-0 p-0" href="profile.php?profileid='.$user_id.'"><span class="username">'.$username.'</span></a>
            <span class="name">'.$name.'</span>
        </div>
    </div>
    <form action="handlers/_handleFollowUnfollow.php" method="post">
    <input type="hidden" name="page" value="'.$page.'">';
    if($user_id == $_SESSION['user_id']){
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
echo    '</form>
        </div>';
}

// Function to check currentPage
function checkPage($str){
    if(str_contains($_SERVER['PHP_SELF'], $str)){
        return true;
    }
    else{
        return false;
    }
}

// Function reverseValidate
function reverseValidate($str){
    $str_unvalidated = str_replace("&lt;", "<", $str);
    $str_unvalidated = str_replace("&gt;", ">", $str_unvalidated);
    return $str_unvalidated;
}

// Function to make links clickable in post content
function makeLinksClickable($postContent) {
    // Regular expression to find URLs in the post content
    $pattern = '/\b(?:https?:\/\/|www\.)\S+\b/';

    // Replace URLs with anchor tags
    $postContent = preg_replace_callback($pattern, function($matches) {
        $url = $matches[0];
        // Check if the URL starts with "http://" or "https://", if not, add "http://"
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            // Check if the URL starts with "www." and add "http://" if it does
            if (strpos($url, 'www.') === 0) {
                $url = "http://" . $url;
            } else {
                // Otherwise, assume it's a domain name without protocol and prepend "http://"
                $url = "http://www." . $url;
            }
        }
        // Create HTML anchor tag
        return '<a href="' . $url . '" target="_blank">' . $url . '</a>';
    }, $postContent);

    return $postContent;
}

?>