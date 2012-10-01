<?php
class MEDatesManage extends Connexion
{
	private $confirmation;
	private $error;
	
	private $dates= array();
	
	public function __construct()
    {
	parent::__construct(BD_NAME, BD_LOGIN, BD_PASSWORD);
    }
    
    public function save(MEDates $dates)
    {
    	if ($dates->isValide() == 'ok')
    	{
        	$dates->isNew() ? $this->add($dates) : $this->update($dates);
        }
        else
        {
            $this->error= $dates->isValide();
        }
    }

	public function isModerateur($idDates) //fonction qui permet de voir si l'utilisateur a bien le droit de modérer la proposition
    {
	$reponse = $this->connexion->prepare('SELECT idAuteur FROM evenements_vacances_d WHERE id = :idDates');
	$reponse->execute(array('idDates' => bdd($idDates)));
	$donnees = $reponse->fetch();
	if($donnees['idAuteur'] == $_SESSION['id'] || $_SESSION['groupe']=='administrateur')
	{
		return true;
	}
	else
	{
		$this->error ='Vous n\'êtes pas modérateur de cette proposition.';
		return false;
	}
    }
    
        public function existe($idDates) //est-ce que la proposition de date existe bien
    {
    	$reponse = $this->connexion->prepare('SELECT * FROM evenements_vacances_d WHERE id = :idDates');
		$reponse->execute(array('idDates' => $idDates));
		$donnees = $reponse->fetch(); 
		
		if($donnees)
		{
			return true;
		}
		else
		{
			$this->error = 'La proposition de date n\'existe pas.';
			return false;
			
		}
    }
        
    public function add(MEDates $dates)
    {
		$reponse = $this->connexion->prepare('INSERT INTO evenements_vacances_d(dateDebut, dateFin, idEvent, idAuteur) VALUES(:dateDebut, :dateFin, :idEvent, :idAuteur)');
		$reponse->execute(array(
			'dateDebut' => bdd($dates->getDatedebut()),
			'dateFin' => bdd($dates->getDatefin()),
			'idEvent' => bdd($dates->getIdevent()),
			'idAuteur' => bdd($dates->getAuteur()),
			));
		
		$this->confirmation = 'La proposition de dates a bien été envoyée.';
    }
    
    public function update(MEDates $dates)
    {
	if($this->existe($dates->getId()) AND $this->isModerateur($dates->getId()))
	{
		$reponse = $this->connexion->prepare('UPDATE evenements_vacances_d SET dateDebut=:dateDebut, dateFin=:dateFin WHERE id=:id');
		$reponse->execute(array(
			'dateDebut' => bdd($dates->getDatedebut()),
			'dateFin' => bdd($dates->getDatefin()),
			'id' => bdd($dates->getId()),
			));
		
		$this->confirmation = 'La proposition de dates a bien été modifée.';
	}
	else
	{
		$this->error = 'Vous ne pouvez pas modifier cette proposition.';
	}
    }
	
	/*public function delete($id) 
	{
		if($this->existe($id))
		{
			if($this->isModerateur($id))
			{
				$reponse = $this->connexion->prepare('DELETE FROM evenements_cadeau_c WHERE id= :id');
				$reponse->execute(array(
					'id' => $id,
					));
			
				$reponse->closeCursor();
				
				$reponse = $this->connexion->prepare('DELETE FROM evenements_cadeau_c_sond WHERE id_cadeau= :id_cadeau');
				$reponse->execute(array(
					'id_cadeau' => $id,
					));
	
				$reponse->closeCursor();
				
				$this->confirmation = 'Le cadeau a bien été supprimé';
			}
		}
	}*/
	
	
	public function getListe($idEvent) 
    {
		$this->dates = null;
		$reponse = $this->connexion->prepare('SELECT dateFin, dateDebut, idAuteur, idEvent, id FROM evenements_vacances_d WHERE idEvent=:idEvent ORDER BY id');
		$reponse->execute(array(
			'idEvent' => $idEvent,
			));	
			
		while ($donnees = $reponse->fetch()) 
		{
			$membre= $this->connexion->prepare('SELECT pseudo FROM membres WHERE id= :id_membre');
			$membre->execute(array(
				'id_membre' => $donnees['idAuteur'],
				));
			$pseudo = $membre->fetch();
			
				
			$this->dates[] = new MEDates(array(
				'id_auteur' => $pseudo['pseudo'],
				'datefin' => $donnees['dateFin'],
				'datedebut' => $donnees['dateDebut'],
				'id_event' => $donnees['idEvent'],
				'id' => $donnees['id'],
				));
				
		}
			
		$reponse->closeCursor();	
			
		return $this->dates;
	} 

	
		
    public function getConfirmation()
	{
		return $this->confirmation;
	}
		
	public function getError()
	{
		return $this->error;
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