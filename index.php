<?php
//session_start();
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
</style>

</head>
<body>
	
<?php
//include 'php/getIPs.php';
?>

<?php
$topics = $conn -> getQuestionTopics();
$lkm = $conn -> getNumberOfQuestions();

$questions = $conn -> getQuestions();

?>

<div id="container">

	<div class="row">
	<div class="six columns">
<p> 		Tehtävien haku tulee tähän. Nyt tarjolla (ei haettavissa) seuraavat tagit:
<?php
print_r($topics);
?>
ja tehtäviä on <?php echo $lkm[0]->lkm; ?> kpl.</p>

	</div>

</div>
</div>


<div class="row">
 <?php

foreach ($questions as $q){
	echo '<div class="question">';
	echo $q -> question; 
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
