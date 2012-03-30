<h2>Jeu de Tic Tac Toe</h2>

<h3>Partie : {$partie->title}</h3>


<ul class="grille">
	{foreach from=$cases item=case}
	<li class="case {if $case['p']}odd{else}even{/if}" id="case-{$case['x']}-{$case['y']}">
		<div class="item {$case['class']}" id="item-{$case['x']}-{$case['y']}"></div>
	</li>
	{/foreach}
</ul>