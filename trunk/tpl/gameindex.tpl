<h3>{$jeu->title}</h3>

<p>{$jeu->description}</p>


<p>Cr&eacute;er une partie :</p>
<form action="games/{$jeu->name}" class="ajaxload">
	<input name="partie_name" />
	<input type="submit" value="Cr&eacute;er la partie" />

</form>