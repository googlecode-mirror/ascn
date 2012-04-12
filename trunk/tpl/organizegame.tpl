<script type="text/javascript">
$(function() {
	Page.addJs('organize_js', 'js/organizegame.js');
});
</script>

<h2>Pr&eacute;paration d'une partie de {$jeu->title}</h2>

<h3>Partie {$partie->title}, cr&eacute;&eacute;e par {$host->pseudo}</h3>


<ol class="liste-joueurs">
{foreach from=$slots item=slot}
	<li>
		{$slot.joueur_pseudo}
	</li>
{/foreach}
</ol>

<hr />

<p>Envoyer ce lien &agrave; vos amis pour qu'ils rejoignent cette partie :</p>
<p>{$smarty.const.WWW_ROOT}#{$smarty.const.DIRNAME_GAMES}/{$jeu->name}?partie={$partie->id}</p>

{if $isHost}
	<hr />
	<form action="games/{$jeu->name}/lancer_partie" class="ajaxaction">
		<h3>Options de la partie</h3>
		
		{foreach from=$options item=option}
			<p>{$option->title}</p>
			<select name="option_{$option->id}">
				{foreach from=$option->getValues() key=key item=value}
					<option value="{$key}">{$value}</option>
				{/foreach}
			</select>
		{/foreach}
	
		<hr />
		
		<input type="hidden" name="partie" value="{$partie->id}" />
		<input type="hidden" name="jeu" value="{$jeu->id}" />
		<input type="submit" value="Lancer la partie !" />
	</form>
{else}
	<p>Partie en cours de pr&eacute;paration...</p>
{/if}