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


<!--
<form>
<label>Linkki</label>
<input type="text" id="fileLink"></input>
</form>
-->


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

$levels = $conn -> getLevels(  );
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
if ( isset($_SESSION['uname'])){

  echo "<p><a href='images.php'>Add Image</a></p>";
  echo "<p><a href='newSolution.php?id=X'>Add Solution with ID</a></p>";
  include('php/newQuestionInsertQuestion.php');
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

      // <Function>, required
      // Use the output html, triggered by element's `oninput` event
      onChange: html => console.log(html),

      // <string>, optional, default = 'div'
      // Instructs the editor which element to inject via the return key
      defaultParagraphSeparator: 'p',

      // <boolean>, optional, default = false
      // Outputs <span style="font-weight: bold;"></span> instead of <b></b>
      styleWithCSS: false,

      // <Array[string | Object]>, string if overwriting, object if customizing/creating
      // action.name<string> (only required if overwriting)
      // action.icon<string> (optional if overwriting, required if custom action)
      // action.title<string> (optional)
      // action.result<Function> (required)
      // Specify the actions you specifically want (in order)
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
        //document.getElementById('questionOutput').innerHTML = html
        //document.getElementById('html-output').textContent = html     
      }
    })
    </script>





<script>

function convert() {
      //
      //  Get the input (it is HTML containing delimited TeX math
      //    and/or MathML tags
      //
      
      var input = editor.content.innerHTML
      //var input = document.getElementById("pell-test").innerHTML;
      //var input = document.getElementById("questionOutput").innerHTML;
      //console.log( document.getElementById("questionOutput").innerHTML )
      //
      //  Clear the old output
      //
      output = document.getElementById('questionOutput');
      output.innerHTML = input;
      //
      //  Reset the tex labels (and automatic equation numbers, though there aren't any here).
      //  Reset the typesetting system (font caches, etc.)
      //  Typeset the page, using a promise to let us know when that is complete
      //
      MathJax.texReset();
      MathJax.typesetClear();
      MathJax.typesetPromise( ['.latexOutput'] )
        .catch(function (err) {
          //
          //  If there was an internal error, put the message into the output instead
          //
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



$('#questionForm').on("submit", function(e) {
  e.preventDefault();

  /*
  var $theBut = $("#saveToDb");
  if ($theBut).is('.disabled')) {
    $theBut.removeClass('disabled'); // enable the button?
    lightFill(); //show fill inputs
    lightEmpty(); //show empty inputs
    return false;
  }
  */


  var url = $(this).attr('action');
  var ref = $("#ref").val() ;
  var qNroOutput =  $("#questionNro").val();
  var questiondateOutput = $("#questiondate").val();
  var linkOutput = $("#link").val();
  var question = editor.content.innerHTML
  var levelOutput = $("#level option:selected" ).val();
  var topicsOutput =  $("#topics").val();

  console.log(url);

  var data = {
    ref: ref,
    qNro: qNroOutput,
    questiondate: questiondateOutput,
    link: linkOutput,
    question: question,
    level: levelOutput,
    topics: topicsOutput
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

  


  $("#refOutput").html( $("#ref").val() );
  $("#qNroOutput").html( $("#questionNro").val() );
  $("#questiondateOutput").html( $("#questiondate").val() );
  $("#linkOutput").html( $("#link").val() );
  convert();
  $("#levelOutput").html( $("#level option:selected" ).text() );
  $("#topicsOutput").html( $("#topics").val() );


});





$( ".lomake" ).submit(function( event ) {
	var id = this.id.substr(this.id.indexOf('-')+1);
	var elio = $("#elio-"+id).val();
	var kuvaaja = $("#kuvaaja-"+id).val();
	//var tekijanoikeus = $("#tekijanoikeus-"+id+" option:selected").text();
	var tekijanoikeus = $("#tekijanoikeus-"+id).val();
	var tieteellinen = $("#tieteellinen-"+id).val();

    event.preventDefault();
	
	var dataString = { 'id': id, 'elio': elio, 'kuvaaja': kuvaaja, 'tekijanoikeus': tekijanoikeus, 'tieteellinen': tieteellinen };

	console.log( dataString);
        $.ajax({
          type: "POST",
          url: "php/tallennaElionTiedot.php",
          data: dataString,
          success: function(result){ 
            console.log(result);    
          } 
	});
})
});
</script>


</div>





</body>
</html>
