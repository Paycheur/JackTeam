<?php
class VEvenements
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

  
public function ShowListEvent($_value)
{
    echo <<<HERE
	<p>
	    <a href="?EX=ShowFormAddEvent&type=1">Créer un événement anniversaire</a><br />
	    <a href="?EX=ShowFormAddEvent&type=2">Créer un événement vacance</a><br />
	    <a href="?EX=ShowFormAddEvent&type=3">Créer un événement soirée/journée</a>
	</p>
    <article>
HERE;
    foreach ($_value as $evenement )
    {
	$datePost = remplacer_date($evenement->getDatepost ());
	$titre = affichage($evenement->getTitre());
	$presentation = affichage($evenement->getPresentation());
	$presentation = Tronquer_Texte($presentation, 150);
	$id = affichage($evenement->getId());
	$type = affichage($evenement->getTypeevent());
	if($type=='1')
	{
	    $nomType="ANNIVERSAIRE";
	}
	elseif($type=='2')
	{
	    $nomType="VACANCE";
	}
	elseif($type=='3')
	{
	    $nomType="JOURNEE/SOIREE";
	}
	else
	{
	    $nomTpe="N/D";
	}
	
	echo <<<HERE
	<div class="blocListEvent">
	    <p class="listEventTitre">{$titre}</p>
	    <p><em>Type :</em> <strong>{$nomType}</strong> <span style="float:right">{$evenement->getJoursrestant ()}</span></p>
	    <p class="listEventPresentation">{$presentation}</p>
	    <p style="font-size:9px;">
		Créé le {$datePost} par {$evenement->getIdauteur ()}
		<span style="float:right"><a href="?EX=ViewEvent&type={$type}&idEvent={$id}">Voir</a> | <a href="?EX=ViewFormUpdateEvent&type={$type}&idEvent={$id}">Modifier</a> | <a onclick="showDeleteFormEvent({$id}, {$type})">Supprimer</a></span>
	    </p>
	    <p class="option_supprimer" id="affichageMsgDelete_{$id}"></p>
	</div>
HERE;
    }

    echo "</article>";

}

public function ShowFormAddAnniv($_value)
{
    //SI c'est une modification de l'évenement, on récupère les données :
    if($_value['evenement']!=null)
    {
	$titre = affichage_form($_value['evenement']->getTitre());
	$presentation = affichage_form($_value['evenement']->getPresentation());
	$date = affichage_form($_value['evenement']->getDateevent());
    }
    else
    {
	$titre = '';
	$presentation = '';
	$date = '';
    }
    
    
    echo <<<HERE
    <fieldset class="bigForm">
HERE;
    if($titre!=null) //si c'est une modification
    {
	echo '<legend>Cadeau(x) anniversaire</legend>';
	    echo '<form method="post" action ="?EX=SendUpdateEventAnniv&idEvent='.$_GET['idEvent'].'" id="formAddAnniv" name="formAddAnniv">';
    }
    else //sinon c'est un nouvel événement
    {
	echo '<legend>Cadeau(x) anniversaire</legend>';
	    echo '<form method="post" action ="?EX=SendAddEventAnniv" id="formAddAnniv" name="formAddAnniv">';
    }
    echo <<<HERE
		<div class="formField">
		    <label for="titre">Titre de l'événement:</label>
		    <input type="text" name="titre" id="titre" value="{$titre}"/>
		</div>
    		
		
    		
		<div class="formField">		
    		<label for="membre_anniv">Membre fêtant son anniversaire:</label>
		<select name="membre_anniv" id="membre_anniv">
HERE;
    			foreach ( $_value['membres'] as $membre )
			{
    				echo '<option value="'.$membre['id'].'">'.$membre['pseudo'].'</option>';
			}
echo <<<HERE
		</select>
		</div>
    		
		<div class="formField">		
    		<label for=date">Date maximum d'achat:</label>
    		<input type="text" name="date" id="date" value="{$date}" onClick="{$_value['cal']->get_strPopup("../Inc/calendrier.pop.inc.php", 350, 170)}" onBlur="" readonly="readonly"/>
    		
		</div>
		
		<div>	
		    <label for="presentation">Presentation:</label>
		    <textarea name="presentation" id="presentation" rows="4" cols="40" >{$presentation}</textarea>
		</div>
    		<p>Une fois l'événement posté, vous pourrez ajouter des idées de cadeau.<br />
    		Vous êtes modérateur de cet événement, vous pourrez validé ou non les cadeaux.</p>
     		<input type="submit" id="valide" name="envoyer" value="Envoyer" />
   	    </form>
    </fieldset>
HERE;
}

public function ShowFormAddVacance($_value)
{
    //SI c'est une modification de l'évenement, on récupère les données :
    if($_value['evenement']!=null)
    {
	$titre = affichage_form($_value['evenement']->getTitre());
	$presentation = affichage_form($_value['evenement']->getPresentation());
	$dateDebut = affichage_form($_value['evenement']->getDateevent());
	$dateFin = affichage_form($_value['evenement']->getDatefin());
	$prix =  affichage_form($_value['evenement']->getPrixevent());
	$lieu = affichage_form($_value['evenement']->getLieu());
    }
    else
    {
	$titre = '';
	$presentation = '';
	$dateDebut = '';
	$dateFin= '';
	$prix= '';
	$liei = '';
    }
    
    
    echo <<<HERE
    <fieldset class="bigForm">
HERE;
    if($titre!=null) //si c'est une modification
    {
	echo '<legend><span class="c_vert">Modifier un événement : </span> Vacance</legend>';
	    echo '<form method="post" action ="?EX=SendUpdateEventVacance&idEvent='.$_GET['idEvent'].'" id="formAddVacance" name="formAddVacance">';
    }
    else //sinon c'est un nouvel événement
    {
	echo '<legend><span class="c_vert">Créer un événement : </span> Vacance</legend>';
	    echo '<form method="post" action ="?EX=SendAddEventVacance" id="formAddVacance" name="formAddVacance">';
    }
    echo <<<HERE
	    <div class="formField">
		<label for="titre">Titre de l'événement:</label>
		<input type="text" name="titre" id="titre" value="{$titre}"/>
    	    </div>
	    <div class="formField">
	   	<label for="presentation">Presentation:</label>
    		<textarea name="presentation" id="presentation" rows="4" cols="40" >{$presentation}</textarea>
	    </div>
	    
		<p>La date est-elle définie ?</p>
	    <div class="formField">
		<label for="oui">OUI</label><input type="radio" name="dateDefinie" value="oui" id="dateOui" />
	    </div>
	    <div class="formField">
		<label for="non">NON</label><input type="radio" name="dateDefinie" value="non" id="dateNon" />
	    </div>
	    
		<div id="definitionDate" style="display:none;">
		    <div class="formField">
    		<label for="dateDebut">Date debut :</label>
    		<input type="text" name="dateDebut" id="dateDebut" value="{$dateDebut}" onClick="{$_value['calDebut']->get_strPopup("../Inc/calendrier.pop.inc.php", 350, 170)}" readonly="readonly"/>
		    </div>
		    <div class="formField">
		<label for="dateFin">Date fin :</label>
    		<input type="text" name="dateFin" id="dateFin" value="{$dateFin}" onClick="{$_value['calFin']->get_strPopup("../Inc/calendrier.pop.inc.php", 350, 170)}" readonly="readonly"/>
		    </div>
		</div>
	    
		<p>Le lieu est-il défini ?</p>
	    <div class="formField">
		<label for="oui">OUI</label><input type="radio" name="lieuDefini" value="oui" id="lieuOui" />
	    </div>
	    <div class="formField">
		<label for="non">NON</label><input type="radio" name="lieuDefini" value="non" id="lieuNon" />
	    </div>
	    
	    <div id="definitionLieu" style="display:none;">
		<div class="formField">
		<label for="lieu">Lieu :</label>
		<input type="text" name="lieu" id="lieu" value="{$lieu}" />
		</div>
	    </div>
	    <div class="formField">
		<label for="lieu">Prix/personne (environ) :</label>
		<input type="number" name="prix" id="prix" value="{$prix}" />
	    </div>
	    <div class="formField">
     		<input type="submit" id="valide" name="envoyer" value="Envoyer" />
	    </div>
   	    </form>
    </fieldset>
HERE;
}

public function ShowFormAddSoirjour($_value)
{
    //SI c'est une modification de l'évenement, on récupère les données :
    if($_value['evenement']!=null)
    {
	$titre = affichage_form($_value['evenement']->getTitre());
	$presentation = affichage_form($_value['evenement']->getPresentation());
	$date = affichage_form($_value['evenement']->getDateevent());
	$heure= affichage_form($_value['evenement']->getHeure());
	$lieu = affichage_form($_value['evenement']->getLieu());
	$prix = affichage_form($_value['evenement']->getPrixEvent());
	$apporter = affichage_form($_value['evenement']->getApporter());
    }
    else
    {
	$titre = '';
	$presentation = '';
	$date = '';
	$heure='';
	$lieu='';
	$prix='';
	$apporter='';
    }
    
    
    echo <<<HERE
    <fieldset class="bigForm">
HERE;
    if($titre!=null) //si c'est une modification
    {
	echo '<legend><span class="c_vert">Modifier un événement : </span> Soirée/Journée</legend>';
	    echo '<form method="post" action ="?EX=SendUpdateEventSoirjour&idEvent='.$_GET['idEvent'].'" id="formAddSoirjour" name="formAddSoirjour">';
    }
    else //sinon c'est un nouvel événement
    {
	echo '<legend><span class="c_vert">Créer un événement : </span> Soirée/Journée</legend>';
	    echo '<form method="post" action ="?EX=SendAddEventSoirjour" id="formAddSoirjour" name="formAddSoirjour">';
    }
    echo <<<HERE
		<p>Type</p>
		<div class="formField">
		    <label for="soir">Soirée</label><input type="radio" name="type" value="1" id="typeSoir" />
		</div>
		<div class="formField">
		    <label for="jour">Journée</label><input type="radio" name="type" value="2" id="typeJour" />
		</div>
		<div class="formField">
		    <label for="titre">Titre de l'événement:</label>
		    <input type="text" name="titre" id="titre" value="{$titre}"/>
    		</div>
		<div class="formField">
		    <label for="presentation">Presentation:</label>
		    <textarea name="presentation" id="presentation" rows="4" cols="40" >{$presentation}</textarea>
    		</div>
		<div class="formField">
		    <label for=date">Date :</label>
		    <input type="text" name="date" id="date" value="{$date}" onClick="{$_value['cal']->get_strPopup("../Inc/calendrier.pop.inc.php", 350, 170)}" onBlur="" readonly="readonly"/>
    		</div>
		<div class="formField">
		    <label for="heure">Heure : </label>
		    <input type="text" name="heure" id="heure" value="{$heure}" />
		</div>
		
		<p>Le lieu où l'activité est-il défini ?</p>
		<div class="formField">
		    <label for="oui">OUI</label><input type="radio" name="lieuDefini" value="oui" id="lieuOui" />
		</div>
		<div class="formField">
		    <label for="non">NON</label><input type="radio" name="lieuDefini" value="non" id="lieuNon" />
		</div>
		
		<div id="definitionLieu" class="formField" style="display:none;">
		    <label for="lieu">Lieu / Activité : </label>
		    <input type="text" name="lieu" id="lieu" value="{$lieu}" />
		</div>
		
		<div class="formField">
		    <label for="prix">Prix/pers : </label>
		    <input type="text" name="prix" id="prix" value="{$prix}" />
		</div>
		
		<div class="formField">
		    <label for="apporter">Apporter quelque chose ? Si oui, quoi ?</label>
		    <textarea name="apporter" id="apporter" rows="4" cols="40" >{$apporter}</textarea>
		</div>
		
		<div class="formField">
		    <input type="submit" id="valide" name="envoyer" value="Envoyer" />
		</div>
   	    </form>
    </fieldset>
HERE;
}

}
?>