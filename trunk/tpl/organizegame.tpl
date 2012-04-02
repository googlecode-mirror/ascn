<script type="text/javascript">
$(function() {
	Page.addJs('organize_js', 'js/organizegame.js');
});
</script>

<h2>Organize {$jeu->name}</h2>


<ol class="liste-joueurs">
{foreach from=$slots item=slot}
	<li>
		{$slot.joueur_pseudo}
	</li>
{/foreach}
</ol>

{if $isHost}
	<form action="games/{$jeu->name}/lancer_partie">
		<input type="hidden" name="partie_id" value="{$partie->id}" />
		<input type="submit" value="Lancer la partie !" />
	</form>
{else}
	<p>Partie en cours de pr&eacute;paration...</p>
{/if}