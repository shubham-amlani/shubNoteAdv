<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include '../partials/_functions.php';
    if(isset($_POST['follower_user_id']) && isset($_POST['followed_user_id'])){
        $follower_user_id = $_POST['follower_user_id'];
        $followed_user_id = $_POST['followed_user_id'];
        $location = $_POST['page'];
        if(checkFollow($follower_user_id, $followed_user_id)){
            $_SESSION['followMessage'] = "You're already following this user";
            header("Location: ../$location");
            exit();
        }
        else{
            include '../partials/_dbconnect.php';
            $sql = "INSERT INTO `followers` (`relation_id`, `follower_user_id`, `followed_user_id`, `timestamp`) VALUES (NULL, ?, ?, current_timestamp())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $follower_user_id, $followed_user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($stmt->affected_rows > 0){
                $stmt->close();
                header("Location: ../$location");
                exit();
            }
            else{
                $_SESSION['followMessage'] = "Cannot follow user at the time, please try again later";
                $stmt->close();
                header("Location: ../$location");
                exit();
            }
        }
    }
    else if(isset($_POST['unfollower_user_id']) && isset($_POST['unfollowed_user_id'])){
    $follower_user_id = $_POST['unfollower_user_id'];
    $followed_user_id = $_POST['unfollowed_user_id'];
    $location = $location = $_POST['page'];;
        if(checkFollow($follower_user_id, $followed_user_id)){
            include '../partials/_dbconnect.php';
            $sql = "DELETE FROM `followers` WHERE `follower_user_id`=? AND `followed_user_id`=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $follower_user_id, $followed_user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($stmt->affected_rows > 0){
                $stmt->close();
                header("Location: ../$location");
                exit();
            }
        }
    }
}
?>