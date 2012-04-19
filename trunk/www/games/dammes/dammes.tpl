

<div id="plateau" class="std-plateau" style="width:{$taille_plateau*64}px;height:{$taille_plateau*64}px;">
	{section name=loop_x start=0 loop=$taille_plateau step=1}
		{section name=loop_y start=0 loop=$taille_plateau step=1}
		
			{if !$plateau_inverse}
				{assign var='x' value=$smarty.section.loop_x.index}
				{assign var='y' value=$smarty.section.loop_y.index}
			{else}
				{assign var='x' value=$taille_plateau-1-$smarty.section.loop_x.index}
				{assign var='y' value=$taille_plateau-1-$smarty.section.loop_y.index}
			{/if}
			{assign var='parite' value=(($x%2)==($y%2))}
			
			
			<div id="case-{$x}-{$y}" class="std-case std-case-{if $parite}blanche{else}noire{/if}">
				<div class="cliquable"></div>
			</div>
			
		{/section}
	{/section}
	
	
	<div id="pions">
		{section name=joueur start=0 loop=2 step=1}
			{assign var='joueur' value=$smarty.section.joueur.index+1}
			{if $joueur==1}
				{assign var='couleur' value='blanc'}
			{else}
				{assign var='couleur' value='noir'}
			{/if}
			
			{section name=pion start=0 loop=$nb_pion step=1}
				{assign var='pion' value=$smarty.section.pion.index}
				
				<div id="pion-{$joueur}-{$pion}" class="std-pion pion-{$couleur} draggable"></div>
			{/section}
		{/section}
	</div>
	
</div>


