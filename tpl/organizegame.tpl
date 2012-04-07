<script type="text/javascript">
$(function() {
	Page.addJs('organize_js', 'js/organizegame.js');
});
</script>

<h2>Pr&eacute;paration d'une partie de {$jeu->title}</h2>

<h3>Partie {$partie->title}, cr&eacute;&eacute;e par {$host->pseudo}</h3>

<p>Envoyer ce lien &agrave; vos amis pour qu'ils rejoignent cette partie :</p>
<p>{$smarty.const.WWW_ROOT}#{$smarty.const.DIRNAME_GAMES}/{$jeu->name}?partie={$partie->id}</p>

<ol class="liste-joueurs">
{foreach from=$slots item=slot}
	<li>
		{$slot.joueur_pseudo}
	</li>
{/foreach}
</ol>



{if $isHost}
	<form action="games/{$jeu->name}/lancer_partie" class="ajaxaction">
		<input type="hidden" name="partie" value="{$partie->id}" />
		<input type="hidden" name="jeu" value="{$jeu->id}" />
		<input type="submit" value="Lancer la partie !" />
	</form>
{else}
	<p>Partie en cours de pr&eacute;paration...</p>
{/if}