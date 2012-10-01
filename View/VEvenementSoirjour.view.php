<?php
class VEvenementSoirjour
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

  
public function ShowEvent($_value)
{
    $titre = affichage($_value['evenement']->getTitre ());
    
    $datePost = remplacer_date ( $_value['evenement']->getDatepost () );
    $auteur = affichage($_value['evenement']->getIdauteur ()) ;
    $presentation = affichage($_value['evenement']->getPresentation ());
    $dateEvent =  affichage($_value['evenement']->getDateevent ());
    $lieu = affichage($_value['evenement']->getLieu ());
    $prix = affichage($_value['evenement']->getPrixEvent ());
    $heure = affichage($_value['evenement']->getHeure ());
    $type = affichage($_value['evenement']->getType ());
    if($type=='1')
    {
	$nomType='Soirée';
    }
    elseif($type=='2')
    {
	$nomType='Journée';
    }
    else
    {
	$nomType='N/D';
    }
    $apporter = affichage($_value['evenement']->getApporter ());
    
    echo <<<HERE
    
   <div class="type_bloc_content">
	<p class="titre_event">{$titre}</p>
	<p>Type : {$nomType}</p>
	<p style="text-align:right;font-size:11px;">Posté le {$datePost} par {$auteur}</p><br />
	<p>{$presentation}</p>
	<p><strong>Date : </strong>{$dateEvent}<br />
	<p><strong>Heure : </strong>{$heure}<br />
        <p><strong>Lieu: </strong>{$lieu}<br />
        <p><strong>Prix/pers (environ) : </strong>{$prix}€<br />
	<p><strong>Apporter: </strong>{$apporter}<br />
	</p><br />
    </div>
HERE;
    
    if($lieu=='N/D')
    {
	$this->ShowFormAddLieu();
	$this->ShowListePropositionLieu($_value['proposition']['lieux']['tab'], $_value['proposition']['lieux']['sond']);
    }
    
    
    $this->showListeParticipation($_value['participation']);
    $this->showBlocResume($_value['evenement']);
    $this->ShowBlocCommentaire($_value['commentaires']);

}


public function ShowFormAddLieu() //formulaire pour proposition de lieu
{
    
    echo <<<HERE
    <fieldset class="type_bloc1" style="width:250px;float:right" i>
	<legend id="titreFormLieu">Proposer un lieu</legend>
	<form method="post" action ="?EX=SendAddLieuSJ&idEvent={$_GET['idEvent']}" id="formAddLieu">
	  <label for="lieu">Lieu :</label><br />
	    <input type="text" name="lieu" id="formNomLieu" value="" /><br />
	    
	<label for="infoPrix">Infos. prix :</label><br />
	    <input type="text" name="infoPrix" id="formInfoPrix" value="" /><br />	

	    <input type="submit" id="sendLieu" name="envoyer" value="Envoyer"/>
	</form>
    </fieldset>
HERE;
}


public function ShowListePropositionLieu($lieux, $sond)
{
    
    if($lieux!=null) //si il y a au moins une date.
    {
	echo '<p>';
	    
		foreach($lieux as $lieu)
		{
		    $sondage = $sond[$lieu->getId()];
		    
		    $nomLieu = affichage($lieu->getLieu());
		    $infoPrix = affichage($lieu->getInfoprix());
		    $auteur = affichage($lieu->getAuteur());
		    $LieuId = affichage($lieu->getId());
		    $SondDejaFait = $sondage->verifDeja_fait ();
		    $SondMembresPour = $sondage->nomsPersonnesPour ();
		    $SondMembresContre = $sondage->nomsPersonnesContre ();
		    $SondNbMembresPour = affichage($sondage->combienDePersonnePour ());
		    $SondNbMembresContre = affichage($sondage->combienDePersonneContre ());
		    
		    echo <<<HERE
		    <div id="lieu{$LieuId}">
		    <button onclick="modifierPropositionLieuSJ({$LieuId}, {$_GET['idEvent']})">Modifier</button>
		    <ul>
		    <li>Lieu : <span>{$nomLieu}</span></li> 
		    <li>Prix : <span>{$infoPrix}</span></li>
		    <li>Proposé par : {$auteur}</li>
		    <li><span id="{$LieuId}nbMembresPourLieuSJ">{$SondNbMembresPour}</span> pour, <span id="{$LieuId}nbMembresContreLieuSJ">{$SondNbMembresContre}</span> contre</li>
		    <p id="infosSondLieuSJ{$LieuId}">
			Membres pour : {$SondMembresPour}<br />
			Membres contre : {$SondMembresContre}
		    </p>
HERE;
		    
		    if($SondDejaFait==0) //Si le membre avait déjà répondu au sondage par NON, on va griser le bouton Non et mettre Oui en update
		    {
			echo <<<HERE
			    <p><input type="button" id="{$LieuId}boutLieuSJOui" value="Oui" onclick="envoyerSondage('LieuSJ', 1, {$LieuId}, 'update')" /> <input type="button" id="{$LieuId}boutLieuSJNon" value="Non" onclick="envoyerSondage('LieuQJ', 0, {$LieuId}, 'update')" disabled="true" /></p>
HERE;
		    }
		    elseif($SondDejaFait==1) //Si le membre avait déjà au sondage par OUI
		    {
			echo <<<HERE
			<p><input type="button" id="{$LieuId}boutLieuSJOui" value="Oui" onclick="envoyerSondage('LieuSJ', 1, {$LieuId}, 'update')" disabled="true" /> <input type="button" id="{$LieuId}boutLieuSJNon" value="Non" onclick="envoyerSondage('LieuSJ', 0, {$LieuId}, 'update')"/></p>
HERE;
		    }
		    else //Si le membre n'a jamais répondu au sondage
		    {
		    echo <<<HERE
			<p><input type="button" id="{$LieuId}boutLieuSJOui" value="Oui" onclick="envoyerSondage('LieuSJ', 1, {$LieuId}, 'new')" /> <input type="button" id="{$LieuId}boutLieuSJNon" value="Non" onclick="envoyerSondage('LieuSJ', 0, {$LieuId}, 'new')"/></p>
HERE;
		    }
		    echo '</ul>';
		    echo '</div>';
		}
	echo '</p>';
    }
    else
    {
	echo '<p>Aucune proposition de lieu.</p>';
    }
    
}


public function showBlocResume($_value) //$_value = $evenement
{
    $resume = affichage($_value->getResume());
    echo <<<HERE
    <div class="type_bloc_content">
	<p>Résumé de l'organisation. Qui, Quand, Où ?</p>
	<p id="blocResume">{$resume}</p>
	<textarea name="insertBlocResume" id="insertBlocResume" rows="4" cols="50" style="display:none;"></textarea>
	<br /><button class="modifBlocResume" idEvent="{$_GET['idEvent']}">Modifier</button>
    </div>
HERE;
}

public function ShowBlocCommentaire($_value)
{
      echo '<div class="commentaires_photos">';
      
      foreach ($_value as $commentaire)
      {
	    echo <<<HERE
	    <div id="commentaire_{$commentaire->getId()}">
	    <p class="commentaire">{$commentaire->getCommentaire()}</p>
	    <p class="infos_commentaire">Par {$commentaire->getAuteur()} le {$commentaire->getDate()}
HERE;
	    if($_SESSION['pseudo']==$commentaire->getAuteur() OR $_SESSION['groupe'] == 'administrateur' ) // le membre qui a mi le commentaire ou l'administrateur pourra supprimer
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
      <fieldset class="type_bloc1" style="width:400px;">
	    <legend><span class="c_vert">Commenter</span> l'évenement</legend>
	    <form action="?EX=SendAddCommentaire&type={$_GET['type']}&idEvent={$_GET['idEvent']}" method="post" id="formCommentaire">
		  <textarea name="commentaire_photo" id="commentaire_photo" rows="3" cols="53" ></textarea><br />
		  <input type="submit" name="envoyer_commentaire" value="Poster" />
	    </form>
      </fieldset>
HERE;
      echo '</div>';
      
}

public function showListeParticipation($_value)
{
    echo <<<HERE
    <ul>
	<li><strong>Participeront : {$_value['oui']}</strong></li>
	<li>Participeront peut etre : {$_value['pe']}</li>
	<li>Ne participeront pas : {$_value['non']}</li>

    </ul>
HERE;
    if($_value['leMembre'] == 0)
    {
	echo <<<HERE
	<p id="msgParticipation">Tu ne participeras pas à l'évènement, sale Juif !</p>
	<input type="button" value="Je participe" id="ParticipationOui" onclick="envoyerParticipation('SendParticipationEvent', {$_GET['idEvent']}, 2)" />
	<input type="button" value="Peut être" id="ParticipationPe" onclick="envoyerParticipation('SendParticipationEvent', {$_GET['idEvent']}, 1)" />
HERE;
    }
    elseif($_value['leMembre'] == 1)
    {
	echo <<<HERE
	<p id="msgParticipation">Peut être que tu participeras...</p>
	<input type="button" value="Je participe" id="ParticipationOui" onclick="envoyerParticipation('SendParticipationEvent', {$_GET['idEvent']}, 2)" />
	<input type="button" value="Je ne participe pas" id="ParticipationNon" onclick="envoyerParticipation('SendParticipationEvent', {$_GET['idEvent']}, 0)" />
HERE;
    }
    elseif($_value['leMembre'] == 2)
    {
	echo <<<HERE
	<p id="msgParticipation">Cool ! Tu participe</p>
	<input type="button" value="Peut être" id="ParticipationPe" onclick="envoyerParticipation('SendParticipationEvent', {$_GET['idEvent']}, 1)" />
	<input type="button" value="Je ne participe pas" id="ParticipationNon" onclick="envoyerParticipation('SendParticipationEvent', {$_GET['idEvent']}, 0)" />
HERE;
    }
    else
    {
	echo <<<HERE
	<p id="msgParticipation">Est-ce que tu participes pour les cadeaux ?</p>
	<input type="button" value="Je participe" id="ParticipationOui" onclick="envoyerParticipation('SendParticipationEvent', {$_GET['idEvent']}, 2)" />
	<input type="button" value="Peut être" id="ParticipationPe" onclick="envoyerParticipation('SendParticipationEvent', {$_GET['idEvent']}, 1)" />
	<input type="button" value="Je ne participe pas" id="ParticipationNon" onclick="envoyerParticipation('SendParticipationEvent', {$_GET['idEvent']}, 0)" />
HERE;
    }
}

}
?>