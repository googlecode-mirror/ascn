<script type="text/javascript">
$(function () {
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

