<?php
class VProfil
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

  
	public function ShowProfil($_value)
	{
		$statuts = $_value['statuts'];
		$membre = $_value['infos']; // obj MMembre
		
		$membre_photo = affichage($membre->getPhoto());
		$membre_pseudo = affichage($membre->getPseudo());
		$membre_nom = affichage($membre->getNom());
		$membre_prenom = affichage($membre->getPrenom());
		$membre_dateNaiss = affichage($membre->getDateNaiss());
		echo <<<HERE
		<div>
			<div class="img_profil_big" style="float:left" ><a href="../$membre_photo" class="img_profil"><img src="../$membre_photo" /></a></div>
			<p>$membre_pseudo</p>
			<p>$membre_nom $membre_prenom</p>
			<p>Né le $membre_dateNaiss</p>
			<a href="membres.php?EX=updateProfil">Modifier mon profil</a>
		</div>
HERE;
	    foreach($statuts as $statut)
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
    
    public function UpdateProfil($_value)
    {
    	
    	$membre = $_value['infos'];
    	$photos = $_value['photos'];
    	//$membre = obj MMembre
    	$membre_photo = affichage($membre->getPhoto());
		$membre_pseudo = affichage($membre->getPseudo());
		$membre_nom = affichage($membre->getNom());
		$membre_prenom = affichage($membre->getPrenom());
		$membre_dateNaiss = affichage($membre->getDateNaiss());
		$membre_email = affichage($membre->getEmail());
		$jourNaiss=0; //a supprimer
		echo <<<HERE
		<form method="post" action="membres.php?EX=SendUpdateProfil" enctype="multipart/form-data"> 
		<fieldset class="bigForm">
		<legend>Informations personnelles</legend>
			<div class="formField">
				<label for="pseudo">Pseudo</label>
				<input type="text" id="pseudo" name="pseudo" value="$membre_pseudo" size="10" />
			</div>
			<div class="formField">
				<label for="nom">Nom</label>
				<input type="text" id="nom" name="nom" value="$membre_nom" size="10" />
			</div>
			<div class="formField">
				<label for="prenom">Prenom</label>
				<input type="text" id="prenom" name="prenom" value="$membre_prenom" size="10" />
			</div>
			<div class="formField">
				<label for="email">Email</label>
				<input type="text" id="email" name="email" value="$membre_email" size="10" />
			</div>
			<div class="formField">
				<label for="">Date de naissance</label>
				
				<select name="jour_naiss_membre">
HERE;
					for($i=1; $i<=31; $i++)
					{
						echo '<option value="'.$i.'" '.($jourNaiss==$i ? 'selected' : '') .'>'.$i.'</option>';
					}
					echo <<<HERE
				</select>
				<select name="mois_naiss_membre">
HERE;
					for($i=1; $i<=12; $i++)
					{
						echo '<option value="'.$i.'" '.($moisNaiss==$i ? 'selected' : '') .'>'.$i.'</option>';
					}
				echo <<<HERE
				</select>
				<select name="annee_naiss_membre">
HERE;
					for($i=1940; $i<=2012; $i++)
					{
						echo '<option value="'.$i.'" '.($anneeNaiss==$i ? 'selected' : '') .'>'.$i.'</option>';
					}
				echo <<<HERE
				</select>
			</div>
		</fieldset>
		<fieldset class="bigForm">
		<legend>Modifier le mot de passe</legend>
			<div class="formField">
				<label for="password_actuel">Mot de passe actuel</label>
				<input type="password" name="password_actuel" id="mdp_actuel" size="10"/>
			</div>
			<div class="formField">
				<label for="password_new">Nouveau mot de passe</label>
				<input type="password" name="password_new" id="mdp_new" size="10"/>
			</div>
			<div class="formField">
				<label for="password_new_verif">Mot de passe actuel</label>
				<input type="password" name="password_new_verif" id="mdp_new_verif" size="10"/>
			</div>
		</fieldset>
		<fieldset class="bigForm">
		<legend>Modifier la photo de profil</legend>
			<div class="formField">
				<label for="photo_upload">Uploader une nouvelle photo</label>
				<input type="file" name="photo_upload" id="photo_upload" />
			</div>
			<div class="formField">
				<label for="photo_upload">Sélectionner une photo de profil</label>
				
HERE;
				foreach($photos as $url)
				{
					echo '<div style="display:inline;"><input type="radio" name="photo_exist" style="float:none;width:20px;height:15px;margin-right:0%;" value="'.$url.'" /><div class="img_profil_small" style="display:inline-block;"><img src="'.$url.'" alt=""/></div></div>';
				}
				echo <<<HERE
				
			</div>
			<div class="formFieldSubmit">
			    <input type="submit" value="Enregistrer" />		
			</div>	
		</fieldset>
    	</form>
HERE;
    }
}
    
?>