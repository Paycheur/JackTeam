<?php
class VAlbum
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
  public function ShowList ($_value)
  {
      
      	    echo '<table CELLSPACING="1px" id="tabListePhoto">';
	
		echo '<tr>';
	$i = 0;
      foreach ($_value as $album)
        {

			$titre=affichage($album->getTitre());
			$description=affichage($album->getDescription());
			$auteur = affichage($album->getAuteur());
			$date = remplacer_date($album->getDate());
			$img = affichage($album->getMiniature());
			if($i==2) //2 albums par ligne
			{
				echo '</tr><tr>';
				$i=0;
			}
echo <<<HERE
<td><div class="nailthumb-container-album square-thumb nailthumb-image-titles-animated-onload">
HERE;
      if((isset($_SESSION['pseudo']) AND $_SESSION['pseudo']==$album->getAuteur()) OR (isset($_SESSION['groupe']) AND $_SESSION['groupe'] == 'administrateur' )) 
      {
	    echo '<div class="optionsAlbum"><img src="../Theme/img/bouton_modifier.jpg" alt="Modifier" onclick="updateAlbum('.$album->getId().')" /><img src="../Theme/img/bouton_supprimer.jpg" alt="Supprimer" onclick="showDeleteFormAlbum('.$album->getId().');" /></div>';
      }
      //<div class="option_supprimer" id="affichageMsgDelete_{$album->getId()}"></div>
		//<div id="description{$album->getId()}" style="display:none;">{$description}</div>
echo <<<HERE
      <a href="?EX=ShowListePhoto&album={$album->getId()}"><img src="{$img}" title="{$titre}" /></a>
			
		  </div>
		  </td>
HERE;
		  $i++;
				
		}
		echo '</tr>';
	    echo '</table>';
      
      $this->ShowFormAddAlbum();
   
    return;
    
  } // ShowList ($_value)
  
  public function ShowFormAddAlbum()
  {
      echo <<<HERE
      <hr />
      <form method="post" action="?EX=SendAddAlbum" id="formAddAlbum">
	    <fieldset class="bigForm">
		<legend><span class="c_vert">Créer</span> un album</legend>
		  <div class="formField">
			<label for="titre">Titre :</label>
			<input type="text" name="titre" id="formTitre" />
		  </div>
		  <div class="formField">
			<label for="description">Description :</label><br />
    			<textarea name="description" id="formDescription" rows="4" cols="40" ></textarea>
		  </div>
		  <div class="formFieldSubmit">
			<input type="submit" name="SendAddAlbum" id="submitFormAlbum" value="Envoyer"/>
		  </div>
   			
	    </fieldset>
      </form>
HERE;
	
	return;
  } //ShowFormAddAlbum


} // VBdd
?>