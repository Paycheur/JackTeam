<?php
class MEEvenementVacanceManage extends MEEvenementManage
{
	
	
    public function __construct()
    {
		parent::__construct();
    }
	
	
	public function save(MEEvenementVacance $evenement)
    {
    	if ($evenement->isValide())
    	{
        	$evenement->isNew() ? $this->add($evenement) : $this->modifier($evenement);
        }
        else
        {
            $this->error = $evenement->isValide();
        }
    }
    
    
    public function isVacance($idEvent) //fonction pour voir si l'évenement est bien de type anniv (type_event = 1)
    {
	$reponse = $this->getConnexion()->prepare('SELECT type_event FROM evenements WHERE id =:id');
	$reponse->execute(array(
	    'id'=> $idEvent,
	    ));
	$donnees = $reponse->fetch();
	if($donnees['type_event'] == 2)
	{
	   return true;
	}
	else
	{
	    return false;
	}
        
    }
    public function add(MEEvenementVacance $evenement)
    {
		
        $reponse = $this->getConnexion()->prepare('INSERT INTO evenements(titre, presentation, id_auteur, type_event, date_event, date_post, resume) VALUES(:titre, :presentation, :id_auteur, :type_event, :date_event, :date_post, :resume)');
		$reponse->execute(array(
			'titre' => bdd($evenement->getTitre()),
			'presentation' => bdd($evenement->getPresentation()),
			'id_auteur' => bdd($evenement->getIdauteur()),
			'type_event' => '2',
			'date_event' => bdd($evenement->getDateevent()),
			'date_post' => bdd($evenement->getDatepost()),
			'resume' => bdd($evenement->getResume()),
			));
		
		$reponse->closeCursor();
		
		$reponse= $this->getConnexion()->prepare('SELECT id FROM evenements WHERE date_post=:date_post');
		$reponse->execute(array(
			'date_post' => bdd($evenement->getDatepost()),
			));
		
		$donnees_id= $reponse->fetch();
		
		$req = $this->getConnexion()->prepare('INSERT INTO evenements_vacances(dateFin, lieu, prix, idEvent) VALUES(:dateFin, :lieu, :prix, :idEvent)');
		$req->execute(array(
			'dateFin' => bdd($evenement->getDatefin()),
                        'lieu' => bdd($evenement->getLieu()),
                        'prix' => bdd($evenement->getPrixevent()),
			'idEvent' => bdd($donnees_id['id']),
			));
		
		$this->setConfirmation('L\'événement a bien été ajouté.');
    }
    
       
    public function getUnique($id)
    {

	    $reponse = $this->getConnexion()->prepare('SELECT titre, presentation, id_auteur, id, date_event, date_post, resume FROM evenements WHERE id = :id');
		$reponse->execute(array('id' => $id));
		$donnees = $reponse->fetch(); 
		
		$reponse_event = $this->getConnexion()->prepare('SELECT dateFin, lieu, prix FROM evenements_vacances WHERE idEvent = :id');
		$reponse_event->execute(array('id' => $id));
		$donnees_event = $reponse_event->fetch(); 
			
			
		$reponse_auteur = $this->getConnexion()->prepare('SELECT pseudo FROM membres WHERE id= :id_membre'); //on récupère le membre selon l'id_membre
		$reponse_auteur->execute(array(
			'id_membre' => $donnees['id_auteur'],
			));
		$donnees_auteur = $reponse_auteur->fetch();
		
		
		if($donnees)
		{
			$evenement = new MEEvenementVacance($donnees['titre'], $donnees['presentation'], $donnees_auteur['pseudo'], $donnees['date_event'], $donnees['date_post'], $donnees['id'], $donnees['resume'], $donnees_event['prix'], $donnees_event['dateFin'], $donnees_event['lieu']);
		}
		else
		{
			$this->setError('L\'évenement n\'existe pas.');
		}
		
		$reponse->closeCursor();
		
		return $evenement;
    }
	
	
	public function modifier(MEEvenementVacance $evenement)
	{
	    if($this->evenementExiste($evenement->getId()) AND $this->isModerateur($evenement->getId()) AND $this->isVacance($evenement->getId()))
	    {
	    $reponse = $this->getConnexion()->prepare('UPDATE evenements SET titre=:titre, presentation=:presentation, date_event=:date_event WHERE id=:idEvent');
	    $reponse->execute(array(
		'idEvent' => bdd($evenement->getId()),
		'titre' => bdd($evenement->getTitre()),
		'presentation' => bdd($evenement->getPresentation()),
		'date_event' => bdd($evenement->getDateevent()),
	    ));
	    
	    $reponse->closeCursor();
	    
	    $reponse = $this->getConnexion()->prepare('UPDATE evenements_vacances SET dateFin=:dateFin, prix=:prix, lieu=:lieu WHERE idEvent=:idEvent');
	    $reponse->execute(array(
		'idEvent' => bdd($evenement->getId()),
		'dateFin' => bdd($evenement->getDatefin()),
		'prix' => bdd($evenement->getPrixEvent()),
		'lieu' => bdd($evenement->getLieu()),
	    ));
	    
	    $reponse->closeCursor();
	    
	    $this->confirmation = 'L\'évenement a bien été modifié.';
	    }
	    else
	    {
		$this->error = 'Vous n\'avez pas les droit pour faire cette opération.';
	    }
	    
	}
	
	
	public function delete($idEvent)
	{
	    if($this->evenementExiste($idEvent) AND $this->isModerateur($idEvent) AND $this->isVacance($idEvent))
	    {
		$reponse = $this->connexion->prepare('DELETE FROM evenements WHERE id= :id');
		$reponse->execute(array(
		    'id' => $idEvent,
		    ));
		
		$reponse->closeCursor();
		
		$reponse = $this->connexion->prepare('DELETE FROM evenements_vacances WHERE idEvent= :id');
		$reponse->execute(array(
		    'id' => $idEvent,
		    ));
		
		$reponse->closeCursor();
		
		$reponse = $this->connexion->prepare('SELECT id FROM evenements_vacances_l WHERE idEvent=:id');
		$reponse->execute(array(
		    'id' => $idEvent,
		    ));
		
		while($donneesLieu=$reponse->fetch()) //pour supprimer tout les sondage lié aux cadeau de cet évenement.
		{
		    $delSondLieu = $this->connexion->prepare('DELETE FROM evenements_vacances_l_sond WHERE idLieu=:idLieu');
		    $delSondLieu->execute(array(
			'idLieu' => $donneesLieu['id'],
		    ));
		    
		    $delSondLieu->closeCursor();
		    
		}
		$reponse->closeCursor();
		
		$reponse = $this->connexion->prepare('SELECT id FROM evenements_vacances_d WHERE idEvent=:id');
		$reponse->execute(array(
		    'id' => $idEvent,
		    ));
		
		while($donneesDates=$reponse->fetch()) //pour supprimer tout les sondage lié aux cadeau de cet évenement.
		{
		    $delSondDates = $this->connexion->prepare('DELETE FROM evenements_vacances_d_sond WHERE idDates=:idDates');
		    $delSondDates->execute(array(
			'idDates' => $donneesDates['id'],
		    ));
		    
		    $delSondDates->closeCursor();
		    
		}
		$reponse->closeCursor();
		
		$reponse = $this->connexion->prepare('DELETE FROM evenements_cadeau_d WHERE idEvent=:id');
		$reponse->execute(array(
		    'id' => $idEvent,
		    ));
		
		$reponse->closeCursor();
		
		$reponse = $this->connexion->prepare('DELETE FROM evenements_cadeau_l WHERE idEvent=:id');
		$reponse->execute(array(
		    'id' => $idEvent,
		    ));
		
		$reponse->closeCursor();
		
		$reponse = $this->connexion->prepare('DELETE FROM evenements_participants WHERE id_event=:id');
		$reponse->execute(array(
		    'id' => $idEvent,
		    ));
		
		$reponse->closeCursor();
		
		$this->confirmation = 'L\'événement a bien été supprimé.';
	    }
	    else
	    {
		$this->error = 'Vous ne pouvez pas supprimer cet évenement.';
	    }
	}

}
?>