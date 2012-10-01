(function($)
{ 
	$.fn.commentairesStatut=function()
	{
	   return this.each(function( )
	   {
		   var self   = $(this); 
			
			self.idStatut = self.attr('id'); // Récupération de l'id du statut
			
			self.boutonEnvoyer = self.find('.envoyeCommentaire');
			self.boutonCommentaire = self.find('.afficherCommentaire');
			
			self.boutonEnvoyer.on('click', function(e) {
				
				self.contenuCommentaire = self.find('.contenuCommentaire').val();
				
				$.ajax({
		            url: '/Php/index.php?EX=SendCommentaire',
		            type: 'POST',
		            data: 'idStatut='+self.idStatut+'&contenu='+self.contenuCommentaire,
		            dataType: 'json',
		            success: function(json) {
		                if(json.reponse == 'ok') {
		                	$.noty.closeAll();
		                	var n = noty({text: 'Commentaire envoyé.'});
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
			});
			
			self.boutonCommentaire.on('click', function(e) {
				$.ajax({
		            url: '/Php/index.php?EX=AllCommentaires',
		            type: 'POST',
		            data: 'idStatut='+self.idStatut,
		            dataType: 'json',
		            success: function(json) {
		                if(json.reponse == 'ok') {
		                	self.find('.bloc_commentaire_statut').html('');
		                	 $.each(json.comments, function(index, value) {
		                		 self.find('.bloc_commentaire_statut').append(value);
		                		 
		                	 });
		                } else {
		                	var n = noty({text: json.reponse});
		            		n.setType('error');
		                }
		            },
		            error: function(resultat, statut, erreur){
		            	alert(erreur);
		            } 
		        });
			});
	   });
	};
	
	
})(jQuery);

$(function()
{
	$('.bloc_statut').commentairesStatut();
});