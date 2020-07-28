<?php
	
//https://stackoverflow.com/questions/13646690/how-to-get-real-ip-from-visitor#13646735
function getUserIP()
{ 
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

include_once("php/luokka.php");
$conn = new Testi( 'root' );

$url = "http://" . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];

$currentPageString =  substr( $_SERVER['PHP_SELF'], 1) ;

$uIP = getUserIP();
$remoteIP    = $_SERVER['REMOTE_ADDR'];
$forwardedIP = "000000";
if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
    $forwardedIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
}

$rID = rand(1000000, 10000000);
//echo $rID;

$conn -> addVisit( $url, $currentPageString, $remoteIP, $forwardedIP,$uIP,$rID, 0,0,NULL  );


echo '<script>' . "\n";
echo 'var myData = {};'. "\n";
echo ' myData.url = "' . $url . '";'. "\n";
echo ' myData.cps = "' . $currentPageString . '";'. "\n";
echo ' myData.rIP = "' . $remoteIP . '";'. "\n";
echo ' myData.fIP = "' . $forwardedIP . '";'. "\n";
echo ' myData.uIP = "' . $uIP . '";'. "\n";
echo ' myData.rID = "' . $rID . '";'. "\n";
echo '</script>'. "\n";


//echo "AAA" . $currentPageString . "BBB"; 
//echo "BBB" . $remoteIP . "CCC" . $forwardedIP;


// mysql -u 100teht -p -h mysqlsci.luntti.net 100teht



/*
CREATE TABLE visits(
   visitID SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
   request_uri VARCHAR(1000),
   php_self VARCHAR(1000),
   remote_addr VARCHAR(1000),
   http_x_forwarded_for  VARCHAR(1000),
   userIP VARCHAR(1000),
   randID INT UNSIGNED,
   vihje BOOLEAN,
   ratkaisu BOOLEAN,
   lisayspvm  DATETIME, 
   kysymysID SMALLINT UNSIGNED,
   PRIMARY KEY (visitID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8; 
*/

?>
