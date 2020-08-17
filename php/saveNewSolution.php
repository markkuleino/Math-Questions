<?php

session_start();
include_once 'luokka.php';
include_once 'config.php';

print_r($_POST);


$solution = $_POST['solution'];
$topics = explode(',', $_POST['topics']);
$qID = $_POST['questionID'];
#
# Find the ID's if possible
#

# Topics
print_r($topics);
$tIDs = [];
foreach($topics as $topic){
    echo $topic; 
    $topicID = $conn -> getTopicA($topic);
    if ( count( $topicID ) == 1){
        $topicID = $topicID[0] -> ID;
    }else{
        $topicID=$conn -> addTopicA($topic);
    }
    print_r( $topicID );
    array_push( $tIDs, $topicID );
    
}
#
# Save the solution
#
$sID = $conn -> addSolution( $solution, $qID );

#
# Save the topics!
foreach($tIDs as $tID){
    $conn -> addTopicSolution( $sID, $tID );
}


if(isset($_FILES["myfile"]))
{
}
 ?>
