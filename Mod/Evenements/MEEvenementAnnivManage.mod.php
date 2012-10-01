<?php
class MEEvenementAnnivManage extends MEEvenementManage
{
	
	
    public function __construct()
    {
		parent::__construct();
    }
	
	
	public function save(MEEvenementAnniv $evenement)
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
    
    
    public function canSee($idEvent) //fonction qui permet de savoir si le membre peut voir l'évenement ou pas
    {
	$reponse = $this->getConnexion()->prepare('SELECT membre_anniv FROM evenements_cadeau WHERE id_event =:id');
	$reponse->execute(array(
	    'id'=> $idEvent,
	    ));
	$donnees = $reponse->fetch();
	if($donnees['membre_anniv'] == $_SESSION['id'])
	{
	     $this->error = 'L\'évenement n\'existe pas.'; //fake erreur
	    return false; //ne peut pas voir
	   
	}
	else
	{
	    return true;
	}
    }
    
    public function isAnniv($idEvent) //fonction pour voir si l'évenement est bien de type anniv (type_event = 1)
    {
	$reponse = $this->getConnexion()->prepare('SELECT type_event FROM evenements WHERE id =:id');
	$reponse->execute(array(
	    'id'=> $idEvent,
	    ));
	$donnees = $reponse->fetch();
	if($donnees['type_event'] == 1)
	{
	   return true;
	}
	else
	{
	    return false;
	}
        
    }
    public function add(MEEvenementAnniv $evenement)
    {
		
        $reponse = $this->getConnexion()->prepare('INSERT INTO evenements(titre, presentation, id_auteur, type_event, date_event, date_post, resume) VALUES(:titre, :presentation, :id_auteur, :type_event, :date_event, :date_post, :resume)');
		$reponse->execute(array(
			'titre' => bdd($evenement->getTitre()),
			'presentation' => bdd($evenement->getPresentation()),
			'id_auteur' => bdd($evenement->getIdauteur()),
			'type_event' => '1',
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
		
		$req = $this->getConnexion()->prepare('INSERT INTO evenements_cadeau(membre_anniv, id_event) VALUES(:membre_anniv, :id_event)');
		$req->execute(array(
			'membre_anniv' => bdd($evenement->getMembreanniv()),
			'id_event' => bdd($donnees_id['id']),
			));
		
		$this->setConfirmation('L\'événement a bien été ajouté. Vous pouvez maitenant ajouter des cadeaux<br />');
    }
    
       
    public function getUnique($id)
    {
    	$reponse = $this->getConnexion()->prepare('SELECT titre, presentation, id_auteur, id, date_event, date_post, resume FROM evenements WHERE id = :id');
		$reponse->execute(array('id' => $id));
		$donnees = $reponse->fetch(); 
		
		$reponse_event = $this->getConnexion()->prepare('SELECT membre_anniv, p_achat FROM evenements_cadeau WHERE id_event = :id');
		$reponse_event->execute(array('id' => $id));
		$donnees_event = $reponse_event->fetch(); 
			
			
		$reponse_auteur = $this->getConnexion()->prepare('SELECT pseudo FROM membres WHERE id= :id_membre'); //on récupère le membre selon l'id_membre
		$reponse_auteur->execute(array(
			'id_membre' => $donnees['id_auteur'],
			));
		$donnees_auteur = $reponse_auteur->fetch();
		
		$reponse_membre_anniv = $this->getConnexion()->prepare('SELECT pseudo FROM membres WHERE id= :id_membre'); //on récupère le membre selon l'id_membre
		$reponse_membre_anniv->execute(array(
			'id_membre' => $donnees_event['membre_anniv'],
			));
		$donnees_membre_anniv = $reponse_membre_anniv->fetch();
		
		
		if($donnees)
		{
			$prixEvent = $this->prixEvent($id);
			$evenement = new MEEvenementAnniv($donnees['titre'], $donnees['presentation'], $donnees_auteur['pseudo'], $donnees['date_event'], $donnees['date_post'], $donnees_membre_anniv['pseudo'], $donnees['id'], $prixEvent, $donnees['resume']);
		}
		else
		{
			$this->setError('L\'évenement n\'existe pas.');
		}
		
		$reponse->closeCursor();
		
		return $evenement;
	}
	
	public function prixEvent($id)
	{
		$reponse = $this->getConnexion()->prepare('SELECT prix FROM evenements_cadeau_c WHERE id_event= :id_event AND valide = 1');
		$reponse->execute(array('id_event' => $id));
		$prix=0;
		while($donnees = $reponse->fetch())
		{
			$prix=$prix+$donnees['prix'];
		}
		
		$reponse->closeCursor();
		
		return $prix;
	}
	
	public function modifier(MEEvenementAnniv $evenement)
	{
	    
	    if($this->evenementExiste($evenement->getId()) AND $this->canSee($evenement->getId()) AND $this->isModerateur($evenement->getId()) AND $this->isAnniv($evenement->getId()))
	    {
	    $reponse = $this->getConnexion()->prepare('UPDATE evenements SET titre=:titre, presentation=:presentation, date_event=:date_event WHERE id=:idEvent');
	    $reponse->execute(array(
		'idEvent' => bdd($evenement->getId()),
		'titre' => bdd($evenement->getTitre()),
		'presentation' => bdd($evenement->getPresentation()),
		'date_event' => bdd($evenement->getDateevent()),
	    ));
	    
	    $reponse->closeCursor();
	    
	    $reponse = $this->getConnexion()->prepare('UPDATE evenements_cadeau SET membre_anniv=:membreAnniv WHERE id_event=:idEvent');
	    $reponse->execute(array(
		'idEvent' => bdd($evenement->getId()),
		'membreAnniv' => bdd($evenement->getMembreanniv()),
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
	    if($this->evenementExiste($idEvent) AND $this->isModerateur($idEvent) AND $this->isAnniv($idEvent))
	    {
		$reponse = $this->connexion->prepare('DELETE FROM evenements WHERE id= :id');
		$reponse->execute(array(
		    'id' => $idEvent,
		    ));
		
		$reponse->closeCursor();
		
		$reponse = $this->connexion->prepare('DELETE FROM evenements_cadeau WHERE id_event= :id');
		$reponse->execute(array(
		    'id' => $idEvent,
		    ));
		
		$reponse->closeCursor();
		
		$reponse = $this->connexion->prepare('SELECT id FROM evenements_cadeau_c WHERE id_event=:id');
		$reponse->execute(array(
		    'id' => $idEvent,
		    ));
		
		while($donnees=$reponse->fetch()) //pour supprimer tout les sondage lié aux cadeau de cet évenement.
		{
		    $delSond = $this->connexion->prepare('DELETE FROM evenements_cadeau_c_sond WHERE id_cadeau=:idCadeau');
		    $delSond->execute(array(
			'idCadeau' => $donnees['id'],
		    ));
		    
		    $delSond->closeCursor();
		    
		}
		$reponse->closeCursor();
		
		$reponse = $this->connexion->prepare('DELETE FROM evenements_cadeau_c WHERE id_event=:id');
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