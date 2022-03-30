<?php

//Include the library
require_once 'AESCryptFileLib.php';
//Include an AES256 Implementation
require_once 'aes256/MCryptAES256Implementation.php';
//Construct the implementation
$mcrypt = new MCryptAES256Implementation();

//Use this to instantiate the encryption library class
$lib = new AESCryptFileLib($mcrypt);



$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);

if(isset($_POST["submit"])) {
	// Check if file already exists
	if (file_exists($target_file)) {
	    echo "Sorry, file already exists.";
	    $uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 900000) {
	    echo "Sorry, your file is too large.";
	    $uploadOk = 0;
	}
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br/>";
		
		$encrypted_file = $target_file.".aes";
		//Ensure target file does not exist
		@unlink($encrypted_file);
		//Encrypt a file
		$lib->encryptFile($target_file, "1234", $encrypted_file);
        
        echo 'The result is <a href="'.$encrypted_file.'">'.$encrypted_file;
        
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<hr/>
<form action="index.php" method="post" enctype="multipart/form-data">
	Your Photo: <input type="file" name="fileToUpload" size="25" />
	<input type="password" name="password" size="25" />
	<input type="submit" name="submit" value="Submit" />
</form>