<?php
class VEvenementsAnniv
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
    $date = remplacer_date ( $_value['evenement']->getDatepost () );
    $auteur = affichage($_value['evenement']->getIdauteur ()) ;
    $presentation = affichage($_value['evenement']->getPresentation ());
    $membreAnniv =  affichage($_value['evenement']->getMembreanniv ());
    echo <<<HERE
    
   <div class="type_bloc_content">
	<p class="titre_event">{$titre}</p>
	<p style="text-align:right;font-size:11px;">Posté le {$date} par {$auteur}</p><br />
	<p>{$presentation}</p>
	<p><strong>Date de l'évenement : </strong>{$_value['evenement']->getDateevent () }<br />
	<strong>Anniversaire de : </strong>{$membreAnniv}<br />
	</p><br />
    </div>
HERE;

    $this->ShowListeCadeauxNonVal($_value);
    $this->ShowListeCadeauxVal($_value);
    $this->showFormAddCadeau();
    $this->ShowBlocCommentaire($_value['commentaires']);

}

public function ShowListeCadeauxNonVal($_value) //$_value -> $_value['evenement'], $_value['cadeauxNonVal']['tab'] (représente la liste des cadeaux), $_value['cadeauxNonVal']['sond'][x] représente le sondage pour le cadeau d'id X, $_value['participation] : gestion des participants
{
    $evenement = $_value['evenement']; //va servir uniquement pour voir quel est le membre qui a créé l'évenement (pour pouvoir le modérer)
    echo '<button class="LienDetailsCadeaux" style="margin-top:8px;">Afficher les détails</button>';
    
    if($_value['cadeauxNonVal']['tab']!=null)
    {
    foreach ( $_value['cadeauxNonVal']['tab'] as $cadeau )
    {
	$sondage = $_value['cadeauxNonVal']['sond'][$cadeau->getId()]; //représente le sondage du cadeau
	
	$CadId= affichage($cadeau->getId());
	$CadDesc = affichage($cadeau->getDescription ());
	$CadPrix = affichage($cadeau->getPrix ());
	$CadMembre = affichage($cadeau->getAuteur ());
	$SondMembresPour = $sondage->nomsPersonnesPour ();
	$SondMembresContre = $sondage->nomsPersonnesContre ();
	$SondNbMembresPour = affichage($sondage->combienDePersonnePour ());
	$SondNbMembresContre = affichage($sondage->combienDePersonneContre ());
	$SondDejaFait = $sondage->verifDeja_fait (); //on vérifie si le membre a déjà répondu une fois au sondage ou pas
	
	echo '<p class="info_cadeau">- ' . affichage($cadeau->getTitre ());
	if ($_SESSION ['pseudo'] == $evenement->getIdauteur () or $_SESSION ['groupe'] == 'administrateur')
	{
		echo ' <span style="font-size:11px">[ <a href="evenements.php?EX=SendValiderCadeau&idEvent=' . $_GET ['idEvent'] . '&idCad=' . $CadId. '" >Valider</a> ]</span>';
	}
	if ($_SESSION ['pseudo'] == $cadeau->getAuteur () or $_SESSION ['groupe'] == 'administrateur' or $_SESSION ['pseudo'] == $evenement->getIdauteur ())
	{
		echo ' <span style="font-size:11px">[ <a onclick="showDeleteFormCadeau('.$_GET['idEvent'].', '.$CadId.')">Supprimer</a> ]</span>';
		echo '<div class="option_supprimer" id="affichageMsgDelete_'.$CadId.'"></div>';
	}
	echo '</p>';
	
	
	
	echo <<<HERE
	
	<ul class="details_cadeau"  style="display:none;">
	    <li><strong>Description : </strong>{$CadDesc}</li>
	    <li><strong>Prix : </strong>{$CadPrix} €</li>
	    <li><strong>Proposé par : </strong>{$CadMembre}</li>
	    <li><strong>Membre(s) pour : </strong>{$SondMembresPour}</li>
	    <li><strong>Membre(s) contre :</strong>{$SondMembresContre}</li>
	</ul>
	<p><span id="{$CadId}nbMembresPourCadeau">{$SondNbMembresPour}</span> pour, <span id="{$CadId}nbMembresContreCadeau">{$SondNbMembresContre}</span> contre</p>
HERE;
	if($SondDejaFait==0) //Si le membre avait déjà répondu au sondage par NON, on va griser le bouton Non et mettre Oui en update
	{
	    echo <<<HERE
	    <p><input type="button" id="{$CadId}boutCadeauOui" value="Oui" onclick="envoyerSondage('Cadeau', 1, {$CadId}, 'update')" /> <input type="button" id="{$CadId}boutNon" value="Non" onclick="envoyerSondage('SendSondCadeau', 0, {$CadId}, 'update')" disabled="true" /></p>
HERE;
	}
	elseif($SondDejaFait==1) //Si le membre avait déjà au sondage par OUI
	{
	    echo <<<HERE
	    <p><input type="button" id="{$CadId}boutCadeauOui" value="Oui" onclick="envoyerSondage('Cadeau', 1, {$CadId}, 'update')" disabled="true" /> <input type="button" id="{$CadId}boutNon" value="Non" onclick="envoyerSondage('SendSondCadeau', 0, {$CadId}, 'update')"/></p>
HERE;
	}
	else //Si le membre n'a jamais répondu au sondage
	{
	    echo <<<HERE
	    <p><input type="button" id="{$CadId}boutCadeauOui" value="Oui" onclick="envoyerSondage('Cadeau', 1, {$CadId}, 'new')" /> <input type="button" id="{$CadId}boutNon" value="Non" onclick="envoyerSondage('SendSondCadeau', 0, {$CadId}, 'new')"/></p>
HERE;
	}
    }
    } //fin du if
    else
    {
	echo '<p>Il n\'y a aucun cadeau en proposition.</p>';
    }
}

public function ShowListeCadeauxVal($_value)
{
    $evenement = $_value['evenement'];
    $prixEvent = affichage($evenement->getPrixEvent());
    $auteurEvent = affichage($evenement->getIdauteur());
    //$prixParParticipant = $prixEvent / $evenement['nbOui'];
    
    echo <<<HERE
    <p class="liste_cadeaux_val">Listes des cadeaux validés</p>
    
HERE;
    if($_value['cadeauxVal']!=null)
    {
	echo '<ul style="background-color:#e4fad8;border:1px dotted #818181;">';
    foreach ( $_value['cadeauxVal'] as $cadeau )
    {
	$CadId= affichage($cadeau->getId());
	$CadDesc = affichage($cadeau->getDescription ());
	$CadPrix = affichage($cadeau->getPrix ());
	$CadMembre = affichage($cadeau->getAuteur ());
	$CadTitre = affichage($cadeau->getTitre ());
	
	echo '<li><strong>' . $CadTitre . '</strong>';
	if ($_SESSION ['pseudo'] == $auteurEvent or $_SESSION ['groupe'] == 'administrateur')
	{
		echo ' <span style="font-size:11px">[ <a href="evenements.php?EX=SendDevaliderCadeau&idEvent=' . $_GET ['idEvent'] . '&idCad=' . $CadId. '" >Ne plus valider</a> ] </span></li>';
	}
	echo <<<HERE
	
	<ul class="details_cadeau_val">
	    <li><strong>Description : </strong>{$CadDesc}</li>
	    <li><strong>Prix : </strong>{$CadPrix} €</li>
	    <li><strong>Proposé par : </strong>{$CadMembre}</li>
	</ul>
HERE;
    }
     echo '</ul>';
    } //fin du if
    else
    {
	echo '<p>Aucun cadeau validé.</p>';
    }
   
    
    echo '<p style="text-align:right">Prix total : ' . $prixEvent. ' €</p><br />';
    
    //echo '<p style="font-size:18px;text-align:right;">PRIX par participant : <span class="c_rouge"><strong>' . $prixParParticipant . '  €</strong></span></p>';
    
    $this->showListeParticipation($_value['participation']);
    
    $this->showBlocResume($evenement);
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

public function showFormAddCadeau()
{
    echo <<<HERE
    <fieldset class="type_bloc1" style="width:250px;float:right">
	<legend><span class="c_vert">Idée</span> de cadeau</legend>
	<form method="post" action ="?EX=SendAddCadeau&idEvent={$_GET['idEvent']}" id="formAddCadeau">
	    <label for="titre">Titre du cadeau :</label><br />
	    <input type="text" name="titre" id="titre" size="40"/><br/>

	    <label for="description">Description :</label><br />
	    <textarea name="description" id="description" rows="4" cols="30" ></textarea>

	    <label for="prix">Prix : </label>
	    <input type="text" name="prix" id="prix" size="5" maxlength="5" />€

	    <p style="font-size:10px;">Utiliser le "." pour une virgule. Si tu ne sais pas le prix exact du cadeau, tu te renseigne ou tu mets un prix maximum.</p>

	    <input type="submit" id="valide" name="envoyer" value="Envoyer"/>
	</form>
    </fieldset>
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


}
?>