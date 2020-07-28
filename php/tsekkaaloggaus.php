<?php

session_start();
header("Content-type: application/json");
include_once('luokka.php');
include_once 'config.php';


$myusername = strtoupper( $_POST['myusername'] );
$mypassword = sha1( $_POST['mypassword'] );


//exit();

$varaaja = $conn -> login($myusername, $mypassword);


if ((empty($varaaja)) || ($varaaja=="")){
  session_destroy(); 
  header('HTTP/1.1 401 Unauthorized');

}else{
    //Kirjautuminen ok
        
  $_SESSION['userid'] =  $varaaja[0] -> ID;
  $_SESSION['uname'] =  $_POST['myusername'];  

}
header( 'location: ../newQuestion.php' );


//echo "<pre>";
//print_r($_SESSION);
//echo "</pre>";


?>


