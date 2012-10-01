<?php
class MAStatutManage extends Connexion
{
	private $confirmation;
	private $error;
	
	private $statuts = array();
	
	public function __construct()
    {
		parent::__construct(BD_NAME, BD_LOGIN, BD_PASSWORD);
    }
    
    public function save(MAStatut $statut)
    {
    	if ($statut->isValide() == 'ok')
    	{
        	$statut->isNew() ? $this->add($statut) : $this->modifier($statut);
        }
        else
        {
            $this->error= $statut->isValide();
        }
    }

	public function isModerateur($idStatut) //fonction qui permet de voir si l'utilisateur a bien le droit de modérer le statut (supprimer)
    {
		$reponse = $this->connexion->prepare('SELECT auteur FROM statut WHERE id = :idStatut');
		$reponse->execute(array('idStatut' => bdd($idStatut)));
		$donnees = $reponse->fetch();
		if($donnees['auteur'] == $_SESSION['id'] || $_SESSION['groupe']=='administrateur')
		{
			return true;
		}
		else
		{
			$this->error ='Vous n\'avez pas le droit de supprimer ce statut.';
			return false;
		}
    }
    
    public function existe($idStatut) //est-ce que le statut existe bien ?
    {
    	$reponse = $this->connexion->prepare('SELECT * FROM statut WHERE id = :idStatut');
		$reponse->execute(array('idStatut' => $idStatut));
		$donnees = $reponse->fetch(); 
		
		if($donnees)
		{
			return true;
		}
		else
		{
			$this->error = 'Le statut n\'existe pas.';
			return false;
			
		}
    }
        
    public function add(MAStatut $statut)
    {

        $reponse = $this->connexion->prepare('INSERT INTO statut(contenu, auteur, date, photo, tag) VALUES(:contenu, :auteur, :date, :photo, :tag)');
		$reponse->execute(array(
			'contenu' => bdd($statut->getContenu()),
			'auteur' => bdd($statut->getAuteur()),
			'date' => bdd($statut->getDate()),
			'photo' => bdd($statut->getPhoto()),
			'tag' => bdd($statut->getTag()),
			));
		
		$this->confirmation = 'Statut ajouté.';
    }
    
   
	
	public function delete($id) 
	{
		if($this->existe($id))
		{
			if($this->isModerateur($id))
			{
				$reponse = $this->connexion->prepare('DELETE FROM statut WHERE id= :id');
				$reponse->execute(array(
					'id' => $id,
					));
			
				$reponse->closeCursor();
				
				
				$this->confirmation = 'Le statut a été supprimé.';
			}
		}
	}
	
	
	public function getListe($idMembre = '') 
    {
    	if($idMembre == '')
    		$sql = 'SELECT id, contenu, auteur, date, photo, tag FROM statut ORDER BY id';
    	else
    		$sql = 'SELECT id, contenu, auteur, date, photo, tag FROM statut WHERE auteur = :auteur ORDER BY id';
		$this->cadeaux = null;
		$reponse = $this->connexion->prepare($sql);
		$reponse->execute(array('auteur' => $idMembre));	
			
		while ($donnees = $reponse->fetch()) 
		{
			$membre= $this->connexion->prepare('SELECT pseudo FROM membres WHERE id= :id_membre');
			$membre->execute(array(
				'id_membre' => $donnees['auteur'],
				));
			$pseudo = $membre->fetch();
			
				
			$this->statuts[] = new MAStatut(array(
				'auteur' => $pseudo['pseudo'],
				'contenu' => $donnees['contenu'],
				'date' => date('d/m/Y à H:i',$donnees['date']),
				'photo' => $donnees['photo'],
				'tag' => $donnees['tag'],
				'id' => $donnees['id'],
				));
				
		}
			
		$reponse->closeCursor();	
			
		return $this->statuts;
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