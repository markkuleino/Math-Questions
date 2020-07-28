<?php
session_start();
header("Content-type: application/json");


$head = "location: ../newQuestion.php";

if( isset( $_SESSION['uname']) ){
    session_unset(); 
    session_destroy();
} 
else{ 
    //the session variable isn't registered, the user shouldn't even
    //be on this page
}

header( $head ); 
exit();

?>