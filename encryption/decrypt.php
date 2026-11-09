<?php

function decryptData($encrypted_data, $key) {
    $cipher = "aes-256-gcm";
    $encryption_key = hash('sha256', $key, true);
    
    $decoded = base64_decode($encrypted_data);
    $iv_length = openssl_cipher_iv_length($cipher);
    
    $iv = substr($decoded, 0, $iv_length);
    $tag = substr($decoded, $iv_length, 16);
    $ciphertext = substr($decoded, $iv_length + 16);

    $decrypted = openssl_decrypt($ciphertext, $cipher, $encryption_key, OPENSSL_RAW_DATA, $iv, $tag);

    return $decrypted;
}
?>