<?php 
    function encryptor($action, $string) {
        $output = false;
        $encrypt_method = 'AES-256-CBC';

        $secretKey = bin2hex(32);
        $secretIv = 'AES256CBC';

        $key = hash('sha256', $secretKey);
        $iv = substr(hash('sha256', $secretIv),0, 16 );

        if($action === 'encrypt')
        {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        elseif($action === 'decrypt')
        {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
            
        }

       return $output; 

    }
?>