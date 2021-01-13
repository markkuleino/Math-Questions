<?php

session_start();
include_once("luokka.php");
include_once("config.php"); //Yhdistetään tietokantaan täällä.


print_r($_FILES);

if (isset($_FILES['file'])){
 
    $tmp = explode(".", $_FILES["file"]["name"]);
    $fileExtension = end($tmp);
    $randName =  uniqid() .".".$fileExtension;
    $targetDir = "images/";
    $targetFile = $targetDir . $randName;

    move_uploaded_file($_FILES["file"]["tmp_name"], "../" . $targetFile);

    echo $targetFile;

    $conn -> addImage($targetFile );

    //
    // Add to database;
    // 


}

?>