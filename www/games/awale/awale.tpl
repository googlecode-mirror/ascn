<div id="containers">

	<div id="grenier-left" class="grenier"></div>
	
	<div id="compartiments">
		{section name=joueur start=0 loop=2 step=1}
			{assign var='joueur' value=$smarty.section.joueur.index}
			
			<div id="compartiments-{$joueur}" class="compartiments-line">
			
			{section name=compartiment start=0 loop=6 step=1}
				{assign var='compartiment' value=$smarty.section.compartiment.index}
				
				<div id="compartiment-{$joueur}-{$compartiment}" class="compartiment" style="">
					<p class="qte_txt">0</p>
				</div>
				
			{/section}
			
			</div>
			
		{/section}
	</div>
		
	<div id="grenier-right" class="grenier"></div>

</div>