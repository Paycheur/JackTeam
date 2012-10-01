<?php
class VEvenementVacance
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
    $dateDebut =  affichage($_value['evenement']->getDateevent ());
    $dateFin =  affichage($_value['evenement']->getDatefin ());
    $lieu = affichage($_value['evenement']->getLieu ());
    $prix = affichage($_value['evenement']->getPrixEvent ());
	    
    echo <<<HERE
    <aside id="asideShowEvent">
HERE;
	$this->showListeParticipation($_value['participation']);
	$this->showBlocResume($_value['evenement']);
	
    echo <<<HERE
    </aside>
    <article id="articleShowEvent">
HERE;
    
    echo <<<HERE
	<p style="text-align:right;font-size:9px;">Posté le {$datePost} par {$auteur}</p><br />
	<div class="eventPresentation">
	<p>{$presentation}</p>
	<p>
	<ul>
	    <li><strong>Debut : </strong>{$dateDebut}</li>
	    <li><strong>Fin : </strong>{$dateFin}</li>
	    <li><strong>Lieu : </strong>{$lieu}</li>
	    <li><strong>Prix/pers (environ) : </strong>{$prix}€</li>
	</ul>
	</p>
HERE;
	
	echo '</div>';
	
    echo '</article>';
    echo '<div id="blocAddPropositions">';
	if($dateFin=='N/D')
	{
	    $this->ShowFormAddDates($dateDebut);
	}
	if($lieu=='N/D')
	{
	    $this->ShowFormAddLieu();
	}
    echo '</div>';
    echo '<div style="margin-top:20px;">';
    if($dateFin=='N/D' || $lieu=='N/D')
    {
	echo '<button class="showFormsPropositions">Ajouter une proposition</button>';
	if($dateFin=='N/D')
	{
		if(isset($_value['proposition']['dates']['sond']))
			$sondDate = $_value['proposition']['dates']['sond'];
		else 	
			$sondDate = array();
			
	    $this->ShowListePropositionDates($_value['proposition']['dates']['tab'], $sondDate);
	}
	if($lieu=='N/D')
	{
		if(isset($_value['proposition']['lieux']['sond']))
			$sondLieu = $_value['proposition']['lieux']['sond'];
		else 	
			$sondLieu = array();
			
	    $this->ShowListePropositionLieu($_value['proposition']['lieux']['tab'], $sondLieu);
	}
    }
    
    
    $this->ShowBlocCommentaire($_value['commentaires']);
   echo '</div>';
}

public function ShowFormAddDates($dateDebut) //formulaire pour proposition de date, avec comme argument $dateDebut si il y en a une...
{
    $calDebut= new Calendrier("formAddDates", "dateDebut");
    $calFin= new Calendrier("formAddDates", "dateFin");
    
    echo <<<HERE
    <fieldset class="smallForm">
	<legend id="titreFormDates">Proposer des dates</legend>
	<form method="post" action ="?EX=SendAddDates&idEvent={$_GET['idEvent']}" id="formAddDates">
	    <div class="formField">
		<label for="dateDebut">Date debut :</label>
		<input type="text" name="dateDebut" id="formDateDebut" value="{$dateDebut}" onClick="{$calDebut->get_strPopup("../Inc/calendrier.pop.inc.php", 350, 170)}" readonly="readonly"/>
	    </div>
    	    
	    <div class="formField">
		<label for="dateFin">Date fin :</label>
		<input type="text" name="dateFin" id="formDateFin" value="" onClick="{$calFin->get_strPopup("../Inc/calendrier.pop.inc.php", 350, 170)}" readonly="readonly"/>
	    </div>

	    <input type="submit" id="sendDates" name="envoyer" value="Envoyer"/>
	</form>
    </fieldset>
HERE;
}

public function ShowFormAddLieu() //formulaire pour proposition de lieu
{
    
    echo <<<HERE
    <fieldset class="smallForm">
	<legend id="titreFormLieu">Proposer un lieu</legend>
	<form method="post" action ="?EX=SendAddLieuV&idEvent={$_GET['idEvent']}" id="formAddLieu">
	    <div class="formField"> 
		<label for="lieu">Lieu :</label>
		<input type="text" name="lieu" id="formNomLieu" value="" />
	    </div>
	    
	    <div class="formField">
		<label for="infoPrix">Infos. prix :</label>
		<input type="text" name="infoPrix" id="formInfoPrix" value="" />
	    </div>

	    <input type="submit" id="sendLieu" name="envoyer" value="Envoyer"/>
	</form>
    </fieldset>
HERE;
}

public function ShowListePropositionDates($dates, $sond)
{
    $calDebut= new Calendrier("formAddDates", "formDateDebut");
    $calFin= new Calendrier("formAddDates", "formDateFin");
    
	echo <<<HERE
	<table class="listeSondage">
	    <tr>
		<th colspan="4">Proposition de dates.</th>
	    </tr>
HERE;
	    
		
    if($dates!=null) //si il y a au moins une date.
    {
		foreach($dates as $date)
		{
		    $sondage = $sond[$date->getId()];
		    
		    $dateDebut = affichage($date->getDatedebut());
		    $dateFin = affichage($date->getDatefin());
		    $auteur = affichage($date->getAuteur());
		    $DatesId = affichage($date->getId());
		    $SondDejaFait = $sondage->verifDeja_fait ();
		    $SondMembresPour = $sondage->nomsPersonnesPour ();
		    $SondMembresContre = $sondage->nomsPersonnesContre ();
		    $SondNbMembresPour = affichage($sondage->combienDePersonnePour ());
		    $SondNbMembresContre = affichage($sondage->combienDePersonneContre ());
		    
		    echo <<<HERE
		    <tr>
			<td>
			    <p id="dates{$DatesId}">
				<strong>Début :</strong> <span>{$dateDebut}</span><br />
				<strong>Fin :</strong> <span>{$dateFin}</span><br />
			    </p>
			</td>
			<td>
			<p>
			    <span id="{$DatesId}nbMembresPourDatesV">{$SondNbMembresPour}</span> pour, <span id="{$DatesId}nbMembresContreDatesV">{$SondNbMembresContre}</span> contre
		    
		    
HERE;
		    
		    if($SondDejaFait==0) //Si le membre avait déjà répondu au sondage par NON, on va griser le bouton Non et mettre Oui en update
		    {
			echo <<<HERE
			    <input type="button" id="{$DatesId}boutDatesVOui" value="Oui" onclick="envoyerSondage('DatesV', 1, {$DatesId}, 'update')" /> <input type="button" id="{$DatesId}boutDatesVNon" value="Non" onclick="envoyerSondage('DatesV', 0, {$DatesId}, 'update')" disabled="true" />
HERE;
		    }
		    elseif($SondDejaFait==1) //Si le membre avait déjà au sondage par OUI
		    {
			echo <<<HERE
			<input type="button" id="{$DatesId}boutDatesVOui" value="Oui" onclick="envoyerSondage('DatesV', 1, {$DatesId}, 'update')" disabled="true" /> <input type="button" id="{$DatesId}boutDatesVNon" value="Non" onclick="envoyerSondage('DatesV', 0, {$DatesId}, 'update')"/>
HERE;
		    }
		    else //Si le membre n'a jamais répondu au sondage
		    {
		    echo <<<HERE
			<input type="button" id="{$DatesId}boutDatesVOui" value="Oui" onclick="envoyerSondage('DatesV', 1, {$DatesId}, 'new')" /> <input type="button" id="{$DatesId}boutDatesVNon" value="Non" onclick="envoyerSondage('DatesV', 0, {$DatesId}, 'new')"/>
HERE;
		    }
		    echo <<<HERE
		    </p>
		    </td>
		    <td>
			<p id="infosSondDatesV{$DatesId}" >
			    Membres pour : {$SondMembresPour}<br />
			    Membres contre : {$SondMembresContre}
			</p>
		    </td>
		    <td>
			<p><em>Proposé par {$auteur} </em></p>
		    <img src="../Img/bouton_modifier.jpg" alt="Modifier" class="imgModifier" title="Modifier" onclick="modifierPropositionDatesV({$DatesId}, {$_GET['idEvent']})" />
		    </td>
		</tr>
HERE;
		}
    }
    else
    {
	echo '<tr><td><p>Aucune proposition de date.</p></td></tr>';
    }
    
    echo '</table>';
    
    
}

public function ShowListePropositionLieu($lieux, $sond)
{
	echo <<<HERE
	<table class="listeSondage">
	    <tr>
		<th colspan="4">Proposition de lieux.</th>
	    </tr>
HERE;
    if($lieux!=null) //si il y a au moins une date.
    {
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
		    <tr>
			<td>
			    <p id="lieu{$LieuId}">
				<strong>Lieu :</strong> <span>{$nomLieu}</span><br />
				<strong>Prix :</strong> <span>{$infoPrix}</span>
			    </p>
			</td>
			<td>
			    <p>
				<span id="{$LieuId}nbMembresPourLieuV">{$SondNbMembresPour}</span> pour, <span id="{$LieuId}nbMembresContreLieuV">{$SondNbMembresContre}</span> contre
HERE;
		    
		    if($SondDejaFait==0) //Si le membre avait déjà répondu au sondage par NON, on va griser le bouton Non et mettre Oui en update
		    {
			echo <<<HERE
			    <input type="button" id="{$LieuId}boutLieuVOui" value="Oui" onclick="envoyerSondage('LieuV', 1, {$LieuId}, 'update')" /> <input type="button" id="{$LieuId}boutLieuVNon" value="Non" onclick="envoyerSondage('LieuV', 0, {$LieuId}, 'update')" disabled="true" />
HERE;
		    }
		    elseif($SondDejaFait==1) //Si le membre avait déjà au sondage par OUI
		    {
			echo <<<HERE
			<input type="button" id="{$LieuId}boutLieuVOui" value="Oui" onclick="envoyerSondage('LieuV', 1, {$LieuId}, 'update')" disabled="true" /> <input type="button" id="{$LieuId}boutLieuVNon" value="Non" onclick="envoyerSondage('LieuV', 0, {$LieuId}, 'update')"/>
HERE;
		    }
		    else //Si le membre n'a jamais répondu au sondage
		    {
		    echo <<<HERE
			<input type="button" id="{$LieuId}boutLieuVOui" value="Oui" onclick="envoyerSondage('LieuV', 1, {$LieuId}, 'new')" /> <input type="button" id="{$LieuId}boutLieuVNon" value="Non" onclick="envoyerSondage('LieuV', 0, {$LieuId}, 'new')"/>
HERE;
    	    }
		    echo <<<HERE
			</p>
		    </td>
		    <td>
			<p id="infosSondLieuV{$LieuId}">
			    Membres pour : {$SondMembresPour}<br />
			    Membres contre : {$SondMembresContre}
			</p>
		    </td>
		    <td>
		    <p><em>Proposé par {$auteur} </em></p>
		    <img src="../Img/bouton_modifier.jpg" alt="Modifier" title="Modifier" onclick="modifierPropositionLieuV({$LieuId}, {$_GET['idEvent']})" />
		    </td>
		</tr>
HERE;
		}
    }
    else
    {
	echo '<tr><td><p>Aucune proposition de lieu.</p></td></tr>';
    }
    echo '</table>';

    
}


public function showBlocResume($_value) //$_value = $evenement
{
    $resume = affichage($_value->getResume());
    echo <<<HERE
    
	<h2 id="h2Gris">Résumé de l'organisation.</h2>
    <div class="blocResume">
	<button class="modifBlocResume" idEvent="{$_GET['idEvent']}">Modifier</button>
	<p id="blocResume">{$resume}</p>
	<textarea name="insertBlocResume" id="insertBlocResume" rows="4" cols="50" style="display:none;"></textarea>
    </div>
HERE;
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
      <fieldset class="bigForm" >
	    <legend>Commenter l'évenement</legend>
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
   <div class="listeParticipation">
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
     echo <<<HERE
    <table>
	<tr>
	    <th>Present</th>
	    <th>Peut-être</th>
	    <th>Absent</th>
	</tr>
	<tr>
	    <td>{$_value['oui']}</td>
	    <td>{$_value['pe']}</td>
	    <td>{$_value['non']}</td>
	</tr>
    </table>
    </div>
HERE;
}

}
?>