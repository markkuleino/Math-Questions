<?php
session_start();
include_once 'luokka.php';
include_once 'config.php';


//if ($_SESSION['julkisuus']>1){
    
    $ID = $_GET['ID'];


    //Hae kuva
    $pikkukuva = $conn -> haePikkukuvaID( $ID );

//    echo $pikkukuva[0] -> pikkukuvatyyppi; 
//     print_r($pikkukuva);

        
    if ( empty( $pikkukuva[0] -> pikkukuvatyyppi) ){
       header("Content-type: image/jpeg");
    }
    else  {
        header("Content-type: " . $pikkukuva[0] -> pikkukuvatyyppi );
    }
    echo stripslashes( $pikkukuva[0] -> pikkukuva );
//}
?>
