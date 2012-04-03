<script type="text/javascript">
	$(function () {
		var input=$('input[name=partie_title]');

		var mem=input.attr('value');
		var effect=true;
		
		input.focusin(function() {
			effect && input.attr('value', '');
			input.select();
		});

		input.focusout(function() {
			effect && input.attr('value', mem);
		});

		
		input.mouseover(function() {
			effect && input.focusin();
		});
		input.mouseout(function() {
			effect && input.focusout();
		});

		input.keypress(function() {
			effect && input.attr('value', '');
			effect=false;
		});
	});
</script>


<h3>{$jeu->title}</h3>

<p>{$jeu->description}</p>


<p>Cr&eacute;er une partie :</p>
<form action="games/{$jeu->name}/creer_partie" class="ajaxaction">
	<input type="text" name="partie_title" value="{$random}" />
	<input type="hidden" name="jeu" value="{$jeu->id}" />
	<input type="hidden" name="jeu_name" value="{$jeu->name}" />
	<input type="submit" name="submit" value="Cr&eacute;er" />
</form>