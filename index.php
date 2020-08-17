<?php
session_start();
include_once("php/luokka.php");
include_once("php/config.php"); //Yhdistetään tietokantaan täällä.

?>


<!DOCTYPE html>
<html lang="fi">
<head>


  <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta charset="utf-8">
  <title>Matikan tehtäviä</title>
  <meta name="description" content="">
  <meta name="author" content="@MarkkuOpe">

  <!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="stylesheet" href="css/skeleton.css">
  <script src="js/jquery.min.js"></script>
  <link rel="stylesheet" href="css/default.css">


  <script src="js/fastsearch.js"></script>
  <script src="js/fastselect.js"></script>
  <link href="css/fastselect.css" rel="stylesheet">
  <!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="images/favicon.png">



  <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script>
  MathJax = {
    tex: {inlineMath: [['$', '$'], ['\\(', '\\)']]},
    startup: {
      ready: function () {
        MathJax.startup.defaultReady();
      }
    }
  }
  </script>


<style>
	.border{
		border-style: solid;
		border-width: 3px;
		border-radius: 5px;
		margin: 10px;
		padding: 15px;
	}
.
.vihje{
  background-color: #a6bdce;
 
 }
 .tietosuojaseloste{
	font-size: 70%;
 }
.question{
	border-style: solid;
	border-width: 1px;
	margin: 2px;
	padding: 5px;
	border-radius: 5px;

}

.solution{
  border: 2px solid red;
  border-radius: 5px;
  margin: 5px;
  padding: 3px
}
.topic{
  border: 2px solid red;
  border-radius: 5px;
  margin: 5px;
  padding: 3px;
}
.qtopic{
  border: 2px solid green;
  border-radius: 5px;
  margin: 5px;
  padding: 3px;
}
.nonvisible{
  display:none;

}
hr.solutions {
  border: 1px dashed red;
}

.fstElement { font-size: 1.2em; }
            .fstToggleBtn { min-width: 16.5em; }

            .submitBtn { display: none; }

            .fstMultipleMode { display: block; }
            .fstMultipleMode .fstControls { width: 100%; }

</style>

</head>
<body>
  





<script>

$(function()   {
function toggleSlider(el) {
    var dv = $(el).next('div');
    if (dv.is(":visible")) {
        dv.animate({ opacity: "0" }, 100, function () { dv.slideUp(); } );
    }
    else { 
      dv.slideDown(100, function () {
            dv.animate( { opacity: "1" }, 100 );
        });
    }
}

$('.toggle_solutions').click(function(e) {
  e.preventDefault();
  toggleSlider(this);
})
});

</script>


















<?php
//include 'php/getIPs.php';
?>

<?php
$topics = $conn -> getQuestionTopics();
$topicsSol = $conn -> getAllSolutionTopics();

$lkm = $conn -> getNumberOfQuestions();

$searchTopics = [];
if (isset( $_GET['q'] ) ){
  foreach( $_GET['q'] as $q ){
    array_push($searchTopics, $q);
  }
}
$searchTopicsSol = [];
if (isset( $_GET['s'] ) ){
  foreach( $_GET['s'] as $q ){
    array_push($searchTopicsSol, $q);
  }
}


$questions = [];
if ( count( $searchTopics ) > 0){
  $questions = $conn -> getTaggedQuestions( $searchTopics );
}
if ( count( $searchTopicsSol ) > 0){
  $questions = array_merge( $questions, $conn -> getTaggedSolutionQuestions( $searchTopicsSol ) );
}
if ( count($questions)==0  ){
  $questions = $conn -> getQuestions();
}



?>

<div id="container">

<form id="findForm" action="#" method="GET">
	<div class="row">
  <h3>Tehtävien haku</h3>



  <p>Tehtäviä on <?php echo $lkm[0]->lkm; ?> kpl. Valitse haluamaisi aiheen kysymykset.</p>



  <div class="five columns">
  <label for="qtopics">Kysymykset:</label>
  <select  class="multipleSelect" name="q[]" id="qtopics" placeholder="" multiple="multiple">
<?php
foreach( $topics as $t){
  if (in_array( $t->topic, $searchTopics )){
    echo '<option selected value="' . $t->topic . '">' .$t->topic.'</option>';

  }else{
    echo '<option value="' . $t->topic . '">' .$t->topic.'</option>';
  }
}
?>
</select>


 	</div>
  <div class="six columns">
  <label for="stopics">Ratkaisut:</label>
  <select class="multipleSelect" name="s[]" id="stopics" multiple="multiple" placeholder="Valitse">
  <?php
foreach( $topicsSol as $t){
  if (in_array( $t->topic, $searchTopicsSol )){
    echo '<option selected value="' . $t->topic . '">' .$t->topic.'</option>';
  }else{
    echo '<option value="' . $t->topic . '">' .$t->topic.'</option>';
  }
}
?>
</select>
 


<script>

    $('.multipleSelect').fastselect();

</script>





</div>

</div>
</div>
<p>Hakuoperaattorina on (Boolean) <em>tai</em>.</p>
<input value="Etsi" type="submit">
</form>


<div class="row">
 <?php

foreach ($questions as $q){
  echo '<div class="question">';
  
  //Print the tag topics;
  //print_r( $q );
  $quesTopics = $conn -> getQuestionTopicsOne( $q -> questionID );

  echo '<div class="topics">';
    foreach ($quesTopics as $t){
        echo '<span class="qtopic">';
        echo( $t -> topic );
        echo '</span>';
    }
  echo '</div>';

  //Print the question; 
  echo $q -> question; 
  
  $solutions = $conn -> getSolutions($q->questionID );
  if (count( $solutions) > 0){

  echo "<hr class='solutions'>"; 

    if (count( $solutions) == 1){
      echo "Kysymykseen on 1 vastaus." . "\n";
    }else{
      echo "Kysymykseen on " . count( $solutions) . " vastausta." . "\n";      
    }


    $solutionNumber = 1;
    foreach( $solutions as $s){
      echo '<a href="#" class="toggle_solutions">'. $solutionNumber .'</a> ';
      $solutionNumber = $solutionNumber +1;

        $solTopics = $conn -> getSolutionTopics( $s -> ID );
        echo '<div class="solution nonvisible">';

        echo '<div class="topics">';
        foreach ($solTopics as $t){
            echo '<span class="topic">';
            echo( $t -> topic );
            echo '</span>';
        }
        echo '</div>';
        echo $s -> solution; 
        echo '</div>' . "\n" ;
    }
}





  if ( isset($_SESSION['uname'])){
    echo "<a href='newSolution.php?id=". $q -> questionID ."'>Lisää vastaus</a>";
  }



	echo '</div>';
}

 ?>


</div>
</div>




<div class="esimerkki">
<div class="row">
<div class="twelve columns">


<h6>Tietosuojaseloste</h6>
	<p class="tietosuojaseloste"><em>Rekisterin ylläpitäjä</em>: Markku Leino, @MarkkuOpe. <em>Käsiteltävät henkilötiedot:  täysin erilliseen tauluun talletetaan käyttäjien <em>IP-osoitteet</em> ja tietokannan hakukellonajat. </p>
	
	<p class="tietosuojaseloste">Korjausehdotukset ja muut kommentiit twitterillä, kiitos.</p>



</div>
</div>
</div>


<script>
        


</script>


