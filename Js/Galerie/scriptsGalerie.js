$(document).ready(function() {
	$('.nailthumb-container').nailthumb({width:120,height:120});
	$('.nailthumb-container-album').nailthumb({width:350,height:180,titleWhen:'load'});
	$('a.linkShowPhoto').lightBox(); 
});