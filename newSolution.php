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



<link href="css/uploadfile.css" rel="stylesheet">
<script src="js/jquery.uploadfile.min.js"></script> 


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



<script>
$(document).ready(function() {

var fileLink = 'DD';

    $("#fileuploader").uploadFile({
                dynamicFormData: function() {
                     //var data ={"ytlink": $("#fileLink").val()};
                     var data ={"ytlink": "nope"};
                     return data;        
                },
                //formData: { ytlink: "BB" + fileLink + "AA" },
		url:"php/saveImage.php",
		fileName:"myfile",
                maxFileSize:128*1024*1024
	});

<!--

//php.ini
//BOTH  -- http://stackoverflow.com/questions/6279897/post-content-length-exceeds-the-limit
//upload_max_filesize = 1000M ;1GB
//post_max_size = 1000M

-->


});
</script>

<?php

$id = $_GET['id'];
$question = $conn -> getQuestion( $id );

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

echo "<p><a href='images.php'>Add Image</a></p>";
echo "<p><a href='newQuestion.php'>Add Question</a></p>";

echo '<div>';
echo '<div class="row"><div class="six columns">';
echo '<h2>Kysymys</h2>';
echo $question[0] -> question;
echo '</div></div>';
echo '</div>';

//
//Check if there exists solutions
//
$solutions = $conn -> getSolutions($id );
if (count( $solutions) > 0){
    echo "Kysymykseen on " . count( $solutions) . " vastausta. Kirjoita uusi, jos se on parempi." . "\n";
    foreach( $solutions as $s){
        $solTopics = $conn -> getSolutionTopics( $s -> ID );
        echo '<div class="solution">';

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

//
//
//

if ( isset($_SESSION['uname'])){
  include('php/newSolutionInsertSolution.php');
}
?>



<script type="text/javascript">
			$(function() {
				$('#topics').tagsInput({
					'autocomplete': {
						source: [
              <?php
               echo $topics;
              ?>
						]
					} 
				});				
			});
</script>


    <script src="js/pell.js"></script>
    <script>
    const editor = pell.init({
      // <HTMLElement>, required
      element: document.getElementById('pell-test'),

      onChange: html => console.log(html),

      defaultParagraphSeparator: 'p',

      styleWithCSS: false,

      actions: [
        'bold',      
        'italic',
        'strikethrough',
        'heading1',
        'paragraph',
        'quote',
        'olist',
        'ulist',
        'code',
        'line',
        'link'
      ],

      // classes<Array[string]> (optional)
      // Choose your custom class names
      classes: {
        actionbar: 'pell-actionbar',
        button: 'pell-button',
        content: 'pell-content',
        selected: 'pell-button-selected'
      },
      onChange: html => {
      }
    })
    </script>





<script>

function convert() {
      //
      var input = editor.content.innerHTML
      //  Clear the old output
      output = document.getElementById('solutionOutput');
      output.innerHTML = input;
      MathJax.texReset();
      MathJax.typesetClear();
      MathJax.typesetPromise( ['.latexOutput'] )
        .catch(function (err) {
          output.innerHTML = '';
          output.appendChild(document.createElement('pre')).appendChild(document.createTextNode(err.message));
        })
        .then(function() {
        });
    }




$(document).ready(function(){

  $('.piilotusnappi').click(function(){
        console.log("Moi");
            //$('.piilotettu', this).toggle(); // p00f
            $(this).siblings('.piilotettu').toggle(); // p00f
    });



$('#solutionForm').on("submit", function(e) {
  e.preventDefault();

  var url = $(this).attr('action');
  var solutiondateOutput = $("#solutiondate").val();
  var solution = editor.content.innerHTML
  var topicsOutput =  $("#topics").val();
  var questionID = <?php echo $id; ?>;
  console.log(url);

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




$('#renderLatex').click(function() {

  $("#topicsOutput").html( $("#topics").val() );
  convert();

});



});
</script>


</div>





</body>
</html>
