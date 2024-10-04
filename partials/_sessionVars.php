<?php
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
    $user_name = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
    $user_email = $_SESSION['user_email'];
    $user_bio = $_SESSION['user_bio'];
}
?>