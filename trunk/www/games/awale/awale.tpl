{* slot_position : 2=normal 1=retourner *}

<div id="text-top" style="position:absolute">
	<p class="au-tour-de"></p>
</div>

<div id="containers">

	<div id="grenier-{if $slot_position==2}left{else}right{/if}" class="grenier">
		<p class="score_txt">0</p>
	</div>
	
	<div id="compartiments">
		{section name=joueur start=0 loop=2 step=1}
			{if $slot_position==2}
				{assign var='joueur' value=$smarty.section.joueur.index}
			{else}
				{assign var='joueur' value=1-$smarty.section.joueur.index}
			{/if}
			
			<div id="compartiments-{$joueur}" class="compartiments-line">
			
			{section name=compartiment start=0 loop=6 step=1}
				{if $slot_position==2}
					{assign var='compartiment' value=$smarty.section.compartiment.index}
				{else}
					{assign var='compartiment' value=5-$smarty.section.compartiment.index}
				{/if}
				
				<div id="compartiment-{$joueur}-{$compartiment}" class="compartiment" style="">
					<p class="qte_txt">0</p>
				</div>
				
			{/section}
			
			</div>
			
		{/section}
	</div>
		
	<div id="grenier-{if $slot_position==2}right{else}left{/if}" class="grenier">
		<p class="score_txt">0</p>
	</div>

</div>
