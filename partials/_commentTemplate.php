<?php
function printComment($comment_id, $user_id, $username, $comment_content, $date){
    $img = displayUserImage($user_id);
    echo '<div class="profile-card bg-secondary-subtle p-3 my-2">
        <div><div class="d-flex">
                '.$img.'
                <a href="profile.php?profileid='.$user_id.'" class="hover-ul nav-link ">
                <p class="username fs-4">'.$username.'</p>
                </a>
            </div><b class="text-secondary">'.$date.'</b><div class="d-flex"><p class="mb-0" data-commentid='.$comment_id.'>'.$comment_content.'</p>
            </div>
        </div>';
     if($user_id == $_SESSION['user_id']){
        echo '<div class="my-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editCommentModal" id="editCommentBtn">
        Edit
        </button>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCommentModal" id="deleteCommentBtn">
        Delete
        </button>
        </div>';
     }   
echo '</div>';
}
?>