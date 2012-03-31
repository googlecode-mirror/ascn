
$(function () {
	//Page.hash('games/{$jeu->name}?partie={$partie->id}');
	setTimeout(autorefresh, 2500);
});


function autorefresh() {
	Page.refresh();
	setTimeout(autorefresh, 2500);
}
