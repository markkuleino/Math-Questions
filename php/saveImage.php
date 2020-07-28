<?php

session_start();
include_once 'luokka.php';
include_once 'config.php';


$output_dir = "../images/";
if(isset($_FILES["myfile"]))
{
	$ret = array();
	
//	This is for custom errors;	
/*	$custom_error= array();
	$custom_error['jquery-upload-file-error']="File already exists";
	echo json_encode($custom_error);
	die();
*/
	$error =$_FILES["myfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 

	if(!is_array($_FILES["myfile"]["name"])) //single file
	{
 	 	$fileName = $_FILES["myfile"]["name"];
                //$fileType = strpos( $_FILES["myfile"]["type"], '.');
				$path = $_FILES["myfile"]["name"];
                $ext = pathinfo( $path, PATHINFO_EXTENSION);
                $ytlink = $_POST["ytlink"];
                $randomname = uniqid() . "." .$ext ;
								
 		$aa = move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$randomname);

                $conn -> LisaaKuva($randomname, $output_dir );

	}
	else  //Multiple files, file[]
	{
	  $fileCount = count($_FILES["myfile"]["name"]);
	  for($i=0; $i < $fileCount; $i++)
	  {

 	 	$fileName = $_FILES["myfile"]["name"][$i];
                //$fileType = substr( $_FILES["myfile"]["type"][$i], -3);
				$path = $_FILES["myfile"]["name"][$i];
                $ext = pathinfo( $path, PATHINFO_EXTENSION);

				$ytlink = $_POST["ytlink"][$i];
                $randomname = uniqid() . "." .$ext ;
 		$aa = move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$randomname);

                $conn -> LisaaKuva($randomname, $output_dir );





	  }
	
	}
    echo json_encode($ret);
 }
 ?>
