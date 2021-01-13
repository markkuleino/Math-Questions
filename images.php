<?php
session_start();
include_once("php/luokka.php");
include_once("php/config.php"); //Yhdistetään tietokantaan täällä.

?>

<!-- import the webpage's javascript file -->
<script src="script.js" defer></script>
<link href="css/style.css" rel="stylesheet">
<link href="css/pell.css" rel="stylesheet">

<script src="js/pell.js"></script>
  <style>
    /* override styles here */
    .pell-content {
      background-color: pink;
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


  </style>
</head>



<html>
<head>
<meta charset="utf-8">





<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">




<script src="js/jquery.tagsinput-revisited.js"></script>
<link rel="stylesheet" href="css/jquery.tagsinput-revisited.css" />
		







<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>


<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script>
  MathJax = {
    tex: {inlineMath: [['$', '$'], ['\\(', '\\)']]},
    startup: {
      ready: function () {
        MathJax.startup.defaultReady();
        document.getElementById('render').disabled = false;
      }
    }
  }
  </script>

</head>


  <!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="stylesheet" href="css/skeleton.css">
  
  <link rel="stylesheet" href="css/default.css">


<?php

$refs = $conn -> getRefs(  );
$topics = $conn -> getTopics(  );
$topics = "'" . implode ( "', '", array_column($topics, 'topic') ) . "'";

?>



<?php 



if ( isset($_SESSION['uname'])){
    print '
        <form name="logout" method="post" action="php/poistaloggaus.php">
        <p><input type="submit" name="Submit-ope" value="Kirjaudu ulos"><p>
        </form>';

    echo $_SESSION['uname'];


}else{
    //Ei kirjauduttu

print '
    <form name="login" method="post" action="php/tsekkaaloggaus.php">
    <p><input name="myusername" type="text" id="myusername"> Etunimi.Sukunimi</p>
    <p><input name="mypassword" type="password" id="mypassword"> Salasana</p>
    <p><input type="submit" name="Submit-ope" value="Kirjaudu sisään"><p>
    </form>';
}

?>

</div>

<?php

echo '<div>';
echo '<div class="row"><div class="six columns">';
echo '<h2>Lisää kuva</h2>';
echo '</div></div>';
echo '</div>';

//
//
//

if ( isset($_SESSION['uname'])){
  include('php/newImage.php');
}


//
//
//

echo "<p>Images</p>";
$images = $conn->getImages();
 //print_r($images[0]);

?>

<?php 

$lkm = 0;
foreach ($images as $i){
  if ($lkm%4 == 0){
    echo '<div class="row">' . "\n";
  }


  echo '<div class="three columns">';
  echo "<img src = '" . $i->namernd . "' class='u-full-width' alt='My Happy SVG' />";
  echo "<p>htmlimage('". $i->namernd ."')</p>";
  echo '</div>' . "\n";

  if (($lkm-3)%4 == 0){
    echo '</div>' . "\n" ;
  }


  $lkm++;

}
?>

</div>
</div>


<!--
<script>
$(document).ready(function(){
$('#solutionForm').on("submit", function(e) {
  e.preventDefault();
  var url = $(this).attr('action');
  var 

  var data = {
    solutiondate: solutiondateOutput,
    solution: solution,
    topics: topicsOutput,
    questionID: questionID
    };
  $.ajax({
    url: url,
    type: 'POST',
    data: data,
  }).done(function(data) {
    console.log(data); 
    $("#result").html(e);
    alert( "Talletettu" + e );

  }).fail(function(e) {
    alert('Fail');
    $("#result").html(e);
    console.log(e);
  });
});
});
</script>

-->

</div>




</body>
</html>
