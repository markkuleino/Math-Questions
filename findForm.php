<?php
$s = new searchVar();
$s -> setDB( $conn );

$topics = $conn -> getQuestionTopics();
$topicsSol = $conn -> getAllSolutionTopics();

//$refsSol = $references -> getRefs( $conn );
$s->getRefsUsed();
//$s->printRefs();

$levels = $kys -> getLevels();
$lkm = $conn -> getNumberOfQuestions();

// print_r( $levels );

//
// The search part
//
$s->setSearchTopics($_GET);
$s->setSearchYearQ($_GET);
$s->setSearchRefs( $_GET );
$s->setSearchLevels($_GET, $levels);
$s->setSearchAdvanced($_GET);

$s->setPage($_GET);

//
//
//

//echo '<pre>';
//print_r($s);
//echo '</pre>';

//
// Do the database search
//


if ( count( $s-> searchTopics ) == 0 && count( $s-> searchTopicsSolution ) == 0 ){
  $questions = $kys -> findQuestionsQ($s);
}else if ( count( $s-> searchTopics ) == 0 ){
  $questions = $kys -> findQuestionsS($s);
}else if ( count( $s-> searchTopicsSolution ) == 0 ){
  $questions = $kys -> findQuestionsQ($s);
}else{
  $questionsQ = $kys -> findQuestionsQ($s);
  $questionsS = $kys -> findQuestionsS($s);
  
  $questions = [];
  if (strcasecmp( $s->searchBothOperator, 'And') == 0  ){ 
    if ( count( $questionsQ) == 0 || count( $questionsS) == 0 ){
      $questions = [];
    }else{
      // And
      $questions = $kys -> combineAND($questionsQ, $questionsS);
    }
  }else{
    //OR
    $questions = array_merge( $questionsQ, $questionsS );  
    $questions = $kys -> removeDuplicate($questions);
  }
  
}

//
//

/*
echo( "Kysymykset ("  . implode(" " . $s->searchTopicOperator . " ", $s->searchTopics ). ") " . ": " . count($questionsQ) . " kpl.</br>" );
echo( "Vastaukset (" . implode(" " . $s->searchSolutionOperator ." ", $s->searchTopicsSolution ) . "): " . count($questionsS) . " kpl." );
*/

//
//
//

/*
if ( count( $searchTopicsSolution ) > 0){
  $questions = array_merge( $questions, $conn -> getTaggedSolutionQuestions( $searchTopicsSolution ) );
}
if ( count($questions)==0  ){
  $questions = $conn -> getQuestions();
}
*/

//
//
//


$yearMin = $kys -> getMinYear($conn);
$minmax = 'placeholder="' . $yearMin->year .'"' . ' min="' . $yearMin->year . '" max="' . date("Y") .'"';
//echo $minmax;


?>




<form id="findForm" action="#" method="GET">
	<div class="row">
  <a href='newQuestion.php'>Lisää uusi kysymys</a>
  <h3>Tehtävien haku</h3>




  <p>Tehtäviä on <?php echo $lkm[0]->lkm; ?> kpl. Valitse haluamaisi aiheen kysymykset.</p>

</div>
<div class="mainSearch">
<div class="row">
  <div class="five columns">
  <label for="qtopics">Kysymykset:</label>
  <select  class="multipleSelect" name="q[]" id="qtopics" placeholder="" multiple="multiple">
<?php
foreach( $topics as $t){
  if (in_array( $t->topic, $s->searchTopics )){
    echo '<option selected value="' . $t->topic . '">' .$t->topic.'</option>';
  }else{
    echo '<option value="' . $t->topic . '">' .$t->topic.'</option>';
  }
}
?>
</select>
<!--
<label for="radio_question">Operaattori</label>
<?php
if (strcasecmp( $s->searchTopicOperator, 'And') == 0  ){    
    echo ('<input type="radio" name="boolean_topic" checked="checked" value="and">Ja
    <input type="radio" name="boolean_topic" value="or">Tai' );
}else{
    echo ('<input type="radio" name="boolean_topic" value="and">Ja
    <input type="radio" name="boolean_topic" checked="checked" value="or">Tai' );
}
?>
-->
</div>

<div class="two columns">
<label for="radio_both">Operaattori</label>
<?php
if (strcasecmp( $s->searchBothOperator, 'And') == 0  ){    
    echo ('<input type="radio" name="boolean_both" checked="checked" value="and">Ja </br>
    <input type="radio" name="boolean_both" value="or">Tai' );
}else{
    echo ('<input type="radio" name="boolean_both" value="and">Ja </br>
    <input type="radio" name="boolean_both" checked="checked" value="or">Tai' );
}
?>
</div>

  <div class="five columns">
  <label for="stopics">Ratkaisut:</label>
  <select class="multipleSelect" name="s[]" id="stopics" multiple="multiple" placeholder="Valitse">
  <?php
foreach( $topicsSol as $t){
  if (in_array( $t->topic, $s->searchTopicsSolution )){
    echo '<option selected value="' . $t->topic . '">' .$t->topic.'</option>';
  }else{
    echo '<option value="' . $t->topic . '">' .$t->topic.'</option>';
  }
}
?>
</select>
<!-- 
<label for="radio_answer">Operaattori</label>
<?php
if (strcasecmp( $s->searchSolutionOperator, 'And') == 0  ){    
    echo ('<input type="radio" name="boolean_solution" checked="checked" value="and">Ja
    <input type="radio" name="boolean_solution" value="or">Tai' );
}else{
    echo ('<input type="radio" name="boolean_solution" value="and">Ja
    <input type="radio" name="boolean_solution" checked="checked" value="or">Tai' );
}
?>
-->


</div>
</div>

<?php 
if ($s->searchAdvanced === 0){
    echo '<div class="border" id="advancedSearch">';
}else{
    echo '<div class="border show-hide" id="advancedSearch">';
  }
?>
<div class="row">
<div class="four columns">
    <label for="from">Ensimmäinen vuosi</label>
    <input type="number" id="yearFromQ" name="yearFromQ" <?php echo $minmax?> value="<?php echo $s->searchYearFromQ; ?>" >
</div>
<div class="four columns">
    <label for="to">Viimeinen vuosi</label>
    <input type="number" id="yearToQ" name="yearToQ" <?php echo $minmax?> value="<?php echo $s->searchYearToQ; ?>" >
</div>
<div class="four columns">
    <label for="to">Päivämäärättömät</label>
    <input type="checkbox" <?php echo ($s->searchIncludeNoDateChecked) ?>  name="includeNoDate" value="1">Ota mukaan
</div>
</div>

<div class="row">

<div class="six columns">
  <label for="ref">Lähde</label>
  <select class="multipleSelect" name="r[]" id="srefs" multiple="multiple" placeholder="Valitse">
  <?php
  echo( $s -> printHtmlRef() );
  /*
  foreach( $refsSol as $r){
    if (in_array( $r->ref, $searchRefs )){
      echo '<option selected value="' . $r->ref . '">' .$r->ref.'</option>';
    }else{
      echo '<option value="' . $r->ref . '">' .$r->ref.'</option>';
    }
  }
  */
  ?>
  </select>
</div>

<div class="four columns">
  <label for="ref">Vaikeustaso</label>
  <select class="multipleSelect" name="l[]" id="levels" multiple="multiple" placeholder="Valitse">
  <?php
 foreach( $levels as $l){
  if (in_array( $l->level, $s->searchLevels )){
    echo '<option selected value="' . $l->level . '">' .$l->level.'</option>';
  }else{
    echo '<option value="' . $l->level . '">' .$l->level.'</option>';
  }
}
  ?>
  </select>

<label for="unclassified">Vaikeustasoluokittelemattomat</label>
<input type="checkbox" <?php echo $s->searchIncludeNoLevelsChecked ?> name="includeNoLevels" value="1">Ota mukaan

</div>

</div>


</div>

<div>
<div class="row">
<div class="four columns">
  <label class="toggle">
    <input name="advanced" <?php echo $s->searchAdvancedchecked
 ?> type="checkbox" id="advancedSearchButton"/>
    <span class="slider"></span>
  </label>
  <span>Laajennettu haku</span>
</div>
<div class="four columns">
  <input value="Etsi" type="submit">
</div>
</div>
</div>
</form>




<script>
  $(function(){

    $('.multipleSelect').fastselect();


    $('#advancedSearchButton').change(function() {
        //$("#advancedSearch").toggleClass("show-hide", this.checked)
        $("#advancedSearch").toggle("show-hide")
  }).change();

  $("#yearFromQ").bind("change paste keyup", function() {
       $("#yearToQ").attr({
       "min" : $(this).val()          // values (or variables) here
    });
  })
  $("#yearToQ").bind("change paste keyup", function() {
       $("#yearFromQ").attr({
       "max" : $(this).val()          // values (or variables) here
    });
  })


})

</script>