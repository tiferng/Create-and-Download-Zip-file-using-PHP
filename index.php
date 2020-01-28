<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class='container'>
<center><h1>Create and Download Zip file using PHP</h1></center>
<center>
<?php
/* creates a compressed zip file */
function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) {
		 echo "<h3 class='alert alert-danger'>";
		 echo "The Zip File <strong>" . $destination ."</strong> is Already Exist";
		 echo "<h3>"; 
		 
		 return false; 
		}
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}

	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		    echo "<h3 class=' alert alert-success'>";
		    echo "The Zip File Created with successful";
		    echo "<h3>";
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}

//YOU CAN CHANGE THIS ARRAY IT IS UP TO WHAT YOU WANT TO ZIP IT
$files_to_zip = array(
	'includes/01.jpg',
	'includes/02.jpg'
);

//CALL create_zip FUNCTION TO CREATE ZIP BY POST METHOD
if(isset($_POST['create'])){
	//if true, good; if false, zip creation failed
    $result = create_zip($files_to_zip,'my-archive.zip');
}

//DOWNLOAD ZIP FILE IF EXIST
if(isset($_POST['download'])){
    $destination = "my-archive.zip";
	if (file_exists($destination)) {
	   header('Content-Type: application/zip');
	   header('Content-Disposition: attachment; filename="'.basename($destination).'"');
	   header('Content-Length: ' . filesize($destination));
  
	   flush();
	   readfile($destination);
	   // DELETE THE ZIP FILE 
	   unlink($destination);
   
	 }
  }
?>

	<form name="zips" action="" method="post">
    <input class="btn btn-primary" type="submit" id="create" name="create" value="Creating Zip " >
    <input class="btn btn-success"  type="submit" id="download" name="download" value="Download Files" >
   </form>

</div>
</center>
</body>
</html>
