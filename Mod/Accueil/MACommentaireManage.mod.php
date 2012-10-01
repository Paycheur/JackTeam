<?php
class MACommentaireManage extends Connexion
{
	private $confirmation;
	private $error;
	
	private $commentaires = array();
	
	public function __construct()
    {
		parent::__construct(BD_NAME, BD_LOGIN, BD_PASSWORD);
    }
    
    public function save(MACommentaire $commentaire)
    {
    	if ($commentaire->isValide() == 'ok')
    	{
        	$commentaire->isNew() ? $this->add($commentaire) : $this->modifier($commentaire);
        }
        else
        {
            $this->error= $commentaire->isValide();
        }
    }

	public function isModerateur($idCommentaire) //fonction qui permet de voir si l'utilisateur a bien le droit de modérer le commentaire (supprimer)
    {
		$reponse = $this->connexion->prepare('SELECT auteur FROM statut_commentaires WHERE id = :idcommentaire');
		$reponse->execute(array('idcommentaire' => bdd($idCommentaire)));
		$donnees = $reponse->fetch();
		if($donnees['auteur'] == $_SESSION['id'] || $_SESSION['groupe']=='administrateur')
		{
			return true;
		}
		else
		{
			$this->error ='Vous n\'avez pas le droit de supprimer ce commentaire.';
			return false;
		}
    }
    
    public function existe($idCommentaire) //est-ce que le commentaire existe bien ?
    {
    	$reponse = $this->connexion->prepare('SELECT * FROM statut_commentaires WHERE id = :idCommentaire');
		$reponse->execute(array('idCommentaire' => $idCommentaire));
		$donnees = $reponse->fetch(); 
		
		if($donnees)
		{
			return true;
		}
		else
		{
			$this->error = 'Le commentaire n\'existe pas.';
			return false;
			
		}
    }
        
    public function add(MAcommentaire $commentaire)
    {

        $reponse = $this->connexion->prepare('INSERT INTO statut_commentaires(contenu, auteur, date, idStatut) VALUES(:contenu, :auteur, :date, :idStatut)');
		$reponse->execute(array(
			'contenu' => bdd($commentaire->getContenu()),
			'auteur' => bdd($commentaire->getAuteur()),
			'date' => bdd($commentaire->getDate()),
			'idStatut' => bdd($commentaire->getIdStatut()),
			));
		
		$this->confirmation = 'commentaire ajouté.';
    }
    
   
	
	public function delete($id) 
	{
		if($this->existe($id))
		{
			if($this->isModerateur($id))
			{
				$reponse = $this->connexion->prepare('DELETE FROM statut_commentaires WHERE id= :id');
				$reponse->execute(array(
					'id' => $id,
					));
			
				$reponse->closeCursor();
				
				
				$this->confirmation = 'Le commentaire a été supprimé.';
			}
		}
	}
	
	
	public function getListe($idStatut, $nb_comment='') 
    {
    	
    	if($nb_comment == '')
    		$sql = 'SELECT id, contenu, auteur, date FROM statut_commentaires WHERE idStatut = :idStatut ORDER BY id';
    	else 
    	{
    		$commencement = $nb_comment-3;
    		$sql = 'SELECT id, contenu, auteur, date FROM statut_commentaires WHERE idStatut = :idStatut ORDER BY id ASC LIMIT '.$commencement.',3';
    	}
    		
    		
		$this->cadeaux = null;
		
		$reponse = $this->connexion->prepare($sql);
		$reponse->execute(array(
			'idStatut' => $idStatut
		));	
			
		while ($donnees = $reponse->fetch()) 
		{
			$membre= $this->connexion->prepare('SELECT pseudo FROM membres WHERE id= :id_membre');
			$membre->execute(array(
				'id_membre' => $donnees['auteur'],
				));
			$pseudo = $membre->fetch();
			
				
			$this->commentaires[] = new MACommentaire(array(
				'auteur' => $pseudo['pseudo'],
				'contenu' => $donnees['contenu'],
				'date' => date('d/m/Y à H:i',$donnees['date']),
				'idStatut' => $idStatut,
				'id' => $donnees['id'],
				));
				
		}
			
		$reponse->closeCursor();	
			
		return $this->commentaires;
	} 

	public function countCommentForStatut($idStatut)
	{
		$reponse = $this->connexion->prepare('SELECT count(1) as nb FROM statut_commentaires WHERE idStatut = :idStatut');
		$reponse->execute(array(
			'idStatut' => $idStatut
		));	
		
		$donnees = $reponse->fetch();
		return $donnees['nb'];
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