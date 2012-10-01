<?php
class VPhoto
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

  /**
   * Affiche la liste des personnes
   * 
   * @access public
   * 
   * @return none
   */

public function ShowList($_value)
{
      echo '<table CELLSPACING="1px" id="tabListePhoto">';
      $i=0;
	    echo '<tr>';
			
	    foreach($_value as $photo)
	    {
		  if($i==6)
		  {
			echo '</tr><tr>';
			$i=0;
		  }
		  $miniature = affichage($photo->getMiniature());
		  $urlPhoto = affichage($photo->getPhotoUrl());
		  //
			echo <<<HERE
			<td><div class="nailthumb-container"><a class="linkShowPhoto" href="{$urlPhoto}" title="" link="?EX=ShowPhoto&album={$_GET['album']}&id={$photo->getId()}"><img src="{$urlPhoto}" alt="image" /></a></div></td>
HERE;
			
			$i++;
	    }
	    echo '</tr>';
      echo '</table>';
      
      
      $base_cible='galerie_photos WHERE id_album='.$_GET['album'];
      page(25, 'galerie.php?EX=ShowListePhoto&album='.$_GET['album'], $base_cible);
      
      $this->ShowFormAddPhoto();
      
      return;
}

public function ShowFormAddPhoto()
{
      echo '<fieldset class="bigForm">'; 
			echo '<legend><span class="c_vert">Ajouter</span> des photos</legend>';
				echo '<form>';
					echo '<p>Nombre de photos à ajouter:';
					echo '<select name="nbr_fichier" onChange="nbFormAddPhoto(document.getElementById(\'nbr_fichier\').value);" id="nbr_fichier">';
					  echo '<option value="1">Selection</option>';
   					  echo '<option value="1">1</option>';
					  echo '<option value="2">2</option>';
					  echo '<option value="3">3</option>';
					  echo '<option value="4">4</option>';
					  echo '<option value="5">5</option>';
					echo '</select>';
				echo '</p>';
			      echo '</form>';
			
	
      		echo '<form method="post" action="?EX=SendAddPhoto&album='.$_GET['album'].'" enctype="multipart/form-data">';
     			echo  '<p id="AffichageForm"></p>';
			echo '</form>';
      echo '</fieldset>';
      
      return;
}

public function ShowPhoto($_value) //$_value['photo] : la photo, $_value['precedente'] : lien de la précédente photo, $_value['suivante']
{
      
      $photo = affichage($_value['photo']->getPhotoUrl());
      $date = remplacer_date($_value['photo']->getDate());
      
      echo <<<HERE
      <a href="{$_value['precedente']}">Précédente</a><br />
      <a href="{$_value['suivante']}">Suivante</a><br />
      <p class="infosDateAuteur" >Ajouté par {$_value['photo']->getAuteur()} le {$date}</p>
      <p style="text-align:center; clear:both;"><img class="affichage_photo" src="{$photo}" alt="{$_value['photo']->getTitre()}" /></p>
      <p id="contenuDescriptionPhoto">
	    <em><span id="titrePhoto">{$_value['photo']->getTitre()}</span></em><img src="../Theme/img/bouton_modifier.jpg" onclick="updateInfosPhoto({$_GET['id']})" alt="Modifier" />
      </p>
HERE;

      $this->ShowBlocJaime($_value['aime']); //le bloc J'aime/J'aime pas
      
      //METTRE CE QUI SUIT DANS UNE FONCTION ?
      
      
      /*echo <<<HERE
      <div class="description_photo">
	    
	    
HERE;
      if($_SESSION['pseudo']==$_value['photo']->getAuteur() OR $_SESSION['groupe'] == 'administrateur' ) 
      {
	    echo '<img src="../Theme/img/bouton_supprimer.jpg" alt="Supprimer" onclick="showDeleteFormPhoto('.$_value['photo']->getId().', '.$_GET['album'].')" />';
      }
      echo '</div>';*/
      

      echo <<<HERE
      
      <div class="option_supprimer" id="affichageMsgDelete_{$_value['photo']->getId()}"></div>
HERE;

      $this->ShowBlocCommentaire($_value['commentaire']);

      
      
}

public function ShowBlocJaime($_value)
{
      
      echo '<div class="note_photo">';
	    echo '<h2 id="h2Gris" >Les avis.</h2>';
	    echo '<p>';
	    if($_value['combienAime']>1)
	    {
		  echo $_value['combienAime'].' personnes aiment : ';
	    }
	    elseif($_value['combienAime']==1)
	    {
		  echo 'Une personne aime : ';
	    }
	    echo $_value['nomsAime'];
				
	    if($_value['combienAimePas']>1)
	    {
		  echo '<br />'.$_value['combienAimePas'].' personnes n\'aiment pas : ';
	    }
	    elseif($_value['combienAimePas']==1)
	    {
		  echo '<br />Une personne n\'aime pas : ';
	    }
	    echo $_value['nomsAimePas'];
				
	    if($_value['combienAimePas'] == 0 && $_value['combienAime'] ==0)
	    {
		  echo 'Aucun membre s\'est exprimé';
	    }
	    
	    if($_value['dejaNote']==false && isset($_SESSION['id']))
	    {
		  echo '<br /><input class="note_photo_plus" type="button" name="1" value="J\'aime" id="note_plus"  onclick="envoyerJaimeJaimePas('.$_GET['id'].', '.$_SESSION['id'].', 1)" />    <input class="note_photo_moins" type="button" name="0" value="Je n\'aime pas" id="note_moins" onclick="envoyerJaimeJaimePas('.$_GET['id'].', '.$_SESSION['id'].', 0)" />';
	    }
	    echo '</p>';
				
      echo '</div>';
}


public function ShowBlocCommentaire($_value)
{
      echo '<div class="commentairesEvent">';
      
      foreach ($_value as $commentaire)
      {
	    echo <<<HERE
	    <div id="commentaire_{$commentaire->getId()}">
	    <p class="commentaire">{$commentaire->getCommentaire()}</p>
	    <p class="infos_commentaire">Par {$commentaire->getAuteur()} le {$commentaire->getDate()}
HERE;
	    if((isset($_SESSION['pseudo']) AND $_SESSION['pseudo']==$commentaire->getAuteur()) OR (isset($_SESSION['groupe']) AND $_SESSION['groupe'] == 'administrateur' )) // le membre qui a mi le commentaire ou l'administrateur pourra supprimer
	    {
		  echo ' | <img src="../Theme/img/bouton_supprimer.jpg" alt="Supprimer" onclick="showDeleteFormCommentaire('.$commentaire->getId().')" /></p>';
	    }
	    else
	    {
		  echo '</p>'; //permet de fermer le paragraphe si le membre ne peut pas supprimer
	    }
	    echo <<<HERE
	    <div class="option_supprimer" id="affichageMsgDelete_{$commentaire->getId()}"></div>
	    </div>
HERE;
      }
      	
      echo <<<HERE
      <br />
      <fieldset class="bigForm" >
	    <legend>Commenter la photo</legend>
	    <form action="galerie.php?EX=SendCommentairePhoto&album={$_GET['album']}&id={$_GET['id']}"  method="post" id="formCommentaire">
		  <textarea name="commentaire_photo" id="commentaire_photo" rows="3" cols="53" required></textarea><br />
		  <input type="submit" name="envoyer_commentaire" value="Poster" />
	    </form>
      </fieldset>
HERE;
      echo '</div>';
      
}

} // VBdd
?>