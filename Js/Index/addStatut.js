$(function() {
	$('#formAddStatut').submit(function(e){ 
	    e.preventDefault(); 
	    var valeurs=$(this).serialize();
		$.ajax({
            url: '/Php/index.php?EX=SendStatut',
            type: 'POST',
            data: valeurs,
            dataType: 'json',
            success: function(json) {
                if(json.reponse == 'ok') {
                	$.noty.closeAll();
                	var n = noty({text: 'Message posté.'});
            		n.setType('success');
            		$('#statut_message').val('');
                } else {
                	var n = noty({text: json.reponse});
            		n.setType('error');
                }
            },
            error: function(resultat, statut, erreur){
            	alert(erreur);
            } 
        });
		return false;
	});
});
		