<?php

session_start();
include_once 'luokka.php';
include_once 'config.php';

print_r($_POST);


$ref = $_POST['ref'];
$qNro = $_POST['qNro'];
$qdate = $_POST['questiondate'];
$link = $_POST['link'];
$question = $_POST['question'];
$level = $_POST['level'];
$topics = explode(',', $_POST['topics']);
#
# Find the ID's if possible
#

# Ref
$refID = $conn -> getRef($ref);
if ( count( $refID ) == 1){
    $refID = $refID[0] -> ID;
}else{
    $refID=$conn -> addRef($ref);
}
print_r( $refID );

# Topics
print_r($topics);
$tIDs = [];
foreach($topics as $topic){
    echo $topic; 
    $topicID = $conn -> getTopicQ($topic);
    if ( count( $topicID ) == 1){
        $topicID = $topicID[0] -> ID;
    }else{
        $topicID=$conn -> addTopicQ($topic);
    }
    print_r( $topicID );
    array_push( $tIDs, $topicID );
    
}
#
# Save the question
#
$qID = $conn -> addQuestion( $question, $qdate, $qNro, $link, $refID );


#
# Save the topics!
#   The beef!
foreach($tIDs as $tID){
    $conn -> addTopicQuestion( $qID, $tID );
}


if(isset($_FILES["myfile"]))
{
}
 ?>
