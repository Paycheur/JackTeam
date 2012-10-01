<?php
class MECommentaireManage extends Connexion
{
	
	private $confirmation;
	private $error;
	
	private $commentaires = array();
	
	public function __construct()
    {
		parent::__construct(BD_NAME, BD_LOGIN, BD_PASSWORD);
    }
	
	
	public function save(MECommentaire $commentaire)
    {
    	if ($commentaire->isValide()=='ok')
    	{
        	$commentaire->isNew() ? $this->add($commentaire) : $this->modifier($commentaire);
        }
        else
        {
            $this->error=$commentaire->isValide();
        }
    }
        
    public function add(MECommentaire $commentaire)
    {

        $reponse = $this->connexion->prepare('INSERT INTO evenements_com(idEvent, idMembre, commentaire, date) VALUES(:idEvent, :idMembre, :commentaire, :date)');
		$reponse->execute(array(
			'idEvent' => bdd($commentaire->getIdevent()),
			'idMembre' => bdd($commentaire->getAuteur()),
			'commentaire' => bdd($commentaire->getCommentaire()),
			'date' =>  bdd($commentaire->getDate()),
			));
		
		$this->confirmation = 'Le commentaire a bien été envoyé<br />';
    }
        
    public function delete($idCommentaire)
    {
	if(ctype_digit($idCommentaire))
	{
		
		//$membre va permettre de verifier si le commentaire appartient bien au membre qui veut le supprimer.
		$membre = $this->connexion->prepare('SELECT idMembre FROM evenements_com WHERE id=:id');
		$membre->execute(array(
			'id' => bdd($idCommentaire),
		));
		
		$donnees = $membre->fetch();
		if($_SESSION['id'] == $donnees['id_membre'] OR $_SESSION['groupe']=='administrateur') //le test pour voir si le membre est le bon.
		{
			$reponse = $this->connexion->prepare('DELETE FROM evenements_com WHERE id = :id');
			$reponse->execute(array(
				'id' => (int) bdd($idCommentaire),
				));
			$reponse->closeCursor();
			
			$this->confirmation = 'Le commentaire a bien été supprimé';	
		}
                else
                {
                    $this->error='Vous ne pouvez pas supprimer ce commentaire.';
                }
	}
	else
	{
		$this->error = 'Le commentaire n\'existe pas';
	}
		
}
	
	public function getListe($idEvent)
    {
		$reponse_commentaire = $this->connexion->prepare('SELECT id, commentaire, idMembre, date FROM evenements_com WHERE idEvent= :idEvent');
		$reponse_commentaire->execute(array(
			'idEvent' => bdd($idEvent),
			));
	
		while($donnees_commentaire = $reponse_commentaire->fetch())
		{
			$reponse_membre = $this->connexion->prepare('SELECT pseudo FROM membres WHERE id= :idMembre'); //on récupère le membre selon l'id_membre
			$reponse_membre->execute(array(
				'idMembre' => $donnees_commentaire['idMembre'],
				));
			$donnees_membre = $reponse_membre->fetch();		
			
				
			$this->commentaires[] = new MECommentaire(array(
				'commentaire' => affichage($donnees_commentaire['commentaire']),
				'date' => remplacer_date($donnees_commentaire['date']),
				'auteur' => affichage($donnees_membre['pseudo']),
				'id' => $donnees_commentaire['id'],
				));
			$reponse_membre->closeCursor();	
		
		}  
		$reponse_commentaire->closeCursor();
		return $this->commentaires;
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