<?php

//Include the library
require_once 'AESCryptFileLib.php';

//Include an AES256 Implementation
require_once 'aes256/MCryptAES256Implementation.php';

//Construct the implementation
$mcrypt = new MCryptAES256Implementation();

//Use this to instantiate the encryption library class
$lib = new AESCryptFileLib($mcrypt);
echo file_get_contents("README.md").'<hr/>';

//This example encrypts and decrypts the README.md file
$file_to_encrypt = "README.md";
$encrypted_file = "uploads/test.png.aes";
$decrypted_file = "uploads/test.png";
//echo file_get_contents("README.md.aes").'<hr/>';


//Ensure target file does not exist
//@unlink($encrypted_file);
//Encrypt a file
//$lib->encryptFile($file_to_encrypt, "1234", $encrypted_file);

//Ensure file does not exist
@unlink($decrypted_file);
//Decrypt using same password
$lib->decryptFile($encrypted_file, "1234", $decrypted_file);

echo "Done";