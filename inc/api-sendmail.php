<?php

add_filter( 'wp_mail_content_type', function( $content_type ) {
    return 'text/html';
} );

function sendMessageContact($data){
    $secretKey = 'sk_b1F6xpjjuNdZAcm5MVWxwP43BRaP5hkKLcKmp2nZ2thEM4w2Y4mhA9q3XtgmtievgozWcE6A2vX5VrWE0B3BguTgcOuErogHtxfOese0oYvXTLRNUrE0FU0LmmvBkBEJ';
    $publicKey = 'pk_k5eN53SQTGs5ZFUqcCJSBxPZ8FfCXkJaUU8ZYlA7uRcAaKwfDIRr6VtFYOuxH58o0GmLPDwNu3pv7zolfRHYfZC24efSLF5eo5apHYcTIPLeq03XGiEP0xX7Oz3fb6bC';
    $results = array(
        'sended' => false,
        'message' => $data['message'],
    );
    if($data['secret_key'] ===  $secretKey && $data['public_key'] === $publicKey){
       $results = sendMail($data['message']);
    }
    return $results;
}
function sendMail($message){

   $subject = '['.get_bloginfo().'] Nouveau message reçu';
   $headers = "From: contact@".get_bloginfo();


    $admins = get_users('role=Administrator');
    $editors= get_users('role=Editor');
    $results = array(
        'sended' => false,
        'message' => $message,
    );
    $usersToSend = array_merge( $admins, $editors);
  
    foreach ($usersToSend as $user) {
        $to = $user->user_email;
        wp_mail( $to, $subject, $message);
        $results['sended'] = true;
    }  
   
    return    $results;
}



