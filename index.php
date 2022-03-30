<?
// Begun May 2015
// Slightly cleaned up October 29 2015

//Include the library
require_once 'engine/AESCryptFileLib.php';
//Include an AES256 Implementation
require_once 'engine/aes256/MCryptAES256Implementation.php';

//ENCRYPT
if(isset($_POST["dencPass"])) {
	
	//Construct the implementation
	$mcrypt = new MCryptAES256Implementation();
	
	//Use this to instantiate the encryption library class
	$lib = new AESCryptFileLib($mcrypt);
	
	$target_dir = "data/decuploads/";
	$target_file = $target_dir . basename($_FILES["dencfileToUpload"]["name"]);
	$uploadOk = 1;
	$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
	
	// echo 'target_file='.$target_file.'<br/>';
	
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
	    if (move_uploaded_file($_FILES["dencfileToUpload"]["tmp_name"], $target_file)) {
	        // echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br/>";
			
			$uniqid = uniqid();
			
			$downloadFile = str_replace('.aes', '', basename($_FILES["dencfileToUpload"]["name"]));
			$decrypted_file = 'data/decdownloads/'.$downloadFile;
			
			//Ensure target file does not exist
			@unlink($encrypted_file);
			//Encrypt a file
			$lib->decryptFile($target_file, $_POST['dencPass'], $decrypted_file);
	        @unlink($target_file); // deletes the uploaded original
			
	        // echo 'The result is <a href="'.$encrypted_file.'">'.$decrypted_file;
	        
	    } else {
	        echo "Sorry, there was an error uploading your file.";
	    }
	}
	
}

//DECRYPT
if(isset($_POST["encPass"])) {
		
	//Construct the implementation
	$mcrypt = new MCryptAES256Implementation();
	
	//Use this to instantiate the encryption library class
	$lib = new AESCryptFileLib($mcrypt);
	
	$target_dir = "data/uploads/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
	
	// echo 'target_file='.$target_file.'<br/>';
	
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
	        // echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br/>";
			
			$uniqid = uniqid();
			
			$encrypted_file = 'data/downloads/'.$uniqid.'.'.$fileType.".aes";
			//Ensure target file does not exist
			@unlink($encrypted_file);
			//Encrypt a file
			$lib->encryptFile($target_file, $_POST['encPass'], $encrypted_file);
	        @unlink($target_file); // deletes the uploaded original
	        // echo 'The result is <a href="'.$encrypted_file.'">'.$encrypted_file;
	        
	    } else {
	        echo "Sorry, there was an error uploading your file.";
	    }
	}
}

include('modules/head.php');
?>

<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			<h3>
				cryptAny <small>Easily encrypt and decrypt any file</small>
			</h3>
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-6 column">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
						Encrypt
					</h3>
				</div>
				<div class="panel-body">
				
					<!-- === after submit display  === -->
					<? if(isset($_POST["encPass"])) { ?>
					<div id="encAfterUploadScreen">
						<!--
						<form method="get" action="download.php">
							<button type="submit" class="btn btn-default">Download encrypted file</button>
							<input type="hidden" name="filename" value="<?=$encrypted_file?>">
						</form>
						-->
						<a href="<?=$encrypted_file?>">Download encrypted file</a><br/>
						Copy URL: <input type="text" value="https://originaldougal.com/anycrypt/<?=$encrypted_file?>" size="62">
						<br/><br/>
						<form method="get" action="index.php">
							<button type="submit" class="btn btn-default">Reset</button>
						</form>
					</div>
					<? } else { ?>
					
					<!-- === Uploading anim  === -->
					<div id="encUploadScreen" style="display:none;"><center><img src="img/uploading.gif"/><br/><small>Encrypting... </small></center></div>
					<!-- === encrypt form === -->
					<div id="encForm">
						<form action="index.php" method="post" enctype="multipart/form-data">
							<div class="form-group">
								 <input type="file" name="fileToUpload" size="25" />
								<p class="help-block">
									Select a file from your computer to encrypt.
								</p>
							</div>
							<div class="form-group">
								 <label for="encPass">Password</label><input name="encPass" type="password" class="form-control" id="encPass">
								 <p class="help-block">
									The password that secures your file must be at least 8 characters long. <small><a href="#">tips</a></small>
								</p>
							</div>
							<button type="submit" class="btn btn-default" id="encButton">Encrypt</button>
						</form>
					</div><!-- === end encrypt form  === -->
					<? } ?>
				</div>
			</div>

		</div>
		<div class="col-md-6 column">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
						Decrypt
					</h3>
				</div>
				<div class="panel-body">
					
					<!-- === after submit display  === -->
					<? if(isset($_POST["dencPass"])) { ?>
					<div id="dencAfterUploadScreen">
						<a href="<?=$decrypted_file?>">Download decrypted file</a><br/>
						Copy URL: <input type="text" value="https://originaldougal.com/anycrypt/<?=$decrypted_file?>" size="62">
						<br/><br/>
						<form method="get" action="index.php">
							<button type="submit" class="btn btn-default">Reset</button>
						</form>
					</div>
					<? } else { ?>
					
					<!-- === Uploading anim  === -->
					<div id="dencUploadScreen" style="display:none;"><center><img src="img/uploading.gif"/><br/><small>Decrypting... </small></center></div>
					<!-- === decrypt form === -->
					<div id="dencForm">
						<form action="index.php" method="post" enctype="multipart/form-data">
							<div class="form-group">
								 <input type="file" name="dencfileToUpload" size="25" />
								<p class="help-block">
									Select a file from your computer to encrypt.
								</p>
							</div>
							<div class="form-group">
								 <label for="encPass">Password</label><input name="dencPass" type="password" class="form-control" id="encPass">
								 <p class="help-block">
									The password that secures your file.
								</p>
							</div>
							<button type="submit" class="btn btn-default" id="dencButton">Decrypt</button>
						</form>
					</div><!-- === end decrypt form  === -->
					<? } ?>

				</div>
			</div>

		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-12 column">
			<p>
				<div id="debug"></div>
				<strong>Disclaimer</strong>: This service is under development. Do not rely on it for strong security at this time.</small>
			</p>
		</div>
	</div>
</div>

<script>
	// $("#debug").html('debug');

	$("#encForm").submit(function() {
		// $("#debug").html('submittad');
		$("#encForm").hide();
		$("#encUploadScreen").css('display', 'inline');
	});
	
	$("#dencForm").submit(function() {
		// $("#debug").html('submittad');
		$("#dencForm").hide();
		$("#dencUploadScreen").css('display', 'inline');
	});

</script>

</body>
</html>