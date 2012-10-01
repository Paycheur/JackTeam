<?php
class MECadeauManage extends Connexion
{
	private $confirmation;
	private $error;
	
	private $cadeaux= array();
	
	public function __construct()
    {
	parent::__construct(BD_NAME, BD_LOGIN, BD_PASSWORD);
    }
    
    public function save(MECadeau $cadeau)
    {
    	if ($cadeau->isValide() == 'ok')
    	{
        	$cadeau->isNew() ? $this->add($cadeau) : $this->modifier($cadeau);
        }
        else
        {
            $this->error= $cadeau->isValide();
        }
    }

	public function isModerateur($idCadeau) //fonction qui permet de voir si l'utilisateur a bien le droit de modérer le cadeau
    {
	$reponse = $this->connexion->prepare('SELECT id_auteur FROM evenements_cadeau_c WHERE id = :idCadeau');
	$reponse->execute(array('idCadeau' => bdd($idCadeau)));
	$donnees = $reponse->fetch();
	if($donnees['id_auteur'] == $_SESSION['id'] || $_SESSION['groupe']=='administrateur')
	{
		return true;
	}
	else
	{
		$this->error ='Vous n\'avez pas les droits pour modifier ce cadeau.';
		return false;
	}
    }
    
        public function existe($idCadeau) //est-ce que le cadeau existe bien ?
    {
    	$reponse = $this->connexion->prepare('SELECT * FROM evenements_cadeau_c WHERE id = :idCadeau');
		$reponse->execute(array('idCadeau' => $idCadeau));
		$donnees = $reponse->fetch(); 
		
		if($donnees)
		{
			return true;
		}
		else
		{
			$this->error = 'Le cadeau n\'existe pas.';
			return false;
			
		}
    }
        
    public function add(MECadeau $cadeau)
    {

        $reponse = $this->connexion->prepare('INSERT INTO evenements_cadeau_c(titre, prix, id_auteur, description, id_event) VALUES(:titre, :prix, :id_auteur, :description, :id_event)');
		$reponse->execute(array(
			'titre' => bdd($cadeau->getTitre()),
			'prix' => bdd($cadeau->getPrix()),
			'id_auteur' => bdd($cadeau->getAuteur()),
			'description' => bdd($cadeau->getDescription()),
			'id_event' => bdd($cadeau->getIdevent()),
			));
		
		$this->confirmation = 'L\'idée de cadeau a bien été envoyé<br />';
    }
    
    public function validerCadeau($idCad, $idEvent) //pour valider un cadeau
	{
		//On vérifie d'abord si le membre a bien le droit de valider le cadeau.
		$req=$this->connexion->prepare('SELECT id_auteur FROM evenements WHERE id=:id');
		$req->execute(array(
			'id' => bdd($idEvent),
		));
		
		$donnees = $req->fetch();
		
		if($donnees['id_auteur'] == $_SESSION['id'])
		{
			$reponse= $this->connexion->prepare('UPDATE evenements_cadeau_c SET valide = :valide WHERE id = :id_cadeau');	
			$reponse->execute(array(
			'valide' => 1,
			'id_cadeau' => bdd($idCad),
			));
			$this->confirmation = 'Le cadeau a été validé.';
		}
		else
		{
			$this->error = 'Vous n\'avez pas le droit de valider ce cadeau.';
		}
		
	}
	
	public function devaliderCadeau($idCad, $idEvent) //pour dévalider un cadeau
	{
		$req=$this->connexion->prepare('SELECT id_auteur FROM evenements WHERE id=:id');
		$req->execute(array(
			'id' => bdd($idEvent),
		));
		
		$donnees = $req->fetch();
		
		if($donnees['id_auteur'] == $_SESSION['id'])
		{
			$reponse= $this->connexion->prepare('UPDATE evenements_cadeau_c SET valide = :valide WHERE id = :id_cadeau');	
			$reponse->execute(array(
				'valide' => 0,
				'id_cadeau' => bdd($idCad),
			));
			$this->confirmation = 'Le cadeau n\'est plus validé.';
		}
		else
		{
			$this->error = 'Vous n\'avez pas le droit de dévalider ce cadeau.';
		}
	}
	
	public function delete($id) 
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
	}
	
	
	public function getListe($id, $type) //liste des cadeaux. $type=0 : non validés, $type=1 : validés
    {
		$this->cadeaux = null;
		$reponse = $this->connexion->prepare('SELECT titre, description, prix, id_auteur, id_event, id FROM evenements_cadeau_c WHERE id_event=:id_event AND valide =:valide ORDER BY id');
		$reponse->execute(array(
			'id_event' => $id,
			'valide' => $type,
			));	
			
		while ($donnees = $reponse->fetch()) 
		{
			$membre= $this->connexion->prepare('SELECT pseudo FROM membres WHERE id= :id_membre');
			$membre->execute(array(
				'id_membre' => $donnees['id_auteur'],
				));
			$pseudo = $membre->fetch();
			
				
			$this->cadeaux[] = new MECadeau(array(
				'id_auteur' => $pseudo['pseudo'],
				'titre' => $donnees['titre'],
				'description' => $donnees['description'],
				'id_event' => $donnees['id_event'],
				'prix' => $donnees['prix'],
				'id' => $donnees['id'],
				));
				
		}
			
		$reponse->closeCursor();	
			
		return $this->cadeaux;
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