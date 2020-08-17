
<h1>Kirjoita ratkaisu </h1>

<form action="php/saveNewSolution.php" id="solutionForm" >

<div class="row">

<div class="six columns">

 

<div id="pell-test"></div>
<input type="button" class="button-primary" value="Render LaTeX" id="renderLatex" />
<div id="fileuploader">Upload</div>

      </select>

<label>Aiheet:</label>
      <input id="topics" name="topics" type="text" value="">


</div>
<div class="six columns">

<div id="solutiondateOutput"></div>
<div id="linkOutput"></div>
<div class="latexOutput" id="solutionOutput">$\LaTeX e=mc2$</div>
<div id="topicsOutput"></div>


<input type="submit" class="button-primary" value="Tallenna" id="saveToDb" />

<div id="result"></div>

</div>
</div>

