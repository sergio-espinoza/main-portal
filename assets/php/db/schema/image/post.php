<?php
require_once('../../connection.php');
require_once('../../queries/insert.php');

$targetDir = 'uploads/';
$src = $_FILES['src'];
$idsection = $_POST['idsection'];
$title = $_POST['title'];
$subtitle = $_POST['subtitle'];
$description = $_POST['description'];

$targetFile = $targetDir.basename($src['name']);
$uploadOk = 1;
$srcTmpName = $src['tmp_name'];
$srcName = $src['name'];

$check = getimagesize($srcTmpName);

if (file_exists($targetFile)) {
  echo 'Sorry, file already exists.';
  $uploadOk = 0;  
}

if ($src['size'] > 500000) {
  echo 'Sorry, your file is too large.';
  $uploadOk = 0;
}

if ($check !== false) {
  echo 'File is an image - '.$check['mime'].'.';
} else {
  echo 'File is not an image.';
  $uploadOk = 0;
}

if ($uploadOk == 0) {
  echo 'Sorry, your file was not uploaded.';
} else {
  if (move_uploaded_file($srcTmpName, $targetFile)) {
    
    $result = insertIntoByTableAndTexts(
      'src, idsection, title, subtitle, description', 
      "'$srcName', '$idsection', '$title', '$subtitle', '$description'", 
      'image', $conn);

    if($result === TRUE) {
      echo "New Image src created successfully";
    } else {
      echo "Error: $conn->error";
    }
    echo '<img src="'.$targetFile.'" alt="new Image" />';
  } else {
    echo 'Sorry, there was an error uploading your file';
  }
}

?>