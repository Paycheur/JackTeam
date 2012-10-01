<?php
class MEEvenementManage extends Connexion
{
	
	private $confirmation;
	private $error;
	
	private $evenements= array();
	
	public function __construct()
    {
		parent::__construct(BD_NAME, BD_LOGIN, BD_PASSWORD);
    }
	
	
    
    public function evenementExiste($id_event)
    {
    	$reponse = $this->connexion->prepare('SELECT titre FROM evenements WHERE id = :id_event');
		$reponse->execute(array('id_event' => $id_event));
		$donnees = $reponse->fetch(); 
		
		if($donnees)
		{
			return true;
		}
		else
		{
			$this->error = 'L\'évenement n\'existe pas.';
			return false;
			
		}
    }
    
    public function isModerateur($idEvent) //fonction qui permet de voir si l'utilisateur a bien le droit de modérer l'évenement
    {
	$reponse = $this->connexion->prepare('SELECT id_auteur FROM evenements WHERE id = :id_event');
	$reponse->execute(array('id_event' => $idEvent));
	$donnees = $reponse->fetch();
	if($donnees['id_auteur'] == $_SESSION['id'] || $_SESSION['groupe']=='administrateur')
	{
		return true;
	}
	else
	{
		return false;
	}
    }	
       
	
	/********** VERIF PARTICIPANTS *************/
	
	public function verifParticipation($idEvent) //est-ce que le membre "participe" deja a l'évenement ?
	{
		$reponse= $this->connexion->prepare('SELECT id_membre, participe FROM evenements_participants WHERE id_event= :id_event');	
		$reponse->execute(array(
			'id_event' => bdd($idEvent),
			));
			
		$result=3; //rien ne peut etre égal a 2 car OUI=2 et NON=0 PEUT ETRE=1
		while($donnees = $reponse->fetch())
		{
			if($_SESSION['id'] == $donnees['id_membre']) //savoir si le membre a déjà noté la cadeau ou pas
			{
				$result= $donnees['participe'];
			}
		}
		$reponse->closeCursor();
	
		return $result;
	}
	
	/***************** LES MEMBRES QUI PARTICIPE *********************/
	
	public function combienDeParticipants($id)
	{
		//Avoir le nombre de personne qui aime la cadeau
		$reponse= $this->connexion->prepare('SELECT COUNT(*) AS nbpersonne FROM evenements_participants WHERE id_event= :id_event AND participe = 2');	
		$reponse->execute(array(
			'id_event' => $id,
			));
		$donnees = $reponse->fetch();
	
		$nbparticipants=$donnees['nbpersonne'];
		
		$reponse->closeCursor();
		
		return $nbparticipants;
	}
	

					
	public function nomsParticipants($id)
	{
		//récupérer le nom des personnes qui aiment
	$reponse= $this->connexion->prepare('SELECT id_membre FROM evenements_participants WHERE id_event= :id_event AND participe = 2');	
	$reponse->execute(array(
			'id_event' => $id,
			));
	$i=1; //init de la variable pour voir si on met une virgule ou pas après le pseudo	
	$membres = '';
	while($donnees = $reponse->fetch())
	{
		
		$membre= $this->connexion->prepare('SELECT pseudo FROM membres WHERE id= :id_membre');
		$membre->execute(array(
			'id_membre' => $donnees['id_membre'],
			));
		
		while($pseudo = $membre->fetch())
		{
			if($i < $this->combienDeParticipants($id)) //virgule ?
			{
				$membres = $membres.'<a href="">'.affichage($pseudo['pseudo']).'</a>, '; 
			}
			else
			{
				$membres = $membres.'<a href="">'.affichage($pseudo['pseudo']).'</a>';
			}
			
			$i++;
		}
		$membre->closeCursor();
	}
	$reponse->closeCursor();
	
	return $membres;
	}
	
	/*************** LES MEMBRES QUI NE PARTICIPE PAS *****************/
	
	public function combienDeNonParticipants($id)
	{
		//Avoir le nombre de personne qui aime la cadeau
		$reponse= $this->connexion->prepare('SELECT COUNT(*) AS nbpersonne FROM evenements_participants WHERE id_event= :id_event AND participe = 0');	
		$reponse->execute(array(
			'id_event' => $id,
			));
		$donnees = $reponse->fetch();
	
		$nbparticipants=$donnees['nbpersonne'];
		
		$reponse->closeCursor();
		
		return $nbparticipants;
	}
	

					
	public function nomsNonParticipants($id)
	{
		//récupérer le nom des personnes qui aiment
	$reponse= $this->connexion->prepare('SELECT id_membre FROM evenements_participants WHERE id_event= :id_event AND participe = 0');	
	$reponse->execute(array(
			'id_event' => $id,
			));
	$i=1; //init de la variable pour voir si on met une virgule ou pas après le pseudo	
	$membres = '';
	while($donnees = $reponse->fetch())
	{
		
		$membre= $this->connexion->prepare('SELECT pseudo FROM membres WHERE id= :id_membre');
		$membre->execute(array(
			'id_membre' => $donnees['id_membre'],
			));
		
		while($pseudo = $membre->fetch())
		{
			if($i < $this->combienDeNonParticipants($id)) //virgule ?
			{
				$membres = $membres.'<a href="">'.affichage($pseudo['pseudo']).'</a>, '; 
			}
			else
			{
				$membres = $membres.'<a href="">'.affichage($pseudo['pseudo']).'</a>';
			}
			
			$i++;
		}
		$membre->closeCursor();
	}
	$reponse->closeCursor();
	
	return $membres;
	}
	
	/*************** LES MEMBRES QUI PARTICIPERONT PEUT ETRE ***************/
	
	public function combienDeParticipantsPasSur($id)
	{
		//Avoir le nombre de personne qui aime la cadeau
		$reponse= $this->connexion->prepare('SELECT COUNT(*) AS nbpersonne FROM evenements_participants WHERE id_event= :id_event AND participe = 1');	
		$reponse->execute(array(
			'id_event' => $id,
			));
		$donnees = $reponse->fetch();
	
		$nbparticipants=$donnees['nbpersonne'];
		
		$reponse->closeCursor();
		
		return $nbparticipants;
	}
	

					
	public function nomsParticipantsPasSur($id)
	{
		//récupérer le nom des personnes qui aiment
	$reponse= $this->connexion->prepare('SELECT id_membre FROM evenements_participants WHERE id_event= :id_event AND participe = 1');	
	$reponse->execute(array(
			'id_event' => $id,
			));
	$i=1; //init de la variable pour voir si on met une virgule ou pas après le pseudo	
	$membres = '';
	while($donnees = $reponse->fetch())
	{
		
		$membre= $this->connexion->prepare('SELECT pseudo FROM membres WHERE id= :id_membre');
		$membre->execute(array(
			'id_membre' => $donnees['id_membre'],
			));
		
		while($pseudo = $membre->fetch())
		{
			if($i < $this->combienDeParticipantsPasSur($id)) //virgule ?
			{
				$membres = $membres.'<a href="">'.affichage($pseudo['pseudo']).'</a>, '; 
			}
			else
			{
				$membres = $membres.'<a href="">'.affichage($pseudo['pseudo']).'</a>';
			}
			
			$i++;
		}
		$membre->closeCursor();
	}
	$reponse->closeCursor();
	
	return $membres;
	}
	
    
    public function getListe()
    {
    	
		$reponse = $this->connexion->query('SELECT titre, presentation, date_event, date_post, id_auteur, type_event, id FROM evenements ORDER BY id');
		
		while ($donnees = $reponse->fetch()) 
		{
			$membre= $this->connexion->prepare('SELECT pseudo FROM membres WHERE id= :id_membre');
			$membre->execute(array(
				'id_membre' => $donnees['id_auteur'],
				));
			$pseudo = $membre->fetch();
			
			if($donnees['date_event']=='N/D') 
			{
				$jours_restant= 'La date n\'est pas encore définie.';
			}
			else
			{
			$fin_evenement = explode("-",$donnees['date_event']); //permet de creer un tableau avec création de case après chaque "-"
			$fin_evenement_timestemp = mktime(0, 0, 0, $fin_evenement[1], $fin_evenement[0], $fin_evenement[2]);
			$date_actuelle = time();
			$diff = $fin_evenement_timestemp - $date_actuelle;
			$nb_jours_restant = floor(($diff/86400)+1);
			if($nb_jours_restant==0)
			{
				$jours_restant='Aujourd\'hui !';
			}
			elseif($nb_jours_restant>0)
			{
				$jours_restant='Il reste '.$nb_jours_restant.' jours.';
			}
			elseif($nb_jours_restant<0)
			{
				
				$jours_restant = 'L\'évènement est terminé depuis le '.$donnees['date_event'].'.';
			}
			}
				
			$this->evenements[] = new MEEvenement(array(
				'id_auteur' => $pseudo['pseudo'],
				'titre' => $donnees['titre'],
				'presentation' => $donnees['presentation'],
				'date_event' => $donnees['date_event'],
				'date_post' => $donnees['date_post'],
				'type_event' => $donnees['type_event'],
				'jours_restant' => $jours_restant,
				'id' => $donnees['id'], //faire les trucs de sécurité affichage / envois BDD
				));
				
		}
			
		$reponse->closeCursor();	
			
		return $this->evenements;
	}
	
	public function addParticipation($idEvent, $idMembre, $value)
	{
		if($value<3 && $value>=0) //sécurité pour ne pas avoir d'autre valeur de participation que 0, 1 ou 2.
		{
			if($this->verifParticipation($idEvent)==3)
			{
				$req2=$this->connexion->prepare('INSERT INTO evenements_participants(id_membre, id_event, participe) VALUES(:id_membre, :id_event, :participe)');
				$req2->execute(array(
				'id_membre' => bdd($idMembre),
				'id_event' => bdd($idEvent),
				'participe' => bdd($value),
				));
			}
			else
			{
				$req2=$this->connexion->prepare('UPDATE evenements_participants SET participe = :participe WHERE id_membre = :id_membre AND id_event = :id_event');
				$req2->execute(array(
				'id_membre' => bdd($idMembre),
				'id_event' => bdd($idEvent),
				'participe' => bdd($value),
				));
			}
		}
		
		
		
	}
	
	public function updateBlocResume($idEvent, $content)
	{
		if($this->evenementExiste($idEvent) AND $this->isModerateur($idEvent))
		{
			$req=$this->connexion->prepare('UPDATE evenements SET resume = :resume WHERE id = :idEvent');
			$req->execute(array(
				'resume' => bdd($content),
				'idEvent' => bdd($idEvent),
				));
			$req->closeCursor();
		}
		
	}
	
	
	public function getConfirmation()
	{
		return $this->confirmation;
	}
		
	public function getError()
	{
		return $this->error;
	}
	
	public function getConnexion()
	{
		return $this->connexion;
	}
		
	public function setConfirmation($message)
	{
		$this->confirmation=$message;
	}

	public function setError($message)
	{
		$this->error=$message;
	}
}
?>