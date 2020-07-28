
<h1>Kirjoita kysymys </h1>

<form action="php/saveNewQuestion.php" id="questionForm" >

<div class="row">

<div class="six columns">

  <label for="ref">Lähde (yo-koe, Putnam etc)</label>
      <input class="u-full-width" type="text" name="ref" id="ref" list="refList">
      <datalist id="refList">
      <?php
foreach ($refs as $ref){
  echo('<option value="'  . $ref -> ref . '" />' ); 
}
?>        
</datalist>

<label for="ref">Kysymyksen numero</label>
      <input class="u-full-width" type="text" name="questionNro" id="questionNro">


  <label for="ref">Kysymyksen pvm</label>
      <input class="u-full-width" type="date" placeholder="1.1.2020" name ="questiondate" id="questiondate">

  <label for="ref">Linkki kysymykseen</label>
      <input class="u-full-width" type="text" name="link" id="link">



<div id="pell-test"></div>
<input type="button" class="button-primary" value="Render LaTeX" id="renderLatex" />
<div id="fileuploader">Upload</div>

<label for="level">Vaikeustaso</label>
      <select class="u-full-width" name="level" id="level">
<?php
foreach ($levels as $level){
  echo('<option value="'  . $level -> ID . '">' . $level -> level . '</option>' ); 
}
?>
      </select>

<label>Aiheet:</label>
      <input id="topics" name="topics" type="text" value="">


</div>
<div class="six columns">

<div id="refOutput"></div>
<div id="qNroOutput"></div>
<div id="questiondateOutput"></div>
<div id="linkOutput"></div>
<div class="latexOutput" id="questionOutput"></div>
<div id="levelOutput"></div>
<div id="topicsOutput"></div>


<input type="submit" class="button-primary" value="Tallenna" id="saveToDb" />

<div id="result"></div>

</div>
</div>

