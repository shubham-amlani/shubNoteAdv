<?php
function sendCode($email, $code){
    $to = $email;
    $subject = "Verify Your Email Address for ShubNote";
    $message = "Dear User,

    Thank you for signing up for ShubNote! To complete your registration and access all the features of our platform, we need to verify your email address.

    Please use the following verification code to verify your email address:

    Verification Code: $code

    If you did not sign up for ShubNote, please disregard this email.

    Thank you,
    The ShubNote Team";

    $header = "From: blackpyttech@gmail.com"; 
    $mailResult = mail($to, $subject, $message, $header);
    return $mailResult;
}


?>