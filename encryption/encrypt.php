<?php

function encryptData($data, $key) {
    $cipher = "aes-256-gcm";
    
    $encryption_key = hash('sha256', $key, true);
    
    $iv_length = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($iv_length);
    $tag = "";

    $ciphertext = openssl_encrypt($data, $cipher, $encryption_key, OPENSSL_RAW_DATA, $iv, $tag);

    return base64_encode($iv . $tag . $ciphertext);
}
?>