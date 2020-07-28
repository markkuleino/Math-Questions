<?php
//session_start();
include_once("php/luokka.php");
include_once("php/config.php"); //Yhdistetään tietokantaan täällä.

?>




<html>
<head>
<meta charset="utf-8">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


<script
  src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"
  integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E="
  crossorigin="anonymous">
</script>


</head>





<style>

  #gallery{
     width: 78%;
     float: left;
     border: 2px solid;
  }

</style>


<h1>Tee uusi koe</h1>
<?php
$eliot=$conn -> haeEliot();
?>

<div id='koeLomake'>
<form id ="koe" action="php/talletaKoe.php" method="post">
	
<div class="row">
<div class="three columns">
	<label for="luokkaAste">Luokka-aste</label>
      <select class="u-full-width" id="luokkaAste" name="luokkaAste">
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="0">Lukio</option>
      </select>
</div>
<div class="four columns">
	<label for="kokeenNimi">Eliöiden lukumäärä kokeessa</label>
    <input name="lkm" class="u-full-width" type="number" min="0" step="1" placeholder="0" id="lkm" >
</div>
<div class="four columns">
	<label for="kokeenNimi">Kokeen nimi</label>
    <input name="kokeenNimi" class="u-full-width" type="text" placeholder="nimi" id="kokeenNimi">
</div>

<div class="five columns">
	<label for="huomioita">Ohjeet oppilaille</label>
    <input name="ohje" class="u-full-width" type="text" placeholder="Ohjeet" id="ohje">
</div>
</div>
<h3>Kokeessa mahdollisesi olevat eliöt</h3>
<p>Jos aiemmin kirjoitettu "Eliöiden lukumäärä kokeessa" on pienempi, näytetään vain niin monta eliötä alla mainituista.
	
</p>
<?php
foreach ($eliot as $elio){
	echo '<input name="eliot-'.$elio->ID.'" id="elio-'.$elio->ID.'" type="checkbox">';
	echo '  <span name="eliot[]" class="elio-'.$elio->ID.'">' .  $elio -> nimi . "</span> " ;
}
?>

<p>
<input class="button-primary" type="submit" value="Talleta koe">
</p>
</form>
</div>



</div>









<script>

$(document).ready(function(){

$( ".lomake" ).submit(function( event ) {
	var id = this.id.substr(this.id.indexOf('-')+1);
	var elio = $("#elio-"+id).val();
	var kuvaaja = $("#kuvaaja-"+id).val();
	//var tekijanoikeus = $("#tekijanoikeus-"+id+" option:selected").text();
	var tekijanoikeus = $("#tekijanoikeus-"+id).val();

    event.preventDefault();
	
	var dataString = { 'id': id, 'elio': elio, 'kuvaaja': kuvaaja, 'tekijanoikeus': tekijanoikeus };

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



</body>
</html>
