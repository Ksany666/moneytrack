<?php
// encryption/decrypt.php

function decryptData($encrypted_data, $key) {
    $cipher = "aes-256-gcm";
    $encryption_key = hash('sha256', $key, true);
    
    $decoded = base64_decode($encrypted_data);
    $iv_length = openssl_cipher_iv_length($cipher);
    
    // Ekstrak komponen dari data yang digabungkan
    $iv = substr($decoded, 0, $iv_length);
    $tag = substr($decoded, $iv_length, 16); // Tag GCM panjangnya fix 16 byte
    $ciphertext = substr($decoded, $iv_length + 16);

    // Proses Dekripsi
    $decrypted = openssl_decrypt($ciphertext, $cipher, $encryption_key, OPENSSL_RAW_DATA, $iv, $tag);

    return $decrypted; // Jika gagal (kunci salah atau data diubah), ini akan mereturn false
}
?>