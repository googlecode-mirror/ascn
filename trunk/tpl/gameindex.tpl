<h3>{$jeu->title}</h3>

<p>{$jeu->description}</p>


<p>Cr&eacute;er une partie :</p>
<form action="games/{$jeu->name}/creer_partie" class="ajaxaction">
	<input type="text" name="partie_title" />
	<input type="hidden" name="jeu_id" value="{$jeu->id}" />
	<input type="hidden" name="jeu_name" value="{$jeu->name}" />
	<input type="submit" name="submit" value="Cr&eacute;er" />
</form>