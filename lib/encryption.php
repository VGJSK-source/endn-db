<?php

function encryptData($data, $key)
{
    $ivLength = openssl_cipher_iv_length($cipher = "AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivLength);
    $encrypted = openssl_encrypt($data, $cipher, $key, $options = 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

// function decryptData($data, $key)
// {
//     list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
//     return openssl_decrypt($encrypted_data, $cipher = "AES-128-CBC", $key, $options = 0, $iv);
// }

function decryptData($data, $key)
{
    // Split the encrypted data and initialization vector
    $parts = explode('::', base64_decode($data), 2);

    // Check if both parts are present
    if (count($parts) !== 2) {
        return "Invalid encrypted data format"; // Return a message indicating invalid data format
    }

    // Extract the encrypted data and initialization vector
    list($encrypted_data, $iv) = $parts;

    // Perform decryption
    $decrypted_data = openssl_decrypt($encrypted_data, $cipher = "AES-128-CBC", $key, $options = 0, $iv);

    // Check if decryption was successful
    if ($decrypted_data === false) {
        return "Decryption failed"; // Return a message indicating decryption failure
    }

    return $decrypted_data; // Return the decrypted data
}


function generateEncryptionKey($length = 32)
{
    // Define character set for generating random key
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_+=~';

    // Generate random key
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
}



?>