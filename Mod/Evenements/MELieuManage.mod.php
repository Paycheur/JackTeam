<?php
class MELieuManage extends Connexion
{
	private $confirmation;
	private $error;
	
	private $table;
	
	private $lieux= array();
	
	public function __construct($table)
    {
	$this->table=$table;
	parent::__construct(BD_NAME, BD_LOGIN, BD_PASSWORD);
    }
    
    public function save(MELieu $lieu)
    {
    	if ($lieu->isValide() == 'ok')
    	{
        	$lieu->isNew() ? $this->add($lieu) : $this->update($lieu);
        }
        else
        {
            $this->error= $lieu->isValide();
        }
    }

	public function isModerateur($idLieu) //fonction qui permet de voir si l'utilisateur a bien le droit de modérer la proposition
    {
	$reponse = $this->connexion->prepare('SELECT idAuteur FROM '.$this->table.' WHERE id = :idLieu');
	$reponse->execute(array('idLieu' => bdd($idLieu)));
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
    
        public function existe($idLieu) //est-ce que la proposition de date existe bien
    {
    	$reponse = $this->connexion->prepare('SELECT * FROM '.$this->table.' WHERE id = :idLieu');
		$reponse->execute(array('idLieu' => $idLieu));
		$donnees = $reponse->fetch(); 
		
		if($donnees)
		{
			return true;
		}
		else
		{
			$this->error = 'La proposition de lieu n\'existe pas.';
			return false;
			
		}
    }
        
    public function add(MELieu $lieu)
    {
		$reponse = $this->connexion->prepare('INSERT INTO '.$this->table.'(lieu, infoPrix, idEvent, idAuteur) VALUES(:lieu, :infoPrix, :idEvent, :idAuteur)');
		$reponse->execute(array(
			'lieu' => bdd($lieu->getLieu()),
			'infoPrix' => bdd($lieu->getInfoprix()),
			'idEvent' => bdd($lieu->getIdevent()),
			'idAuteur' => bdd($lieu->getAuteur()),
			));
		
		$this->confirmation = 'La proposition de lieu a bien été envoyée.';
    }
    
    public function update(MELieu $lieu)
    {
	if($this->existe($lieu->getId()) AND $this->isModerateur($lieu->getId()))
	{
		$reponse = $this->connexion->prepare('UPDATE '.$this->table.' SET lieu=:lieu, infoPrix=:infoPrix WHERE id=:id');
		$reponse->execute(array(
			'lieu' => bdd($lieu->getLieu()),
			'infoPrix' => bdd($lieu->getInfoprix()),
			'id' => bdd($lieu->getId()),
			));
		
		$this->confirmation = 'La proposition de lieu a bien été modifée.';
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
		$this->lieux = null;
		$reponse = $this->connexion->prepare('SELECT lieu, infoPrix, idAuteur, idEvent, id FROM '.$this->table.' WHERE idEvent=:idEvent ORDER BY id');
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
			
				
			$this->lieux[] = new MELieu(array(
				'id_auteur' => $pseudo['pseudo'],
				'lieu' => $donnees['lieu'],
				'infoPrix' => $donnees['infoPrix'],
				'id_event' => $donnees['idEvent'],
				'id' => $donnees['id'],
				));
				
		}
			
		$reponse->closeCursor();	
			
		return $this->lieux;
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