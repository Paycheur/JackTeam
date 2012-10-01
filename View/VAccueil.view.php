<?php
class VAccueil
{
  /**
   * Constructeur
   * 
   * @access public
   * 
   * @return none
   */
  public function __construct() {return;}

  /**
   * Destructeur
   * 
   * @access public
   * 
   * @return none
   */
  public function __destruct() {return;}

  
	public function ShowListeStatuts($_value)
	{
	    foreach($_value as $statut)
	    {
	    	$message=affichage($statut->getContenu());
	    	$date=affichage($statut->getDate());
	    	$photo=affichage($statut->getPhoto());
	    	$tag=affichage($statut->getTag());
	    	$auteur=affichage($statut->getAuteur());
	    	$id=affichage($statut->getId());
	    	
	    	$commentaires = new MACommentaireManage();
	    	$nb_comment = $commentaires->countCommentForStatut($id);
	    	$allComment = $commentaires->getListe($id, $nb_comment);
	    	
	    	echo <<<HERE
	    	<div class="bloc_statut" id="$id">
		    	<img src="../Img/img_statut.png" />
		    	<p class="message">$message <span class="auteur_date">Par $auteur le $date</span></p>
		    	<input type="text" class="contenuCommentaire" />
		    	<input type="button" class="envoyeCommentaire" value="Envoyer" />
HERE;
				if($nb_comment > 3)
				{
	    			echo '<input type="button" class="afficherCommentaire" value="Afficher tous les commentaires" />';
				}
				echo <<<HERE
	    	
	    	<div class="bloc_commentaire_statut">
HERE;
			
			foreach($allComment as $com)
			{
				echo '<p>'.affichage($com->getContenu()).' par '.affichage($com->getAuteur()).' le '.affichage($com->getDate()).'</p>';
			}
			echo '</div>';
			echo '</div>';
	    }
    }
}
    
?>