<?php
session_start();
include_once("php/luokka.php");
include_once("php/config.php"); //Connect to the database

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

  <link rel="stylesheet" href="css/style.css">


<!--
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="js/jquery-ui.min.js"></script>
-->

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

$kys = new question();
$kys -> setDB($conn);
$references = new references();
$references -> setDB($conn);

?>


<div id="container">

<?php 
// include("findForm.php");
?>



<div class="row">
 <?php

//print_r($questions);

//
// Set the page: How many.
//

$qid = 0;
if (isset($_GET['id'])){
    $qid = $_GET['id'];
}

  echo '<div class="question">';
  //echo '<p>' . $qnro . '</p>';

  //
  // The question
  $qText = $kys -> printQuestion( $qid );
  preg_match_all('/\(\'(.*?)\'\)/i', $qText , $images);
  $qText1 = preg_replace('/\(\'(.*?)\'\)/i', '', $qText );

  //
  // https://stackoverflow.com/questions/11249445/php-regex-get-a-string-within-parentheses

  echo '<p>';
  $count = count($images[1]);
  $search = 'htmlimage';
  $searchlen = strlen($search);
  $qText2 = '';
  $offset = 0;
  for($i = 0; $i < $count; $i++) {
      if (($pos = strpos($qText1, $search, $offset)) !== false){
          $qText2 .= substr($qText1, $offset, $pos-$offset) . $kys->imageString( $images[1][$i] );
          $offset = $pos + $searchlen;
      }
  }
  $qText2 .= substr($qText1, $offset);
  echo $qText2;
  echo '</p>';


  if ( isset($_SESSION['uname'] ) ){
    echo "<p><a href='newSolution.php?id=" . $q -> questionID . "'>Add Solution</a></p>";
  }

  echo '</div>';


?>



</div>
</div>


<?php include("php/statement.php");?>


<script>
        


</script>


