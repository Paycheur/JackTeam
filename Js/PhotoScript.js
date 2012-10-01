$(document).ready(function() {
	$('.img_profil_big').nailthumb({width:200,height:200});
	$('.img_profil_medium').nailthumb({width:120,height:120});
	$('.img_profil_small').nailthumb({width:50,height:50});
	
	$('a.img_profil').lightBox(); 
});