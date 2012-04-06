<h2>Scores</h2>

<table class="scores"><tbody>
	<tr>
		<th>#</th>
		<th>Position</th>
		<th>Joueur</th>
		<th>Score</th>
	</tr>
	{foreach from=$slots item=s name=foo}
	<tr{if ($s.slot_id==$slot->id)} class="self"{/if}>
		<td>{$s.slot_position}</td>
		<td>{$smarty.foreach.foo.iteration}</th>
		<td>{$s.joueur_pseudo}</th>
		<td>{$s.slot_score}</th>
	</tr>
	{/foreach}
</tbody></table>

<a href="index.php" class="ajaxload">Retour &agrave; l'accueil</a>