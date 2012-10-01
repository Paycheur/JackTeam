<?php
class MGCommentaireManage extends Connexion
{
	
	private $confirmation;
	private $error;
	
	private $commentaires = array();
	
	public function __construct()
    {
		parent::__construct(BD_NAME, BD_LOGIN, BD_PASSWORD);
    }
	
	
	public function save(MGCommentaire $commentaire)
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
        
    public function add(MGCommentaire $commentaire)
    {

        $reponse = $this->connexion->prepare('INSERT INTO galerie_photos_com(id_photo, id_membre, commentaire, date) VALUES(:id_photo, :id_membre, :commentaire, :date)');
		$reponse->execute(array(
			'id_photo' => bdd($commentaire->getIdphoto()),
			'id_membre' => bdd($commentaire->getAuteur()),
			'commentaire' => bdd($commentaire->getCommentaire()),
			'date' =>  bdd($commentaire->getDate()),
			));
		
		$this->confirmation = 'Le commentaire a bien été envoyé<br />';
    }
        
    public function delete($idCommentaire, $idMembre)
    {
	if(ctype_digit($idCommentaire))
	{
		
		//$membre va permettre de verifier si le commentaire appartient bien au membre qui veut le supprimer.
		$membre = $this->connexion->prepare('SELECT id_membre FROM galerie_photos_com WHERE id=:id');
		$membre->execute(array(
			'id' => bdd($idCommentaire),
		));
		
		$donnees = $membre->fetch();
		if($idMembre == $donnees['id_membre']) //le test pour voir si le membre est le bon.
		{
			$reponse = $this->connexion->prepare('DELETE FROM galerie_photos_com WHERE id = :id');
			$reponse->execute(array(
				'id' => (int) bdd($idCommentaire),
				));
			$reponse->closeCursor();
			
			$this->confirmation = 'Le commentaire a bien �t� supprim�';	
		}
	}
	else
	{
		$this->error = 'Le commentaire n\'existe pas';
	}
		
}
	
	public function getListe($id_photo)
    {
		$reponse_commentaire = $this->connexion->prepare('SELECT id, commentaire, id_membre, date FROM galerie_photos_com WHERE id_photo= :id');
		$reponse_commentaire->execute(array(
			'id' => bdd($id_photo),
			));
	
		while($donnees_commentaire = $reponse_commentaire->fetch())
		{
			$reponse_membre = $this->connexion->prepare('SELECT pseudo FROM membre WHERE id= :id_membre'); //on r�cup�re le membre selon l'id_membre
			$reponse_membre->execute(array(
				'id_membre' => $donnees_commentaire['id_membre'],
				));
			$donnees_membre = $reponse_membre->fetch();		
			
				
			$this->commentaires[] = new MGCommentaire(array(
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