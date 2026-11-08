<?php
// encryption/encrypt.php

function encryptData($data, $key) {
    $cipher = "aes-256-gcm";
    
    // AES-256 butuh kunci tepat 32 byte. Kita gunakan hashing SHA-256 agar panjang kunci konsisten.
    $encryption_key = hash('sha256', $key, true);
    
    // Generate IV (GCM biasanya pakai 12 byte)
    $iv_length = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($iv_length);
    $tag = ""; // Variabel tag akan diisi otomatis oleh openssl_encrypt

    // Proses Enkripsi
    $ciphertext = openssl_encrypt($data, $cipher, $encryption_key, OPENSSL_RAW_DATA, $iv, $tag);

    // Gabungkan IV + Tag + Ciphertext, lalu jadikan base64 agar aman disimpan dalam bentuk teks
    return base64_encode($iv . $tag . $ciphertext);
}
?>