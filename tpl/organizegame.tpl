<script type="text/javascript">
$(function () {
	Page.hash('games/{$jeu->name}?partie={$partie->id}');
});
</script>

<h2>Organize {$jeu->name}</h2>
{debug}

<ol>
{foreach from=$slots item=slot}
	<li>
		{$slot.joueur_pseudo}
	</li>
{/foreach}
</ol>