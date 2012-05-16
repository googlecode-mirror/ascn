<script type="text/javascript">
<!--
var plateau_inverse={if $plateau_inverse}true{else}false{/if};
var nb_joueur={$nb_joueur};
//-->
</script>

<div id="plateau" class="std-plateau" style="width:{$regles->taille_plateau*64}px;height:{$regles->taille_plateau*64}px;">
	{section name=loop_x start=0 loop=$regles->taille_plateau step=1}
		{section name=loop_y start=0 loop=$regles->taille_plateau step=1}
		
			{if !$plateau_inverse}
				{assign var='x' value=$smarty.section.loop_x.index}
				{assign var='y' value=$smarty.section.loop_y.index}
			{else}
				{assign var='x' value=$regles->taille_plateau-1-$smarty.section.loop_x.index}
				{assign var='y' value=$regles->taille_plateau-1-$smarty.section.loop_y.index}
			{/if}
			{assign var='partie' value=(($x%2)==($y%2))}
			<div id="case-{$x}-{$y}" class="std-case std-case-{if $partie}blanche{else}noire{/if}"></div>
		{/section}
	{/section}
	
	
	<div id="pions">
		{section name=joueur start=0 loop={$nb_joueur} step=1}
			{assign var='joueur' value=$smarty.section.joueur.index+1}
			{if $joueur==1}
				{assign var='couleur' value='blanc'}
			{else}
				{assign var='couleur' value='noir'}
			{/if}
			
			{section name=pion start=0 loop=$regles->nb_pion step=1}
				{assign var='pion' value=$smarty.section.pion.index}
				<div id="pion-{$joueur}-{$pion}" class="std-pion pion-{$couleur} draggable"></div>
			{/section}
		{/section}
	</div>
	
</div>
